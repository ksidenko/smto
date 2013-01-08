<?php
$this->breadcrumbs=array(
	'Параметры'=>array('index'),
	'Управление',
);

$this->menu=array(
	array('label'=>'Список параметров', 'url'=>array('index')),
	array('label'=>'Управление параметрами', 'url'=>array('admin')),
);
?>

<h1>Создать параметр</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>