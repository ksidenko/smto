<div class="yiiForm form">
<p>
Fields with <span class="required">*</span> are required.
</p>

<?php echo CHtml::beginForm(); ?>

<?php echo CHtml::errorSummary($model); ?>

<div class="simple">
<?php echo CHtml::activeLabelEx($model,'description'); ?>
<?php echo CHtml::activeTextField($model,'description',array('size'=>60,'maxlength'=>255)); ?>
</div>
<div class="simple">
<?php echo CHtml::activeLabelEx($model,'action'); ?>
<?php echo CHtml::activeTextField($model,'action',array('size'=>32,'maxlength'=>32)); ?>
</div>
<div class="simple">
<?php echo CHtml::activeLabelEx($model,'model'); ?>
<?php echo CHtml::activeTextField($model,'model',array('size'=>60,'maxlength'=>64)); ?>
</div>
<div class="simple">
<?php echo CHtml::activeLabelEx($model,'modelId'); ?>
<?php echo CHtml::activeTextField($model,'modelId'); ?>
</div>
<div class="simple">
<?php echo CHtml::activeLabelEx($model,'changes'); ?>
<?php echo CHtml::activeTextField($model,'changes',array('size'=>60,'maxlength'=>255)); ?>
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
<?php echo CHtml::activeLabelEx($model,'data'); ?>
<?php echo CHtml::activeTextArea($model,'data',array('rows'=>6, 'cols'=>50));; ?>
</div>

<?php echo CHtml::hiddenField('return_url', P2Helper::return_url()); ?>

<div class="action">
<?php echo CHtml::submitButton($update ? 'Save' : 'Create'); ?>
</div>

<?php echo CHtml::endForm(); ?>

</div><!-- yiiForm -->