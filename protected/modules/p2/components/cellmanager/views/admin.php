<!-- Cell Admin -->
<div class='yiiForm'>
    <h2><?php echo Yii::t('P2Module.p2','Properties') ?></h2>
    <p class="hint"><?php echo Yii::t('P2Module.p2','__CELL_PROPERTIES__') ?></p>
    <?php echo P2Helper::table($this->_params); ?>

    <h2><?php echo Yii::t('P2Module.p2','Configuration') ?></h2>

    <p class="hint"><?php echo Yii::t('P2Module.p2','__CELL_CONFIGURATION__') ?></p>
    <?php echo P2Helper::table($this->_config); ?>

    <?php
    echo CHtml::link(
    '<span class="ui-icon ui-icon-gear"></span>'.Yii::t('P2Module.p2',"Manage Widgets"),
    Yii::app()->createUrl(
    "p2/p2Cell/admin",
    array(
    'return_url'=>P2Helper::uri(),
    'P2CellParams'=>$this->getFilter()
    )
    ),
    array('class' => P2Helper::juiButtonCssClass(true))
    );
    ?>
    <?php echo P2Helper::clearfloat(); ?>
</div>