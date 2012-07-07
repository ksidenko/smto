<?php
$this->breadcrumbs=array(
	'Event Colors'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List EventColor', 'url'=>array('index')),
	array('label'=>'Manage EventColor', 'url'=>array('admin')),
);
?>

<h1>Create EventColor</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>