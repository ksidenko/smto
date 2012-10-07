<?php
$this->breadcrumbs=array(
	'Группы станков'=>array('admin'),
	'Создать',
);

$this->menu=array(
	array('label'=>'Список групп', 'url'=>array('index')),
	array('label'=>'Управление станками', 'url'=>array('admin')),
);
?>

<h1>Создать группу станков</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>