<?php
/**
* This is the template for generating the form view for crud.
* The following variables are available in this template:
* - $ID: the primary key name
* - $modelClass: the model class name
* - $columns: a list of column schema objects
*/
?>
<div class="form">
    <p>
        <?php echo "<?php echo Yii::t('P2Module.p2','Fields with <span class=\"required\">*</span> are required.') ?>"; ?>
    </p>

    <?php echo "<?php echo CHtml::beginForm(); ?>\n"; ?>

    <?php echo "<?php echo CHtml::errorSummary(\$model); ?>\n"; ?>

    <?php echo "<?php echo \$this->renderPartial('_form-inputs', array(
	'model'=>\$model,
	'update'=>\$update,
)); ?>";?>

    <?php echo "<?php echo CHtml::hiddenField('return_url', P2Helper::return_url()); ?>\n"; ?>

    <div class="row buttons">
<?php echo "<?php echo CHtml::submitButton(\$update ? 'Save' : 'Create'); ?>\n"; ?>
    </div>

<?php echo "<?php echo CHtml::endForm(); ?>\n"; ?>

</div><!-- yiiForm -->