<?php

class TaskManager {
    
    public static function getTaskByMachineId($machineId){
        $cr = new CDbCriteria();
        $cr->select = '*';
        $cr->condition = 'machine_id = :machineId';
        $cr->params = array( ':machineId' => $machineId);
        $cr->order = 'dt_create DESC';
        $cr->limit = 1;

        return Task::model()->find($cr);
    }

    public static function getList(){
        $cr = new CDbCriteria();
        $cr->select = '*';
        //$cr->condition = 'status=:status';
        //$cr->params = array( ":status" => $status);
        $cr->order = 'dt_check ASC';

        return Task::model()->findAll($cr);
    }

//    public function add($taskStatus, $machineId) {
//        $ret = false;
//        if (!self::checkExists($taskStatus, $machineId)) {
//            $task = new Task;
//            $task->status = $taskStatus;
//            $task->machine_id = $machineId;
//
//            $ret =  $task->save();
//        }
//        return $ret;
//    }
//
//    public function checkExists($taskStatus, $machineId){
//
//        $cr = new CDbCriteria();
//        $cr->select = 'id';
//        $cr->condition = 'status = :status and machine_id = :machine_id';
//        $cr->params = array(':status' => $taskStatus, ':machine_id' => $machineId);
//        $cnt = Task::model()->count($cr);
//        return $cnt == 1;
//    }

    public function save($taskStatus, $machineId) {
        $cr = new CDbCriteria();
        $cr->select = '*';
        $cr->condition = 'machine_id = :machine_id';
        $cr->params = array(':machine_id' => $machineId);

        if (!($task = Task::model()->find($cr))) {
            $task = new Task;
        }

        $task->status = $taskStatus;
        $task->machine_id = $machineId;
        $task->save();
    }
}
?>