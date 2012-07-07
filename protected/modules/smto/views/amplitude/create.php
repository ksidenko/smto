<?php
$this->breadcrumbs=array(
	'Amplitudes'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Amplitude', 'url'=>array('index')),
	array('label'=>'Manage Amplitude', 'url'=>array('admin')),
);
?>

<h1>Create Amplitude</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>