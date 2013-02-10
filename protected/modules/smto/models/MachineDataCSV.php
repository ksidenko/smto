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


//    public $machineInfo = null;
//    public $machineStateInfo = null;
//    public $machineEventInfo = null;
//    public $operatorInfo = null;

    public $errors = null;

    public $isMachineAvailable = false;

    static protected $ignoreInsertDublicates = false;

    public function init() { }

    static public function getSqlInsertPart () { }
    
    public function parseCSVLine($line, &$lastMachineDataRec = null) { }

    public function getSqlPart() { }

    public function isIdentityRecords($lastMachineDataRec) { }

    public function __get($name) {

        if ($name == 'machineInfo') {

            if (!isset($this->machineInfo)) {
                $this->machineInfo = null;

                if ($this->machineId) {
                    $this->machineInfo = Machine::model()->cache(600)->findByPk($this->machineId);
                }
            }

            return $this->machineInfo;

        } else if ($name == 'machineStateInfo') {

            if (!isset($this->machineStateInfo)) {
                $this->machineStateInfo = null;

                if ($this->state !== null) {
                    $this->machineStateInfo = MachineState::model()->cache(600)->findByPk($this->state);
                    if ($this->machineStateInfo) {
                        $e = EventColor::model()->cache(600)->findByAttributes(array('code' => 'machine_' . $this->state));
                        $this->machineStateInfo->color = '#' . ltrim($e->color, '#');
                    }
                }
            }

            return $this->machineStateInfo;

        } else if ($name == 'machineEventInfo') {

            if (!isset($this->machineEventInfo)) {
                $this->machineEventInfo = null;

                if ($this->operator_last_fkey > 0) {
                    $fkeys = $this->machineInfo->cache(600)
                                  ->fkey(array('condition' => 'number = ' . $this->operator_last_fkey));
                    if ($fkeys) {
                        $this->machineEventInfo = $fkeys[0]->cache(600)->machine_event;
                    }
                }
            }
            return $this->machineEventInfo;

        } else if ($name == 'operatorInfo') {

            if (!isset($this->operatorInfo)) {
                $this->operatorInfo = null;

                if ($this->operator_id > 0) {
                    $this->operatorInfo = Operator::model()->cache(600)->findByPk($this->operator_id);
                }
            }
            return $this->operatorInfo;
        }

        return false;
    }

    public function __set($name, $value) {

        if ($name == 'machineInfo') {
            $this->machineInfo = $value;
            if ($value) {
                $this->machineId = $value->id;
            }
            return true;

        } else if ($name == 'machineStateInfo') {
            $this->machineStateInfo = $value;
            return true;

        } else if ($name == 'machineEventInfo') {
            $this->machineEventInfo = $value;
            return true;

        }  else if ($name == 'operatorInfo') {
            $this->operatorInfo = $value;
            if ($value) {
                $this->operator_id = $value->id;
            }
            return true;
        }
    }
}
