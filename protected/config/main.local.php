<?php

return CMap::mergeArray(
     include_once 'main.php',    
  array(
	  
      
	'name'=>'СМТО',
	'modules'=>array(
		// uncomment the following to enable the Gii tool
		
		'gii'=>array(
			'class'=>'system.gii.GiiModule',
			'password'=>'1',
            // If removed, Gii defaults to localhost only. Edit carefully to taste.
			'ipFilters'=>array('::1', '192.168.0.100', '127.0.0.1'),
 		),
		
        'smto' //=> array()
	),
      
      
      
      
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
				//'<controller:\w+>/<id:\d+>'=>'<controller>/view',
				//'<controller:\w+>/<action:\w+>/<id:\d+>'=>'<controller>/<action>',
				//'<controller:\w+>/<action:\w+>'=>'<controller>/<action>',
                'machine_template' => 'smto/machine/update/id/1'
			),
            //'baseUrl'=>'/test',
		),
        
		// uncomment the following to use a MySQL database
		'db'=>array( 
			'connectionString' => 'mysql:host=localhost;dbname=smto_13_10_2011',
			'emulatePrepare' => true,
			'username' => 'root',
			'password' => '1',
			'charset' => 'utf8',
            'enableProfiling'=>true,
            'enableParamLogging'=>true,
		),
		
		'log'=>array(
			'class'=>'CLogRouter',
			'routes'=>array(
				array(
					'class'=>'CFileLogRoute',
					'levels'=>'error, warning',
				),
				// uncomment the following to show log messages on web pages
				
				array(
					'class'=>'CWebLogRoute',
                    //'levels'=>'trace, info, error, warning',
                    'levels'=>'error, warning',
                    'showInFireBug'=>true,
                    'categories'=>'system.db.CDbCommand.*',
                    //'filter'=>'CLogFilter',
				),
			),
		),
	),
)
);