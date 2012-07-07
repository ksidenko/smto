<?php 
    $index = '['. $index . ']' ;
    $errors =  $form->errorSummary($model); 
    if (!empty($errors)) { ?>
        <tr><td><?php echo $errors;?></td></tr>
    <?php }    
?>
<tr>
	<td width="50">  
        <?php echo $form->hiddenField($model, $index . 'id'); ?>
        <?php echo $form->hiddenField($model, $index . 'number'); ?>
        <?php echo $form->hiddenField($model, $index . 'type'); ?>
        
		<?php echo $model->number; ?>
    </td>

	<td width="50">
		<?php //echo $form->labelEx($model, $index . 'type'); ?>
		<?php echo $form->dropDownList($model, $index . 'type', array('zero' => 'Ноль', 'idle_run' => 'Холостой ход'), array( 'disabled'=>'true')); ?>
		<?php echo $form->error($model, $index . 'type'); ?> 
	</td>
    
	<td width="50">
		<?php //echo $form->labelEx($model, $index . 'value'); ?>
		<?php echo $form->textField($model, $index . 'value',array('size'=>2,'maxlength'=>4)); ?>
		<?php echo $form->error($model, $index . 'value'); ?>
    </td>
</tr>
