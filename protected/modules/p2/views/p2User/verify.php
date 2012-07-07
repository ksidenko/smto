<?php $this->layout = "main"; ?>
<?php if ($user===null): ?>
<h2>Verfification failed!</h2>

The link you followed either expired or was modified.
<?php else: ?>
<h2>User verified</h2>

<p>We verified your email address. You will receive a further notice
    as soon as we activate your account.</p>

<p><b>Please also check your 'spam-folder'.</b></p>
<br/>
<p><a href="<?php echo Yii::app()->homeUrl ?>">Click here</a> to return to the home page.</p>

<?php endif; ?>
