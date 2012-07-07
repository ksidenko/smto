<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'amplitude-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'number'); ?>
		<?php echo $form->textField($model,'number'); ?>
		<?php echo $form->error($model,'number'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'machine_id'); ?>
		<?php echo $form->textField($model,'machine_id'); ?>
		<?php echo $form->error($model,'machine_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'value'); ?>
		<?php echo $form->textField($model,'value'); ?>
		<?php echo $form->error($model,'value'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'type'); ?>
		<?php echo $form->textField($model,'type',array('size'=>8,'maxlength'=>8)); ?>
		<?php echo $form->error($model,'type'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'rec_type'); ?>
		<?php echo $form->textField($model,'rec_type',array('size'=>8,'maxlength'=>8)); ?>
		<?php echo $form->error($model,'rec_type'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->