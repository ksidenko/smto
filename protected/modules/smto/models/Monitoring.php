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

        $machinesAR = Machine::model()->cache(600)->findAll();
        $groups = array();

        foreach($machinesAR as $machineAR) {

            $cache = Yii::app()->cache->get(__METHOD__ . $machineAR->mac);
            if ($cache) {
                $output['machines'][$machineAR->id] = $cache;
                continue;
            }

            $machineDataFabric = new MachineDataManager('2.0');

            $isMachineAvailable = true;

	        $lineParser = null;
            if (!is_numeric($machineAR->mac)) { //format v2.0
                $filename = $path . 'cr' . $machineAR->mac . '.cdt';
                //echo $filename . '|';

                $lastModifyTime = @filemtime($filename);
                if ( !$lastModifyTime || $lastModifyTime < ( strtotime('now') + Yii::app()->getModule('smto')->max_last_time_modify ) ) {
                    $isMachineAvailable = false;
                }

                $fd = @fopen($filename, 'r');
                if (!$fd) {
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
                $lineParser = $machineDataFabric->getLineParser($machineAR->mac);
                $lineParser->parseCSVLine($line);
            } else { //format v1.0
                /*$machineMonitor = new MachineMonitor($machineAR->ip, $machineAR->port);
                $lineParser = $machineMonitor->run($machineAR->mac);
                echo '<pre>!!!' . print_r($lineParser, true) . '</pre>'; die;
                */
                  $cr = new CDBCriteria();
                  $cr->select = 'state, operator_id, operator_last_fkey';
                  $cr->condition = 'machine_id = '.$machineAR->id . ' and dt > "' . date('Y-m-d H:i:s', strtotime('- 10 minutes')) . '"';
                  $cr->limit = 1;
                  $cr->order = 'dt desc';
                  
                $md = MachineData::model();
                $lineParser = $md->find($cr);
                if (!$lineParser) {
                    $lineParser = new stdClass;
                    $lineParser->state = 0;
                    $lineParser->operator_id = 0;                    
                    $lineParser->operator_last_fkey = 0;                    
                } 
            }
                                                                        
            //echo '<pre>' . print_r($lineParser, true) . '</pre>'; die;

            $machineState = MachineState::getRec($lineParser->state);
            if (!$machineState) {
                $this->errors[] = 'Не корректный статус станка ('.$machineAR->full_name.'): ( ' . $lineParser->state . ' )';
                continue;
            }
	    
            if ($machineState->code == 'on') {
                $machineState->name = "Включен";
            }

            if ($machineState->code == 'on' && empty($lineParser->operator_last_fkey)) {
                $machineState->name = "Необосн-й простой";
            }
            
            $dataState = array(
                'code' => $machineState->code,
                'name' => $machineState->name,
                'color' => EventColor::getColorByCode('machine_' . $lineParser->state),
            );


            if ( !empty($lineParser->operator_id) ) {
                $operatorInfo = Operator::getRec($lineParser->operator_id);
                if (!$operatorInfo) {
                    $this->errors[] = 'Не корректный идентификатор оператора ('.$machineAR->full_name.'): ( ' . $lineParser->operator_id . ' )';
                    $operatorInfo = new stdClass();
                    $operatorInfo->full_name = 'Не авторизован';
                }
            } else {
                $operatorInfo = new stdClass();
                $operatorInfo->full_name = 'Не авторизован';
            }
            

            $operatorLastFkey = array();
            if ($lineParser->operator_last_fkey > 0) {
            
                //echo "{$lineParser->operator_last_fkey} | ";
                $fkey = $machineAR->cache(600)->fkey(array('condition' => 'number = ' . $lineParser->operator_last_fkey));
                $fkeyState = null;
                if ($fkey) {
                    $fkeyState = $fkey[0]->cache(600)->machine_event;
                }
                if (!empty($fkeyState->id)) {
                    $operatorLastFkey = array(
                        'code' => $fkeyState['code'],
                        'name' => $fkeyState['name'],
                        'color' => '#' . ltrim($fkeyState['color'], '#'),
                    );
                    if ($machineState->code == 'on') {
                        $machineState->name = $fkeyState['name'];
                        $dataState['name'] = $fkeyState['name'];
                    }
                } else {
                    $this->errors[] = 'Событие не определено для ' . $machineAR->full_name . ': ( ' . $lineParser->operator_last_fkey . ' )';
                }
            }
            $dataOperator = array(
                'full_name' => $operatorInfo->full_name,
            );

            $groupIds = array();
            foreach ($machineAR->cache(600)->groups as $group) {
                $groups[$group->id] = $group->name;
                $groupIds [] = $group->id;
            }

            $output['machines'][$machineAR->id] = array(
                'full_name' => $machineAR->full_name,
                'name' => $machineAR->name,
                'code' => $machineAR->code,
                'span_number' => $machineAR->span_number,
                'place_number' => $machineAR->place_number,
                'ip' => $machineAR->ip,
                'mac' => $machineAR->mac,
                'state' => $dataState,
                'operator_last_fkey' => $operatorLastFkey,
                'operator' => $dataOperator,
                'groups' => array_values($groupIds),
                'isMachineAvailable' => $isMachineAvailable,
            );

            Yii::app()->cache->set(__METHOD__ . $machineAR->mac, $output['machines'][$machineAR->id], 20);
        }

        $output['groups'] = $groups;

        //echo '<pre>' . print_r($output, true) . '</pre>'; die;

        return $output;
    }

}