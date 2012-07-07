<?php
$this->breadcrumbs=array(
	'Detectors'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List Detector', 'url'=>array('index')),
	array('label'=>'Create Detector', 'url'=>array('create')),
	array('label'=>'View Detector', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage Detector', 'url'=>array('admin')),
);
?>

<h1>Update Detector <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>