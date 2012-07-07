<?php

/**
 * This is the model class for table "operator".
 *
 * The followings are the available columns in table 'operator':
 * @property integer $id
 * @property string $c1
 * @property string $c2
 * @property string $c3
 * @property string $full_name
 * @property string $phone
 */
class Operator extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return Operator the static model class
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
		return 'operator';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('c1, c2, c3, full_name', 'required'),
			array('c1, c2, c3', 'length', 'max'=>10),
			array('full_name', 'length', 'max'=>1024),
			array('phone', 'length', 'max'=>32),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, c1, c2, c3, full_name, phone', 'safe', 'on'=>'search'),
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
			'c1' => 'Код 1',
			'c2' => 'Код 2',
			'c3' => 'Код 3',
			'full_name' => 'Ф.И.О.',
			'phone' => 'Телефон',
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
		$criteria->compare('c1',$this->c1,true);
		$criteria->compare('c2',$this->c2,true);
		$criteria->compare('c3',$this->c3,true);
		$criteria->compare('full_name',$this->full_name,true);
		$criteria->compare('phone',$this->phone,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

    public static function getRecByCode($c1, $c2, $c3)   {
        static $ids = array();

        $code = $c1.$c2.$c3;
        if ( !isset($ids[$code]) ) {

            $res = false;
            try {
                $criteria = new CDbCriteria();
                $criteria->addCondition('c1 = :c1');
                $criteria->addCondition('c2 = :c2');
                $criteria->addCondition('c3 = :c3');
                $criteria->params = array(
                    ':c1' => $c1,':c2' => $c2,':c3' => $c3,
                );
                $criteria->limit = 1;

                $res = Operator::model()->find($criteria);
                if ($res) {
                    $ids[$code] = $res;
                }
            } catch (Exception $e) {
                print_r($e->getMessage());
            }
        }

        return isset($ids[$code]) ? $ids[$code] : false;
    }
}