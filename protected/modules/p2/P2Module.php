<?php
/**
 * Base module
 *
 * @package p2
 */
class P2Module extends CWebModule {

    private $_checks = array();
    private $_hasInstallationError = false;

    public function init() {
        // this method is called when the module is being created
        // you may place code here to customize the module or the application

        // import the module-level models and components
        $this->setImport(array(
            'p2.models.*',
            'p2.controllers.*',
            'p2.components.*',
            'p2.extensions.*',
            'p2.extensions.editor.*',
        ));
    }

    public function beforeControllerAction($controller, $action) {
        if(parent::beforeControllerAction($controller, $action)) {
            // this method is called before any module controller action is performed
            // you may place customized code here
            $this->layout = "main";
            return true;
        }
        else
            return false;
    }

    public function isInstalled() {
        $this->runInstallationChecks();
        return (count($this->getInstallationErrors())===0);
    }

    public function getInstallationErrors() {
        $this->runInstallationChecks();
        $return = array();
        foreach($this->_checks AS $key => $check) {
            if ($check['error'] === true) {
                $return[$key] = $check;
                $this->_hasInstallationError;
            }
        }
        return $return;
    }
    
    public function getInstallationWarnings() {
        $this->runInstallationChecks();
        $return = array();
        foreach($this->_checks AS $key => $check) {
            if ($check['warning'] === true)
                $return[$key] = $check;
        }
        return $return;
    }

    private function runInstallationChecks() {
        
        $consoleConfigFile = Yii::app()->basePath.DS.'config'.DS.'console.php';
        if(!is_file($consoleConfigFile)) {
            $this->_checks['console.config.file'] = new CMap(array('error' => true, 'message' => "Console config file not found", 'hints' => array('create file')));
        } else {
            $consoleConfig = include($consoleConfigFile);
            if (!isset($consoleConfig['commandMap']['install'])) {
                $this->_checks['console.config.commandMap'] = new CMap(array('error' => true, 'message' => "Phundament commands (p2,install) not imported in console config", 'hints' => array('import p2 command map')));
            }
        }
        
        $params = Yii::app()->params;
        
        $this->checkConfigurationParameter('adminEmail', 'error', array('update application configuration'));
        $this->checkConfigurationParameter('languages', 'error', array('update application configuration'));
        $this->checkConfigurationParameter('publicRuntimeUrl', 'warning', array('update application configuration'));
        
        $this->checkConfigurationParameterDirectory('publicRuntimePath', 'warning', array('update application configuration'));
        $this->checkConfigurationParameterDirectory('protectedRuntimePath', 'warning', array('update application configuration'));
        $this->checkConfigurationParameterDirectory('protectedDataPath', 'warning', array('update application configuration'));
        
        $this->checkModel('P2Html', 'error', array('run install command'));
        $this->checkModel('P2File', 'error', array('run install command'));
        $this->checkModel('P2Info', 'error', array('run install command'));
        $this->checkModel('P2Cell', 'error', array('run install command'));
        $this->checkModel('P2Page', 'error', array('run install command'));
        $userTableCheck = $this->checkModel('P2User', 'error', array('run install command'));
        
        $this->checkModel('P2Log', 'error', array('run install command'));

        /*
        Error without db
        try {
                if(P2User::model()->findByPk(1) == null) {
                    $this->_checks['p2.db.P2User'] = new CMap(array('error' => true, 'message' => "Admin user not found", 'hints' => array('run install command')));
                }
        } catch (Exception $e) {
            $this->_checks['p2.db.P2User'] = new CMap(array('error' => true, 'message' => $e->getMessage(), 'hints' => array()));
        }*/

        $this->checkModule('srbac', 'warning', array('download'));

        $this->checkLib('Zend', 'warning', array('download'));

    }
    
    private function checkConfigurationParameter($name, $level = 'error', $hints = array('')){
        $params = Yii::app()->params;
            if (!isset($params[$name])) {
                $this->_checks['app.config.params.'.$name] = new CMap(array($level => true, 'message' => "Application parameter '".$name."' not set", 'hints' => $hints));
            }
    
    }

    private function checkConfigurationParameterDirectory($paramName, $level = 'error', $hints = array('')){
            $params = Yii::app()->params;

        if(!isset($params[$paramName])){
            $this->_checks['app.config.params.'.$paramName] = new CMap(array($level => true, 'message' => "Application parameter '".$paramName."' not set", 'hints' => $hints));
        } else {
            $dir = Yii::app()->basePath.DS.$params[$paramName];
            if (!is_dir($dir)) {
                $this->_checks['filesystem.directories.'.$paramName] = new CMap(array($level => true, 'message' => "Directory '".$dir."' does not exist, application parameter '".$paramName."', value '".$params[$paramName]."'", 'hints' => $hints));
            } else {
            if(!is_writable(Yii::app()->basePath.DS.$params[$paramName])){
                $this->_checks['filesystem.permissions.'.$paramName] = new CMap(array($level => true, 'message' => "Directory '".$dir."' is not writable, application parameter '".$paramName."', value '".$params[$paramName]."'", 'hints' => $hints));
            }
            }
        }    
    }

    private function checkModel($model, $level = 'error', $hints = array('')){
        try {
    	   call_user_func(array($model, 'model'));
        } catch(Exception $e) {
            $this->_checks['p2.database.'.$model] = new CMap(array($level => true, 'message' => $e->getMessage(), 'hints' => $hints));
            return false;
        }
    }

    private function checkModule($name, $level = 'error', $hints = array('')){
        if(!is_dir(Yii::app()->getModulePath().DS.$name)){
            $this->_checks['modules.srbac'] = new CMap(array($level => true, 'message' => 'Module '.$name.' is not present', 'hints' => $hints));
            return false;
        } else
        return true;
        
    }
    
    private function checkLib($name, $level = 'error', $hints = array('')){
        $dir = Yii::getPathOfAlias("lib.Zend");
        if(!is_dir($dir)){
            $this->_checks['lib.zend'] = new CMap(array($level => true, 'message' => 'Library '.$name.' is not present in '.$dir, 'hints' => $hints));
    return false;
    } else
            return true;
    }
}
