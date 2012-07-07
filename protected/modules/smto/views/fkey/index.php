<?php
$this->breadcrumbs=array(
	'Fkeys',
);

$this->menu=array(
	array('label'=>'Create fkey', 'url'=>array('create')),
	array('label'=>'Manage fkey', 'url'=>array('admin')),
);
?>

<h1>Fkeys</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
