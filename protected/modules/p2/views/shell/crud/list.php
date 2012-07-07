<?php
/**
 * This is the template for generating the list view for crud.
 * The following variables are available in this template:
 * - $ID: the primary key name
 * - $modelClass: the model class name
 * - $columns: a list of column schema objects
 */
?>
<h2>
<?php echo "<?php echo Yii::t('P2Module.p2','".$modelClass."'); ?> -"?>
<?php echo " <?php echo Yii::t('P2Module.p2','List') ?>" ?>
</h2>


<?php echo "<div class='actionBar'><?php \$this->widget(
    'application.modules.p2.components.P2CrudActionBar') ?>"; ?>
<?php echo "<?php echo P2Helper::clearfloat(); ?>"; ?>
<?php echo "</div>" ?>


<?php echo "<?php \$this->widget('CLinkPager',array('pages'=>\$pages)); ?>" ?>


<?php echo "<?php foreach(\$models as \$n=>\$model): ?>\n"; ?>
<div class="view">
    <h3>
        <?php echo "<?php echo CHtml::link('# '.\$model->{$ID},array('show','id'=>\$model->{$ID})); ?>"; ?>
    </h3>


<?php foreach($columns as $column): ?>
    <?php if($column->name == $ID) continue; ?>
    <p>
<?php echo "<?php echo CHtml::encode(\$model->getAttributeLabel('{$column->name}')); ?>"; ?><br/>
<?php echo "<?php echo ".$this->generateOutputField($modelClass,$column)."; ?>\n"; ?>
    </p>
<?php endforeach; ?>

<?php
$model = new $modelClass;
foreach($model->relations() AS $name => $relation):
    if ($relation[0] === CActiveRecord::BELONGS_TO) continue;
?>
    <p>
        <?php echo "<?php echo ".$this->generateRelationLabel($modelClass,$name)."; ?>\n"; ?><br/>
        <?php echo "<?php echo ".$this->generateRelationField($modelClass,$name,$relation)."; ?>\n"; ?>
   <p>
<?php endforeach; ?>

</div>
<?php echo "<?php endforeach; ?>\n"; ?>
<br/>
<?php echo "<?php \$this->widget('CLinkPager',array('pages'=>\$pages)); ?>" ?>
