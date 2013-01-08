<?php
$this->breadcrumbs=array(
	'Параметры'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Редактировать',
);

$this->menu=array(
    array('label'=>'Список параметров', 'url'=>array('index')),
    array('label'=>'Создать параметр', 'url'=>array('create')),
	array('label'=>'Просмотр параметра', 'url'=>array('view', 'id'=>$model->id)),
    array('label'=>'Управление параметрами', 'url'=>array('admin')),
);
?>

<h1>Редактирование параметра #<?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>