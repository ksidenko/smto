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
    protected $output = array('separate' => array(), 'join' => array('machine' => array()));

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

        if ($this->machineId && is_int($this->machineId)) {
            $this->machineInfo = Machine::model()->findByPk($this->machineId);
        } else if (is_string($this->machineId)) {
            $this->machineReportType = $this->machineId;
            $this->machineId = null;
        }

        if ($this->operatorId) {
            $this->operatorInfo = Operator::model()->findByPk($this->operatorId);
        }

        if ($this->timetableId) {
            $this->timetableInfo = Timetable::model()->findByPk($this->timetableId);
        }

        if ( $this->dtStart && $this->dtEnd ) {
            ;
        } else if ( $this->dtStart && !$this->dtEnd ) {
            $this->dtEnd = date('Y.m.d H:i:s'); 
        } else if ( !$this->dtStart && $this->dtEnd) { // imposible case
            $this->dtStart = date('Y.m.d 00:00:00');
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
        
        $this->cr->addBetweenCondition('t.dt', $this->dtStart, $this->dtEnd);
        $this->secTotal = strtotime($this->dtEnd) - strtotime($this->dtStart);
        //print_r($this->getAttributes());die;
        //print_r($this->secTotal);die;

        if ($this->operatorId) {
            if ($this->operatorId > 0) {
                $this->cr->compare('t.operator_id', $this->operatorId);
            } else if ($this->operatorId == -1) {
                $this->cr->addCondition('t.operator_id is null');
            }
        }

        if ($this->timetableInfo) {
            $this->cr->addCondition('cast(t.dt as time) between :dtStartTimeTable and :dtEndTimeTable');
            $this->cr->params[':dtStartTimeTable'] = $this->timetableInfo->time_from;
            $this->cr->params[':dtEndTimeTable'] = $this->timetableInfo->time_to;
        }

        $this->cr->select += array( 't.machine_id', 't.state');
        $this->cr->select []= new CDbExpression("SUM(IFNULL(t.duration,10)) + 0  as sec_duration");


        if (is_numeric($this->machineId)) {
            $this->cr->compare('t.machine_id', $this->machineId);
        }

        $this->crNotWorking = clone($this->cr);
        
//        if ($this->machineReportType == 'join') {
//            //$this->cr->select [] = new CDbExpression('count(distinct t.machine_id) as cnt_machine');
//            //$this->cr->group []= 't.state';
//            //$this->crNotWorking->group []= 't.operator_last_fkey';
//        } else if ($this->machineReportType == 'separate') {
//            //$this->cr->select [] = new CDbExpression('1 as cnt_machine');
//        }

        $this->cr->group += array('t.machine_id', 't.state');
        $this->crNotWorking->group += array('t.machine_id', 't.operator_last_fkey');

        $this->crNotWorking->select [] = 't.operator_last_fkey';
	    $this->cr->addInCondition('t.state', array(MachineState::STATE_MACHINE_IDLE_RUN, MachineState::STATE_MACHINE_WORK));
	    $this->cr->addCondition('t.operator_last_fkey = 0');
	
        //$this->crNotWorking->addInCondition('t.state', array(MachineState::STATE_MACHINE_OFF, MachineState::STATE_MACHINE_ON));
        $this->crNotWorking->addNotInCondition('t.state', array(MachineState::STATE_MACHINE_IDLE_RUN, MachineState::STATE_MACHINE_WORK));
        // Может быть state == 0|1, но оператор ничего не нажимал!
        //$this->crNotWorking->addCondition('t.operator_last_fkey != 0');

        $this->machineId = $this->machineReportType;

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
            $currMachineInfo = &$this->output['separate'][$currMachineId];

            $machineState = MachineState::model()->findByPk($stateInfo['state']);
            $currMachineInfo['states_work'] [$machineState->code] = array(
                'code' => $machineState->code,
                'name' => $machineState->name,
                'color' => EventColor::getByCode('machine_' . $stateInfo['state']),
                //'color' => MachineState::getByColor($machineState->id),
                'sec_duration' => $stateInfo['sec_duration'],
            );
            //print_r($stateInfo->machine->getAttributes()); die;
        }

        $countMachines = count($processMachines);

        foreach($dataNotWork as $stateInfo) {
//            if ($this->machineReportType == 'join') {
//                $currMachineId = 0;
//            } else {
                $currMachineId = $stateInfo['machine_id'];
//            }

            $this->initMachineOutput ($currMachineId, $processMachines);
            $currMachineInfo = &$this->output['separate'][$currMachineId];

            if (empty($stateInfo['operator_last_fkey'])) { // Оператор не жал кнопки Fkey
                $machineState = MachineState::model()->findByPk($stateInfo['state']);
                $code = $machineState->code;
                $name = $machineState->name;
                $color = EventColor::getByCode('machine_' . $stateInfo['state']);
            } else {
                $fkeyState = MachineEvent::getRec($stateInfo['operator_last_fkey']);
                $code = $fkeyState->code;
                $name = $fkeyState->name;
                $color = $fkeyState->color;
            }

            $currMachineInfo['states_not_work'] [$code] = array(
                'code' => $code,
                'name' => $name,
                'color' => $color,
                'sec_duration' => $stateInfo['sec_duration'],
            );
        }

        // считаем для каждого станка не учтеное время
        foreach($this->output['separate'] as $currMachineId => &$currMachineInfo) {
            $secTotalProcess = 0;

            foreach($currMachineInfo['states_work'] as $stateInfo) {
                $secTotalProcess += $stateInfo['sec_duration'];
            }
            foreach($currMachineInfo['states_not_work'] as $stateInfo) {
                $secTotalProcess += $stateInfo['sec_duration'];
            }

            $timeIgnored = max(0, $this->secTotal  - $secTotalProcess);

            if ( $timeIgnored > 10 ) {
                $code = 'time_ignored';
                $currMachineInfo['states_not_work'] [$code]= array(
                    'code' => $code,
                    'name' => 'Не учтеное время',
                    'color' => EventColor::getByCode('time_ignored'),
                    'sec_duration' => $timeIgnored,
                );
            }
        }

        // считаем объединенную информацию по всем станкам
        $joinMachineInfo = array(
            'states_work' => array(),
            'states_not_work' => array(),
        );

        foreach ($this->output['separate'] as $currMachineId => &$currMachineInfo) {

            foreach ($currMachineInfo['states_work'] as $code => $stateInfo) {
                if (!isset($joinMachineInfo['states_work'][$code])) {
                    $joinMachineInfo['states_work'][$code] = $stateInfo;
                } else {
                    $joinMachineInfo['states_work'][$code]['sec_duration'] += $stateInfo['sec_duration'];
                }
            }

            foreach($currMachineInfo['states_not_work'] as $code => $stateInfo) {
                if (!isset($joinMachineInfo['states_not_work'][$code])) {
                    $joinMachineInfo['states_not_work'][$code] = $stateInfo;
                } else {
                    $joinMachineInfo['states_not_work'][$code]['sec_duration'] += $stateInfo['sec_duration'];
                }
            }
        }
        $this->output['join']['machine'] = $joinMachineInfo;

        //echo '<pre>' . print_r($this->output, true) . '</pre>';die();

        //echo $secTotalProcess . ' ' . $this->secTotal; die;
        
        //var_export($this->output); die();

        return $this->output;
    }

    private function initMachineOutput ($currMachineId, &$processMachines){
        if (!in_array($currMachineId, $processMachines)) {
            $processMachines []= $currMachineId;
            $this->output['separate'][$currMachineId] = array(
                'machine' => array(),
                'operator' => array(),
                'states_work' => array(),
                'states_not_work' => array(),
            );

            $machineInfo = Machine::model()->findByPk($currMachineId);
            if ($machineInfo) {
                $this->output['separate'][$currMachineId]['machine'] = array(
                    'id' => $machineInfo->id,
                    'name' => $machineInfo->name,
                    'work_type' => $machineInfo->work_type,
                );
            }

            if ( $this->operatorInfo ) {
                $this->output['separate'] [$currMachineId]['operator'] = array(
                    'name' => $this->operatorInfo->full_name
                );
            }
        }
    }
}