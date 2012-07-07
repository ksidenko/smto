<?php

class EvalCounters extends TransformerPlugin
{
	public function transform()
	{
		$q = '//*[@counter-reset]';
		$akkus = $this->XPath->query($q);
		//echo $q." returned ".$akkus->length." nodes<br>";
		foreach($akkus as $node)
		{
			$attrs = explode(" ", Property::get($node, "counter-reset"));
			while (count($attrs))
			{
				$cid = array_shift($attrs);
				Property::set($node, PropertyDefinition::AKKU_PREFIX.$cid, 0);				
				//Property::set($node, Property::COUNTER_PREFIX.$cid, 0);
			}
		}

		$q = '//*[@counter-increment]';
		$instances = $this->XPath->query($q);
		//echo $q." returned ".$instances->length." nodes<br>";
		foreach($instances as $node)
		{
			$cid = Property::get($node, "counter-increment");
			if ($cid != null)
			{
				$cname = PropertyDefinition::AKKU_PREFIX.$cid;
				$subq = 'ancestor::*[@'.$cname.'][1]';
				$res = $this->XPath->query($subq, $node);
				//echo $subq." returned ".$res->length." nodes<br>";
				$cobj = $res->item(0);
				if ($cobj)
				{
					$cval = Property::get($cobj, $cname) + 1;
					Property::set($cobj, $cname, $cval);
					Property::set($node, PropertyDefinition::COUNTER_PREFIX.$cid, $cval);	
				}
					
			}
		}
	}
}


?>