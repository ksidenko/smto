<?php
$this->breadcrumbs=array();
$this->menu = array();

if (!$model->getIsTemplate()) {
    $this->breadcrumbs += array('Станки'=>array('admin'));
    $this->menu []= array('label'=>'Добавить станок', 'url'=>array('create'));
    $this->menu []= array('label'=>'Редактировать станок', 'url'=>array('update', 'id'=>$model->id));
    $this->menu []= array('label'=>'Удалить станок', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Вы действительно хотите удалить станок?'));
    $this->menu []= array('label'=>'Управление станками', 'url'=>array('admin'));
} else {
    $this->breadcrumbs += array('Шаблоны станков'=>array('admin', 'template' => 1));
    $this->menu []= array('label'=>'Добавить шаблон станка', 'url'=>array('create', 'template' => 1));
    $this->menu []= array('label'=>'Редактировать шаблон станка', 'url'=>array('update', 'id'=>$model->id, 'template' => 1));
    $this->menu []= array('label'=>'Удалить шаблон станка', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Вы действительно хотите удалить шаблон станка?', 'template' => 1));
    $this->menu []= array('label'=>'Управление шаблонами станков', 'url'=>array('admin', 'template' => 1));
}
$this->breadcrumbs []= $model->name;
$this->menu []= array('label'=>'Управление событиями станков', 'url'=>array('/smto/machineEvent/admin'));

?>

<?php if(!$model->getIsTemplate()) {?>
    <h1>Просмотр станка <?php echo $model->name . ' ( ' . $model->id . ' )'; ?></h1>
<?php } else { ?>
    <h1>Просмотр шаблона станка <?php echo $model->name . ' ( ' . $model->id . ' )'; ?></h1>
<?php } ?>


<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
        'full_name',
		'name',
        'code',
        'span_number',
        'place_number',
		'ip',
        'port',
        'local_port',
        'pwd',
		'mac',
        's_values',
        'reasons_timeout_table',
        'data_fix_period',
        'peak_average_period',
        'main_detector_digit',
        'main_detector_analog',
        array('label'=>'Группы', 'value'=> $model->getGroupsText())
	),
)); ?>
