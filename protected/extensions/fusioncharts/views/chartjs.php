<div id="chart_<?php echo $this->chartId; ?>" align="center">The chart will appear within this DIV. This text will be replaced by the chart.</div>

<?php
Yii::app()->clientScript->registerScriptFile( $this->assetsPath . '/JSClass/FusionCharts.js', CClientScript::POS_HEAD );

$chartUrlExtra = '?'.Yii::app()->fusioncharts->covertParamToXml($this->chartMessage);

if($this->chartNoCache==true){
  if($this->chartMessage==''){
     $chartUrlExtra = "?nocache=" . microtime();
  }else{
     $chartUrlExtra .=  "&nocache=" . microtime();
  }
}

$chartID = 'chart_' . $this->chartId;
$chartType = $this->assetsPath . '/Charts/' . $this->chartType . '.swf' . $chartUrlExtra;
$chartWidth = (int) $this->chartWidth;
$chartHeight = (int) $this->chartHeight;
$registerWithJS = (int) $this->registerWithJS;
$debugMode = (int) $this->debugMode;
$chartData = '';

if ( $this->chartAction != '' )
{
    // DataURL Mode
    $chartData = "myChart_{$chartID}.setDataURL('{$this->chartAction}');";
}
else
{
    $chartDataXml = str_replace('\'', '\\\'', str_replace(array("\n", "\r\n", "\r"), '', $this->chartData));
    //DataXML Mode
    $chartData = "myChart_{$chartID}.setDataXML('{$chartDataXml}');";
}

$jsStr = "
	<!-- START Script Block for Chart $chartID -->
	var myChart_{$chartID} = new FusionCharts('{$chartType}', '{$chartID}', '{$chartWidth}', '{$chartHeight}', '{$debugMode}', '{$registerWithJS}');
    {$chartData}
    myChart_{$chartID}.setTransparent('{$this->chartTransparent}');
	myChart_{$chartID}.render('{$chartID}');
	<!-- END Script Block for Chart $chartID -->
";

Yii::app()->clientScript->registerScript( 'chartjs_'.$this->chartId, $jsStr );

?>