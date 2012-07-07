<h2><?php echo Yii::t('P2Module.p2','Files').' - '.Yii::t('P2Module.p2','List'); ?></h2>

<div class="actionBar">
    <?php $this->widget('application.modules.p2.components.P2CrudActionBar') ?>
    <?php
    $this->widget('application.modules.p2.components.P2AutoComplete',
        array(
        'name' => 'autoComplete',
        'searchModel' => 'P2File',
        'mode' => P2AutoComplete::MODE_UPDATE,
        'class' => 'ui-widget',)
    );
    ?>
</div>
<?php echo P2Helper::clearfloat() ?>

<?php $this->widget('CLinkPager',array('pages'=>$pages)); ?>
<?php echo P2Helper::clearfloat() ?>
<?php foreach($models as $n=>$model): ?>
<div class="imageSelector">

<div class="imageContainer">
      <?php echo CHtml::link(CHtml::image(CController::createUrl('/p2/p2File/image',array('id'=>$model->id,'preset'=>'fckbrowse'))),array('show','id'=>$model->id));?>
</div>
<div class="info">
<?php echo CHtml::link($model->id,array('show','id'=>$model->id)); ?>
<br/>
<?php echo CHtml::encode($model->name); ?>
<br/>
<?php echo CHtml::encode($model->fileType); ?>
</div>

</div>
<?php endforeach; ?>
<?php echo P2Helper::clearfloat() ?>
<?php $this->widget('CLinkPager',array('pages'=>$pages)); ?>