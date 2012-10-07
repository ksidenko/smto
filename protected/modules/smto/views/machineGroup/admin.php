<?php
$this->breadcrumbs=array(
	'Группы станков'=>array('admin'),
	'Управление группами',
);

$this->menu=array(
	array('label'=>'Список групп', 'url'=>array('index')),
	array('label'=>'Создать группу', 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('machine-group-grid', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Управление группами</h1>

<?php echo CHtml::link('Подробный поиск','#',array('class'=>'search-button')); ?>
<div class="search-form" style="display:none">
<?php $this->renderPartial('_search',array(
	'model'=>$model,
)); ?>
</div><!-- search-form -->

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'machine-group-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'id',
		'name',
		array(
			'class'=>'CButtonColumn',
		),
	),
)); ?>
