<?php $this->layout = "main"; ?>
<h2>Forgot Password</h2>

<?php if(Yii::app()->user->getFlash('p2UserForgotPassword')): ?>
	<p>We've sent you an email with further instructions.</p>
	<p><a href="<?php echo Yii::app()->homeUrl ?>">Click here</a> to return to the home page.</p>
<?php else: ?>
<div class="yiiForm form">
	<p>
	<!--Please enter your username or your e-mail address to reset your password.-->
	</p>

	<?php echo CHtml::beginForm(); ?>

	<?php echo CHtml::errorSummary($form); ?>	
	<div class="simple">
		<?php echo CHtml::activeLabelEx($form,'name'); ?>
		<?php echo CHtml::activeTextField($form,'name',array('size'=>45,'maxlength'=>45)); ?>
	</div>
	<div class="simple">
		<?php echo CHtml::activeLabelEx($form,'eMail'); ?>
		<?php echo CHtml::activeTextField($form,'eMail',array('size'=>60,'maxlength'=>100)); ?>
	</div>
	<div class="action">
		<?php echo CHtml::submitButton(Yii::t('P2Module.p2','Reset Password')) ?>
                <br/>
                <small><?php echo Yii::t(
                    'P2Module.p2',
                    '__USER_PROBLEMS__',
                    array(
                        '{email}'=>Yii::app()->params['adminEmail'],
                        '{contactUrl}'=>$this->createUrl('/site/contact')));
                ?>
                </small>
	</div>
	<?php echo CHtml::endForm(); ?>
</div>
<?php endif; ?>
