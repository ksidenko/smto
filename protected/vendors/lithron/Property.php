<?php

class Property
{
	public static function get($node, $attribute, $index = -1)
	{
		$cached_attribute = "cached_".$attribute;
		$base_node = null;
		if ($node instanceof DOMElement)
		{
			if ($node->hasAttribute($cached_attribute))
				return $node->getAttribute($cached_attribute);
			$base_node = $node;		
		}

		//if (!$node instanceof DOMElement) return null;
		if ($node instanceof WorkerPlugin) $node = $node->DOMNode;
		/*
		if (!($node instanceof DOMNode))
		{
			return null;
			var_dump($node);
		}
		*/
		//echo "Property::get $attribute of ".$node->nodeName."<br>";
		
		if (!PropertyDefinition::has($attribute)) 
			return $node->getAttribute($attribute);	
		
		if (PropertyDefinition::isInherited($attribute))
			while ($node)
			{
				if (!$node instanceof DOMElement)
				{
					$node = $node->parentNode;
					continue;					
				}
				if ($node->hasAttribute($attribute)) break;			
				$node = $node->parentNode;
			}


		if ($node instanceof DOMElement && $node->hasAttribute($attribute))
			$retval = $node->getAttribute($attribute);
		else		
			$retval = PropertyDefinition::initial($attribute);

		if (Lithron::$AllowCaching && $base_node)
		{
			$base_node->setAttribute($cached_attribute, $retval);
		}
			
		if ($index == -1) return $retval;
		//self::types($retval, $comp_array, $type_array);
			
	}
	
	public static function set($node, $attribute, $value)
	{
		if (!PropertyDefinition::has($attribute) && !PropertyDefinition::isPrivate($attribute))
			Lithron::log("Setting unknown property $attribute", LOG_NOTICE, "Property");
		$node->setAttribute($attribute, $value);
		if (Lithron::$AllowCaching)
		{
			Lithron::log("setting cache value for $attribute to  $value", LOG_NOTICE, "Property");
			$node->setAttribute("cached_".$attribute, $value);
			
		}
	}
	
	public static function types($value, &$components, &$types, &$matches)
	{
		$types = array();
		$matches = array();
		$reg = "/\s*((?:\"(?:\\\\.|[^\"])*\"?)|(?:'(?:\\\\.|[^'])*'?)|(?:[a-z]+\(.*?\))|\/)|\s+/";
		$components = preg_split($reg, $value, -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY);
		//echo "<pre>"; var_dump($components); echo "</pre>";
		foreach($components as $num => $comp)
		{
			if (preg_match("/^[\+\-]?[0-9]+$/", $comp))
			{
				$types[] = "<integer>";
				$matches[] = array($comp);
			}
			else if (preg_match("/^[\+\-]?[0-9]*(\.[0-9]+)?$/", $comp))
			{
				$types[] = "<number>";
				$matches[] = array($comp);
			}
			if (preg_match("/^([\"']).*\\1$/", $comp))
			{
				$types[] = "<string>";
				$matches[] = array($comp);
			}
			if (preg_match("/^(0)$/", $comp, $m) ||
				preg_match("/^([\+\-]?[0-9]*\.?[0-9]+?)(em|ex|px|in|cm|mm|pt|pc)$/", $comp, $m))
			{
				$types[] = "<length>";
				array_shift($m);
				$matches[] = $m;
			}
			if (preg_match("/^[\+\-]?[0-9]*(\.[0-9]+)?%$/", $comp, $m))
			{
				$types[] = "<percentage>";
				$matches[] = array(substr($comp, 0, strlen($comp)-1));
			}
			if (preg_match("/^([a-z]+)\((.*?)\)$/", $comp, $m))
			{
				$types[] = "<{$m[1]}>";
				$reg = "/\s*,\s*/";
				$fparams = preg_split($reg, $m[2], -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY);
				$matches[] = $fparams;
			}
			if (count($types) == $num)
			{
				if (PropertyDefinition::isKeyword($comp))
					$types[] = $comp;
				else
					$types[] = "<identifier>";
					
				$matches[] = array($comp);
			}
		}
		return implode(" ", $types);
	}

	public static function validate($name, $value, $show_error = true)
	{
		if (!PropertyDefinition::has($name)) 
		{
			 if ($show_error)
				Lithron::log("Property $name is unknown", LOG_NOTICE, "Property");
			return false;
		}
		$types = self::types($value, $comp_array, $type_array, $match_array);
		$pattern = PropertyDefinition::allowed($name);
		if (PropertyDefinition::isShorthand($name))
		{
			preg_match_all("/<'(.*?)'>/", $pattern, $shprops);
			foreach($shprops[1] as $key => $match)
				if (PropertyDefinition::has($match))
					$pattern = preg_replace("/<'$match'>/", "(".PropertyDefinition::allowed($match).")", $pattern); 			
		}

		$valid = false;
		foreach($type_array as $type)
		{
			$valid = preg_match("/^$pattern$/", $type) > 0;
			if ($valid) break;
		}
		
		if (!$valid && $show_error) 
		{
			Lithron::log("Property $name could not be validated", LOG_NOTICE, "Property");
			Lithron::log("Value: $value", LOG_DEBUG, "Property");
			Lithron::log("Types: ".htmlentities($types), LOG_DEBUG, "Property");
			Lithron::log("Allowed: ".htmlentities($pattern), LOG_DEBUG, "Property");
		}
		return $valid;		
	}

}

?>
