<?php

class MonitoringController extends SBaseController
{
    public $layout='//layouts/column2'; 
        
    public $menu = array();
    public $breadcrumbs = array();
        
    
	public function actionMonitor()
	{
	//die(1);
        $monitoring = new Monitoring();

        $data = $monitoring->getMonitorData();

        if ( $monitoring->hasErrors() ) {
            $errors = $monitoring->getErrors();
            Yii::app()->user->setFlash('error', implode('<br>', $errors));
        }

        if (!Yii::app()->request->isAjaxRequest) {
            Yii::app()->clientScript->registerCoreScript('jquery');

            $basePath=Yii::getPathOfAlias('application.modules.smto.views.monitoring.js');
            $baseUrl=Yii::app()->assetManager->publish($basePath, true, 0, YII_DEBUG);
            Yii::app()->clientScript->registerScriptFile($baseUrl . '/monitor.js', CClientScript::POS_END);
        }

        if (!Yii::app()->request->isAjaxRequest) {
            $this->render('monitor', array('monitorData' => $data));

        } else { //AJAX request
            $output['html'] = $this->widget('CMonitor', array('data' => $data), true);
            $output['errors'] = Yii::app()->user->getFlash('error', '');
            echo json_encode($output);
            Yii::app()->end();
        }
	}
}
