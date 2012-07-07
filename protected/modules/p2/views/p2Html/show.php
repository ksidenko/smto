<h2><?php echo Yii::t('P2Module.p2','HTML').' - '.Yii::t('P2Module.p2','Show').' #'.$model->id; ?></h2>


<div class="actionBar">
    <?php $this->widget('application.modules.p2.components.P2CrudActionBar', array('id'=>$_REQUEST['id'])) ?>
    <?php
    $this->widget('application.modules.p2.components.P2AutoComplete',
        array(
        'name' => 'autoComplete',
        'searchModel' => 'P2Html',
        'mode' => P2AutoComplete::MODE_UPDATE,
        'class' => 'ui-widget',)
    );
    ?>
</div>
<?php echo P2Helper::clearfloat() ?>


<table class="dataGrid">
<tr>
	<th class="label"><?php echo CHtml::encode($model->getAttributeLabel('name')); ?>
</th>
    <td><?php echo CHtml::encode($model->name); ?>
</td>
</tr>
<tr>
	<th class="label"><?php echo CHtml::encode($model->getAttributeLabel('html')); ?>
</th>
    <td><?php echo $model->html; ?>
</td>
</tr>
<tr>
	<th class="label"><?php echo CHtml::encode($model->getAttributeLabel('p2_infoId')); ?>
</th>
    <td><?php echo CHtml::encode($model->p2_infoId); ?>
</td>
</tr>
</table>
