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
 * WebUser for P2User
 *
 * Detailed info
 * <pre>
 * $var = code_example();
 * </pre>
 * {@link DefaultController}
 *
 * @author Tobias Munk <schmunk@usrbin.de>
 * @version $Id: P2WebUser.php 521 2010-03-24 13:20:13Z schmunk $
 * @package p2.auth
 * @since 2.0
 */
//require_once basedir(__FILE__).'/../../../modules/user/UserModule.php';
class P2WebUser extends CWebUser 
{

    private $_model;

    /**
     * Get database record for the current logged in user.
     *
     * @param mixed $status if not null, user must have this status or null is returned
     * @return mixed P2User record of current user or null if not found.
     */
    public function getModel($status=null) {
        if ($this->_model===null) {
            $criteria= $status===null ? '' : array(
                    'condition'=>'status=:status',
                    'params'=>array(':status'=>(int)$status),
            );
            $this->_model=P2User::model()->findByPk($this->getId(),$criteria);
        }
        return $this->_model;
    }
    
    public function getProfile() {
        if (isset(Yii::app()->params['p2.user.profileModel'])) {
            $modelClass = Yii::app()->params['p2.user.profileModel'];
            $model = new $modelClass;
            return $model->findByAttributes(array(Yii::app()->params['p2.user.profileModel.fkUser']=>$this->getId()));
        } else {
            throw new Exception('External model not defined.');
        }

    }
}
?>
