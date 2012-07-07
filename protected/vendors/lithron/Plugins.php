<?php

class BasePlugin
{
	public $Lithron;
	public $Document;
	public $XPath;
	
	public function __construct($lithron)
	{
		$this->Lithron = $lithron;
		$this->Document = $lithron->Document;
		$this->XPath = $lithron->XPath;
	}
	
}

class TransformerPlugin extends BasePlugin
{
}


class WorkerPlugin extends BasePlugin
{
	public $parentWorker = null;
	public $previousSiblingWorker = null;
	public $nextSiblingWorker = null;
	public $firstChildWorker = null;
	
	public $DOMNode;
	public $Level;
	public $RenderOperation = null;
	
	private $Cache = array();

	private $Children;
	
	public function __construct($lithron, $node, $level)
	{
		parent::__construct($lithron);
		$this->DOMNode = $node;
		$this->Level = $level;
	}
	
	public function initializePre()
	{
	}
	
	public function initializePost()
	{
	}
	
	public function getTraceParam()
	{
		return 0;
	}
	
	public function appendChild($childworker)
	{
		$c = count($this->Children);
		$this->Children[$c] = $childworker;
		if ($c != 0) 
		{
			$childworker->previousSiblingWorker = $this->Children[$c-1];
			$this->Children[$c-1]->nextSiblingWorker = $childworker;
		}
		else
		{
			$this->firstChildWorker = $childworker;
		}
		$childworker->parentWorker = $this;
	}

	public function isPositioned()
	{
		$p = Property::get($this->DOMNode, "position");
		return !in_array($p, array("relative", "static"));
	}

	public function isMarker()
	{
		$m = Property::get($this->DOMNode, "display");
		return $m == "marker";
	}

	public function isInline()
	{
		$p = Property::get($this->DOMNode, "display");
		return in_array($p, array("inline", "inline-collection"));
	}

	public function isContainedInFixed()
	{
		$w = $this;
		while ($w)
		{
			if (in_array($w->propPosition(), array("fixed")))
				return true;
			$w = $w->parentWorker;
		}
		return false;
	}

	public function findContainingRop()
	{
		$w = $this;
		while ($w)
		{
			if (in_array($w->propPosition(), array("absolute", "relative", "fixed")))
				return $w->RenderOperation;
			if ($w instanceof WorkerPage)
				return $w->RenderOperation;
			$w = $w->parentWorker;
		}
		throw new Exception("No containing rop found!");
	}



	public function __call($method, $params)
	{
		if (isset($this->Cache[$method])) return $this->Cache[$method];
		
		if (strncmp($method, "prop", 4) == 0)
		{
			$suffix = substr($method, 4);
			$a = substr(preg_replace("/([A-Z])/e", "\"-\".strtolower(\"\\1\")", $suffix), 1);
			$res = Property::get($this->DOMNode, $a);
			//NONONO
			//$this->Cache[$method] = $res;
			return $res;
		}
		elseif (strncmp($method, "typeOf", 6) == 0)
		{
			$suffix = substr($method, 6);		
			$a = substr(preg_replace("/([A-Z])/e", "\"-\".strtolower(\"\\1\")", $suffix), 1);
			$val = Property::get($this->DOMNode, $a);
			Property::types($val, $comp_array, $type_array, $match_array);
			if (isset($params[0]))
				return in_array($params[0], $type_array);
			else
				return $type_array;
		}
		elseif (strncmp($method, "matchOf", 7) == 0)
		{
			$suffix = substr($method, 7);		
			$a = substr(preg_replace("/([A-Z])/e", "\"-\".strtolower(\"\\1\")", $suffix), 1);
			$val = Property::get($this->DOMNode, $a);
			Property::types($val, $comp_array, $type_array, $match_array);
			if (!isset($params[0]))
				return $match_array;
			$ct = count($type_array);
			for ($i = 0; $i < $ct; $i++)
				if ($params[0] == $type_array[$i]) break;
			if ($i == $ct) return null;
			return $match_array[$i];
		}
		elseif (strncmp($method, "calc", 4) == 0)
		{
			$suffix = substr($method, 4);		
			preg_match("/^(M?)(B?)(P?)(Top|Right|Bottom|Left|Horiz|Vert)$/", $suffix, $matches);
			if (count($matches) == 0)
				throw new Exception("Invalid method '$method' called!");
			//var_dump($matches);
			$axe = strtolower($matches[4]);
			switch($axe)
			{
				case "horiz":
					$axes = array("right", "left");
					break;				
				case "vert":
					$axes = array("top", "bottom");				
					break;
				default:
					$axes = array($axe);				
			}

			$akku = 0;
			foreach($axes as $axe)
			{
				// margin
				if ($matches[1])
				{
					$p = Property::get($this->DOMNode, "margin-$axe");
					$akku += $p == "auto" ? 0 : $p;
				}

				// border
				if ($matches[2])
				{
					$p = Property::get($this->DOMNode, "border-$axe-width");
					$akku += Property::get($this->DOMNode, "border-$axe-style") == "none" ? 0 : $p;
				}

				// padding
				if ($matches[3])
				{
					$p = Property::get($this->DOMNode, "padding-$axe");
					$akku += $p == "auto" ? 0 : $p;
				}
			}
			$this->Cache[$method] = $akku;
			return $akku;										
		}
		else {
                    $msg = "Invalid method '$method' on ".$this->DOMNode->nodeName." called!";
                    Lithron::log($msg, LOG_WARNING, "Plugins");
                    # throw new Exception($msg);
                }
	}
	
}

?>