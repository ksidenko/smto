<?php

/**
 * This is the model class for table "task".
 *
 * The followings are the available columns in table 'task':
 * @property integer $id
 * @property integer $pid
 * @property integer $machine_id
 * @property string $status
 * @property string $dt_create
 * @property string $dt_check
 * @property integer $progress
 * @property string $error
 */
class Task extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return Task the static model class
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
		return 'task';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('pid, machine_id, progress', 'numerical', 'integerOnly'=>true),
			array('status', 'length', 'max'=>8),
			array('dt_create, dt_check, error', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, pid, machine_id, status, dt_create, dt_check, progress, error', 'safe', 'on'=>'search'),
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
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'pid' => 'Pid',
			'machine_id' => 'Machine',
			'status' => 'Status',
			'dt_create' => 'Dt Create',
			'dt_check' => 'Dt Check',
			'progress' => 'Progress',
			'error' => 'Error',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('pid',$this->pid);
		$criteria->compare('machine_id',$this->machine_id);
		$criteria->compare('status',$this->status,true);
		$criteria->compare('dt_create',$this->dt_create,true);
		$criteria->compare('dt_check',$this->dt_check,true);
		$criteria->compare('progress',$this->progress);
		$criteria->compare('error',$this->error,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}