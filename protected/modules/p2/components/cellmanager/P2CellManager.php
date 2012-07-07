<?php
/**
 * Class File
 *
 * @author Tobias Munk <schmunk@usrbin.de>
 * @link http://www.phundament.com/
 * @copyright Copyright &copy; 2005-2010 diemeisterei GmbH
 * @license http://www.phundament.com/license/
 */

/**
 * Widget which aggrgates and manages other widgets
 *
 * Config Options:
 * -
 *
 * @author Tobias Munk <schmunk@usrbin.de>
 * @version $Id: P2CellManager.php 521 2010-03-24 13:20:13Z schmunk $
 * @package p2.cellmanager
 * @since 2.0
 */
Yii::import("application.modules.p2.models.*");
Yii::import("application.modules.p2.components.*");
Yii::import("application.modules.p2.extensions.*");
Yii::import("system.web.helpers.CJSON",true); // needed for cached values!

class P2CellManager extends CWidget {

    const CSSCLASS_CELL   = 'p2CellManager';
    const CSSCLASS_DIALOG = 'dialog';
    const CSSCLASS_INPUT  = 'classProps';
    const CSSCLASS_NORMAL = 'widgetContent';
    const CSSCLASS_ADMIN  = 'widgetAdmin';
    const CSSCLASS_WIDGET_STATUS = "widgetStatus";
    const WIDGET_STATUS_UNSAVED = 0;
    const WIDGET_STATUS_SAVED = 1;
    const GET_PARAM_ADMIN_CONTROLS = 'p2CellManager_adminControls';

    public $widgetWrapperPre = '';
    public $widgetWrapperPost = '';

    public $varyByCellId = true;
    public $varyByModuleId = true;
    public $varyByControllerId = true;
    public $varyByActionName = true;
    public $varyByRequestParam = false;
    public $varyBySessionParam = false;
    public $varyByCookieParam = false;
    public $varyByApplicationParam = false;
    public $varyByModuleParam = false;

    public $availableWidgets;

    protected $_params = array();
    protected $_config = array();

    private $_cssFile;


    /**
     * Initializes the widget
     */
    public function init() {
        Yii::trace("Initializing #".$this->id.'...', 'p2.cellmanager');
        if (isset(Yii::app()->params["p2.cellManager.availableWidgets"])) {
            $this->availableWidgets = Yii::app()->params["p2.cellManager.availableWidgets"];
        }
        else {
            throw new CException("Module configuration parameter 'p2CellManager.availableWidgets' not found!");
        }

        $this->_params['cellId'] = $this->id;
        $this->_params['controllerId'] = $this->controller->id;
        $this->_params['actionName'] = $this->controller->action->id;

        if ($this->controller->module !== null && $this->varyByModuleId === true) {
            $this->_params['moduleId'] = $this->controller->module->Id;
            if ($this->varyByModuleParam != false) {
                $this->_params['moduleParam'] = $this->controller->module->params[$this->varyByApplicationParam];
            }
        }

        if (isset($_REQUEST[$this->varyByRequestParam])) {
            $this->_params['requestParam'] = $_REQUEST[$this->varyByRequestParam];
        }
        if (isset($_SESSION[$this->varyBySessionParam])) {
            $this->_params['sessionParam'] = $_SESSION[$this->varyBySessionParam];
        }
        if (isset($_COOKIE[$this->varyByCookieParam])) {
            $this->_params['cookieParam'] = $_COOKIE[$this->varyByCookieParam];
        }

        if ($this->varyByApplicationParam != false) {
            $this->_params['applicationParam'] = Yii::app()->params[$this->varyByApplicationParam];
        }

        foreach(get_class_vars(get_class($this)) AS $param => $value) {
            if (substr($param, 0, 6) == "varyBy") {
                #if ($this->$param) {
                #   $data[$param] = $this->$param;
                #}
                $varyBy = str_replace("varyBy",'',$param);
                $this->_config[$varyBy] = $this->$param;
            }
        }


    }

    /**
     * Executes the widget
     *
     * In user-mode widgets are rendered without errors
     */
    public function run() {
        if (Yii::app()->user->checkAccess('widgetManager') && $this->getShowAdminControls()) {
            // render admin interface
            $this->registerClientScripts();

            $this->render(
                'main',
                array('cells'=>$this->getCells('localized'),)
            );
        } else {
            // render content
            foreach($this->getCells('default') AS $cell) {
                echo $this->widgetWrapperPre;
                echo '<div class="'.self::CSSCLASS_NORMAL.'">';
                echo $this->prepareWidgetContent($cell);
                echo '</div>'.$this->widgetWrapperPost;
            }
        }
        Yii::trace("Finished #".$this->id.'.', 'p2.cellmanager');
    }

    private function getShowAdminControls() {
        if (isset($_GET[self::GET_PARAM_ADMIN_CONTROLS]) && $_GET[self::GET_PARAM_ADMIN_CONTROLS] === "0")
            return false;
        else
            return true;
    }

    /**
     * Get filter for active record from {@link _params}
     *
     * Returns a JSON string
     *
     * @return string
     */
    protected function getFilter() {
        $return = CJSON::encode($this->_params);
        return $return;
    }

    /**
     * Registers custom stylesheet and javascript files
     */
    public function registerClientScripts() {
        #$this->_cssFile = P2Helper::configure('cTabView:cssFile');
        $script = dirname(__FILE__).DS.'p2CellManager.css';
        $url = Yii::app()->assetManager->publish($script);
        Yii::app()->clientScript->registerCssFile($url);

        $script = dirname(__FILE__).DS.'p2CellManager.js';
        $url = Yii::app()->assetManager->publish($script);
        Yii::app()->clientScript->registerScriptFile($url);
    }

    /**
     * Creates widget content for a single widget
     *
     * @param P2Cell Widget Active Record
     * @param boolean Show errors
     * @return string
     */
    protected function prepareWidgetContent($cell, $displayError = false) {
        Yii::trace("Preparing widget content #".$cell->id.' ('.$cell->classPath.') ...', 'p2.cellmanager');
        $error = false;
        $className = P2Helper::classExists($cell->classPath);

        $classProps = self::getCellClassProps($cell);

        if ($className) {
            if (!is_array($classProps)) {
                $classProps = array();
                $msg = "Malformed properties for widget #".$cell->id;
                Yii::log($msg, CLogger::LEVEL_WARNING, "p2.P2CellManager");
                $error = true;
            }
            try { // to render widget
                ob_start();
                $widget = self::instantiateWidget($cell);
                if ($widget)
                    $widget->run();
                else
                    throw new Exception("Could not instantiate widget {$cell->classPath}! see log for more information.");
                #$this->widget($className, array('model'=>$model)); // TODO ? - ERRORS SUPPRESSED !!!
                $widgetContent = ob_get_clean();
            } catch (Exception $e) {
                $widgetContent = ob_get_clean(); // make sure to get any output! but render?
                $widgetContent .= "<div class='errorSummary'><h1>P2Cell Error</h1><p>".$e->getMessage()."</p></div>";
                Yii::log($e->getMessage(), CLogger::LEVEL_WARNING, "p2.P2CellManager");
                $error = true;
            }
        } else {
            $msg = "Missing widget ".$className."!";
            $widgetContent = "<div class='errorSummary'><h1>P2Cell Error</h1><p>$msg</p></div>";
            Yii::log($msg, LOG_WARNING, "p2.P2CellManager");
            $error = true;
        }
        if ((!$error) || ($displayError && $error))
            return $this->widgetWrapperPre.$widgetContent.$this->widgetWrapperPost.P2Helper::clearfloat();
    }

    /**
     * Creates a widget instance from P2Cell model
     *
     * Sets classProps or model attributes, if instance implements {@link ICellManagerWidget}
     *
     * @param P2Cell $cell P2Cell model
     * @return Object Widget instance
     */
    public static function instantiateWidget($cell) {
        if (P2Helper::classExists($cell->classPath)) {
            $classProps = self::getCellClassProps($cell);
            if (is_array($classProps)) {
                try {
                    $config = array_merge(array('class'=>$cell->classPath));
                    
                    // we need to determine widget type
                    $widget = Yii::createComponent($config);
                    if ($widget instanceof ICellManagerWidget) {
                        // taken from CBaseController
                        if(($factory=Yii::app()->getWidgetFactory())!==null)
                		$widget=$factory->createWidget(Yii::app()->controller,$cell->classPath);
                        $widget->model->setAttributes($classProps);
                        // special primary key treatment
                        if ($widget->model instanceof CActiveRecord && isset($classProps[$widget->model->tableSchema->primaryKey])) {
                            $widget->model->setPrimaryKey($classProps[$widget->model->tableSchema->primaryKey]);
                        }
                        $widget->init();
                    } else {
                        $widget = Yii::app()->controller->createWidget($cell->classPath, $classProps);
                    }
                    return $widget;
                } catch (Exception $e) {
                    Yii::log($e->getMessage(), CLogger::LEVEL_WARNING, "p2.cellmanager");
                    return null;
                }
            }
        }
        #throw new Exception('Widget '.$cell->classPath.' could not be created');
    }

    private static function getCellClassProps($model) {

        $array = false;
        $array = Yii::app()->cache->get("P2Cell.classProps.".md5($model->classProps));
        if ($array == false) {
            $array = CJSON::decode($model->classProps);
            Yii::app()->cache->set("P2Cell.classProps.".md5($model->classProps), $array, 0);
        }
        return $array;
    }

    /**
     * Retrieves all widgets for the current cell
     *
     * @return array
     */
    private function getCells($scope = null) {

        $cacheKey = $scope.CJSON::encode($this->_params);

        #if($value = Yii::app()->cache->get($cacheKey)){
        #    return $value;
        #}

        $cell = new P2Cell;
        $criteria = new CDbCriteria();
        $criteria->order = "rank";
        foreach($this->_params AS $key => $param) {
            $criteria->condition .= "(".$key." = :".$key." OR ".$key." = '') AND "; // todo: should be NUL
            $criteria->params[":".$key] = $param;
        }
        $criteria->condition .= "1 = 1";
        if ($scope !== null){
            $return = $cell->model()->$scope()->with('P2Info')->findAll($criteria);
        } else {
            $return = $cell->model()->with('P2Info')->findAll($criteria);
        }

        // FIXME: Solution for deleted records?
        #$dependency = new CDbCacheDependency("SELECT MAX(modifiedAt) FROM P2Cell LEFT OUTER JOIN P2Info ON P2Info.modelId = P2Cell.id WHERE P2Info.model = 'P2Cell'");
        #Yii::app()->cache->set($cacheKey, $return, 0, $dependency);

        return $return;
    }

    /**
     * Internal use only
     *
     * @param P2Cell Widget Active Record
     * @return string
     * @todo ugly code! FIXME
     */
    protected static function getWidgetHeadline($object) {

        if ($object instanceof ICellManagerWidget) {
            try {
                $name = $object->getHeadline();
            } catch (Exception $e) {
                $name = "[n/a]";
            }
        } elseif (is_object($object)) {
            $name = get_class($object);
        } else {
            $name = "[error]";
        }

        return $name;
    }


}

?>
