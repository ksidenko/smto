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
 * @version $Id: P2Info.php 549 2010-03-31 23:45:24Z schmunk $
 * @package p2.models
 * @since 2.0
 */
class P2Info extends CActiveRecord
{
        const STATUS_DELETED    = 0;
        const STATUS_DRAFT      = 10;
        const STATUS_PENDING    = 20;
        const STATUS_ACTIVE     = 30;
        const STATUS_LOCKED     = 40;
        const STATUS_HIDDEN     = 50;
        const STATUS_ARCHIVE    = 60;

	/**
	 * The followings are the available columns in table 'P2Info':
	 * @var integer $id
	 * @var string $model
	 * @var string $language
	 * @var integer $status
	 * @var integer $type
	 * @var integer $createdBy
	 * @var string $createdAt
	 * @var integer $modifiedBy
	 * @var string $modifiedAt
	 * @var string $begin
	 * @var string $end
	 * @var string $keywords
	 * @var string $customData
	 */

        public function init(){
            parent::init();
            $this->attributes = array('language' => Yii::app()->language);
        }

	/**
	 * Returns the static model of the specified AR class.
	 * @return CActiveRecord the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
        return Yii::app()->params['p2.databaseName'].'p2_info';
    }

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return array(
			array('model','length','max'=>45),
			array('language','length','max'=>16),
			array('createdAt, modifiedAt', 'required'),
			array('type', 'match', 'pattern' => '/^[a-z0-9-_.]+$/', 'message' => Yii::t('P2Module.p2','__VALIDATION_ERROR_REGEXP_ALPHANUMERIC__', array('{var}'=>'Type'))),
			array('status', 'numerical', 'integerOnly'=>true),
			array('begin', 'safe'), // todo
			array('end', 'safe'),// todo
			array('keywords', 'safe'),
			array('customData', 'safe'),
                        array('checkAccess', 'default', 'setOnEmpty' => true, 'value' => null),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'Creator' => array(self::BELONGS_TO, 'P2User', 'createdBy'),
			'Modifier' => array(self::BELONGS_TO, 'P2User', 'modifiedBy'),
		);
	}


	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'Id',
			'model' => 'Model',
			'language' => 'Language',
			'status' => 'Status',
			'type' => 'Type',
			'createdBy' => 'Created By',
			'createdAt' => 'Created At',
			'modifiedBy' => 'Modified By',
			'modifiedAt' => 'Modified At',
			'begin' => 'Begin',
			'end' => 'End',
			'keywords' => 'Keywords',
			'customData' => 'Custom Data',
		);
	}

        public static function statusOptions()
        {
            return array(
                self::STATUS_DRAFT => 'Draft',
                self::STATUS_PENDING => 'Pending',
                self::STATUS_ACTIVE => 'Active',
                self::STATUS_HIDDEN => 'Hidden',
                self::STATUS_ARCHIVE => 'Archive',
                self::STATUS_LOCKED => 'Locked',
                self::STATUS_DELETED => 'Trash',
            );
        }
        public static function getStatusOptionName($value)
        {
            $options = self::statusOptions();
            if (isset($options[$value])) {
                return $options[$value];
            } else {
                return self::STATUS_DRAFT;
            }
        }

        public static function types($modelClass = null)
        {
            if (isset(Yii::app()->params['p2.info.types'][$modelClass])) {
                return Yii::app()->params['p2.info.types'][$modelClass];
            } else {
                return array();
            }
        }

        public static function checkAccess(){
            foreach(Yii::app()->authManager->getAuthItems(2,Yii::app()->user->id) AS $item) {
                $roles[$item->name] = $item->name;
            }
            return array('Roles' => $roles);
        }

}