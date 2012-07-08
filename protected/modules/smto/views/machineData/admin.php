<?php
$this->breadcrumbs=array(
	'Данные со станков',//=>array('index'),
	'Управление',
);

$this->menu=array(
	//array('label'=>'Список данных со станка', 'url'=>array('index')),
	//array('label'=>'Create', 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('machine-data-grid', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Управление данными со станков</h1>

<?php echo CHtml::link('Удалить все данные',Yii::App()->baseUrl . '/smto/task/CleanMachineData',array('confirm'=>'Внимание! Будут удалены ВСЕ данные со станков!', 'style'=>'color:red;display:block; padding-bottom: 30px')); ?>

<?php //echo CHtml::link('Расширенный поиск','#',array('class'=>'search-button')); ?>
<div class="search-form" style="display:none">
<?php $this->renderPartial('_search',array(
	'model'=>$model,
)); ?>
</div><!-- search-form -->

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'machine-data-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
    'ajaxUpdate'=>false,
	'columns'=>array(
        array(
            'name' => 'id',
            'htmlOptions' => array('width' => '20')
        ),
		//'number',
        array(
            'name' => 'dt',
            'htmlOptions' => array('width' => '130')
        ),
        array(
            'name' => 'duration',
            'htmlOptions' => array('width' => '20')
        ),
        array(
            'name' => 'mac',
            'htmlOptions' => array('width' => '120')
        ),
        //'machine_id',
        'machine.name',
//        array(
//            'name' => 'machine.name',
//            'filter'=>CHtml::listData(Machine::model()->findAll(), 'id', 'name'),
//            'value' => '$data->machine->name'
//        ),
        //'operator_id',
        'operator.full_name',
		/*

		'da_max1',
		'da_max2',
		'da_max3',
		'da_max4',
		'da_avg1',
		'da_avg2',
		'da_avg3',
		'da_avg4',
		'dd1',
		'dd2',
		'dd3',
		'dd4',
		'dd_change1',
		'dd_change2',
		'dd_change3',
		'dd_change4',
		'state',
		'operator_last_fkey',
		'fkey_all',
		'flags',
		*/
		array(
			'class'=>'CButtonColumn',
		),
	),
)); ?>
