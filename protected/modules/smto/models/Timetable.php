<?php

/**
 * This is the model class for table "timetable".
 *
 * The followings are the available columns in table 'timetable':
 * @property integer $id
 * @property string $name
 * @property string $time_from
 * @property string $time_to
 */
class Timetable extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return Timetable the static model class
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
		return 'timetable';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id, name, time_from, time_to', 'required'),
			array('id', 'numerical', 'integerOnly'=>true),
			array('name', 'length', 'max'=>512),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, name, time_from, time_to', 'safe', 'on'=>'search'),
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
			'name' => 'Name',
			'time_from' => 'Time From',
			'time_to' => 'Time To',
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
		$criteria->compare('name',$this->name,true);
		$criteria->compare('time_from',$this->time_from,true);
		$criteria->compare('time_to',$this->time_to,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}