<?php
/**
 * This is the template for generating the show view for crud.
 * The following variables are available in this template:
 * - $ID: the primary key name
 * - $modelClass: the model class name
 * - $columns: a list of column schema objects
 */
?>
<h2>
<?php echo "<?php echo Yii::t('P2Module.p2','".$modelClass."'); ?> -"?>
<?php echo " <?php echo Yii::t('P2Module.p2','View').' #'.\$model->{$ID}; ?>" ?>
</h2>

<?php echo "<div class='actionBar'><?php \$this->widget(
    'application.modules.p2.components.P2CrudActionBar', array('id'=>\$_REQUEST['id'])) ?>"; ?>
<?php echo "<?php echo P2Helper::clearfloat(); ?>"; ?>
<?php echo "</div>" ?>

<table class="dataGrid">
<?php foreach($columns as $name=>$column): ?>
<tr>
	<th class="label"><?php echo "<?php echo CHtml::encode(\$model->getAttributeLabel('$name')); ?>\n"; ?></th>
    <td><?php echo "<?php echo ".$this->generateOutputField($modelClass,$column)."; ?>\n"; ?></td>
</tr>
<?php endforeach; ?>


<?php
$model = new $modelClass;
foreach($model->relations() AS $name => $relation):
    if ($relation[0] === CActiveRecord::BELONGS_TO) continue;
?>
    <tr>
        <th class="label"><?php echo "<?php echo ".$this->generateRelationLabel($modelClass,$name)."; ?>\n"; ?></th>
        <td><?php echo "<?php echo ".$this->generateRelationField($modelClass,$name,$relation)."; ?>\n"; ?></td>
    </tr>
<?php endforeach; ?>

</table>
