<?php

class WorkerLithron extends WorkerPlugin
{
	public function work()
	{
		$w = $this->firstChildWorker;
		while ($w)
		{
			$w->work();
			$w = $w->nextSiblingWorker;
		}
	}
}


?>