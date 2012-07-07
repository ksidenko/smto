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
 * Widget displaying custom CRUD navigation
 *
 * The navigation menu is generated automatically determining the current action.
 *
 * @author Tobias Munk <schmunk@usrbin.de>
 * @version $Id: P2CrudActionBar.php 524 2010-03-24 22:54:23Z schmunk $
 * @package p2.widgets
 * @since 2.0
 */
class P2CrudActionBar extends CWidget {

    public $id;
    /**
     *
     * @var array() Key is the name to be displayed, value is an array {@link CHtml::createUrl}
     */
    public $extraLinks = null;

    function run() {
        $return = "<div class=''>";
        if (!P2Helper::return_url()) {
            $return .= CHtml::link(
                '<span class="ui-icon ui-icon-home"></span>'.Yii::t('P2Module.p2','Home'),
                Yii::app()->homeUrl,
                array('class'=>P2Helper::juiButtonCssClass()))." ";
            if($this->controller->module !== null) {
            $return .= CHtml::link(
                '<span class="ui-icon ui-icon-suitcase"></span>'.Yii::t('P2Module.p2','Module'),
                array('/'.$this->controller->module->id.'/default/index'),
                array('class'=>P2Helper::juiButtonCssClass()))." ";
            }
            if($this->id && $this->controller->action->id != 'update') {
                $return .= CHtml::link(
                    '<span class="ui-icon ui-icon-pencil"></span>'.Yii::t('P2Module.p2','Update'),
                    array('update','id'=>$this->id, 'return_url' => P2Helper::return_url()),
                    array('class'=>P2Helper::juiButtonCssClass()))." ";
            }
            $return .= CHtml::link(
                '<span class="ui-icon ui-icon-plus"></span>'.Yii::t('P2Module.p2','New'),
                array('create', 'return_url' => P2Helper::return_url()),
                array('class'=>P2Helper::juiButtonCssClass()))." ";
            if ($this->controller->action->id != 'list') {
                $return .= CHtml::link(
                    '<span class="ui-icon ui-icon-note"></span>'.Yii::t('P2Module.p2','List'),
                    array('list', 'return_url' => P2Helper::return_url()),
                    array('class'=>P2Helper::juiButtonCssClass()))." ";
            }
            if ($this->controller->action->id != 'admin') {
                $return .= CHtml::link(
                    '<span class="ui-icon ui-icon-gear"></span>'.Yii::t('P2Module.p2','Manage'),
                    array('admin', 'return_url' => P2Helper::return_url()),
                    array('class'=>P2Helper::juiButtonCssClass()))." ";
            }
            if($this->id) {
                $return .= CHtml::linkButton(
                    '<span class="ui-icon ui-icon-trash"></span>'.Yii::t('P2Module.p2','Delete'),
                    array(
                    'submit'=> array(
                        'delete',
                        'id'=>$this->id),
                    'confirm'=>'Are you sure?',
                    'return_url' => P2Helper::return_url(),
                    'class'=>P2Helper::juiButtonCssClass()))." ";
            }
        } else {
            $return .= CHtml::link(
                '<span class="ui-icon ui-icon-arrow-1-w"></span>'.Yii::t('P2Module.p2','Return'),
                P2Helper::return_url(),
                array('class'=>P2Helper::juiButtonCssClass()));
        }

        if (is_array($this->extraLinks)) foreach ($this->extraLinks as $text => $url) {
                $return .= CHtml::link(
                    $text,
                    $url,
                    array('class'=>P2Helper::juiButtonCssClass()));
            }
            $return .= "</div>";

        #$return .= P2Helper::clearfloat();

        echo $return;
    }
}
?>
