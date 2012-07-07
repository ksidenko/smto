<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="language" content="en" />

	<!-- blueprint CSS framework -->
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/screen.css" media="screen, projection" />
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/print.css" media="print" />
	<!--[if lt IE 8]>
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/ie.css" media="screen, projection" />
	<![endif]-->

	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/main.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/form.css" />

	<title><?php echo CHtml::encode(Param::getParamValue('title')); ?></title>
        
        <?php Yii::app()->clientScript->registerCoreScript('jquery'); ?>
</head>

<body>

<div class="container" id="page">

	<div id="header">
		<div id="logo"><?php echo CHtml::encode(Param::getParamValue('site_name')); ?></div>
	</div><!-- header -->

	<div id="mainmenu">
		<?php $this->widget('zii.widgets.CMenu',array(
			'items'=>array(
				array('label'=>'Главная', 'url'=>array('/site/index')),
                                array('label'=>'Станки', 'url'=>array('/smto/machine/admin'), 'visible'=>!Yii::app()->user->isGuest),
                                //array('label'=>'Шаблоны станков', 'url'=>array('/smto/machine/admin', 'template' => 1 ), 'visible'=>!Yii::app()->user->isGuest),
                                array('label'=>'События станков', 'url'=>array('/smto/machineEvent/admin'), 'visible'=>!Yii::app()->user->isGuest),
                                array('label'=>'Операторы', 'url'=>array('/smto/operator/admin'), 'visible'=>!Yii::app()->user->isGuest),
                                //array('label'=>'Цвета событий', 'url'=>array('/smto/eventColor/admin'), 'visible'=>!Yii::app()->user->isGuest),
                                array('label'=>'Данные со станков', 'url'=>array('/smto/machineData/admin'), 'visible'=>!Yii::app()->user->isGuest),
                                array('label'=>'Отчет', 'url'=>array('/smto/report'), 'visible'=>!Yii::app()->user->isGuest),
                                array('label'=>'Мониторинг', 'url'=>array('/smto/report/monitoring'), 'visible'=>!Yii::app()->user->isGuest),
                                //array('label'=>'Импорт', 'url'=>array('/smto/task/import'), 'visible'=>!Yii::app()->user->isGuest),
				//array('label'=>'About', 'url'=>array('/site/page', 'view'=>'about')),
				//array('label'=>'Contact', 'url'=>array('/site/contact')),
				
				array('url'=>Yii::app()->user->loginUrl, 'label'=> 'Авторизация', 'visible'=>Yii::app()->user->isGuest),
				//array('url'=>Yii::app()->getModule('user')->registrationUrl, 'label'=>Yii::app()->getModule('user')->t("Register"), 'visible'=>Yii::app()->user->isGuest),
				//array('url'=>Yii::app()->getModule('user')->profileUrl, 'label'=>Yii::app()->getModule('user')->t("Profile"), 'visible'=>!Yii::app()->user->isGuest),
				array('url'=>'/site/logout', 'label'=>"Выйти".' ('.Yii::app()->user->name.')', 'visible'=>!Yii::app()->user->isGuest),				
				array('url'=> array('/p2/default/'), 'label' => 'CMS', 'visible'=>!Yii::app()->user->isGuest),				

			),
		)); ?>
            <?php //print_r(Yii::app()->user);die;?>
	</div><!-- mainmenu -->
	<?php if(isset($this->breadcrumbs)):?>
		<?php $this->widget('zii.widgets.CBreadcrumbs', array(
			'links'=>$this->breadcrumbs,
		)); ?><!-- breadcrumbs -->
	<?php endif?>
    <?php if(Yii::app()->user->hasFlash('success')):?>
        <div class="flash-success">
            <?php echo Yii::app()->user->getFlash('success'); ?>
        </div>
    <?php endif; ?>
    <?php if(Yii::app()->user->hasFlash('error')):?>
        <div class="flash-error">
            <?php echo Yii::app()->user->getFlash('error'); ?>
        </div>
    <?php endif; ?>
	<?php echo $content; ?>

	<div id="footer">
		Copyright &copy; <?php echo date('Y'); ?> by sks.develop<br/>
	</div><!-- footer -->

</div><!-- page -->

</body>
</html>