<?php
$this->breadcrumbs=array(
	'Операторы'=>array('index'),
	$model->full_name,
);

$this->menu=array(
	array('label'=>'Список операторов', 'url'=>array('index')),
	array('label'=>'Добавить оператора', 'url'=>array('create')),
	array('label'=>'Редактировать оператора', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Удалить оператора', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Управление операторами', 'url'=>array('admin')),
);
?>

<h1>Просмотр оператора <i><?php echo $model->full_name; ?></i></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'c1',
		'c2',
		'c3',
		'full_name',
		'phone',
	),
)); ?>
