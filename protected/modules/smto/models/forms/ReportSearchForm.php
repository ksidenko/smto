<?php

class ReportSearchForm extends CFormModel
{
    /*
     * Начало, конец временного диапазона отчета
     */
    public $dtStart, $dtEnd;

    /*
     * ID станка
     */
    public $machineId = null;

    /*
     * Тип отчета по станкам
     */
    public $machineReportType = null;

    static public $arrMachineReportType = array(
        'join' => 'Объединенный',
        'separate' => 'Отдельный',
        'id' => 'Задать'
    );


    /*
     * ID оператора
     */
    public $operatorId = null;

    /*
     * ID расписания работы оператора
     */
    public $timetableId = null;

    /*
     * тип графика:
     * - круговая диаграмма
     * - столбцовая
     * - таблица
     */
    public $graphReportType = null;

    static public $arrGraphType = array(
        'pie' => 'Круговая', // круговая
        'bar' => 'Столбцовая',  // столбцовая
        //'table' => 'Таблица' // табличный вид

    );

    public function rules()
    {
        return array(
            array('dtStart, graphReportType', 'required'),
            //array('dtStart, dtEnd', 'date'),
            array('dtStart, dtEnd', 'safe'),
            array('machineId, operatorId, timetableId', 'type', 'type' => 'int'),
            array('graphReportType', 'in', 'range' => array_keys(self::$arrGraphType)),
            array('machineReportType', 'in', 'range' => array_keys(self::$arrMachineReportType)),
        );
    }

    public function attributeLabels() {
        return array(
            'dtStart' => 'Период',
            'dtEnd' => 'Конец',
            'machineId' => 'Станок',
            'machineReportType' => 'Станок',
            'operatorId' => 'Оператор',
            'timetableId' => 'График работы',
            'graphReportType' => 'Вывод'
        );
    }
}