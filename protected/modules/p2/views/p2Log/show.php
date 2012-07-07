<h2>View P2Log <?php echo $model->id; ?></h2>
<div class="actionBar">
<?php $this->widget('application.modules.p2.components.P2CrudActionBar', array('id'=>$_REQUEST['id'])) ?>
    <?php echo P2Helper::clearfloat() ?>
    </div>
<table class="dataGrid">
<tr>
	<th class="label"><?php echo CHtml::encode($model->getAttributeLabel('description')); ?>
</th>
    <td><?php echo CHtml::encode($model->description); ?>
</td>
</tr>
<tr>
	<th class="label"><?php echo CHtml::encode($model->getAttributeLabel('action')); ?>
</th>
    <td><?php echo CHtml::encode($model->action); ?>
</td>
</tr>
<tr>
	<th class="label"><?php echo CHtml::encode($model->getAttributeLabel('model')); ?>
</th>
    <td><?php echo CHtml::encode($model->model); ?>
</td>
</tr>
<tr>
	<th class="label"><?php echo CHtml::encode($model->getAttributeLabel('modelId')); ?>
</th>
    <td><?php echo CHtml::encode($model->modelId); ?>
</td>
</tr>
<tr>
	<th class="label"><?php echo CHtml::encode($model->getAttributeLabel('changes')); ?>
</th>
    <td><?php echo CHtml::encode($model->changes); ?>
</td>
</tr>
<tr>
	<th class="label"><?php echo CHtml::encode($model->getAttributeLabel('createdBy')); ?>
</th>
    <td><?php echo CHtml::encode($model->createdBy); ?>
</td>
</tr>
<tr>
	<th class="label"><?php echo CHtml::encode($model->getAttributeLabel('createdAt')); ?>
</th>
    <td><?php echo CHtml::encode($model->createdAt); ?>
</td>
</tr>
<tr>
	<th class="label"><?php echo CHtml::encode($model->getAttributeLabel('data')); ?>
</th>
    <td><?php echo CHtml::encode($model->data); ?>
</td>
</tr>
</table>
