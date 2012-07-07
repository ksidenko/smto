<?php
/**
 * fusionChartsWidget Class file
 *
 * @author Vadim Gabriel <vadimg88[at]gmail[dot]com>
 * @link http://www.vadimg.com/
 * @copyright Vadim Gabriel
 * @license http://www.yiiframework.com/license/
 *
 */

/**
 * Description:
 * --------------
 * This extension allows you to create charts using the known fusion charts {@link http://fusioncharts.com} API.
 * It incorporates all the available methods exists in the fusion charts PHP Class library. 
 * With this extension you will be able to create charts in various types 
 * Such as Column2D, Column3D, Bar2D, Bar3D, Lines, Areas, Pies etc.
 * Fusion Charts online documentation can be found here: http://www.fusioncharts.com/docs/
 * 
 * Requirements:
 * --------------
 * Yii 1.1.x or above
 * 
 * Installation:
 * --------------
 * Extract the zip/tar archive and move the contents to your applications extensions directory.
 * So the fusionCharts.php file will be located under application/extensions/fusioncharts.
 * Then you need to initiate the component in the application configuration file by adding the following
 * code to the components array
 * 
 * 	'components'=>array(
 * 		.....
 * 		'fusioncharts' => array(
 * 					'class' => 'ext.fusioncharts.fusionCharts',
 * 			),
 * 	 ),
 * 
 * That will only make the fusion charts extension available to access as a component. Now we need to create a chart and display it.
 * 
 * Creating A Chart:
 * ------------------
 * First in your controller create an action, For this example we will call it 'actionChart'.
 * Inside that chart we start by calling the method {@link fusionCharts::setChartOptions()} and we pass an array
 * as the first argument of key=>value pairs that will work as <chart> element Attributes, In our example we set the caption, xAxisName and yAxisName.
 * 
 * Yii::app()->fusioncharts->setChartOptions( array( 'caption'=>'My Chart', 'xAxisName'=>'Months', 'yAxisName'=>'Revenue' ) );
 *
 * Now we would like to add sets, which are used for simple charts as the actual chart data. In order to add a set all we need to do is call
 * the method {@link fusionCharts::addSet()} and pass an array as the first and only argument to that method with the <set> element Attributes.
 * In our example we set the label and the set actual value:
 * 
 * Yii::app()->fusioncharts->addSet(array('label'=>'July', 'value'=>'680000'));
 * Yii::app()->fusioncharts->addSet(array('label'=>'August', 'value'=>'680000'));
 * Yii::app()->fusioncharts->addSet(array('label'=>'Jan', 'value'=>'680000'));
 * 
 * Note: For convince there is a helper function called {@link fusionCharts::addSets()} that you can pass an array of arrays that each array
 * will be treated as a set and will be added, So the above can be rewritten as follows:
 * 
 * 	$sets = array(
 *	   array('label'=>'July', 'value'=>'680000'),
 *	   array('label'=>'August', 'value'=>'680000'),
 *	   array('label'=>'Jan', 'value'=>'680000'),
 *	);
 *	
 *	Yii::app()->fusioncharts->addSets($sets);
 * 
 * In those charts you can add categories, In this extension that can be done by calling {@link fusioncharts::addCategory()}, In this
 * Method you can also pass an array as the first and only argument that will be treated as the <category> element attributes. 
 *
 * Yii::app()->fusioncharts->addCategory(array('label'=>'Jan'));
 * Yii::app()->fusioncharts->addCategory(array('label'=>'Feb'));
 * Yii::app()->fusioncharts->addCategory(array('label'=>'Mar'));
 * 
 * If you need to add multiple values at once without calling the addCategory method every time you can pass an array of arrays to the 
 * {@link fusioncharts::addCategories()} method similar to the addSets method, So the above code can be rewritten like the following:
 *
 * 	$categories = array(
 *	   array('label'=>'July'),
 *	   array('label'=>'August'),
 *	   array('label'=>'Jan'),
 *	);
 *	
 *	Yii::app()->fusioncharts->addCategories($categories);
 * 
 * Besides sets and categories you can create datasets by using the {@link fusioncharts::addDataSet()} and {@link fusioncharts::addSetToDataSet()}
 * Those two methods accept two arguments, the first is the dataset unique key and the second is an array of key=>value pairs that will act
 * as the <dataset> / <set> element attributes. So in order to create two datasets and add sets to those datasets we can use the following code:
 * 
 * 	// Add two data sets
 *	Yii::app()->fusioncharts->addDataSet('data_unique_key', $options);
 *	Yii::app()->fusioncharts->addDataSet('data_unique_key2', $options);		
 *	
 *	// Add three sets to the 'data_unique_key' data set
 *	Yii::app()->fusioncharts->addSetToDataSet('data_unique_key', $options);
 *	Yii::app()->fusioncharts->addSetToDataSet('data_unique_key', $options);
 *	Yii::app()->fusioncharts->addSetToDataSet('data_unique_key', $options);
 *	
 *	// Add two sets to the 'data_unique_key2' data set
 *	Yii::app()->fusioncharts->addSetToDataSet('data_unique_key2', $options);
 *	Yii::app()->fusioncharts->addSetToDataSet('data_unique_key2', $options);
 *  
 * 
 * You can also add trendlines, styles and applications to apply those to the right elements by using one/all of the following methods:
 *
 * 	Yii::app()->fusioncharts->addTrendLine(array('startValue'=>'700000', 'color'=>'009933', 'displayvalue'=>'Target'));
 *	Yii::app()->fusioncharts->addDefinition(array('name'=>'CanvasAnim', 'type'=>'animation', 'param'=>'_xScale', 'start'=>'0', 'duration'=>'1'));
 *	Yii::app()->fusioncharts->addApplication(array('toObject'=>'Canvas', 'styles'=>'CanvasAnim'));
 *
 * Sometimes you may want to add a vLine (a line separating columns) you can add vLines to both sets and categories by calling
 * {@link fusioncharts::addVLine()} and {@link fusioncharts::addCategoryVLine()} Both accept an array of options for the element attributes.
 * If you need to add a vLine to a data set then just use {@link fusioncharts::addDataSetVLine($key, $options)} 
 * 
 * If for some reason the labels on the chart do not display properly just set the property useI18N to true:
 *
 * Yii::app()->fusioncharts->useI18N = true;
 * 
 * Either in the application configuration or manually at any place in the application.
 *
 * To display the xml generated call {@link fusioncharts::getXMLData()} and the xml will be printed as string. If you want it to be printed as
 * an actual XML document with the correct content type headers pass in a boolean value true to that method.
 * 
 * In the fusion chart you can specify special messages that are passed with the .swf file in the url, To specify those just either apply the messages
 * to the Yii::app()->fusioncharts->chartMessage property or call {@link fusioncharts::setChartMessage($msg)}.
 * 
 * If you need to have a list of available charts then call either {@link fusioncharts::getChartTypes()} or {@link fusioncharts::getKeyValueChartTypes()}.
 *
 * By default the charts are cached by the URL, if you want to disable caching then simple set the property
 * Yii::app()->fusioncharts->chartNoCache to a boolean value of true.
 *
 * To get a list of commonly used colors in the chart call {@link fusioncharts::getColors()}
 *
 * If for some reason you want to add custom XML string to the generated XML Document you can simply call {@link fusioncharts::addCustomXmlData()}
 *
 * You can turn the chart in debug mode by setting the property Yii::app()->fusioncharts->debugMode to 1. This will display a block on top of the generated 
 * Chart with some development info.
 *
 * If you browsed through the Fusion charts PHP Class and you liked that one better you can use the methods in that class by simply calling those methods
 * as if they were inside this extension. So if that class has a method called 'test' You can call Yii::app()->fusioncharts->test(); and that method will be invoked.
 * Or you can also use the builder which implements that class Yii::app()->fusioncharts->builder->test() (both calls are the same), Same thing applies to class members
 * You would like to set or get, Yii::app()->fusioncharts->key = 'value'; or echo Yii::app()->fusioncharts->key;
 *
 * If at some point you need to reset the xml data just call {@link fusioncharts::resetXml()}.
 *
 * Note: All the add* methods return an object of the fusion charts, So you can chain methods. 
 * Ie: addset($options)->addSet($options)->addCategory($options)...
 *
 * Displaying A Chart:
 * ------------------
 * 
 * After creating the chart in the 'actionChart' action. We now want to display it. We can use the {@link fusionChartWidget} to display the generated chart.  
 * 
 * $this->widget('ext.fusioncharts.fusionChartsWidget', array( 
 *															  'chartNoCache'=>true, // disabling chart cache
 *															  'chartAction'=>Yii::app()->urlManager->createUrl('game/chart'), // the chart action that we just generated the xml data at
 *															  'chartId'=>'mychart')); // If you display more then one chart on a single page then make sure you specify and id
 *
 * The above code will display a 'Column2D' chart by default, if you want to display another chart simply set the property 'chartType' in the widget or globally in the application component
 * and render the widget again (use the helper function {@link fusioncharts::getKeyValueChartTypes()} for a list of available charts).
 * 
 * You can also specify the width and height of the chart by specifying the properties 'chartWidth' and 'chartHeight' in the widget or globally in the application component. 
 *
 * By default the widget will display the generated chart using JS supplied by fusionCharts. If you want to support users that do not have JS enabled then set the property 'htmlChart' to a boolean value true
 * in the widget properties or globally in the application component and the chart will be rendered using <embed> tag.
 * 
 *
 * Note: This extension is just a wrapper for the fustion charts service. You will need to browse trough their documentation and 
 * read through the available properties in order to create the chart you want to.
 * 
 */
class fusionChartsWidget extends CWidget
{
	/**
	 * @var $chartId unique chart ID
	 * @see {fusionCharts}
	 */
	public $chartId = null;
	/**
	 * @var $chartType Chart type (without the .swf suffix)
	 * Must be located under extensions.fusioncharts.assets.Charts
	 * @see {fusionCharts}
	 */
	public $chartType = null;
	/**
	 * @var $chartsPath Charts location path
	 * @see {fusionCharts}
	 */
	public $chartsPath = null;
	/**
	 * @var $assetsPath this extension assets path
     * @see {fusionCharts}
	 */
	public $assetsPath = null;
	/**
	 * @var $chartAction the action to load the chart data
	 * @see {fusionCharts}
	 */
	public $chartAction = null;
	/**
	 * @var $chartData the data string
	 * @see {fusionCharts}
	 */
	public $chartData = null;
	/**
	 * @var $chartWidth the chart flash width
	 * @see {fusionCharts}
	 */
	public $chartWidth = null;
	/**
	 * @var $chartHeight the chart flash height
	 * @see {fusionCharts}
	 */
	public $chartHeight = null;
	/**
	 * @var $registerWithJS registers the chart with the ability
	 * to add JS functions to drill down chrats @see Fusion Charts API Documentation
	 * @see {fusionCharts}
	 */
	public $registerWithJS = null;
	/**
	 * @var $debugMode enable fusion charts debug mode
	 * @see {fusionCharts}
	 */
	public $debugMode = null;
	/**
	 * @var $htmlChart display the fusion chart in an HTML form? 
	 * Using embed html tags instead of JS embedding.
	 * @see {fusionCharts}
	 */
	public $htmlChart = null;
	/**
	 * @var $useI18N if you are using language other then English
	 * And for some reason the language strings do not display properly
	 * assign a boolean value true to this property.
	 * @see {fusionCharts}
	 */
	public $useI18N = null;
	/**
	 * @var $chartTransparent Use transparent chart background?
	 * You can specify false for 'opaque' or true for 'transparent'
	 * @see {fusionCharts}
	 */
	public $chartTransparent = null;	
	/**
	 * @var $chartMessage the message that will be displayed on the chart
	 * @see {fusionCharts}
	 */
	public $chartMessage = null;
	/**
	 * @var $chartNoCache either true/false to cache or not the generated chart
	 * @see {fusionCharts}
	 */
	public $chartNoCache = null;
	
	/**
	 * Widget initiate function
	 * @return void
	 */
	public function init(  )
	{
		// Add path alias
		Yii::setPathOfAlias('_fc', dirname(__FILE__));
		
		// fusioncharts application configurations
		$this->chartId = $this->chartId !== null ? $this->chartId : Yii::app()->fusioncharts->chartId;
		$this->chartType = $this->chartType !== null ? $this->chartType : Yii::app()->fusioncharts->chartType;
		$this->chartsPath = $this->chartsPath !== null ? $this->chartsPath : Yii::app()->fusioncharts->chartsPath;
		$this->assetsPath = $this->assetsPath !== null ? $this->assetsPath : Yii::app()->fusioncharts->assetsPath;
		$this->chartAction = $this->chartAction !== null ? $this->chartAction : Yii::app()->fusioncharts->chartAction;
		$this->chartWidth = $this->chartWidth !== null ? $this->chartWidth : Yii::app()->fusioncharts->chartWidth;
		$this->chartHeight = $this->chartHeight !== null ? $this->chartHeight : Yii::app()->fusioncharts->chartHeight;
		$this->registerWithJS = $this->registerWithJS !== null ? $this->registerWithJS : Yii::app()->fusioncharts->registerWithJS;
		$this->debugMode = $this->debugMode !== null ? $this->debugMode : Yii::app()->fusioncharts->debugMode;
		$this->htmlChart = $this->htmlChart !== null ? $this->htmlChart : Yii::app()->fusioncharts->htmlChart;
		$this->chartTransparent = $this->chartTransparent !== null ? $this->chartTransparent : Yii::app()->fusioncharts->chartTransparent;
		$this->chartMessage = $this->chartMessage !== null ? $this->chartMessage : Yii::app()->fusioncharts->chartMessage;
		$this->chartNoCache = $this->chartNoCache !== null ? $this->chartNoCache : Yii::app()->fusioncharts->chartNoCache;
		
		// Set charts path
		$this->chartsPath = $this->chartsPath === null ? Yii::getPathOfAlias('_fc.assets') : $this->chartsPath;
		
		// Publish Chart
		$this->assetsPath = Yii::app()->assetManager->publish( $this->chartsPath );
	}
	
	/**
	 * run widget
	 * @return string
	 */
	public function run()
	{
		$this->render( $this->htmlChart === true ? 'charthtml' : 'chartjs' );
	}
}