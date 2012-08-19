<?php

class ReportController extends CController
{
    public $layout='//layouts/column2'; 
        
    public $menu = array();
    public $breadcrumbs = array();
        
    
	public function actionIndex()
	{
        $model = new ReportConstructor();

        $chartData = array();
        $chartDataJSON = array();
        $chartDataJSON_ = array();
        $machineIds = array();
//        $chartPath = Yii::getPathOfAlias('ext.fusioncharts.assets');
//        $chartAssetsPath = Yii::app()->assetManager->publish( $chartPath, YII_DEBUG );
//        Yii::app()->clientScript->registerScriptFile( $chartAssetsPath . '/JSClass/FusionCharts.site.min.js', CClientScript::POS_HEAD );
//        Yii::app()->clientScript->registerScriptFile( $chartAssetsPath . '/JSClass/highcharts.debug.js', CClientScript::POS_HEAD );

        $basePath=Yii::getPathOfAlias('application');
        $baseUrl=Yii::app()->getAssetManager()->publish($basePath . '/../js', true, -1, YII_DEBUG);
        Yii::app()->getClientScript()->registerScriptFile($baseUrl . '/jquery-ui/js/jquery-ui-1.8.22.custom.min.js', CClientScript::POS_END);
        Yii::app()->getClientScript()->registerScriptFile($baseUrl . '/jquery-ui/js/jquery-ui-timepicker-addon.js', CClientScript::POS_END);
        //Yii::app()->getClientScript()->registerCssFile($baseUrl . '/jquery-ui/css/ui-lightness/jquery-ui-1.8.22.custom.css', CClientScript::POS_END);


        if (isset($_POST['ReportConstructor'])) {
            $model->attributes=$_POST['ReportConstructor'];

            if ($model->validate()) {
                $chartData = $model->getData();

                if ( isset($chartData, $chartData['machines'], $chartData['reports']) ) {

                    $chartDataJSON_['machines'] = $chartData['machines'];

                    foreach ( array('main', 'work', 'not_work') as $reportType) {

                        if (count($chartData['reports'][$reportType]) == 0) {
                            continue;
                        }

                        if (count($chartData['reports'][$reportType][$model->machineReportType]) == 0) {
                            $chartData['reports'][$reportType][$model->machineReportType] = array(1 => array());
                        }

                        foreach($chartData['reports'][$reportType][$model->machineReportType] as $machineId => $currReportData) {
                            $machineInfo = isset($chartData['machines'][$machineId]['machine']) ? $chartData['machines'][$machineId]['machine'] : null;
                            $operatorInfo = isset($chartData['machines'][$machineId]['operator']) ? $chartData['machines'][$machineId]['operator'] : null;

                            $caption = array();
                            if ( $machineInfo && trim($machineInfo['name']) != '') {
                                $caption []= $machineInfo['name'];
                            }
                            if ( $operatorInfo  && trim($operatorInfo['name']) != '') {
                                $caption []= $operatorInfo['name'];
                            }

                            $chartDataJSON_ ['reports']['report-' . $reportType][$machineId] = array(
                                "chart" => array(),
                                "data" => array()
                            );

                            foreach($currReportData as $row){
                                //if ( ($row["sec_duration"] / $model->secTotal * 100) > 2) {
                                    $chartDataJSON_['reports']['report-' . $reportType][$machineId]["data"] []= array(
                                        'label' => $row['code'] . ', '. $row['name'] . ', ' . Helpers::secToTime($row["sec_duration"]),
                                        'color' => '#' . ltrim($row["color"], '#'),
                                        'data' => intval($row["sec_duration"]),

                                        //'displayValue'=>$row['name'] . ', ' . Helpers::secToTime($row["sec_duration"]),
                                    );
                                //}
                            }

                            if ( count($chartDataJSON_['reports']['report-' . $reportType][$machineId]["data"]) > 0) {
                                $machineIds[$machineId] = 1;
                            }
                            $caption = implode(', ', $caption);
                            //$caption = !empty($caption) ? ', ' . $caption : '';

                            $chartDataJSON_['reports']['report-' . $reportType][$machineId]['chart']["caption"] = $caption;
                        }
                    }
//                    //foreach($chartData as $reportType => $value) {
//                        $chartDataJSON_ = array(
//                            'states_work' => array(),
//                            'states_not_work' => array(),
//                        );
//
//                        $reportType = $model->machineReportType;
//                        foreach($chartData[$reportType] as $machineId => $machineData) {
//                            $caption = array();
//                            if (isset($machineData['machine']) && $machineData['machine']) {
//                                $caption []= $machineData['machine']['name'];
//                            }
//                            if (isset($machineData['operator']) && $machineData['operator']) {
//                                $caption []= $machineData['operator']['name'];
//                            }
//
//                            $chartDataJSON_ ['states_work'][$machineId] = array(
//                                "chart" => array(
//                                    "caption" => implode(', ', $caption),
//                                    "showpercentvalues" => 1,
//                                    "palettecolors" => "",
//                                    "numberscalevalue" => "60,60,24,7",
//                                    "numberscaleunit" => "мин,ч,д,нед",
//                                ),
//                                "data" => array()
//                            );
//                            $chartDataJSON_ ['states_not_work'][$machineId] = $chartDataJSON_ ['states_work'][$machineId];
//
//                            $colors = array();
//                            foreach($machineData['states_work'] as $row){
//                                if ( ($row["sec_duration"] / $model->secTotal * 100) > 2) {
//                                    $chartDataJSON_['states_work'][$machineId]["data"] []= array(
//                                        //'label'=>$row['name'],
//                                        'toolText' => $row['name'] . ', ' . Helpers::secToTime($row["sec_duration"]),
//                                        'value'=>$row["sec_duration"],
//                                        'color'=>$row["color"],
//                                        //'displayValue'=>$row['name'] . ', ' . Helpers::secToTime($row["sec_duration"]),
//                                    );
//                                    $colors []= $row["color"];
//                                }
//                            }
//                            $chartDataJSON_ ['states_work'][$machineId]["chart"]["palettecolors"] = implode(', ', $colors);
//
//
//                            $caption = implode(', ', $caption);
//                            $caption = !empty($caption) ? ', ' . $caption : '';
//
//                            $chartDataJSON_ ['states_not_work'][$machineId]["chart"]["caption"] = 'Простой оборудования' . $caption;
//
//                            $colors = array();
//                            foreach($machineData['states_not_work'] as $row){
//                                if ( ($row["sec_duration"] / $model->secTotal * 100) > 2) {
//                                    $chartDataJSON_['states_not_work'][$machineId]["data"] []= array(
//                                        //'label'=>$row['name'],
//                                        'toolText'=>$row['name'] . ', ' . Helpers::secToTime($row["sec_duration"]),
//                                        'value'=>$row["sec_duration"],
//                                        'color'=>$row["color"],
//                                        //'displayValue'=>$row['code'] . ', ' . Helpers::secToTime($row["sec_duration"]),
//                                    );
//                                    $colors []= $row["color"];
//                                }
//                            }
//                            $chartDataJSON_ ['states_not_work'][$machineId]["chart"]["palettecolors"] = implode(', ', $colors);
//                        }
//                        $chartDataJSON = $chartDataJSON_;
                    //}

                }
                //echo '<pre>'.print_r($chartDataJSON_, true) . '<pre>'; die;
            }
        } else {
            $model->dtStart = date('d.m.Y 00:00:00');
            $model->dtEnd = date('d.m.Y H:i:s');
        }

//        $chartDataView = array();
//        if ($chartData) {
//            if ($model->machineReportType == 'join') {
//                $chartDataView = $chartData['join'];
//            } else {
//                $chartDataView = $chartData['separate'];
//            }
//        }

        //echo '<pre>'.print_r($model->machineId, true) . '<pre>'; die;

        $model->dtStart = date('d.m.Y H:i:s', strtotime($model->dtStart));
        $model->dtEnd = date('d.m.Y H:i:s', strtotime($model->dtEnd));

        $machineIds = array_keys($machineIds);

        //print_r($chartDataXml); die;
        $chartType = ucfirst($model->graphReportType).'2D';
        $this->render('index',array(
            'model'=>$model,
            //'chartAssetsPath' => $chartAssetsPath,
            'chartType' => $chartType,
            'machineIds' => $machineIds,
            "chartDataJSON" => $chartDataJSON_
        ));
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

            $path = Machine::getMachineDataPathCurr();
            $machineDataCurr = array();

            foreach ($dataCondition as $cond) {
                $machineAR = Machine::model()->findByPk($cond['machine_id']);
                if (!$machineAR) {
                    continue;
                }

                $machineDataFabric = new MachineDataManager('2.0');

                if (isset($machineDataCurr[$machineAR->id])) {
                    $lineParser = $machineDataCurr[$machineAR->id];
                } else {
                    $filename = $path . 'cr' . $machineAR->mac . '.cdt';
                    //echo $filename;

                    $fd = @fopen($filename, 'r');
                    if (!$fd) {
                        continue;
                    }
                    while ( $line = fgets($fd) ) {

                        if ( !isset($line[0]) || $line[0] != 'C' ) {
                            continue;
                        }
                        break;
                    }
                    fclose($fd);
                    $lineParser = $machineDataFabric->getLineParser($machineAR->mac);
                    $lineParser->parseCSVLine($line);

                    //print_r($lineParser); die;

                    $machineDataCurr[$machineAR->id] = $lineParser;
                }

//                if ($cond['dt_start'] < strtotime($lineParser->dt)) {
                    $output[$cond['machine_id']] [$cond['name']] []= array(
                        (strtotime($lineParser->dt)+7*60*60)*1000,
                        $lineParser->{$cond['name']},
                );
//                }
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

    public function actionLiniar() {

        $basePath=Yii::getPathOfAlias('application');
        $baseUrl=Yii::app()->getAssetManager()->publish($basePath . '/../js', true, -1, YII_DEBUG);
        Yii::app()->getClientScript()->registerScriptFile($baseUrl . '/jquery-ui/js/jquery-ui-1.8.22.custom.min.js', CClientScript::POS_END);
        Yii::app()->getClientScript()->registerScriptFile($baseUrl . '/jquery-ui/js/jquery-ui-timepicker-addon.js', CClientScript::POS_END);


        $model = new ReportLinearConstructor();
        $chartData = array();

        $model->dtStart = '18-08-2012 10:00:00';
        $model->dtEnd = '18-08-2012 10:10:00';

        if (isset($_POST['ReportLinearConstructor'])) {
            $chartData = $model->getData();
        } else {
            $model->dtStart = date('d.m.Y 00:00:00');
            $model->dtEnd = date('d.m.Y H:i:s');
        }


        $this->render('liniar', array(
            'model' => $model,
            'chartData' => $chartData,
        ));
    }
}
