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
 * Widget, wraps mbmenu, a jQuery based drop-down menu
 *
 * See p2/extensinos/mbmenu/examples/ for sample code
 *
 * @todo more details ...
 * {@link http://pupunzi.open-lab.com/mb-jquery-components/mb-_menu/}
 *
 * @author Tobias Munk <schmunk@usrbin.de>
 * @version $Id: EP2Mbmenu.php 537 2010-03-30 23:24:21Z schmunk $
 * @package extensions.p2mbmenu
 * @since 2.0
 */

/*
 * DEFAULT OPTIONS
 * options: {
 template:"yourMenuVoiceTemplate",--> the url that returns the menu voices via ajax. the data passed in the request is the "menu" attribute value as "menuId"
 additionalData:"",--> if you need additional data to pass to the ajax call
 menuSelector:".menuContainer",--> the css class for the menu container
 menuWidth:150,--> min menu width
 openOnRight:false,--> if the menu has to open on the right insted of bottom
 iconPath:"ico/",	--> the path for the icons on the left of the menu voice
 hasImages:true,	--> if the menuvoices have an icon (a space on the left is added for the icon)
 fadeInTime:100,	--> time in milliseconds to fade in the menu once you roll over the root voice
 fadeOutTime:200,	--> time in milliseconds to fade out the menu once you close the menu
 menuTop:0,--> top space from the menu voice caller
 menuLeft:0,	--> left space from the menu voice caller
 submenuTop:0,--> top space from the submenu voice caller
 submenuLeft:4,--> left space from the submenu voice caller
 opacity:1,--> opacity of the menu
 shadow:false,--> if the menu has a shadow
 shadowColor:"black",	--> the color of the shadow
 shadowOpacity:.2,--> the opacity of the shadow
 openOnClick:true,--> if the menu has to be opened by a click event (otherwise is opened by a hover event)
 closeOnMouseOut:false,--> if the menu has to be cloesed on mouse out
 closeAfter:500,	--> time in millisecond to whait befor closing menu once you mouse out
 minZindex:"auto", --> if set to "auto" the zIndex is automatically evaluate, otherwise it start from your settings ("auto" or int)
 hoverInted:0, --> if you use jquery.hoverinted.js set this to time in milliseconds to delay the hover event (0= false)
 onContextualMenu:function(o,e){} --> a function invoked once you call a contextual menu; it pass o (the menu you clicked on) and e (the event)
 },
 */

class EP2Mbmenu extends CWidget {
    public $cssFile = null;
    public $cssDir = null;
    public $cssSelector = '.p2Mbmenu'; // selector for menu template, rootVoice and subMenu
    public $menuSelector = '.p2MbmenuContainer'; // selector for css-file, styles will be applied to items in the page
    public $template = null;
    public $iconPath = null;
    public $params = array();
    private $_defaultParams = array(
        'template' => null,
        'additionalData'  => "",
        'menuWidth'  => 200,
        'openOnRight' => false,
        'iconPath' => null,
        'hasImages' => true,
        'fadeInTime' => 100,
        'fadeOutTime' => 300,
        'adjustLeft' => 2,
        'minZindex' => 'auto',
        'adjustTop' => 10,
        'opacity' => .95,
        'shadow' => false,
        'openOnClick' => false,
        'closeOnMouseOut' => true,
        'closeAfter' => 1000
    );


    public function init() {
        foreach($this->_defaultParams AS $name => $value) {
            if (!isset($this->params[$name])) {
                $this->params[$name] = $value;
            };
        }

        $am = Yii::app()->assetManager;
        $cs = Yii::app()->clientScript;
        $path = dirname(__FILE__).DS.'pupunzi-jquery.mb.menu-2.7.6';

        $this->iconPath = ($this->iconPath) ? $this->iconPath : $am->publish($path.DS."ico");
        $this->params['template'] = ($this->template) ? $this->template : "";#Yii::app()->assetManager->publish(dirname(__FILE__).DS."menuVoices.html");
        $this->params['iconPath'] = $this->iconPath.'/';
        $this->params['menuSelector'] = $this->menuSelector;
        
        
        $cs->registerScriptFile($am->publish($path.DS."inc/jquery.metadata.js"));
        $cs->registerScriptFile($am->publish($path.DS."inc/jquery.hoverIntent.js"));
        $cs->registerScriptFile($am->publish($path.DS."inc/mbMenu.js"));

        if ($this->cssFile) {
            if ($this->cssDir !== null) {
               $am->publish($this->cssDir);
            }
            $cs->registerCssFile($this->cssFile);
        } else
        {
            $am->publish($path.DS."css");
            $cs->registerCssFile($am->publish($path.DS."css/menu.css"));
        }

    }

    public function run()
    {
        #var_dump($this->cssSelector);
        $cs = Yii::app()->clientScript;
        $cs->registerScript($this->cssSelector, $this->getMenuConfig());

    }

    public function getMenuConfig() {
        $script = '$(function(){
            $("'.$this->cssSelector.'").buildMenu('.CJSON::encode($this->params).');
            });';
        
        return $script;
    }
}

?>
