<h3>Video</h3>

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
        <?php echo CHtml::activeLabel($model, 'videoUrl') ?>
        <?php echo CHtml::activeTextField($model, 'videoUrl') ?>
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
        <?php echo CHtml::activeLabel($model, 'videoControls') ?>
        <?php echo CHtml::activeDropDownList($model, 'videoControls' ,
                array(
                    'flowplayer.controls-3.1.5.swf'=>'flowplayer.controls-3.1.5.swf',
                    'flowplayer.controls-tube-3.1.5.swf'=>'flowplayer.controls-tube-3.1.5.swf'));
        ?>
    </div>
    <div class="simple">
        <?php echo CHtml::activeLabel($model, 'autoPlay') ?>
        <?php echo CHtml::activeDropDownList($model, 'autoPlay', array(0=>'false',1=>'true'));?>
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