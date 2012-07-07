<?php
$this->breadcrumbs=array(
	'Цвета событий'=>array('index'),
	'Manage',
);

$this->menu=array(
	array('label'=>'Список событий', 'url'=>array('index')),
	//array('label'=>'Create EventColor', 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('event-color-grid', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Управление цветами событий</h1>

<?php echo CHtml::link('Расширенный поиск','#',array('class'=>'search-button')); ?>
<div class="search-form" style="display:none">
<?php $this->renderPartial('_search',array(
	'model'=>$model,
)); ?>
</div><!-- search-form -->

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'event-color-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'id',
		'code',
		'name',
		array( 
            'type'=>'raw',
            'name' => 'color', 
            'value' => '\'<span style="width: 70px; background-color: #\' . CHtml::encode($data->color) . \'" > \' . CHtml::encode($data->color) . \'</span>\''
        ),
		array(
			'class'=>'CButtonColumn',
		),
	),
)); ?>
