<?php
$this->breadcrumbs=array(
	'Amplitudes',
);

$this->menu=array(
	array('label'=>'Create Amplitude', 'url'=>array('create')),
	array('label'=>'Manage Amplitude', 'url'=>array('admin')),
);
?>

<h1>Amplitudes</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
