<?php

class GenerateAnonymousElements extends TransformerPlugin
{
	public function transform()
	{
		// find nodes with a content property, and insert it as text
		$q = '//*[@content]';
		$result = $this->XPath->query($q);
		foreach($result as $node)
		{
			$cnt = Property::get($node, "content");
			//var_dump("$cnt<br>");
			switch($cnt)
			{
				case "pagenum":
					$newnode = $this->Document->createTextNode("%LX_PAGENUM%");
					break;
				default:
					$newnode = $this->Document->createTextNode($cnt);
					break;
			}
			$node->removeAttribute("content");
			$node->appendChild($newnode);
		}

		// find text nodes not wrapped in inline elements and wrap em
		$q = '//*[@display!="inline" and @display]/text()';
		$result = $this->XPath->query($q);
		foreach($result as $node)
		{
			//echo "<b>".$node->parentNode->nodeName."</b><br>";
			//echo "<b>".$node->parentNode->getAttribute("display")."</b><br>";
			//echo $node->nodeValue."<hr>";
			$newnode = $this->Document->createElement(PropertyDefinition::ANON_NODE_NAME);
            if ($node->parentNode->hasAttribute("text-decoration")) {
                $newnode->setAttribute("text-decoration", $node->parentNode->getAttribute("text-decoration"));
            }
            $node->parentNode->replaceChild($newnode, $node);
			$newnode->appendChild($node);
		}		

		// find inline nodes with more than 1 text child and wrap them in anonymous inline elements
		//$q = '//*[count(./child::text()) > 1]/text()';
		$q = '//*[count(./child::text()) > 1]';
		$result = $this->XPath->query($q);
		foreach($result as $node)
		{
			$subq = './@*';
			$attrs = $this->XPath->query($subq, $node);

			$subnode = $node->firstChild;
			while ($subnode)
			{
				if ($subnode->nodeType == XML_TEXT_NODE)
				{
					//echo $subnode->nodeName."<br>";
					//echo $subnode->nodeValue."<br>";
					//echo "<hr>";
					$newnode = $this->Document->createElement(PropertyDefinition::ANON_NODE_NAME);
					foreach($attrs as $attr) $newnode->setAttribute($attr->name, $attr->value);
					$subnode->parentNode->replaceChild($newnode, $subnode);
					$newnode->appendChild($subnode);
					$subnode = $newnode;
				}
				$subnode = $subnode->nextSibling;
			}
			// is better to leave them there
			//foreach($attrs as $attr) $attr->parentNode->removeAttribute($attr->name);
			Property::set($node, "display", "inline-collection");
		}

		// find inline nodes zero text childs, and change their type into inline-collection
		$q = '//*[(@display="inline" or not(@display)) and count(./child::text()) = 0 and count(./child::node()) > 0]';
		$result = $this->XPath->query($q);
		foreach($result as $node)
		{
			Lithron::log($node->nodeName." converted to inline-collection", LOG_NOTICE, "Property");
			Property::set($node, "display", "inline-collection");
		}



		return;


		// find groups of inline elements that have a non inline parent, and wrap them
		// in block-of-inline elements
		$q = '//*[@display != "inline"]/child::node()[@display="inline" or not(@display)]';
		$result = $this->XPath->query($q);
		$na = array();
		$lastindex = $result->length - 1;
		foreach($result as $num => $node)
		{
			$na[] = $node;
			if ($num == $lastindex || $result->item($num+1)->previousSibling !== $node)
			{
				$newnode = $this->Document->createElement(PropertyDefinition::ANON_NODE_NAME);
				Property::set($newnode, "display", "block-of-inline");
				Property::set($newnode, "breakable", "yes");
				foreach($na as $i => $movenode)
				{
					if ($i == 0)
						$movenode->parentNode->replaceChild($newnode, $movenode);
					else
						$movenode->parentNode->removeChild($movenode);
					$newnode->appendChild($movenode);
				}
			
				// remove all completely empty blocks	
				$subq = './/text()';
				$testnodes = $this->XPath->query($subq, $newnode);
				$remove = true;
				foreach ($testnodes as $testnode)
					if (!$testnode->isWhitespaceInElementContent() || Property::get($testnode, "white-space") != "normal")
					{
						$remove = false;
						break;
					}
				if ($remove)
					$newnode->parentNode->removeChild($newnode);
				$na = array();
			}
		}


	}
}


?>