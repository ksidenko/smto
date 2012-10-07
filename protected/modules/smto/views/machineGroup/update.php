<?php
$this->breadcrumbs=array(
	'Список групп'=>array('index'),
	$model->name=>array('view','id'=>$model->id),
	'Редактировать',
);

$this->menu=array(
	array('label'=>'Список групп', 'url'=>array('index')),
	array('label'=>'Создать группу', 'url'=>array('create')),
	array('label'=>'Просмотр группы', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Управление группами ', 'url'=>array('admin')),
);
?>

<h1>Редактировать группу <?php echo $model->name; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>