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
		<?php echo $form->label($model,'name'); ?>
		<?php echo $form->textField($model,'name',array('size'=>60,'maxlength'=>512)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'code'); ?>
		<?php echo $form->textField($model,'code',array('size'=>60,'maxlength'=>512)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'ip'); ?>
		<?php echo $form->textField($model,'ip',array('size'=>32,'maxlength'=>32)); ?>
	</div>

    <div class="row">
        <?php echo $form->label($model,'port'); ?>
        <?php echo $form->textField($model,'port',array('size'=>32,'maxlength'=>32)); ?>
    </div>

    <div class="row">
        <?php echo $form->label($model,'local_port'); ?>
        <?php echo $form->textField($model,'local_port',array('size'=>32,'maxlength'=>32)); ?>
    </div>

    <div class="row">
        <?php echo $form->label($model,'pwd'); ?>
        <?php echo $form->textField($model,'pwd',array('size'=>32,'maxlength'=>32)); ?>
    </div>

    <div class="row">
        <?php echo $form->label($model,'s_values'); ?>
        <?php echo $form->textField($model,'s_values',array('size'=>32,'maxlength'=>32)); ?>
    </div>

    <div class="row">
        <?php echo $form->label($model,'reasons_timeout_table'); ?>
        <?php echo $form->textField($model,'reasons_timeout_table',array('size'=>32,'maxlength'=>32)); ?>
    </div>


	<div class="row buttons">
		<?php echo CHtml::submitButton('Поиск'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->