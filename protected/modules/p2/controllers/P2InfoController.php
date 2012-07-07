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
 * Controller, handles info actions
 *
 * Detailed info
 * <pre>
 * $var = code_example();
 * </pre>
 * {@link DefaultController}
 *
 * @author Tobias Munk <schmunk@usrbin.de>
 * @version $Id: P2InfoController.php 510 2010-03-24 00:39:06Z schmunk $
 * @package p2.controllers
 * @since 2.0
 */
class P2InfoController extends CController
{
	const PAGE_SIZE=10;

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
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
		);
	}

	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
		return array(
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('list','show','create','ajaxCreate','update','admin','delete'),
				'roles'=>array('admin'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	/**
	 * Shows a particular model.
	 */
	public function actionShow()
	{
		$this->render('show',array('model'=>$this->loadP2Info()));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'show' page.
	 */
	public function actionCreate()
	{
		$model=new P2Info;
		if(isset($_POST['P2Info']))
		{
			$model->attributes=$_POST['P2Info'];
			try{
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
                else
                {
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
	public function actionUpdate()
	{
		$model=$this->loadP2Info();
				if(isset($_POST['P2Info']))
		{
			$model->attributes=$_POST['P2Info'];
			try{
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
                else
                {
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
	public function actionDelete()
	{
		if(Yii::app()->request->isPostRequest)
		{
			// we only allow deletion via POST request
			try {
                            $this->loadP2Info($_REQUEST['id'])->delete();
                        } catch (Exception $e) {
                            $error = $e->getMessage();
                        }

                        if (Yii::app()->request->isAjaxRequest)
                        {
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
	public function actionList()
	{
		$criteria=new CDbCriteria;

		$pages=new CPagination(P2Info::model()->count($criteria));
		$pages->pageSize=self::PAGE_SIZE;
		$pages->applyLimit($criteria);

		$models=P2Info::model()->findAll($criteria);

		$this->render('list',array(
			'models'=>$models,
			'pages'=>$pages,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$this->processAdminCommand();

		$criteria=new CDbCriteria;

		$pages=new CPagination(P2Info::model()->count($criteria));
		$pages->pageSize=self::PAGE_SIZE;
		$pages->applyLimit($criteria);

		$sort=new CSort('P2Info');
		$sort->applyOrder($criteria);

		$models=P2Info::model()->findAll($criteria);

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
	public function loadP2Info($id=null)
	{
		if($this->_model===null)
		{
			if($id!==null || isset($_GET['id']))
				$this->_model=P2Info::model()->findbyPk($id!==null ? $id : $_GET['id']);
			if($this->_model===null)
				throw new CHttpException(404,'The requested page does not exist.');
		}
		return $this->_model;
	}

	/**
	 * Executes any command triggered on the admin page.
	 */
	protected function processAdminCommand()
	{
		if(isset($_POST['command'], $_POST['id']) && $_POST['command']==='delete')
		{
			$this->loadP2Info($_POST['id'])->delete();
			// reload the current page to avoid duplicated delete actions
			$this->refresh();
		}
	}
}
