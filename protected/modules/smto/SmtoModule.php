<?php

class SmtoModule extends CWebModule
{
    public $max_duration;
    public $max_time_between_machine_records;
    public $is_save_process_files;
    public $max_record_duration;
    public $max_last_time_modify;


	public function init()
	{
		// this method is called when the module is being created
		// you may place code here to customize the module or the application

		// import the module-level models and components
		$this->setImport(array(
			'smto.models.*',
			'smto.components.*',
		));
	}

	public function beforeControllerAction($controller, $action)
	{
		if(parent::beforeControllerAction($controller, $action))
		{
			// this method is called before any module controller action is performed
			// you may place customized code here
			return true;
		}
		else
			return false;
	}
}
