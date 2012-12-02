<?php

// change the following paths if necessary
$yii=dirname(__FILE__).'/lib/yii.php';
$config=dirname(__FILE__).'/protected/config/main.php';

// remove the following lines when in production mode
defined('YII_DEBUG') or define('YII_DEBUG',0);
// specify how many levels of call stack should be shown in each log message
defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL',3);

require_once($yii);
$app = Yii::createWebApplication($config);

Yii::import("application.components.zendAutoloader.EZendAutoloader", true);

// you are able to load custom code that is using Zend class naming convention
// with different prefix
EZendAutoloader::$prefixes = array('Zend', 'Custom');
Yii::registerAutoloader(array("EZendAutoloader", "loadClass"), true);

$app->run();