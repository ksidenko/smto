<div class="wide form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

	<div class="row">
		<?php echo $form->label($model,'id'); ?>
		<?php echo $form->textField($model,'id'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'pid'); ?>
		<?php echo $form->textField($model,'pid'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'machine_id'); ?>
		<?php echo $form->textField($model,'machine_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'status'); ?>
		<?php echo $form->textField($model,'status',array('size'=>8,'maxlength'=>8)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'dt_create'); ?>
		<?php echo $form->textField($model,'dt_create'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'dt_check'); ?>
		<?php echo $form->textField($model,'dt_check'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'progress'); ?>
		<?php echo $form->textField($model,'progress'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'error'); ?>
		<?php echo $form->textArea($model,'error',array('rows'=>6, 'cols'=>50)); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Search'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->