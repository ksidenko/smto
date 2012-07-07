<?php
$this->breadcrumbs=array(
	'Machine Logs'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List MachineLog', 'url'=>array('index')),
	array('label'=>'Create MachineLog', 'url'=>array('create')),
	array('label'=>'Update MachineLog', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete MachineLog', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage MachineLog', 'url'=>array('admin')),
);
?>

<h1>View MachineLog #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'number',
		'dt',
		'duration',
		'mac',
		'machine_id',
		'operator_id',
		'da_max1',
		'da_max2',
		'da_max3',
		'da_max4',
		'da_avg1',
		'da_avg2',
		'da_avg3',
		'da_avg4',
		'dd1',
		'dd2',
		'dd3',
		'dd4',
		'dd_change1',
		'dd_change2',
		'dd_change3',
		'dd_change4',
		'state',
		'fkey_last',
		'fkey_all',
		'flags',
	),
)); ?>
