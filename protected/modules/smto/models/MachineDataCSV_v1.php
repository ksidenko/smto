<?php

/**
 * Анализ строки файла CSV в старом формате
 *
 */

class MachineDataCSV_v1 extends MachineDataCSV {
    
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
        $this->countColumns = 8;

        $machineRec = $this->_machine->getRecByMAC($this->mac);
        $this->machineId = $machineRec ? $machineRec['id'] : null;
    }

    static public function getSqlInsertPart () {
        $sql = 'insert into machine_data (`dt`, `duration`, `mac`, `machine_id`, `operator_id`, `da_max1`, `operator_last_fkey`,  `state`)
                values ' . PHP_EOL;

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

    public function parseCSVLine($line, &$lastDateTime = null) {

        $this->init();

        $arr = explode($this->separator, $line);
        $res = false;

        if (count($arr) == $this->countColumns) {
            // format example
            // //21.04.2011,15:51:48,0,1,0,0,0,24058455

            $date = trim(array_shift($arr));
            $time = trim(array_shift($arr));
            $this->dt = date('Y/m/d', strtotime($date)) . ' ' . $time;
            if ($lastDateTime != null) {
                $this->duration = strtotime($this->dt) - strtotime($lastDateTime);
                if ($this->duration < 0 || $this->duration > Yii::app()->smto->max_duration ) {
                    $this->duration = null;
                }
            }

            $amplitude = array_shift($arr);

            $this->da_max1 = $amplitude;
            $powerOn = trim(array_shift($arr));
            $isWorking = trim(array_shift($arr));
            $isAlarm = trim(array_shift($arr));
            $eventCode = trim(array_shift($arr));

            if ($powerOn == false) {
                $this->state = MachineState::STATE_MACHINE_OFF;
            } else {
                if ($isAlarm) {
                    $this->state = MachineState::STATE_MACHINE_OFF;
                } else {
                    if ($isWorking == true) {
                        $range = Amplitude::getAmplitudesRange($this->machineId);
                        if ($range) {
                            if ($amplitude < $range[0]) {
                                $this->state = MachineState::STATE_MACHINE_ON; // Включен
                            } else if ($amplitude < $range[1]) {
                                $this->state = MachineState::STATE_MACHINE_IDLE_RUN; // Холостой ход
                            } else  {
                                $this->state = MachineState::STATE_MACHINE_WORK; // Работает
                            } 
                        } else {
                            $this->state = MachineState::STATE_MACHINE_ON;
                        }
                    }
                }
            }

            $this->operator_last_fkey = $eventCode;
            
            $c1 = trim(array_shift($arr));
            $c2 = $c3 = '';

            $opearatorRec = $this->_operator->getRecByCode($c1, $c2, $c3);
            if (!$opearatorRec) {
                //echo 'no operator: ' . $c1 . ', ' . $c2 . ', '. $c3;
            }
            $this->operatorId = $opearatorRec ? $opearatorRec['id'] : null;

            $res = true;
        } else {
            $errors = 'Bad column count: ' . count($arr);
        }

        return $res;
    }

    function getSqlPart() {
        $s = '';

        if ( $this->errors == null ) {
            $s = implode(',', array(
                '"' . str_replace(':', '^', $this->dt) . '"',
                ($this->duration == null || $this->duration < 0 ? "null" : $this->duration),
                '"' . $this->mac . '"',
                ( !empty($this->machineId) ? $this->machineId : 'null'),
                ( !empty($this->operatorId) ? $this->operatorId : 'null'),
                $this->da_max1,
                $this->operator_last_fkey,
                $this->state,
            ));
        }

        $s = $s != '' ? '(' . $s . ')' : '';

        return $s;
    }
}
 
