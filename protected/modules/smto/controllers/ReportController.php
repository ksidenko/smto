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
        $chartDataJSON = array('states_work' => array(), 'states_not_work' => array());

        $chartPath = Yii::getPathOfAlias('ext.fusioncharts.assets');
        $chartAssetsPath = Yii::app()->assetManager->publish( $chartPath, YII_DEBUG );
        Yii::app()->clientScript->registerScriptFile( $chartAssetsPath . '/JSClass/FusionCharts.site.min.js', CClientScript::POS_HEAD );
        Yii::app()->clientScript->registerScriptFile( $chartAssetsPath . '/JSClass/highcharts.debug.js', CClientScript::POS_HEAD );


        if (isset($_POST['ReportConstructor'])) {
            $model->attributes=$_POST['ReportConstructor'];

            if ($model->validate()) {
                $chartData = $model->getData();

                if (isset($chartData, $chartData['separate'], $chartData['join']) && ($chartData['separate'] || $chartData['join']) ) {

                    //foreach($chartData as $reportType => $value) {
                        $chartDataJSON_ = array(
                            'states_work' => array(),
                            'states_not_work' => array(),
                        );

                        $reportType = $model->machineReportType;
                        foreach($chartData[$reportType] as $machineId => $machineData) {
                            $caption = array();
                            if (isset($machineData['machine']) && $machineData['machine']) {
                                $caption []= $machineData['machine']['name'];
                            }
                            if (isset($machineData['operator']) && $machineData['operator']) {
                                $caption []= $machineData['operator']['name'];
                            }

                            $chartDataJSON_ ['states_work'][$machineId] = array(
                                "chart" => array(
                                    "caption" => implode(', ', $caption),
                                    "showpercentvalues" => 1,
                                    "palettecolors" => "",
                                    "numberscalevalue" => "60,60,24,7",
                                    "numberscaleunit" => "мин,ч,д,нед",
                                ),
                                "data" => array()
                            );
                            $chartDataJSON_ ['states_not_work'][$machineId] = $chartDataJSON_ ['states_work'][$machineId];

                            $colors = array();
                            foreach($machineData['states_work'] as $row){
                                if ( ($row["sec_duration"] / $model->secTotal * 100) > 2) {
                                    $chartDataJSON_['states_work'][$machineId]["data"] []= array(
                                        //'label'=>$row['name'],
                                        'toolText' => $row['name'] . ', ' . Helpers::secToTime($row["sec_duration"]),
                                        'value'=>$row["sec_duration"],
                                        'color'=>$row["color"],
                                        //'displayValue'=>$row['name'] . ', ' . Helpers::secToTime($row["sec_duration"]),
                                    );
                                    $colors []= $row["color"];
                                }
                            }
                            $chartDataJSON_ ['states_work'][$machineId]["chart"]["palettecolors"] = implode(', ', $colors);


                            $caption = implode(', ', $caption);
                            $caption = !empty($caption) ? ', ' . $caption : '';

                            $chartDataJSON_ ['states_not_work'][$machineId]["chart"]["caption"] = 'Простой оборудования' . $caption;

                            $colors = array();
                            foreach($machineData['states_not_work'] as $row){
                                if ( ($row["sec_duration"] / $model->secTotal * 100) > 2) {
                                    $chartDataJSON_['states_not_work'][$machineId]["data"] []= array(
                                        //'label'=>$row['name'],
                                        'toolText'=>$row['name'] . ', ' . Helpers::secToTime($row["sec_duration"]),
                                        'value'=>$row["sec_duration"],
                                        'color'=>$row["color"],
                                        //'displayValue'=>$row['code'] . ', ' . Helpers::secToTime($row["sec_duration"]),
                                    );
                                    $colors []= $row["color"];
                                }
                            }
                            $chartDataJSON_ ['states_not_work'][$machineId]["chart"]["palettecolors"] = implode(', ', $colors);
                        }
                        $chartDataJSON = $chartDataJSON_;
                    //}

                }
                //echo '<pre>'.print_r($chartDataJSON, true) . '<pre>'; die;
            }
        } else {

            $model->dtStart = '2012-07-04 08:00:00';
            $model->dtEnd = '2012-07-04 09:00:00';
        }

        $chartDataView = array();
        if ($chartData) {
            if ($model->machineReportType == 'join') {
                $chartDataView = $chartData['join'];
            } else {
                $chartDataView = $chartData['separate'];
            }
        }

        //echo '<pre>'.print_r($model->machineId, true) . '<pre>'; die;

        //print_r($chartDataXml); die;
        $chartType = ucfirst($model->graphReportType).'2D';
        $this->render('index',array(
            'model'=>$model,
            'chartAssetsPath' => $chartAssetsPath,
            'chartType' => $chartType,
            'chartData' => $chartDataView,
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