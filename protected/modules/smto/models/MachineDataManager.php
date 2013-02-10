<?php

class MachineDataManager {

    private $_version = null;
    private $_mac = null;


    function __construct($version = '2.0', $mac = null) {
        $this->_version = $version;
        $this->_mac = $mac;
    }

    public function getVersion() {
        return $this->_version;
    }

    function getFileFormat($mac) {
        $s = '';

//        if ($this->version == '1.0') {
//            $s = '/^log1.txt_.*/i';
//        } else if ($this->version == '2.0') {
//            $s = '/^pw' . $mac . '.*\.dat$/i';
//        }

        if ($this->_version == '1.0') {
            $s = 'log1.txt_*';
        } else if ($this->_version == '2.0') {
            //$s = 'pw' . $mac . '*.dat';
            $s = '*.dat';
        }

        return $s;
    }

    function getLineParser($useCurr = false) {
        $res = null;

        if ($this->_version == '1.0') {

            if ($useCurr) {
                $res = new MachineDataCSV_v1_curr();
            } else {
                $res = new MachineDataCSV_v1();
            }

            $res->mac = $this->_mac;
        } else if ($this->_version == '2.0') {
            $res = new MachineDataCSV_v2();
        }

        return $res;
    }

    public function getSqlInsertPart () {
        $res = '';

        if ($this->_version == '1.0') {
            $res = MachineDataCSV_v1::getSqlInsertPart();
        } else if ($this->_version == '2.0') {
            $res = MachineDataCSV_v2::getSqlInsertPart();
        }

        return $res;
    }

}
