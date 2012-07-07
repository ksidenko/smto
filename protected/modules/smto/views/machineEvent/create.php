<?php
$this->breadcrumbs=array(
	'События станков'=>array('admin'),
	'Создать',
);

$this->menu=array(
	array('label'=>'Список событий', 'url'=>array('index')),
	array('label'=>'Управление событиями', 'url'=>array('admin')),
);
?>

<h1>Создать событие</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>