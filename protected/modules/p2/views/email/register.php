<?php $mail->setSubject('Your registration with '.Yii::app()->name) ?>
Hello <?php echo $user->name ?>,

thanks for registering with <?php echo Yii::app()->name ?>!

Please click the following link to finish your registration:
<?php echo $verificationUrl ?>


Your username: <?php echo $user->name ?>

Your password: <?php echo $_POST['P2User']['newpassword'] ?>