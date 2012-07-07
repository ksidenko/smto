<?php


class LineBox
{
	const CMD_WORD = 0;
	const CMD_SPACE = 1;
	const CMD_NEWLINE = 2;
	const CMD_COLUMNBREAK = 3;
	const CMD_UNDERLINE_ON = 4;
	const CMD_UNDERLINE_OFF = 5;
	const CMD_LINK = 6;
	
	const FLOW_OK = 0;
	const FLOW_NEWLINE = 1;
	const FLOW_DONE = 2;
	const FLOW_FULL = 3;
	const FLOW_COLUMNBREAK = 4;


	private $_max_w = null;
	private $_w = 0;
	private $_lh = 0;
	private $_space_akku = 0;
	private $_cmds = array();
	private $_affected_inlines = array();
	private $_is_full = false;

	public function __construct($width)
	{
		$this->_max_w = $width;
	}

	public function canPush($width)
	{
		if ($this->_w == 0) return true;
        if ($this->_w + $this->_space_akku + $width <= $this->_max_w + Lithron::EPSILON)
        {
            return true;
        }
        else
        {
            $this->_is_full = true;
            return false;
        }
	}

	public function push($cmd)
	{
		switch($cmd[0])
		{
			case LineBox::CMD_SPACE:
				if ($this->_space_akku == 0)
					$this->_space_akku = $cmd[1];
                else
                    return;
				break;
			case Linebox::CMD_WORD:
				if ($this->_w != 0) $this->_w += $this->_space_akku;
				$this->_w += $cmd[1];
				$this->_space_akku = 0;
				break;
			case Linebox::CMD_UNDERLINE_ON:
			case Linebox::CMD_UNDERLINE_OFF:
            case LineBox::CMD_LINK:
				break;
			default:
				throw new Exception("Pushed wrong element");
		}
		$this->_cmds[] = $cmd;
	}

	public function addAffectedInline($inline)
	{
		$found = false;
		foreach($this->_affected_inlines as $cmp)
			if ($inline === $cmp)
			{
				$found = true;
				break;				
			}
		if (!$found)
		{
			$this->_affected_inlines[] = $inline;
			//Lithron::log("Saving for ".$inline->DOMNode->nodeName, "Layout");
			$inline->save();
		}
	}

	public function revert()
	{
		//Lithron::log("Reverting Box<br>".$this->dump(), "Layout");
		foreach($this->_affected_inlines as $cmp)
			$cmp->restore();
	}
	
	public function ensureLineHeight($lh)
	{
		if ($lh > $this->_lh) $this->_lh = $lh;
	}
	
	public function getWidth()
	{
		return $this->_w;
	}

	public function getHeight()
	{
		return $this->_lh;
	}
	
	public function render($rop)
	{
		if ($this->_lh == 0) return 0;
		
		$asc = -1; $desc = 1; $ta = "left";
		foreach($this->_cmds as $cmd)
			switch($cmd[0])
			{
				case LineBox::CMD_SPACE:
					break;
				case Linebox::CMD_WORD:
					$asc = max($asc, $cmd[2]->getAscender());
					$desc = min($desc, $cmd[2]->getDescender());
					$ta = $cmd[2]->getTextAlign();
					break;
			}

        // unset justify, when textbox is not full
        if($ta == "justify" && !$this->_is_full) $ta="left";

		$h = $asc - $desc;
		$halflead = ($this->_lh - $h)/2;
		//echo "<h3>RENDERING ASC: $asc, DESC: $desc, W: {$this->_w}, LH: {$this->_lh}, H: $h, HALFLEAD: $halflead</h3><br>";

		//$rop->PRE_save();
		
		if ($ta == "right")
		{
			//echo "ALIGN RIGHT<br>";
			$xoff = $this->_max_w - $this->_w;
			//var_dump($this->_max_w, $this->_w, $xoff);
		}
		else if ($ta == "center")
		{
			//echo "ALIGN CENTER<br>";
			$xoff = 0.5 * ($this->_max_w - $this->_w);
			//var_dump($this->_max_w, $this->_w, $xoff);
		}
		else if ($ta == "justify")
		{
            // TODO !!!
            $xoff = 0;
            $wordcount = 0;
            foreach($this->_cmds AS $commands)
            {
                if ($commands[0] === Linebox::CMD_WORD)
                   {
                       $wordcount++;
                   }
            }
            if ($wordcount !== 1)
            {
                $justify_space = ($this->_max_w - $this->_w)/($wordcount-1);
                #echo " space <br/>";
                #echo $wordcount." wc<br/>";
                #echo $this->_w." width<br/>";
                #echo $this->_max_w." max-width<br/>";
                #echo " <br/>";
            }

        }
        else
			$xoff = 0;		

        $c = count($this->_cmds);
		while ($c > 0) {
            if ($this->_cmds[$c-1][0] == Linebox::CMD_WORD)
                break;
            $c--;
        }
        $this->_cmds = array_slice($this->_cmds, 0, $c);

        $rop->PRE_save();
		$rop->PRE_translate($xoff, - $asc - $halflead);
		$rop->PRE_set_parameter("underline", 'false');


        $linkRecorder = array();

        $pos_akku = $xoff;
		$is_w = false; $node = null; $uline = false; 
		foreach($this->_cmds as $cmd)
		{
			switch($cmd[0])
			{
				// HTML also strips leading spaces
                case LineBox::CMD_SPACE:
					if ($is_w) 
                    {
                        if ($node !== $cmd[2])
                        {
                            $node = $cmd[2];
                            $rop->PRELI_setfont($node);
                            $col = Property::get($node, "color");
                            $rop->PRELI_setfillcolor($col);
                        }
                        $pos_akku += $cmd[1];
                        if (isset($justify_space)) {
                            $pos_akku += $justify_space;
                            $rop->PRE_set_value("charspacing", $justify_space);
                            $rop->PRE_show(" ");
                            $rop->PRE_set_value("charspacing", 0);
                        } else {
                            $rop->PRE_show(" ");
                        }
                    }
					break;
				case Linebox::CMD_WORD:
					if ($node !== $cmd[2])
					{
						$node = $cmd[2];
						$rop->PRELI_setfont($node);
						$col = Property::get($node, "color");
						$rop->PRELI_setfillcolor($col);
					}
					$rop->PRE_show($node->getWord($cmd[3]));
                    $pos_akku += $cmd[1];
					$is_w = true;
					break;
				case Linebox::CMD_UNDERLINE_ON:
					if (!$uline)
					{
						$rop->PRE_set_parameter("underline", 'true');
						$uline = true;
					}
					break;
				case Linebox::CMD_UNDERLINE_OFF:
					if ($uline)
					{
						$rop->PRE_set_parameter("underline", 'false');
						$uline = false;
					}
					break;
                case LineBox::CMD_LINK:
                    if ($cmd[1] != "") {
                        // LINK ON
                        if (!isset($linkIndex) || $cmd[1] != $linkRecorder[$linkIndex][0]) {
                            // is new: open link
                            $linkRecorder[] = array($cmd[1], $pos_akku);
                            $linkIndex = count($linkRecorder) - 1;
                        }
                    } else {
                        // LINK OFF
                        if (isset($linkIndex)) {
                            // close link
                            $linkRecorder[$linkIndex][2] = $pos_akku;
                            unset($linkIndex);
                        }
                    }
                    break;
			}
		}

        if (isset($linkIndex)) {
            $linkRecorder[$linkIndex][2] = $pos_akku;
        }

		$rop->PRE_restore();
        if (count($linkRecorder)) {
            //var_dump($linkRecorder);
            foreach($linkRecorder as $info) {
				$rop->PRELI_link($info, $this->_lh);
            }
        }
        


        //$rop->PRE_set_parameter("underline", 'false');
		//$rop->PRE_restore();
		//$rop->PRE_translate(0, $desc - $asc - 2*$halflead );
		return $this->_lh; 
	}


	public function dump()
	{
		$log = "LINEBOX MAXW:{$this->_max_w}, W:{$this->_w}, H:{$this->_lh}<br>";
		foreach($this->_cmds as $cmd)
			switch($cmd[0])
			{
				case LineBox::CMD_SPACE:
					$log .= " [SPACE] ";
					break;
				case Linebox::CMD_WORD:
					$log .= '<span style="color:red;">'.$cmd[2]->getWord($cmd[3]).'</span>';			
					break;
			}
		return $log;
	}

}


?>