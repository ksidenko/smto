<?php

class ComputeProps extends TransformerPlugin
{
	
	private function convertLength($node, $num, $unit)
	{
		switch ($unit)
		{
			case "px":
			case "pt":
				return $num;
			case "mm":
				return $num / 25.4 * 72.0;
			case "cm":
				return $num / 2.54 * 72.0;
			case "in":
				return $num * 72.0;
			case "em":
				if ($node->name == "font-size")
					$psize = Property::get($node->ownerElement->parentNode, "font-size");
				else
				{
					$this->ensureComputed($node, "font-size");
					$psize = Property::get($node->ownerElement, "font-size");
				}
				return $num * $psize;
			case "ex":
				if ($node->name == "font-size")
					$psize = Property::get($node->ownerElement->parentNode, "font-size");
				else
				{
					$this->ensureComputed($node, "font-size");
					$psize = Property::get($node->ownerElement, "font-size");
				}
				$xh = $this->Lithron->getFontMetric($node->ownerElement, "xheight");
				return $num * $psize * $xh;
			case "pc":
				return $num * 12.0; 			
		}
	}
	
	
	private function formatNumber($value, $type)
	{
		switch($type)
		{
			case "decimal":
				return $value;
						
			default:
				return $value;
		}
	}
	
	
	private function ensureComputed($node, $property)
	{
		$pnode = $node->ownerElement->getAttributeNode($property);
		if ($pnode)
			$this->computeProperty($pnode);
	}
	
	private function mapChar($code)
	{
		return chr(hexdec($code[1]));
	}


	private function computeProperty($node)
	{
		Property::types($node->value, $comp_array, $type_array, $match_array);
		$CC = count($comp_array);
		//echo $node->name.": ".htmlentities($node->value)."<br>";
		//echo htmlentities(var_export($type_array, true))."<br>";
		//echo htmlentities(var_export($comp_array, true))."<br>";
		$replace = array();
		for($i = 0; $i < $CC; $i++)
		{
			$comp = $comp_array[$i];
			$type = $type_array[$i];
			$match = $match_array[$i];
			//echo "$i: $comp (".htmlentities($type).")<br>";
			//echo "Match: <pre>"; var_dump($match); echo "</pre>";
			switch($type)
			{
				case "<number>":
				case "<integer>":
				case "<identifier>":
					if ($node->name == "font-weight")
					{
						switch($comp)
						{
							case "700":
								$comp = "bold";
								break;
						}
					}
					$replace[] = $comp;
					break;
		
				case "<length>": 
					$res = $this->convertLength($node, $match[0], $match[1]);
					$replace[] = $res;
					break;
		
				case "<string>":
					$text = preg_replace("/^([\"'])(.*?)(\\1)$/", "\\2", $comp);
					$replace[] = preg_replace_callback("/\\\\([^\s])+/", array($this, "mapChar"), $text);
					//var_dump($replace[count($replace)-1]);
					break;
		
				case "<percentage>":
					if (PropertyDefinition::isInherited($node->name))
					{
						$pProp = Property::get($node->ownerElement->parentNode, $node->name);
						$res = $match[0] * $pProp / 100.0;
						//echo $match[0]." * ".$pProp." / 100.0 = $res<br>";
						$replace[] = $res;
					}
					else
						$replace[] = $comp;
					break;
		
				case "<counter>":
					$counter_name = PropertyDefinition::COUNTER_PREFIX.$match[0];
					$q = './ancestor-or-self::*/@'.$counter_name;
					$cnodes = $this->XPath->query($q, $node->ownerElement);
					if ($cnodes->length)
						$replace[] = $this->formatNumber($cnodes->item($cnodes->length-1)->value, $match[1]);
					else
						$replace[] = "N/A";
					//echo htmlentities(var_export($match, true))."<hr>";
					break;
		
				case "<counters>":
					$counter_name = PropertyDefinition::COUNTER_PREFIX.$match[0];
					$joint = preg_replace("/^([\"'])(.*?)(\\1)$/", "\\2", $match[1]);
					$q = './ancestor-or-self::*/@'.$counter_name;
					$cnodes = $this->XPath->query($q, $node->ownerElement);
					$carr = array();
					foreach($cnodes as $id => $numnode)
					{
						//echo "NODE ($id) ".$numnode->value."<br>";
						$carr[] = $numnode->value;
					}
					if (count($carr) == 0) $carr[] = "N/A";
					$replace[] = implode($joint, $carr);
					//$replace[] = $node->value;
					//echo htmlentities(var_export($match, true))."<hr>";
					break;

				case "<cmyk>":
					//echo "$i: $comp (".htmlentities($type).")<br>";
					//echo "Match: <pre>"; var_dump($match); echo "</pre>";
					foreach($match as $key => $value) $match[$key] = (float)$value / 100.0;
					array_unshift($match, "cmyk");
					$replace[] = implode("|", array_pad($match, 5, 0));
					break;
		
				default:
					if (!PropertyDefinition::isKeyword($comp))
					{
						echo "WARNING: unhandled property $comp<br>";
					}
					$replace[] = $comp;
			}
		}
		//var_dump($node->name, $node->value);
		//echo "<hr>";
		#$node->value = implode("", $replace);
                $node->value = implode("", str_replace("&", "&amp;", $replace));
		
	}


	public function transform()
	{
		$q = '//@*';
		$attrs = $this->XPath->query($q);
		//echo $q." returned ".$attrs->length." nodes<br>";
		foreach($attrs as $node)
			$this->computeProperty($node);
	}
}


?>