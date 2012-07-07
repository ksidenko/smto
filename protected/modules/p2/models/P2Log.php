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
 * @version $Id: P2Log.php 527 2010-03-28 19:32:03Z schmunk $
 * @package p2.models
 * @since 2.0
 */
class P2Log extends CActiveRecord
{
	/**
	 * The followings are the available columns in table 'P2Log':
	 * @var integer $id
	 * @var string $description
	 * @var string $action
	 * @var string $model
	 * @var integer $idModel
	 * @var string $changes
	 * @var integer $createdBy
	 * @var string $createdAt
	 * @var string $data
	 */

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
        return Yii::app()->params['p2.databaseName'].'p2_log';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return array(
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
			'createdBy0' => array(self::BELONGS_TO, 'P2User', 'createdBy'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'Id',
			'description' => 'Description',
			'action' => 'Action',
			'model' => 'Model',
			'idModel' => 'Id Model',
			'changes' => 'Changes',
			'createdBy' => 'Created By',
			'createdAt' => 'Created At',
			'data' => 'Data',
		);
	}
}