<div class="form">
    <p>
        Fields with <span class="required">*</span> are required.
    </p>

    <?php echo CHtml::beginForm(); ?>

    <?php echo CHtml::errorSummary($model); ?>

    <div class="row">
        <?php echo CHtml::activeLabelEx($model,'status'); ?>
        <?php echo CHtml::activeDropDownList($model,'status',P2User::statusOptions()) ?>
    </div>
    <div class="row">
        <?php echo CHtml::activeLabelEx($model,'name'); ?>
        <?php echo CHtml::activeTextField($model,'name',array('size'=>45,'maxlength'=>45)); ?>
            <?php if($profileModelName = $this->module->params['p2User.profileModel']): ?>
                <?php 
                $profileModel = new $profileModelName;
                if ($profileModel->model()->findByAttributes(array($this->module->params['p2User.profileModel.fkUser'] => $model->id)))
                echo CHtml::link(
                'Review Profile',
                array(
                '/'.P2Helper::lcfirst($this->module->params['p2User.profileModel']).'/show',
                $this->module->params['p2User.profileModel.fkUser'] => $model->id),
                array('target' => '_blank')
                ); ?>
            <?php endif; ?>
    </div>
    <div class="row">
        <?php echo CHtml::activeLabelEx($model,'firstName'); ?>
        <?php echo CHtml::activeTextField($model,'firstName',array('size'=>60,'maxlength'=>100)); ?>
    </div>
    <div class="row">
        <?php echo CHtml::activeLabelEx($model,'lastName'); ?>
        <?php echo CHtml::activeTextField($model,'lastName',array('size'=>60,'maxlength'=>100)); ?>
    </div>
    <div class="row">
        <?php echo CHtml::activeLabelEx($model,'eMail'); ?>
        <?php echo CHtml::activeTextField($model,'eMail',array('size'=>60,'maxlength'=>100)); ?>
    </div>

    <div class="row">
        <?php echo CHtml::activeLabelEx($model,'newpassword'); ?>
        <?php echo CHtml::activePasswordField($model,'newpassword',array('size'=>45,'maxlength'=>45)); ?><br/>
    </div>

    <div class="row">
        <?php echo CHtml::activeLabelEx($model, 'repeatpassword'); ?>
        <?php echo CHtml::activePasswordField($model,'repeatpassword',array('size'=>45,'maxlength'=>45)); ?>
        <small><?php echo Yii::t('P2Module.p2','Repeat password please.') ?></small>
    </div>

    <?php echo CHtml::hiddenField('return_url', P2Helper::return_url()); ?>

    <div class="action">
        <?php echo CHtml::submitButton($update ? 'Save' : 'Create'); ?>
    </div>

    <?php echo CHtml::endForm(); ?>

</div><!-- yiiForm -->