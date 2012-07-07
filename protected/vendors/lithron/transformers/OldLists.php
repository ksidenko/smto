<?php

class OldLists extends TransformerPlugin
{
	public function transform()
	{
		$q = '//*[@display="list-item"][not (./child::'.PropertyDefinition::ANON_NODE_NAME.'[@'.PropertyDefinition::PSEUDO_SELECTOR_NAME.'="before"][@display="marker"])]';
		$result = $this->XPath->query($q);
		//echo $q." returned ".$result->length." nodes<br>";

		foreach($result as $node)
		{
			$newnode = $this->Document->createElement(PropertyDefinition::ANON_NODE_NAME);
			if ($node->firstChild)
				$node->insertBefore($newnode, $node->firstChild);
			else
				$node->appendChild($newnode);

			Property::set($newnode, PropertyDefinition::PSEUDO_SELECTOR_NAME, "before");
			Property::set($newnode, "display", "marker");
			Property::set($newnode->parentNode, "counter-increment", "item");
			
			$st = Property::get($node, "list-style-type");
			$content = "";
			switch($st)
			{
				case "decimal":
					//$content = 'counter(item, decimal) ". "'; 
					$content = 'counters(item, ".") " "'; 
					break;
					
				case "disc":					
					$content = '"&amp;middot;"'; 
					break;
			}
			
			if ($content)
			{
				Property::validate("content", $content);
				Property::set($newnode, "content", $content);
			} 
			
		}
	}
}


?>