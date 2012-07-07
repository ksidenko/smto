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
 * Model class
 *
 * Detailed info
 * <pre>
 * $var = code_example();
 * </pre>
 * {@link DefaultController}
 *
 * @author Tobias Munk <schmunk@usrbin.de>
 * @version $Id: P2ForgotPassword.php 509 2010-03-24 00:36:59Z schmunk $
 * @package p2.models
 * @since 2.0
 */
class P2ForgotPassword extends CFormModel 
{
    /**
     * @var mixed the user name 
     */
    public $name='';

    /**
     * @var mixed user's email address
     */
    public $eMail='';


    /**
     * @return array rules for this form 
     */
    public function rules() {
        return array(
            array('eMail','email'),

            // validate that name or eMail exist in active users
            array('name,eMail', 'exist', 
                'className'=>'P2User', 
                'allowEmpty'=>'true', 
                'criteria'=>array(
                    'condition' => 'status=:status',
                    'params'=>array(':status'=>P2User::STATUS_ACTIVE),
                ),
            ),
        );
    }

    /**
     * Verifies that at least one field is not empty
     */
    public function beforeValidate()
    {
        if ($this->name==='' && $this->eMail==='') {
            $this->addError('name', Yii::t('P2Module.p2','Please enter your user name or email address'));
            return false;
        }
        return true;
    }


    /**
     * @return mixed the P2User object matching these form values or null if not existant
     */
    public function getUser()
    {
        $condition='status=:status';
        $params=array(':status'=>P2User::STATUS_ACTIVE);

        if ($this->name!=='')
            return P2User::model()->findByAttributes(array('name'=>$this->name),$condition,$params);
        elseif($this->eMail!=='')
            return P2User::model()->findByAttributes(array('eMail'=>$this->eMail),$condition,$params);
        else 
            return null;
    }
}
