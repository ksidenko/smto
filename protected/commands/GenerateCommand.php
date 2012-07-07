<?php

class GenerateCommand extends CConsoleCommand {

    public function actionIndex($machineId = 1, $mac = null, $deltaSec = 2, $countFiles = 1, $countLines = 50, $formatVersion = '2.0') {
        echo 'run ' .  __METHOD__ . ' command' . PHP_EOL;

        //Yii::import('application.modules.smto.models.*');
        $dir = YiiBase::getPathOfAlias('application.runtime.import.machine_data');

        if($mac){
            $machine = Machine::model()->findByAttributes(array('mac' => $mac));
            $machineId = $machine->id;
        } else {
            $machine = Machine::model()->findByPk($machineId);
            if ($machine == false){
                echo 'Unknown machine id!' . PHP_EOL;
                exit;
            }
            $mac = $machine['mac'];
        }
        echo "machineId = $machineId, mac = $mac" . PHP_EOL;

        $dt_start = date(DATE_ATOM, strtotime(date(DATE_ATOM) . " -10 seconds"));
        $number=0;
        for ($counter=0; $counter<$countFiles; $counter++) {
            echo '.';
            $filename = 'pw' . $mac . '_' . uniqid('random_') . '.dat';
            $fd = fopen($dir . '/' . $filename, 'w+');

            $dt_file = date("d.m.Y,H:i:s", strtotime($dt_start . " +" . ($counter*$countLines*$deltaSec) . " seconds"));

            for($i = 0; $i< $countLines ; $i++) {
                if ($i%10 == 0) {
                    fwrite($fd, ";P" . PHP_EOL);
                }

                $dt = date("d.m.Y,H:i:s", strtotime($dt_file . " +" . ( $i * $deltaSec) . " seconds"));

                echo $dt . PHP_EOL;
                
                $da_max1 = 974 + rand(0,10);
                $da_max2 = 974 + rand(0,10);
                $da_max3 = 974 + rand(0,10);
                $da_max4 = 974 + rand(0,10);

                $da_avg1 = 974 + rand(0,10);
                $da_avg2 = 750 + rand(0,10);
                $da_avg3 = 466 + rand(0,10);
                $da_avg4 = 723 + rand(0,10);

                $dd1 = (rand(0,1) > 0.5) ? 1 : 0;
                $dd2 = (rand(0,1) > 0.5) ? 1 : 0;
                $dd3 = (rand(0,1) > 0.5) ? 1 : 0;
                $dd4 = (rand(0,1) > 0.5) ? 1 : 0;

                $dd_change1 = (rand(0,1) > 0.5) ? 1 : 0;
                $dd_change2 = (rand(0,1) > 0.5) ? 1 : 0;
                $dd_change3 = (rand(0,1) > 0.5) ? 1 : 0;
                $dd_change4 = (rand(0,1) > 0.5) ? 1 : 0;

                $state = rand(0,3);

                $fkey_last = ($state <= 1 ? rand(1,16) : 0);
                $fkey_all = 0;
                $flags = 0;

                $operatorInfo = Operator::model()->findBySql('select * from operator order by rand() limit 1');
                $c1 = $operatorInfo->c1;
                $c2 = $operatorInfo->c2;
                $c3 = $operatorInfo->c3;
                fwrite($fd,"D,$mac,$number, $dt, $da_max1,$da_max2,$da_max3,$da_max4, $da_avg1,$da_avg2,$da_avg3,$da_avg4, $dd1,$dd2,$dd3,$dd4, $dd_change1,$dd_change2,$dd_change3,$dd_change4, $state,$fkey_last, $fkey_all,$flags, $c1,$c2,$c3" . PHP_EOL);
                $number++;
                if($number > 1000) $number = 0;
            }
            fclose($fd);
        }
        echo PHP_EOL;
        echo $dir . PHP_EOL;
    }
}
