<?php

/**
 * This is the model class for table "machine_state".
 *
 * The followings are the available columns in table 'machine_state':
 * @property integer $id
 * @property string $code
 * @property string $name
 */
class MachineConfig extends CActiveRecord
{

    //номер условия - применение - параметр,
    //
    //применение:
    //0 - игнорировать
    //1 - достаточно, чтобы да
    //2 - достаточно, чтобы нет
    //3 - необходимо, чтобы да
    //4 - необходимо, чтобы нет
    //
    //(применение==0) - конец таблицы

    static $arrApplyNumbers = array(
        0 => 'игнорировать',
        1 => 'дост-но, чтобы да',
        2 => 'дост-но, чтобы нет',
        3 => 'необх-о, чтобы да',
        4 => 'необх-о, чтобы нет',
    );

	/**
	 * Returns the static model of the specified AR class.
	 * @return MachineState the static model class
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
		return 'machine_config';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id, machine_id, machine_state_id', 'required'),
			array('id', 'condition_number, apply_number, value', 'integerOnly'=>true),
			array('id, code, name', 'safe', 'on'=>'search'),
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
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

}