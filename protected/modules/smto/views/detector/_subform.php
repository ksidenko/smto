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
		<?php echo $form->dropDownList($model, $index . 'type', array('digit' => 'Цифровой', 'analog' => 'Аналоговый'), array('disabled'=>'true')); ?>
		<?php echo $form->error($model, $index . 'type'); ?>
	</td>
    
	<td width="50">
		<?php //echo $form->labelEx($model, $index . 'status'); ?>
		<?php echo $form->checkBox($model, $index . 'status'); ?>
		<?php echo $form->error($model, $index . 'status'); ?>
    </td>
</tr>
