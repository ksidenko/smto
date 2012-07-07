<?php
$this->breadcrumbs=array(
	'Machine Logs',
);

$this->menu=array(
	array('label'=>'Create MachineLog', 'url'=>array('create')),
	array('label'=>'Manage MachineLog', 'url'=>array('admin')),
);
?>

<h1>Machine Logs</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
