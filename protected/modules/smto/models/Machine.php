<?php

/**
 * This is the model class for table "machine".
 *
 * The followings are the available columns in table 'machine':
 * @property integer $id
 * @property string $name
 * @property string $code
 * @property string $ip
 * @property string $mac
 * @property string $work_type
 * @property integer $time_idle_run
 * @property string $rec_type
 */
class Machine extends CActiveRecord
{
    public static $work_type_list = array(
        'amplitude' => 'По амплитуде', 
        'average' => 'По средним значениям'
    );

    static $MAX_DA = 4;
    static $MAX_DD = 4;

    private $isTemplate = null;

	/**
	 * Returns the static model of the specified AR class.
	 * @return Machine the static model class
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
		return 'machine';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		$validators =  array(
            array('id', 'safe'),
			array('name, code, ip, work_type', 'required'),
			array('time_idle_run', 'numerical', 'integerOnly'=>true),
			array('name, code', 'length', 'max'=>512),
			//array('mac', 'length', 'max'=>16),
            array('mac', 'unique', 
                'allowEmpty' => false, 
                'className' => 'Machine', 
                'attributeName' => 'mac', 
                'caseSensitive' => false, 
                'criteria' => array(
                    'condition' => 't.rec_type=:rec_type' . ($this->id ? ' and t.id!=:id' : ''),
                    'params' => array(
                        ':rec_type' => ($this->getIsTemplate() ? "template" : "real" )
                    ) + ($this->id ? array(':id' => $this->id) : array())
                ),
                'message' => 'MAC адрес должен быть уникальным!'
            ),        
			array('work_type', 'length', 'max'=>9),
			array('rec_type', 'length', 'max'=>8),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			//array('id, name, code, ip, mac, work_type, time_idle_run, rec_type', 'safe', 'on'=>'search'),
		);

        if(!$this->getIsTemplate()) {
            $validators[]=array('ip', 'IPValidator', 'message' => 'Не корректный IP адрес');
        }
        return $validators;
	}

    public function behaviors(){
        return array('ESaveRelatedBehavior' => array(
         'class' => 'application.components.ESaveRelatedBehavior')
     );
}
	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		return array(
            'fkey'=>array(self::HAS_MANY, 'Fkey', 'machine_id', 'order' => 'number ASC'),
            'detector'=>array(self::HAS_MANY, 'Detector', 'machine_id', 'order' => 'type ASC, number ASC'),
            'amplitude'=>array(self::HAS_MANY, 'Amplitude', 'machine_id', 'order' => 'type ASC, number ASC'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'name' => 'Название',
			'code' => 'Код',
			'ip' => 'IP',
			'mac' => 'MAC',
			'work_type' => 'Тип работы',
			'time_idle_run' => 'Время холостого хода, сек',
			'rec_type' => 'Тип записи',
            'template_id' => 'Шаблон',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search() {
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('code',$this->code,true);
		$criteria->compare('ip',$this->ip,true);
		$criteria->compare('mac',$this->mac,true);
		$criteria->compare('work_type',$this->work_type,true);
		$criteria->compare('time_idle_run',$this->time_idle_run);
		$criteria->compare('rec_type',$this->rec_type,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
            'pagination'=>array(
                  'pageSize'=>50,
            ),
		));
	}
    
    public function scopes() {
		return array(
            'templates_list' => array(
                'condition' => 'rec_type="template"',
                'order' => 'id asc'
            ),

            'template_records' => array(
                'condition' => 'rec_type="template"',
            ),

            'real_records' => array(
                'condition' => 'rec_type="real"',
            )
        );
	}

    public function defaultScope() {
		return array(
            //'condition' => 'rec_type="real"'
        );
	}

    public function saveMachine(&$data, $isNewRecord = true)
	{
        $this->rec_type = $this->getIsTemplate() ? 'template' : 'real';

        if ($isNewRecord) unset($data['Machine']['id']);

        foreach(array('Fkey', 'Detector', 'Amplitude') as $key => $rel) {
            foreach($data[$rel] as &$val) {
                $val['rec_type'] = $this->rec_type;
                if ($isNewRecord) $val['id'] = null;
            }
        }

        $this->attributes = $data['Machine'];
        $this->fkey = $data['Fkey'];
        $this->detector = $data['Detector'];
        $this->amplitude = $data['Amplitude'];
        $res = $this->saveWithRelated(array('fkey', 'detector', 'amplitude'));

        return $res;
	}

    public static function getRecByMAC($mac, $recType = 'real')  {
        static $ids = array();

        if ( !isset($ids[$mac]) ) {

            $res = false;
            try {
                $criteria = new CDbCriteria();
                $criteria->addCondition('mac = :mac');
                $criteria->addCondition('rec_type = :rec_type');
                $criteria->params = array(
                    ':mac' => $mac,
                    ':rec_type' => $recType
                );
                $criteria->limit = 1;

                $res = Machine::model()->find($criteria);
                if ($res) {
                    $ids[$mac] = $res;
                } else {
                    $ids[$mac] = false;
                }
            } catch (Exception $e) {
                print_r($e->getMessage());
            }
        }

        return isset($ids[$mac]) ? $ids[$mac] : false;
    }

    static function getList() {
        static $data = null;
        if (!$data) {
            $data = Machine::model()->resetScope()->templates_list()->findAll();
        }
        return $data;
    }

    public function setIsTemplate($isTemplate) {
        $this->isTemplate = $isTemplate;
    }

    public function getIsTemplate() {
        if ($this->isTemplate === null && intval($this->id) > 0) {
            $this->isTemplate = $this->rec_type == 'template';
        }
        return (bool)$this->isTemplate;
    }
}