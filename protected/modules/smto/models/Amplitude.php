<?php

/**
 * This is the model class for table "amplitude".
 *
 * The followings are the available columns in table 'amplitude':
 * @property integer $id
 * @property integer $number
 * @property integer $machine_id
 * @property integer $value
 * @property string $type
 * @property string $rec_type
 */
class Amplitude extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return Amplitude the static model class
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
		return 'amplitude';
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
			array('value, type', 'required'),
			array('number, machine_id, value', 'numerical', 'integerOnly'=>true),
			array('type, rec_type', 'length', 'max'=>8),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, number, machine_id, value, type, rec_type', 'safe', 'on'=>'search'),
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
			'value' => 'Значение',
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
		$criteria->compare('value',$this->value);
		$criteria->compare('type',$this->type,true);
		$criteria->compare('rec_type',$this->rec_type,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
    
    public function scopes() { 
		return array();
	}

    public static function getAmplitudesRange($machineId)  {
        static $ids = array();

        if ( !isset($ids[$machineId]) ) {
            $res = false;
            try {
                $criteria = new CDbCriteria();
                $criteria->select = 'value, type';
                $criteria->addCondition('machine_id = :machine_id');
                $criteria->addCondition('number = :number');
                $criteria->params = array(
                    ':machine_id' => $machineId,
                    ':number' => 1
                );

                $rows = Amplitude::model()->cache(600)->findAll($criteria);
                if ($rows) {
                    $res = array('zero' => null, 'idle_run' => null);

                    foreach($rows as $row) {
                        $res[$row['type']] = $row['value'];
                    }

                    $ids[$machineId] = array_values($res);
                }
            } catch (Exception $e) {
                print_r($e->getMessage());
            }
        }

        return isset($ids[$machineId]) ? $ids[$machineId] : false;
    }
}