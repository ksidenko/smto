<?php
$this->breadcrumbs=array(
	'Цвета событий',
);

$this->menu=array(
	//array('label'=>'Create EventColor', 'url'=>array('create')),
	array('label'=>'Управление цветами', 'url'=>array('admin')),
);
?>

<h1>Цвета событий</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
