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
 * Action for rendering SWF files from a PDF file
 *
 * Detailed info
 * <pre>
 * $var = code_example();
 * </pre>
 * {@link DefaultController}
 *
 * @author Tobias Munk <schmunk@usrbin.de>
 * @version $Id: P2CreateInputAction.php 337 2010-01-25 15:32:19Z schmunk $
 * @package extensions.pdf
 * @since 2.0
 */

Yii::setPathOfAlias('ESwfRenderAction', dirname(__FILE__));
Yii::import('ESwfRenderAction.*');

class ESwfRenderAction extends CAction {

    public $pdfFile;
    public $swfPublishPath;
    public $swfPublishUrl;
    public $swftoolsPath;

    public function run() {
        Yii::trace("SWF render action with ".$this->pdfFile,'extensions.pdf');

        umask(0);
        $manager = new EPdfManager();
        $manager->swfPublishPath = $this->swfPublishPath;
        $manager->swftoolsPath = $this->swftoolsPath;
        $manager->swfPublishUrl = $this->swfPublishUrl;
        $manager->init();

        if (count(CFileHelper::findFiles($manager->swfPublishPath, array('fileTypes'=>array('swf')))) == 0)
            $manager->renderSwf($this->pdfFile);
        echo $manager->getSwfData($manager->swfPublishPath);
        exit; // no log output
    }
}

?>
