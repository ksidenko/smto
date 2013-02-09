<?php

class ReportSearchForm extends CFormModel
{
    /*
     * Начало, конец временного диапазона отчета
     */
    public $dtStart, $dtEnd;

    /*
     * Диапазон выбранного времени
     */
    public $timeRange;

    static public function getTimeRanges() {

        $row = Timetable::model()->cache(10)->find('cast(now() as time) between time_from and time_to');

        //echo $row['time_to'];die;
        $currMonth = date('m');
        //$currMonth = 11;
        if ( 1 <= $currMonth && $currMonth <= 3 ) {
            $prevQuarterStart = 10;
        } else if ( 4 <= $currMonth && $currMonth <= 6 ) {
            $prevQuarterStart = 1;
        } else if ( 7 <= $currMonth && $currMonth <= 9 ) {
            $prevQuarterStart = 4;
        } else if ( 10 <= $currMonth && $currMonth <= 12 ) {
            $prevQuarterStart = 7;
        } else {
            throw new Exception('Bad month: ' . $currMonth);
        }

        //$prevQuarterStart = 12 - $prevQuarterStart + 2;
        $prevQuarterEnd = $prevQuarterStart + 2;

        //$prevQuarterStart = (($currMonth%3)) * 3 - 2;
        //$prevQuarterEnd = (($currMonth%3)) * 3;

        //echo (floor($currMonth/4)) ; die;
        //echo $prevQuarterStart . ' - ' . $prevQuarterEnd; die;

        $arr = array(
            'labels' => array(
                'custom' => 'Произвольный период',
                'current-turn' => 'Текущая смена',
                'today' => 'Сегодня',
                'yesterday' => 'Вчера',

                'week' => 'Эта неделя',
                'month' => 'Этот месяц',
                'quarter' => 'Этот квартал',
                'half-year' => 'Это полугодие',
                'year' => 'Этот год',

                'last-week' => 'Прошлая неделя',
                'last-month' => 'Прошлый месяц',
//                'last-quarter' => 'Прошлый квартал',
//                'last-half-year' => 'Прошлое полугодие',
//                'last-year' => 'Прошлый год',
            ),
            'values' => array(
                'custom' => array( 'start_date' => date('d.m.Y 00:00:00'), 'end_date' => date('d.m.Y H:i:s') ),
                'current-turn' => array( 'start_date' => date('d.m.Y ' . $row['time_from']), 'end_date' => date('d.m.Y ' . $row['time_to']) ),
                'today' => array( 'start_date' => date('d.m.Y 00:00:00'), 'end_date' => date('d.m.Y H:i:s') ),
                'yesterday' => array( 'start_date' => date('d.m.Y 00:00:00', strtotime('-1 day')), 'end_date' => date('d.m.Y 23:59:59', strtotime('-1 day')) ),

                'week' => array( 'start_date' => date('d.m.Y 00:00:00', strtotime('this week last monday')), 'end_date' => date('d.m.Y 23:59:59') ),
                'month' => array( 'start_date' => date('d.m.Y 00:00:00', strtotime('first day of this month')), 'end_date' => date('d.m.Y 23:59:59') ),
                'quarter' => array( 'start_date' => date('d.m.Y 00:00:00', strtotime('first day of -3 month')), 'end_date' => date('d.m.Y 23:59:59') ),
                'half-year' => array( 'start_date' => date('d.m.Y 00:00:00', strtotime('first day of -5 month')), 'end_date' => date('d.m.Y 23:59:59') ),
                'year' => array( 'start_date' => date('d.m.Y 00:00:00', strtotime('first day of January')), 'end_date' => date('d.m.Y 23:59:59') ),

                'last-week' => array( 'start_date' => date('d.m.Y 00:00:00', strtotime('previous week')), 'end_date' => date('d.m.Y 23:59:59', strtotime('previous week +6 day')) ),
                'last-month' => array( 'start_date' => date('d.m.Y 00:00:00', strtotime('first day of previous month')), 'end_date' => date('d.m.Y 23:59:59', strtotime('last day of previous month')) ),
                //'last-quarter' => array( 'start_date' => date("01.{$prevQuarterStart}.Y 00:00:00"), 'end_date' => date("01.{$prevQuarterEnd}.Y ") ),
            )
        );

        //echo '<pre>' . print_r($arr, 1) . '</pre>'; die;

        return $arr;
    }


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
            array('dtStart', 'required'),
            //array('dtStart, dtEnd', 'date'),
            array('timeRange, dtStart, dtEnd', 'safe'),
            array('machineId, operatorId, timetableId, byPass, withoutBreaks', 'type', 'type' => 'int'),
            //array('graphReportType', 'in', 'range' => array_keys(self::$arrGraphType)),
            //array('machineReportType', 'in', 'range' => array_keys(self::$arrMachineReportType)),
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
            'graphReportType' => 'Вывод',
            'byPass' => 'По карточкам',
            'withoutBreaks' => 'С учетом перерывов',
        );
    }
}