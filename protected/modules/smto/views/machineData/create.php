<?php
$this->breadcrumbs=array(
	'Machine Logs'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List MachineLog', 'url'=>array('index')),
	array('label'=>'Manage MachineLog', 'url'=>array('admin')),
);
?>

<h1>Create MachineLog</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>