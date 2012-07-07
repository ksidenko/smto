<?php
$this->breadcrumbs=array(
	'События станков'=>array('admin'),
	$model->name,
);

$this->menu=array(

    array('label'=>'Список событий', 'url'=>array('index')),
	array('label'=>'Создать событие', 'url'=>array('create')),
	array('label'=>'Редактировать событие', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Удалить событие', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Вы действительно хотите удалить событие?')),
	array('label'=>'Управление событиями', 'url'=>array('admin')),
);
?>

<h1>Просмотр события <?php echo $model->name; ?></h1>

<?php
//$this->widget('zii.widgets.CDetailView', array(
//	'data'=>$model,
//	'attributes'=>array(
//		'id',
//		'code',
//		'name',
//		'descr',
//		'color',
//	),
//));
echo $this->renderPartial('_view', array('data'=>$model));
?>
