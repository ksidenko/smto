<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('pid')); ?>:</b>
	<?php echo CHtml::encode($data->pid); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('machine_id')); ?>:</b>
	<?php echo CHtml::encode($data->machine_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('status')); ?>:</b>
	<?php echo CHtml::encode($data->status); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('dt_create')); ?>:</b>
	<?php echo CHtml::encode($data->dt_create); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('dt_check')); ?>:</b>
	<?php echo CHtml::encode($data->dt_check); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('progress')); ?>:</b>
	<?php echo CHtml::encode($data->progress); ?>
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('error')); ?>:</b>
	<?php echo CHtml::encode($data->error); ?>
	<br />

	*/ ?>

</div>