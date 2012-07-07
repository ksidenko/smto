<?php
$this->breadcrumbs=array(
	'Timetables'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Timetable', 'url'=>array('index')),
	array('label'=>'Manage Timetable', 'url'=>array('admin')),
);
?>

<h1>Create Timetable</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>