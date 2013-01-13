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
            $machineDataFabric = new MachineDataManager('2.0');

            $filename = $path . 'cr' . $machineAR->mac . '.cdt';
            //echo $filename . '|';

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

            //echo '<pre>' . print_r($lineParser, true) . '</pre>'; die;

            $machineState = MachineState::getRec($lineParser->state);
            if (!$machineState) {
                $this->errors[] = 'Не корректный статус станка ('.$machineAR->full_name.'): ( ' . $lineParser->state . ' )';
                continue;
            }

            if ($machineState->code == 'on') {
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
            );
        }

        $output['groups'] = $groups;

        //echo '<pre>' . print_r($output, true) . '</pre>'; die;

        return $output;
    }

}