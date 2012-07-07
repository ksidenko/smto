<?php
$this->breadcrumbs=array(
	'Detectors'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List Detector', 'url'=>array('index')),
	array('label'=>'Create Detector', 'url'=>array('create')),
	array('label'=>'Update Detector', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete Detector', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Detector', 'url'=>array('admin')),
);
?>

<h1>View Detector #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'number',
		'machine_id',
		'status',
		'type',
		'rec_type',
	),
)); ?>
