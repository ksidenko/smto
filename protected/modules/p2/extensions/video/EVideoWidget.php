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
 * @package extensions.video
 * @since 2.0
 */

Yii::setPathOfAlias("EVideoWidget", dirname(__FILE__));

class EVideoWidget extends CWidget {

    #http://e1h13.simplecdn.net/flowplayer/flowplayer.flv
    #http://onair.adobe.com.edgesuite.net/onair/onair_dannydura_airapis.flv
    public $videoUrl = "http://e1h13.simplecdn.net/flowplayer/flowplayer.flv";
    public $videoControls = "flowplayer.controls-3.1.5.swf";
    public $width = "320px";
    public $height = "192px";
    public $autoPlay = 0;

    protected $_uniqid;

    public function init() {
        $this->_uniqid = uniqid("EVideoWidget");
    }

    public function run() {
        $flowplayerSwf = Yii::app()->assetManager->publish(dirname(__FILE__).DS."support")."/flowplayer-3.1.5.swf";
        $flowplayerControlsSwf = Yii::app()->assetManager->publish(dirname(__FILE__).DS."support/");

        $script = dirname(__FILE__).DS."/flowplayer-3.1.4.min.js";
        $url = Yii::app()->assetManager->publish($script);
        Yii::app()->clientScript->registerScriptFile($url);

        $this->render(
            'video',
            array(
                'flowplayerSwf' => $flowplayerSwf,
                'width' => $this->width,
                'height' => $this->height,
                'videoUrl' => $this->videoUrl,
                'videoControls' => $this->videoControls,
                'autoPlay' => $this->autoPlay,
            )
       );
    }
}