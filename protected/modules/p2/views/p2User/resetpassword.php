<?php $this->layout = "main"; ?>
<?php if ($user===null): ?>
<h2>Verfification failed!</h2>

The link you followed either expired or was modified.
<?php elseif(Yii::app()->user->getFlash('p2UserResetPassword')): ?>
	<p>Your password has been reset</p>
    <p><a href="<?php echo $this->createUrl('/site/login')?>">Click here</a> to login with your new password.</p>
<?php else: ?>
<h2>Reset Password</h2>

<div class="yiiForm form">
	<p>
	Fields with <span class="required">*</span> are required.
	</p>

	<?php echo CHtml::beginForm(); ?>

	<?php echo CHtml::errorSummary($user); ?>

	<div class="simple">
	<?php echo CHtml::activeLabelEx($user,'newpassword'); ?>
	<?php echo CHtml::activePasswordField($user,'newpassword',array('size'=>45,'maxlength'=>45)); ?>
	</div>

	<div class="simple">
	<?php echo CHtml::activeLabelEx($user,'repeatpassword'); ?>
	<?php echo CHtml::activePasswordField($user,'repeatpassword',array('size'=>45,'maxlength'=>45)); ?>
	</div>

	<div class="action">
	<?php echo CHtml::submitButton('Update password') ?>
	</div>

	<?php echo CHtml::endForm(); ?>
</div>

<?php endif; ?>
