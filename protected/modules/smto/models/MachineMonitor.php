<?php
/**
 * Created by JetBrains PhpStorm.
 * User: kirill
 * Date: 1/15/13
 * Time: 10:16 PM
 * To change this template use File | Settings | File Templates.
 */
class MachineInfo {
    public $dummy1;
    public $stanokFilename;
    public $stanokPeriod;
    public $stanokPeriodCnt;
    public $stanokErrors;
    public $stanokSocket;
    public $stanokWinSocket;
    public $stanokIP;
    public $stanokStatus;
    public $stanokName;
    public $stanokBox;
    public $stanokNumber;
    public $stanokAnalogValue;
    public $stanokDigitalValue;
    public $stanokOperatorName;
    public $stanokOperatorCard1;
    public $stanokOperatorCard2;

    public $stanokNameTxt;
    public $stanokTypeTxt;
    public $stanokOperatorTxt;
    public $stanokIPTxt;
    public $stanokButtonTxt;
}

class MachineMonitor
{
    const IS_OK = 2;
    const opros_bad = 1;

    const mode_idle = 4;
    const mode_work = 1;
    const mode_avar = 2;


    public $serverIP;
    public $port;

    private $machineInfoArr = array();

//typedef struct
//{
//unsigned int dummy1;
//unsigned int stanokFilename;
//unsigned int stanokPeriod;
//unsigned int stanokPeriodCnt;
//unsigned int stanokErrors;
//unsigned int stanokSocket;
//unsigned int stanokWinSocket;
//unsigned int stanokIP;
//unsigned int stanokStatus;
//unsigned int stanokName;
//unsigned int stanokBox;
//unsigned int stanokNumber;
//unsigned int stanokAnalogValue;
//unsigned int stanokDigitalValue;
//unsigned int stanokOperatorName;
//unsigned int stanokOperatorCard1;
//unsigned int stanokOperatorCard2;
//char stanokNameTxt[32];
//char stanokTypeTxt[32];
//char stanokOperatorTxt[20];
//char stanokIPTxt[20];
//char stanokButtonTxt[32];
//
//} stanok;

    public function __construct($serverIP, $port = 80) {
        $this->serverIP = '10.128.131.9'; //$serverIP
        $this->port = 12942; //$port
    }

    public function run($machineMAC) {

//return false;;        
        $fp = stream_socket_client('tcp://'.$this->serverIP . ':' . $this->port, $errno, $errstr, 1);
	if (!$fp) {
    	    echo "$errstr ($errno)<br />\n";
        }
        //$line = fgets($fp, 10);
        //$s = date();
        //echo "!$line!";
        fclose($fp); 
     die();
        
        
        if (($sock = socket_create(AF_INET, SOCK_STREAM, SOL_TCP)) === false) {
	    echo "Не удалось выполнить socket_create(): причина: " . socket_strerror(socket_last_error()) . "\n";
        }
        //die('socket is created');
        
        if (socket_bind($sock, $this->serverIP, $this->port) === false) {
            echo "Не удалось выполнить socket_bind(): причина: " . socket_strerror(socket_last_error($sock)) . "\n";
	}
        echo 'bind'; die;
        
        if(!($fp = fsockopen($this->serverIP, $this->port, $errno, $errstr, 20))) {
            echo 'Could not connect to USPS! Error number: ' . $errno . '(' . $errstr . ')';
            return false;
        }
        echo 'socket is open!'; die;


        $machineInfo = Yii::app()->cache->get($this->_getCacheKey($machineMAC));
        if (!$machineInfo) {
            while(!feof($fp)) {
                $machineInfo = new MachineInfo();

                $machineInfo->dummy1 = $this->_read($fp);
                $machineInfo->stanokFilename = $this->_read($fp);
                $machineInfo->stanokPeriod = $this->_read($fp);
                $machineInfo->stanokPeriodCnt = $this->_read($fp);
                $machineInfo->stanokErrors = $this->_read($fp);
                $machineInfo->stanokSocket = $this->_read($fp);
                $machineInfo->stanokWinSocket = $this->_read($fp);
                $machineInfo->stanokIP = $this->_read($fp);
                $machineInfo->stanokStatus = $this->_read($fp);
                $machineInfo->stanokName = $this->_read($fp);
                $machineInfo->stanokBox = $this->_read($fp);
                $machineInfo->stanokNumber = $this->_read($fp);
                $machineInfo->stanokAnalogValue = $this->_read($fp);
                $machineInfo->stanokDigitalValue = $this->_read($fp);
                $machineInfo->stanokOperatorName = $this->_read($fp);
                $machineInfo->stanokOperatorCard1 = $this->_read($fp);
                $machineInfo->stanokOperatorCard2 = $this->_read($fp);

                $machineInfo->stanokNameTxt = $this->_read($fp);
                $machineInfo->stanokTypeTxt = $this->_read($fp);
                $machineInfo->stanokOperatorTxt = $this->_read($fp, 20);
                $machineInfo->stanokIPTxt = $this->_read($fp, 20);
                $machineInfo->stanokButtonTxt = $this->_read($fp);

                echo print_r($machineInfo, true);

                //$this->machineInfoArr[$machineInfo->stanokNumber] = $machineInfo;
                Yii::app()->cache->set($this->_getCacheKey($machineInfo->stanokNumber), $machineInfo, 60);
            }
        }
        $machineInfo = Yii::app()->cache->get($this->_getCacheKey($machineMAC));
        if (!$machineInfo) {
            return false;
        }

        //$machineInfo = $this->machineInfoArr [$machineMAC];

        $rec = false;

        if ($machineInfo->stanokStatus== self::IS_OK) {
            $rec = new MachineDataCSV_v1();

            $rec->da_max1 = $machineInfo->stanokAnalogValue;

            //Table->Cells[5][a+1]="Опрашивается";
            $k=($machineInfo->stanokDigitalValue>>16)&0x07;

            switch ($k)
            {
                case self::mode_idle:
                    $rec->state = MachineState::STATE_MACHINE_IDLE_RUN;

                    if (($machineInfo->stanokDigitalValue>>8)&0xff) {
                        $operatorLastFkey = $this->_string2Utf($machineInfo->stanokButtonTxt);
                        //todo
                        $rec->operator_last_fkey = $operatorLastFkey;
                    } else {
                        $eventName = "Необоснованный простой";
                        $rec->state = MachineState::STATE_MACHINE_ON;
                    }
                    break;

                case self::mode_work:
                    $rec->state = MachineState::STATE_MACHINE_WORK;
                    $eventName = "Работа";
                    break;

                default:
                    //$rec->operator_last_fkey = $operatorLastFkey;
                    //todo
                    $eventName = "Авария";
            };

            $operatorAR = Operator::model()->cache(600)->findByAttributes(array('full_name' => $this->_string2Utf($machineInfo->stanokOperatorTxt)));
            if ($operatorAR)
                $rec->operator_id = $operatorAR->id;
            else
                $rec->operator_id = 0;

        } else {
            $eventName = "Не отвечает";
        }

        print_r($rec);
        return $rec;
    }

    private function _read($fp, $length = 32) {
        return hexdec(fgets($fp, $length));
    }

    private function _getCacheKey ($machineMAC) {
        return $key = $this->serverIP . '_' . $this->port . '_' . $machineMAC;
    }


    private function _string2Utf($str) {
          return mb_convert_encoding($str, 'UTF8', 'cp1251');
    }
}









































