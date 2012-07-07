<?php

/**
 * Строка файла csv
 *
 */

class MachineDataCSV {
    public $separator = ',';
    public $countColumns = 0;

    public $dt = null;

    public $machineId = null;
    public $operatorId = null;

    public $_machine = null;
    public $_operator = null;
    public $errors = null;

    public function init() {
        if ($this->_machine == null) {
            $this->_machine = new Machine();
        }

        if ($this->_operator == null) {
            $this->_operator = new Operator();
        }
    }

    static public function getSqlInsertPart () { }
    
    public function parseCSVLine($line, &$lastDateTime = null) { }

    public function getSqlPart() { }
}
 
