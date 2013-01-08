<?php 
    $index = '['. $index . ']' ;
    $errors =  $form->errorSummary($model); 
    if (!empty($errors)) { ?>
        <tr><td><?php echo $errors;?></td></tr>
    <?php }    
?>
<tr>
	<td>    
        <?php echo $form->hiddenField($model, $index . 'id'); ?>
        <?php echo $form->hiddenField($model, $index . 'number'); ?>
        <?php echo $form->hiddenField($model, $index . 'code'); ?>
        
		<?php echo $model->number; ?>
	</td>

	<td>
		<?php //echo $form->labelEx($model,$index . 'code'); ?>
		<?php //echo $form->textField($model,$index . 'code',array('size'=>7,'maxlength'=>32)); ?>
        <?php echo $model->code; ?>
		<?php echo $form->error($model,$index . 'code'); ?>
	</td>

	<td>
        <?php if ($model->machine_event)  { ?>
		<?php //echo $form->labelEx($model,$index . 'color'); ?>
        <?php echo CHtml::label($model->machine_event->color, '', array('size'=>12,'maxlength'=>12, 'style' =>
            'background-color: #' . $model->machine_event->color
        )); ?>
		<?php //echo $form->textField($model->machine_event,$index . 'color',array('size'=>12,'maxlength'=>12)); ?>
		<?php //echo $form->error($model->machine_event,$index . 'color'); ?>
        <?php } ?>
	</td>

    <td>
        <?php echo $form->dropDownList($model, $index . 'machine_event_id', array(-1 => '') + CHtml::listData(MachineEvent::getList(), 'id', 'name')) ?>
        <?php echo $form->error($model, $index . 'machine_event_id'); ?>
    </td>

	<td>
		<?php //echo $form->labelEx($model,$index . 'type'); ?>
		<?php //echo $form->dropDownList($model, $index . 'type', array('work' => 'Работа', 'valid' => 'Обоснованный простой', 'not_valid' => 'Необоснованный простой'), array()); ?>
		<?php //echo $form->error($model,$index . 'type'); ?>
	</td>

	<td>
		<?php //echo $form->textField($model, $index . 'descr', array('size'=>20)); ?>
		<?php //echo $form->error($model,$index . 'descr'); ?>
	</td>

	<td>
		<?php //echo $form->labelEx($model,$index . 'status'); ?>
		<?php echo $form->checkBox($model,$index . 'status'); ?>
		<?php echo $form->error($model,$index . 'status'); ?>
	</td>
</tr>