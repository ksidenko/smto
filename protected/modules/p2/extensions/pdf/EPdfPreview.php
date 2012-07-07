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
 * Description ...
 *
 * Detailed info
 * <pre>
 * $var = code_example();
 * </pre>
 * {@link DefaultController}
 *
 * @author Tobias Munk <schmunk@usrbin.de>
 * @version $Id$
 * @package extensions.pdf
 * @since 2.0
 */

Yii::setPathOfAlias('EPdfPreview', dirname(__FILE__));
Yii::import('EPdfPreview.*');

class EPdfPreview extends CWidget {

    public $mediaId;
    public $pdfFile;
    public $width = "100%";
    public $height = "600px";
    public $allowTogglePageLayout = 1;
    public $allowFullScreen = 1;
    public $allowZoom = 1;
    public $zoomFactor = "3";
    public $themeColor = "#278aca";
    public $renderUrl = null;
    public $renderRoute = 'p2/ajax/renderSwf';
    public $swfView = 'EPdfPreview.views.preview';    
    

    public function run() {
        
        if ($this->renderUrl === null) {
            $renderUrl = Yii::app()->controller->createAbsoluteUrl(
                $this->renderRoute,
                array('pdfFile'=>base64_encode($this->pdfFile))
            );
        } else {
            $renderUrl = $this->renderUrl;
        }

        $this->controller->renderPartial(
            $this->swfView,
            array(
                'mediaId' => $this->mediaId,
                'width' => $this->width,
                'height' => $this->height,
                'allowTogglePageLayout' => $this->allowTogglePageLayout,
                'allowFullScreen' => $this->allowFullScreen,
                'allowZoom' => $this->allowZoom,
                'zoomFactor' => $this->zoomFactor,
                'themeColor' => $this->themeColor,
                'swfUrl' => $renderUrl,
            ),
            false,
            true
       );
    }
}

?>