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
 * Widget for displaying P2Info form fields
 *
 * Can be rendered as 'form' or 'table'
 *
 * @author Tobias Munk <schmunk@usrbin.de>
 * @version $Id: P2InfoInputWidget.php 527 2010-03-28 19:32:03Z schmunk $
 * @package p2.widgets
 * @since 2.0
 */
class P2InfoInputWidget extends CWidget {

    /**
     * Record this widget's record should belong to
     * @var <type> 
     */
    public $owner= null;
    /**
     * Render-mode of this widget
     * @var <type> form|table
     */
    public $display= 'form';
    private $_model = null;

    function init() {
        if ((!isset(Yii::app()->params['languages']))) {
            throw new Exception("Missing application parameter 'languages'!");
        }
        if (isset($_POST['P2Info'])) {
            $this->_model = new P2Info;
            $this->_model->attributes = $_POST['P2Info'];
        }
        elseif (isset($this->owner->P2Info)) {
            #echo "modmd";
            $this->_model = $this->owner->P2Info;
        } elseif (isset($this->owner->p2_infoId)) {
            $this->_model = P2Info::model()->findByPk($this->owner->p2_infoId);
        } else {
            $this->_model = new P2Info;
            #$this->_model->language = Yii::app()->language;
        }
        #var_dump($this->owner);exit;
    }

    function run() {
        #echo get_class($this->owner);
        if ($this->_model === null) {
            Yii::log('Error finding P2Info model for '.get_class($this->owner).'#'.$this->owner->id, CLogger::LEVEL_ERROR, 'p2.P2Info');
            return;
        }
        if ($this->_model->isNewRecord) { // FIXME
            if (isset($_GET['P2Info']))
                $this->_model->attributes = $_GET['P2Info'];#return;
        }
        if ($this->display == 'table') {
            $code = $this->prepareDisplay();
        } else {
            $code = $this->prepareForm();
        }
        echo $code;
    }

    public function prepareForm() {
        $model = $this->_model;
        $code = "<fieldset><legend>Meta Data</legend>";
        $code .= '<p>';
        $code .= CHtml::activeLabelEx($model,'p2_infoId');
        $code .= CHtml::encode($model->id);
        $code .= '</p>';
        $code .= '<p>';
        $code .= CHtml::activeLabelEx($model,'language');
        $code .= CHtml::activeDropDownList(
            $model,
            'language',
            Yii::app()->params['languages'],
            array('prompt'=>Yii::t('P2Module.p2','all'))
        );
        $code .= '<span class="hint">'.Yii::t('P2Module.p2','Item will be available in this language.').'</span>';
        $code .= '</p>';
        $code .= '<p>';
        $code .= CHtml::activeLabelEx($model,'status');
        $code .= CHtml::activeDropDownList($model, 'status', P2Info::statusOptions());
        $code .= '</p>';
        $code .= '<p>';
        $code .= CHtml::activeLabelEx($model,'type');
        $code .= CHtml::activeDropDownList($model, 'type', P2Info::types(get_class($this->owner)), array('empty'=>'(none)'));
        $code .= '<span class="hint">'.Yii::t('P2Module.p2','Special item type, i.e. \'blog\'.').'</span>';
        $code .= '</p>';
        $code .= '<p>';
        $code .= CHtml::activeLabelEx($model,'checkAccess');
        $code .= CHtml::activeDropDownList($model, 'checkAccess', P2Info::checkAccess(), array('empty' => '(all)'));
        $code .= '<span class="hint">'.Yii::t('P2Module.p2','Item is only accessible for member of this role.').'</span>';
        $code .= '</p>';

        $code .= '<p>';
        $code .= CHtml::activeLabelEx($model,'begin');

        ob_start();
        $this->widget('zii.widgets.jui.CJuiDatePicker', array(
            'model'=> $model,
            'attribute' => 'begin',
            // additional javascript options for the date picker plugin
            'options'=>array(
                'dateFormat'=>'yy-mm-dd',
            ),
            'htmlOptions'=>array(
            #'style'=>'height:20px;'
            ),
        ));
        $code .= ob_get_clean();
        $code .= '<span class="hint">'.Yii::t('P2Module.p2','Item will not be visible before this date.').'</span>';

        $code .= '</p>';
        $code .= '<p>';
        $code .= CHtml::activeLabelEx($model,'end');

        ob_start();
        $this->widget('zii.widgets.jui.CJuiDatePicker', array(
            'model'=> $model,
            'attribute' => 'end',
            // additional javascript options for the date picker plugin
            'options'=>array(
                'dateFormat'=>'yy-mm-dd',
            ),
            'htmlOptions'=>array(
            #'style'=>'height:20px;'
            ),
        ));
        $code .= ob_get_clean();
        $code .= '<span class="hint">'.Yii::t('P2Module.p2','Item will become invisible after this date.').'</span>';
        $code .= '</p>';
        $code .= '<p>';
        $code .= CHtml::activeLabelEx($model,'keywords');
        $code .= CHtml::activeTextField($model, 'keywords');
        $code .= '<span class="hint">'.Yii::t('P2Module.p2','Comma separated list.').'</span>';
        $code .= '</p>';
        $code .= '<p>';
        $code .= CHtml::activeLabelEx($model,'customData');
        $code .= CHtml::activeTextField($model, 'customData');
        $code .= '<span class="hint">'.Yii::t('P2Module.p2','Free text string/option for this item.').'</span>';
        $code .= '</p>';
        if (!$this->_model->isNewRecord) {
            $code .= '<p>';
            $code .= CHtml::label('Created By','createdBy');
            if (isset($model->Creator)) {
                $code .= '<span id="createdBy">'.$model->Creator->name.'</span>';
            }
            $code .= '</p>';
            $code .= '<p>';
            $code .= CHtml::label('Created At','modifiedAt');
            $code .= '<span id="createdAt">'.$model->createdAt.'</span>';
            $code .= '</p>';
            $code .= '<p>';
            $code .= CHtml::label('Modified By','modifiedBy');
            if (isset($model->Modifier)) {
                $code .= '<span id="modifiedBy">'.$model->Modifier->name.'</span>';
            }
            $code .= '</p>';
            $code .= '<p>';
            $code .= CHtml::label('Modified At','modifiedAt');
            $code .= '<span id="modifiedAt">'.$model->modifiedAt.'</span>';
            $code .= '</p>';
        }
        $code .= '</fieldset>';
        return $code;
    }
    public function prepareDisplay() {
        $code = "";
        $data['Status'] = P2Info::getStatusOptionName($this->_model->status);
        $data['Language'] = $this->_model->language;
        $status = P2Info::statusOptions();
        $data['Type'] = $this->_model->type;
        $data['Check&nbsp;Access'] = $this->_model->checkAccess;
        if (isset($model->Modifier)) {
            $data['Modified By'] = $this->_model->Modifier->name;
        } else {
            $data['Modified By'] = '';
        }
        $data['Modified At'] = $this->_model->modifiedAt;
        $data['Keywords'] = $this->_model->keywords;
        $data['CustomData'] = $this->_model->customData;
        
        #$code .= P2Helper::table($data);
        $code .= "<div>";
        $code .= $data['Status']."&nbsp;".$data['Language']."&nbsp;[".$data['Check&nbsp;Access']."]&nbsp;".$data['Type'];
        $code .= "</div><div style='white-space: nowrap'>";
        $code .= $data['Modified At']." ".$data['Modified By'];
        $code .= "</div><div>";
        $code .= $data['Keywords']." ".$data['CustomData'];
        $code .= "</div>";

        return $code;
    }
}
?>
