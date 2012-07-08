<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'machine-data-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Поля отмеченные <span class="required">*</span> обязательны для заполнения.</p>
    
	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'number'); ?>
		<?php echo $form->textField($model,'number'); ?>
		<?php echo $form->error($model,'number'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'dt'); ?>
		<?php echo $form->textField($model,'dt'); ?>
		<?php echo $form->error($model,'dt'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'duration'); ?>
		<?php echo $form->textField($model,'duration'); ?>
		<?php echo $form->error($model,'duration'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'mac'); ?>
		<?php echo $form->textField($model,'mac',array('size'=>16,'maxlength'=>16)); ?>
		<?php echo $form->error($model,'mac'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'machine_id'); ?>
		<?php echo $form->textField($model,'machine_id'); ?>
		<?php echo $form->error($model,'machine_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'operator_id'); ?>
		<?php echo $form->textField($model,'operator_id'); ?>
		<?php echo $form->error($model,'operator_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'da_max1'); ?>
		<?php echo $form->textField($model,'da_max1'); ?>
		<?php echo $form->error($model,'da_max1'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'da_max2'); ?>
		<?php echo $form->textField($model,'da_max2'); ?>
		<?php echo $form->error($model,'da_max2'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'da_max3'); ?>
		<?php echo $form->textField($model,'da_max3'); ?>
		<?php echo $form->error($model,'da_max3'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'da_max4'); ?>
		<?php echo $form->textField($model,'da_max4'); ?>
		<?php echo $form->error($model,'da_max4'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'da_avg1'); ?>
		<?php echo $form->textField($model,'da_avg1'); ?>
		<?php echo $form->error($model,'da_avg1'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'da_avg2'); ?>
		<?php echo $form->textField($model,'da_avg2'); ?>
		<?php echo $form->error($model,'da_avg2'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'da_avg3'); ?>
		<?php echo $form->textField($model,'da_avg3'); ?>
		<?php echo $form->error($model,'da_avg3'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'da_avg4'); ?>
		<?php echo $form->textField($model,'da_avg4'); ?>
		<?php echo $form->error($model,'da_avg4'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'dd1'); ?>
		<?php echo $form->textField($model,'dd1'); ?>
		<?php echo $form->error($model,'dd1'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'dd2'); ?>
		<?php echo $form->textField($model,'dd2'); ?>
		<?php echo $form->error($model,'dd2'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'dd3'); ?>
		<?php echo $form->textField($model,'dd3'); ?>
		<?php echo $form->error($model,'dd3'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'dd4'); ?>
		<?php echo $form->textField($model,'dd4'); ?>
		<?php echo $form->error($model,'dd4'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'dd_change1'); ?>
		<?php echo $form->textField($model,'dd_change1'); ?>
		<?php echo $form->error($model,'dd_change1'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'dd_change2'); ?>
		<?php echo $form->textField($model,'dd_change2'); ?>
		<?php echo $form->error($model,'dd_change2'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'dd_change3'); ?>
		<?php echo $form->textField($model,'dd_change3'); ?>
		<?php echo $form->error($model,'dd_change3'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'dd_change4'); ?>
		<?php echo $form->textField($model,'dd_change4'); ?>
		<?php echo $form->error($model,'dd_change4'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'state'); ?>
		<?php echo $form->textField($model,'state'); ?>
		<?php echo $form->error($model,'state'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'operator_last_fkey'); ?>
		<?php echo $form->textField($model,'operator_last_fkey'); ?>
		<?php echo $form->error($model,'operator_last_fkey'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'fkey_all'); ?>
		<?php echo $form->textField($model,'fkey_all'); ?>
		<?php echo $form->error($model,'fkey_all'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'flags'); ?>
		<?php echo $form->textField($model,'flags'); ?>
		<?php echo $form->error($model,'flags'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->