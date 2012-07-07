<h2>View P2Info <?php echo $model->id; ?></h2>

<?php $this->widget('application.modules.p2.components.P2CrudActionBar', array('id'=>$_REQUEST['id'])) ?>
<table class="dataGrid">
<tr>
	<th class="label"><?php echo CHtml::encode($model->getAttributeLabel('model')); ?>
</th>
    <td><?php echo CHtml::encode($model->model); ?>
</td>
</tr>
<tr>
	<th class="label"><?php echo CHtml::encode($model->getAttributeLabel('language')); ?>
</th>
    <td><?php echo CHtml::encode($model->language); ?>
</td>
</tr>
<tr>
	<th class="label"><?php echo CHtml::encode($model->getAttributeLabel('status')); ?>
</th>
    <td><?php echo CHtml::encode($model->status); ?>
</td>
</tr>
<tr>
	<th class="label"><?php echo CHtml::encode($model->getAttributeLabel('type')); ?>
</th>
    <td><?php echo CHtml::encode($model->type); ?>
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
	<th class="label"><?php echo CHtml::encode($model->getAttributeLabel('modifiedBy')); ?>
</th>
    <td><?php echo CHtml::encode($model->modifiedBy); ?>
</td>
</tr>
<tr>
	<th class="label"><?php echo CHtml::encode($model->getAttributeLabel('modifiedAt')); ?>
</th>
    <td><?php echo CHtml::encode($model->modifiedAt); ?>
</td>
</tr>
<tr>
	<th class="label"><?php echo CHtml::encode($model->getAttributeLabel('begin')); ?>
</th>
    <td><?php echo CHtml::encode($model->begin); ?>
</td>
</tr>
<tr>
	<th class="label"><?php echo CHtml::encode($model->getAttributeLabel('end')); ?>
</th>
    <td><?php echo CHtml::encode($model->end); ?>
</td>
</tr>
<tr>
	<th class="label"><?php echo CHtml::encode($model->getAttributeLabel('keywords')); ?>
</th>
    <td><?php echo CHtml::encode($model->keywords); ?>
</td>
</tr>
<tr>
	<th class="label"><?php echo CHtml::encode($model->getAttributeLabel('customData')); ?>
</th>
    <td><?php echo CHtml::encode($model->customData); ?>
</td>
</tr>
</table>
