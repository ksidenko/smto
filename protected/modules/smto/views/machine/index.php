<?php
$this->breadcrumbs=array();
$this->menu = array();

if (!$model->getIsTemplate()) {
    $this->breadcrumbs []= 'Станки';
    $this->menu []= array('label'=>'Добавить станок', 'url'=>array('create'));
    $this->menu []= array('label'=>'Управление станками', 'url'=>array('admin'));
} else {
    $this->breadcrumbs []= 'Шаблоны станков';
    $this->menu []= array('label'=>'Добавить шаблон станка', 'url'=>array('create', 'template' => 1));
    $this->menu []= array('label'=>'Управление шаблонами станков', 'url'=>array('admin', 'template' => 1));
}
$this->menu []= array('label'=>'Управление событиями станков', 'url'=>array('/smto/machineEvent/admin'));

?>

<?php if(!$model->getIsTemplate()) {?>
    <h1>Станки</h1>
<?php } else { ?>
    <h1>Шаблоны станков</h1>
<?php } ?>


<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
