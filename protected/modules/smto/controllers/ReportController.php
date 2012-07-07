<?php

class ReportController extends SBaseController
{
    public $layout='//layouts/column2'; 
        
    public $menu = array();
    public $breadcrumbs = array();
        
    
	public function actionIndex()
	{
        $model = new ReportConstructor();

        $chartData = array();
        $chartDataJSON = '{}';
        
        $chartPath = Yii::getPathOfAlias('ext.fusioncharts.assets');
        $chartAssetsPath = Yii::app()->assetManager->publish( $chartPath, YII_DEBUG );
        Yii::app()->clientScript->registerScriptFile( $chartAssetsPath . '/JSClass/FusionCharts.site.min.js', CClientScript::POS_HEAD );
        Yii::app()->clientScript->registerScriptFile( $chartAssetsPath . '/JSClass/highcharts.debug.js', CClientScript::POS_HEAD );


        if (isset($_POST['ReportConstructor'])) {
            $model->attributes=$_POST['ReportConstructor'];
            if($model->validate()) {
                $chartData = $model->getData();

                if(count($chartData)) {
                    $chartDataJSON_ = array(
                        'states' => array(),
                        'states_not_working' => array(),                        
                    );
                    
                    //print_r($chartData); die;
                    foreach($chartData as $machineId => $machineData) {
                        $caption = array();
                        if ($machineData['machine']) {
                            $caption []= $machineData['machine']['name'];
                        }
                        if ($machineData['operator']) {
                            $caption []= $machineData['operator']['name'];
                        }
                              
                        
                        $chartDataJSON_ ['states'][$machineId]= array(
                            "chart" => array(
                                "caption" => implode(', ', $caption),
                                "showpercentvalues" => 1,
                                "palettecolors" => "",
                                "numberscalevalue" => "60,60,24,7",
                                "numberscaleunit" => "мин,ч,д,нед",
                                
                            ),
                            "data" => array()
                        );
                        $chartDataJSON_ ['states_not_working'][$machineId] = $chartDataJSON_ ['states'][$machineId];
                        
                        $colors = array();
                        foreach($machineData['states'] as $row){
                            $chartDataJSON_['states'][$machineId]["data"] []= array(
                                //'label'=>$row['name'],
                                'toolText' => $row['name'] . ', ' . Helpers::secToTime($row["sec_duration"]),
                                'value'=>$row["sec_duration"],
                                'color'=>$row["color"],
                                //'displayValue'=>$row['name'] . ', ' . Helpers::secToTime($row["sec_duration"]),
                            );
                            $colors []= $row["color"];
                        }
                        $chartDataJSON_ ['states'][$machineId]["chart"]["palettecolors"] = implode(', ', $colors);

                        $chartDataJSON_ ['states_not_working'][$machineId]["chart"]["caption"] = 'Причины простоя оборудования';

                        $colors = array();
                        foreach($machineData['states_not_working'] as $row){
                            $chartDataJSON_['states_not_working'][$machineId]["data"] []= array(
                                //'label'=>$row['name'],
                                'toolText'=>$row['name'] . ', ' . Helpers::secToTime($row["sec_duration"]),
                                'value'=>$row["sec_duration"],
                                'color'=>$row["color"],
                                //'displayValue'=>$row['code'] . ', ' . Helpers::secToTime($row["sec_duration"]),
                            );
                            $colors []= $row["color"];
                        }
                        $chartDataJSON_ ['states_not_working'][$machineId]["chart"]["palettecolors"] = implode(', ', $colors);
                    }
                    $chartDataJSON = $chartDataJSON_;
                }
                //print_r($chartDataJSON); die;
            }
        } else {

            $model->dtStart = '2011-11-21 08:00:00';
            $model->dtEnd = '2011-11-21 10:00:00';
        }

        //print_r($chartDataXml); die;
        $chartType = ucfirst($model->graphReportType).'2D';
        $this->render('index',array('model'=>$model, 
            'chartAssetsPath' => $chartAssetsPath, 
            'chartType' => $chartType,
            'chartData' => $chartData, 
            "chartDataJSON" => $chartDataJSON));
	}

//	public function actionReport()
//	{
//		$this->render('report');
//	}


    public function actionMonitoring()
	{
        if (Yii::app()->request->isAjaxRequest) {
            $dataCondition = Yii::app()->request->getParam('dataCondition');
            $machineData = new MachineData;

            $output = array();

            foreach ($dataCondition as $cond) {
                $result = $machineData->getData($cond);

                foreach((array)$result as $res) {
                    $output[$cond['machine_id']] [$cond['name']] []= array(
                        ( strtotime($res['dt']) + 7 * 60 * 60 )*1000, // hack for timezone
                        $res[$cond['name']],
                    );
                }
            }

            echo json_encode($output);
            Yii::app()->end();
        } else {
            // Load main page for monitoring 
            $basePath=Yii::getPathOfAlias('application.modules.smto.views.report');
            $baseUrl=Yii::app()->getAssetManager()->publish($basePath, true, -1, YII_DEBUG);
            Yii::app()->getClientScript()->registerScriptFile($baseUrl . '/plot.js', CClientScript::POS_END);

    //        $model = new Machine;
    //        $machines = $model->findAll();
    //        $machineList = array();
    //        foreach($machines as $machine) {
    //            $machineList[$machine['id']] = $machine['name'];
    //        }
    //		$this->render('index', array( 'machines' => $machines, 'machineList' => $machineList) );

            $this->render('monitoring');
        }
	}

	// Uncomment the following methods and override them if needed
	/*
	public function filters()
	{
		// return the filter configuration for this controller, e.g.:
		return array(
			'inlineFilterName',
			array(
				'class'=>'path.to.FilterClass',
				'propertyName'=>'propertyValue',
			),
		);
	}

	public function actions()
	{
		// return external action classes, e.g.:
		return array(
			'action1'=>'path.to.ActionClass',
			'action2'=>array(
				'class'=>'path.to.AnotherActionClass',
				'propertyName'=>'propertyValue',
			),
		);
	}
	*/
}