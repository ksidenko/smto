<?php

// This is the configuration for yiic console application.
// Any writable CConsoleApplication properties can be configured here.
return CMap::mergeArray(
    include_once 'main.php',
    array(
        'name'=>'My Console Application',
        // application components
        'components'=>array(
            'db'=>array(
                'enableProfiling'=>0,
                'enableParamLogging'=>1,
            ),

            'log'=>array(
                'class'=>'CLogRouter',
                'routes'=>array(
                    'CFileLogRoute' => array(
                        'class'=>'CFileLogRoute',
                        'levels'=>'error, warning',
                    ),
                    'CWebLogRoute' => array(
                        'class'=>'CWebLogRoute',
                        'levels'=>'error, warning',
                        'showInFireBug'=>0,
                        'categories'=>'system.db.CDbCommand.*',
                        //'filter'=>'CLogFilter',
                    ),
                ),
            ),
        ),

        'commandMap'=>array(
           //'check' => '/../modules/smto/commands/MachineDataCommand.php'
        ),
    )
);