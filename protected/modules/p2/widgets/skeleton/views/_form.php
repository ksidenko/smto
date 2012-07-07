<h3>Skeleton</h3>

<div class="yiiForm form classProps">

    <?php echo CHtml::beginForm() ?>
    <?php echo CHtml::hiddenField('modelClass', get_class($model)) ?>

    <div class="simple">
        <?php echo CHtml::activeLabel($model, 'foo') ?>
        <?php echo CHtml::activeTextField($model, 'foo') ?>
    </div>

    <div class="complex">
        <?php echo CHtml::activeLabel($model, 'bar') ?>
        <?php $this->widget(
            'P2AutoComplete',
            array(
            'model' => $model,
            'attribute' => 'bar',
            'searchModel' => 'P2Page',
            )) ?>
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
