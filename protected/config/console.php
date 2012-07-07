<?php

// This is the configuration for yiic console application.
// Any writable CConsoleApplication properties can be configured here.
return CMap::mergeArray(
    include_once 'main.php',
    array(
        'name'=>'My Console Application',
        // application components
        'components'=>array(
            'log'=>array(
                'class'=>'CLogRouter',
                'routes'=>array(
                    array(
                        'class'=>'CFileLogRoute',
                        'levels'=>'error, warning',
                    ),
                    array(
                        'class'=>'CWebLogRoute',
                        'levels'=>'errors',
                        //'showInFireBug'=>false,

                        //'categories'=>'system.db.CDbCommand.*',
                        //'filter'=>'CLogFilter',
                        //'enabled' => false
                    ),
                ),
            ),
        ),

        'commandMap'=>array(
           //'check' => '/../modules/smto/commands/MachineDataCommand.php'
        ),
    )
);