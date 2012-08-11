<?php

/**
 * Анализ строки файла CSV в новом формате
 *
 */

class MachineDataCSV_v2 extends MachineDataCSV {
    // in fields
    public $number = null;
    public $dt = null;
    public $duration = null;
    public $mac = null;

    public $da_max1 = null;
    public $da_max2 = null;
    public $da_max3 = null;
    public $da_max4 = null;

    public $da_avg1 = null;
    public $da_avg2 = null;
    public $da_avg3 = null;
    public $da_avg4 = null;

    public $dd1 = null;
    public $dd2 = null;
    public $dd3 = null;
    public $dd4 = null;

    public $dd_change1 = null;
    public $dd_change2 = null;
    public $dd_change3 = null;
    public $dd_change4 = null;

    public $state = null;

    public $operator_last_fkey = null;
    public $fkey_all = null;
    public $flags = null;


    public function init() {
        parent::init();
        $this->countColumns = 28;
    }

    static public function getSqlInsertPart () {
        $sql = 'insert ignore into machine_data (`number`, `dt`, `duration`, `mac`, `machine_id`, `operator_id`, `da_max1`, `da_max2`, `da_max3`, `da_max4`, `da_avg1`, `da_avg2`, `da_avg3`, `da_avg4`, `dd1`, `dd2`, `dd3`, `dd4`, `dd_change1`, `dd_change2`, `dd_change3`, `dd_change4`, `state`, `operator_last_fkey`, `fkey_all`, `flags`)
                values ' . PHP_EOL;

        return $sql;
    }

    public function parseCSVLine($line, &$lastDateTime = null) {
        if ( !isset($line[0])  ) {
            return false;
        }
        $lineType = $line[0];
        if ( !in_array($lineType, array( 'D', 'C') ) ) {
            return false;
        }
        
        $this->init();

        $arr = explode($this->separator, $line);
        $res = false;

        if ( 1 || count($arr) == 28) {
            // format example
            // D,00BD3B330571,9462, 03.06.2011,21:43:46, 
            // 165,1023,241,11, 165,1023,241,10, 1,0,1,1, 0,0,0,0, 2,3, 0,0, 59,16,48508

            array_shift($arr); //skip type record - only D-type
            $this->mac = trim(array_shift($arr));
            $machineRec = $this->_machine->getRecByMAC($this->mac);
            $this->machineId = $machineRec ? $machineRec['id'] : null;
            $this->number = trim(array_shift($arr));
            $date = trim(array_shift($arr));
            $time = trim(array_shift($arr));

            $this->dt = date('Y/m/d', strtotime($date)) . ' ' . $time;
            if ($lastDateTime != null) {
                $this->duration = strtotime($this->dt) - strtotime($lastDateTime);
                if ($this->duration < 0 || $this->duration > 100) {
                    $this->duration = null;
                }
            }
            if ($lineType == 'C') {
        	    array_shift($arr);
                array_shift($arr);
                array_shift($arr);
                array_shift($arr);
            }
            $this->da_max1 = intval(trim(array_shift($arr)));
            $this->da_max2 = intval(trim(array_shift($arr)));
            $this->da_max3 = intval(trim(array_shift($arr)));
            $this->da_max4 = intval(trim(array_shift($arr)));

            $this->da_avg1 = intval(trim(array_shift($arr)));
            $this->da_avg2 = intval(trim(array_shift($arr)));
            $this->da_avg3 = intval(trim(array_shift($arr)));
            $this->da_avg4 = intval(trim(array_shift($arr)));

            if ($lineType == 'C') {
        	    array_shift($arr);
                array_shift($arr);
                array_shift($arr);
                array_shift($arr);
            }


            $this->dd1 = intval(trim(array_shift($arr)));
            $this->dd2 = intval(trim(array_shift($arr)));
            $this->dd3 = intval(trim(array_shift($arr)));
            $this->dd4 = intval(trim(array_shift($arr)));

            $this->dd_change1 = intval(trim(array_shift($arr)));
            $this->dd_change2 = intval(trim(array_shift($arr)));
            $this->dd_change3 = intval(trim(array_shift($arr)));
            $this->dd_change4 = intval(trim(array_shift($arr)));

            //состояние станка (0...3), с точки зрения контроллера, на НАЧАЛО интервала (то есть 10 секунд назад от времени записи)
            //0 - выключен, 1 - включен, 2 - холостой ход, 3 - работает
            $this->state = trim(array_shift($arr));

            //последняя причина простоя, указанная оператором станка на пульте контроллера
            //0 - неизвестная причина, 1...15 - нажатая оператором станка кнопка.
            $this->operator_last_fkey = intval(trim(array_shift($arr)));

            $this->fkey_all = intval(trim(array_shift($arr)));
            $this->flags = intval(trim(array_shift($arr)));

            $c1 = trim(array_shift($arr));
            $c2 = trim(array_shift($arr));
            $c3 = trim(array_shift($arr));

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

    public function getSqlPart() {
        $s = '';

        if ( $this->errors == null ) {
            $s = implode(',', array(
                $this->number,
                '"' . str_replace(':', '^', $this->dt) . '"',
                ($this->duration == null || $this->duration < 0 ? 'null': $this->duration),
                '"' . $this->mac . '"',

                ( !empty($this->machineId) ? $this->machineId : 'null'),
                ( !empty($this->operatorId) ? $this->operatorId : 'null'),

                $this->da_max1,$this->da_max2,$this->da_max3,$this->da_max4,

                $this->da_avg1,$this->da_avg2,$this->da_avg3,$this->da_avg4,

                $this->dd1,$this->dd2,$this->dd3,$this->dd4,

                $this->dd_change1,$this->dd_change2,$this->dd_change3,$this->dd_change4,

                $this->state,

                $this->operator_last_fkey,
                $this->fkey_all,
                $this->flags,
            ));
        }

        $s = $s != '' ? '(' . $s . ')' : '';

        return $s;
    }


}
 
