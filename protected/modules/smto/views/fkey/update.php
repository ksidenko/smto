<?php
$this->breadcrumbs=array(
	'Fkeys'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List fkey', 'url'=>array('index')),
	array('label'=>'Create fkey', 'url'=>array('create')),
	array('label'=>'View fkey', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage fkey', 'url'=>array('admin')),
);
?>

<h1>Update fkey <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>