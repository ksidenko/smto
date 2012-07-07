<?php
$this->breadcrumbs=array(
	'Операторы'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Редактировать',
);

$this->menu=array(
	array('label'=>'Список операторов', 'url'=>array('index')),
	array('label'=>'Добавить оператора', 'url'=>array('create')),
	array('label'=>'Просмотр оператора', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Управление операторами', 'url'=>array('admin')),
);
?>

<h1>Редактировать оператора <i><?php echo $model->full_name; ?></i></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>