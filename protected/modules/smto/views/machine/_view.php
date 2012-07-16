<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('name')); ?>:</b>
	<?php echo CHtml::encode($data->name); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('code')); ?>:</b>
	<?php echo CHtml::encode($data->code); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('ip')); ?>:</b>
	<?php echo CHtml::encode($data->ip); ?>
	<br />

    <b><?php echo CHtml::encode($data->getAttributeLabel('port')); ?>:</b>
    <?php echo CHtml::encode($data->port); ?>
    <br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('local_port')); ?>:</b>
	<?php echo CHtml::encode($data->local_port); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('pwd')); ?>:</b>
	<?php echo CHtml::encode($data->pwd); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('s_values')); ?>:</b>
	<?php echo CHtml::encode($data->s_values); ?>
	<br />

    <b><?php echo CHtml::encode($data->getAttributeLabel('reasons_timeout_table')); ?>:</b>
    <?php echo CHtml::encode($data->reasons_timeout_table); ?>
    <br />

</div>