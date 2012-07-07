<?php

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
if( $this->chartTransparent )
{
  $nsetTransparent=($this->chartTransparent==false?"opaque":"transparent");
}else
{
  $nsetTransparent="window";
}

$strFlashVars = "&chartWidth=" . $chartWidth . "&chartHeight=" . $chartHeight . "&debugMode=" . $debugMode . "&registerWithJS=" . $registerWithJS;
if ( $this->chartAction != '' )
{
    // DataURL Mode
    $strFlashVars .= "&dataURL=" . $this->chartAction;
}
else
{
    //DataXML Mode
    $strFlashVars .= "&dataXML=" . $this->chartData;
}

// Secured connection?
$httpPrefix = 'http';
if( Yii::app()->request->getIsSecureConnection() )
{
	$httpPrefix = 'https';
}

$HTML_chart = <<<HTMLCHART
	<!-- START Code Block for Chart $chartID -->
	<object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" codebase="{$httpPrefix}://fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=8,0,0,0" width="$chartWidth" height="$chartHeight" id="$chartID">
		<param name="allowScriptAccess" value="always" />
		<param name="movie" value="$chartType"/>		
		<param name="FlashVars" value="$strFlashVars" />
		<param name="quality" value="high" />
		<embed  src="$chartType" FlashVars="$strFlashVars" wmode="$nsetTransparent" quality="high" width="$chartWidth" height="$chartHeight" name="$chartID" allowScriptAccess="always" type="application/x-shockwave-flash" pluginspage="{$httpPrefix}://www.macromedia.com/go/getflashplayer" />
	</object>
	<!-- END Code Block for Chart $chartID -->
HTMLCHART;

echo $HTML_chart;

?>