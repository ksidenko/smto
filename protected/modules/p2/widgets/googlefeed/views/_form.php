<h3>Skeleton</h3>

<div class="yiiForm form classProps">

    <?php echo CHtml::beginForm() ?>
    <?php echo CHtml::hiddenField('modelClass', get_class($model)) ?>

    <div class="simple">
        <?php echo CHtml::activeLabel($model, 'url') ?>
        <?php echo CHtml::activeTextField($model, 'url') ?>
    </div>
    <div class="simple">
        <?php echo CHtml::activeLabel($model, 'numEntries') ?>
        <?php echo CHtml::activeTextField($model, 'numEntries') ?>
    </div>
    <div class="simple">
        <?php echo CHtml::activeLabel($model, 'displayAuthor') ?>
        <?php echo CHtml::activeCheckBox($model, 'displayAuthor') ?>
    </div>
    <div class="simple">
        <?php echo CHtml::activeLabel($model, 'displayEntryDate') ?>
        <?php echo CHtml::activeCheckBox($model, 'displayEntryDate') ?>
    </div>
    <div class="simple">
        <?php echo CHtml::activeLabel($model, 'displayDescription') ?>
        <?php echo CHtml::activeCheckBox($model, 'displayDescription') ?>
    </div>
    <div class="simple">
        <?php echo CHtml::activeLabel($model, 'displayShortContent') ?>
        <?php echo CHtml::activeCheckBox($model, 'displayShortContent') ?>
    </div>
    <div class="simple">
        <?php echo CHtml::activeLabel($model, 'displayFullContent') ?>
        <?php echo CHtml::activeCheckBox($model, 'displayFullContent') ?>
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
