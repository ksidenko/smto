<!-- Widget Admin -->

<?php $this->beginClip('widgetAdmin'); ?>
<div class='yiiForm'>
    <?php if (Yii::app()->user->checkAccess('editor')): ?>
    <h2>Widget</h2>
    <h3>Properties</h3>
        <?php echo P2Helper::table(CJSON::decode($cell->classProps)); ?>
    <h2>Cell</h2>
    <h3>Attributes</h3>
        <?php echo P2Helper::table(array('id'=>$cell->id,'rank'=>$cell->rank,'Class Path'=>$cell->classPath)); ?>
    <h3>Info</h3>
        <?php $this->widget('p2.components.P2InfoInputWidget', array('owner'=>$cell,'display'=>'table')); ?>
    <?php endif; ?>
</div>
<?php $this->endClip(); ?>



<?php $this->beginClip('widgetContent'); ?>
<div class="buttons p2InfoStatus<?php echo $cell->P2Info->status ?>">
    <?php
        if($cell->P2Info->checkAccess) {
            echo "<div class='checkAccess ".P2Helper::juiButtonCssClass()."'>".P2Helper::juiIcon('locked').$cell->P2Info->checkAccess."</div>";
        }
    ?>




    <?php
            echo CHtml::beginForm();

            echo CHtml::link(
    P2Helper::juiIcon('gear').Yii::t("P2Module.p2","Edit Widget"),
    Yii::app()->createUrl(
    "/p2/p2Cell/update",
    array("id" => $cell->id, "return_url" => P2Helper::uri())),
    array('class'=>'button ui-state-default ui-corner-all ui-icon-container')
    ); ?>

    <?php
    echo CHtml::linkButton(
    P2Helper::juiIcon('minus').Yii::t('P2Module.p2','Remove Widget'),
    array(
    'class'=>P2Helper::juiButtonCssClass().' ui-icon-container',
    'submit'=>array(
        "/p2/p2Cell/delete",
        'id'=>$cell->id,
        'return_url'=>P2Helper::uri()
    ),
    'confirm'=> Yii::t('P2Module.p2','__UNLINK_WIDGET__'))
    );
    ?>

    <?php if ($widget instanceof ICellManagerWidget): ?>

        <?php
        $cellClassProps = new CMap(CJSON::decode($cell->classProps));
        $widgetClassProps = P2Helper::getClassProperties($cell->classPath);
        $adminParams = $widget->getAdminParams();
        ?>

        <?php if ($widget->getHasData()): ?>
            <?php
            echo CHtml::link(
            P2Helper::juiIcon('pencil').Yii::t('P2Module.p2','Edit Data'),
            Yii::app()->createUrl(
            $adminParams['route']."/update",
            array(
            'id' => $widget->model->id,
            "return_url" => P2Helper::uri(),)),
            array(
            'class'=>'button ui-state-default ui-corner-all ui-icon-container',)
            );?>

            <?php
            echo CHtml::linkButton(
            P2Helper::juiIcon('trash').Yii::t('P2Module.p2','Delete Data'),
            array(
            'submit'=> array(
                $adminParams['route']."/delete",
                'id'=>$cellClassProps[$adminParams['dataKey']],
                'return_url'=> P2Helper::uri()),
            'confirm'=> Yii::t('P2Module.p2','__DELETE_WIDGET_DATA__'),
            'class'=>'button  ui-state-default ui-corner-all ui-icon-container')
            );
            ?>

            
        <?php endif; ?>

    <?php
    
    echo P2Helper::clearfloat();
    echo CHtml::endForm();
    endif;

    ?>

    <?php echo P2Helper::clearfloat(); ?>
</div>
    <?php echo P2Helper::clearfloat(); ?>
<div class="<?php echo P2CellManager::CSSCLASS_NORMAL ?>">
    <?php echo $this->prepareWidgetContent($cell, true); ?>
</div>
<?php $this->endClip(); ?>
<!-- end clips -->


<div>
<?php
$this->widget(
    'system.zii.widgets.jui.CJuiTabs',
    array(
    'id' => "widget".$cell->id,
    'actionPrefix' => $this->Id,
    'tabs'=>array(
        Yii::t('P2Module.p2','Widget').' - '.$this->getWidgetHeadline($widget) => array(
            'title' => $this->getWidgetHeadline($widget),
            'content' => $this->controller->clips['widgetContent'],),
        P2Helper::juiIcon('gear', false) => array(
            'title' => 'edit',
            'content'=>$this->controller->clips['widgetAdmin'],),
    #'cssFile'=> $this->_cssFile
    ))
);
?>
</div>