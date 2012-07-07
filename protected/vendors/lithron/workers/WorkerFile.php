<?php

class WorkerFile extends WorkerPlugin
{
	public $FilePath;
        public $Name;
	
	public function work()
	{
		$this->Lithron->Files[] = $this;
		
		/*
		$this->Lithron->Wells = array();
		Lithron::$ActiveSink = null;
		Lithron::$ActiveFile = null;
		*/

		$file_rop = new RenderOperation($this);

		$file_rop->PRE_set_info("Title", $this->propTitle());
		$file_rop->PRE_set_info("Author", $this->propAuthor());		
		$file_rop->PRE_set_info("Creator", $this->propCreator());		
		
		$dir = $this->Lithron->getOutputPath()."/".$this->Lithron->JobId; 
		$this->Name = $this->propName();
                $this->FilePath = $dir."/".$this->propName();
		Lithron::$ActiveFile = $this->FilePath;

		$params = array();
		$params[] = "compatibility={".$this->propCompatibility()."}";
		if ($this->propPermissions()) 
		{
			$params[] = "masterpassword={".uniqid()."}";
			$params[] = "permissions={".$this->propPermissions()."}";
		}
		$file_rop->PRE_begin_document($this->FilePath, implode(" ", $params));

		// disable host fonts because of Leopard bug
		$file_rop->PRE_set_parameter("debug", "h");

		$file_rop->PRE_set_parameter("textformat", "utf8");
		$file_rop->PRE_set_parameter("charref", "true");		
		$file_rop->POST_end_document("");

		$w = $this->firstChildWorker;
		while ($w)
		{
			$subrop = new RenderOperation($w);
			$w->work($subrop);
			$file_rop->addChild($subrop);
			$w = $w->nextSiblingWorker;
		}
		
		$pdf = new PDFLib();
		if ($this->Lithron->LicenseFile !== null)
		{
			// set license key
			try 
			{
				$res = $pdf->set_parameter("licensefile", $this->Lithron->LicenseFile);
				Lithron::log("PDFlib serial number set - $res", LOG_DEBUG, "Lithron");	
			}
			catch (PDFlibException $e)
			{
				$pdf = new PDFLib();
				Lithron::log("PDFlib license file invalid", LOG_WARNING, "Lithron");	
			}
		}		
		
		$file_rop->execute($pdf);
	}
}


?>