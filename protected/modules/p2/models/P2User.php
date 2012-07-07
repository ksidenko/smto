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
 * @version $Id: P2User.php 543 2010-03-31 00:28:27Z schmunk $
 * @package p2.models
 * @since 2.0
 */
class P2User extends CActiveRecord {
    /**
     * The followings are the available columns in table 'P2User':
     * @var integer $id
     * @var string $name
     * @var string $firstName
     * @var string $lastName
     * @var string $eMail
     * @var string $password
     * @var integer $p2_infoId
     */

    /**
     * @var int Default status: STATUS_NEW
     */
    public $status=self::STATUS_NEW;

    /**
     * @var string newpassword form field
     */
    public $newpassword='';

    /**
     * @var string repeatpassword form field
     */
    public $repeatpassword='';


    const STATUS_DELETED=0;
    const STATUS_BLOCKED=10;
    const STATUS_NEW=20;		// New user, email not verified
    const STATUS_VERIFIED=30;	// ... and verified
    const STATUS_ACTIVE=40;

    /**
     * Returns the static model of the specified AR class.
     * @return CActiveRecord the static model class
     */
    public static function model($className=__CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return Yii::app()->params['p2.databaseName'].'p2_user';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        return array(
            // Common rules for all scenarios
            array('name','length','min'=>3,'max'=>45),
            array('name', 'match',
                'pattern' => '/^[a-zA-Z0-9-_.]+$/',
                'message' => Yii::t('P2Module.p2','__VALIDATION_ERROR_REGEXP_ALPHANUMERIC__', array('{var}'=>'Name'))
            ),
            array('firstName,lastName,eMail','length','max'=>100),
            array('eMail,verifyEmail','email'),
            array('newpassword,repeatpassword','length','max'=>45,'min'=>6),
            array('repeatpassword','compare','compareAttribute'=>'newpassword','allowEmpty'=>false),

            array('status','type','type'=>'integer'),

            // Additional rules for special scenarios
            array('name, firstName, lastName, eMail', 'required', 'on'=>'register,insert,update'),
            array('name,eMail','unique', 'allowEmpty'=>true, 'on'=>'register,insert,update,userupdate'),
            array('firstName, lastName', 'required', 'on'=>'userupdate'),
            array('newpassword', 'required', 'on'=>'register,insert,resetpassword'),

            array('token', 'safe', 'on'=>'register,insert,resetpassword'),
            array('tokenExpires', 'safe', 'on'=>'register,insert,resetpassword'),
        );
    }

    /**
     * @return array model behaviours
     */
    public function behaviors() {
        return array(
            'ActiveRecordLogableBehavior'=>
            'application.modules.p2.behaviors.P2ActiveRecordLogableBehavior',
        );
    }


    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => Yii::t('P2Module.p2','Id'),
            'name' => Yii::t('P2Module.p2','Username'),
            'firstName' => Yii::t('P2Module.p2','First Name'),
            'lastName' => Yii::t('P2Module.p2','Last Name'),
            'eMail' => Yii::t('P2Module.p2','E-Mail'),
            'verifyEmail' => Yii::t('P2Module.p2','New E-Mail'),
            'newpassword' => Yii::t('P2Module.p2','Password'),
            'repeatpassword' => Yii::t('P2Module.p2','Retype password'),
            'p2_infoId' => Yii::t('P2Module.p2','P2 Info'),
        );
    }

    public function safeAttributes() {
        return array(
            // Default if no scenario specified:
            array_merge( parent::safeAttributes(), array('newpassword','repeatpassword')),


            // Safe attributes for registration
            'register'=>array('name','firstName','lastName','eMail','newpassword','repeatpassword'),

            // Safe attributes when user updates his info
            'userupdate'=>array('verifyEmail','newpassword','repeatpassword'),
        );
    }

    /**
     * Create hashed password if new password is set
     */
    public function beforeSave() {
        if ($this->newpassword!=='')
            $this->password=P2Helper::hash($this->newpassword);
        return true;
    }

    public function getUsername() {
        return $this->name;
    }

    public function setUsername($value) {
        $this->name = $value;
    }

    /**
     * Updates email and password from verifyEmail and
     * newpassword if one of them was changed.
     *
     * @access public
     * @return bool wether one of the attributes was changed
     */
    public function updateEmailAndPassword() {
        $update=array();

        if ($this->hasChangedEmail())
            $update[]='verifyEmail';

        if ($this->hasChangedPassword()) {
            $this->password=P2Helper::hash($this->newpassword);
            $update[]='password';
        }

        return count($update) ? $this->update($update) : false;
    }

    /**
     * @access public
     * @return bool wether there's a changed email in verifyEmail attribute. Returns false if empty.
     */
    public function hasChangedEmail() {
        return $this->verifyEmail!=='' && $this->verifyEmail!==$this->eMail;
    }

    /**
     * @access public
     * @return bool wether there's a changed password in newpassword. Returns false if empty.
     */
    public function hasChangedPassword() {
        return $this->newpassword!=='' && $this->password!==P2Helper::hash($this->newpassword);
    }

    /**
     * Use the saved unverified email address as current address
     * and update the attributes accordingly.
     *
     * @access public
     * @return bool false if there's no unverified email adress, true on success.
     */
    public function useVerifiedEmail() {
        if ($this->verifyEmail==='')
            return false;

        $this->eMail=$this->verifyEmail;
        $this->verifyEmail='';
        return $this->saveAttributes(array('eMail','verifyEmail'));
    }


    /**
     * Creates and saves a new user token.
     *
     * This token will be valid for the specified lifetime.
     *
     * @param int $lifetime
     * @access public
     * @return string the new created token
     */
    public function createToken($lifetime=3600) {

        do {
            $this->token=md5(uniqid(mt_rand(),true));
        } while(self::model()->findByAttributes(array('token'=>$this->token))!==null);

        $this->setPrimaryKey($this->id); // yii 1.1 bugfix - TODO?

        $this->tokenExpires=time() + $lifetime;
        $this->save();
        return $this->token;
    }

    /**
     * Finds a record by a given token. If the token is
     * not found or token has expired, null will be returned.
     *
     * @param string $token to search
     * @param mixed $status only search for given status. Ignored if null.
     * @return mixed P2User object or null if no valid token.
     */
    public function findByToken($token,$status=null) {
        if ($status===null)
            return $this->find(array(
                'condition' => 'token=:token AND tokenExpires < NOW()',
                'params' => array(':token' => $token),
            ));
        else
            return $this->find(array(
                'condition' => 'status=:status AND token=:token AND tokenExpires < NOW()',
                'params' => array(':token' => $token, ':status'=>(int)$status),
            ));
    }

    /**
     * @return array list of status options for use in dropdown menus
     */
    public static function statusOptions() {
        return array(
            self::STATUS_DELETED => 'Deleted',
            self::STATUS_BLOCKED => 'Blocked',
            self::STATUS_NEW     => 'New',
            self::STATUS_VERIFIED=> 'Verified',
            self::STATUS_ACTIVE  => 'Active',
        );
    }

    /**
     * @return string Name of the current status or empty string if unknown
     */
    public function getStatusName() {
        $stati=self::statusOptions();
        return isset($stati[$this->status]) ? $stati[$this->status] : '';
    }

    /**
     * @return CActiveRecord Profile model, if exists
     */
    public function getProfile() {
        if (isset(Yii::app()->params['p2.user.profileModel'])) {
            $modelClass = Yii::app()->params['p2.user.profileModel'];
            $model = new $modelClass;
            return $model->findByAttributes(array(Yii::app()->params['p2.user.profileModel.fkUser']=>$this->id));
        } else {
            return null;
        }

    }


}
