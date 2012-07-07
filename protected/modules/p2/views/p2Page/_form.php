<div class="form">

    <p>
        Fields with <span class="required">*</span> are required.
    </p>

    <?php echo CHtml::beginForm(); ?>

    <?php echo CHtml::errorSummary($model); ?>
    <div class="span-16">
    <div class="row">
        <?php echo CHtml::activeLabelEx($model,'parentId'); ?>
        <?php #echo CHtml::activeTextField($model,'parentId'); ?>
        <?php echo CHtml::activeDropDownList(
        $model,
        'parentId',
        CHtml::listData(P2Page::model()->localized()->findAll(), 'id', 'name', 'Parent.name')
        ); ?>
        <p class="hint"><?php echo Yii::t('P2Module.p2','Page, this page will be a child of.')?></p>
    </div>
    <div class="row">
        <?php echo CHtml::activeLabelEx($model,'name'); ?>
        <?php echo CHtml::activeTextField($model,'name',array('size'=>60,'maxlength'=>128)); ?>
            <br/>
            <?php $this->widget('P2NameNormalizer', array('selector' => '#P2Page_name')); ?>

        <p class="hint"><?php echo Yii::t('P2Module.p2','Alphanumeric characters only, may be used in friendly URLs.')?></p>
    </div>
    <div class="row">
        <?php echo CHtml::activeLabelEx($model,'descriptiveName'); ?>
        <?php echo CHtml::activeTextField($model,'descriptiveName',array('size'=>60,'maxlength'=>255)); ?>
        <p class="hint"><?php echo Yii::t('P2Module.p2','Menu or display name.')?></p>
    </div>
    <div class="row">
        <?php echo CHtml::activeLabelEx($model,'url'); ?>
        <?php echo CHtml::activeTextField($model,'url',array('size'=>60,'maxlength'=>255)); ?>
        <p class="hint"><?php echo Yii::t('P2Module.p2','Either a string or a JSON-object, i.e. {"route":"/site","params":{"lang":"en_us"}}. Takes precedence over layout and view.')?></p>
    </div>
    <div class="row">
        <?php echo CHtml::activeLabelEx($model,'layout'); ?>
        <?php echo CHtml::activeDropDownList($model,'layout', P2Page::getLayouts()); ?>
        <p class="hint"><?php echo Yii::t('P2Module.p2','Layout for this page.')?></p>
    </div>
    <div class="row">
        <?php echo CHtml::activeLabelEx($model,'view'); ?>
        <?php echo CHtml::activeDropDownList($model,'view', P2Page::getViews()); ?>
        <p class="hint"><?php echo Yii::t('P2Module.p2','View for this page.')?></p>
    </div>
    <!--<div class="row"> ** NIY **
        <?php echo CHtml::activeLabelEx($model,'replaceMethod'); ?>
        <?php echo CHtml::activeTextField($model,'replaceMethod',array('size'=>60,'maxlength'=>128)); ?>
    </div>-->
    <div class="row">
        <?php echo CHtml::activeLabelEx($model,'rank'); ?>
        <?php echo CHtml::activeTextField($model,'rank'); ?>
        <p class="hint"><?php echo Yii::t('P2Module.p2','Position within a menu.')?></p>
    </div>
    <div class="row buttons">
        <?php echo CHtml::submitButton($update ? 'Save' : 'Create'); ?>
    </div>
    </div>

    <div class="span-8 last">
    <?php $this->widget('P2InfoInputWidget', array('owner'=>$model)); ?>
    </div>


    <?php echo CHtml::endForm(); ?>

</div><!-- yiiForm -->