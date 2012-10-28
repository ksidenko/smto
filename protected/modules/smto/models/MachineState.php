<?php

/**
 * This is the model class for table "machine_state".
 *
 * The followings are the available columns in table 'machine_state':
 * @property integer $id
 * @property string $code
 * @property string $name
 */
class MachineState extends CActiveRecord
{
    const STATE_MACHINE_OFF = 0; // выключен
    const STATE_MACHINE_ON = 1; // включен
    const STATE_MACHINE_IDLE_RUN = 2; // холостой ход
    const STATE_MACHINE_WORK = 3; // работа

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
		return 'machine_state';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id, code', 'required'),
			array('id', 'numerical', 'integerOnly'=>true),
			array('code, name', 'length', 'max'=>512),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
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
			'code' => 'Code',
			'name' => 'Name',
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

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

    static public function getByColor($stateId) {
        $row = MachineState::model()->cache(600)->findByPk($stateId);
        if ($row) {
            return '#' . str_replace('#', '', $row->color);
        }
        return '';
    }

    public static function getRec($id)  {
        static $date = array();

        if ( !isset($date[$id]) ) {
            $res = false;
            try {
                $rows = MachineState::model()->cache(600)->findAll();
                if ($rows) {
                    foreach($rows as $row) {
                        $date[$row->id] = $row;
                    }
                }
            } catch (Exception $e) {
                //print_r($e->getMessage());
            }
        }

        return isset($date[$id]) ? $date[$id] : false;
    }
}