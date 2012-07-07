<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'event-color-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'code'); ?>
		<?php echo $form->textField($model,'code',array('size'=>60,'maxlength'=>512, 'disabled'=>'true')); ?>
		<?php echo $form->error($model,'code'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'name'); ?>
		<?php echo $form->textField($model,'name',array('size'=>60,'maxlength'=>512)); ?>
		<?php echo $form->error($model,'name'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'color'); ?>
		<?php //echo $form->textField($model,'color',array('size'=>15,'maxlength'=>15)); ?>
        <?php
            $this->widget('application.extensions.colorpicker.EColorPicker', 
                array(
                    'name'=> get_class($model) . '[color]',
                    'value' => $model->color,
                    //'model' => $model,
                    'mode'=>'textfield',
                    'fade' => true,
                    'slide' => false,
                    'curtain' => false,
                   )
             );
        ?>        
		<?php echo $form->error($model,'color'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->