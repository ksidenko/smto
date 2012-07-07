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
 * Widget which renders p2's admin menu
 *
 * Detailed info
 * <pre>
 * $var = code_example();
 * </pre>
 * {@link DefaultController}
 *
 * @author Tobias Munk <schmunk@usrbin.de>
 * @version $Id: P2Menu.php 506 2010-03-24 00:32:15Z schmunk $
 * @package p2.widgets
 * @since 2.0
 */
class P2Menu extends CWidget {
    function run() {
        $file = Yii::getPathOfAlias('p2').DS.'assets'.DS.'p2Menu';
        $asset = Yii::app()->assetManager->publish($file);
        if (Yii::app()->user->checkAccess('editor')) { // FIXE: add to rbac p2 menu task...
            $this->render('p2Menu');
        }
    }
}
?>
