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
 * Widget, transforms input field value
 *
 * Detailed info
 * <pre>
 * $var = code_example();
 * </pre>
 * {@link DefaultController}
 *
 * @author Tobias Munk <schmunk@usrbin.de>
 * @version $Id: DefaultController.php 510 2010-03-24 00:39:06Z schmunk $
 * @package p2.widgets
 * @since 2.0
 */

class P2NameNormalizer extends CWidget{

    public $selector;

    function init() {
        $langOnly = substr(Yii::app()->language,0,2);
        $js = "function normalizeString(selector){
                str = $(selector).val();
                str = str.replace (/^\s+/, '').replace (/\s+$/, ''); // trim
                if (!str.match(/[a-z]{1,}\-[a-z]{0,}\-/)) { // add type and lang
                    str = '".$langOnly."-'+$('#P2Info_type').val()+'-'+str;
                } 
                str = str.replace(/[^a-zA-Z0-9.\-_]+/g,'_'); // remove non-alphanumeric chars
               
                $(selector).val(str);
            }";
        Yii::app()->clientScript->registerScript('P2NameNormalizer',$js,CClientScript::POS_END);
    }   
    function run() {
     
        echo CHtml::link(
            'Normalize name', '#', 
            array(
                'onclick'=>'normalizeString("'.$this->selector.'");'
                )
            );
    }
}

?>