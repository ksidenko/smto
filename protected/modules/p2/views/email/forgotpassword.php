<?php $mail->setSubject('Reset password at '.Yii::app()->name) ?>
Hello <?php echo $user->name ?>,

you've requested to reset your password at <?php echo Yii::app()->name ?>!

Please click the following link to create a new password for your account:
<?php echo $resetPasswordUrl ?>

If you haven't requested a password reset, simply disregard this e-mail.
