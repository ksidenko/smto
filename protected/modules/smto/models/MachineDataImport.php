<?php
//require_once 'MachineDataCSV.php';

class MachineDataImport {
    public $errors = null;

    /**
     * @var array $parsedRows массив строк обработанных записей из dat-файлов
     */
    public $parsedRows = array();

    private $_manager = null;

    private $_db = null;

    private $_mac = '';
    private $_machine = null;

    function __construct($version = '2.0', $mac) {
        $this->_db = Yii::app()->db;
        //DEBUG
        //$this->_db->createCommand('truncate machine_data')->execute();

        $this->_mac = $mac;

        $this->_machine = Machine::getRecByMAC($this->_mac);
        $this->_manager = new MachineDataManager($version, $this->_mac);

        //TODO
        ini_set("max_execution_time", "300");
    }
    
    public function run($dir, $limitFileProcess = 10) {

        $filenames = (array)$this->getFilenames($dir, $this->_mac, $limitFileProcess);
        //print_r($filenames); die;

        $processFiles = array();

        foreach($filenames as $filename) {


            //debug
            echo "=============================================$filename=========================" . PHP_EOL;

            $lastParsedRow = array_pop($this->parsedRows);
            if ($lastParsedRow && intval($lastParsedRow->id) > 0 ) {
                $machineDataARLast = $lastParsedRow;
                echo "=============================================Its AR!=========================" . PHP_EOL;
            } else {
                if ($lastParsedRow) {
                    $lastDt = date('Y/m/d H:i:s', strtotime($lastParsedRow->dt . ' -5 minutes')) ;
                } else {
                    $lastDt = date('Y/m/d H:i:s', strtotime('-1 day')) ;
                }

                //echo "last search dt in DB: $lastDt" . PHP_EOL;

                $machineDataARLast = $this->getLastMachineDataRec($lastDt);
            }
            //$machineDataARLast = null;

            if ($machineDataARLast) {
                $isAr = intval($machineDataARLast->id) > 0 ? 'true' : 'false';
                echo "Found last row in DB: {$machineDataARLast->id}) {$machineDataARLast->dt} - {$machineDataARLast->duration} (is AR: $isAr)" . PHP_EOL;
            }

            $this->parsedRows = array();
            if ($machineDataARLast) {
                $this->parsedRows[] = $machineDataARLast;
            }

            $filename = rtrim($dir, '\\') . '/' . $filename;
            //echo $filename . PHP_EOL;
            
            $res = $this->parseFile($filename, $machineDataARLast);
            if (!$res) {
                // file is empty, we think so...
                continue;
            }

            $sql = $this->generateSql();

            foreach ($this->parsedRows as $parsedRow) {
                $isAr = intval($parsedRow->id) > 0 ? 'true' : 'false';
                echo "Parsed row: {$parsedRow->dt} - {$parsedRow->duration} (is AR: $isAr)" . PHP_EOL;
            }

            $cnt = 0;
            try {
                //die($sql);
                if (!empty($sql)) {
                    $cnt = $this->_db->createCommand($sql)->execute();
                }

                foreach ($this->parsedRows as $parsedRow) {
                    if (intval($parsedRow->id) > 0) {
                        $parsedRow->isNewRecord = false;
                        $parsedRow->save(false, array('dt', 'duration'));
                        $cnt++;
                    }
                }
            } catch (Exception $e) {
                $cnt = false;
                echo $e->getMessage() . PHP_EOL;
                //echo $e->getTraceAsString();
                //fb($e, '', '', true);
            }
            if ( $cnt >= 1) {

                $filename_ = basename($filename);
                $processFiles [$cnt]= $filename;

                $m = Yii::app()->getModules();
                $isSaveProcessFiles = $m['smto']['is_save_process_files'];
                if ($isSaveProcessFiles) {
                    $path = dirname($filename) . '/process/';
                    if (!file_exists($path)) {
                        mkdir($path);
                    }
                    rename($filename, $path . $filename_);
                } else {
                    unlink($filename);
                }
            }
        }
        print_r($processFiles);

        if ($this->errors) {
            Yii::log(print_r($this->errors, true), 'errors', __METHOD__);
        }
    }
    
    protected function getFilenames($dir, $mac, $limit) {
        $format = $this->_manager->getFileFormat($mac);

        $desc = true;
        $sortType = '-tr';
        if ($this->_manager->getVersion() == '1.0'){
            $sortType = '-X';
            $desc = false;
        }
        $filenames = Helpers::scandirFast($dir, $format, $desc , $limit, $sortType);

//        $filenames = Helpers::scandir($dir, $format, 'ctime', true);
//
//        if ( count($filenames) > $limit ) {
//            $filenames = array_slice($filenames, 0, $limit);
//        }
        return $filenames;
    }


    protected function parseFile ($filename, &$lastMachineDataRec = null) {
        
        $file = @fopen($filename, 'r');
        
        if (!$file) {
            return false;
        }

	    $fstat = fstat($file);
            $size = $fstat['size'];
            echo "size = $size" . PHP_EOL;
	    if ( $size == 0 && file_exists($filename) ) {
		echo "unlink $filename" . PHP_EOL;
		unlink($filename);
		return false;
            }
        
        while ( $line = fgets($file) ) {
            //echo 'line: ' . $line . PHP_EOL;
            $line = trim(str_replace(array("\r\n", "\n"), '', $line));

            if ( isset($line[0]) && $line[0] == ';' ) {
                continue; // skip comment
            }

            $lineParser = $this->_manager->getLineParser();
            $res = $lineParser->parseCSVLine($line, $lastMachineDataRec);

            if ($res) {
                $this->parsedRows[] = $lineParser;
                $lastMachineDataRec = $lineParser;
            } else {
                $this->errors []= $lineParser->errors;
            }
        }

        fclose($file);

        //$c = count($this->parsedRows); echo "count parsed rows = $c"  . PHP_EOL; die;

        return true;
    }

    protected function generateSql () {

        if (count($this->parsedRows) == 0) {
            return false;
        }

        //$c = count($this->parsedRows); echo "count parsed rows = $c"  . PHP_EOL; die;

        // Join dublicates
        reset($this->parsedRows);
        $parsedRowPrev = next($this->parsedRows);
        foreach ($this->parsedRows as $i => $parsedRow) {

            $isIdentityRecords = MachineData::isIdentityRecords($parsedRowPrev, $parsedRow, true);

            if ($isIdentityRecords) {
                $parsedRowPrev->dt = $parsedRow->dt;
                //$duration = (strtotime($parsedRow->dt) - strtotime($parsedRowPrev->dt));
                //$parsedRowPrev->duration += $duration;
                $parsedRowPrev->duration += $parsedRow->duration;
                unset($parsedRow, $this->parsedRows[$i]);
            } else {
                $parsedRowPrev = $parsedRow;
            }
        }

//        foreach ($this->parsedRows as $parsedRow) {
//            $isAr = intval($parsedRow->id) > 0 ? 'true' : 'false';
//            echo "!!Parsed row: {$parsedRow->dt} - {$parsedRow->duration} (is AR: $isAr)" . PHP_EOL;
//        }
        //$c = count($this->parsedRows); echo "count parsed rows = $c"  . PHP_EOL; die;

        $sqlHeader = $this->_manager->getSqlInsertPart();
        $sqlBody = '';
        // Prepare sql for insert
        foreach ($this->parsedRows as $parsedRow) {
            if (intval($parsedRow->id) == 0) {
                $sqlBody_ = $parsedRow->getSqlPart();

                if ( trim($sqlBody_) != '' ) {
                    $sqlBody .= PHP_EOL . $sqlBody_ . ',';
                }
            }
        }
        $sqlBody = rtrim($sqlBody, ',');

        if (trim($sqlBody) != '') {
            $sql = $sqlHeader . $sqlBody . PHP_EOL ; //. ' ON DUPLICATE KEY UPDATE dt = dt' . PHP_EOL ;
            Yii::log("$sql", 'info', __METHOD__);
        } else {
            $sql = '';
        }

        //die($sql);

        return $sql;
    }

    private function getLastMachineDataRec($dt) {
        $machineDataAR = new MachineData();

        $criteria = new CDbCriteria();
        $criteria->addCondition('machine_id = :machine_id');
        $criteria->addCondition('dt >= :dt');
        $criteria->params = array(
            ':machine_id' => $this->_machine->id,
            ':dt' => $dt
        );
        $criteria->order = 'dt desc';
        $criteria->limit = 1;

        $rec = $machineDataAR->find($criteria);

        return $rec;
    }

}