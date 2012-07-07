<h2><?php echo Yii::t('P2Module.p2','Info').' - '.Yii::t('P2Module.p2','Create'); ?></h2>

<?php $this->widget('application.modules.p2.components.P2CrudActionBar') ?>
<?php echo $this->renderPartial('_form', array(
	'model'=>$model,
	'update'=>false,
)); ?>