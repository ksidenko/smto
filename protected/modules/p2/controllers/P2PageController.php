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
 * Controller, handles page actions
 *
 * Detailed info
 * <pre>
 * $var = code_example();
 * </pre>
 * {@link DefaultController}
 *
 * @author Tobias Munk <schmunk@usrbin.de>
 * @version $Id: P2PageController.php 527 2010-03-28 19:32:03Z schmunk $
 * @package p2.controllers
 * @since 2.0
 */
class P2PageController extends CController {
    const PAGE_SIZE=30;

    public $menu;
    public $breadcrumbs;

    /**
     * @var string specifies the default action to be 'list'.
     */
    public $defaultAction='list';

    /**
     * @var CActiveRecord the currently loaded data model instance.
     */
    private $_model;

    /**
     * @return array action filters
     */
    public function filters() {
        return array(
        'accessControl', // perform access control for CRUD operations
        );
    }

    /**
     * Specifies the access control rules.
     * This method is used by the 'accessControl' filter.
     * @return array access control rules
     */
    public function accessRules() {
        return array(
        array('allow',
        'actions'=>array('view'),
        'users'=>array('*'),
        ),
        array('allow', // allow admin user to perform 'admin' and 'delete' actions
        'actions'=>array('copy','list','show','create','create','update','admin','delete','sitemap'),
        'roles'=>array('editor','admin'),
        ),
        array('deny',  // deny all users
        'users'=>array('*'),
        ),
        );
    }

    /**
     * Shows a view from database.
     */
    public function actionView() {

        $model = null;
        $id = (isset($_GET[P2Page::PAGE_ID_KEY]) && is_numeric($_GET[P2Page::PAGE_ID_KEY]))?$_GET[P2Page::PAGE_ID_KEY]:null; 
        $name = isset($_GET[P2Page::PAGE_NAME_KEY])?$_GET[P2Page::PAGE_NAME_KEY]:null; 
        
        if ($id) {
            $model = P2Page::model()->default()->findByPk($id);
        } elseif ($name) {
            $model = P2Page::model()->default()->findByAttributes(array('name' => $name));
            // redirect for consistency reasons
            if ($model !== null) {
                Yii::app()->request->redirect($this->createUrl('/p2/p2Page/view',array_merge($_GET,array(P2Page::PAGE_ID_KEY=>$model->id,P2Page::PAGE_NAME_KEY=>$model->name))));
            }
        } else {
            throw new CHttpException(404, 'Id/name not found!');        
        }

        if ($model == null && $id) {
            // look for the reason ...
            // is active and localized, but access is not granted
            $model = P2Page::model()->active()->localized()->findByPk($id);
            if ($model !== null && Yii::app()->user->isGuest) {
                Yii::app()->user->loginRequired();
            } elseif ($model !== null && !Yii::app()->user->isGuest) {
                throw new CHttpException(404, 'You are not authorized to view this page!');
            }
            // is active and accessable, but not localized
            $model = P2Page::model()->active()->checkAccess()->findByPk($id);
            if ($model !== null) {
                throw new CHttpException(404, 'Page not available in this language!');
            }
            
        } elseif ($model instanceof P2Page) {
            // record found in db
            if ($model->url) {
                Yii::app()->controller->redirect($model->getUrlString());
            }
            if (!$model->view || !$model->layout) {
                throw new CHttpException(500, 'No view file in database!');
            }
            $this->pageTitle = $model->descriptiveName;
            $this->layout = $model->layout;
            $this->render($model->view, array('model' => $model));
            return;
        }
        throw new CHttpException(404, 'Id/name not found in database!');
    }

    /**
     * Shows a particular model.
     */
    public function actionShow() {
        $this->render('show',array('model'=>$this->loadP2Page()));
    }

    /**
     * Shows a particular model.
     */
    public function actionCopy() {
        $data['step'] = 0;

        if(isset($_POST['copyNow']) && $_POST['copyNow'] == 1) {

            $from = $_POST['from'];
            $to = $_POST['to'];
            $data['page'] = P2Page::model()->findByPk($from);
            $attributes = array(
                'controllerId'=>'p2Page','actionName'=>'view','requestParam'=>$from);
            $data['cells'] = P2Cell::model()->with('P2Info')->findAllByAttributes($attributes,'P2Info.language = "'.$data['page']->P2Info->language.'"');

            $page = new P2Page;
            $page->attributes = $data['page']->attributes;
            $page->p2_infoId = null;
            $page->parentId = $to;
            $page->name = Yii::app()->language.'-'.$page->name;
            $page->save();

            $data['result'] = "";
            $data['result'] = CHtml::link('Go to page',array('/p2/p2Page/view', P2Page::PAGE_ID_KEY => $page->id));
            $data['result'] .= "<div class='successSummary'>Page copied to #{$page->id}</div>";

            #$data['page']->isNewRecord = true;
            #$data['page']->P2Info->language = Yii::app()->language;
            #$data['page']->save();

            foreach($data['cells'] AS $dataCell){
                // FIXME: check widget as instance ...
                $newClassProps = $dataCell->classProps;
                if ($dataCell->classPath == 'p2.widgets.html.P2HtmlWidget') {
                    $classProps = CJSON::decode($dataCell->classProps);
                    $widgetData = P2Html::model()->findByPk($classProps['id']);
                    
                    if ($widgetData == null){
                        $newClassProps = null;
                        $data['result'] .= "<div class='errorSummary'>Error while duplicating HTML #{$classProps['id']}</div>";
                    } else {
                    
                    try {
                    $html = new P2Html;
                    $html->attributes = $widgetData->attributes;
                    $html->name = "COPY-".Yii::app()->language."-".$html->name.
                    $html->p2_infoId = null;
                    $html->save();
                    
                    $data['result'] .= "<div class='successSummary'>HTML copied to #{$html->id}</div>";
                    } catch (Exception $e) {
                        $data['result'] .= "<div class='errorSummary'>Error! HTML not copied.</div>";                
                    }
                    $newClassProps = CJSON::encode(array('id'=>$html->id));
                    }
                } else {
                    $newClassProps = $dataCell->classProps;
                }

                $cell = new P2Cell;
                $cell->attributes = $dataCell->attributes;
                $cell->classProps = $newClassProps;
                $cell->requestParam = $page->id;
                $cell->p2_infoId = null;
                $cell->save();

                $data['result'] .= "<div class='successSummary'>Cell copied to #{$cell->id}</div>";
            }

            $data['step'] = 2;
            $this->render('copy', $data);

        } elseif(isset($_POST['from']) && $_POST['from'] != 1 && isset($_POST['to'])) {
            $from = $_POST['from'];
            $to = $_POST['to'];
            $data['page'] = P2Page::model()->findByPk($from);
            $attributes = array('controllerId'=>'p2Page','actionName'=>'view','requestParam'=>$from);
            $data['cells'] = P2Cell::model()->with('P2Info')->findAllByAttributes($attributes,'P2Info.language = "'.$data['page']->P2Info->language.'"');
            $data['step'] = 1;
            $this->render('copy', $data);
        } else {
            $this->render('copy', $data);
        }
    }


    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'show' page.
     */
    public function actionCreate() {
        $model=new P2Page;
        if(isset($_POST['P2Page'])) {
            $model->attributes=$_POST['P2Page'];
            if($model->save())
                $this->redirect(array('show','id'=>$model->id));
        }
        $this->render('create',array('model'=>$model));
    }

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'show' page.
     */
    public function actionUpdate() {
        $model=$this->loadP2Page();
        if(isset($_POST['P2Page'])) {
            $model->attributes=$_POST['P2Page'];
            if($model->save())
                $this->redirect(array('show','id'=>$model->id));
        }
        $this->render('update',array('model'=>$model));
    }

    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'list' page.
     */
    public function actionDelete() {
        if(Yii::app()->request->isPostRequest) {
        // we only allow deletion via POST request
            $this->loadP2Page()->delete();
            $this->redirect(array('list'));
        }
        else
            throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
    }

    /**
     * Lists all models.
     */
    public function actionList() {
        $criteria=new CDbCriteria;

        $pages=new CPagination(P2Page::model()->count($criteria));
        $pages->pageSize=self::PAGE_SIZE;
        $pages->applyLimit($criteria);

        $models=P2Page::model()->findAll($criteria);

        $this->render('list',array(
            'models'=>$models,
            'pages'=>$pages,
        ));
    }

    /**
     * Manages all models.
     */
    public function actionAdmin() {
        $this->processAdminCommand();

        $criteria=new CDbCriteria;

        $pages=new CPagination(P2Page::model()->localized()->count($criteria));
        $pages->pageSize=self::PAGE_SIZE;
        $pages->applyLimit($criteria);

        $sort=new CSort('P2Page');
        $sort->applyOrder($criteria);

        $models=P2Page::model()->localized()->findAll($criteria);

        $this->render('admin',array(
            'models'=>$models,
            'pages'=>$pages,
            'sort'=>$sort,
        ));
    }

        /**
     * Manages all models.
     */
    public function actionSitemap() {
        $this->processAdminCommand();

        $criteria=new CDbCriteria;

        $pages=new CPagination(P2Page::model()->count($criteria));
        $pages->pageSize=self::PAGE_SIZE;
        $pages->applyLimit($criteria);

        $sort=new CSort('P2Page');
        $sort->applyOrder($criteria);

        #$models=P2Page::model()->findAll($criteria);

        $this->render('sitemap',array(
            #'models'=>$models,
            #'pages'=>$pages,
            #'sort'=>$sort,
        ));
    }


    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer the primary key value. Defaults to null, meaning using the 'id' GET variable
     */
    public function loadP2Page($id=null) {
        if($this->_model===null) {
            if($id!==null || isset($_GET['id']))
                $this->_model=P2Page::model()->findbyPk($id!==null ? $id : $_GET['id']);
            if($this->_model===null)
                throw new CHttpException(404,'The requested page does not exist.');
        }
        return $this->_model;
    }

    /**
     * Executes any command triggered on the admin page.
     */
    protected function processAdminCommand() {
        if(isset($_REQUEST['id']) && $_REQUEST['id'] == 1) {
            throw new Exception('Root-node can not be modified!');
        }
        if(isset($_POST['command'], $_POST['id']) && $_POST['command']==='delete') {
            $this->loadP2Page($_POST['id'])->delete();
            // reload the current page to avoid duplicated delete actions
            $this->refresh();
        }
    }
}
