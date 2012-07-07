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
 * Widget base class, implements ICellManagerWidget
 *
 * Detailed info
 * <pre>
 * $var = code_example();
 * </pre>
 * {@link DefaultController}
 *
 * @author Tobias Munk <schmunk@usrbin.de>
 * @version $Id: P2BaseWidget.php 506 2010-03-24 00:32:15Z schmunk $
 * @package p2.cellmanager
 * @since 2.0
 */
Yii::import("application.modules.p2.interfaces", true);

class P2BaseWidget extends CWidget implements ICellManagerWidget {

    public      $model = null;
    protected   $modelName = null;

    public function  __construct($owner=null) {
        parent::__construct($owner=null);
        if ($this->modelName)
            $this->model = Yii::createComponent(array('class'=>$this->modelName));
        else
            throw new CHttpException("Property 'modelName' not set!");
    }

    public function getCreateView() {
        throw new CHttpException('Create view method not implemented!');
    }

    public function getCreateData() {
        #throw new CHttpException('Method not implemented! Override this method and return an data array.');
        return array();
    }

    public function getUpdateView() {
        throw new CHttpException('Update view method not implemented!');
    }

    public function getUpdateData() {
        throw new CHttpException('Update data method not implemented!');
    }

    public function getHasData() {
        return false;
    }

    public function getAdminParams() {
        return false;
    }

    public function getHeadline() {
        return get_class($this);
    }
}
?>
