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
 * Widget for managing and displaying P2Html content
 *
 * Detailed info
 * <pre>
 * $var = code_example();
 * </pre>
 * {@link DefaultController}
 *
 * @author Tobias Munk <schmunk@usrbin.de>
 * @version $Id: P2HtmlWidget.php 505 2010-03-24 00:19:06Z schmunk $
 * @package p2.cellmanager.widgets
 * @since 2.0
 */

Yii::import("application.modules.p2.components.cellmanager.*");
Yii::import("application.modules.p2.models.*");
Yii::import("application.modules.p2.extensions.editor.*");

class P2HtmlWidget extends P2BaseWidget {

    public $wrapperPre = '';
    public $wrapperPost = '';

    protected $modelName = 'P2Html';
    protected $data;

    public function getCreateView() {
        return "p2.widgets.html.views._form";
    }

    public function getCreateData() {
        $model = new P2Html;
        return array(
            'select' => P2Html::model()->findAll(),
            'update' => false
        );
    }

    public function getAdminParams() {
        $return = new CMap;
        $return['route'] = '/p2/p2Html';
        $return['dataKey'] = "id";
        return $return;
    }

    public function getHasData() {
        return ($this->data)?true:false;
    }

    public function getHeadline() {
        return $this->data->name." (HTML)";
    }

    public function init() {
        if ($this->model->id === null) {
            throw new CException('No id specified for P2HtmlWidget!');
        }
        else {
            $this->data = P2Html::model()->findByPK($this->model->id);
            if (!$this->data instanceof P2Html) {
                $msg = Yii::t('P2Module.p2', "P2Html #{id} not found!", array('{id}'=>$this->id));
                throw new CException($msg);
            } else {
            }
        }
    }

    public function run() {
        echo $this->wrapperPre.$this->data->html.$this->wrapperPost;
    }

}
?>
