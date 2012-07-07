<?php
$this->breadcrumbs=array(
	'Detectors'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Detector', 'url'=>array('index')),
	array('label'=>'Manage Detector', 'url'=>array('admin')),
);
?>

<h1>Create Detector</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>