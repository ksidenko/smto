<?php
$this->breadcrumbs=array(
	'Detectors',
);

$this->menu=array(
	array('label'=>'Create Detector', 'url'=>array('create')),
	array('label'=>'Manage Detector', 'url'=>array('admin')),
);
?>

<h1>Detectors</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
