<?php
class ReportConstructor extends ReportSearchForm {

    public $cr = null;
    public $crNotWorking = null;

    public $max;
    public $machineInfo = null;
    public $machineStateInfo = null;
    public $operatorInfo = null;
    public $timetableInfo = null;
    public $secTotal = null;
    protected $output = array(
        'machines' => array(),
        'reports' => array(
            'main' => array(),
            'work' => array(),
            'not_work' => array(),
        )
    );

    public function __construct() {
        $this->cr = new CDbCriteria();
        $this->cr->select = array();
        $this->cr->group = array();

        $this->crNotWorking = new CDbCriteria();
        $this->crNotWorking->select = array();
        $this->crNotWorking->group = array();

        $this->max = Machine::$MAX_DA;
        parent::__construct();
    }

    public function _processParams() {

        if ($this->machineId && is_numeric($this->machineId)) {
            $this->machineReportType = 'separate';
            $this->machineInfo = Machine::model()->cache(600)->findByPk($this->machineId);
        } else if (is_string($this->machineId)) {
            $this->machineReportType = $this->machineId;
        }

        if ($this->operatorId) {
            $this->operatorInfo = Operator::model()->cache(60)->findByPk($this->operatorId);
        }

        if ($this->timetableId) {
            $this->timetableInfo = Timetable::model()->cache(600)->findByPk($this->timetableId);
        }

        if ($this->dtEnd) {
            $curr = strtotime( date('d.m.Y H:i:s') );
            $dtEnd = strtotime( $this->dtEnd );
            if ($dtEnd > $curr) { // its future
                $this->dtEnd = date('d.m.Y H:i:s');
            }
        }

        if ( $this->dtStart && $this->dtEnd ) {
            $this->dtStart = date('Y.m.d H:i:s', strtotime($this->dtStart));
            $this->dtEnd = date('Y.m.d H:i:s', strtotime($this->dtEnd));
        } else if ( $this->dtStart && !$this->dtEnd ) {
            $this->dtStart = date('Y.m.d H:i:s', strtotime($this->dtStart));
            $this->dtEnd = date('Y.m.d H:i:s');
        } else if ( !$this->dtStart && $this->dtEnd) { // impossible case
            $this->dtStart = date('Y.m.d 00:00:00');
            $this->dtEnd = date('Y.m.d H:i:s', strtotime($this->dtEnd));
        } else if ( !$this->dtStart && !$this->dtEnd ){
            $this->dtStart = date('Y.m.d 00:00:00');
            $this->dtEnd = date('Y.m.d H:i:s');
        }

        if ($this->dtStart) {
            $this->dtStart = str_replace('.', '-', $this->dtStart);
        }
        if ($this->dtEnd) {
            $this->dtEnd = str_replace('.', '-', $this->dtEnd);
        }

        //$this->cr->addBetweenCondition('t.dt', $this->dtStart, $this->dtEnd);

        $this->cr->addCondition( new CDbExpression("'{$this->dtStart}' < t.dt") );
        //$this->cr->addCondition( new CDbExpression("UNIX_TIMESTAMP('{$this->dtEnd}') > UNIX_TIMESTAMP(t.dt) - t.duration") );
        $this->cr->addCondition( new CDbExpression("'{$this->dtEnd}' > DATE_SUB(t.dt, INTERVAL t.duration SECOND)") );

        $this->secTotal = strtotime($this->dtEnd) - strtotime($this->dtStart);
        //print_r($this->getAttributes());die;
        //print_r($this->secTotal);die;

        if ($this->operatorId) {
            if (is_numeric($this->operatorId) && $this->operatorId > 0) {
                $this->cr->compare('t.operator_id', $this->operatorId);
            } else if ($this->operatorId == 'by_pass') {
                $this->cr->addCondition('t.operator_id > 0');
            } else if ($this->operatorId == 'not_register') {
                $this->cr->addCondition('t.operator_id is null');
            }
        }

        if ($this->timetableInfo) {
            $this->cr->addCondition( new CDbExpression("'{$this->timetableInfo->time_from}' < t.dt") );
            $this->cr->addCondition( new CDbExpression("'{$this->timetableInfo->time_to}' > DATE_SUB(t.dt, INTERVAL t.duration SECOND)") );
        }

        $this->cr->select += array( 't.machine_id', 't.state');


        if (is_numeric($this->machineId)) {
            $this->cr->compare('t.machine_id', $this->machineId);
        }

        $this->cr->select []= new CDbExpression("SUM(
            UNIX_TIMESTAMP(
            IF(
                '{$this->dtEnd}' < t.dt,
                '{$this->dtEnd}',
                t.dt
            ))
            -
            UNIX_TIMESTAMP(
            IF(
                '{$this->dtStart}' < DATE_SUB(t.dt, INTERVAL t.duration SECOND),
                DATE_SUB(t.dt, INTERVAL t.duration SECOND),
                '{$this->dtStart}'
            ))
        ) + 0  as sec_duration");

        $this->crNotWorking = clone($this->cr);

//        if ($this->machineReportType == 'join') {
//            //$this->cr->select [] = new CDbExpression('count(distinct t.machine_id) as cnt_machine');
//            //$this->cr->group []= 't.state';
//            //$this->crNotWorking->group []= 't.operator_last_fkey';
//        } else if ($this->machineReportType == 'machines') {
//            //$this->cr->select [] = new CDbExpression('1 as cnt_machine');
//        }

        $this->cr->group += array('t.machine_id', 't.state');
        $this->crNotWorking->group += array('t.machine_id', 't.state', 't.operator_last_fkey');

        $this->crNotWorking->select [] = 't.operator_last_fkey';
        $this->cr->addInCondition('t.state', array(MachineState::STATE_MACHINE_IDLE_RUN, MachineState::STATE_MACHINE_WORK));
        $this->cr->addCondition('t.operator_last_fkey = 0');

        // плохой вариант ограничения!
        //$this->crNotWorking->addInCondition('t.state', array(MachineState::STATE_MACHINE_OFF));

        // Правильный вариант ограничения!
        $this->crNotWorking->addNotInCondition('t.state', array(MachineState::STATE_MACHINE_IDLE_RUN, MachineState::STATE_MACHINE_WORK));

        // Может быть state == 0|1, но оператор ничего не нажимал!
        //$this->crNotWorking->addCondition('t.operator_last_fkey != 0');

        $this->cr->group = implode(', ', $this->cr->group);
        $this->crNotWorking->group = implode(', ', $this->crNotWorking->group);

        //print_r($this);die;
    }

    public function getData() {

        $this->_processParams();
        //print_r($this->getAttributes());die;

        $dataWork = MachineData::model()->findAll($this->cr);
        //print_r($dataWork);die;

        $dataNotWork = MachineData::model()->findAll($this->crNotWorking);
        //print_r($dataNotWork);die;

        $processMachines = array();
        foreach($dataWork as $stateInfo) { // loop througth machine states

//            if ( in_array($stateInfo['status'], array(MachineState::STATE_MACHINE_IDLE_RUN, MachineState::STATE_MACHINE_WORK)) ) {
//                $stateCode = 'state_idle';
//            } else {
//                $stateCode = 'state_work';
//            }

//            if ($this->machineReportType == 'join') {
//                $currMachineId = 0;
//            } else {
            $currMachineId = $stateInfo['machine_id'];
//            }

            $this->initMachineOutput ($currMachineId, $processMachines);
            $currMachineInfo = &$this->output['machines'][$currMachineId];

            $machineState = MachineState::model()->cache(600)->findByPk($stateInfo['state']);
            $currMachineInfo['states'] [$machineState->code] = array(
                'code' => $machineState->code,
                'name' => $machineState->name,
                'color' => EventColor::getColorByCode('machine_' . $stateInfo['state']),
                'sec_duration' => $stateInfo['sec_duration'],
            );
        }

        $countMachines = count($processMachines);

        foreach($dataNotWork as $stateInfo) {
            $currMachineId = $stateInfo['machine_id'];

            $this->initMachineOutput ($currMachineId, $processMachines);
            $currMachineInfo = &$this->output['machines'][$currMachineId];

            if (empty($stateInfo['operator_last_fkey'])) { // Оператор не жал кнопки Fkey
                $machineState = MachineState::model()->cache(600)->findByPk($stateInfo['state']);
                $code = $machineState->code;
                $name = $machineState->name;
                $color = EventColor::getColorByCode('machine_' . $stateInfo['state']);
            } else {
                $machineInfo = Machine::model()->cache(600)->findByPk($currMachineId);
                $fkey = $machineInfo->cache(600)->fkey(array('select' => 'machine_event_id', 'condition' => 'number = ' . $stateInfo['operator_last_fkey']));
                $fkeyState = $fkey[0]->cache(600)->machine_event;
                //$fkeyState = MachineEvent::getRec($stateInfo['operator_last_fkey']);
                $code = $fkeyState['code'];
                $name = $fkeyState['name'];
                $color = '#' . ltrim($fkeyState['color'], '#');
    	        if (empty($code)) {
                    $code ='event_undefined';
                    $name = 'Событие не определено (' . $stateInfo['operator_last_fkey'] . ')' ;
                    $color = '#000000';
                }
            }

            $currMachineInfo['states'] [$code] = array(
                'code' => $code,
                'name' => $name,
                'color' => $color,
                'sec_duration' => $stateInfo['sec_duration'],
            );
        }

        // считаем для каждого станка не учтеное время
        foreach($this->output['machines'] as $currMachineId => &$currMachineInfo) {
            $secTotalProcess = 0;

            foreach($currMachineInfo['states'] as $stateInfo) {
                $secTotalProcess += $stateInfo['sec_duration'];
            }

            $timeIgnored = max(0, $this->secTotal  - $secTotalProcess);

            if ( $timeIgnored > 10 ) {
                $code = 'time_ignored';
                $currMachineInfo['states'] [$code]= array(
                    'code' => $code,
                    'name' => 'Нет данных',
                    'color' => EventColor::getColorByCode('time_ignored'),
                    'sec_duration' => $timeIgnored,
                );
            }
        }

        //echo '<pre>' . print_r($this->output, true) . '</pre>';die();

        // считаем объединенную информацию по всем станкам
        if ($this->output['machines']) {

            //-----------------------------------------------------------------------------------
            //Create data for report "main"
            //-----------------------------------------------------------------------------------
            $mainReportInfo = array( 'separate' => array(), 'join' => array() );

            foreach ($this->output['machines'] as $currMachineId => &$currMachineInfo) {
                foreach ($currMachineInfo['states'] as $c => $stateInfo) {

                    $code = 'not_work';
                    if (in_array($c, array('idle_run', 'work'))) {
                        $code = 'work';
                    }

                    if ($code == 'work') {
                        $machineState = MachineState::model()->cache(600)->findByPk( MachineState::STATE_MACHINE_WORK );
                        $code = $machineState->code;
                        //$name = $machineState->name;
                        $name = 'Работа';
                            //$color = EventColor::getColorByCode('machine_' . $stateInfo['state']);
                        $color = '#2f940d'; //todo
                    } else {
                        $machineState = MachineState::model()->cache(600)->findByPk( MachineState::STATE_MACHINE_OFF );
                        $code = $machineState->code;
                        //$name = $machineState->name;
                        $name = 'Простой';
                        //$color = EventColor::getColorByCode('machine_' . $stateInfo['state']);
                        $color = '#e8051b'; //todo
                    }

                    $sec_duration = 0;
                    if (isset($mainReportInfo['separate'][$currMachineId][$code])) {
                        $sec_duration = $mainReportInfo['separate'][$currMachineId][$code]['sec_duration'];
                    }
                    $sec_duration += $stateInfo['sec_duration'];

                    $data = array(
                        'code' => $code,
                        'name' => $name,
                        'color' => $color,
                        'sec_duration' => $sec_duration,
                    );

                    $mainReportInfo['separate'][$currMachineId][$code] = $data;
                }
            }

            foreach($mainReportInfo['separate'] as $currMachineId => &$currMachineInfo) {
                foreach ($currMachineInfo as $code => $stateInfo) {
                    if (isset($mainReportInfo['join'][1][$code])) {
                        $mainReportInfo['join'][1][$code]['sec_duration'] += $stateInfo['sec_duration'];
                    } else {
                        $mainReportInfo['join'][1][$code] = $stateInfo;
                    }
                }
            }

            $this->output['reports']['main'] = $mainReportInfo;

            //-----------------------------------------------------------------------------------
            //Create data for report "work"
            //-----------------------------------------------------------------------------------
            $workReportInfo = array( 'separate' => array(), 'join' => array() );

            foreach ($this->output['machines'] as $currMachineId => &$currMachineInfo) {
                foreach ($currMachineInfo['states'] as $c => $stateInfo) {

                    if (!in_array($c, array('idle_run', 'work'))) {
                         continue;
                    }

                    if (!isset($workReportInfo[$c])) {
                        $workReportInfo['separate'][$currMachineId][$c] = $stateInfo;
                    } else {
                        $workReportInfo['separate'][$currMachineId][$c]['sec_duration'] += $stateInfo['sec_duration'];
                    }
                }
            }

            foreach($workReportInfo['separate'] as $currMachineId => &$currMachineInfo) {
                foreach ($currMachineInfo as $code => $stateInfo) {
                    if (isset($workReportInfo['join'][1][$code])) {
                        $workReportInfo['join'][1][$code]['sec_duration'] += $stateInfo['sec_duration'];
                    } else {
                        $workReportInfo['join'][1][$code] = $stateInfo;
                    }
                }
            }

            $this->output['reports']['work'] = $workReportInfo;

            //-----------------------------------------------------------------------------------
            //Create data for report "not work"
            //-----------------------------------------------------------------------------------
            $notWorkReportInfo = array( 'separate' => array(), 'join' => array() );

            foreach ($this->output['machines'] as $currMachineId => &$currMachineInfo) {
                foreach ($currMachineInfo['states'] as $c => $stateInfo) {

                    if (in_array($c, array('idle_run', 'work'))) {
                        $machineState = MachineState::model()->cache(600)->findByPk( MachineState::STATE_MACHINE_WORK );
                        $code = 'work';
                        $c = 'work';
                        $name = $machineState->name;
                        //$color = EventColor::getColorByCode('machine_' . $stateInfo['state']);
                        $color = '#2f940d'; //todo
                    } else {
                        $code = $stateInfo['code'];
                        $name = $stateInfo['name'];
                        $color = $stateInfo['color'];

                        if ($c == 'on') {
                            $name = 'Необоснованный простой';    // вместо "включен"
                        }
                    }

                    if (!isset($notWorkReportInfo['separate'][$currMachineId][$c])) {
                        $data = array(
                            'code' => $code,
                            'name' => $name,
                            'color' => $color,
                            'sec_duration' => $stateInfo['sec_duration'],
                        );
                        $notWorkReportInfo['separate'][$currMachineId][$c] = $data;
                    } else {
                        $notWorkReportInfo['separate'][$currMachineId][$c]['sec_duration'] += $stateInfo['sec_duration'];
                    }
                }
            }

            foreach($notWorkReportInfo['separate'] as $currMachineId => &$currMachineInfo) {
                foreach ($currMachineInfo as $code => $stateInfo) {
                    if (isset($notWorkReportInfo['join'][1][$code])) {
                        $notWorkReportInfo['join'][1][$code]['sec_duration'] += $stateInfo['sec_duration'];
                    } else {
                        $notWorkReportInfo['join'][1][$code] = $stateInfo;
                    }
                }
            }

            $this->output['reports']['not_work'] = $notWorkReportInfo;
        }
        //echo '<pre>' . print_r($this->output, true) . '</pre>';die();

        //echo $secTotalProcess . ' ' . $this->secTotal; die;

        //var_export($this->output); die();

        return $this->output;
    }

    private function initMachineOutput ($currMachineId, &$processMachines){
        if (!in_array($currMachineId, $processMachines)) {
            $processMachines []= $currMachineId;
            $this->output['machines'][$currMachineId] = array(
                'machine' => array(),
                'operator' => array(),
                'states' => array(),
            );

            $machineInfo = Machine::model()->cache(600)->findByPk($currMachineId);
            if ($machineInfo) {
                $this->output['machines'][$currMachineId]['machine'] = array(
                    'id' => $machineInfo->id,
                    'name' => $machineInfo->full_name,
                    'work_type' => $machineInfo->work_type,
                );
            }

            if ( $this->operatorInfo ) {
                $this->output['machines'] [$currMachineId]['operator'] = array(
                    'name' => $this->operatorInfo->short_name
                );
            }
        }
    }
}
