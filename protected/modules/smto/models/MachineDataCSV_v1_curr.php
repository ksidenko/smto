<?php

/**
 * Анализ строки файла CSV в старом формате
 *
 */

class MachineDataCSV_v1_curr extends MachineDataCSV {
    public $version = '1.0';

    private $lineColumnCounts = 30;

    // in fields
    public $number = null;
    public $dt = null;
    public $duration = null;
    public $mac = null;

    public $da_max1 = null;

    public $operator_last_fkey = null;
    
    public $state = null;

    public function init() {
        parent::init();
    }

    static public function getSqlInsertPart () {
        return '';
    }

 /*
1. O - идентификатор записи - всегда 'O' ;
2. 4 - номер станка (stanokNumber);
3. 4390 - время записи в файл в виде числа секунд, прошедших с начала текущих суток по местному времени;
4. 08.02.2013 - дата записи в файл;
5. 01:13:10 - время записи в файл;

6. 2 - состояние опроса станка (stanokStatus). 2 - опрос идет, другое значение - станок не отвечает
7. 4 - состояние станка ((stanokDigitalValue>>16)&0x07). 1 - работа, 4 - простой, другое значение - авария
8. 0 - причина простоя ((stanokDigitalValue>>8)&0xff). 0 - неизвестная причина (необоснованный простой)

9. 0 - (dummy1)
10. 12664664 - (stanokFilename)
11. 20 - (stanokPeriod)
12. 0 - (stanokPeriodCnt)
13. 0 - (stanokErrors)
14. 15625172 - (stanokSocket)
15. 15625324 - (stanokWinSocket)
16. 0 - (stanokIP)
17. 2 - (stanokStatus)
18. 0 - (stanokName)
19. 1 - (stanokBox)
20. 0 - (stanokAnalogValue)
21. 262144 - (stanokDigitalValue)
22. 0 - (stanokOperatorName)
23. 0 - (stanokOperatorCard1)
24. 0 - (StanokStr.stanokOperatorCard2)

Далее идут текстовые поля, в кавычках, в кодировке Windows CP-1251.

25. "Bystronyc" - (stanokNameTxt);
26. "Лазерная резка с ЧПУ" - (stanokTypeTxt);
27. "" - (stanokOperatorTxt);
28. "10.128.132.203" - (stanokIPTxt);
29. "                               " - (stanokButtonTxt) - кнопка, причина простоя

30. D7836FE3 - шестнадцатеричное 32-битное число - CRC32 предыдущей части строки, начиная с 'O' и по последнюю запятую включительно.
           Пробелы также участвуют в подсчете CRC32.
*/

    public function parseCSVLine($line, &$lastMachineDataRec = null) {

        $this->init();

        $arr = explode($this->separator, $line);

        if ( count($arr) != $this->lineColumnCounts ) {
            $errors = 'Bad column count in line: ' . count($arr);
            return false;
        }

        $this->machineInfo = Machine::getRecByMAC($this->mac);

        array_shift($arr);array_shift($arr);array_shift($arr);

        $date = trim(array_shift($arr));
        $time = trim(array_shift($arr));
        $this->dt = date('Y/m/d', strtotime($date)) . ' ' . $time;

        $isMachineAvailable = intval(trim(array_shift($arr)));
        $this->isMachineAvailable = ($isMachineAvailable == 2);

        $isAlarm = false;
        $state = intval(trim(array_shift($arr)));
        if ($state == 1) { //state work
            $this->state = MachineState::STATE_MACHINE_WORK;
        } else if ($state == 4) { // state on
            $this->state = MachineState::STATE_MACHINE_ON;
        } else { //state off, event alarm
            $isAlarm = true;
            $this->state = MachineState::STATE_MACHINE_OFF;

            $event = MachineEvent::model()->cache(600)->findByAttributes(array('code' => 'crash'));
            $fkeys = $this->machineInfo->cache(600)->fkey(array('select' => 'number', 'condition' => 'machine_event_id = "'.$event->id.'"'));
            if ($fkeys) {
                $this->operator_last_fkey = $fkeys[0]->number;
            } else {
                $this->operator_last_fkey = 0;
                Yii::log("No event 'crash' for machine with mac: {$this->machineInfo->mac}", 'error', __METHOD__);
            }
        }

        $operator_last_fkey = intval(trim(array_shift($arr)));
        if ( !$isAlarm ) {
            $this->operator_last_fkey = $operator_last_fkey;
        }

        array_shift($arr);array_shift($arr);array_shift($arr);array_shift($arr);array_shift($arr);array_shift($arr);
        array_shift($arr);array_shift($arr);array_shift($arr);array_shift($arr);array_shift($arr);array_shift($arr);
        array_shift($arr);array_shift($arr);array_shift($arr);array_shift($arr);array_shift($arr);array_shift($arr);

        $full_name = $this->_string2Utf(str_replace('"', '', trim(array_shift($arr))));
        //$this->operatorInfo = Operator::model()->cache(600)->findByAttributes(array('full_name' => $full_name));
        $this->operatorInfo = Operator::model();
        $this->operatorInfo->full_name = $full_name;

        //echo $this->operatorInfo->full_name . '| ';

        array_shift($arr);

        $eventName = $this->_string2Utf(str_replace('"', '', trim(array_shift($arr))));

        if ($this->operator_last_fkey == 0 && $eventName != '') {
            //echo $eventName; die;
            $this->machineEventInfo = MachineEvent::model()->cache(600)->findByAttributes(array('name' => $eventName));
            if (!$this->machineEventInfo) {
                //echo $this->machineInfo->full_name . ' | ';

                $this->machineEventInfo = MachineEvent::model();
                $this->machineEventInfo->name = $eventName;
                $this->machineEventInfo->color = '#550000';
            }
        }

        return true;
    }

    function getSqlPart() {
        return '';
    }

    private function _string2Utf($str) {
        return mb_convert_encoding($str, 'UTF8', 'cp1251');
        //return $str;
    }
}
 
