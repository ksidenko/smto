<?php
//require_once 'MachineDataCSV.php';

class MachineDataImport {
    public $errors = null;
    public $sqlParts = array();
    public $db = null;
    public $manager = null;
    public $mac = '';

    function __construct($version = '2.0') {
        $this->db = Yii::app()->db;
        //$this->db->query('truncate machine_log');
        $this->manager = new MachineDataManager($version);
        ini_set("max_execution_time", "300");
    }
    
    public function run($dir, $mac, $limitFileProcess = 10) {
        $this->mac = $mac;
        $filenames = $this->getFilenames($dir, $mac, $limitFileProcess);

        $processFiles = array();
        foreach((array)$filenames as $filename) {
            $filename = rtrim($dir, '\\') . '/' . $filename;

            //echo $filename . PHP_EOL;
            
            $res = $this->parseFile($filename, $lastDateTime);
            if (!$res) {
                echo 'bad file ' . $filename . PHP_EOL;;
                unlink($filename);
                continue;
            }
            $sql = $this->getSql(); //die($sql);
            
            $cnt = 0;
            try {
                //die($sql);
                $cnt = $this->db->createCommand($sql)->execute();
            } catch (Exception $e) {
                echo $e->getMessage() . PHP_EOL;
                echo $e->getTraceAsString();
                //fb($e, '', '', true);
            }
            if ( $cnt > 0 ) {
                $path = dirname($filename) . '/process/';
                $filename_ = basename($filename);

                if (!file_exists($path)) {
                    mkdir($path);
                }
            
                $processFiles [$cnt]= $filename;
                //copy($filename, $path . 'process_' . date("Y.m.d_H.i", time()) . '_' . $filename_);

                //rename($filename, $path . $filename_);
                unlink($filename);
            }
        }
        print_r($processFiles);
    }
    
    public function getFilenames($dir, $mac, $limit) {
        $format = $this->manager->getFileFormat($mac);
        $filenames = Helpers::scandir($dir, $format, 'ctime', true);

        if ( count($filenames) > $limit ) {
            $filenames = array_slice($filenames, 0, $limit);
        }
        return $filenames;
    }
    
    public function getSql() {
        if ( count($this->sqlParts) <= 0) {
            return '';
        }
        
        $sql = $this->manager->getSqlInsertPart();

        return $sql . implode( ',' . PHP_EOL, $this->sqlParts );
        
    }
    
    public function parseFile ($filename, &$lastDateTime = null) {
        
        $file = fopen($filename, 'r');
        
        if (!$file) {
            return false;
        }
        
        $this->sqlParts = array();
                
        $sql = '';
        while ( $line = fgets($file) ) {
            //echo 'line: ' . $line . PHP_EOL;
            $line = str_replace(array("\r\n", "\n"), '', $line);

            $csvLine = $this->manager->getLineParser($this->mac);
            $res = $csvLine->parseCSVLine($line, $lastDateTime);

            if ($res) {
                $sql_ = $csvLine->getSqlPart();
                if ( trim($sql_) != '') {
                    $sql .= PHP_EOL . $sql_ . ',';
                }
            } else {
                $errors []= $csvLine->errors;
            }
            
            $lastDateTime = $csvLine->dt;
            unset($csvLine);
        }
        if ( trim($sql) != '') {
            $sql = rtrim($sql, ','); 
            $this->sqlParts [] = $sql;
        }
        fclose($file);
        
        return trim($sql) != '';
    }

}