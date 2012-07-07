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
 * Behavior, manages info attributes for active records
 *
 * Detailed info
 * <pre>
 * $var = code_example();
 * </pre>
 * {@link DefaultController}
 *
 * @author Tobias Munk <schmunk@usrbin.de>
 * @version $Id: P2ActiveRecordInfoBehavior.php 527 2010-03-28 19:32:03Z schmunk $
 * @package p2.behaviors
 * @since 2.0
 */
class P2ActiveRecordInfoBehavior extends CActiveRecordBehavior {

    private $_infoModel;

    public function beforeSave($event) {
        if ($this->Owner->hasErrors()) {
            return;
        }

        if ($this->_infoModel = P2Info::model()->findByPk($this->Owner->p2_infoId) === null) {

            // new attributes
            $this->_infoModel=new P2Info;
            $this->_infoModel->model = get_class($this->Owner);
            $this->_infoModel->createdAt = date("Y-m-d H:i:s");
            $this->_infoModel->createdBy = Yii::app()->user->id;
            $this->_infoModel->modifiedAt = date("Y-m-d H:i:s");
            $this->_infoModel->modifiedBy = Yii::app()->user->id;
            $this->_infoModel->language = Yii::app()->language;
            $this->applyPostData();
            $this->_infoModel->save();
            $this->Owner->p2_infoId = $this->_infoModel->id;
            Yii::log("Auto-created P2Info #".$this->_infoModel->id, LOG_DEBUG, "p2.db");
        }
        else {
            $this->_infoModel = P2Info::model()->findByPk($this->Owner->p2_infoId);
            $this->_infoModel->modifiedAt = date("Y-m-d H:i:s");
            $this->_infoModel->modifiedBy = Yii::app()->user->id;
            $this->applyPostData();

            $this->_infoModel->save();
        }
    }

    public function afterSave($event) {
        if ($this->Owner->hasErrors()) {
            return;
        }
        $this->_infoModel = P2Info::model()->findByPk($this->Owner->p2_infoId);
        $this->_infoModel->modelId = $this->Owner->id;
        $this->_infoModel->save();
    }

    public function afterDelete($event) {

        $info = P2Info::model()->findByPk($this->Owner->p2_infoId);
        if ($info instanceof CActiveRecord) {
            Yii::log("Auto-deleted P2Info #".$info->id, CLogger::LEVEL_INFO, "p2.db");
            $info->delete();
        } else {
            Yii::log("Could not auto-delete P2Info", CLogger::LEVEL_WARNING, "p2.db");
        }
    }

    private function applyPostData() {
        if ((isset($_POST['P2Info']))) { // TODO validate!
            $this->_infoModel->attributes=$_POST['P2Info'];
            if ($_POST['P2Info']['language'] == "") {
                $this->_infoModel->language = null;
            }
        }

    }
}
?>
