<?php
P2Helper::registerJsExtension("js/json2.js");

$context = uniqid('context');
// register script file in view, may be rendered by P2CellController or P2CreateInputAction
$asset = realpath(dirname(__FILE__)."/../../components/cellmanager/p2CellManager.js");
$file = Yii::app()->assetManager->publish($asset);
Yii::app()->clientScript->registerScriptFile($file,0);

?>

<div class="yiiForm form" id="<?php echo $context ?>">
    <p class="hint">
        <?php echo Yii::t('P2Module.p2','__CELLMANAGER_CREATE__') ?>
    </p>

    <?php echo CHtml::beginForm(
    ($update)?
    $this->createUrl('/p2/p2Cell/update', array('id' => $model->id)):
    $this->createUrl('/p2/p2Cell/create')
    ); ?>

    <?php echo CHtml::hiddenField(
    'return_url',
    (P2Helper::return_url())?P2Helper::return_url(true):P2Helper::uri()
    ); ?>

    <?php echo CHtml::errorSummary($model); ?>

    <div class="row">
        <?php echo CHtml::label('Widget','P2Cell[classPath]'); ?>
        <?php echo CHtml::activeDropDownList(
        $model,
        'classPath',
        Yii::app()->params["p2.cellManager.availableWidgets"],
        array(
        'id' => $this->id.'ClassPath',
        #'prompt' => Yii::t('P2Module.p2','Please select ...'),
        'onchange' => '$("#editButton_'.$context.'").click()'
        )
        );
        ?>
        <div>
            <?php
            // Widget Dialog
            $this->widget(
                'system.zii.widgets.jui.CJuiDialog',
                array(
                'id'=> "P2Cell_classProps_externalModel_".$context,
                // additional javascript options for the dialog plugin
                'options'=>array(
                    'title'=>Yii::t('P2Module.p2','Widget'),
                    'width'=> Yii::app()->params['p2.cellManager.dialog.width'],
                    'height' => Yii::app()->params['p2.cellManager.dialog.height'],
                    'position' => Yii::app()->params['p2.cellManager.dialog.position'],
                    'modal' => Yii::app()->params['p2.cellManager.dialog.modal'],
                    'autoOpen'=> Yii::app()->params['p2.cellManager.dialog.autoOpen'],
                    'buttons' => array(
                        Yii::t('P2Module.p2','Save')=>'js:function(){
                                            if($("#P2Cell_classProps_externalModel_'.$context.' .widgetStatus").val() == '.P2CellManager::WIDGET_STATUS_UNSAVED.') {
                                                alert("'.Yii::t('P2Module.p2',"No record selected or created yet.").'");
                                                return false;
                                            }
                                            $("#P2Cell_classProps").externalModel("applyClassProps", {"context":"'.$context.'"});
                                            $("#P2Cell_classProps").externalModel("closeDialog", {"context":"'.$context.'"});
                                            $("INPUT.submit", "#'.$context.'").click().hide();
                                        }',
                        Yii::t('P2Module.p2','Apply')=>'js:function(){
                                            if($("#P2Cell_classProps_externalModel_'.$context.' .widgetStatus").val() == '.P2CellManager::WIDGET_STATUS_UNSAVED.') {
                                                alert("'.Yii::t('P2Module.p2',"No record selected or created yet.").'");
                                                return false;
                                            }
                                            $("#P2Cell_classProps").externalModel("applyClassProps", {"context":"'.$context.'"});
                                            $("#P2Cell_classProps").externalModel("closeDialog", {"context":"'.$context.'"});
                                        }',
                        Yii::t('P2Module.p2','Cancel')=>'js:function(){
                                            $("#P2Cell_classProps_externalModel_'.$context.'").dialog("close");
                                        }',),
                )
                )
            );
            ?>
        </div>
    </div>




    <div class="row">
        <?php echo CHtml::activeLabelEx($model,'classProps'); ?>
        <?php
        echo CHtml::ajaxButton(
        Yii::t('P2Module.p2','Edit Widget Properties'),
        array('/p2/ajax/generateInput'),
        array(
        'type' => 'POST',
        'error' => 'function(data){
                    alert(data.responseText);
                    }',
        'beforeSend' => 'function(data){
                    $("#P2Cell_classProps").externalModel("openDialog", {"context":"'.$context.'"});
                    $("#P2Cell_classProps_externalModel_'.$context.'").html("Loading form ...");
                    }',
        'success' => 'function(data){
                    $("#P2Cell_classProps_externalModel_'.$context.'").html(data);
                    //$("#P2Cell_classProps_externalModel").dialog("open");
                    }'),
        array(
        'id' => 'editButton_'.$context,
        'class' => P2Helper::juiButtonCssClass(),
        #'onclick' => '$("#P2Cell_classProps").externalModel("openDialog", {"context":"'.$context.'"})'
        )
        )
        ?>
        <?php echo CHtml::hiddenField("ajaxInput[0]", "P2Cell"); ?>
        <?php echo CHtml::hiddenField("ajaxInput[1]", "classPath"); ?>
        <?php echo P2Helper::clearfloat() ?>
        <?php echo CHtml::activeTextArea($model,'classProps',array('rows'=>1, 'cols'=>30));?>
        <span class="hint"><?php echo Yii::t('P2Module.p2','Widget properties, JSON-encoded.')?></span>
    </div>


    <div class="row">
        <?php echo CHtml::activeLabelEx($model,'rank'); ?>
        <?php echo CHtml::activeTextField($model,'rank'); ?>
        <p class="hint"><?php echo Yii::t('P2Module.p2','Position within the cell.')?></p>
    </div>



    <?php $this->beginClip('extendedOptions'); ?>
    <div class="row">
        <?php echo CHtml::activeLabelEx($model,'cellId'); ?>
        <?php echo CHtml::activeTextField($model,'cellId',array('size'=>20,'maxlength'=>100)); ?>
        <p class="hint"><?php echo Yii::t('P2Module.p2','Cell property id.')?></p>
    </div>
    <div class="row">
        <?php echo CHtml::activeLabelEx($model,'moduleId'); ?>
        <?php echo CHtml::activeTextField($model,'moduleId',array('size'=>20,'maxlength'=>45)); ?>
    </div>
    <div class="row">
        <?php echo CHtml::activeLabelEx($model,'controllerId'); ?>
        <?php echo CHtml::activeTextField($model,'controllerId',array('size'=>20,'maxlength'=>45)); ?>
    </div>
    <div class="row">
        <?php echo CHtml::activeLabelEx($model,'actionName'); ?>
        <?php echo CHtml::activeTextField($model,'actionName',array('size'=>20,'maxlength'=>45)); ?>
    </div>
    <div class="row">
        <?php echo CHtml::activeLabelEx($model,'requestParam'); ?>
        <?php echo CHtml::activeTextField($model,'requestParam',array('size'=>20,'maxlength'=>45)); ?>
        <p class="hint"><?php echo Yii::t('P2Module.p2','Vary cell contents by reuqest parameter.')?></p>
    </div>
    <div class="row">
        <?php echo CHtml::activeLabelEx($model,'sessionParam'); ?>
        <?php echo CHtml::activeTextField($model,'sessionParam',array('size'=>20,'maxlength'=>45)); ?>
    </div>
    <div class="row">
        <?php echo CHtml::activeLabelEx($model,'cookieParam'); ?>
        <?php echo CHtml::activeTextField($model,'cookieParam',array('size'=>20,'maxlength'=>45)); ?>
    </div>
    <div class="row">
        <?php echo CHtml::activeLabelEx($model,'applicationParam'); ?>
        <?php echo CHtml::activeTextField($model,'applicationParam',array('size'=>20,'maxlength'=>45)); ?>
    </div>
    <div class="row">
        <?php echo CHtml::activeLabelEx($model,'moduleParam'); ?>
        <?php echo CHtml::activeTextField($model,'moduleParam',array('size'=>20,'maxlength'=>45)); ?>
    </div>
    <?php $this->endClip(); ?>

    <?php $this->beginClip('p2Info');
    $this->widget('P2InfoInputWidget', array('owner'=>$model));
    $this->endClip(); ?>

    <?php
    $this->widget('zii.widgets.jui.CJuiAccordion', array(
        'panels'=>array(
            Yii::t('P2Module.p2','Extended Options')=>$this->clips['extendedOptions'],
            Yii::t('P2Module.p2','Meta Data')=>$this->clips['p2Info'],
        ),
        // additional javascript options for the accordion plugin
        'options'=>array(
            'active'=>false,
            'collapsible'=>true,
        ),
    )); ?>


    <div class="row buttons">
        <?php echo CHtml::submitButton(
        $update ? Yii::t('P2Module.p2','Save') : Yii::t('P2Module.p2','Create'),
        array('class' => P2Helper::juiButtonCssClass().' submit')
        ); ?>
    </div>

    <?php echo P2Helper::clearfloat(); ?>

    <?php echo CHtml::endForm(); ?>

</div><!-- yiiForm -->