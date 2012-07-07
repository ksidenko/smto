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
 * Controller, handles user actions
 *
 * Detailed info
 * <pre>
 * $var = code_example();
 * </pre>
 * {@link DefaultController}
 *
 * @author Tobias Munk <schmunk@usrbin.de>
 * @version $Id: P2UserController.php 543 2010-03-31 00:28:27Z schmunk $
 * @package p2.controllers
 * @since 2.0
 */
class P2UserController extends P2BaseController
{
	const PAGE_SIZE=25;

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
			array('allow', // allow anonymous users to register/verify/request new password
				'actions'=>array('register','verify','forgotpassword','resetpassword'),
				'users'=>array('?'),
			),
			array('allow', // allow logged in users to reset password and update infos
				'actions'=>array('userupdate','verifyemail'),
				'users'=>array('@'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('list','show','create','create','update','admin','delete'),
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
		$this->processActivationCommand();
		$this->render('show',array('model'=>$this->loadP2User()));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'show' page.
	 */
	public function actionCreate()
	{
		$model=new P2User;
		if(isset($_POST['P2User']))
		{
                    $model->attributes=$_POST['P2User'];
                    try{
                        $model->save();
                    } catch (Exception $e) {
                        $model->addError(null, $e->getMessage());
                    }

                    if (Yii::app()->request->isAjaxRequest) {
                        if (!$model->hasErrors()) {
                            echo $model->getPrimaryKey();
                        } else {
                            throw new CHttpException(400, implode("<br/>".$model->hasErrors()));
                        }
                    } elseif (!$model->hasErrors()) {
                        if(P2Helper::return_url()) {
                            $this->redirect(P2Helper::return_url());
                        } else {
                            $this->redirect(array('show','id'=>$model->id, 'return_url' => P2Helper::return_url()));
                        }
                    }
		}
                $this->render('create',array('model'=>$model));
 	}


	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'show' page.
	 */
	public function actionUpdate()
	{
		$model=$this->loadP2User();
                if(isset($_POST['P2User']))
		{
                    $model->attributes=$_POST['P2User'];
                    try{
                        $model->save();
                    } catch (Exception $e) {
                        $model->addError(null, $e->getMessage());
                    }

                    if (Yii::app()->request->isAjaxRequest) {
                        if (!$model->hasErrors()) {
                            echo $model->getPrimaryKey();
                        } else {
                            throw new CHttpException(400, implode("<br/>".$model->hasErrors()));
                        }
                    } elseif (!$model->hasErrors()) {
                        if(P2Helper::return_url()) {
                            $this->redirect(P2Helper::return_url());
                        } else {
                            $this->redirect(array('show','id'=>$model->id, 'return_url' => P2Helper::return_url()));
                        }
                    }
		}

                $this->render('update',array('model'=>$model));
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
                            $this->loadP2User($_REQUEST['id'])->delete();
                        } catch (Exception $e) {
                            $error = $e->getMessage();
                        }

                        if (Yii::app()->request->isAjaxRequest)
                        {
                            if (isset($error)) {
                                throw new CHttpException(400, $error);
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

		$pages=new CPagination(P2User::model()->count($criteria));
		$pages->pageSize=self::PAGE_SIZE;
		$pages->applyLimit($criteria);

		$models=P2User::model()->findAll($criteria);

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

		$pages=new CPagination(P2User::model()->count($criteria));
		$pages->pageSize=self::PAGE_SIZE;
		$pages->applyLimit($criteria);

		$sort=new CSort('P2User');
		$sort->applyOrder($criteria);

		$models=P2User::model()->findAll($criteria);

		$this->render('admin',array(
			'models'=>$models,
			'pages'=>$pages,
			'sort'=>$sort,
		));
	}


	/**
	 * Show/process registration form
	 */
	public function actionRegister() {
		$user=new P2User;
		$user->scenario='register';
		$errors = null;
		$profile = null;

#$transaction=$user->dbConnection->beginTransaction();
#try
#{
    // find and save are two steps which may be intervened by another request
    // we therefore use a transaction to ensure consistency and integrity

		// instantiate and validate external model
		if($profileModelClass = $this->module->params['p2.user.profileModel']) {
			$profile = new $profileModelClass;
			$profile->scenario='register';
			if (isset($_POST[$profileModelClass])) {
				$profile->attributes=$_POST[$profileModelClass];
				$profile->validate();
			}
		}

		// validate user model
		if (isset($_POST['P2User'])) {
			$user->attributes=$_POST['P2User'];
			$user->validate();

			// save them, when there are no errors
			if(!$user->hasErrors() && ($profile === null || !$profile->hasErrors())) {
				if ($user->save()) {
					if ($profile) {
                                                // we try both - for backwards comp. - FIX ME?
						$profile->{Yii::app()->params['p2.user.profileModel.fkUser']}=$user->id;
						$profile->save();
					}
					$this->module->mailer->sendUserVerification($user);
					Yii::app()->user->setFlash('p2UserRegistered',true);
					$this->refresh();
				}
			} 
		}
#}
#catch(Exception $e)
#{
#    $transaction->rollBack();
#    throw new Exception($e);
#}

                // collect errors
		if ($profile) {
			$errors = array($user, $profile);
		} else {
			$errors = $user;
		}

		$this->render('register', array(
			'user' => $user,
			'profile' => $profile,
			'errors' => $errors
		));
	}

	/**
	 * Show/process form for email/password update
	 */
	public function actionUserupdate()
	{
		if (($user=Yii::app()->user->getModel(P2User::STATUS_ACTIVE))===null)
			throw new CException('User not found!');

		$user->scenario='userupdate';

		if (isset($_POST['P2User'])) {
			$user->attributes=$_POST['P2User'];
                        $user->update(array('firstName','lastName'));
			if ($user->validate() && $user->updateEmailAndPassword()) {
				if ($emailChanged=$user->hasChangedEmail()) 
					$this->module->mailer->sendUserEmailVerification($user);

				Yii::app()->user->setFlash('p2UserUpdated',$emailChanged ? 'mailsent' : true);
			}
		}

		$this->render('userupdate',array(
			'user'=>$user
		));
	}

	/**
	 * Handle email verification
	 */
	public function actionVerifyemail()
	{
		if (!isset($_GET['token']))
			throw new CException('Invalid request!');

		if(($user=P2User::model()->findByToken($_GET['token']))!==null) 
			$user->useVerifiedEmail();
                else
                        throw new CHttpException('Verification error!');

		$this->render('verifyemail', array(
			'user' => $user,
		));
	}


	/**
	 * Verifies a user.
	 * 
	 * @access public
	 * @return void
	 */
	public function actionVerify()
	{
		if (!isset($_GET['token']))
			throw new CException('Invalid request!');

		if(($user=P2User::model()->findByToken($_GET['token']))!==null) {
			$user->status=P2User::STATUS_VERIFIED;
			$user->save();
			$this->module->mailer->sendAdminNotification($user);
		}

		$this->render('verify', array(
			'user' => $user,
		));
	}

	/**
	 * Display "forgot password" form and send email
	 *
	 * @access public
	 * @return void
	 */
	public function actionForgotpassword() 
	{
		$form=new P2ForgotPassword;

		if (isset($_POST['P2ForgotPassword'])) {
			$form->attributes=$_POST['P2ForgotPassword'];
			if ($form->validate()) {
				$this->module->mailer->sendUserForgotPassword($form->getUser());
				Yii::app()->user->setFlash('p2UserForgotPassword',true);
				$this->refresh();
			}
		}
		$this->render('forgotpassword', array(
			'form' => $form,
		));

	}

	/**
	 * Display "reset password" form and update user
	 *
	 * @access public
	 * @return void
	 */
	public function actionResetPassword()
	{
		if (!isset($_GET['token']))
			throw new CException('Invalid request!');

		if(($user=P2User::model()->findByToken($_GET['token'],P2User::STATUS_ACTIVE))!==null) {
			$user->scenario='resetpassword';
			if (isset($_POST['P2User'])) {
				$user->attributes=$_POST['P2User'];
				if ($user->save()) {
					Yii::app()->user->setFlash('p2UserResetPassword',true);
					$this->refresh();
				}
			}
		}

		$this->render('resetpassword', array(
			'user' => $user,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the primary key value. Defaults to null, meaning using the 'id' GET variable
	 */
	public function loadP2User($id=null)
	{
		if($this->_model===null)
		{
			if($id!==null || isset($_GET['id']))
				$this->_model=P2User::model()->findbyPk($id!==null ? $id : $_GET['id']);
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
			$this->loadP2User($_POST['id'])->delete();
			// reload the current page to avoid duplicated delete actions
			$this->refresh();
		}
	}

	/**
	 * Executes activation
	 */
	protected function processActivationCommand()
	{
		if(isset($_POST['command'], $_POST['id']) && $_POST['command']==='activate')
		{
			$user=$this->loadP2User($_POST['id']);
			$user->status=P2User::STATUS_ACTIVE;
			$user->save();
			$this->module->mailer->sendUserActivation($user);
			// reload the current page to avoid duplicated activation actions
			$this->refresh();
		}
	}
}
