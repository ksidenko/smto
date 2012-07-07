<?php

class WorkerSink extends WorkerBlock
{
	protected function getFirstWorker()
	{
		$well =  $this->Lithron->Wells[$this->propWellId()];
		return $well->getFirstWorker();
	}

	protected function getNextWorker($cur_worker)
	{
		$well =  $this->Lithron->Wells[$this->propWellId()];
		return $well->getNextWorker($cur_worker);
	}
	
	public function work($rop, $width, $height)
	{
		$mywell = $this->Lithron->Wells[$this->propWellId()];
		$tp = $mywell->getTracePath();
		Lithron::trace("<h3>SINK WORK:</h3>" ,"Layout");
		Lithron::$ActiveSink = $this;
		$ret = parent::work($rop, $width, $height, $tp, array($mywell));
		$mywell->setRanDry($ret == WorkerBlock::BWR_OK);
		Lithron::trace("<h3>SINK WORK END WITH $ret:</h3>" ,"Layout");
	}
	
}


?>