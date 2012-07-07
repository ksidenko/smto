<h2><?php echo Yii::t('P2Module.p2','Pages').' - '.Yii::t('P2Module.p2','List'); ?></h2>


<div class="actionBar">
    <?php $this->widget('application.modules.p2.components.P2CrudActionBar', array('extraLinks' => array(
            'Sitemap' => array('/p2/p2Page/sitemap')
        )
        )) ?>
    <?php
    $this->widget('application.modules.p2.components.P2AutoComplete',
        array(
        'name' => 'autoComplete',
        'searchModel' => 'P2Page',
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
<?php echo CHtml::encode($model->getAttributeLabel('parentId')); ?>:
<?php echo CHtml::encode($model->parentId); ?>
<br/>
<?php echo CHtml::encode($model->getAttributeLabel('descriptiveName')); ?>:
<?php echo CHtml::encode($model->descriptiveName); ?>
<br/>
<?php echo CHtml::encode($model->getAttributeLabel('url')); ?>:
<?php echo CHtml::encode($model->url); ?>
<br/>
<?php echo CHtml::encode($model->getAttributeLabel('layout')); ?>:
<?php echo CHtml::encode($model->layout); ?>
<br/>
<?php echo CHtml::encode($model->getAttributeLabel('view')); ?>:
<?php echo CHtml::encode($model->view); ?>
<br/>
<?php echo CHtml::encode($model->getAttributeLabel('replaceMethod')); ?>:
<?php echo CHtml::encode($model->replaceMethod); ?>
<br/>
<?php echo CHtml::encode($model->getAttributeLabel('rank')); ?>:
<?php echo CHtml::encode($model->rank); ?>
<br/>
<?php echo CHtml::encode($model->getAttributeLabel('p2_infoId')); ?>:
<?php echo CHtml::encode($model->p2_infoId); ?>
<br/>

</div>
<?php endforeach; ?>
<br/>
<?php $this->widget('CLinkPager',array('pages'=>$pages)); ?>