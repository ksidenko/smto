<?php

require_once(dirname(__FILE__)."/WorkerBlock.php");

class WorkerPdfLibCommand extends WorkerBlock
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
			$this->_is_pdf = true;
			$pdf_handle = $pdfLib->open_pdi($fname, '', 0);
			$img_handle = $pdfLib->open_pdi_page($pdf_handle, 1, '');
		}
		else
		{
            $img_handle = $pdfLib->load_image("auto", $fname, "");
		}
		
		if ($parts[count($parts)-1] == "pdf")
		{
			$this->_width = $pdfLib->get_pdi_value("width", $pdf_handle, $img_handle, 0);
			$this->_height = $pdfLib->get_pdi_value("height", $pdf_handle, $img_handle, 0);
			$this->_ires = 72.0;
		}
		else
		{
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
        return 0;
	}

	public function getPrefHeight($width)
	{
        return 0;
	}

	public function work($rop, $cw, $ch)
	{
		$subrop = new RenderOperation($this);
		$rop->addChild($subrop);
		$subrop->setDimensions($cw, $ch);
        #eval($this->)
		#$picrop->POST_drawLine($this->propSrc(), $this->propImgScale(), $this->propImgPositionMode(), $this->propImgPositionX(), $this->propImgPositionY());

        $subrop->PRE_moveto(0,0);
        $subrop->PRE_lineto(100,-100);
        $subrop->PRE_stroke();
		#$rop->POST_stroke();
		
		parent::work($rop, $cw, $ch);
	}
}


?>