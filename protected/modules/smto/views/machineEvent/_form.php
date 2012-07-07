<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'machine-event-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Поля отмеченные <span class="required">*</span> обязательны для заполнения.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'id'); ?>
		<?php echo $form->textField($model,'id'); ?>
		<?php echo $form->error($model,'id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'code'); ?>
		<?php echo $form->textField($model,'code',array('size'=>60,'maxlength'=>512)); ?>
		<?php echo $form->error($model,'code'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'name'); ?>
		<?php echo $form->textField($model,'name',array('size'=>60,'maxlength'=>512)); ?>
		<?php echo $form->error($model,'name'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'descr'); ?>
		<?php echo $form->textArea($model,'descr',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'descr'); ?>
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
		<?php echo CHtml::submitButton($model->getIsNewRecord() ? 'Добавить' : 'Сохранить'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->