<?php
$this->breadcrumbs = array();
$this->menu = array();

if (!$model->getIsTemplate()) {
    $this->breadcrumbs += array('Станки'=>array('admin'));
    $this->menu += array(
        array('label'=>'Список станков', 'url'=>array('index')),
        array('label'=>'Добавить станок', 'url'=>array('create')),
        array('label'=>'Управление шаблонами станков', 'url'=>array('admin', 'template' => 1)),
    );
} else {
    $this->breadcrumbs += array('Шаблоны станков'=>array('admin', 'template' => 1));
    $this->menu += array(
	    array('label'=>'Список шаблонов станков', 'url'=>array('index', 'template' => 1)),
        array('label'=>'Добавить шаблон станка', 'url'=>array('create', 'template' => 1)),
    );
}
$this->breadcrumbs += array('Управление');
$this->menu []= array('label'=>'Управление событиями станков', 'url'=>array('/smto/machineEvent/admin'));


Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('machine-grid', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<?php if(!$model->getIsTemplate()) {?>
    <h1>Управление станками</h1>
<?php } else { ?>
    <h1>Управление шаблонами станков</h1>
<?php } ?>



<?php echo CHtml::link('Подробный поиск','#',array('class'=>'search-button')); ?>
<div class="search-form" style="display:none">
<?php $this->renderPartial('_search',array(
	'model'=>$model,
)); ?>
</div><!-- search-form -->

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'machine-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'id',
		'name',
		'code',
		'ip',
        'port',
        'local_port',
        //'pwd',
        'mac',
        //'s_values',
        //'reasons_timeout_table',
//		array(
//            'type'=>'raw',
//            'name' => 'work_type',
//            //'value' => CHtml::activeDropDownList($data,"work_type",Machine::$work_type)
//            'value' => 'Machine::$work_type_list[$data->work_type]'
//        ),
        
		array(
			'class'=>'CButtonColumn',
		),
	),
)); ?>
