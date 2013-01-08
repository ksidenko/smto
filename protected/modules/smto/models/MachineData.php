<?php

/**
 * This is the model class for table "machine_data".
 *
 * The followings are the available columns in table 'machine_data':
 * @property integer $id
 * @property integer $number
 * @property string $dt
 * @property integer $duration
 * @property string $mac
 * @property integer $machine_id
 * @property integer $operator_id
 * @property integer $da_max1
 * @property integer $da_max2
 * @property integer $da_max3
 * @property integer $da_max4
 * @property integer $da_avg1
 * @property integer $da_avg2
 * @property integer $da_avg3
 * @property integer $da_avg4
 * @property integer $dd1
 * @property integer $dd2
 * @property integer $dd3
 * @property integer $dd4
 * @property integer $dd_change1
 * @property integer $dd_change2
 * @property integer $dd_change3
 * @property integer $dd_change4
 * @property integer $state
 * @property integer $operator_last_fkey
 * @property integer $fkey_all
 * @property integer $flags
 */
class MachineData extends CActiveRecord
{
    public $sec_duration;
    public $machine_stopped_max1;
    public $machine_idle_run_max1;
    public $machine_work_max1;

    public $machine_stopped_max2;
    public $machine_idle_run_max2;
    public $machine_work_max2;

    public $machine_stopped_max3;
    public $machine_idle_run_max3;
    public $machine_work_max3;

    public $machine_stopped_max4;
    public $machine_idle_run_max4;
    public $machine_work_max4;

    public $machine_stopped_avg1;
    public $machine_idle_run_avg1;
    public $machine_work_avg1;

    public $machine_stopped_avg2;
    public $machine_idle_run_avg2;
    public $machine_work_avg2;

    public $machine_stopped_avg3;
    public $machine_idle_run_avg3;
    public $machine_work_avg3;

    public $machine_stopped_avg4;
    public $machine_idle_run_avg4;
    public $machine_work_avg4;

    public $cnt_machine;

    public $da_avg;

    /**
	 * Returns the static model of the specified AR class.
	 * @return MachineData the static model class
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
		return 'machine_data';
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
		return array(
			array('number, dt, mac, machine_id, da_max1, da_max2, da_max3, da_max4, da_avg1, da_avg2, da_avg3, da_avg4, dd1, dd2, dd3, dd4, dd_change1, dd_change2, dd_change3, dd_change4, state, operator_last_fkey, fkey_all, flags', 'required'),
			array('number, duration, machine_id, operator_id, da_max1, da_max2, da_max3, da_max4, da_avg1, da_avg2, da_avg3, da_avg4, dd1, dd2, dd3, dd4, dd_change1, dd_change2, dd_change3, dd_change4, state, operator_last_fkey, fkey_all, flags', 'numerical', 'integerOnly'=>true),
			array('mac', 'length', 'max'=>16),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, number, dt, duration, mac, machine_id, operator_id, da_max1, da_max2, da_max3, da_max4, da_avg1, da_avg2, da_avg3, da_avg4, dd1, dd2, dd3, dd4, dd_change1, dd_change2, dd_change3, dd_change4, state, operator_last_fkey, fkey_all, flags', 'safe', 'on'=>'search'),
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
            'machine'=>array(self::BELONGS_TO, 'Machine', 'machine_id'),
            'operator'=>array(self::BELONGS_TO, 'Operator', 'operator_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'number' => '№',
			'dt' => 'Дата/Время',
			'duration' => 'Период',
			'mac' => 'MAC',
			'machine_id' => 'Machine id',
			'operator_id' => 'Operator id',
			'da_max1' => 'Da Max1',
			'da_max2' => 'Da Max2',
			'da_max3' => 'Da Max3',
			'da_max4' => 'Da Max4',
			'da_avg1' => 'Da Avg1',
			'da_avg2' => 'Da Avg2',
			'da_avg3' => 'Da Avg3',
			'da_avg4' => 'Da Avg4',
			'dd1' => 'Dd1',
			'dd2' => 'Dd2',
			'dd3' => 'Dd3',
			'dd4' => 'Dd4',
			'dd_change1' => 'Dd Change1',
			'dd_change2' => 'Dd Change2',
			'dd_change3' => 'Dd Change3',
			'dd_change4' => 'Dd Change4',
			'state' => 'State',
			'operator_last_fkey' => 'Operator Last Fkey',
			'fkey_all' => 'Fkey All',
			'flags' => 'Flags',
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

		$criteria->compare('t.id',$this->id);
		$criteria->compare('t.number',$this->number);
		$criteria->compare('t.dt',$this->dt,true);
		$criteria->compare('t.duration',$this->duration);
		$criteria->compare('t.mac',$this->mac,true);
		$criteria->compare('t.machine_id',$this->machine_id);
		$criteria->compare('t.operator_id',$this->operator_id);
		$criteria->compare('t.da_max1',$this->da_max1);
		$criteria->compare('t.da_max2',$this->da_max2);
		$criteria->compare('t.da_max3',$this->da_max3);
		$criteria->compare('t.da_max4',$this->da_max4);
		$criteria->compare('t.da_avg1',$this->da_avg1);
		$criteria->compare('t.da_avg2',$this->da_avg2);
		$criteria->compare('t.da_avg3',$this->da_avg3);
		$criteria->compare('t.da_avg4',$this->da_avg4);
		$criteria->compare('t.dd1',$this->dd1);
		$criteria->compare('t.dd2',$this->dd2);
		$criteria->compare('t.dd3',$this->dd3);
		$criteria->compare('t.dd4',$this->dd4);
		$criteria->compare('t.dd_change1',$this->dd_change1);
		$criteria->compare('t.dd_change2',$this->dd_change2);
		$criteria->compare('t.dd_change3',$this->dd_change3);
		$criteria->compare('t.dd_change4',$this->dd_change4);
		$criteria->compare('t.state',$this->state);
		$criteria->compare('t.operator_last_fkey',$this->operator_last_fkey);
		$criteria->compare('t.fkey_all',$this->fkey_all);
		$criteria->compare('t.flags',$this->flags);

        $criteria->compare('t.flags',$this->flags);
        
        $criteria->with = array('machine', 'operator');
        //$criteria->together= true;

//        $sort = new CSort();
//        $sort->attributes = array(
//            'defaultOrder'=>'t.id DESC',
//            'machine'=>array(
//                'asc'=>'machine.name',
//                'desc'=>'machine.name desc',
//            ),
//        );

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
            //'sort'=>$sort,
            'pagination'=>array(
                  'pageSize'=>100,
            ),
		));
	}
    
    public function scopes()
	{
		return array(
            'default' => array( 
                 'condition' => 'rec_type="real"'
             ), 
        );
	}

    public function getData(array $params)
	{
        $start = $params['dt_start'];

        $end = $start + $params['dt_delta_sec'];
        //$end = $params['dt_end'];
        //echo date(DATE_ATOM, $start); die;
        //echo date(DATE_ATOM, $end); die;

        //$end = self::nextDate($start, $params['dt_delta_sec'])/1000;
        //echo date(DATE_ATOM, $end); die;
        
        $cr = new CDbCriteria();
        $cr->select = 'id, number, machine_id, dt, ' . $params['name'];
        $cr->addCondition('machine_id = :machine_id');
        $cr->addCondition('dt >= :start');
        if (!empty($end)) {
            $cr->addCondition('dt < :end');
            $cr->params[':end'] = date('Y-m-d H:i:s', $end);
        }
        $cr->params = array_merge(array(
            ':machine_id' => $params['machine_id'],
            ':start' => date('Y-m-d H:i:s', $start),
        ), $cr->params);
        $cr->order = 'id asc';

        //print_r($cr);die;
        //print_r($this->count($cr)); die;
        return $this->findAll($cr);
	}
    
    static function nextDate($dt, $sec) {
        return strtotime(date(DATE_ATOM, $dt) . " +$sec seconds")*1000;
    }

    static public function isIdentityRecords( $lastMachineDataRec, $parsedRow, $useHash = false ) {

        if ( !isset($lastMachineDataRec->state) || is_null($lastMachineDataRec->state) ) {
            return false;
        }

        $b = false;

        $duration = strtotime($parsedRow->dt) - strtotime($lastMachineDataRec->dt);
        $m = Yii::app()->getModules();
        $maxTime = $m['smto']['max_time_between_machine_records'];

        //echo "duration = $duration" . PHP_EOL;
        if ($duration >= 0 && $duration < $maxTime && // $duration may be 0!
            $parsedRow->operator_id == $lastMachineDataRec->operator_id &&
            $parsedRow->state == $lastMachineDataRec->state &&
            $parsedRow->operator_last_fkey == $lastMachineDataRec->operator_last_fkey
        ) {
            $b = true;
            if ($useHash) {

                if ( !empty($lastMachineDataRec->da_max2) ) {
                    $hash1 = md5($parsedRow->da_max1.$parsedRow->da_max2.$parsedRow->da_max3.$parsedRow->da_max4.
                        $parsedRow->da_avg1.$parsedRow->da_avg2.$parsedRow->da_avg3.$parsedRow->da_avg4.
                        $parsedRow->dd1.$parsedRow->dd2.$parsedRow->dd3.$parsedRow->dd4.
                        $parsedRow->dd_change1.$parsedRow->dd_change2.$parsedRow->dd_change3.$parsedRow->dd_change4);

                    $hash2 = md5($lastMachineDataRec->da_max1.$lastMachineDataRec->da_max2.$lastMachineDataRec->da_max3.$lastMachineDataRec->da_max4.
                        $lastMachineDataRec->da_avg1.$lastMachineDataRec->da_avg2.$lastMachineDataRec->da_avg3.$lastMachineDataRec->da_avg4.
                        $lastMachineDataRec->dd1.$lastMachineDataRec->dd2.$lastMachineDataRec->dd3.$lastMachineDataRec->dd4.
                        $lastMachineDataRec->dd_change1.$lastMachineDataRec->dd_change2.$lastMachineDataRec->dd_change3.$lastMachineDataRec->dd_change4);
                        
                        $b = ($hash1 == $hash2);
                } else { // version 1.0
                    $hash1 = md5($parsedRow->da_avg1);

                    $hash2 = md5($lastMachineDataRec->da_avg1);

                    $b = ($hash1 == $hash2);

                    if ($parsedRow->da_avg1 > 0) {
                        if (abs($parsedRow->da_avg1 - $lastMachineDataRec->da_avg1)/$parsedRow->da_avg1 < 0.002) {
    	                    $b = true;
	                }
                    }
                }
            }
        }

        return $b;
    }
}