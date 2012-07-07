<?php
$this->breadcrumbs=array();
$this->menu=array();

//echo '$model->getIsTemplate: ' . ($model->getIsTemplate() ? '1' : '0');

if (!$model->getIsTemplate()) {
    $this->breadcrumbs += array('Станки'=>array('admin'));
    $this->menu += array(
        array('label'=>'Список станков', 'url'=>array('index')),
        array('label'=>'Управление станками', 'url'=>array('admin')),
        array('label'=>'Управление шаблонами станков', 'url'=>array('admin', 'template' => 1)),
    );
} else {
    $this->breadcrumbs += array('Шаблоны станков'=>array('admin', 'template' => 1));
    $this->menu += array(
	    array('label'=>'Список шаблонов станков', 'url'=>array('index', 'template' => 1)),
        array('label'=>'Управление шаблонами станков', 'url'=>array('admin', 'template' => 1)),
    );
}
$this->breadcrumbs += array('Добавить');
$this->menu []= array('label'=>'Управление событиями станков', 'url'=>array('/smto/machineEvent/admin'));

?>

<?php if(!$model->getIsTemplate()) {?>
    <h1>Добавление станка</h1>
<?php } else { ?>
    <h1>Добавление шаблона для станка</h1>
<?php } ?>

<?php echo $this->renderPartial('_form', array('model'=>$model, 'is_create' => true)); ?>