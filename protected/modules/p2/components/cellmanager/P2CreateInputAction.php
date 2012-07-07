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
 * Action for generating forms from class properties or view files
 *
 * Detailed info
 * <pre>
 * $var = code_example();
 * </pre>
 * {@link DefaultController}
 *
 * @author Tobias Munk <schmunk@usrbin.de>
 * @version $Id: P2CreateInputAction.php 506 2010-03-24 00:32:15Z schmunk $
 * @package p2.cellmanager
 * @since 2.0
 */
class P2CreateInputAction extends CAction {

    public function run() {

        $return = "";

        // do not load query again, may break existing js plugins in page
        Yii::app()->clientScript->scriptMap['jquery.js'] = false;
        Yii::app()->clientScript->scriptMap['jquery.min.js'] = false;
        Yii::app()->clientScript->scriptMap['jquery-ui.min.js'] = false;

        $fields = Yii::app()->request->getPost('ajaxInput');

        // determine widget class
        if (is_array($fields)) {
            $classPath = P2Helper::getPostVar($fields);
        }
        else {
            $classPath = $fields;
        }

        if ($className = P2Helper::classExists($classPath)) {
            $widget = new $className;

            // prepare cell manager widget or standard widget
            if($widget instanceof ICellManagerWidget) {
                $view = $widget->getCreateView();
                $data = $widget->getCreateData();

                $model = $widget->model;
                if(isset($_POST['P2Cell']['classProps'])) {
                    $attributes = CJSON::decode($_POST['P2Cell']['classProps']);
                    $model->setAttributes($attributes);
                    #var_dump($attributes);
                    if ($model instanceof CActiveRecord && isset($attributes[$model->tableSchema->primaryKey])) {
                        $model->setPrimaryKey($attributes[$model->tableSchema->primaryKey]);
                    }
                } else {

                }
                $data['model'] = $model;

                $capture = true;
                $processOutput = true;
                $return = $this->controller->renderPartial($view, $data, $capture, $processOutput);
            } else {
                $return .= '<h3>'.$className.'</h3>';
                $return .= '<div class="yiiForm form">';
                if(isset($_POST['P2Cell']['classProps'])) {
                    $attributes = CJSON::decode($_POST['P2Cell']['classProps']);
                }
                foreach(get_class_vars($className) AS $key => $var) {
                    $return .= '<div class="row">';
                    $return .= CHtml::label($key,$key);
                    $return .= CHtml::textField(
                        P2CellManager::CSSCLASS_INPUT."_".$key."",
                        (isset($attributes[$key]))?$attributes[$key]:$widget->$key,
                        array('class'=>P2CellManager::CSSCLASS_INPUT, 'size'=>25,'maxlength'=>255,));
                    $return .= '</div>';
                }
                $return .= '</div>';
            }
            echo $return;
        }
        else {
            $msg = "Widget {classPath} not found!";
            Yii::log($msg, LOG_WARNING, "p2.ajaxController");
            throw new CHttpException('Ajax',Yii::t("p2", $msg, array("{classPath}"=>$classPath)));
        }
    }
}

?>
