<?php

// uncomment the following to define a path alias
Yii::setPathOfAlias('local','/var/www/smto/');

return CMap::mergeArray(
    include(dirname(__FILE__)."/../modules/p2/config/module.php"),
    array(
        'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',

        'name'=>'СМТО',

        // preloading 'log' component
        'preload'=>array('log'),

        // autoloading model and component classes
        'import'=>array(
            'application.models.*',
            'application.components.*',
            'application.helpers.*',
            'application.extensions.ipvalidator.*',
            'application.modules.user.models.*',
            'application.modules.user.components.*',
            'application.modules.srbac.controllers.SBaseController',
            'application.modules.smto.models.*',
            'application.modules.smto.models.forms.*',
            'application.modules.smto.widgets.*',
            //'application.vendors.*',
        ),

        'modules'=>array(
            // uncomment the following to enable the Gii tool

            'gii'=>array(
                'class'=>'system.gii.GiiModule',
                'password'=>'1',
                // If removed, Gii defaults to localhost only. Edit carefully to taste.
                //'ipFilters'=>array('178.49.155.34', '178.49.201.40', '::1'),

            ),
            'smto' => array(
                'max_duration' => 100,
                'max_time_between_machine_records' => 60,
                'is_save_process_files' => false,
                'max_record_duration' => 25200, // 7*60*60 = 25200 sec
                'max_last_time_modify' => 60, // 60 sec
            )
        ),
        'onBeginRequest' =>function($event){
            //echo Yii::getPathOfAlias('system.gii.GiiModule'); die;
            //print_r(Yii::App()->db);die;
         },

        // application components
        'components'=>array(
            // uncomment the following to enable URLs in path-format
            'urlManager'=>array(
                'urlFormat'=>'path',
                'showScriptName' => false,
                'rules'=>array(
                      'gii'=>'gii',
                      'gii/<controller:\w+>'=>'gii/<controller>',
                      'gii/<controller:\w+>/<action:\w+>'=>'gii/<controller>/<action>',
                ),
                'baseUrl'=>'',
            ),

            'db'=>array(
                'connectionString' => 'mysql:host=localhost;dbname=smto',
                'emulatePrepare' => true,
                'username' => 'root',
                'password' => '1',
                'charset' => 'utf8',
                'enableProfiling'=>0,
                'enableParamLogging'=>0,
                'queryCacheID' => 'cache',
                'schemaCachingDuration' => 3600,
                'schemaCacheID' => 'cache',
            ),
            'cache'=>array(
                'class'=>'system.caching.CFileCache',
            ),
            'errorHandler'=>array(
                'errorAction'=>'site/error',
            ),
            'log'=>array(
                'class'=>'CLogRouter',
                'routes'=>array(
                    'CFileLogRoute'=> array(
                        'class'=>'CFileLogRoute',
                        'levels'=>'error, warning',
                        //'categories'=>'system.db.CDbCommand.*',
                    ),
                    'CWebLogRoute'=> array(
                        'class'=>'CWebLogRoute',
                        'levels'=>'error, warning',
                        'showInFireBug'=>1,
                        'categories'=>'system.db.CDbCommand.*',
                        //'filter'=>'CLogFilter',
                    ),
                ),
            ),
            'fusioncharts' => array(
                'class' => 'ext.fusioncharts.fusionCharts',
            ),
        ),

        'params'=>array(
            // this is used in contact page
            'adminEmail'=>'webmaster@example.com',
            'languages' => array(
                        'ru_ru' => "Русский",
                        'en_us' => 'English'),
        ),
        //'language' => 'ru_ru'
    )
);
