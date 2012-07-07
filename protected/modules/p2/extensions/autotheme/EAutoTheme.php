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
 * Widget, includes css and js files from theme directories
 *
 * Detailed info
 * <pre>
 * $var = code_example();
 * </pre>
 * {@link DefaultController}
 *
 * @author Tobias Munk <schmunk@usrbin.de>
 * @version $Id: EAutoTheme.php 508 2010-03-24 00:35:21Z schmunk $
 * @package extensions.autotheme
 * @since 2.0
 */
class EAutoTheme extends CWidget
{
    private $_themePath = null;
    private $_themeUrl = null;
    private $_themeCssDir = "css";
    private $_themeJsDir = "js";
    private $_themeImagesDir = "images";

    public function init()
    {
        if (isset($this->CssDir)) {
            $this->_themeCssDir = $this->CssDir;
        }
        if (isset($this->JsDir)) {
            $this->_themeJsDir = $this->JsDir;
        }

        if (Yii::app()->theme !== null){
            $this->_themePath = Yii::app()->themeManager->basePath.DS.Yii::app()->theme->name;
            $this->_themeUrl = Yii::app()->themeManager->baseUrl.'/'.Yii::app()->theme->name;
        }
    }

    public function run()
    {
        $cs = Yii::app()->getClientScript();
        $cssPath = $this->_themePath.DS.$this->_themeCssDir;
        Yii::trace("Scanning css and js folder in theme directory ".$this->_themePath." ...", "extensions.autotheme");
        if(is_dir($cssPath)){
            foreach (scandir($cssPath) AS $file){
                if ((substr($file,-3,3) == "css" && !is_file($cssPath.DS.$file.'.php')) || (substr($file,-7,7) == "css.php")){
                    $cssLocation = $this->_themeUrl.'/'.$this->_themeCssDir.'/'.$file;
                    $cs->registerCssFile($cssLocation);
                }
            }
        }

        $jsPath = $this->_themePath.DS.$this->_themeJsDir;
        if(is_dir($jsPath)){
            foreach (scandir($jsPath) AS $file){
                if (substr($file,-2,2) == "js"){
                    $jsLocation = $this->_themeUrl.'/'.$this->_themeJsDir.'/'.$file;
                    
                    switch(strrchr(str_replace('.js','',$file),'.')) {
                        case '.head':
                            $cs->registerScriptFile($jsLocation, CClientScript::POS_HEAD);
                            break;                            
                        case '.load':
                            $cs->registerScriptFile($jsLocation, CClientScript::POS_LOAD);
                            break;                            
                        case '.begin':
                            $cs->registerScriptFile($jsLocation, CClientScript::POS_BEGIN);
                            break;                            
                        case '.ready':
                            $cs->registerScriptFile($jsLocation, CClientScript::POS_READY);
                            break;                            
                        case '.end':
                            $cs->registerScriptFile($jsLocation, CClientScript::POS_END);
                            break;                            
                        default:
                            $cs->registerScriptFile($jsLocation);
                            break;
                    }

                }
            }
        }

        if(is_file($this->_themePath.DS.$this->_themeImagesDir).DS.'favicon.ico') {
	        $cs->registerLinkTag('shortcut icon', 'image/x-icon', $this->_themeUrl.'/'.$this->_themeImagesDir.'/favicon.ico');
        }

    }
}


?>