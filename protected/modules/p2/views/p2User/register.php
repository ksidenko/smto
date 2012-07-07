<?php $this->layout = "main"; ?>
<h2>Register</h2>

<?php if(Yii::app()->user->getFlash('p2UserRegistered')): ?>
	<p>Thanks for your registration! We've sent you an email with further instructions.</p>
	<p><b>Please also check your 'spam-folder'.</b></p>
	<br/>
        <p><a href="<?php echo Yii::app()->homeUrl ?>">Click here</a> to return to the home page.</p>
<?php else: ?>
<div class="yiiForm form">
	<p>
	Fields with <span class="required">*</span> are required.
	</p>

	<?php echo CHtml::beginForm(); ?>

	<?php echo CHtml::errorSummary($user); ?>

	<div class="simple">
	<?php echo CHtml::activeLabelEx($user,'name'); ?>
	<?php echo CHtml::activeTextField($user,'name',array('size'=>45,'maxlength'=>45)); ?>
	</div>
	<div class="simple">
	<?php echo CHtml::activeLabelEx($user,'firstName'); ?>
	<?php echo CHtml::activeTextField($user,'firstName',array('size'=>60,'maxlength'=>100)); ?>
	</div>
	<div class="simple">
	<?php echo CHtml::activeLabelEx($user,'lastName'); ?>
	<?php echo CHtml::activeTextField($user,'lastName',array('size'=>60,'maxlength'=>100)); ?>
	</div>
	<div class="simple">
	<?php echo CHtml::activeLabelEx($user,'eMail'); ?>
	<?php echo CHtml::activeTextField($user,'eMail',array('size'=>60,'maxlength'=>100)); ?>
	</div>

	<div class="simple">
	<?php echo CHtml::activeLabelEx($user,'newpassword'); ?>
	<?php echo CHtml::activePasswordField($user,'newpassword',array('size'=>45,'maxlength'=>45)); ?>
	</div>
	<div class="simple">
	<?php echo CHtml::activeLabelEx($user,'repeatpassword'); ?>
	<?php echo CHtml::activePasswordField($user,'repeatpassword',array('size'=>45,'maxlength'=>45)); ?>
	</div>

	<div class="action">
	<?php echo CHtml::submitButton('Register') ?>
	</div>

	<?php echo CHtml::endForm(); ?>
</div>

<?php endif; ?>
