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
 * Widget for testing and debugging
 *
 * @author Tobias Munk <schmunk@usrbin.de>
 * @version $Id: P2SkeletonWidget.php 505 2010-03-24 00:19:06Z schmunk $
 * @package p2.cellmanager.widgets
 * @since 2.0
 */
Yii::import("p2.components.cellmanager.*");
Yii::setPathOfAlias('p2SkeletonWidget', dirname(__FILE__));

class P2SkeletonWidget extends P2BaseWidget {
    protected $modelName = 'P2SkeletonWidgetForm';

    public function getCreateView() {
        return 'p2SkeletonWidget.views._form';
    }
    public function getEditView() {
        return 'p2SkeletonWidget.views._form';
    }

    public function run() {
        echo "<div class='p2widget'>";
        echo "<h1>".$this->id."</h1>";
        echo "<h2>".$this->actionPrefix."</h2>";
        echo "<p>".$this->model->bar."</p>";
        echo "<p>".$this->model->foo."</p>";
        echo "</div>";
    }

}


/**
 * FormModel for P2SkeletonWidget
 *
 * see also {@link P2SkeletonWidget}
 *
 * @author Tobias Munk <schmunk@usrbin.de>
 * @version $Id: P2SkeletonWidget.php 505 2010-03-24 00:19:06Z schmunk $
 * @package p2.cellmanager.widgets
 * @since 2.0
 */
class P2SkeletonWidgetForm extends CFormModel {

    public $foo;
    public $bar;

    public $safeAttributeNames = array();

    function rules() {
        return array(
            array('bar', 'numerical'),
            array('foo', 'length', 'min' => 10)
        );
    }
}
?>
