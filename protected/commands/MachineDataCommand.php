<?php

class MachineDataCommand extends CConsoleCommand {

    public function actionCheck() {
        //Yii::import('application.modules.smto.models.*');

        //$oper = Operator::model()->findByPk(1);
        //print_r(Operator::getRecByCode($oper->c1, $oper->c2, $oper->c3)->full_name);

        $machines = Machine::model()->real_records()->cache(600)->findAll();
        //$cnt = count($machines);echo "$cnt".PHP_EOL;die;

        $dir = realpath(dirname(__FILE__ ) . '/../');
        //echo "$dir".PHP_EOL;die;
	
	shuffle($machines);
	
        foreach($machines as $machineAR) {
        
        
    	    $check = "ps ax | grep -v grep | grep -i 'MachineData import' | grep -v grep ";
            //echo "$check" . PHP_EOL;

            exec($check, $output);

	    $c = count($output);

	    //if ( $c > 2 ) {
        //	echo 'Machines too match!' . PHP_EOL;
        //	return false;
          //  }
            
        
        
            $check = "ps ax | grep -v grep | grep -i 'MachineData import --mac=" . $machineAR->mac . "'";
            //echo "$check" . PHP_EOL;
            $output = array();
            exec($check, $output);

	    $c = count($output);
            if ($c > 0) {
                echo "Machine with MAC {$machineAR->mac} is all ready processing..." . PHP_EOL;
                continue;
            } 
            

            $cmd = array();

            $cmd[] = "$dir/yiic";
            $cmd[] = "MachineData import";
            $cmd[] = "--mac=" . $machineAR->mac;
            $cmd[] = "--maxProcessDataFiles=" . 200;
            $cmd[] = "> /dev/null 2>/dev/null &";
            $cmd = implode(' ', $cmd);

            //echo "$cmd" . PHP_EOL;

            exec($cmd);
        }
    }

    public function actionImport($mac, $maxProcessDataFiles = 10) {

        $machineAR = Machine::getRecByMAC($mac);

        if (!$machineAR) {
            Yii::log("Machine with MAC: $mac is unknown!", 'warning', __METHOD__);
            Yii::app()->end();
        }

        $import = new MachineDataImport();
        $dir = $machineAR->getMachineDataPath();

        //$output = Helpers::scandir($dir, $exp="/^cr.*$/i");
        //$output = Helpers::scandirFast($dir,"", true, 2);
        //echo print_r($output, true) . PHP_EOL; die();

        $import->run($dir, $machineAR->mac, $maxProcessDataFiles);
    }
}
