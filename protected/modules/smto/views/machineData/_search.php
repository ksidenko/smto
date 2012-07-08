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
		<?php echo $form->label($model,'number'); ?>
		<?php echo $form->textField($model,'number'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'dt'); ?>
		<?php echo $form->textField($model,'dt'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'duration'); ?>
		<?php echo $form->textField($model,'duration'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'mac'); ?>
		<?php echo $form->textField($model,'mac',array('size'=>16,'maxlength'=>16)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'machine_id'); ?>
		<?php echo $form->textField($model,'machine_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'operator_id'); ?>
		<?php echo $form->textField($model,'operator_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'da_max1'); ?>
		<?php echo $form->textField($model,'da_max1'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'da_max2'); ?>
		<?php echo $form->textField($model,'da_max2'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'da_max3'); ?>
		<?php echo $form->textField($model,'da_max3'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'da_max4'); ?>
		<?php echo $form->textField($model,'da_max4'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'da_avg1'); ?>
		<?php echo $form->textField($model,'da_avg1'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'da_avg2'); ?>
		<?php echo $form->textField($model,'da_avg2'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'da_avg3'); ?>
		<?php echo $form->textField($model,'da_avg3'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'da_avg4'); ?>
		<?php echo $form->textField($model,'da_avg4'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'dd1'); ?>
		<?php echo $form->textField($model,'dd1'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'dd2'); ?>
		<?php echo $form->textField($model,'dd2'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'dd3'); ?>
		<?php echo $form->textField($model,'dd3'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'dd4'); ?>
		<?php echo $form->textField($model,'dd4'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'dd_change1'); ?>
		<?php echo $form->textField($model,'dd_change1'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'dd_change2'); ?>
		<?php echo $form->textField($model,'dd_change2'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'dd_change3'); ?>
		<?php echo $form->textField($model,'dd_change3'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'dd_change4'); ?>
		<?php echo $form->textField($model,'dd_change4'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'state'); ?>
		<?php echo $form->textField($model,'state'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'operator_last_fkey'); ?>
		<?php echo $form->textField($model,'operator_last_fkey'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'fkey_all'); ?>
		<?php echo $form->textField($model,'fkey_all'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'flags'); ?>
		<?php echo $form->textField($model,'flags'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Search'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->