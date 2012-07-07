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
 * @package pii.cells
 * @since 2.0
 */

Yii::import("application.lib.*");
//require_once 'Zend/Loader/Autoloader.php';
//spl_autoload_unregister(array('YiiBase','autoload'));
//spl_autoload_register(array('Zend_Loader_Autoloader','autoload'));
//spl_autoload_register(array('YiiBase','autoload'));
Yii::import("application.vendors.Zend.Amf.Server", true);
Yii::import("p2.components.P2ZendAmfServer", true);

class AmfController extends CController {

    public function actionIndex() {
        #require_once('Zend/Amf/Server.php');
        #require_once('MyService.php');
        $server = new Zend_Amf_Server();

        $server->setProduction(false);

        //adding our class to Zend AMF Server
        $server->setClass("P2ZendAmfServer");

        //Mapping the ActionScript VO to the PHP VO
        //you don't have to add the package name
        $server->setClassMap("VOMenuItem", "VOMenuItem");
        $server->setClassMap("VOSubMenuItem", "VOSubMenuItem");
        $server->setClassMap("VOProjectItem", "VOProjectItem");
        
        $handle = $server->handle();
        echo($handle);
    }
}
?>
