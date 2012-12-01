<?php
class Monitoring {

    public function getMonitorData() {

        $output = array();

        $path = Machine::getMachineDataPathCurr();

        $machinesAR = Machine::model()->findAll();
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

            //$lineParser->state = 2;
            //print_r($lineParser); die;
            //echo '<pre>' . print_r($lineParser, true) . '</pre>'; die;

            $machineState = MachineState::getRec($lineParser->state);
            $dataState = array(
                'code' => $machineState->code,
                'name' => $machineState->name,
                'color' => EventColor::getColorByCode('machine_' . $lineParser->state),
            );

            $groupIds = array();
            foreach ($machineAR->groups as $group) {
                $groups[$group->id] = $group->name;
                $groupIds [] = $group->id;
            }

            $output['machines'][$machineAR->id] = array(
                'name' => $machineAR->name,
                'ip' => $machineAR->ip,
                'mac' => $machineAR->mac,
                'state' => $dataState,
                'groups' => array_values($groupIds),
            );
        }

        $output['groups'] = $groups;

        //echo '<pre>' . print_r($output, true) . '</pre>'; die;

        return $output;
    }

}