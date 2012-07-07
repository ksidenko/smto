<?php


class RenderOperation
{
	private $_pre = array();
	private $_children = array();
	private $_positioned = array();
	private $_post = array();
	private $_worker = null;
	public $_width = 0;
	public $_height = 0;
	
	private $_top = 0;
	private $_bottom = 0;
	private $_left = 0;
	private $_right = 0;
	
	public function __construct($worker = null)
	{
		if ($worker !== null)
		{
			$this->_worker = $worker;
			$worker->RenderOperation = $this;
		}
	}
	
	public function setPosition($top, $right, $bottom, $left)
	{
		if ($left === "auto" && $right === "auto") $left = 0;						
		if ($top === "auto" && $bottom === "auto") $top = 0;						
		if ($left !== "auto" && $right !== "auto") $right = "auto";						
		if ($top !== "auto" && $bottom !== "auto") $bottom = "auto";
		$this->_top    = $top;
		$this->_bottom = $bottom;
		$this->_left   = $left;
		$this->_right  = $right;
	}

	public function setDimensions($width, $height)
	{
		$this->_width = max($width,0); // TODO - Log Message?
		$this->_height = max($height,0);
	}

	public function __call($name, $params)
	{
		if (strpos($name, "PRE_") === 0)
		{
			$func = substr($name, 4);
			$this->_pre[] = array("PDF", $func, $params);
		}
		if (strpos($name, "PRELI_") === 0)
		{
			$func = substr($name, 6);
			$this->_pre[] = array("Lithron", $func, $params);
		}
		if (strpos($name, "POST_") === 0)
		{
			$func = substr($name, 5);
			$this->_post[] = array("PDF", $func, $params);
		}
		if (strpos($name, "POSTLI_") === 0)
		{
			$func = substr($name, 7);
			$this->_post[] = array("Lithron", $func, $params);
		}

		if ($func == "begin_page_ext")
		{
			$this->_worker->Lithron->CurrentPageNum++;
			//echo "BEGIN PAGE ".$this->_worker->Lithron->CurrentPageNum."<br>";
		}
		return null;
	}
	
	public function addChild($c)
	{
		$this->_children[] = $c;
	}
	
	public function addPositioned($c)
	{
		$this->_positioned[] = $c;
	} 
	
	private function issueSingleCommand($pdfLib, $command, $param)
	{
		if ($command == "show" && strpos($param[0], "%LX_ANCHOR%") === 0)
		{
			$split = explode("%", $param[0]);
			$param[0] = Lithron::$Instance->Anchors[$split[2]]; 
		}

		$pparm = array();
		foreach($param as $cmd)
		{
			if (is_string($cmd))
				$pparm[] = "\"$cmd\"";
			else if (is_bool($cmd))
				$pparm[] = $cmd ? "true" : "false";
			else if (is_object($cmd))
				$pparm[] = "<".get_class($cmd).">";
			else 
				$pparm[] = $cmd;
		}

		$log = array();
		$scope = $pdfLib->get_parameter("scope", 0);
		$log[] = $scope;
		/*
		if (!in_array($scope, array("object", "document")))
		{
			$x = $pdfLib->get_value("currentx", 0);
			$y = $pdfLib->get_value("currenty", 0);
			$log[] = "($x / $y)";
		}
		*/
		$log[] = "PDF->".$command."(".implode(", ", $pparm).")";
		try
		{
			ob_start();
            $ret = call_user_func_array(array($pdfLib, $command), $param);
			$log[] = $ret;
            $ob = ob_get_clean();
            if (!empty($ob))
            {
                Lithron::log($ob, LOG_DEBUG, "RenderOperation"); // TODO ?
            }
		}
		catch (exception $e)
		{
            throw new LithronException($e->getMessage());
            #die("<h1>PDFlib Exception</h1>".$e->getMessage());
		}
		/*
		if (!in_array($scope, array("object", "document")))
		{
			$x = $pdfLib->get_value("currentx", 0);
			$y = $pdfLib->get_value("currenty", 0);
			$log[] = "($x / $y)";
		}
		*/
		
		$msg = implode(" -- ", $log)."<br/>";  
		Lithron::trace($msg);
		
		if ($ret == 0)
		{
			throw new Exception($pdfLib->get_errmsg());
		}
        return $ret;
	}

	private function execCommand($pdfLib, $command)
	{
		if ($command[0] == "PDF")
			$this->issueSingleCommand($pdfLib, $command[1], $command[2]);
		else
		{
			switch($command[1])
			{
				case "setfont":
					$node = $command[2][0];
					$family = Property::get($node, "font-family");
					$weight = Property::get($node, "font-weight");
					$style = Property::get($node, "font-style");
					$size = Property::get($node, "font-size");
					$sel = "";
					if ($weight != "normal") $sel .= $weight;
					if ($style != "normal") $sel .= $style;
					if ($sel == "") $sel = "normal";
					$handle = Lithron::$Instance->getFontHandle($pdfLib, Lithron::$ActiveFile, $family, $sel);
					$this->issueSingleCommand($pdfLib, "setfont", array($handle, $size));
					break;

				case "setfillcolor":
					$fc = explode("|", $command[2][0]);
					//Lithron::trace("Setting fill color {$command[2][0]}!", "Output");	
					array_unshift($fc, "fill");
					$this->issueSingleCommand($pdfLib, "setcolor", $fc);
					break;

                case "setstrokecolor":
					$fc = explode("|", $command[2][0]);
					//Lithron::trace("Setting fill color {$command[2][0]}!", "Output");
					array_unshift($fc, "stroke");
					$this->issueSingleCommand($pdfLib, "setcolor", $fc);
					break;

                case "setfillstrokecolor":
					$fc = explode("|", $command[2][0]);
					//Lithron::trace("Setting fill color {$command[2][0]}!", "Output");
					array_unshift($fc, "fillstroke");
					$this->issueSingleCommand($pdfLib, "setcolor", $fc);
					break;

				case "rect":
					$x = $pdfLib->get_value("currentx", 0);
					$y = $pdfLib->get_value("currenty", 0);
					$w = $command[2][0];
					$h = $command[2][1];
					$this->issueSingleCommand($pdfLib, "rect", array($x, $y-$h, $w, $h));
					$this->issueSingleCommand($pdfLib, "fill", array());
					//$this->issueSingleCommand($pdfLib, "translate", array(0, $h));
					break;

				case "putimage":
					$fname = html_entity_decode($command[2][0], ENT_COMPAT, "UTF-8");
					$scale = $command[2][1];
					$mode = $command[2][2];
					$px = $command[2][3];
					$py = $command[2][4];

					$gotrights = is_readable($fname) && is_file($fname);
					if (!$gotrights) 
					{
						Lithron::log("Image '$fname' cannot be read!", LOG_WARNING, "Files");
						break;
					}

					$parts = explode(".", $fname);
					if ($parts[count($parts)-1] == "pdf")
					{
						// PDFlib Lite Hack - TODO
                        try{
                            $pdf_handle = $pdfLib->open_pdi($fname, '', 0);
                            $img_handle = $pdfLib->open_pdi_page($pdf_handle, 1, '');
                            $cmd = "fit_pdi_page";
                        }
                        catch (Exception $e)
                        {
                            Lithron::log($e->getMessage(), LOG_ERR, "Files");
                            #$img_handle = 0;
                        }
					}
					else
					{
						$img_handle = $pdfLib->load_image("auto", $fname, "");
						$cmd = "fit_image";
					}
					if ($img_handle == 0)
					{
							Lithron::log("Image '$fname' cannot be read!", LOG_WARNING, "Files");
							break;	
					}
					if ($parts[count($parts)-1] == "pdf")
					{
						$iw = $pdfLib->get_pdi_value("width", $pdf_handle, $img_handle, 0);
						$ih = $pdfLib->get_pdi_value("height", $pdf_handle, $img_handle, 0);
						$ires = 72.0;
					}
					else
					{
						$iw = $pdfLib->get_value("imagewidth", $img_handle);
						$ih = $pdfLib->get_value("imageheight", $img_handle);
						$ires = $pdfLib->get_value("resx", $img_handle);
						if ($ires <= 0) $ires = 72.0;
					}
					Lithron::trace("FILE: '$fname', IRES: $ires, IW: $iw, IH: $ih, SCALE: $scale, MODE $mode, POSX ($px / $py) WIDTH: ".$this->_width.", HEIGHT: ".$this->_height."<br>") ;
					$ires = $ires / 72.0;

					$params = array();
					switch($mode)
					{
						case "clip":
							$s = $scale*$ires;
							$params[] = "scale={{$s}}";
							break;
						case "meet":
							$px *= 100;
							$py *= 100;
							$params[] = "fitmethod={{$mode}}";
							$params[] = "boxsize={".$this->_width." ".$this->_height."}";
							$params[] = "position={ $px $py }"; 
							break;	
						
					}

					$fitstr = implode(" ", $params);
					switch($mode)
					{
						case "clip":
							$this->issueSingleCommand($pdfLib, "save", array());
							$this->issueSingleCommand($pdfLib, "rect", array(0, -$this->_height, $this->_width, $this->_height));
							$this->issueSingleCommand($pdfLib, "clip", array());
							$this->issueSingleCommand($pdfLib, $cmd, array($img_handle, $px*$this->_width, -$py*$this->_width - $ih*$scale, $fitstr));
							$this->issueSingleCommand($pdfLib, "restore", array());
							break;
						case "meet":
							$this->issueSingleCommand($pdfLib, $cmd, array($img_handle, 0, -$this->_height, $fitstr));
							break;	
						
					}
					break;

				case "positioned":
					foreach($this->_positioned as $elem)
					{
						//echo "POSITIONED ".$elem->_worker->DOMNode->nodeName." IN ".$this->_worker->DOMNode->nodeName."<br>";
						//echo "CONTAINER W/H: ".$this->_width." / ".$this->_height."<br>";
						//echo "ELEM W/H: ".$elem->_width." / ".$elem->_height."<br>";						
						//var_dump($this->_top, $this->_right, $this->_bottom, $this->_left);
						//echo "<hr>";

						//$t = $elem->_worker->propTop();
						//$r = $elem->_worker->propRight();
						//$b = $elem->_worker->propBottom();
						//$l = $elem->_worker->propLeft();
						//if ($l == "auto" && $r == "auto") $l = 0;						
						//if ($t == "auto" && $b == "auto") $t = 0;						
						//if ($l != "auto" && $r != "auto") $r = "auto";						
						//if ($t != "auto" && $b != "auto") $b = "auto";
						//echo "PINFO $t $r $l $b<br>";
						
						// TODO  unbedingt überall ----> var_dump($t, ($t === "auto"));
						// da (int 0) == "auto" --> true;
						
						$topc = ($elem->_top === "auto") ? $this->_height - $elem->_bottom - $elem->_height : $elem->_top;
						$leftc = ($elem->_left === "auto") ? $this->_width - $elem->_right - $elem->_width : $elem->_left;
						//var_dump($topc, $leftc);
						//echo "<hr>";
						
						$this->issueSingleCommand($pdfLib, "save", array());
						$this->issueSingleCommand($pdfLib, "translate", array($leftc, -$topc));
						$elem->execute($pdfLib);
						$this->issueSingleCommand($pdfLib, "restore", array());
					}
					break;

                case "link":
                    $info = $command[2][0];
                    $lh = $command[2][1];
                    if (isset($info[0]) && isset($info[1]) && isset($info[2])) {
    					$action = $this->issueSingleCommand($pdfLib, "create_action", array("URI", "url={".$info[0]."}"));
	    				$this->issueSingleCommand($pdfLib, "create_annotation", array($info[1], -$lh, $info[2], 0, "Link", "usercoordinates=true linewidth=0 action {activate $action}"));
					} else {
					    Lithron::log("Unable to create link.", LOG_DEBUG, "RenderOperation"); // TODO ?
					}
                    break;

			}
		}
	}

	public function execute($pdfLib)
	{
		foreach($this->_pre as $command)
			$this->execCommand($pdfLib, $command);
			
		foreach($this->_children as $child)
			$child->execute($pdfLib);

		foreach($this->_post as $command)
			$this->execCommand($pdfLib, $command);
	}

}

?>
