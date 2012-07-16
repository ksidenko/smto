<?php
$this->breadcrumbs = array();
$this->menu = array();

if (!$model->getIsTemplate()) {
    $this->breadcrumbs += array('Станки'=>array('admin'));
    $this->menu += array(
        array('label'=>'Список станков', 'url'=>array('index')),
        array('label'=>'Добавить станок', 'url'=>array('create')),
        array('label'=>'Просмотр станка', 'url'=>array('view', 'id'=>$model->id)),
        array('label'=>'Управление станками', 'url'=>array('admin')),

        array('label'=>'Управление шаблонами станков', 'url'=>array('admin', 'template' => 1)),
    );
} else {
    $this->breadcrumbs += array('Шаблоны станков'=>array('admin', 'template' => 1));
    $this->menu += array(
	    array('label'=>'Список шаблонов станков', 'url'=>array('index', 'template' => 1)),
        array('label'=>'Добавить шаблон станка', 'url'=>array('create', 'template' => 1)),
        array('label'=>'Просмотр шаблона станка', 'url'=>array('view', 'id'=>$model->id, 'template' => 1)),
        array('label'=>'Управление шаблонами станков', 'url'=>array('admin', 'template' => 1)),

        array('label'=>'Управление станками', 'url'=>array('admin')),
    );
}
$this->breadcrumbs += array($model->name=>array('view','id'=>$model->id), 'Редактировать',);
$this->menu []= array('label'=>'Управление событиями станков', 'url'=>array('/smto/machineEvent/admin'));

?>

<?php if(!$model->getIsTemplate()) {?>
    <h1>Редактирование станка <?php echo '<i>' . $model->name . '</i>' . ' ( ' . $model->id . ' )'; ?></h1>
<?php } else {?> 
    <h1>Редактирование шаблона <?php echo '<i>' . $model->name . '</i>' . ' ( ' . $model->id . ' )'; ?> для нового станка</h1>
<?php } ?> 
<?php echo $this->renderPartial('_form', array('model'=>$model, 'machineConfigData' => $machineConfigData, 'is_create' => false)); ?>