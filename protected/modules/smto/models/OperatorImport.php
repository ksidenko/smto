<?php
/**
 * Created by JetBrains PhpStorm.
 * User: kirill
 * Date: 1/12/13
 * Time: 8:08 PM
 * To change this template use File | Settings | File Templates.
 */

class OperatorImport {
    private $filePath = '';
    private $_db = null;

    public $separator = ';';
    private $lineColumnCounts = 2;

    private $errors = array();


    public function __construct ($filePath) {
        $this->filePath = $filePath;
        $this->_db = Yii::app()->db;
    }


    public function addErrors($errors) {
        $errors = (array) $errors;

        foreach($errors as $error) {
            $this->errors []= $error;
        }
    }

    public function getErrors() {
        return $this->errors;
    }

    public function hasErrors() {
        return count($this->errors) > 0;
    }

    public function run () {
        $fd = @fopen($this->filePath, 'r');

        if (!$fd) {
            $this->addErrors( 'Incorrect file path: ' . $this->filePath );
            return false;
        }

        while ( $line = fgets($fd) ) {
            $line = mb_convert_encoding($line, 'UTF8', 'cp1251');
            $line = trim(str_replace(array("\r\n", "\n"), '', $line));
            //echo "$line" . PHP_EOL;
            $this->parseLine ($line);
        }

        // mark deleted old records
        $this->_db->createCommand('update operator set delete_dt = NOW() where create_dt < DATE_SUB(NOW(), INTERVAL 10 minute)')->execute();

        fclose($fd);
        return true;
    }

    private function parseLine ($line) {
        $arr = explode($this->separator, $line);

        if (count($arr) != $this->lineColumnCounts) {
            $this->addErrors('Incorrect columns count: ' . count($arr));
            return false;
        }

        $fullName = trim(array_shift($arr));
        $code = trim(array_shift($arr));
        Operator::codeExplode($code, $c1,$c2,$c3);

        $operatorAR = Operator::model();
        $rec = $operatorAR->getRecByCode($c1,$c2,$c3, false);

        if ($rec) {
            echo "Operator exists: $fullName" . PHP_EOL;
            unset($operatorAR);
            $operatorAR = $rec;
            $operatorAR->setIsNewRecord(false);
            $operatorAR->full_name = $fullName;
            $operatorAR->create_dt = new CDbExpression('NOW()');
            $operatorAR->delete_dt = new CDbExpression('NULL');
            $res = $operatorAR->save(true);
        } else {
            $operatorAR->full_name = $fullName;
            $operatorAR->c1 = $c1;
            $operatorAR->c2 = $c2;
            $operatorAR->c3 = $c3;
            $operatorAR->id = null;
            $operatorAR->create_dt = new CDbExpression('NOW()');
            $operatorAR->delete_dt = new CDbExpression('NULL');
            $operatorAR->setIsNewRecord(true);
            $res = $operatorAR->save(true);
            echo "save new AR: $fullName" . PHP_EOL;
        }

        if (!$res) {
            foreach($operatorAR->getErrors() as $error ) {
                $this->addErrors( $error );
            }
        }

        return true;
    }
}