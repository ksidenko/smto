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
 * @version $Id: P2Cell.php 527 2010-03-28 19:32:03Z schmunk $
 * @package p2.models
 * @since 2.0
 */
class P2Cell extends P2ActiveRecord
{
	/**
	 * The followings are the available columns in table 'P2Cell':
	 * @var integer $id
	 * @var string $classPath
	 * @var string $classProps
	 * @var integer $rank
	 * @var string $cellId
	 * @var string $moduleId
	 * @var string $controllerId
	 * @var string $actionName
	 * @var string $requestParam
	 * @var string $sessionParam
	 * @var string $cookieParam
	 * @var string $applicationParam
	 * @var string $moduleParam
	 * @var integer $p2_infoId
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
        return Yii::app()->params['p2.databaseName'].'p2_cell';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return array(
			array('classPath','length','max'=>200),
			array('classProps','match','pattern'=>'/\{(.*)\}/','message'=>'Params not valid.'),
			array('cellId','length','max'=>100),
			array('moduleId','length','max'=>45),
			array('controllerId','length','max'=>45),
			array('actionName','length','max'=>45),
			array('requestParam','length','max'=>45),
			array('sessionParam','length','max'=>45),
			array('cookieParam','length','max'=>45),
			array('applicationParam','length','max'=>45),
			array('moduleParam','length','max'=>45),
			array('classPath', 'required'),
			array('rank, p2_infoId', 'numerical', 'integerOnly'=>true),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'Id',
			'classPath' => 'Class Path',
			'classProps' => 'Class Props',
			'rank' => 'Rank',
			'cellId' => 'Cell',
			'moduleId' => 'Module',
			'controllerId' => 'Controller',
			'actionName' => 'Action Name',
			'requestParam' => 'Request Param',
			'sessionParam' => 'Session Param',
			'cookieParam' => 'Cookie Param',
			'applicationParam' => 'Application Param',
			'moduleParam' => 'Module Param',
			'p2_infoId' => 'P2 Info',
		);
	}
}