<?php
$this->breadcrumbs=array(
	'Список групп станков',
);

$this->menu=array(
	array('label'=>'Создать группу', 'url'=>array('create')),
	array('label'=>'Управление группами ', 'url'=>array('admin')),
);
?>

<h1>Список групп станков</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
