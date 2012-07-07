<?php $this->layout = "main"; ?>

<h1>User Profile</h1>

<?php if($flash=Yii::app()->user->getFlash('p2UserUpdated')): ?>

<p>Your changes have been saved.</p>
    <?php if ($flash==='mailsent'): ?>
<p>We have sent you an email to verify the new address. 
		Please click the link in that mail to complete the change.</p>
    <?php endif; ?>

<?php else: ?>

<div class="yiiForm form">

        <?php echo CHtml::beginForm(); ?>
        <?php echo CHtml::errorSummary($user); ?>

    <h3>Name</h3>
    <p>

            <?php echo CHtml::activeLabelEx($user,'firstName'); ?>
            <?php echo CHtml::activeTextField($user,'firstName',array('size'=>30,'maxlength'=>32)); ?>

            <?php echo CHtml::activeLabelEx($user,'lastName'); ?>
            <?php echo CHtml::activeTextField($user,'lastName',array('size'=>30,'maxlength'=>32)); ?>
    </p>

    <h3>Password</h3>
    <p>
            <?php echo CHtml::activeLabelEx($user,'newpassword'); ?>
            <?php echo CHtml::activePasswordField($user,'newpassword',array('size'=>30,'maxlength'=>45)); ?>
            <?php echo CHtml::activeLabelEx($user,'repeatpassword'); ?>
            <?php echo CHtml::activePasswordField($user,'repeatpassword',array('size'=>30,'maxlength'=>45)); ?>
    </p>



    <h3>E-mail</h3>
    <p>
            <?php echo CHtml::activeLabelEx($user,'verifyEmail'); ?>
            <?php echo CHtml::activeTextField($user,'verifyEmail',array('size'=>30,'maxlength'=>100)); ?>
    </p>


    <p>
            <?php echo CHtml::submitButton('Update') ?>
        <br/>
            <?php if (P2Helper::return_url()) echo CHtml::link(Yii::t('P2Module.p2','Cancel'), P2Helper::return_url()); ?>
    </p>
        <?php echo CHtml::endForm(); ?>
</div>

<?php endif; ?>
