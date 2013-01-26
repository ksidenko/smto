<?php
class ReportLinearConstructor extends ReportSearchForm {

    /*
    * Начало, конец временного диапазона отчета
    */
    public $dtStart, $dtEnd;

    public $cr = null;

    public $machineInfo = null;

    public $secTotal;

    public $maxDeltaDt = 20; // in seconds

    protected $output = array(
        'states' => array(
            'machine_state' => array(),
            'operator_last_fkey' => array(),
            'operator' => array(),
        ),
    );

    public function __construct() {
        $this->cr = new CDbCriteria();
        $this->cr->select = array();
        $this->cr->group = array();

        //parent::__construct();
    }

    public function _processParams() {

        $this->machineInfo = Machine::model()->cache(60)->findByPk($this->machineId);

        if ($this->operatorId) {
            $this->operatorInfo = Operator::model()->cache(600)->findByPk($this->operatorId);
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
        $this->cr->addCondition( new CDbExpression("'{$this->dtEnd}' > DATE_SUB(t.dt, INTERVAL t.duration SECOND)") );
        $this->secTotal = strtotime($this->dtEnd) - strtotime($this->dtStart);
        //print_r($this->getAttributes());die;
        //print_r($this->secTotal);die;

        $this->cr->select += array( 't.dt', 't.duration',  't.machine_id', 't.state', 't.operator_last_fkey', 't.operator_id',);

        $daAvg = 'da_avg' . strval(intval($this->machineInfo->main_detector_analog) + 1);
        $this->cr->select []= new CDbExpression( 't.' . $daAvg . ' as da_avg');


        $this->cr->compare('t.machine_id', $this->machineId);
        //$this->cr->addCondition('t.state>0');

        //$this->cr->group += array('t.machine_id', 't.state');
        $this->cr->group = implode(', ', $this->cr->group);
        $this->cr->order = 't.dt';

        //print_r($this);die;
    }

    public function getData() {

        $this->_processParams();
        //print_r($this->getAttributes());die;

        $dataMachineInfo = MachineData::model()->findAll($this->cr);
        $this->dtStart = date('d.m.Y H:i:s', strtotime($this->dtStart));
        $this->dtEnd = date('d.m.Y H:i:s', strtotime($this->dtEnd));

        //print_r(count($dataMachineInfo ));die;

        $lastTimeValues = array();
        foreach ($dataMachineInfo as $machineDataRow) {



            //-----------------------------------------------------------------------------------
            //Prepare chart data for machine states
            //-----------------------------------------------------------------------------------

            $machineStateCode = $machineDataRow['state'];

            if ($machineStateCode == MachineState::STATE_MACHINE_IDLE_RUN) {
                $machineStateCode = MachineState::STATE_MACHINE_WORK;
            }

            $arrStates = array($machineStateCode);
            if ($machineStateCode == MachineState::STATE_MACHINE_WORK) {
                $arrStates []= MachineState::STATE_MACHINE_ON;
            }

            foreach ($arrStates as $machineStateCode) {

                if ($machineStateCode == MachineState::STATE_MACHINE_OFF) {
                    continue;
                }
	    

                //-----------------------------------------------------------------------------------
                //Prepare chart data for machine states
                //-----------------------------------------------------------------------------------

                $hasBreak = false;
                // process break for machine states
                if ( isset($lastTimeValues['machine_state'][$machineStateCode]) ) {
                    $dtPrev = $lastTimeValues['machine_state'][$machineStateCode];
                    $dt = $machineDataRow['dt'];

                    if (strtotime($dt) - $machineDataRow['duration'] - strtotime($dtPrev) > $this->maxDeltaDt) {
                        $this->output['states']['machine_state'][$machineStateCode]['data'] [] = array();
		                $hasBreak = true;
                    }
                } else {
            	    $hasBreak = true;
                }
                
                // save last dt value for current machine state
                $lastTimeValues['machine_state'][$machineStateCode] = $machineDataRow['dt'];

		        $min =  max(strtotime($machineDataRow['dt']) - $machineDataRow['duration'], strtotime($this->dtStart));
	            //if ($hasBreak) {
                $this->output['states']['machine_state'][$machineStateCode]['data'] []= array(
                    $this->toJsTimestamp( $min ),
                    (int)$machineStateCode
                );
                //       }

                $max = min(strtotime($machineDataRow['dt']), strtotime($this->dtEnd));
                $this->output['states']['machine_state'][$machineStateCode]['data'] []= array(
                    $this->toJsTimestamp( $max-4 ),
                    (int)$machineStateCode
                );
                
                $this->output['states']['machine_state'][$machineStateCode]['data'] [] = array();                
                $this->output['states']['machine_state'][$machineStateCode]['data'] []= array(
                    $this->toJsTimestamp( $max-5 ),
                    (int)$machineStateCode
                );
                
                $this->output['states']['machine_state'][$machineStateCode]['data'] []= array(
                    $this->toJsTimestamp( $max ),
                    (int)$machineStateCode
                );

                $machineState = MachineState::getRec($machineStateCode);
                $data_ = array(
                    'code' => $machineState->code,
                    'name' => $machineState->name,
                    'color' => EventColor::getColorByCode('machine_' . $machineStateCode),
                );

                $this->output['states']['machine_state'][$machineStateCode]['info'] = $data_;
            }



            //-----------------------------------------------------------------------------------
            //Prepare chart data for machine analog values
            //-----------------------------------------------------------------------------------

	        $hasBreak = false;
            // process break for machine states
            if ( isset($lastTimeValues['machine_da_value']) ) {
                $dtPrev = $lastTimeValues['machine_da_value'];
                $dt = $machineDataRow['dt'];
                if (strtotime($dt) - $machineDataRow['duration'] - strtotime($dtPrev) > $this->maxDeltaDt) {
                    $this->output['machine_da_value']['data'] []= array();
		            $hasBreak = true;
                }
            }
            // save last dt value for current machine state
            $lastTimeValues['machine_da_value'] = $machineDataRow['dt'];

            $this->output['machine_da_value']['data'] []= array(
                $this->toJsTimestamp( max(strtotime($machineDataRow['dt']) - $machineDataRow['duration'], strtotime($this->dtStart)) ),
                (int)$machineDataRow['da_avg']
            );
            $this->output['machine_da_value']['data'] []= array(
                $this->toJsTimestamp( min(strtotime($machineDataRow['dt']), strtotime($this->dtEnd)) ),
                (int)$machineDataRow['da_avg']
            );
            $data_ = array(
                'code' => '',
                'name' => 'Нагрузка da_avg',
                'color' => EventColor::getColorByCode('machine_' . MachineState::STATE_MACHINE_WORK),
            );

            $belowValue = array();
            foreach($this->machineInfo->cache(600)->config as $machineConfig) {
                if ( $machineConfig->condition_number == (12 + $this->machineInfo->main_detector_analog) &&
                    ($machineConfig->machine_state_id == MachineState::STATE_MACHINE_ON ||
                     $machineConfig->machine_state_id == MachineState::STATE_MACHINE_IDLE_RUN ||
                     $machineConfig->machine_state_id == MachineState::STATE_MACHINE_WORK )
                ) {
                    $belowValue[$machineConfig->machine_state_id] = $machineConfig->value;
                }
            }
            if ($belowValue) {
//                $data_ ['threshold'] = array(
//                    'below' => $belowValue,
//                    'color' => EventColor::getColorByCode('machine_' . MachineState::STATE_MACHINE_IDLE_RUN)
//                );
                $data_['constraints'] = array(
                    //array( 'threshold' => $belowValue[MachineState::STATE_MACHINE_ON], 'color' => EventColor::getColorByCode('machine_' . MachineState::STATE_MACHINE_OFF), 'evaluate' => new CJavaScriptExpression('function(y, threshold){ return y < threshold; }') ),
                    array( 'threshold' => $belowValue[MachineState::STATE_MACHINE_IDLE_RUN], 'color' => EventColor::getColorByCode('machine_' . MachineState::STATE_MACHINE_ON), 'evaluate' => new CJavaScriptExpression('function(y, threshold){ return y <= threshold; }') ),
                    array( 'threshold' => $belowValue[MachineState::STATE_MACHINE_IDLE_RUN], 'color' => EventColor::getColorByCode('machine_' . MachineState::STATE_MACHINE_IDLE_RUN), 'evaluate' => new CJavaScriptExpression('function(y, threshold){ return y >= threshold;; }') ),
                    array( 'threshold' => $belowValue[MachineState::STATE_MACHINE_WORK], 'color' => EventColor::getColorByCode('machine_' . MachineState::STATE_MACHINE_WORK), 'evaluate' => new CJavaScriptExpression('function(y, threshold){ return y > threshold;; }') ),
                );
            }

            $this->output['machine_da_value']['info'] = $data_;


            //-----------------------------------------------------------------------------------
            //Prepare chart data for operator last keys
            //-----------------------------------------------------------------------------------

            if ( $machineDataRow['operator_last_fkey'] > 0 ) {

                $operatorLastKey = $machineDataRow['operator_last_fkey'];

                $fkey = $this->machineInfo->cache(600)->fkey(array('condition' => 'number = ' . $machineDataRow['operator_last_fkey']));
                $fkeyState = $fkey[0]->cache(600)->machine_event;

                $fkeyState->id = empty($fkeyState->id) ? 0 : $fkeyState->id;
                $operatorLastKey = $fkeyState->id + MachineEvent::$idOffset;

                // process break for machine states
                if ( isset($lastTimeValues['operator_last_fkey'][$operatorLastKey]) ) {
                    $dtPrev = $lastTimeValues['operator_last_fkey'][$operatorLastKey];
                    $dt = $machineDataRow['dt'];
                    if (strtotime($dt) - $machineDataRow['duration'] - strtotime($dtPrev) > $this->maxDeltaDt) {
                        $this->output['states']['operator_last_fkey'][$operatorLastKey]['data'] [] = array();
                    }
                }
                // save last dt value for current machine state
                $lastTimeValues['operator_last_fkey'][$operatorLastKey] = $machineDataRow['dt'];

                $this->output['states']['operator_last_fkey'][$operatorLastKey]['data'] []= array(
                    $this->toJsTimestamp( max(strtotime($machineDataRow['dt']) - $machineDataRow['duration'], strtotime($this->dtStart)) ),
                    (int)$operatorLastKey
                );
                $this->output['states']['operator_last_fkey'][$operatorLastKey]['data'] []= array(
                    $this->toJsTimestamp( min(strtotime($machineDataRow['dt']), strtotime($this->dtEnd)) ),
                    (int)$operatorLastKey
                );

                //$fkeyState = MachineEvent::getRec($machineDataRow['operator_last_fkey']);
                if (!empty($fkeyState->id)) {
                    $data_ = array(
                        'code' => $fkeyState['code'],
                        'name' => $fkeyState['name'],
                        'color' => '#' . ltrim($fkeyState['color'], '#'),
                    );
                } else {
                    $data_ = array(
                        'code' => 'event_undefined',
                        'name' => 'Событие не определено (' . $machineDataRow['operator_last_fkey'] . ')',
                        'color' => '#000000',
                    );
                }

                $this->output['states']['operator_last_fkey'][$operatorLastKey]['info'] = $data_;

            }


            //-----------------------------------------------------------------------------------
            //Prepare chart data for operators
            //-----------------------------------------------------------------------------------

            if ($machineDataRow['operator_id'] > 0) {

                $operatorId = $machineDataRow['operator_id'];
                $operatorId += Operator::$idOffset;

                // process break for machine states
                if ( isset($lastTimeValues['operator'][$operatorId]) ) {
                    $dtPrev = $lastTimeValues['operator'][$operatorId];
                    $dt = $machineDataRow['dt'];
                    if (strtotime($dt) - $machineDataRow['duration'] - strtotime($dtPrev) > $this->maxDeltaDt) {
                        $this->output['states']['operator'][$operatorId]['data'] [] = array();
                    }
                }
                // save last dt value for current machine state
                $lastTimeValues['operator'][$operatorId] = $machineDataRow['dt'];

                $this->output['states']['operator'][$operatorId] ['data'] []= array(
                    $this->toJsTimestamp( max(strtotime($machineDataRow['dt']) - $machineDataRow['duration'], strtotime($this->dtStart)) ),
                    (int)$operatorId
                );
                $this->output['states']['operator'][$operatorId] ['data'] []= array(
                    $this->toJsTimestamp( min(strtotime($machineDataRow['dt']), strtotime($this->dtEnd)) ),
                    (int)$operatorId
                );

                $operatorInfo = Operator::model()->cache(60)->findByPk($machineDataRow['operator_id']);
                $data_ = array(
                    'code' => '',
                    'id' => $operatorInfo->id,
                    'name' => $operatorInfo->short_name,
                    'color' => '#FBC2C4',
                );

                $this->output['states']['operator'][$operatorId]['info'] = $data_;
            }
        }

        //echo '<pre>' . print_r($this->output, true) . '</pre>';die();
        //var_export($this->output); die();

        //echo $this->secTotal; die;



        return $this->output;
    }

    function startDttoJsTimestamp($useTimezone = true) {
        return $this->toJsTimestamp($this->dtStart, $useTimezone);
    }

    function endDttoJsTimestamp($useTimezone = true) {
        return $this->toJsTimestamp($this->dtEnd, $useTimezone);
    }


    private function toJsTimestamp($dt, $useTimezone = true) {
        if (!is_numeric($dt)) {
             $dt = strtotime($dt);
        }
        $ret = '';
        if ($useTimezone)
            $ret = ($dt + 7 * 60 * 60) * 1000;
        else
            $ret = ($dt + 17 * 60 * 60) * 1000;

        //debug
        //$ret = date("d.m.Y H:i:s", $dt);

        return $ret;
    }
}
