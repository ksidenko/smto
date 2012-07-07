<?php

class LithronDisplay extends TTemplateControl
{
	var $_L;
	
	function onPreRender($param)
	{
		$this->DebugPanel->Controls[] = $this->_L->dumpLog();
		foreach ($this->_L->Files as $file)
			{
				
				$this->PdfPanel->Controls[]= '<div class="pdf-download"><b id="DownloadPDF" >Download <a href="' . $file->FilePath . '">' . $file->propName() . '</a></b><br/><br/>';
				$preview = new LImage;
				$preview->ImageUrl = $file->FilePath;
				$preview->DestHeight = 250;
				$preview->DestWidth = 400;
				$preview->DestType = "png";
				$preview->Style->setStyleField('border','1px solid gray');
				$this->PdfPanel->Controls[]= '<a href="' . $file->FilePath . '">';
				$this->PdfPanel->Controls[]= $preview;
				$this->PdfPanel->Controls[]= '</a></div>';
			}
			$this->PdfPanel->Controls[]= '<div class="pdf-download-rightclick">Please right-click to download files to your harddisk</div>';
			$hl= new THyperLink();
			$hl->setText("Download a package with all files");
			$hl->setNavigateUrl("?get_tar=" . $this->_L->JobId);
			$hl->setTarget("_blank");
			$this->PdfPanel->Controls[]= "<p id='DownloadPackage'>";
			$this->PdfPanel->Controls[]= $hl;
			$this->PdfPanel->Controls[]= "</p>";
	}
}

?>