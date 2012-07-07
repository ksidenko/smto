<div class="yiiForm form">
<p>
Fields with <span class="required">*</span> are required.
</p>

<?php echo CHtml::beginForm(); ?>

<?php echo CHtml::errorSummary($model); ?>

<div class="simple">
<?php echo CHtml::activeLabelEx($model,'model'); ?>
<?php echo CHtml::activeTextField($model,'model',array('size'=>45,'maxlength'=>45)); ?>
</div>
<div class="simple">
<?php echo CHtml::activeLabelEx($model,'language'); ?>
<?php echo CHtml::activeTextField($model,'language',array('size'=>16,'maxlength'=>16)); ?>
</div>
<div class="simple">
<?php echo CHtml::activeLabelEx($model,'status'); ?>
<?php echo CHtml::activeTextField($model,'status'); ?>
</div>
<div class="simple">
<?php echo CHtml::activeLabelEx($model,'type'); ?>
<?php echo CHtml::activeTextField($model,'type'); ?>
</div>
<div class="simple">
<?php echo CHtml::activeLabelEx($model,'createdBy'); ?>
<?php echo CHtml::activeTextField($model,'createdBy'); ?>
</div>
<div class="simple">
<?php echo CHtml::activeLabelEx($model,'createdAt'); ?>
<?php echo CHtml::activeTextField($model,'createdAt'); ?>
</div>
<div class="simple">
<?php echo CHtml::activeLabelEx($model,'modifiedBy'); ?>
<?php echo CHtml::activeTextField($model,'modifiedBy'); ?>
</div>
<div class="simple">
<?php echo CHtml::activeLabelEx($model,'modifiedAt'); ?>
<?php echo CHtml::activeTextField($model,'modifiedAt'); ?>
</div>
<div class="simple">
<?php echo CHtml::activeLabelEx($model,'begin'); ?>
<?php echo CHtml::activeTextField($model,'begin'); ?>
</div>
<div class="simple">
<?php echo CHtml::activeLabelEx($model,'end'); ?>
<?php echo CHtml::activeTextField($model,'end'); ?>
</div>
<div class="simple">
<?php echo CHtml::activeLabelEx($model,'keywords'); ?>
<?php echo CHtml::activeTextArea($model,'keywords',array('rows'=>6, 'cols'=>50));; ?>
</div>
<div class="simple">
<?php echo CHtml::activeLabelEx($model,'customData'); ?>
<?php echo CHtml::activeTextArea($model,'customData',array('rows'=>6, 'cols'=>50));; ?>
</div>

<?php echo CHtml::hiddenField('return_url', P2Helper::return_url()); ?>

<div class="action">
<?php echo CHtml::submitButton($update ? 'Save' : 'Create'); ?>
</div>

<?php echo CHtml::endForm(); ?>

</div><!-- yiiForm -->