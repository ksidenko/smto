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
                    continue;
                }
                break;
            }
            fclose($fd);
            $lineParser = $machineDataFabric->getLineParser($machineAR->mac);
            $lineParser->parseCSVLine($line);

            // debug info
            //$lineParser->state = 2; $lineParser->operatorId = '222';

            //echo '<pre>' . print_r($lineParser, true) . '</pre>'; die;

            $machineState = MachineState::getRec($lineParser->state);
            if (!$machineState) {
                $this->errors[] = 'Не корректный статус станка (mac: '.$machineAR->mac.'): ' . $lineParser->state;
                continue;
            }

            $dataState = array(
                'code' => $machineState->code,
                'name' => $machineState->name,
                'color' => EventColor::getColorByCode('machine_' . $lineParser->state),
            );

            if ( !empty($lineParser->operator_id) ) {
                $operatorInfo = Operator::getRec($lineParser->operator_id);
                if (!$operatorInfo) {
                    $this->errors[] = 'Не корректный идентификатор оператора (mac: '.$machineAR->mac.'): ' . $lineParser->operator_id;
                    $operatorInfo = new stdClass();
                    $operatorInfo->full_name = 'Не авторизован';
                }
            } else {
                $operatorInfo = new stdClass();
                $operatorInfo->full_name = 'Не авторизован';
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
                'operator' => $dataOperator,
                'groups' => array_values($groupIds),
            );
        }

        $output['groups'] = $groups;

        //echo '<pre>' . print_r($output, true) . '</pre>'; die;

        return $output;
    }

}