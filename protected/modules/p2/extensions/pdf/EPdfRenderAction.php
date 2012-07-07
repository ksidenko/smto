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
 * Action for rendering PDF files
 *
 * Specify additional $_GET parameter (download|link|preview|publish)
 * Default action is 'publish'
 *
 * @author Tobias Munk <schmunk@usrbin.de>
 * @version $Id: P2CreateInputAction.php 337 2010-01-25 15:32:19Z schmunk $
 * @package extensions.pdf
 * @since 2.0
 */

Yii::setPathOfAlias('EPdfRenderAction', dirname(__FILE__));
Yii::import('EPdfRenderAction.*');

class EPdfRenderAction extends CAction {

    public $templateView = null;
    public $templateData = array();

    public $iMagickPath;
    public $pdfOutputPath;
    public $pdfPublishPath;
    
    public $pdfPublishUrl;

    public function run() {
        #umask(0);
        // get XML
        $xml = $this->controller->renderPartial($this->templateView, $this->templateData, true);
        
        // create manager
        $manager = new EPdfManager();
        #file_put_contents(Yii::app()->basePath.DS.Yii::app()->params['publicRuntimePath'].DS."log.log", $xml);exit;
        $manager->setXml($xml);
        $manager->pdfOutputPath = $this->pdfOutputPath;
        $manager->pdfPublishPath = $this->pdfPublishPath;
        $manager->init();
        $manager->renderJob();

        // do specified action
        if (isset($_GET['download'])) {
            $manager->sendPdf(0,true);
        } elseif (isset($_GET['link'])) {
            $path = $manager->publishJob();
            $manager->deleteJob();
            $link = str_replace($this->pdfPublishPath, $this->pdfPublishUrl, $path.'/'.$manager->Pdf->Files[0]->Name);
            echo CHtml::link('Download PDF', $link);
        } elseif (isset($_GET['preview'])) {
            echo "Job folder ".$manager->Pdf->OutputPath.DS.$manager->Pdf->JobId;
            $this->controller->widget('EPdfPreview',array('pdfFile'=>$manager->Pdf->Files[0]->FilePath));
        } else {
            $manager->publishJob();
            $manager->deleteJob();
            echo "Job #{$manager->getPdf()->JobId} published to '{$this->pdfPublishPath}'.";
        }
    }
}

?>
