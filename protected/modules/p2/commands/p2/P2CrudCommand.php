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
 * Command, generates p2 CRUD files
 *
 * Usage:
 * <pre>p2crud Model</pre>
 *
 * See also:
 * {@link P2Command}
 *
 * @author Tobias Munk <schmunk@usrbin.de>
 * @version $Id: P2CrudCommand.php 524 2010-03-24 22:54:23Z schmunk $
 * @package p2.cli.commands
 * @since 2.0
 */
Yii::import('system.cli.commands.shell.CrudCommand');
class P2CrudCommand extends CrudCommand {

    public $actions=array('create','update','show','view','admin','list','_form','_form-inputs', '_search');

    public function __construct() {
        $this->templatePath = Yii::getPathOfAlias("application.modules.p2.views.shell.crud");
    }

    public function generateInputField($modelClass,$column) {

        #echo $column->dbType;
        if($column->isForeignKey) {
            $model = new $modelClass;
            foreach($model->relations() AS $name => $relation) {
                if ($relation[2] == $column->name) {
                    return "P2Helper::widget('p2.extensions.relation.Relation', array(
                       'model' => \$model,
                       'relation' => '$name',
                       'htmlOptions' => array('prompt'=>'Select ...'),
                       'fields' => 'title' // show the field  of the parent element
                    ));";
                    // BELONGS TO - HAS ONE
                    #return "CHtml::activeDropDownList(\$model,'{$column->name}', CHtml::listData({$relation[1]}::model()->findAll(array('order'=>'title','condition'=>'id NOT IN('.implode(',',CHtml::listData(\$model::model()->findAll(array('select'=>'id')),'id','id')).')')), 'id', 'title'), array('prompt'=>'Select ...'));";
                }
            }
        }

        else if($column->isPrimaryKey) // no good idea ;)
            return "''";

        
        else if($column->type==='boolean')
            return "CHtml::activeCheckBox(\$model,'{$column->name}')";


        else if((stripos($column->name,'p2File')!==false) || (stripos($column->name,'mediaId')!==false)) {
            $preset = ($context == 'admin')?'fckbrowse':'default';
            return
                "P2File::image(\$model->{$column->name},'{$preset}');".
                "echo '<br/>';".
                "echo P2Helper::widget('application.modules.p2.components.P2AutoComplete',
                array(
                'model' => \$model,
                'attribute' => '".$column->name."',
                'searchModel' => 'P2File',
                'mode' => P2AutoComplete::MODE_SELECT,
                'class' => 'ui-widget',)
            );";
        }

        else if(stripos($column->dbType,'text')!==false) {
            $code = "CHtml::activeTextArea(\$model,'{$column->name}',array('rows'=>6, 'cols'=>40));";

            // tiny addon
            if(stripos($column->name,'html')!==false)
                $code .= "\$this->widget('P2Editor',
                    array(
                        'name'=>'".$modelClass."_".$column->name."',
                        'type'=> Yii::app()->params['editor']['type'],
                        'height' => Yii::app()->params['editor']['height'],
                        'toolbar' => Yii::app()->params['editor']['toolbar'],
                        'config' => Yii::app()->params['editor']['config']
                    )
                );";
            return $code;
        }

        else if(stripos($column->dbType,'date')!==false) {
            $code = "P2Helper::widget('system.zii.widgets.jui.CJuiDatePicker',
              array(
                    'model'=> \$model,
                    'attribute'=> '{$column->name}',
                    'htmlOptions'=>array('size'=>10),
                    'options' => array(
                        'dateFormat' => 'js:$.datepicker.ISO_8601'
                    )
                   )
             );";
            return $code;
        }

        else {
            if(preg_match('/^(password|pass|passwd|passcode)$/i',$column->name))
                $inputField='activePasswordField';
            else
                $inputField='activeTextField';

            if($column->type!=='string' || $column->size===null)
                return "CHtml::{$inputField}(\$model,'{$column->name}')";
            else {
                if(($size=$maxLength=$column->size)>60)
                    $size=60;
                return "CHtml::{$inputField}(\$model,'{$column->name}',array('size'=>$size,'maxlength'=>$maxLength))";
            }
        }


    }

    public function generateOutputField($modelClass,$column, $context = null) {
        if (stripos($column->name, 'p2File')!==false || (stripos($column->name,'mediaId')!==false)) {
            $preset = ($context == 'admin')?'fckbrowse':'default';
            return "P2File::image(\$model->{$column->name},'{$preset}');";
        } else if($column->isForeignKey) {
             $model = new $modelClass;
            foreach($model->relations() AS $name => $relation) {
                if ($relation[2] == $column->name) {
                    return "P2Helper::relation(\$model->{$name}, 'title')";
                }
            }
            
        }
        else {
            return "CHtml::encode(\$model->{$column->name});";
        }
    }

    public function generateRelationLabel($modelClass, $name = "n/a") {
        return "CHtml::label('$name','$name')"; // FIXME
    }

    public function generateRelationField($modelClass, $name, $relation) {
        $model = new $modelClass;
        #if ($model->hasAttributes($name)) return "ATTR";
        switch($relation[0]) {
            case CActiveRecord::HAS_ONE:
                return "'(not yet implemented)'";#"\$model->".$name."->title;";
                break;
            case CActiveRecord::HAS_MANY:
                return "P2Helper::hasMany(\$model->$name);";
                break;
            default:
                return "'<div>'.P2Helper::widget('p2.extensions.relation.Relation',array(
                       'model' => \$model,
                       'relation' => '$name',
                       'allowEmpty' => false,
                       'style' => 'onepane',
                       'fields' => 'title', // show the field  of the parent element
                    )).'</div>';";
                break;
        }

    }
}

?>