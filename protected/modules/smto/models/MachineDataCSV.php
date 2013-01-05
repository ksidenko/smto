<?php

/**
 * Строка файла csv
 *
 */

class MachineDataCSV {
    public $version = '';
    public $id = null;
    public $separator = ',';

    public $dt = null;

    public $mac = null;
    public $machineId = null;
    public $operator_id = null;

    public $_machine = null;
    public $_operator = null;
    public $errors = null;

    static protected $ignoreInsertDublicates = false;

    public function init() {
        if ($this->_machine == null) {
            $this->_machine = new Machine();
        }

        if ($this->_operator == null) {
            $this->_operator = new Operator();
        }
    }

    static public function getSqlInsertPart () { }
    
    public function parseCSVLine($line, &$lastMachineDataRec = null) { }

    public function getSqlPart() { }

    public function isIdentityRecords($lastMachineDataRec) { }
}
 
