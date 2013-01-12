<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('c1')); ?>:</b>
	<?php echo CHtml::encode($data->c1); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('c2')); ?>:</b>
	<?php echo CHtml::encode($data->c2); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('c3')); ?>:</b>
	<?php echo CHtml::encode($data->c3); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('full_name')); ?>:</b>
	<?php echo CHtml::encode($data->full_name); ?>
	<br />

<!--	<b>--><?php //echo CHtml::encode($data->getAttributeLabel('phone')); ?><!--:</b>-->
<!--	--><?php //echo CHtml::encode($data->phone); ?>
<!--	<br />-->


</div>