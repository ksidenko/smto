<?php

require_once(dirname(__FILE__)."/WorkerBlock.php");

class WorkerBlockImage extends WorkerBlock
{
	private $_is_readable = false;
	private $_width = 0;
	private $_height = 0;
	private $_ires = 72.0;
	private $_is_pdf = false;

	public function initializePost()
	{
        $fname = html_entity_decode($this->propSrc(), ENT_COMPAT, "UTF-8");
		$pdfLib = $this->Lithron->DummyPDF;

		$this->_is_readable = is_file($fname) && is_readable($fname);
		if (!$this->_is_readable) {
    		Lithron::log("Failed to load image: ".$fname, LOG_WARNING, "BlockImage");
            return;
        }

		$parts = explode(".", $fname);
		if ($parts[count($parts)-1] == "pdf")
		{
			// PDFlib Lite check - TODO?
            if ($this->Lithron->PdfLibMode == "lite")
            {
        		Lithron::log("PDFlib Lite mode: Aborting WorkerBlockImage::initializePost() for ".$fname, LOG_WARNING, "BlockImage");
            }
            else
            {
                $this->_is_pdf = true;
                $pdf_handle = $pdfLib->open_pdi($fname, '', 0);
                $img_handle = $pdfLib->open_pdi_page($pdf_handle, 1, '');
                $this->_width = $pdfLib->get_pdi_value("width", $pdf_handle, $img_handle, 0);
                $this->_height = $pdfLib->get_pdi_value("height", $pdf_handle, $img_handle, 0);
                $this->_ires = 72.0;
            }
		}
		else
		{
            $img_handle = $pdfLib->load_image("auto", $fname, "");
			$this->_width = $pdfLib->get_value("imagewidth", $img_handle);
			$this->_height = $pdfLib->get_value("imageheight", $img_handle);
			$this->_ires = $pdfLib->get_value("resx", $img_handle);
			//var_dump("RES", $pdfLib->get_value("resx", $img_handle), $pdfLib->get_value("resy", $img_handle));
			if ($this->_ires <= 0) $this->_ires = 72.0;
		}
				
		//var_dump($fname, $this->_is_readable, $this->_width, $this->_height, $this->_ires);
		
		if ($this->_width == 0 || $this->_height == 0)
			$this->_is_readable = false;
	}
	
	public function getPrefWidth()
	{
		if (!$this->_is_readable) return 0;
		$res = $this->_width*$this->propImgScale(); //*72.0/$this->_ires;
		Lithron::trace("<span style=\"color:blue;\">".$this->DOMNode->nodeName.".getPrefWidth() = $res</span>", "Layout", $this->Level);
		return $res;
	}

	public function getPrefHeight($width)
	{
		if (!$this->_is_readable) return 0;
		$res = $this->_height * $width / $this->_width;
		Lithron::trace("<span style=\"color:blue;\">".$this->DOMNode->nodeName.".getPrefHeight($width) = $res</span>", "Layout", $this->Level);
		return $res;
	}
	

	private function convertToLowRes($cw, $ch)
	{
		$origname = html_entity_decode($this->propSrc(), ENT_COMPAT, "UTF-8");
		if (!is_readable($origname)) return;

		$px = $this->propImgPositionX();
		$py = $this->propImgPositionY();
		$dpifactor = 72.0/$this->_ires;
		
		//var_dump($cw,$ch);
		
		switch($this->propImgPositionMode())
		{
			case "clip":
				$s = $this->propImgScale();
				$actw = $this->_width*$s;
				$acth = $this->_height*$s;
				var_dump($actw, $acth);
				$xover1 = max(0, -$cw*$px);
				$xover2 = max(0, $cw*$px + $actw - $cw);
				$yover1 = max(0, -$cw*$py);
				$yover2 = max(0, $cw*$py + $acth - $ch);
				$show = array(
					$xover1/$s, 
					$yover1/$s, 
					($actw - $xover1 - $xover2)/$s, 
					($acth - $yover1 - $yover2)/$s,
					$this->propImgScale()
				);
				break;
			case "meet":
			default:
				$show = array(0, 0, $this->_width, $this->_height, $cw/$this->_width);
				break;
		}

		$format = "%1.5f";
		$format_sigma = "%+1.5f";

		$fhash = md5_file($origname);
		$counterfeitname = $this->Lithron->getTmpPath()."/".md5($fhash."|".implode("|", $show)).".jpg";

		$retval = 0;
		if (!is_file($counterfeitname))
		{
			$page = 0;
			$cmd = $this->Lithron->getIMagickPath()."/convert \"{$origname}[{$page}]\" ";
			$cmd .= "-strip ";
			$cmd .= "-crop ".(sprintf($format, $show[2]))."x".(sprintf($format, $show[3])).(sprintf($format_sigma, $show[0])).(sprintf($format_sigma, $show[1]))." +repage ";
			$cmd .= "-geometry ".(sprintf($format, $show[4]*100))."% ";
			$cmd .= "-density 72 ";
			$cmd .= $counterfeitname;
			$res = exec($cmd, $output, $retval);
			
            switch($retval)
            {
                case 127:
                   $level = LOG_ERR;
                   break;
                case 1:
                   $level = LOG_WARNING;
                   break;
                default:
                   $level = LOG_NOTICE;
                   break;
            }
            Lithron::log("issued ".$cmd." - return code was ".$retval, $level, "ImageMagick");
		}
		
		//var_dump($counterfeitname, $show, filesize($counterfeitname), $retval);
		
		if ($retval == 0)
		{
			Property::set($this->DOMNode, "src", $counterfeitname);
			Property::set($this->DOMNode, "img-scale", 1.0);
			if ($this->propImgPositionMode() == "clip")
			{
				Property::set($this->DOMNode, "img-position-x", max(0, $this->propImgPositionX()));
				Property::set($this->DOMNode, "img-position-y", max(0, $this->propImgPositionY()));
			}
		}
	}

    public function work($rop, $cw, $ch)
    {
        if (!$this->_is_readable) return;
		// LORES / HIRES
		if ($this->propImgResolution() == "low")
		{
			if ($lsrc = $this->propSrcLow())
				Property::set($this->DOMNode, "src", $lsrc);
			elseif (!$this->_is_pdf)
				$this->convertToLowRes($cw, $ch);			
		}
		$picrop = new RenderOperation($this);
		$rop->addChild($picrop);
		$picrop->setDimensions($cw, $ch);
		$picrop->POSTLI_putimage($this->propSrc(), $this->propImgScale(), $this->propImgPositionMode(), $this->propImgPositionX(), $this->propImgPositionY());
		
		parent::work($rop, $cw, $ch);
	}
}


?>