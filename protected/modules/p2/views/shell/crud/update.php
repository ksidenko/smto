<?php
/**
 * This is the template for generating the update view for crud.
 * The following variables are available in this template:
 * - $ID: the primary key name
 * - $modelClass: the model class name
 * - $columns: a list of column schema objects
 */
?>
<h2>
<?php echo "<?php echo Yii::t('P2Module.p2','".$modelClass."'); ?> -"?>
<?php echo " <?php echo Yii::t('P2Module.p2','Update').' #'.\$model->{$ID}; ?>" ?>
</h2>


<?php echo "<div class='actionBar'><?php \$this->widget(
    'application.modules.p2.components.P2CrudActionBar', array('id'=>\$_REQUEST['id'])) ?>"; ?>
<?php echo "<?php echo P2Helper::clearfloat(); ?>"; ?>
<?php echo "</div>" ?>

<?php echo "<?php echo \$this->renderPartial('_form', array(
	'model'=>\$model,
	'update'=>true,
)); ?>"; ?>