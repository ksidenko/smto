<?php
/**
 * Class File
 *
 * @author Tobias Munk <schmunk@usrbin.de>
 * @link http://www.phundament.com/
 * @copyright Copyright &copy; 2005-2010 diemeisterei GmbH
 * @license http://www.phundament.com/license/
 */

/**
 * Component, manages rendering of PDF files with lithron
 *
 * Dependecies
 * {@link http://www.lithron.de}
 * {@link http://www.swftools.org}
 *
 * @author Tobias Munk <schmunk@usrbin.de>
 * @version $Id$
 * @package extensions.pdf
 * @since 2.0
 */
Yii::setPathOfAlias('EPdfManager', dirname(__FILE__));

class EPdfManager extends CComponent {

    /**
     * @var string Path to swftools executables
     */
    public $swftoolsPath    = "/usr/bin";
    /**
     * @var string Path to ImageMagick executables
     * Only for lithron in low-res mode needed.
     */
    public $iMagickPath     = "/usr/bin";

    /**
     * @var string Lithron's job folder
     */
    public $pdfOutputPath   = null;

    public $pdfPublishPath  = null;
    public $swfPublishPath   = null;
    public $swfPublishUrl   = null;
    public $licenseFile     = null;

    public $LogLevel        = 6;

    private $_xml             = null;
    private $_pdf             = null;
    private $_swf             = null;

    public function init() {
        if ($this->pdfOutputPath !== null && !is_dir($this->pdfOutputPath)) {
            mkdir($this->pdfOutputPath, 0777, true);
        }
        if ($this->pdfPublishPath !== null &&!is_dir($this->pdfPublishPath)) {
            mkdir($this->pdfPublishPath, 0777, true);
        }
        if ($this->swfPublishPath !== null && !is_dir($this->swfPublishPath)) {
            mkdir($this->swfPublishPath, 0777, true);
        }
    }

    /**
     * @return Lithron Lithron render object 
     */
    public function getPdf(){
        return $this->_pdf;
    }
    /**
     * @return string XML Template 
     */
    public function getXml(){
        return $this->_xml;
    }
    /**
     * @param string $xml XML Template
     */
    public function setXml($xml){
        if ($this->_xml != null) throw new Exception("XML String can be only set once!");
        $this->_xml = $xml;
    }

    /**
     * Renders the XML {@link setXml} template
     */
    public function renderJob() {
        $errorLevel = error_reporting(E_ALL);
        // invoke lithron rendering process
        $this->_pdf = new Lithron();
        $this->_pdf->LicenseFile = $this->licenseFile;
        $this->_pdf->setOutputPath($this->pdfOutputPath);
        $this->_pdf->setIMagickPath($this->iMagickPath);
        $this->_pdf->setLogLevel($this->LogLevel);
        $this->_pdf->setXMLString($this->_xml);
        $this->_pdf->init();
        $this->_pdf->work();
        Yii::log("Created ".count($this->_pdf->Files)." PDF file(s) in ".$this->pdfOutputPath, CLogger::LEVEL_INFO, 'extensions.pdf');
        error_reporting($errorLevel);
    }

    /**
     * Sends PDF file from job to the client
     * 
     * @param integer $fileNum Number of file in job starting with zero (0)
     * @param boolean $delete Delete job after sending the file, defaults to false
     */
    public function sendPdf($fileNum = 0, $delete = false) {
        header('Content-Description: File Transfer');
        header('Content-Type: application/pdf');
        header('Content-Disposition: attachment; filename="'.$this->_pdf->Files[$fileNum]->Name.'"');
        header('Content-Transfer-Encoding: binary');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Expires: 0');
        header('Pragma: public');
        header('Content-Length: ' . filesize($this->_pdf->Files[$fileNum]->FilePath));
        flush();
        readfile($this->_pdf->Files[$fileNum]->FilePath);
        if ($delete == true) $this->deleteJob();
        exit; // Prevent debug output
    }

    /**
     * Copies job to {@link pdfPublishPath}
     * @return <type>
     */
    public function publishJob() {
        Yii::trace("Publishing job ...", 'extensions.pdf');
#echo $this->_pdf->OutputPath.DS.$this->_pdf->JobId."xxxx".$this->pdfPublishPath;exit;
        CFileHelper::copyDirectory($this->_pdf->OutputPath.DS.$this->_pdf->JobId, $this->pdfPublishPath, array('fileTypes'=>array('pdf')));
        return $this->pdfPublishPath;
    }

    /**
     * Deletes current job
     */
    public function deleteJob() {
        $dir = $this->_pdf->OutputPath.DS.$this->_pdf->JobId;
        foreach(CFileHelper::findFiles($dir) AS $file) {
            unlink($file);
        }
        rmdir($dir);
    }

    /**
     * Creates a SWF file for every page in the PDF
     * 
     * @param string $pdfFile Path to PDF file
     * @return array Rendered SWF files 
     */
    public function renderSwf($pdfFile) {
        Yii::trace("Using pdf2swf from ".$this->swftoolsPath, 'extensions.pdf');

        $dirName = dirname($pdfFile);
        $files = array();
        $outFile = str_replace($dirName, $this->swfPublishPath, $pdfFile);

        $cmd = $this->swftoolsPath.'/pdf2swf -o"'.$outFile.'.%.swf" "'.$pdfFile.'"';
        Yii::trace($this->swftoolsPath.'/pdf2swf -o"'.$outFile.'.%.swf" "'.$pdfFile.'"', 'extensions.pdf');

        $result = exec($cmd);
        Yii::trace($result,'extensions.pdf');

        $dir = scandir($this->swfPublishPath);
        Yii::log("Created ".count($dir)." SWF file(s) in ".$this->swfPublishPath, CLogger::LEVEL_INFO, 'extensions.pdf');

        $i = 1;
        foreach($dir AS $file) {
            if(substr($file,-4,4) == '.swf') {
                preg_match("/pdf.([0-9]{1,}).swf/", $file, $matches);
                $files[$matches[1]] = $this->swfPublishPath.DS.$file;
                $i++;
            }
        }
        if (count($files) == 0) {
            throw new Exception('Unable to create SWF previews with '.$cmd);
        } else {
            ksort($files, SORT_NUMERIC);
            return $files;
        }
    }

    public function getSwfData($swfDir) {
        Yii::trace("Collecting SWF data ...", 'extensions.pdf');

        $files = CFileHelper::findFiles($swfDir, array('type'=>'swf'));
        // create data structure for main.swf
        $data = new StdClass;
        foreach($files AS $i => $file) {
            $swf = new StdClass;
            /*$swf->id = $i;
            $swf->src = str_replace($this->swfPublishPath, $this->swfPublishUrl, $file);
            $data->list[] = $swf;*/

            $swf->src = str_replace($this->swfPublishPath, $this->swfPublishUrl, $file);
            preg_match("/pdf.([0-9]{1,}).swf/", $file, $matches);
            $swf->id = $matches[1];
            $_list[(int)$matches[1]] = $swf;
        }

        ksort($_list);
        foreach ($_list AS $key => $item){
            $data->list[] = $item;
        }

        #echo $this->swfPublishPath;
        #echo $this->swfPublishUrl;
        #var_dump($files);exit;
        echo CJSON::encode($data);

    }
}
?>
