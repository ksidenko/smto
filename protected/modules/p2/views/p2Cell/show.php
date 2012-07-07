<h2><?php echo Yii::t('P2Module.p2','Cells').' - '.Yii::t('P2Module.p2','Show').' #'.$model->id; ?></h2>

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

<table class="dataGrid">
<tr>
	<th class="label"><?php echo CHtml::encode($model->getAttributeLabel('classPath')); ?>
</th>
    <td><?php echo CHtml::encode($model->classPath); ?>
</td>
</tr>
<tr>
	<th class="label"><?php echo CHtml::encode($model->getAttributeLabel('classProps')); ?>
</th>
    <td><?php echo CHtml::encode($model->classProps); ?>
</td>
</tr>
<tr>
	<th class="label"><?php echo CHtml::encode($model->getAttributeLabel('rank')); ?>
</th>
    <td><?php echo CHtml::encode($model->rank); ?>
</td>
</tr>
<tr>
	<th class="label"><?php echo CHtml::encode($model->getAttributeLabel('cellId')); ?>
</th>
    <td><?php echo CHtml::encode($model->cellId); ?>
</td>
</tr>
<tr>
	<th class="label"><?php echo CHtml::encode($model->getAttributeLabel('moduleId')); ?>
</th>
    <td><?php echo CHtml::encode($model->moduleId); ?>
</td>
</tr>
<tr>
	<th class="label"><?php echo CHtml::encode($model->getAttributeLabel('controllerId')); ?>
</th>
    <td><?php echo CHtml::encode($model->controllerId); ?>
</td>
</tr>
<tr>
	<th class="label"><?php echo CHtml::encode($model->getAttributeLabel('actionName')); ?>
</th>
    <td><?php echo CHtml::encode($model->actionName); ?>
</td>
</tr>
<tr>
	<th class="label"><?php echo CHtml::encode($model->getAttributeLabel('requestParam')); ?>
</th>
    <td><?php echo CHtml::encode($model->requestParam); ?>
</td>
</tr>
<tr>
	<th class="label"><?php echo CHtml::encode($model->getAttributeLabel('sessionParam')); ?>
</th>
    <td><?php echo CHtml::encode($model->sessionParam); ?>
</td>
</tr>
<tr>
	<th class="label"><?php echo CHtml::encode($model->getAttributeLabel('cookieParam')); ?>
</th>
    <td><?php echo CHtml::encode($model->cookieParam); ?>
</td>
</tr>
<tr>
	<th class="label"><?php echo CHtml::encode($model->getAttributeLabel('applicationParam')); ?>
</th>
    <td><?php echo CHtml::encode($model->applicationParam); ?>
</td>
</tr>
<tr>
	<th class="label"><?php echo CHtml::encode($model->getAttributeLabel('moduleParam')); ?>
</th>
    <td><?php echo CHtml::encode($model->moduleParam); ?>
</td>
</tr>
<tr>
	<th class="label"><?php echo CHtml::encode($model->getAttributeLabel('p2_infoId')); ?>
</th>
    <td><?php echo CHtml::encode($model->p2_infoId); ?>
</td>
</tr>
</table>
