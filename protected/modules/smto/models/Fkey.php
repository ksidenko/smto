<?php

/**
 * This is the model class for table "fkey".
 *
 * The followings are the available columns in table 'fkey':
 * @property integer $id
 * @property integer $number
 * @property integer $machine_id
 * @property integer $machine_event_id
 * @property string $code
 * @property string $color
 * @property string $type
 * @property integer $status
 * @property string $rec_type
 */
class Fkey extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return Fkey the static model class
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
		return 'fkey';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return array(
            array('id', 'safe'),
			array('code, type', 'required'),
			array('number, machine_id, machine_event_id, status', 'numerical', 'integerOnly'=>true),
			array('code, color', 'length', 'max'=>128),
			array('type', 'length', 'max'=>9),
			array('rec_type', 'length', 'max'=>8),
            array('color, descr', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, number, machine_id, code, color, type, status, rec_type', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		return array(
            'machine'=>array(self::BELONGS_TO, 'machine', 'machine_id'),
            'machine_event'=>array(self::BELONGS_TO, 'MachineEvent', 'machine_event_id'),
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
            'machine_event_id' => 'ID события',
            'machine_event' => 'Событие',
			'code' => 'Код',
			'color' => 'Цвет',
			'type' => 'Тип',
			'status' => 'Статус',
			'rec_type' => 'Тип записи',
            'descr' => 'Описание',
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
		$criteria->compare('code',$this->code,true);
		$criteria->compare('color',$this->color,true);
		$criteria->compare('type',$this->type,true);
		$criteria->compare('status',$this->status);
		$criteria->compare('rec_type',$this->rec_type,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
    
    public function scopes()
	{
		return array(
            'default' => array( 
                'condition' => 'rec_type="real"', 
                
             ), 
        );
	}
    
    public function __set($name,$value)
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
    

            public static function getRec($fkeyNumber, $machineId)   {
        $criteria = new CDbCriteria();
        $criteria->compare('number', $fkeyNumber);
        $criteria->compare('machine_id', $machineId);

        return Fkey::model()->find($criteria);
//        static $ids = array();
//        
//        $code = $c1.$c2.$c3;
//        if ( !isset($ids[$code]) ) {
//
//            $res = false;
//            try {
//                $criteria = new CDbCriteria();
//                $criteria->addCondition('c1 = :c1');
//                $criteria->addCondition('c2 = :c2');
//                $criteria->addCondition('c3 = :c3');
//                $criteria->params = array(
//                    ':c1' => $c1,':c2' => $c2,':c3' => $c3,
//                );
//                $criteria->limit = 1;
//
//                $res = Operator::model()->find($criteria);
//                if ($res) {
//                    $ids[$code] = $res;
//                }
//            } catch (Exception $e) {
//                print_r($e->getMessage());
//            }
//        }
//
//        return isset($ids[$code]) ? $ids[$code] : false;
    }
}