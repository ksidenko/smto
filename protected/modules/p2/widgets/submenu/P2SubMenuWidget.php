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
 * Widget displaying a submenu.
 *
 * {@link P2Page}
 *
 * @author Tobias Munk <schmunk@usrbin.de>
 * @version $Id: P2BlogWidget.php 371 2010-02-04 01:51:13Z schmunk $
 * @package p2.cellmanager.widgets
 * @since 2.1
 */
class P2SubMenuWidget extends P2BaseWidget {

    public $modelName = 'P2SubMenuWidgetForm';

    function getCreateView() {
        return 'p2.widgets.submenu.views._form';
    }

    function getCreateData() {
        return array('startNode' => $this->model->startNode);
    }

    function run() {
        $model = P2Page::model()->findByPk($this->model->startNode);
        if ($model !== null) {
            echo "<h2>".CHtml::link(
            ($this->model->headline)?$this->model->headline:P2Page::model()->findByPk($this->model->startNode)->descriptiveName,
            $model->getUrlString())."</h2>";
            echo P2Page::renderTree($this->model->startNode,1,'default','normal',array('<ul>','<li>','</li>','</ul>'),'merida');
        } else {
            echo "<div class='errorSummary'>Page #".$this->model->startNode." is invalid!</div>";
        }
    }
}

/**
 * FormModel for P2SubMenuWidget
 *
 * see also {@link P2SubMenu}
 *
 * @author Tobias Munk <schmunk@usrbin.de>
 * @version $Id: P2BlogWidget.php 371 2010-02-04 01:51:13Z schmunk $
 * @package p2.cellmanager.widgets
 * @since 2.1
 */
class P2SubMenuWidgetForm extends CFormModel {

    public $startNode;
    public $headline = null;

    function rules() {
        return array(
            array('startNode', 'numerical'),
            array('headline', 'safe'),
        );
    }
}
?>
