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
 * Controller, handles file actions
 *
 * Detailed info
 * <pre>
 * $var = code_example();
 * </pre>
 * {@link DefaultController}
 *
 * @author Tobias Munk <schmunk@usrbin.de>
 * @version $Id: P2FileController.php 527 2010-03-28 19:32:03Z schmunk $
 * @package p2.controllers
 * @since 2.0
 */
class P2FileController extends CController {
    const PAGE_SIZE=10;

    /**
     * @var string specifies the default action to be 'list'.
     */
    public $defaultAction='list';

    /**
     * @var CActiveRecord the currently loaded data model instance.
     */
    private $_model;

    public function actions() {
        return array(
            'image'=>array(
                'class'=>'application.modules.p2.actions.P2FileImageAction',
            ),
        );
    }


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
            array('allow',  // allow all users to perform 'list' and 'show' actions
                'actions'=>array('image'),
                'users'=>array('*'),
            ),
            array('allow', // allow authenticated user to perform 'create' and 'update' actions
                'actions'=>array('import','list','show','create','ajaxCreate','update','admin','ckbrowse','ckbrowseimage','ckbrowseflash','ckupload','delete'),
                'roles'=>array('editor','admin'),
            ),
            array('deny',  // deny all users
                'users'=>array('*'),
            ),
        );
    }

    /**
     * Shows a particular model.
     */
    public function actionShow() {
        $this->render('show',array('model'=>$this->loadP2File()));
    }

    /**
     * Shows a import manager screen.
     *
     * Uses the value in modules=>p2=>params=>p2File=>importPath relative to
     * application base path
     */
    public function actionImport() {

        $data['result'] = null;
        $data['directories'] = array();

        // check
        if (isset(Yii::app()->params['p2.file.importPaths'])) {
            $paths = Yii::app()->params['p2.file.importPaths'];
        } else {
            throw new CHttpException(500, 'p2.file.importPaths not set!');
        }

        foreach($paths AS $path) {
            if (!is_dir(Yii::app()->basePath.DS.$path)) {
                throw new CHttpException(500, 'Import path '.$path.' does not exist!');
            }
        }



        if (isset($_POST['files'])) {
            $data['result'] = "Starting import ...<br/>";
            foreach($_POST['files'] AS $filePath) {
                $md5=md5($filePath);
                if ($exists = P2File::model()->findByAttributes(array('fileMd5'=>$md5))) {
                    $data['result'] .= "<div class='errorSummary'>".$filePath." exists as #".$exists->id."</div>";
                } else {
                    $fullPath = Yii::app()->basePath.DS.$filePath;
                    $model = new P2File;
                    $model->FileUploadBehavior->detach($model);
                    $name = preg_replace("|/+|","-", $filePath);
                    $name = preg_replace("/[^a-zA-Z0-9.\-_]+/","_", $name);
                    $model->name = $name;
                    $model->filePath = $filePath;
                    $model->fileType = CFileHelper::getMimeType($fullPath);
                    $model->fileSize = filesize($fullPath);
                    $model->fileOriginalName = basename($filePath);
                    $model->fileMd5 = $md5;
                    #try {
                    $model->save();
                    $data['result'] .= "<div class='successSummary'>".$filePath." imported as #".$model->id."</div>";
                }
                #} catch (Exception $e) {
                #   echo $e->getMessage();
                #}
                #var_dump($model->errors);
            }
            $data['result'] .= "done.";
            $this->render('import', $data);
        } elseif (isset($_POST['dir'])) {
            $options = array();
            $data['files'] = array();

            foreach($paths AS $path) {
                $data['directories'] = CMap::mergeArray($data['directories'], $this->getDirectories(Yii::app()->basePath.DS.$path));
            }

            foreach(CFileHelper::findFiles($_POST['dir'],array('exclude'=>array('.DS_Store'))) AS $file) {
                $shortName = str_replace(Yii::app()->basePath.DS,'',$file);
                $data['files'][$shortName] = $shortName;
            }
            $this->render('import', $data);
        } else {
            $data['files'] = array();

            foreach($paths AS $path) {
                $data['directories'] = CMap::mergeArray($data['directories'], $this->getDirectories(Yii::app()->basePath.DS.$path));
            }
            $this->render('import', $data);
        }
        #$this->render('import');
    }

    private function getDirectories($directory, $list = array()) {
        foreach(scandir($directory) AS $file) {
            $path = $directory.DS.$file;
            if(!is_dir($path) && !is_link($path)) continue;
            if(substr($file,0,1) == ".") continue;
            $list[$path] = str_replace(Yii::app()->basePath,"",$path);
            $list = array_merge($list, $this->getDirectories($path, $list));
        }
        #var_dump($list);
        return $list;
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'show' page.
     */
    public function actionCreate() {
#          echo P2Helper::return_url();exit;
        $model=new P2File;
        if(isset($_POST['P2File'])) {
            $model->attributes=$_POST['P2File'];
            try {
                if($model->save()) {
                    $success = CJSON::encode(array(true,$model->id));
                } else {
                    foreach($model->getErrors() AS $errors)
                        $msg = implode("<br/>",$errors);
                    $error = $msg;
                }
            } catch (Exception $e) {
                $error = $e->getMessage();
            }
        }
        else {
            $error = "No data received!";
        }

        if (Yii::app()->request->isAjaxRequest) {
            if (isset($success)) {
                echo $success;
            } else {
                throw new CHttpException('Ajax', $error);
            }
        } else {
            if (isset($success)) {
                if(P2Helper::return_url()) {
                    $this->redirect(P2Helper::return_url());
                } else {
                    $this->redirect(array('show','id'=>$model->id, 'return_url' => P2Helper::return_url()));
                }
            } else {
                $this->render('create',array('model'=>$model));
            }
        }

    }


    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'show' page.
     */
    public function actionUpdate() {
        $model=$this->loadP2File();
        if(isset($_POST['P2File'])) {
            $model->attributes=$_POST['P2File'];
            try {
                if($model->save()) {
                    $success = CJSON::encode(array(true,$model->id));
                } else {
                    foreach($model->getErrors() AS $errors)
                        $msg = implode("<br/>",$errors);
                    $error = $msg;
                }
            } catch (Exception $e) {
                $error = $e->getMessage();
            }
        }
        else {
            $error = "No data received!";
        }

        if (Yii::app()->request->isAjaxRequest) {
            if (isset($success)) {
                echo $success;
            } else {
                throw new CHttpException('Ajax', $error);
            }
        } else {
            if (isset($success)) {
                if(P2Helper::return_url()) {
                    $this->redirect(P2Helper::return_url());
                } else {
                    $this->redirect(array('show','id'=>$model->id, 'return_url' => P2Helper::return_url()));
                }
            } else {
                $this->render('update',array('model'=>$model));
            }
        }
    }

    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'list' page.
     */
    public function actionDelete() {
        if(Yii::app()->request->isPostRequest) {
            // we only allow deletion via POST request
            try {
                $this->loadP2File($_REQUEST['id'])->delete();
            } catch (Exception $e) {
                $error = $e->getMessage();
            }

            if (Yii::app()->request->isAjaxRequest) {
                if (isset($error)) {
                    throw new CHttpException('Ajax', $error);
                } else {
                    echo CJSON::encode(array(true));
                }
            } else {
                if(P2Helper::return_url()) {
                    $this->redirect(P2Helper::return_url());
                } else {
                    $this->redirect(array('list', 'return_url' => P2Helper::return_url()));
                }
            }
        }
        else
            throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
    }

    /**
     * Lists all models.
     */
    public function actionList() {
        $criteria=new CDbCriteria;

        $pages=new CPagination(P2File::model()->count($criteria));
        $pages->pageSize=self::PAGE_SIZE;
        $pages->applyLimit($criteria);

        $models=P2File::model()->findAll($criteria);

        $this->render('list',array(
            'models'=>$models,
            'pages'=>$pages,
        ));
    }

    /**
     * Lists all models.
     */
    public function actionCkbrowseimage() {
        $this->actionCkbrowse('image');
    }
    public function actionCkbrowseflash() {
        $this->actionCkbrowse('flash');
    }

    public function actionCkbrowse($type = null) {
        $array = Yii::app()->params['p2.file.imagePresets'];
        $copy = Yii::app()->params['p2.file.imagePresets'];
        foreach ($copy AS $key => $preset) {
            if (isset($preset['name'])) {
                $presets[$key] = $preset['name'];
            }
        }

        $criteria=new CDbCriteria;
        if ($type !== null) {
            $criteria->condition = "fileType LIKE '%{$type}%' ";
            $criteria->order = "id DESC"; // FIXME
        }
        $pages=new CPagination(P2File::model()->count($criteria));
        $pages->pageSize=5;
        $pages->applyLimit($criteria);

        $models=P2File::model()->findAll($criteria);

        $this->render('ckbrowse',array(
            'models'=>$models,
            'pages'=>$pages,
            'presets' => $presets,
            'type' => $type
        ));
    }

    public function actionCkupload() {
        $model=new P2File;
        if (isset($_POST['P2File'])) {
            $this->actionCreate();
        } else {
            $this->render('ckupload', array(
                'model' => $model
            ));
        }
    }

    /**
     * Manages all models.
     */
    public function actionAdmin() {
        $this->processAdminCommand();

        $criteria=new CDbCriteria;

        $pages=new CPagination(P2File::model()->count($criteria));
        $pages->pageSize=self::PAGE_SIZE;
        $pages->applyLimit($criteria);

        $sort=new CSort('P2File');
        $sort->applyOrder($criteria);

        $models=P2File::model()->findAll($criteria);

        $this->render('admin',array(
            'models'=>$models,
            'pages'=>$pages,
            'sort'=>$sort,
        ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer the primary key value. Defaults to null, meaning using the 'id' GET variable
     */
    public function loadP2File($id=null) {
        if($this->_model===null) {
            if($id!==null || isset($_GET['id']))
                $this->_model=P2File::model()->findbyPk($id!==null ? $id : $_GET['id']);
            if($this->_model===null)
                throw new CHttpException(404,'The requested page does not exist.');
        }
        return $this->_model;
    }

    /**
     * Executes any command triggered on the admin page.
     */
    protected function processAdminCommand() {
        if(isset($_POST['command'], $_POST['id']) && $_POST['command']==='delete') {
            $this->loadP2File($_POST['id'])->delete();
            // reload the current page to avoid duplicated delete actions
            $this->refresh();
        }
    }


}
