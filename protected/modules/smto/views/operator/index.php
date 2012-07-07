<?php
$this->breadcrumbs=array(
	'Операторы',
);

$this->menu=array(
	array('label'=>'Добавить оператора', 'url'=>array('create')),
	array('label'=>'Управление операторами', 'url'=>array('admin')),
);
?>

<h1>Операторы</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
