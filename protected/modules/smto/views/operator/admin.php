<?php
$this->breadcrumbs=array(
	'Операторы'=>array('index'),
	'Управление',
);

$this->menu=array(
	array('label'=>'Список операторов', 'url'=>array('index')),
	array('label'=>'Добавить оператора', 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('operator-grid', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Управление операторами</h1>

<?php echo CHtml::link('Расширенный поиск','#',array('class'=>'search-button')); ?>
<div class="search-form" style="display:none">
<?php $this->renderPartial('_search',array(
	'model'=>$model,
)); ?>
</div><!-- search-form -->

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'operator-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'id',
		'c1',
		'c2',
		'c3',
		'full_name',
		'phone',
		array(
			'class'=>'CButtonColumn',
		),
	),
)); ?>
