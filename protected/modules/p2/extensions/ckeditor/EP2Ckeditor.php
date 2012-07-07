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
 * Widget, wraps ckeditor
 *
 * Detailed info
 * <pre>
 * $var = code_example();
 * </pre>
 * {@link DefaultController}
 *
 * @author Tobias Munk <schmunk@usrbin.de>
 * @version $Id: EP2Ckeditor.php 537 2010-03-30 23:24:21Z schmunk $
 * @package extensions.ckeditor
 * @since 2.0
 */
class EP2Ckeditor extends CWidget {
    
    public $name;
    public $path;
    public $config = array(); // http://docs.cksource.com/ckeditor_api/symbols/CKEDITOR.config.html

    function init(){
        if (!Yii::app()->params['ckeditor']) {
            throw new Exception("Parameters for 'ckeditor' not set!");
        }
        /*parent::init();*/
        $cs = Yii::app()->clientScript;
        $am = Yii::app()->assetManager;

        $this->path = $assets = $am->publish(dirname(__FILE__).DS."ckeditor-3.2.1");
        $script = "var CKEDITOR_BASEPATH = '".$this->path."/'";
        $cs->registerScript('CKEDITOR_GETURL', $script, CClientScript::POS_HEAD);
        $cs->registerScriptFile($assets."/ckeditor.js", CClientScript::POS_END);
        
        $this->config['baseHref'] = Yii::app()->request->hostInfo.Yii::app()->request->baseUrl.DS;

        $id = $this->name.'Replace';
        $script = "$(document).ready(function(){CKEDITOR.replace('".$this->name."',".CJavaScript::encode($this->config).");})";
        $cs->registerScript($id, $script, CClientScript::POS_END);
    }

    function run(){
        #echo "editor";
    }
}
?>
