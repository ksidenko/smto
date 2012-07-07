<?php

class LithronHelper
{
    function getpdfsize($file)
    {
        #return;
        
        $lithron = new Lithron();
        $pdfLib = $lithron->DummyPDF;

        $pdf_handle = $pdfLib->open_pdi($file, '', 0);
        $img_handle = $pdfLib->open_pdi_page($pdf_handle, 1, '');
        $width = $pdfLib->get_pdi_value("width", $pdf_handle, $img_handle, 0);
        $height = $pdfLib->get_pdi_value("height", $pdf_handle, $img_handle, 0);
        return array('width'=>$width, 'height'=>$height);
    }

}

?>