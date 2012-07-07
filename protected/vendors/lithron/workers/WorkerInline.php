<?php

class WorkerInline extends WorkerPlugin
{
	protected $_whitespace;
	protected $_line_height;
	protected $_font_family;
	protected $_font_weight;
	protected $_font_style;
	protected $_font_size;
	protected $_text_transform;
	protected $_text_decoration;
	protected $_text_align;
	protected $_ascender;
	protected $_descender;
	protected $_space_width;
	protected $_word_spacing;
	protected $_href;

	protected $_content = array();
	protected $_pos = 0;
	protected $_pos_backup = array();

	public function save()
	{
		$this->_pos_backup[] = $this->_pos;
		return count($this->_pos_backup)-1;
	}

	public function restore($id = null)
	{
		if ($id === null) $id = count($this->_pos_backup) - 1;
		$this->_pos = $this->_pos_backup[$id];
	}

	public function is2ByteLength($word){
                #return (utf8_encode(utf8_decode($word)) == $word);

		$Slen=strlen($word);
		for ($i=0; $i<$Slen; $i++) {
			$str_ord=ord($word[$i]);
			if ($str_ord < 0xE1) // some cyrillic chars are only working with 0x80 or maybe higher - FIX ME
                            continue;
			else
                            return true;
		}
		return false;
	}


	public function initializePost()
	{
		//Lithron::trace("Initializing InlineWorker for ".$this->DOMNode->nodeName." - Start", "Init");
		$this->_whitespace = Property::get($this->DOMNode, "white-space");
		$this->_line_height = Property::get($this->DOMNode, "line-height");
		if ($this->_line_height == "normal") $this->_line_height = Property::get($this->DOMNode, "font-size") * 1.1;
		$this->_font_family = Property::get($this->DOMNode, "font-family");
		$this->_font_weight = Property::get($this->DOMNode, "font-weight");
		$this->_font_style = Property::get($this->DOMNode, "font-style");
		$this->_font_size = Property::get($this->DOMNode, "font-size");
		$this->_text_transform = Property::get($this->DOMNode, "text-transform");
		$this->_text_decoration = Property::get($this->DOMNode, "text-decoration");
		$this->_text_align = Property::get($this->DOMNode, "text-align");
		$this->_word_spacing = Property::get($this->DOMNode, "word-spacing");
		if ($this->_word_spacing == "normal") $this->_word_spacing = 0;
		$this->_ascender = $this->Lithron->getFontMetric($this->DOMNode, "ascender");
		$this->_descender = $this->Lithron->getFontMetric($this->DOMNode, "descender");
		$this->_space_width = $this->Lithron->getFontMetric($this->DOMNode, "spacewidth");
		
        $_href = Property::get($this->DOMNode, "href");
        // TODO - use a regexp here!
        if (( (substr($_href,0,7) == "http://") || (substr($_href,0,7) == "mailto:")) && strstr($_href, " ") == null) {
            $this->_href = $_href;
        } elseif($_href) {
            Lithron::log("Omitting malformed hyperlink: '".$_href."'", LOG_ERR);
        }

		$this->_space_width += $this->_word_spacing;

		$sel = "";
		if ($this->_font_weight != "normal") $sel .= $this->_font_weight;
		if ($this->_font_style != "normal") $sel .= $this->_font_style;
		if ($sel == "") $sel = "normal";
		$handle = $this->Lithron->getFontHandle($this->Lithron->DummyPDF, "dummyhandle", $this->_font_family, $sel);

		$q = "./child::text()";
		$result = $this->XPath->query($q, $this->DOMNode);
		$rnum = $result->length;
		if ($rnum > 1)
			throw new Exception("Assert - got no more than 1 textnode failed, got $rnum!");
		elseif ($rnum == 0)
			return;
		$node = $result->item(0);
		switch($this->_whitespace)
		{
			case "normal":
				$words = preg_split("/([\s]+)/", $node->nodeValue, -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY);
				foreach($words as $word)
					if (trim($word) == "")
						$this->_content[] = LineBox::CMD_SPACE;
					else
					{
						switch($this->_text_transform)
						{
							case "uppercase_sz":
								$word = str_replace("ß", "ss", $word);
								// break left intentionally
							case "uppercase":
								$word = mb_convert_case($word, MB_CASE_UPPER, "UTF-8");
								$word = str_replace("&NBSP;", "&nbsp;", $word);
								break;
							case "lowercase":
								$word = mb_convert_case($word, MB_CASE_LOWER, "UTF-8");
								break;
							case "capitalize":
								$word = mb_convert_case($word, MB_CASE_TITLE, "UTF-8");
								break;
						}

                                                if ($this->is2ByteLength($word)){
							preg_match_all('/./u', $word, $chars);
							foreach($chars[0] as $char)
								$this->_content[] = array($char, $this->Lithron->DummyPDF->stringwidth($char, $handle, (float)$this->_font_size));
						}
						else
						{
                                                        // TODO - fixme: Problems with cyrillic chars?
							$this->_content[] = array($word, $this->Lithron->DummyPDF->stringwidth($word, $handle, (float)$this->_font_size));
						}
					}
				break;

			case "pre":
				$lines = preg_split("/([\n\f])/", $node->nodeValue, -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY);
				foreach($lines as $line)
					switch($line)
					{
						case chr(0x0a):
							$this->_content[] = LineBox::CMD_NEWLINE;
							break;
						case chr(0x0c):
							$this->_content[] = LineBox::CMD_COLUMNBREAK;
							break;
						default:
							$this->_content[] = array($line, $this->Lithron->DummyPDF->stringwidth($line, $handle, $this->_font_size));
					}
				break;
		}

		//Lithron::trace("Initializing InlineWorker for ".$this->DOMNode->nodeName." - End", "Init");
		//echo "WS: {$this->_whitespace}, ASC: {$this->_ascender}, DESC: {$this->_descender}, LH: {$this->_line_height}<br>";
		//var_dump($this->_content);
		//echo "<hr>";
	}

	public function getLineHeight()
	{
		return $this->_lh;
	}

	public function getWord($pos)
	{
		$w = $this->_content[$pos][0];
		if ($w == "%LX_PAGENUM%")
		{
			//echo "GETWORD ".$this->Lithron->CurrentPageNum."<br>";
			$w = $this->Lithron->CurrentPageNum-1;
		}
		else if ($w == "%LX_ANCHOR%")
		{
			$w .= $this->propAnchorGet();
		}
		return $w;
	}

	public function getAscender()
	{
		return $this->_ascender;
	}

	public function getDescender()
	{
		return $this->_descender;
	}

	public function getTextAlign()
	{
		return $this->_text_align;
	}

	public function flowInto($lb)
	{
		if ($this->propAnchorSet() != "none")
		{
			//echo "SET ANCHOR ".$this->propAnchorSet()." TO ".$this->Lithron->CurrentPageNum."<br>";
			$this->Lithron->Anchors[$this->propAnchorSet()] = $this->Lithron->CurrentPageNum-1;
		}

		$ret = LineBox::FLOW_OK;
		do
		{
			if ($this->_pos >= count($this->_content))
			{
				//Lithron::trace("<b style=\"color:fuchsia;\">".$this->DOMNode->nodeName.".flowInto() - no more content</b>", "Layout", $this->Level);
				$ret = LineBox::FLOW_DONE;
			}
			else
				switch($this->_content[$this->_pos])
				{
					case LineBox::CMD_SPACE:
						$lb->addAffectedInline($this);
						if ($this->_text_decoration == "underline")
							$lb->push(array(LineBox::CMD_UNDERLINE_ON));
						else
							$lb->push(array(LineBox::CMD_UNDERLINE_OFF));
						$lb->push(array(LineBox::CMD_LINK, $this->_href));

						$lb->push(array(LineBox::CMD_SPACE, $this->_space_width, $this));
						$this->_pos++;
						//Lithron::trace("<b style=\"color:fuchsia;\">".$this->DOMNode->nodeName.".flowInto() - pushed space</b>", "Layout", $this->Level);
						break;
					case LineBox::CMD_NEWLINE:
						$lb->addAffectedInline($this);
						$lb->ensureLineHeight($this->_line_height);
						$ret = LineBox::FLOW_NEWLINE;
						$this->_pos++;
						break;
					case LineBox::CMD_COLUMNBREAK:
						$lb->addAffectedInline($this);
						$ret = LineBox::FLOW_COLUMNBREAK;
						$this->_pos++;
						break;
					default:
						$w = $this->_content[$this->_pos][1];
						if ($this->_content[$this->_pos][0] == "%LX_PAGENUM%")
						{
							$sel = "";
							if ($this->_font_weight != "normal") $sel .= $this->_font_weight;
							if ($this->_font_style != "normal") $sel .= $this->_font_style;
							if ($sel == "") $sel = "normal";
							$handle = $this->Lithron->getFontHandle($this->Lithron->DummyPDF, "dummyhandle", $this->_font_family, $sel);
							$w = $this->Lithron->DummyPDF->stringwidth($this->Lithron->CurrentPageNum-1, $handle, $this->_font_size);
							//echo "GETWIDTH ".$this->Lithron->CurrentPageNum."<br>";
						}
						elseif ($this->_content[$this->_pos][0] == "%LX_ANCHOR%")
						{
						}
						if ($lb->canPush($w))
						{
							$lb->addAffectedInline($this);
							if ($this->_text_decoration == "underline")
								$lb->push(array(LineBox::CMD_UNDERLINE_ON));
							else
								$lb->push(array(LineBox::CMD_UNDERLINE_OFF));
    						$lb->push(array(LineBox::CMD_LINK, $this->_href));
							$lb->push(array(LineBox::CMD_WORD, $w, $this, $this->_pos));
							$lb->ensureLineHeight($this->_line_height);
							$this->_pos++;
							//Lithron::trace("<b style=\"color:fuchsia;\">".$this->DOMNode->nodeName.".flowInto() - pushed {$this->_content[$this->_pos-1][0]}</b>", "Layout", $this->Level);
						}
						else
							$ret = Linebox::FLOW_FULL;
				}
		}
		while($ret == LineBox::FLOW_OK);

		//$msg = array(LineBox::FLOW_OK => "FLOW_OK", LineBox::FLOW_NEWLINE => "FLOW_NEWLINE", LineBox::FLOW_DONE => "FLOW_DONE", LineBox::FLOW_FULL => "FLOW_FULL",);
		//Lithron::trace("<b style=\"color:orange;\">".$this->DOMNode->nodeName.".flowInto() - finished with ".$msg[$ret]."</b>", "Layout", $this->Level);
		return $ret;
	}

}


?>