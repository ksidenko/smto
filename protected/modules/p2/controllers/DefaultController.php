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
 * Controller, handles p2 actions
 *
 * Detailed info
 * <pre>
 * $var = code_example();
 * </pre>
 * {@link DefaultController}
 *
 * @author Tobias Munk <schmunk@usrbin.de>
 * @version $Id: DefaultController.php 510 2010-03-24 00:39:06Z schmunk $
 * @package p2.controllers
 * @since 2.0
 */
class DefaultController extends CController {

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
                'actions'=>array('test','install'),
                'users'=>array('*'),
            ),
            array('allow', // allow authenticated user to perform 'create' and 'update' actions
                'actions'=>array('index','pdf'),
                'roles'=>array('editor','admin'),
            ),
            array('allow', // allow admin user to perform 'admin' and 'delete' actions
                'actions'=>array('status','cleanup'),
                'roles'=>array('admin'),
            ),
            array('deny',  // deny all users
                'users'=>array('*'),
            ),
        );
    }

    public function actionIndex() {
        $this->render('index');
    }

    public function actionInstall() {
        $this->render('install');
    }

    public function actionStatus() {
        $this->render('status');
    }

    public function actionPdf() {
        $this->render('pdf');
    }

    public function actionCleanup() {
        $files = CFileHelper::findFiles(Yii::app()->basePath.DS.Yii::app()->params['protectedRuntimePath']);
#       $files = CFileHelper::findFiles(Yii::app()->basePath.DS.Yii::app()->params['publicRuntimePath']);
        $now = time();
        foreach($files AS $i => $file){
            $atime = fileatime($file);
            $diff = $now-$atime;
            echo $diff."--".$file."<br/>";
        }
    }


}