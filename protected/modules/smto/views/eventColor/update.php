<?php
$this->breadcrumbs=array(
	'Цвета событий'=>array('index'),
	$model->name=>array('view','id'=>$model->id),
	'Редактировать',
);

$this->menu=array(
	array('label'=>'Список', 'url'=>array('index')),
	//array('label'=>'Create EventColor', 'url'=>array('create')),
	array('label'=>'Просмотр', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Управление', 'url'=>array('admin')),
);
?> 
<h1>Редактирования цвета события <i><?php echo $model->name; ?></i></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>