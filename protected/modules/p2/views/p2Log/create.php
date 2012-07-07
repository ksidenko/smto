<h2>New P2Log</h2>
<div class="actionBar">
<?php $this->widget('application.modules.p2.components.P2CrudActionBar') ?>
    <?php echo P2Helper::clearfloat() ?>
</div>
<?php echo $this->renderPartial('_form', array(
	'model'=>$model,
	'update'=>false,
)); ?>