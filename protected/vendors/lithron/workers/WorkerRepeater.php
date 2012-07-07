<?php

class WorkerRepeater extends WorkerPlugin
{
	public function work($rop)
	{
		//var_dump(array_keys($this->Lithron->Wells));
		$mywell = null;
		if (isset($this->Lithron->Wells[$this->propWellId()]))
			$mywell = $this->Lithron->Wells[$this->propWellId()];
		
		$c = 0;
		while($c < 50)
		{
			$pnum = $this->Lithron->CurrentPageNum;
			if ($this->propModuloResult() != "none")
			{
				$cmp_from = ($pnum % $this->propModulo());
				$cmp_to = $this->propModuloResult();
				#var_dump($cmp_from, $cmp_to); echo "<hr>";
				$do_break = false;
				switch($this->propModuloMode())
				{
					case "equal":
                        Lithron::trace($pnum." MOD ".$this->propModulo()." = ".$cmp_from." == ".$cmp_to."?");
						$do_break = $cmp_from == $cmp_to;
						break;
					case "different":
						Lithron::trace($pnum." MOD ".$this->propModulo()." = ".$cmp_from." != ".$cmp_to."?");
						$do_break = $cmp_from != $cmp_to;
						break;
					default:
						throw new Exception("WTF");
				}
				if ($do_break) break;	
			}
			
			$w = $this->firstChildWorker;
			while ($w)
			{
				$subrop = new RenderOperation($w);
				$w->work($subrop);
				$rop->addChild($subrop);
				$w = $w->nextSiblingWorker;
			}

			$c++;
			if ($this->propModuloResult() != "none") break;
			if ($mywell && $mywell->getRanDry()) break;
		}
	}
}

?>