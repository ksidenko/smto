<?php

class MachineController extends SBaseController
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column2';
        
    public $menu = array();
    public $breadcrumbs = array();

    public $isTemplate = false;
        
	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
		);
	}

    public function beforeAction() {
        $this->isTemplate = isset($_REQUEST['template']) && $_REQUEST['template'] == true;

        //echo '$template = ' . ($this->isTemplate ? '1' : '0');
        return true;
    }
	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($id)
	{
		$this->render('view',array(
			'model'=>$this->loadModel($id, true),
		));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
        $model = new Machine;
        $model->setIsTemplate($this->isTemplate);

        $res = false;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if (isset($_POST['Machine']) && !isset($_POST['apply_template'])) {
            //echo '<pre>';print_r($_POST); echo '</pre>'; die;

            try {
                $res = $model->saveMachine($_POST, true);

                if ($res !== false) {
                    Yii::app()->user->setFlash('success',"Данные успешно добавлены");
                    $this->redirect(array('update','id'=>$model->id));
                }
            } catch(Exception $e) {
                $res = false;
                print_r($e->getMessage());
            }
            if ($res === false) {
                Yii::app()->user->setFlash('error',"Произошла ошибка при добавлении");
            }
		} else {
            $templateId = 1;
            if ( isset($_POST['apply_template']) ) {
                $templateId = $_POST['Machine']['template_id'];
                //echo $templateId; die;
            }

            //echo $templateId; die;
            // load template
            $model = $this->loadModel($templateId, true);
            $model->template_id = $templateId;
            $model->setIsTemplate($this->isTemplate);
            //$model->setIsNewRecord(true);
        }

		$this->render('create',array(
			'model'=>$model
		));
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
		$model=$this->loadModel($id, true);
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if (isset($_POST['Machine']) ) {

            //echo '<pre>'. print_r($_POST, true) . '</pre>'; die;

            try {
                $res = $model->saveMachine($_POST, false);
                if ($res !== false) {
                    Yii::app()->user->setFlash('success',"Данные успешно от редактированы");
                    $param = array('admin','template' => intval($model->getIsTemplate()));
                    $this->redirect($param);
                }
            } catch(Exception $e) {
                $res = false;
                //print_r($e); die;
            }
            if ($res === false) {
                Yii::app()->user->setFlash('error',"Произошла ошибка при редактировании");
            }
		} 

        $path = Param::getParamValue('machine_config_data_path') . '' . $model->mac . '/' . 'd0003.cfg_old';
        //$model->initMachineConfigFromFile($path);


        $machineConfig = MachineConfig::model();
        $machineConfigRows = $machineConfig->findAllByAttributes(array('machine_id' => $model->id));
        $machineConfigData = array();
        foreach ($machineConfigRows as $machineConfigRow) {
            $machineConfigData[$machineConfigRow['condition_number']][$machineConfigRow['machine_state_id']]['apply_number'] = $machineConfigRow['apply_number'];
            $machineConfigData[$machineConfigRow['condition_number']][$machineConfigRow['machine_state_id']]['value'] = $machineConfigRow['value'];
        }

        //print_r($machineConfigData); die;

		$this->render('update',array(
			'model'=>$model,
            'machineConfigData' => $machineConfigData,
		));
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
		if(Yii::app()->request->isPostRequest)
		{
			// we only allow deletion via POST request
			$this->loadModel($id, false)->delete();

			// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
			if(!isset($_GET['ajax']))
				$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
		}
		else
			throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
        $model=new Machine();

        $model->setIsTemplate($this->isTemplate);
        if ($this->isTemplate)
            $model->resetScope()->template_records();
        else
            $model->real_records();

		$dataProvider=new CActiveDataProvider($model,array(
            'criteria'=>array(
            ),
            'pagination'=>array(
                'pageSize'=>20,
            ),
        ));


		$this->render('index',array(
			'dataProvider'=>$dataProvider,
            'model'=>$model,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new Machine('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Machine']))
			$model->attributes=$_GET['Machine'];

        $model->setIsTemplate($this->isTemplate);
        if ($this->isTemplate)
            $model->resetScope()->template_records();
        else
            $model->real_records();

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 */
	public function loadModel($id, $resetScope = false)
	{
		$model=Machine::model();
        if ($resetScope) {
            $model->resetScope();
        }
        $model=$model->findByPk($id);
        
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param CModel the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='machine-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
