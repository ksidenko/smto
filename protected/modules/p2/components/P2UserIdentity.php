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
 * UserIdentity for P2User
 *
 * Detailed info
 * <pre>
 * $var = code_example();
 * </pre>
 * {@link DefaultController}
 *
 * @author Tobias Munk <schmunk@usrbin.de>
 * @version $Id: P2UserIdentity.php 506 2010-03-24 00:32:15Z schmunk $
 * @package p2.auth
 * @since 2.0
 */

Yii::import('p2.models.*');

class P2UserIdentity extends CUserIdentity {
    const ROLE_EDITOR = 'editor';
    const ERROR_BLOCKED=110;
    const ERROR_NEW=120;
    const ERROR_VERIFIED=130;

    private $_id;
    private $_model;

    public function authenticate() {
        $criteria = new CDbCriteria();
        $model=P2User::model()->findByAttributes(array('name'=>$this->username));
        if($model===null)
            $this->errorCode=self::ERROR_USERNAME_INVALID;
        elseif($model->password!==P2Helper::hash($this->password))
            $this->errorCode=self::ERROR_PASSWORD_INVALID;
        elseif ($model->status == P2User::STATUS_BLOCKED) {
            $this->errorCode=self::ERROR_BLOCKED;
        } elseif ($model->status == P2User::STATUS_NEW) {
            $this->errorCode=self::ERROR_NEW;
        } elseif ($model->status == P2User::STATUS_VERIFIED) {
            $this->errorCode=self::ERROR_VERIFIED;
        }else {
            $this->_model=$model;
            $this->_id=$model->id;
            $this->setState('title', $model->firstName." ".$model->lastName);
            $this->errorCode=self::ERROR_NONE;
        }
        return !$this->errorCode;
    }

    public function getId() {
        return $this->_id;
    }

    public function getModel() {
        return $this->_model;
    }
}

?>
