<?php
$this->breadcrumbs=array(
	'Params'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List Param', 'url'=>array('index')),
	array('label'=>'Create Param', 'url'=>array('create')),
	array('label'=>'View Param', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage Param', 'url'=>array('admin')),
);
?>

<h1>Update Param <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>