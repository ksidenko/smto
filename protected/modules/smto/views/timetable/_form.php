<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'timetable-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'id'); ?>
		<?php echo $form->textField($model,'id'); ?>
		<?php echo $form->error($model,'id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'name'); ?>
		<?php echo $form->textField($model,'name',array('size'=>60,'maxlength'=>512)); ?>
		<?php echo $form->error($model,'name'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'time_from'); ?>
		<?php echo $form->textField($model,'time_from'); ?>
		<?php echo $form->error($model,'time_from'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'time_to'); ?>
		<?php echo $form->textField($model,'time_to'); ?>
		<?php echo $form->error($model,'time_to'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->