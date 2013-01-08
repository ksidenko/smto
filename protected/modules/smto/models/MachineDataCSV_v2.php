<?php

/**
 * Анализ строки файла CSV в новом формате
 *
 */

class MachineDataCSV_v2 extends MachineDataCSV {
    public $version = '2.0';

    private $arrLineTypes = array( 'C', 'D' );
    private $lineColumnCounts = array( 'C' => 42, 'D' => 28 );

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
    }

    static public function getSqlInsertPart () {
        $ignore = '';
        if (self::$ignoreInsertDublicates) {
            $ignore = 'ignore';
        }

        $sql = "insert $ignore into machine_data (`number`, `dt`, `duration`, `mac`, `machine_id`, `operator_id`, `da_max1`, `da_max2`, `da_max3`, `da_max4`, `da_avg1`, `da_avg2`, `da_avg3`, `da_avg4`, `dd1`, `dd2`, `dd3`, `dd4`, `dd_change1`, `dd_change2`, `dd_change3`, `dd_change4`, `state`, `operator_last_fkey`, `fkey_all`, `flags`)
                values " . PHP_EOL;

        return $sql;
    }

    public function parseCSVLine($line, &$lastMachineDataRec = null) {
        $this->init();

        if ( !isset($line[0])  ) {
            return false;
        }
        $lineType = $line[0];
        if ( !in_array($lineType, $this->arrLineTypes ) ) {
            $errors = 'Incorrect line type: ' . $lineType;
            Yii::log("$errors", 'info', __METHOD__);
            return false;
        }

        $arr = explode($this->separator, $line);

        if ( count($arr) != $this->lineColumnCounts[$lineType] ) {
            $errors = 'Bad column count in line: ' . count($arr);
            Yii::log("$errors", 'info', __METHOD__);
            return false;
        }

        // format *.dat example
        // D,00BD3B330571,9462, 03.06.2011,21:43:46,
        // 165,1023,241,11, 165,1023,241,10, 1,0,1,1, 0,0,0,0, 2,3, 0,0, 59,16,48508

        // format *.cdt example
        // C,1275AD210D84,51368, 26.12.2011,14:16:08,
        // 1,1,1,1, 2,2,1,992, 1,1,1,504, 0,0,1,1, 0,0,0,1, 0,0,0,0, 0,44,0,0,0, 0,0,0,330, 59,16,48508, 8810A879


        array_shift($arr); //skip type record - only C|D-type
        $this->mac = trim(array_shift($arr));
        $machineRec = $this->_machine->getRecByMAC($this->mac);
        $this->machineId = $machineRec ? $machineRec['id'] : null;
        $this->number = trim(array_shift($arr));
        $date = trim(array_shift($arr));
        $time = trim(array_shift($arr));

        $this->dt = date('Y/m/d', strtotime($date)) . ' ' . $time;
        if ( $lastMachineDataRec ) {
	    if ($this->dt == $lastMachineDataRec->dt) {
	        return false;
	    }
	
	    $this->duration = strtotime($this->dt) - strtotime($lastMachineDataRec->dt);
	    if ($this->duration < 0) {
		// we restore old data file, last rec is incorrect
		$lastMachineDataRec = null;
	    }
	
            $m = Yii::app()->getModules();
            $t = $m['smto']['max_time_between_machine_records'];
            if ($this->duration < 0 || $this->duration > $t ) {
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

        $operatorRec = $this->_operator->getRecByCode($c1, $c2, $c3);

        $this->operator_id = null;
        if ($operatorRec) {
            $this->operator_id = $operatorRec['id'];
        }

        return true;
    }

    public function getSqlPart() {
        $s = implode(',', array(
            $this->number,
            '"' . str_replace(':', '^', $this->dt) . '"',
            ($this->duration == null || $this->duration < 0 ? 'null': $this->duration),
            '"' . $this->mac . '"',

            ( !empty($this->machineId) ? $this->machineId : 'null' ),
            ( !empty($this->operator_id) ? $this->operator_id : 'null' ),

            $this->da_max1,$this->da_max2,$this->da_max3,$this->da_max4,

            $this->da_avg1,$this->da_avg2,$this->da_avg3,$this->da_avg4,

            $this->dd1,$this->dd2,$this->dd3,$this->dd4,

            $this->dd_change1,$this->dd_change2,$this->dd_change3,$this->dd_change4,

            $this->state,

            $this->operator_last_fkey,
            $this->fkey_all,
            $this->flags,
        ));

        $s = '(' . $s . ')';

        return $s;
    }


}
 
