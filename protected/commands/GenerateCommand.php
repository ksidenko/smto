<?php

class GenerateCommand extends CConsoleCommand {

    public function actionIndex($machineId = 1, $mac = null, $deltaSec = 2, $countFiles = 1, $countLines = 50, $formatVersion = '2.0') {
        //Yii::import('application.modules.smto.models.*');

        if ($mac) {
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
        $dir = $machine->getMachineDataPath();

        exec("rm $dir/*.dat");
        //echo "machineId = $machineId, mac = $mac, dir = $dir" . PHP_EOL; die();

        $dt_start = date("Y-m-d\T17:00:00P");
        $number = 0;
        for ($counter=0; $counter<$countFiles; $counter++) {
            echo '.';
            $filename = 'pw' . $mac . '_' . $number . '_' . uniqid('random_') . '.dat';
            $fd = fopen($dir . '/' . $filename, 'w+');

            $dt_file = date("d.m.Y,H:i:s", strtotime($dt_start . " +" . ($counter * $countLines * $deltaSec) . " seconds"));

            for($i = 0; $i < $countLines ; $i++) {
                if ($i%10 == 0) {
                    fwrite($fd, ";P" . PHP_EOL);
                }

                $dt = date("d.m.Y,H:i:s", strtotime($dt_file . " +" . ( $i * $deltaSec ) . " seconds"));

                echo $dt . PHP_EOL;

                $randomize = false;

                $da_max1 = 0;
                $da_max2 = 0;
                $da_max3 = 0;
                $da_max4 = 0;

                $da_avg1 = 0;
                $da_avg2 = 0;
                $da_avg3 = 0;
                $da_avg4 = 0;

                $dd1 = 0;
                $dd2 = 0;
                $dd3 = 0;
                $dd4 = 0;

                $dd_change1 = 0;
                $dd_change2 = 0;
                $dd_change3 = 0;
                $dd_change4 = 0;

                if ($randomize) {
                    $da_max1 += rand(0,10);
                    $da_max2 += rand(0,10);
                    $da_max3 += rand(0,10);
                    $da_max4 += rand(0,10);

                    $da_avg1 += rand(0,10);
                    $da_avg2 += rand(0,10);
                    $da_avg3 += rand(0,10);
                    $da_avg4 += rand(0,10);

                    $dd1 += (rand(0,1) > 0.5) ? 1 : 0;
                    $dd2 += (rand(0,1) > 0.5) ? 1 : 0;
                    $dd3 += (rand(0,1) > 0.5) ? 1 : 0;
                    $dd4 += (rand(0,1) > 0.5) ? 1 : 0;

                    $dd_change1 += (rand(0,1) > 0.5) ? 1 : 0;
                    $dd_change2 += (rand(0,1) > 0.5) ? 1 : 0;
                    $dd_change3 += (rand(0,1) > 0.5) ? 1 : 0;
                    $dd_change4 += (rand(0,1) > 0.5) ? 1 : 0;
                }

                $t = strtotime($dt);
                if ( $t < strtotime(date('Y-m-d\T17:10:00P'))) {
                    $state = 0;
                    $da_avg1 = 0;
                    $dd1 = 0;
                } else if ( $t < strtotime(date('Y-m-d\T17:15:00P')) ) {
                    $state = 1;
                    $da_avg1 = 5;
                    $dd1 = 1;
                } else if ( $t < strtotime(date('Y-m-d\T17:20:00P')) ) {
                    $state = 2;
                    $da_avg1 = 40;
                    $dd1 = 1;
                } else if ( $t < strtotime(date('Y-m-d\T17:30:00P')) ) {
                    $state = 3;
                    $da_avg1 = 90;
                    $dd1 = 1;
                } else if ( $t < strtotime(date('Y-m-d\T17:40:00P')) ) {
                    $state = 1;
                    $da_avg1 = 7;
                    $dd1 = 1;
                } else if ( $t < strtotime(date('Y-m-d\T17:50:00P')) ) {
                    $state = 0;
                    $da_avg1 = 2;
                    $dd1 = 1;
                } else {
                    $state = 3;
                    $da_avg1 = 90;
                    $dd1 = 1;
                }

                $operator_last_fkey = ($state <= 1 ? 0 : 0);
                $fkey_all = 0;
                $flags = 0;

                //$operatorInfo = Operator::model()->findBySql('select * from operator order by rand() limit 1');
                $operatorInfo = Operator::model()->findBySql('select * from operator where id = 1');
                $c1 = $operatorInfo->c1;
                $c2 = $operatorInfo->c2;
                $c3 = $operatorInfo->c3;
                fwrite($fd,"D,$mac,$number, $dt, $da_max1,$da_max2,$da_max3,$da_max4, $da_avg1,$da_avg2,$da_avg3,$da_avg4, $dd1,$dd2,$dd3,$dd4, $dd_change1,$dd_change2,$dd_change3,$dd_change4, $state,$operator_last_fkey, $fkey_all,$flags, $c1,$c2,$c3" . PHP_EOL);
                $number++;
                if ($number > 1000) $number = 0;
            }
            fclose($fd);
        }
        echo PHP_EOL;
        echo $dir . PHP_EOL;
    }
}
