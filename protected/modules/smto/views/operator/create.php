<?php
$this->breadcrumbs=array(
	'Операторы'=>array('index'),
	'Добавить',
);

$this->menu=array(
	array('label'=>'Список операторов', 'url'=>array('index')),
	array('label'=>'Управление операторами', 'url'=>array('admin')),
);
?>

<h1>Добавить оператора</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>