<?php
$this->breadcrumbs=array(
	'События станков'=>array('admin'),
	$model->name=>array('view','id'=>$model->id),
	'Редактировать',
);

$this->menu=array(
	array('label'=>'Список событий', 'url'=>array('index')),
	array('label'=>'Создать событие', 'url'=>array('create')),
	array('label'=>'Просмотр события', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Управление событиями', 'url'=>array('admin')),
);
?>

<h1>Редактирование события <?php echo $model->name; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>