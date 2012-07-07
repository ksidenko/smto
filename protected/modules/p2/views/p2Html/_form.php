<div class="form">
    <p>
        Fields with <span class="required">*</span> are required.
    </p>

    <?php echo CHtml::beginForm(); ?>

    <?php echo CHtml::errorSummary($model); ?>

    <div class="span-17">
        <div class="row">
            <?php echo CHtml::activeLabelEx($model,'name'); ?>
            <?php echo CHtml::activeTextField($model,'name',array('size'=>60,'maxlength'=>64)); ?>
            <br/>
            <?php $this->widget('P2NameNormalizer', array('selector' => '#P2Html_name')); ?>
            <span class="hint"><?php echo Yii::t('P2Module.p2','A unique identifier for this item.') ?></span>
        </div>

        <div class="row">
            <?php echo CHtml::activeLabelEx($model,'html'); ?>
            <?php echo CHtml::activeTextArea($model,'html',array('rows'=>6, 'cols'=>50)); ?>
            <?php
            $this->widget(
                'application.modules.p2.extensions.ckeditor.EP2Ckeditor',
                array(
                'id'=>'ck',
                'name'=>'P2Html[html]',
                'config'=>P2Helper::configure('ckeditor:config', Yii::app()->params['ckeditor'])
                )
            );
            ?>
            <span class="hint"><?php echo Yii::t('P2Module.p2','Item content with markup.') ?></span>
        </div>
        <div class="row buttons">
            <?php echo CHtml::submitButton($update ? 'Save' : 'Create'); ?>
        </div>
    </div>

    <div class="span-7 last">

        <?php $this->widget('P2InfoInputWidget', array('owner'=>$model)); ?>

    </div>
    <?php echo CHtml::endForm(); ?>

</div><!-- yiiForm -->