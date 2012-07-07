<?php

/**
 * This is the model class for table "detector".
 *
 * The followings are the available columns in table 'detector':
 * @property integer $id
 * @property integer $number
 * @property integer $machine_id
 * @property integer $status
 * @property string $type
 * @property string $rec_type
 */
class Detector extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return Detector the static model class
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
		return 'detector';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
            array('id', 'safe'),
			array('status, type', 'required'),
			array('number, machine_id, status', 'numerical', 'integerOnly'=>true),
			array('type', 'length', 'max'=>6),
			array('rec_type', 'length', 'max'=>8),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, number, machine_id, status, type, rec_type', 'safe', 'on'=>'search'),
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
			'number' => 'Номер',
			'machine_id' => 'ID станка',
			'status' => 'Статус',
			'type' => 'Тип',
			'rec_type' => 'Тип записи',
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
		$criteria->compare('number',$this->number);
		$criteria->compare('machine_id',$this->machine_id);
		$criteria->compare('status',$this->status);
		$criteria->compare('type',$this->type,true);
		$criteria->compare('rec_type',$this->rec_type,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
    
    public function scopes()
	{
		return array(
//            'default' => array( 
//                 'condition' => 'rec_type="real"'
//             ), 
        );
	}
}