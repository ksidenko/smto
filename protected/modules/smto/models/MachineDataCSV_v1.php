<?php

/**
 * Анализ строки файла CSV в старом формате
 *
 */

class MachineDataCSV_v1 extends MachineDataCSV {
    public $version = '1.0';

    private $lineColumnCounts = 8;

    // in fields
    public $number = null;
    public $dt = null;
    public $duration = null;
    public $mac = null;

    public $da_max1 = null;

    public $operator_last_fkey = null;
    
    public $state = null;

    public function init() {
        parent::init();
    }

    static public function getSqlInsertPart () {
        $ignore = '';
        if (self::$ignoreInsertDublicates) {
            $ignore = 'ignore';
        }
        $sql = "insert $ignore into machine_data (`dt`, `duration`, `mac`, `machine_id`, `operator_id`, `da_avg1`, `operator_last_fkey`,  `state`)
                values " . PHP_EOL;

        return $sql;
    }

 /*
1 	Дата 	строка 		21.04.2011
2 	Время 	строка 		15:51:48
3 	Аналоговое значение 	целое 	назрузка, напряжение на станке 	100
4 	Вкл\Выкл 	1/0 	Питание на станке - есть или нет 	1
5 	Работает\Неработает 	1/0 	что это? под нагрузкой или нет? Холостой ход или работа. 	0
6 	Авария 	1/0 		0
7 	Код события 	0,1,2..8 		3
8 	Пропуск 	целое 	Номер карточки оператора 	240584
*/

    public function parseCSVLine($line, &$lastMachineDataRec = null) {

        $this->init();

        $arr = explode($this->separator, $line);

        if ( count($arr) != $this->lineColumnCounts ) {
            $errors = 'Bad column count in line: ' . count($arr);
            return false;
        }


        $machineRec = $this->_machine->getRecByMAC($this->mac);
        $this->machineId = $machineRec ? $machineRec['id'] : null;

        // format example
        // //21.04.2011,15:51:48,0,1,0,0,0,24058455

        $date = trim(array_shift($arr));
        $time = trim(array_shift($arr));
        $this->dt = date('Y/m/d', strtotime($date)) . ' ' . $time;
        if ( $lastMachineDataRec ) {
            $this->duration = strtotime($this->dt) - strtotime($lastMachineDataRec->dt);
            $m = Yii::app()->getModules();
            $maxDuration = $m['smto']['max_duration'];
            if ($this->duration < 0 || $this->duration > $maxDuration ) {
                $this->duration = null;
            }
        }

        $amplitude = array_shift($arr);
	//echo "ampl = $amplitude" . PHP_EOL;

        $this->da_avg1 = $amplitude;
        $powerOn = trim(array_shift($arr));
        $isWorking = trim(array_shift($arr));
        $isAlarm = trim(array_shift($arr));
        $eventCode = trim(array_shift($arr));

        //$range = Amplitude::getAmplitudesRange($this->machineId);
        //echo '|'.print_r($range,true) . '|'; die;
        
        if ($powerOn == false) {
            $this->state = MachineState::STATE_MACHINE_OFF;
        } else {
                $range = Amplitude::getAmplitudesRange($this->machineId);
                if ($isWorking == true) {
                    if ($range) {
                        if ($amplitude < $range[MachineState::STATE_MACHINE_ON]) {
                            $this->state = MachineState::STATE_MACHINE_IDLE_RUN; // 
                        } else if ($amplitude < $range[MachineState::STATE_MACHINE_IDLE_RUN]) {
                            $this->state = MachineState::STATE_MACHINE_IDLE_RUN; // Холостой ход
                        } else  {
                            $this->state = MachineState::STATE_MACHINE_WORK; // Работает
                        }
                    } else {
                        $this->state = MachineState::STATE_MACHINE_ON;
                    }
                } else {
                    if ($range) {
                        if ($amplitude < $range[MachineState::STATE_MACHINE_ON]) {
                            $this->state = MachineState::STATE_MACHINE_ON; // Включен
                        } else if ($amplitude < $range[MachineState::STATE_MACHINE_IDLE_RUN]) {
                            $this->state = MachineState::STATE_MACHINE_IDLE_RUN; // Холостой ход
                        } else  {
                            $this->state = MachineState::STATE_MACHINE_WORK; // Работает
                        }
                    } else {
                        $this->state = MachineState::STATE_MACHINE_ON;
                    }
                }
        }

        $this->operator_last_fkey = $eventCode;

        $c3 = trim(array_shift($arr));
        $c1 = $c2 = '';

        $operatorRec = $this->_operator->getRecByCode($c1, $c2, $c3);

        $this->operator_id = null;
        if ($operatorRec) {
            $this->operator_id = $operatorRec['id'];
        }

        return true;
    }

    function getSqlPart() {
        $s = implode(',', array(
            '"' . str_replace(':', '^', $this->dt) . '"',
            ($this->duration == null || $this->duration < 0 ? "null" : $this->duration),
            '"' . $this->mac . '"',
            ( !empty($this->machineId) ? $this->machineId : 'null' ),
            ( !empty($this->operator_id) ? $this->operator_id : 'null' ),
            $this->da_avg1,
            $this->operator_last_fkey,
            $this->state,
        ));

        $s = '(' . $s . ')';

        return $s;
    }
}
 
