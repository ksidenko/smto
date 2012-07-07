<?php $mail->setSubject('Change of email address at '.Yii::app()->name) ?>
Hello <?php echo $user->name ?>,

you have requested a change of your email address at
<?php echo Yii::app()->name ?>.

Please confirm the update by clicking on the following link:
<?php echo $verificationUrl ?>
