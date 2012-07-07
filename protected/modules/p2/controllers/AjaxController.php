<?php
/**
 * AjaxController Class File
 *
 * @author Tobias Munk <schmunk@usrbin.de>
 * @link http://www.phundament.com/
 * @copyright Copyright &copy; 2005-2010 diemeisterei GmbH
 * @license http://www.phundament.com/license/
 */

/**
 * Controller, serves XMLHTTPRequests
 *
 * AjaxController imports the following actions:
 * <ul>
 * <li>{@link P2CreateInputAction}</li>
 * <li>{@link P2AjaxSearchAction}</li>
 * <ul>
 *
 * @author Tobias Munk <schmunk@usrbin.de>
 * @version $Id: AjaxController.php 519 2010-03-24 02:15:49Z schmunk $
 * @package p2.controllers
 * @since 2.0
 */

class AjaxController extends CController {

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
                'actions'=>array('generateInput','search','renderPdf'),
                'roles'=>array('editor','admin'),
            ),
            array('allow',  // allow all users to perform 'list' and 'show' actions
                'actions'=>array('renderSwf'),
            ),
            array('deny',  // deny all users
                'users'=>array('*'),
            ),
        );
    }

    public function beforeAction($event){
        Yii::trace("AJAX action ".$this->action->id, 'p2');
        return true;
    }

    /**
     * Returns available actions.
     *
     * @return array Controller actions
     */
    public function actions() {
       $uid = uniqid(); // todo md5 of source PDF file

       if(isset($_GET['pdfFile'])){
	    $file = realpath(base64_decode($_GET['pdfFile']));
            Yii::trace("PDF file: ".$file);
            if (is_file($file)) {
                $uid = md5_file($file);
            }
       } else {
            $file = null;
       }
       /*
       if (isset($_GET['pdfFile'])) {
            $file = realpath(base64_decode($_GET['pdfFile']));
        } else {
            $file = null;
        }*/
        return array(
            'generateInput'=>array(
                'class'=>'p2.components.cellmanager.P2CreateInputAction',
            ),
            'search'=>array(
                'class'=>'p2.actions.P2AjaxSearchAction',
            ),
            'renderPdf'=>array(
                'class'=>'p2.extensions.pdf.EPdfRenderAction',
                'templateView'=>'p2.views.pdfTemplates.test',
                #'templateData'=>array(),
                'pdfOutputPath' => Yii::app()->basePath.DS.Yii::app()->params['publicRuntimePath'].DS.'pdf', // lithron's job folder
                'pdfPublishPath' => Yii::app()->basePath.DS.Yii::app()->params['publicRuntimePath'].DS.'publish',
                'pdfPublishUrl' => Yii::app()->baseUrl.Yii::app()->params['publicRuntimeUrl'].'/publish',
            ),
            'renderSwf'=>array(
                'class'=>'p2.extensions.pdf.ESwfRenderAction',
                'swfPublishPath' => Yii::app()->basePath.DS.Yii::app()->params['publicRuntimePath'].DS.'swf'.DS.$uid,
                'swfPublishUrl' => Yii::app()->baseUrl.Yii::app()->params['publicRuntimeUrl'].'/swf/'.$uid,
                'swftoolsPath' => '/opt/bin',
                'pdfFile' => $file,
            ),
        );
    }

}
