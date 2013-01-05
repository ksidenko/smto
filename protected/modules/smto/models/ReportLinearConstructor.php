<?php
class ReportLinearConstructor extends ReportSearchForm {

    /*
    * Начало, конец временного диапазона отчета
    */
    public $dtStart, $dtEnd;

    public $cr = null;

    public $machineInfo = null;

    public $secTotal;

    public $maxDeltaDt = 240; // in seconds

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
            $this->operatorInfo = Operator::model()->cache(60)->findByPk($this->operatorId);
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

        if ($this->dtEnd) {
            $curr = strtotime( date('Y.m.d H:i:s') );
            $dtEnd = strtotime($this->dtEnd);
            if ($dtEnd > $curr) { // its future
                $this->dtEnd = date('Y.m.d H:i:s');
            }
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


        foreach ($dataMachineInfo as $machineDataRow) {
            $machineStateCode = $machineDataRow['state'];

            if ($machineStateCode == MachineState::STATE_MACHINE_IDLE_RUN) {
                $machineStateCode = MachineState::STATE_MACHINE_WORK;
            }

            $arrStates = array($machineStateCode);
            if ($machineStateCode == MachineState::STATE_MACHINE_WORK) {
                $arrStates []= MachineState::STATE_MACHINE_ON;
            }

            foreach ($arrStates as $machineStateCode) {

                //-----------------------------------------------------------------------------------
                //Prepare chart data for machine states
                //-----------------------------------------------------------------------------------

                //print_r($machineDataRow); die;
//                $t1 = strtotime($machineDataRow['dt']);
//                $t0 = strtotime($machineDataRow['dt']) - $machineDataRow['duration'];
//                $dt = $t1 - $t0;
//                echo "$t1 - $t0 = $dt"; die;
                $this->output['states']['machine_state'][$machineStateCode]['data'] []= array(
                    $this->toJsTimestamp( max(strtotime($machineDataRow['dt']) - $machineDataRow['duration'], strtotime($this->dtStart)) ),
                    (int)$machineStateCode
                );
                $this->output['states']['machine_state'][$machineStateCode]['data'] []= array(
                    $this->toJsTimestamp( min(strtotime($machineDataRow['dt']), strtotime($this->dtEnd)) ),
                    (int)$machineStateCode
                );
                $this->output['states']['machine_state'][$machineStateCode]['data'] [] = null;

                $machineState = MachineState::getRec($machineStateCode);
                $data_ = array(
                    'code' => $machineState->code,
                    'name' => $machineState->name,
                    'color' => EventColor::getColorByCode('machine_' . $machineStateCode),
                );

                $this->output['states']['machine_state'][$machineStateCode]['info'] = $data_;


                //-----------------------------------------------------------------------------------
                //Prepare chart data for machine analog values
                //-----------------------------------------------------------------------------------

                $this->output['machine_da_value']['data'] []= array(
                    $this->toJsTimestamp( max(strtotime($machineDataRow['dt']) - $machineDataRow['duration'], strtotime($this->dtStart)) ),
                    (int)$machineDataRow['da_avg']
                );
                $this->output['machine_da_value']['data'] []= array(
                    $this->toJsTimestamp( min(strtotime($machineDataRow['dt']), strtotime($this->dtEnd)) ),
                    (int)$machineDataRow['da_avg']
                );
                $this->output['machine_da_value']['data'] []= null;

                $data_ = array(
                    'code' => '',
                    'name' => 'Нагрузка da_avg',
                    'color' => EventColor::getColorByCode('machine_' . MachineState::STATE_MACHINE_WORK),
                );

                $belowValue = null;
                foreach($this->machineInfo->config as $machineConfig) {
                    if ( $machineConfig->condition_number == (12 + $this->machineInfo->main_detector_analog) &&
                        $machineConfig->machine_state_id == MachineState::STATE_MACHINE_WORK
                    ) {
                        $belowValue = $machineConfig->value;
                    }
                }
                if ($belowValue) {
                    $data_ ['threshold'] = array(
                        'below' => $belowValue,
                        'color' => EventColor::getColorByCode('machine_' . MachineState::STATE_MACHINE_IDLE_RUN)
                    );
                }

                $this->output['machine_da_value']['info'] = $data_;


                //-----------------------------------------------------------------------------------
                //Prepare chart data for operator last keys
                //-----------------------------------------------------------------------------------

                if ($machineDataRow['operator_last_fkey'] > 0) {

                    $operatorLastKey = $machineDataRow['operator_last_fkey'];
                    $operatorLastKey += MachineEvent::$idOffset;

                    $this->output['states']['operator_last_fkey'][$operatorLastKey]['data'] []= array(
                        $this->toJsTimestamp( max(strtotime($machineDataRow['dt']) - $machineDataRow['duration'], strtotime($this->dtStart)) ),
                        (int)$operatorLastKey
                    );
                    $this->output['states']['operator_last_fkey'][$operatorLastKey]['data'] []= array(
                        $this->toJsTimestamp( min(strtotime($machineDataRow['dt']), strtotime($this->dtEnd)) ),
                        (int)$operatorLastKey
                    );
                    $this->output['states']['operator_last_fkey'][$operatorLastKey]['data'] []= null;


                    $fkeyState = MachineEvent::getRec($machineDataRow['operator_last_fkey']);

                    $data_ = array(
                        'code' => $fkeyState->code,
                        'name' => $fkeyState->name,
                        'color' => $fkeyState->color,
                    );

                    $this->output['states']['operator_last_fkey'][$operatorLastKey]['info'] = $data_;
                }


                //-----------------------------------------------------------------------------------
                //Prepare chart data for operators
                //-----------------------------------------------------------------------------------

                if ($machineDataRow['operator_id'] > 0) {
                    $this->output['states']['operator'][$machineDataRow['operator_id']] ['data'] []= array(
                        $this->toJsTimestamp( max(strtotime($machineDataRow['dt']) - $machineDataRow['duration'], strtotime($this->dtStart)) ),
                        ((int)$machineDataRow['operator_id'] + 10)
                    );
                    $this->output['states']['operator'][$machineDataRow['operator_id']] ['data'] []= array(
                        $this->toJsTimestamp( min(strtotime($machineDataRow['dt']), strtotime($this->dtEnd)) ),
                        ((int)$machineDataRow['operator_id'] + 10)
                    );

                    $this->output['states']['operator'][$machineDataRow['operator_id']] ['data'] []= null;

                    $operatorInfo = Operator::model()->cache(60)->findByPk($machineDataRow['operator_id']);
                    $data_ = array(
                        'code' => '',
                        'name' => $operatorInfo->full_name,
                        'color' => '#FF22FF',
                    );

                    $this->output['states']['operator'][$machineDataRow['operator_id']]['info'] = $data_;
                }
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
        if ($useTimezone)
            return ($dt + 7 * 60 * 60) * 1000;
        else
            return ($dt + 17 * 60 * 60) * 1000;
    }
}
