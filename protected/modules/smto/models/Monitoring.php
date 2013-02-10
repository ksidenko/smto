<?php
class Monitoring {

    private $errors = array();

    public function setErrors($errors)
    {
        $this->errors = $errors;
    }

    public function getErrors()
    {
        return $this->errors;
    }

    public function hasErrors()
    {
        return count($this->errors) > 0;
    }

    public function getMonitorData() {

        $output = array('machines' => array(), 'groups' => array());

        $path = Machine::getMachineDataPathCurr();

        $machinesAR = Machine::model()->cache(600)->real_records()->findAll();
        $groups = array();

        foreach($machinesAR as $machineAR) {

            $cache = Yii::app()->cache->get(__METHOD__ . $machineAR->mac);
            if ($cache) {
                $output['machines'][$machineAR->id] = $cache;
                continue;
            }

	        $lineParser = null;
            if (!is_numeric($machineAR->mac)) { //format v2.0
                $machineDataFabric = new MachineDataManager('2.0', $machineAR->mac);
                $filename = $path . 'cr' . $machineAR->mac . '.cdt';
                //echo $filename . '|';

                $lastModifyTime = @filemtime($filename);
                if ( !$lastModifyTime || $lastModifyTime < ( strtotime('now') + Yii::app()->getModule('smto')->max_last_time_modify ) ) {
                    $isMachineAvailable = false;
                }

                $fd = @fopen($filename, 'r');
                if (!$fd) {
                    if (Yii::app()->user->checkAccess('smto-MonitoringAdministrating')) {
                        $link = CHtml::link('Станок', array('machine/update', 'id'=>$machineAR->id));
                        $this->errors[] = "$link {$machineAR->full_name} ( $machineAR->mac, $machineAR->ip ) не доступен";
                    }
                    continue;
                }
                while ( $line = fgets($fd) ) {
                    if ( !isset($line[0]) || $line[0] != 'C' ) {
                        Yii::log("ignore line ($line)", 'info', __METHOD__);
                        continue;
                    }
                    break;
                }
                fclose($fd);
                $lineParser = $machineDataFabric->getLineParser();
                $lineParser->parseCSVLine($line);
                $lineParser->isMachineAvailable = $isMachineAvailable;
            } else { //format v1.0

                if (true) {
                    $machineDataFabric = new MachineDataManager('1.0', $machineAR->mac);

                    $c = $machineAR->place_number;
                    $c = str_repeat('0', (10 - strlen($c)) ) . $c;
                    $filename = $path . 'cro' .  $c . '.ddt';
                    //echo $filename . '|';

                    $lastModifyTime = @filemtime($filename);
                    if ( !$lastModifyTime || $lastModifyTime < ( strtotime('now') + Yii::app()->getModule('smto')->max_last_time_modify ) ) {
                        $isMachineAvailable = false;
                    }

                    $fd = @fopen($filename, 'r');
                    if (!$fd) {
                        if (Yii::app()->user->checkAccess('smto-MonitoringAdministrating')) {
                            $link = CHtml::link('Станок', array('machine/update', 'id'=>$machineAR->id));
                            $this->errors[] = "$link {$machineAR->full_name} ( $machineAR->mac, $machineAR->ip ) не доступен";
                        }
                        continue;
                    }
                    $line = fgets($fd);
                    fclose($fd);

                    $lineParser = $machineDataFabric->getLineParser(true);
                    $lineParser->parseCSVLine($line);

                    if ($lineParser->isMachineAvailable && !$isMachineAvailable) {
                        $lineParser->isMachineAvailable = false;
                    }
                } else {
                    // read last data from DB
                    $cr = new CDBCriteria();
                    $cr->select = 'state, operator_id, operator_last_fkey';
                    $cr->condition = 'machine_id = '.$machineAR->id . ' and dt > "' . date('Y-m-d H:i:s', strtotime('- 10 minutes')) . '"';
                    $cr->limit = 1;
                    $cr->order = 'dt desc';

                    $md = MachineData::model();
                    $lineParser = $md->find($cr);
                    if (!$lineParser) {
                        $lineParser = new MachineDataCSV_v1_curr();
                        $lineParser->state = 0;
                        $lineParser->operator_id = 0;
                        $lineParser->operator_last_fkey = 0;
                        $lineParser->isMachineAvailable = false;
                    }
                }
            }
            //echo '<pre>' . print_r($lineParser, true) . '</pre>'; die;

            $machineState = $lineParser->machineStateInfo;

            if (!$machineState) {
                $this->errors[] = 'Не корректный статус станка ('.$machineAR->full_name.'): ( ' . $lineParser->state . ' )';
                continue;
            }

            $machineStateInfo = array(
                'code' => $machineState->code,
                'name' => $machineState->name,
                'color' => $machineState->color,
            );

            $operatorInfo = $lineParser->operatorInfo;
            if (!$operatorInfo && !empty($lineParser->operator_id)) {
                $this->errors[] = 'Не корректный идентификатор оператора ('.$machineAR->full_name.'): ( ' . $lineParser->operator_id . ' )';
            }

            if (!$operatorInfo) {
                $operatorInfo = new stdClass();
                $operatorInfo->full_name = $operatorInfo->short_name = 'Не авторизован';
            }

            $operatorInfo = array(
                'short_name' => $operatorInfo->short_name,
                'full_name' => $operatorInfo->full_name,
            );

            $machineEventInfo = array();

            if ($lineParser->machineEventInfo) {
                $machineEventInfo = array(
                    'code' => $lineParser->machineEventInfo->code,
                    'name' => $lineParser->machineEventInfo->name,
                    'color' => '#' . ltrim($lineParser->machineEventInfo->color, '#'),
                );
            } else if ($lineParser->operator_last_fkey > 0) {
                $this->errors[] = 'Событие не определено для ' . $machineAR->full_name . ': ( ' . $lineParser->operator_last_fkey . ' )';
            }

            if ($machineState->code == 'on') { // станок включен
                if ( $lineParser->operator_last_fkey == 0 ) { // событие не указано
                    $machineStateInfo['name'] = "Необосн-й простой";
                } else if ( $lineParser->machineEventInfo ) { // событие указано
                    $machineStateInfo['name'] = $lineParser->machineEventInfo->name;
                }
            }

            $groupIds = array();
            foreach ($machineAR->cache(600)->groups as $group) {
                $groups[$group->id] = $group->name;
                $groupIds [] = $group->id;
            }

            //$lineParser->isMachineAvailable = true;
            $output['machines'][$machineAR->id] = array(
                'full_name' => $machineAR->full_name,
                'name' => $machineAR->name,
                'code' => ltrim($machineAR->code, '№'),
                'span_number' => ltrim($machineAR->span_number, '№'),
                'place_number' => $machineAR->place_number,
                'ip' => $machineAR->ip,
                'mac' => $machineAR->mac,
                'machineStateInfo' => $machineStateInfo,
                'machineEventInfo' => $machineEventInfo,
                'operatorInfo' => $operatorInfo,
                'groups' => array_values($groupIds),
                'isMachineAvailable' => $lineParser->isMachineAvailable,
            );

            Yii::app()->cache->set(__METHOD__ . $machineAR->mac, $output['machines'][$machineAR->id], 20);
        }

        $output['groups'] = $groups;

        //echo '<pre>' . print_r($output, true) . '</pre>'; die;

        return $output;
    }

}