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
 * @package p2.widgets
 * @since 2.0
 */

Yii::setPathOfAlias("P2PdfPreview", dirname(__FILE__));

class P2PdfPreviewWidget extends P2BaseWidget {
    
    protected $_pdfFile;
    protected $_pdfFileBase64;
    protected $_pdfDownloadUrl;
    protected $modelName = 'P2PdfPreviewForm';
    
    public function getCreateView() {
        return 'P2PdfPreview.views._form';
    }
    public function getEditView() {
        return 'P2PdfPreview.views._form';
    }

    public function init(){
        if ($this->model->mediaId) {
            $model = P2File::model()->findByPk($this->model->mediaId);
            $this->_pdfFile = Yii::app()->basePath.DS.$model->filePath;
            $this->_pdfFileBase64 = base64_encode($this->_pdfFile);
            $this->_pdfDownloadUrl = Yii::app()->createUrl("/p2/p2File/image", array("preset"=>"originalFile","id"=>$this->model->mediaId));
        } else {
            throw new Exception("No mediaId specified!");
        }
        
    }

    public function run() {

        $url = Yii::app()->controller->createUrl(
            '/p2/ajax/renderSwf',
            array('pdfFile'=>$this->_pdfFileBase64)
        );

        $this->widget(
            'p2.extensions.pdf.EPdfPreview',
            array(
                'mediaId' => $this->model->mediaId,
                'width' => $this->model->width,
                'height' => $this->model->height,
                'allowTogglePageLayout' => $this->model->allowTogglePageLayout,
                'allowFullScreen' => $this->model->allowFullScreen,
                'allowZoom' => $this->model->allowZoom,
                'zoomFactor' => $this->model->zoomFactor,
                'themeColor' => $this->model->themeColor,
                'pdfFile' => $this->_pdfFile
                #'swfUrl' => $url,
                #'downloadFile'
            )
       );

       echo '<div>
        <a href="<?php echo $file;?>" class="downloadLink">Download PDF</a>
    </div>';

    }
}

class P2PdfPreviewForm extends CFormModel {

    public $mediaId;
    public $width = "100%";
    public $height = "600px";
    public $allowTogglePageLayout = 1;
    public $allowFullScreen = 1;
    public $allowZoom = 1;
    public $zoomFactor = "3";
    public $themeColor = "#278aca";
    public $swfUrl = "/p2/ajax/renderSwf";

    function rules() {

        return array(
            array('mediaId,pdfFile,width,height,allowTogglePageLayout,allowFullScreen,allowZoom,zoomFactor,themeColor,swfUrl', 'safe')
        );

    }
}
?>
