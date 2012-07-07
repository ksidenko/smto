<h3>Skeleton</h3>

<div class="yiiForm form classProps">

    <?php echo CHtml::beginForm() ?>
    <?php echo CHtml::hiddenField('modelClass', get_class($model)) ?>

    <div class="simple">
        <?php echo CHtml::activeLabel($model, 'mediaId') ?>
        <?php $this->widget(
            'P2AutoComplete',
            array(
            'model' => $model,
            'attribute' => 'mediaId',
            'searchModel' => 'P2File',
            )) ?>
    </div>

    <div class="simple">
        <?php echo CHtml::activeLabel($model, 'width') ?>
        <?php echo CHtml::activeTextField($model, 'width') ?>
    </div>
    <div class="simple">
        <?php echo CHtml::activeLabel($model, 'height') ?>
        <?php echo CHtml::activeTextField($model, 'height') ?>
    </div>
    <div class="simple">
        <?php echo CHtml::activeLabel($model, 'swfUrl') ?>
        <?php echo CHtml::activeTextField($model, 'swfUrl') ?>
    </div>
    <div class="simple">
        <?php echo CHtml::activeLabel($model, 'allowZoom') ?>
        <?php echo CHtml::activeDropDownList($model, 'allowZoom' , array(0=>'No',1=>'Yes')); ?>
    </div>
    <div class="simple">
        <?php echo CHtml::activeLabel($model, 'zoomFactor') ?>
        <?php echo CHtml::activeTextField($model, 'zoomFactor') ?>
    </div>
    <div class="simple">
        <?php echo CHtml::activeLabel($model, 'themeColor') ?>
        <?php echo CHtml::activeTextField($model, 'themeColor') ?>
    </div>
    <div class="simple">
        <?php echo CHtml::activeLabel($model, 'allowTogglePageLayout') ?>
        <?php echo CHtml::activeDropDownList($model, 'allowTogglePageLayout' , array(0=>'No',1=>'Yes')); ?>
    </div>
    <div class="simple">
        <?php echo CHtml::activeLabel($model, 'allowFullScreen') ?>
        <?php echo CHtml::activeDropDownList($model, 'allowFullScreen' , array(0=>'No',1=>'Yes')); ?>
    </div>


    <div class="action">
        <?php echo CHtml::button(
        'apply',
        array('onclick'=>'
                $("#P2Cell_classProps").externalModel("applyClassProps")
                $("#P2Cell_classProps").externalModel("closeDialog");
                ')) ?>
    </div>

    <?php echo CHtml::endForm() ?>

</div>
