<?php
class CMonitor extends CWidget
{
    public $data;

    public function init() {

    }

    public function run() {
        $this->render('monitor');
    }
}
