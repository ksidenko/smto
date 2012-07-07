<h2>P2Info List</h2>


<?php $this->widget('application.modules.p2.components.P2CrudActionBar') ?>
<?php $this->widget('CLinkPager',array('pages'=>$pages)); ?>

<?php foreach($models as $n=>$model): ?>
<div class="item">
<?php echo CHtml::encode($model->getAttributeLabel('id')); ?>:
<?php echo CHtml::link($model->id,array('show','id'=>$model->id)); ?>
<br/>
<?php echo CHtml::encode($model->getAttributeLabel('model')); ?>:
<?php echo CHtml::encode($model->model); ?>
<br/>
<?php echo CHtml::encode($model->getAttributeLabel('language')); ?>:
<?php echo CHtml::encode($model->language); ?>
<br/>
<?php echo CHtml::encode($model->getAttributeLabel('status')); ?>:
<?php echo CHtml::encode($model->status); ?>
<br/>
<?php echo CHtml::encode($model->getAttributeLabel('type')); ?>:
<?php echo CHtml::encode($model->type); ?>
<br/>
<?php echo CHtml::encode($model->getAttributeLabel('createdBy')); ?>:
<?php echo CHtml::encode($model->createdBy); ?>
<br/>
<?php echo CHtml::encode($model->getAttributeLabel('createdAt')); ?>:
<?php echo CHtml::encode($model->createdAt); ?>
<br/>
<?php echo CHtml::encode($model->getAttributeLabel('modifiedBy')); ?>:
<?php echo CHtml::encode($model->modifiedBy); ?>
<br/>
<?php echo CHtml::encode($model->getAttributeLabel('modifiedAt')); ?>:
<?php echo CHtml::encode($model->modifiedAt); ?>
<br/>
<?php echo CHtml::encode($model->getAttributeLabel('begin')); ?>:
<?php echo CHtml::encode($model->begin); ?>
<br/>
<?php echo CHtml::encode($model->getAttributeLabel('end')); ?>:
<?php echo CHtml::encode($model->end); ?>
<br/>
<?php echo CHtml::encode($model->getAttributeLabel('keywords')); ?>:
<?php echo CHtml::encode($model->keywords); ?>
<br/>
<?php echo CHtml::encode($model->getAttributeLabel('customData')); ?>:
<?php echo CHtml::encode($model->customData); ?>
<br/>

</div>
<?php endforeach; ?>
<br/>
<?php $this->widget('CLinkPager',array('pages'=>$pages)); ?>