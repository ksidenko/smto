<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('name')); ?>:</b>
	<?php echo CHtml::encode($data->name); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('time_from')); ?>:</b>
	<?php echo CHtml::encode($data->time_from); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('time_to')); ?>:</b>
	<?php echo CHtml::encode($data->time_to); ?>
	<br />


</div>