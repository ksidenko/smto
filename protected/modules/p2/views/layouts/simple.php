<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<!-- BEGIN <?php echo str_replace( Yii::app()->basePath, ".",__FILE__) ?> -->
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <link rel="stylesheet" type="text/css"
              href="<?php echo Yii::app()->assetManager->publish(Yii::app()->basePath."/modules/p2/assets/p2.css") ?>" />
        <link rel="stylesheet" type="text/css"  media="screen, projection"
              href="<?php echo Yii::app()->assetManager->publish(Yii::app()->basePath."/modules/p2/assets/blueprint/screen.css") ?>" />
        <link rel="stylesheet" type="text/css"  media="screen, projection"
              href="<?php echo Yii::app()->assetManager->publish(Yii::app()->basePath."/modules/p2/assets/blueprint/form.css") ?>" />
        <link rel="stylesheet" type="text/css"  media="print"
              href="<?php echo Yii::app()->assetManager->publish(Yii::app()->basePath."/modules/p2/assets/blueprint/print.css") ?>" />
        
                  <?php #$this->widget("application.modules.p2.extensions.autotheme.EAutoTheme"); ?>
        
        <title><?php echo $this->pageTitle; ?></title>
    </head>

    <body id="p2Simple">
        <div id="wrapper">
            <div id="content">
                <?php echo $content; ?>
            </div><!-- content -->
        </div><!-- page -->
    </body>

</html>
<!-- END <?php echo str_replace( Yii::app()->basePath, ".",__FILE__) ?> -->