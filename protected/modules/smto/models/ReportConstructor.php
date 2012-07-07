B<?php
class ReportConstructor extends ReportSearchForm {

    public $cr = null;
    public $crNotWorking = null;

    public $max;
    public $machineInfo = null;
    public $machineStateInfo = null;
    public $operatorInfo = null;
    public $timetableInfo = null;
    public $secTotal = null;
    protected $output = array();

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
        $this->secTotal = strtotime($this->dtEnd) - strtotime($this->dtStart) - 3;
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
        $this->cr->select []= new CDbExpression("SUM(IFNULL(t.duration,0)) + 0  as sec_duration");
        $this->cr->select [] = new CDbExpression('count(distinct t.machine_id) as cnt_machine');

        if ($this->machineId) {
            $this->cr->compare('t.machine_id', $this->machineId);
        }

        $this->crNotWorking = clone($this->cr);
        
        if ($this->machineReportType == 'join') {
            $this->cr->group []= 't.state';
            $this->crNotWorking->group []= 't.fkey_last';
        } else if ($this->machineReportType == 'separate') {
            $this->cr->group += array('t.machine_id', 't.state');
            $this->crNotWorking->group += array('t.machine_id', 't.fkey_last');
        }

        $this->crNotWorking->select [] = 't.fkey_last';
	//$this->cr->addInCondition('t.state', array(MachineState::STATE_MACHINE_IDLE_RUN, MachineState::STATE_MACHINE_WORK));
	//$this->cr->addCondition('t.fkey_last = 0');
	
        //$this->crNotWorking->addInCondition('t.state', array(MachineState::STATE_MACHINE_OFF, MachineState::STATE_MACHINE_ON));

        $this->machineId = $this->machineReportType;

        $this->cr->group = implode(', ', $this->cr->group);
        $this->crNotWorking->group = implode(', ', $this->crNotWorking->group);
        $this->crNotWorking->addCondition('t.fkey_last > 0');
       // print_r($this);die;
    }

    public function getData() {

        $this->_processParams();
        //print_r($this->getAttributes());die;
        
        $data = MachineData::model()->findAll($this->cr);
        $dataNotWorking = MachineData::model()->findAll($this->crNotWorking);
        //print_r($dataNotWorking);die;

        $maxCountMachines = 1;
        $processMachines = array();
        foreach($data as $row) { // loop througth machine states

            if ($this->machineReportType == 'join') {
                $currMachineId = 0;
            } else {
                $currMachineId = $row['machine_id'];
            }
            if (!in_array($currMachineId, $processMachines)) {
                $processMachines []= $currMachineId;
                $this->output [$currMachineId] = array(
                    'machine' => null,
                    'operator' => null,
                    'states' => array(),
                    'states_not_working' => array(),
                );

                $machineInfo = Machine::model()->findByPk($currMachineId);
                if ($machineInfo) {
                    $this->output [$currMachineId]['machine'] = array(
                        'id' => $machineInfo->id,
                        'name' => $machineInfo->name,
                        'work_type' => $machineInfo->work_type,
                    );
                }

                if ( $this->operatorInfo ) {
                    $this->output [$currMachineId]['operator'] = array(
                        'name' => $this->operatorInfo->full_name
                    );
                }
            }

            $machineState = MachineState::model()->findByPk($row['state']);
            $this->output [$currMachineId]['states'] []= array(
                'code' => $machineState->code,
                'name' => $machineState->name,
                'color' => EventColor::getByCode('machine_' . $row['state']),
                //'color' => MachineState::getByColor($machineState->id),
                'sec_duration' => $row['sec_duration'],
            );
            $maxCountMachines = max($maxCountMachines, $row['cnt_machine']);
            //print_r($row->machine->getAttributes()); die;
        }

        $this->secTotal = $maxCountMachines * $this->secTotal;
        
        foreach($this->output as $currMachineId => &$currMachineInfo) {
            $secTotalProcess = 0;

            foreach($currMachineInfo['states'] as $state) {
                $secTotalProcess += $state['sec_duration'];
            }

	    
            $currMachineInfo['states'] []= array(
                'code' => 'time_ignored',
                'name' => 'Не учтеное время',
                'color' => EventColor::getByCode('time_ignored'),
                'sec_duration' => $this->secTotal  - $secTotalProcess,
            );
        }

        foreach($dataNotWorking as $row) {
            if ($this->machineReportType == 'join') {
                $currMachineId = 0;
            } else {
                $currMachineId = $row['machine_id'];
            }
            
            $fkeyState = MachineEvent::getRec($row['fkey_last']);

            if (!empty($fkeyState)) {
                //print_r($fkeyState); die;
                $this->output [$currMachineId]['states_not_working'] []= array(
                    'code' => $fkeyState->code,
                    'name' => $fkeyState->name,
                    'color' => $fkeyState->color,
                    'sec_duration' => $row['sec_duration'],
                );
            }
        }
        
        //echo '<pre>' . print_r($this->output, true) . '</pre>';die();

        //echo $secTotalProcess . ' ' . $this->secTotal; die;
        
        //var_export($this->output); die();

        return $this->output;
    }
}