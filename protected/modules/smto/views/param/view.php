<?php
$this->breadcrumbs=array(
	'Параметры'=>array('index'),
	$model->id,
);

$this->menu=array(
    array('label'=>'Список параметров', 'url'=>array('index')),
    array('label'=>'Создать параметр', 'url'=>array('create')),
    array('label'=>'Редактировать параметр', 'url'=>array('update', 'id'=>$model->id)),
    array('label'=>'Удалить', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
    array('label'=>'Управление параметрами', 'url'=>array('admin')),
);

?>

<h1>Просмотр параметра #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'key',
		'value',
		'descr',
		'stable',
	),
)); ?>
