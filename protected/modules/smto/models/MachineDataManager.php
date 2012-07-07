<?php

class MachineDataManager {

    public $version = null;

    function __construct($version = '2.0') {
        $this->version = $version;
    }

    function getFileFormat($mac) {
        $s = '';

//        if ($this->version == '1.0') {
//            $s = '/^log1.txt_.*/i';
//        } else if ($this->version == '2.0') {
//            $s = '/^pw' . $mac . '.*\.dat$/i';
//        }

        if ($this->version == '1.0') {
            $s = 'log1.txt_*';
        } else if ($this->version == '2.0') {
            $s = 'pw' . $mac . '*.dat';
        }

        return $s;
    }

    function getLineParser($mac) {
        $res = null;

        if ($this->version == '1.0') {
            $res = new MachineDataCSV_v1();
        } else if ($this->version == '2.0') {
            $res = new MachineDataCSV_v2();
        }
        $res->mac = $mac;

        return $res;
    }

    public function getSqlInsertPart () {
        $ret = '';
        if ($this->version == '1.0') {
            $ret = MachineDataCSV_v1::getSqlInsertPart();
        } else if ($this->version == '2.0') {
            $ret = MachineDataCSV_v2::getSqlInsertPart();
        }

        return $ret;
    }

}