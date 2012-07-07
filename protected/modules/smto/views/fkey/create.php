<?php
$this->breadcrumbs=array(
	'Fkeys'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List fkey', 'url'=>array('index')),
	array('label'=>'Manage fkey', 'url'=>array('admin')),
);
?>

<h1>Create fkey</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>