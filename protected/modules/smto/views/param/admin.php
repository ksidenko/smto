<?php
$this->breadcrumbs=array(
	'Params'=>array('index'),
	'Manage',
);

$this->menu=array(
	array('label'=>'List Param', 'url'=>array('index')),
	array('label'=>'Create Param', 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('param-grid', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Manage Params</h1>

<?php echo CHtml::link('Advanced Search','#',array('class'=>'search-button')); ?>
<div class="search-form" style="display:none">
<?php $this->renderPartial('_search',array(
	'model'=>$model,
)); ?>
</div><!-- search-form -->

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'param-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
    'ajaxUpdate'=>false,
	'columns'=>array(
		'id',
		'key',
		'value',
		'descr',
		array(
			'class'=>'CButtonColumn',
		),
	),
)); ?>
