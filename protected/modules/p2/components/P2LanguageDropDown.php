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
 * Widget for changing application language
 *
 * Detailed info
 * <pre>
 * $var = code_example();
 * </pre>
 * {@link DefaultController}
 *
 * @author Tobias Munk <schmunk@usrbin.de>
 * @version $Id: P2LanguageDropDown.php 506 2010-03-24 00:32:15Z schmunk $
 * @package p2.widgets
 * @since 2.0
 */
class P2LanguageDropDown extends CWidget {
    function run() {

        $name = "lang";        
        $select = Yii::app()->language;
        $data = Yii::app()->params['languages'];

        $htmlOptions = array('id' => uniqid(get_class()), 'submit'=>'');

        if($this->controller->action->id == "error")
            $htmlOptions['disabled'] = true;
        if(P2Page::getActivePage() !== null && P2Page::getActivePage()->P2Info->language != null)
            $htmlOptions['disabled'] = true;

        $params = CMap::mergeArray($_GET,array('lang'=>'__EMPTY__'));
        
        $code = CHtml::beginForm($this->controller->createUrl($this->controller->id."/".$this->controller->action->id, $params), 'get');
        $code .= CHtml::dropDownList($name, $select, $data, $htmlOptions);
        $code .= CHtml::endForm();

        echo $code;
    }
}
?>
