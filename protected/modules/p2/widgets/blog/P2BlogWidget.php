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
 * Widget displaying blog posts.
 *
 * {@link P2Html}
 *
 * @author Tobias Munk <schmunk@usrbin.de>
 * @version $Id: P2BlogWidget.php 505 2010-03-24 00:19:06Z schmunk $
 * @package p2.cellmanager.widgets
 * @since 2.1
 */
Yii::import('p2.components.cellmanager.P2BaseWidget');
Yii::setPathOfAlias('p2BlogWidget', dirname(__FILE__));

class P2BlogWidget extends P2BaseWidget {

    const ID_KEY = 'postId';
    const NAME_KEY = 'postName';

    public $modelName = 'P2BlogWidgetForm';

    public $detailView = 'detail';
    public $listView = 'list';

    public function getCreateView() {
        return 'p2BlogWidget.views._form';
    }

    public function getEditView() {
        return 'p2BlogWidget.views._form';
    }

    function run() {
        if (isset($_GET[P2BlogWidget::ID_KEY]) && is_numeric($_GET[P2BlogWidget::ID_KEY]) && $_GET[P2BlogWidget::ID_KEY] != 0) {
            $model = P2Html::model()->checkAccess()->active()->findByPk($_GET[P2BlogWidget::ID_KEY]);
            if ($model == null) throw new CHttpException(404, 'Blog posting not available');
            $this->controller->pageTitle = $model->getHtml("//h1")." - ".$this->controller->pageTitle;
            $this->render(
                $this->detailView,
                array(
                'model' => $model,
            ));
        } else {
            $criteria = new CDbCriteria();
            $criteria->with = array('P2Info');
            $criteria->condition = "P2Info.type = '".$this->model->type."'";
            $criteria->order = "begin DESC";

            $htmlModel = P2Html::model()->active()->checkAccess();
            if ($this->model->ongoing == true) {
                $htmlModel = $htmlModel->ongoing();
            }
            if ($this->model->localized == true) {
                $htmlModel = $htmlModel->localizedStrict();
            } else {
                $htmlModel = $htmlModel->localized();            
            }
            $_model = clone($htmlModel);
            $all = $htmlModel->findAll($criteria);

            $pages=new CPagination(count($all));
            $pages->pageSize=$this->model->pageSize;
            $pages->applyLimit($criteria);

            $models = $_model->findAll($criteria);

            Yii::trace("Rendering ".$this->listView." ...", "p2.widgets.blog");
            $this->render(
                $this->listView,
                array(
                'models' => $models,
                'pages' => $pages
            ));
        }
    }

    public function extractHeadline($model){
        return $model->getHtml("//".$this->model->headlineTag."[1]");
    }

    public function extractContent($model){
        return trim(preg_replace("/<".$this->model->headlineTag.">.*<\/".$this->model->headlineTag.">/ms","",$model->getHtml()));
        #return $model->getHtml('//html/*[not(self::'.$this->model->headlineTag.')]',true);
    }

    public function extractShortContent($model){
        return preg_replace("/".$this->model->moreMarker."(.*)/ms",$this->activeLinkDetail($model, $this->model->detailUrlText),$this->extractContent($model));
    }


    protected function activeLinkList($model, $label = null) {
        if ($label == null) {
            $label = $this->model->listUrlText;
        }
        $urlParams[0] = "";
        $urlParams = CMap::mergeArray($urlParams, $_GET);
        $urlParams = CMap::mergeArray($urlParams, array(
            P2BlogWidget::ID_KEY=>0,
            P2BlogWidget::NAME_KEY=>0,
            #'#'=>'post'.$model->id
            )
          );
        return CHtml::link( 
            $label,
            $urlParams
            #array('name'=>'post'.$model->id)
        );
    }

    protected function activeLinkDetail($model, $label = "Read more...") {
        if ($label == null) {
            $label = $this->model->detailUrlText;
        }
        $urlParams = $this->createDetailUrl($model);
        return CHtml::link(
            $label,
            $urlParams,
            array('class'=>'blogPostingLink',
        ));
    }

    protected function createDetailUrl($model, $absolute = false) {

        $urlParams = CJson::decode($this->model->detailUrl);

        if (isset($urlParams['route'])) {
            $route = $urlParams['route'];
            unset($urlParams['route']);
        } else {
            $urlParams = CMap::mergeArray($urlParams, $_GET);
            $route = "";
        }

        $urlParams = CMap::mergeArray($urlParams, array(
            P2BlogWidget::ID_KEY=>$model->id,
            P2BlogWidget::NAME_KEY=>$model->name,
            ));
        
        if ($absolute == true) {
            return $this->controller->createAbsoluteUrl($route, $urlParams);
        } else {
            return $this->controller->createUrl($route, $urlParams);
        }
    }
}


/**
 * FormModel for P2BlogWidget
 *
 * see also {@link P2BlogWidget}
 *
 * @author Tobias Munk <schmunk@usrbin.de>
 * @version $Id: P2BlogWidget.php 505 2010-03-24 00:19:06Z schmunk $
 * @package p2.cellmanager.widgets
 * @since 2.1
 */
class P2BlogWidgetForm extends CFormModel {

    public $pageSize = 5;
    public $type = "blog";

    public $moreMarker = "<!--READMORE-->";

    public $title = "Blog";

    public $detailUrl = "";
    public $detailUrlText = "Read more ...";
    public $listUrl = "";
    public $listUrlText = "Overview";

    public $headlineTag = "h3";

    public $showFullEntries = false;

    public $displayPager = true;
    public $ongoing = false;
    public $localized = true;

    function rules() {
        return array(
            array('title, linkSelector, moreMarker', 'safe'),
            array('listUrl, listUrlText', 'safe'),
            array('detailUrl, detailUrlText, headlineTag', 'safe'),
            array('type', 'required'),
            array('localized, ongoing, displayPager, showFullEntries', 'boolean'),
            array('pageSize', 'numerical',  'integerOnly' => true)
        );
    }
}

?>
