<?php

class WorkerInlineCollection extends WorkerPlugin
{
	private $_current_worker = -1;
	private $_backup = array();
	
/*
  	const FLOW_OK = 0;
	const FLOW_NEWLINE = 1;
	const FLOW_DONE = 2;
	const FLOW_FULL = 3;
 */	

	
	public function save()
	{
		$state = array($this->_current_worker);
		$sub = $this->firstChildWorker;
		while($sub)
		{
			$state[] = $sub->save();
			$sub = $sub->nextSiblingWorker;
		}
		$this->_backup[] = $state;
		return count($this->_backup)-1;		
	}

	public function restore($id = null)
	{
		if ($id === null) $id = count($this->_backup) - 1;
		$b = $this->_backup[$id];
		$this->_current_worker = array_shift($b); 
		$sub = $this->firstChildWorker;
		while($sub)
		{
			$sub->restore(array_shift($b));
			$sub = $sub->nextSiblingWorker;
		}
	}

	public function flowInto($lb)
	{
		if ($this->_current_worker === -1)
			$this->_current_worker = $this->firstChildWorker;

	 	$sub = $this->_current_worker;
	 	$ret = LineBox::FLOW_DONE;						
		while($sub)
		{
			Lithron::trace("GOING IN ON ".$sub->DOMNode->nodeName."<br>") ;
			$ret = $sub->flowInto($lb);
			Lithron::trace("RESULT IS $ret<br>");
			if ($ret != LineBox::FLOW_OK && $ret != LineBox::FLOW_DONE)
				return $ret;
			$sub = $sub->nextSiblingWorker;
			$this->_current_worker = $sub;
		}
		return $ret;
	}


}


?>