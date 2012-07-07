<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'task-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'pid'); ?>
		<?php echo $form->textField($model,'pid'); ?>
		<?php echo $form->error($model,'pid'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'machine_id'); ?>
		<?php echo $form->textField($model,'machine_id'); ?>
		<?php echo $form->error($model,'machine_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'status'); ?>
		<?php echo $form->textField($model,'status',array('size'=>8,'maxlength'=>8)); ?>
		<?php echo $form->error($model,'status'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'dt_create'); ?>
		<?php echo $form->textField($model,'dt_create'); ?>
		<?php echo $form->error($model,'dt_create'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'dt_check'); ?>
		<?php echo $form->textField($model,'dt_check'); ?>
		<?php echo $form->error($model,'dt_check'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'progress'); ?>
		<?php echo $form->textField($model,'progress'); ?>
		<?php echo $form->error($model,'progress'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'error'); ?>
		<?php echo $form->textArea($model,'error',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'error'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->