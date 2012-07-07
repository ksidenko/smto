<?php
/**
 * Class File
 *
 */

/**
 * Application component for image manipulation
 *
 * @version $Id: CImageComponent.php 401 2010-02-14 04:03:52Z schmunk $
 * @package extensions.image
 * @since 2.0
 */

include_once(dirname(__FILE__).DIRECTORY_SEPARATOR.'Image.php');
class CImageComponent extends CApplicationComponent
{
    /**
     * Drivers available:
     *  GD - The default driver, requires GD2 version >= 2.0.34 (Debian / Ubuntu users note: Some functions, eg. sharpen may not be available)
     *  ImageMagick - Windows users must specify a path to the binary. Unix versions will attempt to auto-locate.
     * @var string
     */
    public $driver = 'GD';

    /**
     * ImageMagick driver params
     * @var array
     */
    public $params = array();

    public function init()
    {
        parent::init();
        if($this->driver != 'GD' && $this->driver != 'ImageMagick'){
            throw new CException('driver must be GD or ImageMagick');
        }
    }

    public function load($image)
    {
        $config = array(
            'driver'=>$this->driver,
            'params'=>$this->params,
        );

        return new Image($image, $config);
    }
}
?>
