<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'operator-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Поля отмеченные <span class="required">*</span> обязательны для заполнения.</p>
    
	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'c1'); ?>
		<?php echo $form->textField($model,'c1',array('size'=>10,'maxlength'=>10)); ?>
		<?php echo $form->error($model,'c1'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'c2'); ?>
		<?php echo $form->textField($model,'c2',array('size'=>10,'maxlength'=>10)); ?>
		<?php echo $form->error($model,'c2'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'c3'); ?>
		<?php echo $form->textField($model,'c3',array('size'=>10,'maxlength'=>10)); ?>
		<?php echo $form->error($model,'c3'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'full_name'); ?>
		<?php echo $form->textField($model,'full_name',array('size'=>60,'maxlength'=>1024)); ?>
		<?php echo $form->error($model,'full_name'); ?>
	</div>

<!--	<div class="row">-->
<!--		--><?php //echo $form->labelEx($model,'phone'); ?>
		<?php echo $form->textField($model,'phone',array('size'=>32,'maxlength'=>32)); ?>
<!--		--><?php //echo $form->error($model,'phone'); ?>
<!--	</div>-->

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Добавить' : 'Сохранить'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->