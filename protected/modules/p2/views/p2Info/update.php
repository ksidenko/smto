<h2>Update P2Info <?php echo $model->id; ?></h2>

<?php $this->widget('application.modules.p2.components.P2CrudActionBar', array('id'=>$_REQUEST['id'])) ?>
<?php echo $this->renderPartial('_form', array(
	'model'=>$model,
	'update'=>true,
)); ?>