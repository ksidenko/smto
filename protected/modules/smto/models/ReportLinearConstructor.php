<?php
class ReportLinearConstructor extends ReportSearchForm {

    /*
    * Начало, конец временного диапазона отчета
    */
    public $dtStart, $dtEnd;

    public $cr = null;

    public $machineInfo = null;

    public $secTotal;

    public $maxDeltaDt = 15; // in seconds

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

        if ($this->operatorId) {
            $this->operatorInfo = Operator::model()->findByPk($this->operatorId);
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

        $this->cr->addBetweenCondition('t.dt', $this->dtStart, $this->dtEnd);
        $this->secTotal = strtotime($this->dtEnd) - strtotime($this->dtStart);
        //print_r($this->getAttributes());die;
        //print_r($this->secTotal);die;

        $this->cr->select += array( 't.dt', 't.machine_id', 't.state', 't.operator_last_fkey', 't.operator_id', 't.da_avg1');
        //$this->cr->select []= new CDbExpression("SUM(IFNULL(t.duration,10)) + 0  as sec_duration");

        $this->cr->compare('t.machine_id', $this->machineId);

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

            // process break for machine states
            if ( isset($lastTimeValues['machine_state'][$machineDataRow['state']]) ) {
                $dtPrev = $lastTimeValues['machine_state'][$machineDataRow['state']];
                $dt = $machineDataRow['dt'];
                if (strtotime($dt) - strtotime($dtPrev) > $this->maxDeltaDt) {
                    $this->output['states']['machine_state'][$machineDataRow['state']]['data'] [] = null;
                }
            }
            // save last dt value for current machine state
            $lastTimeValues['machine_state'][$machineDataRow['state']] = $machineDataRow['dt'];


            $this->output['states']['machine_state'][$machineDataRow['state']]['data'] []= array(
                $this->toJsTimestamp($machineDataRow['dt']),
                (int)$machineDataRow['state']
            );

            $machineState = MachineState::model()->findByPk($machineDataRow['state']);
            $data_ = array(
                'code' => $machineState->code,
                'name' => $machineState->name,
                'color' => EventColor::getColorByCode('machine_' . $machineDataRow['state']),
            );

            $this->output['states']['machine_state'][$machineDataRow['state']]['info'] = $data_;

            //-----------------------------------------------------------------------------------
            //Prepare chart data for machine analog values
            //-----------------------------------------------------------------------------------

            // process break for machine states
            if ( isset($lastTimeValues['machine_da_value']) ) {
                $dtPrev = $lastTimeValues['machine_da_value'];
                $dt = $machineDataRow['dt'];
                if (strtotime($dt) - strtotime($dtPrev) > $this->maxDeltaDt) {
                    $this->output['machine_da_value'] []= null;
                }
            }
            // save last dt value for current machine state
            $lastTimeValues['machine_da_value'] = $machineDataRow['dt'];


            $this->output['machine_da_value']['data'] []= array(
                $this->toJsTimestamp($machineDataRow['dt']),
                (int)$machineDataRow['da_avg1']
            );

            $data_ = array(
                'code' => '',
                'name' => 'Нагрузка da_avg1',
                'color' => '#FF0F0F',
            );

            $this->output['machine_da_value']['info'] = $data_;



            //-----------------------------------------------------------------------------------
            //Prepare chart data for operator last keys
            //-----------------------------------------------------------------------------------

            if ($machineDataRow['operator_last_fkey'] > 0) {

                // process break for machine states
                if ( isset($lastTimeValues['operator_last_fkey'][$machineDataRow['operator_last_fkey']]) ) {
                    $dtPrev = $lastTimeValues['operator_last_fkey'][$machineDataRow['operator_last_fkey']];
                    $dt = $machineDataRow['dt'];
                    if (strtotime($dt) - strtotime($dtPrev) > $this->maxDeltaDt) {
                        $this->output['states']['operator_last_fkey'][$machineDataRow['operator_last_fkey']]['data'] [] = null;
                    }
                }
                // save last dt value for current machine state
                $lastTimeValues['operator_last_fkey'][$machineDataRow['operator_last_fkey']] = $machineDataRow['dt'];


                $this->output['states']['operator_last_fkey'][$machineDataRow['operator_last_fkey']]['data'] []= array(
                    $this->toJsTimestamp($machineDataRow['dt']),
                    (int)$machineDataRow['operator_last_fkey']
                );


                $fkeyState = MachineEvent::getRec($machineDataRow['operator_last_fkey']);

                $data_ = array(
                    'code' => $fkeyState->code,
                    'name' => $fkeyState->name,
                    'color' => $fkeyState->color,
                );

                $this->output['states']['operator_last_fkey'][$machineDataRow['operator_last_fkey']]['info'] = $data_;
            }

            $this->output['states']['operator'][$machineDataRow['operator_id']] ['data'] []= array(
                $this->toJsTimestamp($machineDataRow['dt']),
                (int)$machineDataRow['operator_id']
            );
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
            return (strtotime($dt) + 7 * 60 * 60) * 1000;
        else
            return (strtotime($dt) + 17 * 60 * 60) * 1000;
    }
}
