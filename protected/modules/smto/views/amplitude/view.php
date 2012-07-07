<?php
$this->breadcrumbs=array(
	'Amplitudes'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List Amplitude', 'url'=>array('index')),
	array('label'=>'Create Amplitude', 'url'=>array('create')),
	array('label'=>'Update Amplitude', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete Amplitude', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Amplitude', 'url'=>array('admin')),
);
?>

<h1>View Amplitude #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'number',
		'machine_id',
		'value',
		'type',
		'rec_type',
	),
)); ?>
