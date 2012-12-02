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
$this->menu []= array('label'=>'Управление группами станков', 'url'=>array('/smto/machineGroup/admin'));


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
        array(
            'type'=>'raw',
            'name' => 'ip',
            'value' => '$data->ip . ":". $data->port',
        ),
        //'port',
        //'local_port',
        //'pwd',
        'mac',
//        array(
//            'name'=>'group_id',
//            'header'=>'Группы',
//            'filter'=>MachineGroup::getListShort(),
//            'value'=>'$data->getGroupsText()',
//        ),
        //'s_values',
        //'reasons_timeout_table',
		array(
            'type'=>'raw',
            'name' => 'reachable',
            //'value' => 'if ( Helpers::checkHostReachable( "192.168.0.100", 80) !== false ) { return 1; } else { return 0; } ',
            'value' => '"<div class=\"machine_reachable_" . Helpers::checkHostReachable( $data->ip, $data->port, 1) . "\" >&nbsp;</div>"',
        ),
        
		array(
			'class'=>'CButtonColumn',
		),
	),
)); ?>
