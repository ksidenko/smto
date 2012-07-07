<?php
$this->breadcrumbs=array(
	'Machine Logs'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List MachineLog', 'url'=>array('index')),
	array('label'=>'Create MachineLog', 'url'=>array('create')),
	array('label'=>'View MachineLog', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage MachineLog', 'url'=>array('admin')),
);
?>

<h1>Update MachineLog <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>