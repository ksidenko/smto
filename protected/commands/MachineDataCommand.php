<?php

class MachineDataCommand extends CConsoleCommand {

    public function actionCheck() {
        //echo 'run check command' . PHP_EOL;

        //Yii::import('application.modules.smto.models.*');
        $tasks = TaskManager::getList();

//        $mac = '00BD3B330571';
//        $machine = Machine::model()->findByAttributes(array('mac' => $mac));
//        print_r(Machine::getRecByMAC($machine->mac)->name);

        //$oper = Operator::model()->findByPk(1);
        //print_r(Operator::getRecByCode($oper->c1, $oper->c2, $oper->c3)->full_name);

        //$dir = DbTable_Param::getParam('data_file_path');
        //$dir = YiiBase::getPathOfAlias('application.runtime.import.machine_data');
        $dir = Yii::app()->smto->machine_data_path;

        //die($dir);

        foreach($tasks as $task) {

            // reload task
            $task = Task::model()->findByPk($task->id);
            if (!in_array($task->status, array('progress') )) {
                $task->status = 'progress';
                $task->dt_check = new CDbExpression('NOW()');
                $task->pid = getmypid ();
                $task->save();

                $machineRec = Machine::model()->findByPk($task->machine_id);
                echo $dir . PHP_EOL . $machineRec['mac'] . PHP_EOL;
                $import = new MachineDataImport();
                $import->run($dir, $machineRec['mac'], 1);

                $task->status = 'end';
                $task->save();
                break;
            } else {
                //TODO: check, is process crashed?
            }
        }
    }

    public function actionImport($mac, $maxProcessDataFiles = 10) {

        $dir = Yii::app()->params['machine_data_path'];

        //$output = Helpers::scandir($dir, $exp="/^cr.*$/i");
        //$output = Helpers::scandirFast($dir,"", true, 2);
        //echo print_r($output, true) . PHP_EOL;die();

        $machineAR = Machine::getRecByMAC($mac);

        if (!$machineAR) {
            Yii::log("Machine with MAC: $mac is unknown!", 'warning', __METHOD__);
            Yii::app()->end();
        }
        echo $machineAR->mac . PHP_EOL;

        $import = new MachineDataImport();
        $import->run($dir, $machineAR->mac, $maxProcessDataFiles);

    }
}
