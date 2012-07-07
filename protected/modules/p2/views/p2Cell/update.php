<h2><?php echo Yii::t('P2Module.p2','Cells').' - '.Yii::t('P2Module.p2','Update').' #'.$model->id; ?></h2>

<div class="actionBar">
    <?php $this->widget('application.modules.p2.components.P2CrudActionBar', array('id'=>$_REQUEST['id'])) ?>
    <?php
    $this->widget('application.modules.p2.components.P2AutoComplete',
        array(
        'name' => 'autoComplete',
        'searchModel' => 'P2Cell',
        'mode' => P2AutoComplete::MODE_UPDATE,
        'class' => 'ui-widget',)
    );
    ?>
</div>
<?php echo P2Helper::clearfloat() ?>

<?php echo $this->renderPartial('_form', array(
	'model'=>$model,
	'update'=>true,
)); ?>