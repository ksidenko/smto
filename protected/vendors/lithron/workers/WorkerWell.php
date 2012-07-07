<?php

require_once(dirname(__FILE__)."/WorkerBlock.php");


class WorkerWell extends WorkerBlock
{
	private $_trace_path = array();
	private $_ran_dry = false;

	public function initializePre()
	{
		$this->Lithron->Wells[$this->propWellId()] = $this;
	}
	
	public function setTracePath($trace)
	{
		$this->_trace_path = $trace;
	}

	public function getTracePath()
	{
		return $this->_trace_path;
	}

	public function setRanDry($value)
	{
		$this->_ran_dry = $value;
	}

	public function getRanDry()
	{
		return $this->_ran_dry;
	}

	public function getPrefWidth()
	{
		return 0;
	}
	
	public function getWidth()
	{
		return 0;
	}
	
	public function getPrefHeight($width)
	{
		return 0;
	}
	
	public function getHeight($width)
	{
		return 0;
	}
	
	public function work()
	{
	}	
}


?>