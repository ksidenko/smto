<?php
$this->breadcrumbs=array(
	'События станков',
);

$this->menu=array(
	array('label'=>'Создать событие', 'url'=>array('create')),
	array('label'=>'Управление событиями', 'url'=>array('admin')),
);
?>

<h1>События со станков</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
