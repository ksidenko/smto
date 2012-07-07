<?php
$this->breadcrumbs=array(
	'Timetables',
);

$this->menu=array(
	array('label'=>'Create Timetable', 'url'=>array('create')),
	array('label'=>'Manage Timetable', 'url'=>array('admin')),
);
?>

<h1>Timetables</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
