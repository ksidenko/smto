<?php

/**
 * This is the model class for table "event_color".
 *
 * The followings are the available columns in table 'event_color':
 * @property integer $id
 * @property string $code
 * @property string $name
 * @property string $color
 */
class EventColor extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return EventColor the static model class
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
		return 'event_color';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('code', 'required'),
            //array('code', 'readOnly'=>true),
			array('code, name', 'length', 'max'=>512),
			array('color', 'length', 'max'=>15),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, code, name, color', 'safe', 'on'=>'search'),
            
            array('id, code', 'unsafe'),
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
			'code' => 'Код',
			'name' => 'Описание',
			'color' => 'Цвет',
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
		$criteria->compare('code',$this->code,true);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('color',$this->color,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
    
    public function __set($name, $value)
	{
        if ($name == 'color') {
            if ( strstr($value, '#') === false ) {
                $value = '#' . $value;
            }
        }
		return parent::__set($name, $value);
	}
    
    public function __get($name)
	{
        $value = parent::__get($name);
        if ($name == 'color') {
            $value = str_replace('#', '', $value); 
        }
		return $value;
	}

    static public function getByCode($code) {
        $row = EventColor::model()->findByAttributes(array('code' => $code), array('select' => 'color'));
        if ($row) {
            return '#' . str_replace('#', '', $row->color);
        }
        return '';
    }
}