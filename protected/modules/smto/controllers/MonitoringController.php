<?php

class MonitoringController extends SBaseController
{
    public $layout='//layouts/column2'; 
        
    public $menu = array();
    public $breadcrumbs = array();
        
    
	public function actionMonitor()
	{
        $monitoring = new Monitoring();

        $data = $monitoring->getMonitorData();

        $this->render('monitor', array( 'monitorData' => $data));
	}
}
