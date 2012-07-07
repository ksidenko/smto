<?php
$this->breadcrumbs=array(
	'События станков'=>array('admin'),
	'Управление событиями',
);

$this->menu=array(
	array('label'=>'Список событий', 'url'=>array('index')),
	array('label'=>'Создать событие', 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('machine-event-grid', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Управление событиями станков</h1>

<?php echo CHtml::link('Подробный поиск','#',array('class'=>'search-button')); ?>
<div class="search-form" style="display:none">
<?php $this->renderPartial('_search',array(
	'model'=>$model,
)); ?>
</div><!-- search-form -->

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'machine-event-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'id',
		'code',
		'name',
		'descr',
		//'color',
        array(
            'type'=>'raw',
            'name' => 'color',
            'value' => '\'<span style="width: 70px; background-color: #\' . CHtml::encode(ltrim($data->color,\'#\')) . \'" > \' . CHtml::encode($data->color) . \'</span>\''
        ),
		array(
			'class'=>'CButtonColumn',
		),
	),
)); ?>
