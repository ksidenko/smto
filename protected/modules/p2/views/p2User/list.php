<h2>P2User List</h2>


<div class="actionBar">
    <?php $this->widget('application.modules.p2.components.P2CrudActionBar') ?>
    <?php
    $this->widget('application.modules.p2.components.P2AutoComplete',
        array(
        'name' => 'autoComplete',
        'searchModel' => 'P2User',
        'mode' => P2AutoComplete::MODE_UPDATE,
        'class' => 'ui-widget',)
    );
    ?>
</div>

<?php $this->widget('CLinkPager',array('pages'=>$pages)); ?>

<?php foreach($models as $n=>$model): ?>
<div class="item">
<?php echo CHtml::encode($model->getAttributeLabel('id')); ?>:
<?php echo CHtml::link($model->id,array('show','id'=>$model->id)); ?>
<br/>
<?php echo CHtml::encode($model->getAttributeLabel('name')); ?>:
<?php echo CHtml::encode($model->name); ?>
<br/>
<?php echo CHtml::encode($model->getAttributeLabel('firstName')); ?>:
<?php echo CHtml::encode($model->firstName); ?>
<br/>
<?php echo CHtml::encode($model->getAttributeLabel('lastName')); ?>:
<?php echo CHtml::encode($model->lastName); ?>
<br/>
<?php echo CHtml::encode($model->getAttributeLabel('eMail')); ?>:
<?php echo CHtml::encode($model->eMail); ?>
<br/>

</div>
<?php endforeach; ?>
<br/>
<?php $this->widget('CLinkPager',array('pages'=>$pages)); ?>