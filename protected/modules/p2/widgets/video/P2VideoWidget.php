<?php
/**
 * Class File
 *
 * @author Marc Mautz <m.mautz@herzogkommunikation.de>
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
 * @author Marc Mautz <m.mautz@herzogkommunikation.de>
 * @version $Id$
 * @package p2.cellmanager.widgets
 * @since 2.0
 */

Yii::setPathOfAlias("P2VideoWidget", dirname(__FILE__));

class P2VideoWidget extends P2BaseWidget {

    protected $_videoUrl;
    protected $modelName = 'P2VideoWidgetForm';

    public function getCreateView() {
        return 'P2VideoWidget.views._form';
    }
    public function getEditView() {
        return 'P2VideoWidget.views._form';
    }

    public function init(){
        if (isset($this->model->videoUrl)) {
            $this->_videoUrl = $this->model->videoUrl;
        } elseif ($this->model->mediaId) {
            $model = P2File::model()->findByPk($this->model->mediaId);
            $this->_videoUrl = Yii::app()->basePath.DS.$model->filePath;
        } else {
            throw new Exception("Neither videoUrl or model->mediaId specified!");
        }
    }

    public function run() {
        $this->widget(
            'p2.extensions.video.EVideoWidget',
            array(
                'width' => $this->model->width,
                'height' => $this->model->height,
                'videoUrl' => $this->_videoUrl,
                'videoControls' => $this->model->videoControls,
                'autoPlay' => $this->model->autoPlay,
            )
       );
    }
}


/**
 * FormModel for P2VideoWidgetForm
 *
 * see also {@link P2VideoWidgetForm}
 *
 * @author Marc Mautz <m.mautz@herzogkommunikation.de>
 * @version $Id: P2VideoWidget.php 401 2010-03-11 16:03:52Z marc $
 * @package p2.cellmanager.widgets
 * @since 2.0
 */
class P2VideoWidgetForm extends CFormModel {

    public $mediaId;
    public $videoUrl = "http://e1h13.simplecdn.net/flowplayer/flowplayer.flv";
    public $videoControls = "flowplayer.controls-3.1.5.swf";
    public $width = "320px";
    public $height = "192px";
    public $autoPlay = 0;

    public $safeAttributeNames = array();

    function rules() {

        return array(
            array('mediaId,videoUrl,videoControls,width,height,autoPlay', 'safe')
        );
    }
}

?>