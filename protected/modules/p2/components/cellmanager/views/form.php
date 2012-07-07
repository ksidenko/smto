<?php
$model = new P2Cell;
$model->attributes = $this->_params;

// collect ...Params
foreach(array("Request","Session","Cookie","Application","Module") AS $key) {
    $column  = $key."Param";
    if (isset($this->_params[$column])) {
        $model->$column = $this->_params[$column];
    }
}

$this->controller->renderPartial(
        'p2.views.p2Cell._form',
        array(
            'model'=>$model,
            'update'=>false)
);
?>
