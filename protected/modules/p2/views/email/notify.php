<?php $mail->setSubject('New User Notifcication from '.Yii::app()->name) ?>
A new user has just registered with <?php echo Yii::app()->name ?>!

Click here to activate/block this user:

<?php echo $activationUrl ?>

