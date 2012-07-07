<h2>P2Log</h2>


<?php #$this->widget('application.modules.p2.components.P2CrudActionBar') ?>

<?php $this->widget('CLinkPager',array('pages'=>$pages)); ?>
<br/><br/>
<?php foreach($models as $n=>$model): ?>
<div class="item">
        <?php echo CHtml::link($model->id,array('show','id'=>$model->id)); ?>

    <i style="padding: 0 3em"><?php echo CHtml::encode($model->createdAt); ?> </i>
        <?php echo CHtml::encode($model->description); ?>
    <!--
    <br/>
        <?php echo CHtml::encode($model->getAttributeLabel('action')); ?>:
        <?php echo CHtml::encode($model->action); ?>
    <br/>
        <?php echo CHtml::encode($model->getAttributeLabel('model')); ?>:
        <?php echo CHtml::encode($model->model); ?>
    <br/>
        <?php echo CHtml::encode($model->getAttributeLabel('modelId')); ?>:
        <?php echo CHtml::encode($model->modelId); ?>
    <br/>
        <?php echo CHtml::encode($model->getAttributeLabel('changes')); ?>:
        <?php echo CHtml::encode($model->changes); ?>
    <br/>
        <?php echo CHtml::encode($model->getAttributeLabel('createdBy')); ?>:
        <?php echo CHtml::encode($model->createdBy); ?>
    <br/>
    <br/>
        <?php echo CHtml::encode($model->getAttributeLabel('data')); ?>:
        <?php echo CHtml::encode($model->data); ?>
    <br/>
    -->
</div>
<?php endforeach; ?>
<br/>
<?php $this->widget('CLinkPager',array('pages'=>$pages)); ?>