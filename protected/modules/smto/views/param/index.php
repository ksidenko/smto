<?php
$this->breadcrumbs=array(
	'Параметры',
);

$this->menu=array(
    array('label'=>'Создать параметр', 'url'=>array('create')),
    array('label'=>'Управление параметрами', 'url'=>array('admin')),
);
?>

<h1>Параметры</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
