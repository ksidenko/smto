<?php $uniqueId = 'p2HtmlWidgetAdmin'.uniqid(); ?>
<?php $htmlAreaId = 'editor'.uniqid(); ?>
<?php $context = "#{$uniqueId}"; ?>


<!-- BEGIN CLIPS -->

<div  id="<?php echo $uniqueId ?>">

    <!-- CREATE -->
    <?php $this->beginWidget('system.web.widgets.CClipWidget', array('id'=>'createTab')); ?>
    <div class="createInput yiiForm form">
        <?php echo CHtml::beginForm('p2/p2Html/create'); ?>
        <?php echo CHtml::hiddenField('return_url', P2Helper::return_url()); ?>
        <?php echo CHtml::hiddenField('modelName', get_class($model)); ?>

        <?php echo CHtml::errorSummary($model); ?>

        <div class="span">
            <?php echo CHtml::activeLabelEx($model,'name'); ?>
            <?php echo CHtml::activeTextField($model,'name',array(
            'size'=>30,'maxlength'=>64,
            )); ?>
            <br/>
            <?php $this->widget('P2NameNormalizer', array('selector' => '#P2Html_name')); ?>
        </div>

        <div class="span">
            <?php /**/echo CHtml::activeLabelEx($model,'html'); ?>
            <div>
                <?php echo CHtml::activeTextArea($model,'html',array('id'=> $uniqueId.'textArea' ,'rows'=>6, 'cols'=>50)); ?>
                <?php
                $this->widget(
                    'application.modules.p2.extensions.ckeditor.EP2Ckeditor',
                    array(
                    'id'=>'ck'.uniqid(),
                    'name'=> $uniqueId.'textArea',
                    'config'=>P2Helper::configure('ckeditor:config', Yii::app()->params['ckeditor'])
                    )
                );
                ?>
            </div>
        </div>

        <div class="span">
            <?php

            echo CHtml::ajaxSubmitButton(
            ($update ? Yii::t('P2Module.p2','Save Widget Data') : Yii::t('P2Module.p2','Create Widget Data')),
            Yii::app()->createUrl('/p2/p2Html/create'),
            array(
            'type'=>'POST', //request type
            'dataType' => 'json',
            'beforeSend' => 'function(request){
                    '.P2Helper::configure('jquery:loadingStart','#'.$uniqueId.' .createInput').'
                    $(".createInput *", $("#'.$uniqueId.'")).attr("disabled",true);
                    $("#'.$uniqueId.'Summary").removeClass().addClass("statusSummary").text("'.Yii::t('P2Module.p2','Sending request ...').'");
                }',
            'complete' => 'function(){
                    '.P2Helper::configure('jquery:loadingEnd','.createInput').'
                }',
            'success'=>'function(data,textStatus){
                    // update status text
                    $("#'.$uniqueId.'Summary").removeClass().addClass("successSummary").text("'.Yii::t('P2Module.p2','Created record').' #"+data[1]);
                    // insert id into classProps field
                    $("#P2Html_id", $("#'.$uniqueId.'")).val(data[1]);
                    // hide form
                    $("#tabView", $("#'.$uniqueId.'")).slideUp("normal");
                    // update text status
                    $(".widgetStatus", $("#'.$uniqueId.'")).val('.P2CellManager::WIDGET_STATUS_SAVED.');
                }',
            'error'=>'function(data,textStatus){
                    $("#'.$uniqueId.'Summary").removeClass().addClass("errorSummary").text("'.Yii::t('P2Module.p2','Error').'");
                    $(".createInput *", $("#'.$uniqueId.'")).attr("disabled",false);
                    $(".'.P2CellManager::CSSCLASS_INPUT.'", $("#'.$uniqueId.'")).val("");
                    alert(data.responseText);
                }',
            ),
            array(
            'class' => P2Helper::juiButtonCssClass(),
            'name' => $uniqueId.'submit',
            'onclick' => '$("#'.$uniqueId.'textArea").val(CKEDITOR.instances["'.$uniqueId.'textArea"].getData());',
            )
            );

            ?>
        </div>
        <?php echo CHtml::endForm(); ?>
    </div>
    <?php $this->endWidget(); ?>



    <!-- SELECT -->
    <?php $this->beginWidget(
        'system.web.widgets.CClipWidget',
        array('id'=>'selectTab')); ?>
    <div class="yiiForm">
        <?php echo CHtml::beginForm('p2/p2Html/create'); ?>
        <?php echo CHtml::errorSummary($model); ?>

        <div class="complex">
            <span><?php echo Yii::t('P2Module.p2', 'Id');?></span>
            <?php $this->widget(
                'P2AutoComplete',
                array(
                'name'=> 'selectedId',
                'searchModel'=>'P2Html')) ?>
        </div>
        <div class="action">
            <?php echo CHtml::button(
            Yii::t('P2Module.p2','Select'), array(
            'class' => P2Helper::juiButtonCssClass(),
            'onclick' => '
                $(".widgetStatus", $("#'.$uniqueId.'")).val('.P2CellManager::WIDGET_STATUS_SAVED.');
                $("#P2Html_id").val($("#selectedId").val());
                $("#'.$uniqueId.'Summary").removeClass().addClass("successSummary").text("'.Yii::t('P2Module.p2','Selected record').' #"+$("#selectedId").val());
           '))
            ?>
        </div>

        <?php echo CHtml::endForm(); ?>
    </div>
    <?php $this->endWidget(); ?>
    <!-- yiiForm -->

<!-- END CLIPS -->


    <h3><?php echo Yii::t('P2Module.p2','P2HtmlWidget') ?></h3>

    <div class="p2HtmlWidgetStatus" style="clear:both;">
        <div id="<?php echo $uniqueId ?>Summary" class="infoSummary">
        <?php echo Yii::t('P2Module.p2',"No record selected or created yet."); ?></div>
        <?php echo CHtml::hiddenField(
        'status',
        P2CellManager::WIDGET_STATUS_UNSAVED,
        array('class' => P2CellManager::CSSCLASS_WIDGET_STATUS,)
        ); ?>
    </div>


    <div>

        <div class="classProps" style="display:none">
            <h3>Debug</h3>
            <?php
            echo CHtml::activeTextField(
            $model,
            'id');
            echo CHtml::hiddenField(
            'modelClass',
            'P2Html');// select only 'id'
            echo CHtml::hiddenField(
            'modelAttributesPattern',
            'id'); ?>
        </div>


        <?php
        $tabParameters = array();
        $clipList = array(
            'createTab' => Yii::t('P2Module.p2','Create'),
            'selectTab' => Yii::t('P2Module.p2','Select') );
        foreach($clipList as $key => $value) {
            $tabParameters[$value] = array('title'=>$value, 'content'=>$this->clips[$key]);
        }
        ?>

        <div class="p2CellManager">
            <?php
            $this->widget(
                'system.zii.widgets.jui.CJuiTabs',
                array(
                'id'=>"tabView",
                'tabs'=>$tabParameters,
                'cssFile' => P2Helper::configure('cTabView:cssFile'),
                )
            );
            ?>
        </div>

    </div>

</div>