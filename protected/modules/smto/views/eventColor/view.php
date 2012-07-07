<?php
$this->breadcrumbs=array(
	'Цвета событий'=>array('index'),
	$model->name,
);

$this->menu=array(
	array('label'=>'Список', 'url'=>array('index')),
	//array('label'=>'Создать', 'url'=>array('create')),
	array('label'=>'Редактировать', 'url'=>array('update', 'id'=>$model->id)),
	//array('label'=>'Удалить', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Вы действительно хотите удалить запись?')),
	array('label'=>'Управление', 'url'=>array('admin')),
);
?>

<h1>Просмотр цвета события <i><?php echo $model->name; ?></i></h1>

<?php 
//$this->widget('zii.widgets.CDetailView', array(
//	'data'=>$model,
//	'attributes'=>array(
//		'id',
//		'code',
//		'name',
//		'color',
//	),
//)); 
echo $this->renderPartial('_view', array('data'=>$model)); 
?>
