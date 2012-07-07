<?php $mail->setSubject('Your account at '.Yii::app()->name.' was activated') ?>
Hello <?php echo $user->name ?>,

thanks for registering with <?php echo Yii::app()->name ?>!

Your account is ready for use now. You can login here with your username and password:
<?php echo Yii::app()->createAbsoluteUrl('/site/login') ?>
