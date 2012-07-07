<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'param-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'key'); ?>
		<?php echo $form->textField($model,'key',array('size'=>60,'maxlength'=>200)); ?>
		<?php echo $form->error($model,'key'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'value'); ?>
		<?php echo $form->textArea($model,'value',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'value'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'descr'); ?>
		<?php echo $form->textArea($model,'descr',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'descr'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'stable'); ?>
		<?php echo $form->textField($model,'stable'); ?>
		<?php echo $form->error($model,'stable'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->