<?php
$this->breadcrumbs=array(
	'Список групп'=>array('index'),
	$model->name,
);

$this->menu=array(
	array('label'=>'Список групп', 'url'=>array('index')),
	array('label'=>'Создать группу', 'url'=>array('create')),
	array('label'=>'Редактировать группу', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Удалить группу', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Вы действительно хотите удалить событие?')),
	array('label'=>'Управление группами ', 'url'=>array('admin')),
);
?>

<h1>Просмотр группы <?php echo $model->name; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'name',
	),
)); ?>
