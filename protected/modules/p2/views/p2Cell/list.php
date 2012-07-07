<h2><?php echo Yii::t('P2Module.p2','Cells').' - '.Yii::t('P2Module.p2','List'); ?></h2>

<div class="actionBar">
    <?php $this->widget('application.modules.p2.components.P2CrudActionBar') ?>
    <?php
    $this->widget('application.modules.p2.components.P2AutoComplete',
        array(
        'name' => 'autoComplete',
        'searchModel' => 'P2Cell',
        'mode' => P2AutoComplete::MODE_UPDATE,
        'class' => 'ui-widget',)
    );
    ?>
</div>
<?php echo P2Helper::clearfloat() ?>

<?php $this->widget('CLinkPager',array('pages'=>$pages)); ?>

<?php foreach($models as $n=>$model): ?>
<div class="item">
<?php echo CHtml::encode($model->getAttributeLabel('id')); ?>:
<?php echo CHtml::link($model->id,array('show','id'=>$model->id)); ?>
<br/>
<?php echo CHtml::encode($model->getAttributeLabel('classPath')); ?>:
<?php echo CHtml::encode($model->classPath); ?>
<br/>
<?php echo CHtml::encode($model->getAttributeLabel('classProps')); ?>:
<?php echo CHtml::encode($model->classProps); ?>
<br/>
<?php echo CHtml::encode($model->getAttributeLabel('rank')); ?>:
<?php echo CHtml::encode($model->rank); ?>
<br/>
<?php echo CHtml::encode($model->getAttributeLabel('cellId')); ?>:
<?php echo CHtml::encode($model->cellId); ?>
<br/>
<?php echo CHtml::encode($model->getAttributeLabel('moduleId')); ?>:
<?php echo CHtml::encode($model->moduleId); ?>
<br/>
<?php echo CHtml::encode($model->getAttributeLabel('controllerId')); ?>:
<?php echo CHtml::encode($model->controllerId); ?>
<br/>
<?php echo CHtml::encode($model->getAttributeLabel('actionName')); ?>:
<?php echo CHtml::encode($model->actionName); ?>
<br/>
<?php echo CHtml::encode($model->getAttributeLabel('requestParam')); ?>:
<?php echo CHtml::encode($model->requestParam); ?>
<br/>
<?php echo CHtml::encode($model->getAttributeLabel('sessionParam')); ?>:
<?php echo CHtml::encode($model->sessionParam); ?>
<br/>
<?php echo CHtml::encode($model->getAttributeLabel('cookieParam')); ?>:
<?php echo CHtml::encode($model->cookieParam); ?>
<br/>
<?php echo CHtml::encode($model->getAttributeLabel('applicationParam')); ?>:
<?php echo CHtml::encode($model->applicationParam); ?>
<br/>
<?php echo CHtml::encode($model->getAttributeLabel('moduleParam')); ?>:
<?php echo CHtml::encode($model->moduleParam); ?>
<br/>
<?php echo CHtml::encode($model->getAttributeLabel('p2_infoId')); ?>:
<?php echo CHtml::encode($model->p2_infoId); ?>
<br/>

</div>
<?php endforeach; ?>
<br/>
<?php $this->widget('CLinkPager',array('pages'=>$pages)); ?>