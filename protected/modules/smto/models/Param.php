<?php

/**
 * This is the model class for table "param".
 *
 * The followings are the available columns in table 'param':
 * @property integer $id
 * @property string $key
 * @property string $value
 * @property string $descr
 * @property integer $stable
 */
class Param extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return Param the static model class
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
		return 'param';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('key, value, descr', 'required'),
			array('stable', 'numerical', 'integerOnly'=>true),
			array('key', 'length', 'max'=>200),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, key, value, descr, stable', 'safe', 'on'=>'search'),
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
			'key' => 'Название',
			'value' => 'Значение',
			'descr' => 'Описание',
			'stable' => 'Удаляемо',
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
		$criteria->compare('key',$this->key,true);
		$criteria->compare('value',$this->value,true);
		$criteria->compare('descr',$this->descr,true);
		$criteria->compare('stable',$this->stable);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

    public static function getParamValue($code){
        static $params = array();

        if ( count($params) == 0 ) {
            $params_ = Param::model()->findAll();
            foreach($params_ as $par) {
                $params[$par['key']] = $par['value'];
            }
        }

        return isset($params[$code]) ? $params[$code]: false;
    }
}