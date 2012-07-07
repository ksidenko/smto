<?php
/**
 * Application Component
 *
 * @package extensions.zend
 */
class EZendAutoloader extends CApplicationComponent {

    public $libraryAlias = "application.lib";

    public function init() {
        Yii::import($this->libraryAlias.".*");
        if(is_file(Yii::getPathOfAlias($this->libraryAlias).DS."Zend".DS."Loader".DS."Autoloader.php")) {
            Yii::import($this->libraryAlias.".Zend.Loader.Autoloader",true);
            spl_autoload_unregister(array('YiiBase','autoload'));
            spl_autoload_register(array('Zend_Loader_Autoloader','autoload'));
            spl_autoload_register(array('YiiBase','autoload'));
            Yii::trace("Zend autoloader registered","extensions.zend");
        } else {
            Yii::log("Zend autoloader could not be found", CLogger::LEVEL_ERROR,"extensions.zend");
        }
        parent::init();
    }

}
