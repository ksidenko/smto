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
    public $reachable;

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

    public function primaryKey()
    {
        return 'id';
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
			array('name, code, ip, pwd, port, local_port', 'required'),
            array('port, local_port', 'type', 'type' => 'integer'),
			array('s_values, reasons_timeout_table', 'required'),
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
            array('mac, name, code, pwd', 'filter', 'filter'=>'trim'),
            array('work_type', 'length', 'max'=>9),
			array('rec_type', 'length', 'max'=>8),

            array('data_fix_period, peak_average_period', 'type', 'type' => 'integer'),

            array('data_fix_period', 'numerical', 'min' => 2, 'max' => 10),
            array('peak_average_period', 'numerical', 'min' => 1, 'max' => 50),


            array('main_detector_digit', 'numerical', 'min' => 0, 'max' => self::$MAX_DD - 1),
            array('main_detector_analog', 'numerical', 'min' => 0, 'max' => self::$MAX_DA - 1),


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
            'config'=>array(self::HAS_ONE, 'MachineConfig', 'machine_id'),
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
            'port' => 'port',
            'local_port' => 'local port',
            'pwd' => 'Пароль',
			'mac' => 'MAC',
            's_values' => 's_values',
            'reasons_timeout_table' => 'reasons_timeout_table',
			//'work_type' => 'Тип работы',
			//'time_idle_run' => 'Время холостого хода, сек',
			'rec_type' => 'Тип записи',
            'template_id' => 'Шаблон',
            'reachable' => 'Доступен',
            'main_detector_analog' => 'Главный аналог. датчик',
            'main_detector_digit' => 'Главный цифровой датчик'
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
                'order' => 'name asc'
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

        foreach(array('Fkey'/*, 'Detector', 'Amplitude'*/) as $key => $rel) {
            foreach($data[$rel] as &$val) {
                $val['rec_type'] = $this->rec_type;
                if ($isNewRecord) $val['id'] = null;
            }
        }

        $this->attributes = $data['Machine'];
        $this->fkey = $data['Fkey'];
        //$this->detector = $data['Detector'];
        //$this->amplitude = $data['Amplitude'];
        $res = $this->saveWithRelated(array('fkey'/*, 'detector', 'amplitude'*/));

        if (!$isNewRecord) {
            MachineConfig::model()->deleteAllByAttributes(array('machine_id' => $this->id));
            foreach ($data['MachineConfig'] as $condition_number => $MachineConfigData) {
                foreach($MachineConfigData as $state => $MachineConfigDataStates) {
                    if ($MachineConfigDataStates['apply_number'] > 0) {
                        $machineConfig = new MachineConfig();
                        $machineConfig->machine_id = $this->id;
                        $machineConfig->condition_number = $condition_number;
                        $machineConfig->machine_state_id = $state;
                        $machineConfig->apply_number = $MachineConfigDataStates['apply_number'];
                        $machineConfig->value = $MachineConfigDataStates['value'];
                        $machineConfig->save(false);
                    }
                }
            }

            $path = $this->getMachineConfigFile();
            //echo $path; die;

            $this->writeMachineConfigToFile($path);

            chmod($path, 0777);

            $dataPath = $this->getMachineDataPath();
            if (!file_exists($dataPath)) {
                mkdir($this->getMachineDataPath(), 0777);
            }

        }

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


    public function writeMachineConfigToFile ($file) {
        if (file_exists($file)) {
              rename($file, $file . '_old');
        }


        $fd = fopen($file, 'w');
        $line = array();
        $line []= '; Этот файл создан автоматически! Нет смысла его менять вручную!';
        $line []= ';';
        $line []= ';Файл конфигурации для контроллера';
        $line []= ';--------------------------------------------------------------';
        $line []= ';Параметры для связи с контроллером';

        $line []= ';Локальный UDP-порт:';
        $line []= 'LocalPort=' . $this->local_port;

        $line []= ';IP-адрес контроллера:';
        $line []= 'IP=' . $this->ip;

        $line []= ';Udp-порт контроллера:';
        $line []= 'Port=' . $this->port;
        $line []= ';MAC-адрес, чтобы не записать по ошибке в другой экземпляр:';
        $line []= 'MAC=' . $this->mac;
        $line []= ';Пароль для доступа к контроллеру:';
        $line []= 'Password=' . $this->pwd ;


        $line []= ';----------------------------------------------------------------';
        $line []= ';Параметры для определения состояния (выключен-включен-холостой ход-работает)';
        $line []= ';См. подробное описание в файле config.txt.';
        $line []= ';Параметры KM0...KM3:';

            $detectors = Detector::model()->findAllByAttributes(array(
                'machine_id' => $this->id,
                'type' => 'analog',
                'rec_type' => 'real',
            ), array(
                'select' => 'max_k_value, avg_k_value',
                'order' => 'number asc'
            ));

            $max_k_value = array();
            $avg_k_value = array();
            foreach( $detectors as  $detector) {
                $max_k_value []= $detector['max_k_value'];
                $avg_k_value []= $detector['avg_k_value'];
            }

	    $s = trim(implode(',', $avg_k_value));
	    if (!empty($s)) {
	        $line []= 'KmValues=' . $s;
        } else {
            $line []= 'KmValues=128,128,128,128';
        }

        $line []= ';Параметры KA0...KA3:';
//        $line []= 'KaValues=' . implode(',', $max_k_value);

	    $s = trim(implode(',', $max_k_value));
	    if (!empty($s)) {
	        $line []= 'KaValues=' . $s;
        } else {
            $line []= 'KaValues=128,128,128,128';
        }

        $line []= ';Параметры S0...S3';
        $line []= 'SValues=' . $this->s_values;

            $machineConfig = MachineConfig::model()->findAllByAttributes(array(
                'machine_id' => $this->id,
            ), array(
                'select' => 'machine_state_id, condition_number, apply_number, value',
                'order' => 'machine_state_id, condition_number asc'
            ));

            $StateTables = array();
            foreach( $machineConfig as $machineConfigData) {
                $StateTables[$machineConfigData->machine_state_id][] =
                      $machineConfigData->condition_number . ','
                    . $machineConfigData->apply_number . ','
                    . $machineConfigData->value
                ;
            }

        if (isset($StateTables[3])) {
            $line []= ';Таблица "номер_условия,применение,параметр,номер_условия,применение,параметр,..." (до 16ти групп) для состояния "работает"';
            $line []= 'State3Table=' . implode(', ', $StateTables[3]);
        }

        if (isset($StateTables[2])) {
            $line []= ';Таблица "номер_условия,применение,параметр,номер_условия,применение,параметр,..." (до 16ти групп) для состояния "холостой ход"';
            $line []= 'State2Table=' . implode(', ', $StateTables[2]);
        }

        if (isset($StateTables[1])){
            $line []= ';Таблица "номер_условия,применение,параметр,номер_условия,применение,параметр,..." (до 16ти групп) для состояния "включен"';
            $line []= 'State1Table=' . implode(', ', $StateTables[1]);
        }

        $line []= ';------------------------------------------------------------------';
        $line []= '; Таблица таймаутов в секундах (0...65535) для причин простоя (до 16-ти).';

        $line []= '; После истечения таймаута причина сбрасывается в состояние "неизвестная причина",';
        $line []= '; что требует от оператора станка повторного ввода причины';
        $line []= '; 0 - означает "бесконечный таймаут"';
        $line []= 'ReasonsTimeoutsTable=' . $this->reasons_timeout_table;

        $line []= '';
        $line []= ';Каталог и префикс(опционально) для выходных файлов. Не может содержать пробелов:';
        $line []= 'OutFileDir=' . $this->getMachineDataPath() . '/pw';

        $line []= '';
        $line []= ';------------------------------------------------------------------';
        $line []= ';Период фиксации данных в секундах (2...10), но рекомендуется не меньше 3';
        $line []= ';по умолчанию - 10';
        $line []= 'DataFixPeriod=' . $this->data_fix_period;

        $line []= ';Интервал интегрирования для вычисления квазипиковых ("максимальных")';
        $line []= ';значений в сотнях миллисекунд (1...50)';
        $line []= ';По умолчанию - 1';
        $line []= ';Рекомендуется, чтобы период фиксации был кратен интервалу интегрирования';
        $line []= ';(если не кратен - то обязательно период фиксации должен быть больше)';
        $line []= 'PeakAveragePeriod=' . $this->peak_average_period;


        $s = implode(PHP_EOL, $line);
        fwrite($fd, $s);
        fclose($fd);
    }


    public function initMachineConfigFromFile ($file) {
        $configParams = parse_ini_file($file, false);
        if ($configParams === false) {
            Yii::log("Incorrect file path: " . $file, 'error');
            return false;
        }

        $db = $this->getDbConnection();

        $tr = $db->beginTransaction();

        if ( isset($configParams['IP']) ) {
            $this->ip = $configParams['IP'];
        }

        if ( isset($configParams['LocalPort']) ) {
            $this->local_port = $configParams['LocalPort'];
        }

        if ( isset($configParams['Port']) ) {
            $this->port = $configParams['Port'];
        }

        if ( isset($configParams['Password']) ) {
            $this->pwd = $configParams['Password'];
        }

        if ( isset($configParams['MAC']) ) {
            $this->mac = $configParams['MAC'];
        }

        if ( isset($configParams['SValues']) ) {
            $this->s_values = $configParams['SValues'];
        }

        if ( isset($configParams['ReasonsTimeoutsTable']) ) {
            $this->reasons_timeout_table = $configParams['ReasonsTimeoutsTable'];
        }

        $detectors = Detector::model()->findAllByAttributes(array(
            'machine_id' => $this->id,
            'type' => 'analog',
            'rec_type' => 'real',
        ), array(
            'order' => 'number asc'
        ));

        if ( isset($configParams['KaValues']) ) {
            $max_k_value = explode(',', $configParams['KaValues']);

            foreach( $detectors as $i => $detector) {
                if ( isset($max_k_value[$i]) ) {
                    $detector->max_k_value = $max_k_value[$i];
                    $detector->save();
                }
            }
        }

        if ( isset($configParams['KmValues']) ) {
            $avg_k_value = explode(',', $configParams['KmValues']);

            foreach( $detectors as $i => $detector) {
                if ( isset($avg_k_value[$i]) ) {
                    $detector->avg_k_value = $avg_k_value[$i];
                    $detector->save();
                }
            }
        }

        MachineConfig::model()->deleteAllByAttributes(array('machine_id' => $this->id));

        if ( isset($configParams['State1Table']) ) {
            $StateTable = explode(',', $configParams['State1Table']);
            if (count($StateTable) % 3 !== 0) {
                Yii::log("Incorrect count items in State1Table: " . $StateTable, 'error');
                return false;
            }

            for ($i=0;$i<count($StateTable); $i = $i + 3) {
                $machineConfig = new MachineConfig;
                $machineConfig->machine_id = $this->id;
                $machineConfig->machine_state_id = MachineState::STATE_MACHINE_ON;
                $machineConfig->condition_number = $StateTable[$i];
                $machineConfig->apply_number = $StateTable[$i+1];
                $machineConfig->value = $StateTable[$i+2];
                $machineConfig->save(false);
            }
        }

        if ( isset($configParams['State2Table']) ) {
            $StateTable = explode(',', $configParams['State2Table']);
            if (count($StateTable) % 3 !== 0) {
                Yii::log("Incorrect count items in State2Table: " . $StateTable, 'error');
                return false;
            }

            for ($i=0;$i<count($StateTable); $i = $i + 3) {
                $machineConfig = new MachineConfig;
                $machineConfig->machine_id = $this->id;
                $machineConfig->machine_state_id = MachineState::STATE_MACHINE_IDLE_RUN;
                $machineConfig->condition_number = $StateTable[$i];
                $machineConfig->apply_number = $StateTable[$i+1];
                $machineConfig->value = $StateTable[$i+2];
                $machineConfig->save(false);
            }
        }

        if ( isset($configParams['State3Table']) ) {
            $StateTable = explode(',', $configParams['State3Table']);
            if (count($StateTable) % 3 !== 0) {
                Yii::log("Incorrect count items in State3Table: " . $StateTable, 'error');
                return false;
            }

            for ($i=0;$i<count($StateTable); $i = $i + 3) {
                $machineConfig = new MachineConfig;
                $machineConfig->machine_id = $this->id;
                $machineConfig->machine_state_id = MachineState::STATE_MACHINE_WORK;
                $machineConfig->condition_number = $StateTable[$i];
                $machineConfig->apply_number = $StateTable[$i+1];
                $machineConfig->value = $StateTable[$i+2];
                $machineConfig->save(false);
            }
        }

        $this->save();

        $tr->commit();

        //print_r($configParams);

        return true;
    }

    public function getMachineDataPath() {
        return rtrim(Param::getParamValue('machine_data_path'), '/') . '/' . $this->mac;
    }

    static public function getMachineDataPathCurr() {
        return rtrim(Param::getParamValue('machine_data_path_curr'), '/') . '/' ;
    }

    public function getMachineConfigFile() {
        return rtrim(Param::getParamValue('machine_config_data_path'), '/') . '/' . 'd00' . substr($this->ip, -2) . '.cfg';
    }

}
