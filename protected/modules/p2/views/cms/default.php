<?php
/**
 * This is the default view file for P2Page records, usually rendered by p2/p2Page/view
 *
 * See p2/config/p2.php modules.p2.params.p2Page.availableViews
 */
?>

<!-- BEGIN <?php echo str_replace( Yii::app()->basePath, ".",__FILE__) ?> -->

<?php $this->pageTitle=$model->descriptiveName.' - '.Yii::app()->name; ?>

<h1><?php echo (isset($model))?$model->descriptiveName:'Default' ?></h1>

<div>
<?php $this->widget(
    'application.modules.p2.components.cellmanager.P2CellManager',
    array(
        'id'=>'headerCell',
    )
) ?>
</div>

<?php echo P2Helper::clearfloat() ?>

<div class="span-16">
<?php $this->widget(
    'application.modules.p2.components.cellmanager.P2CellManager',
    array(
        'id'=>'mainCell',
        'varyByRequestParam' => 'pageId'
    )
) ?>
</div>

<div class="span-8 last">
<?php $this->widget(
    'application.modules.p2.components.cellmanager.P2CellManager',
    array(
        'id'=>'rightCell',
        'varyByRequestParam' => 'pageId'
    )
) ?>
</div>


<br class="clearfloat" />
<!-- END <?php echo str_replace( Yii::app()->basePath, ".",__FILE__) ?> -->