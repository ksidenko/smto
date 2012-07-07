<h2><?php echo Yii::t('P2Module.p2','HTML').' - '.Yii::t('P2Module.p2','List'); ?></h2>

<div class="actionBar">
    <?php $this->widget('application.modules.p2.components.P2CrudActionBar') ?>
    <?php
    $this->widget('application.modules.p2.components.P2AutoComplete',
        array(
        'name' => 'autoComplete',
        'searchModel' => 'P2Html',
        'mode' => P2AutoComplete::MODE_UPDATE,
        'class' => 'ui-widget',)
    );
    ?>
</div>
<?php echo P2Helper::clearfloat() ?>

<?php $this->widget('CLinkPager',array('pages'=>$pages)); ?>

<?php foreach($models as $n=>$model): ?>
<div class="view">
<?php echo CHtml::encode($model->getAttributeLabel('id')); ?>:
<?php echo CHtml::link($model->id,array('show','id'=>$model->id)); ?>
<br/>
<?php echo CHtml::encode($model->getAttributeLabel('name')); ?>:
<?php echo CHtml::encode($model->name); ?>
<br/>
<?php echo CHtml::encode($model->getAttributeLabel('html')); ?>:
<div><?php echo $model->html; ?></div>
<?php echo P2Helper::clearfloat(); ?>
<br/>
<?php echo CHtml::encode($model->getAttributeLabel('p2_infoId')); ?>:
<?php echo CHtml::encode($model->p2_infoId); ?>
<br/>

</div>
<?php endforeach; ?>
<br/>
<?php $this->widget('CLinkPager',array('pages'=>$pages)); ?>