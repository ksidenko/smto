<?php
$this->breadcrumbs=array(
	'Amplitudes'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List Amplitude', 'url'=>array('index')),
	array('label'=>'Create Amplitude', 'url'=>array('create')),
	array('label'=>'View Amplitude', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage Amplitude', 'url'=>array('admin')),
);
?>

<h1>Update Amplitude <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>