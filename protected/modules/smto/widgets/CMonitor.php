<?php
class CMonitor extends CWidget
{
    public $data;
    public $urlReport = '/smto/report/index?';

    public function init() {
    }

    public function getUrlReport($machineId) {

        $currTime = Timetable::model()->cache(60)->find('cast(now() as time) between time_from and time_to');

        $startDate = date('d.m.Y ' . $currTime['time_from']);
        $endDate = date('d.m.Y ' . $currTime['time_to']);

        $urlReport = $this->urlReport . "ReportConstructor[dtStart]=$startDate&ReportConstructor[dtEnd]=$endDate&ReportConstructor[machineId]=$machineId";
        return $urlReport;
    }

    public function run() {
        $this->render('monitor');
    }
}
