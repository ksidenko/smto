<h2><?php echo Yii::t('P2Module.p2','Files').' - '.Yii::t('P2Module.p2','Show').' #'.$model->id; ?></h2>


<div class="actionBar">
    <?php $this->widget('application.modules.p2.components.P2CrudActionBar', array('id'=>$_REQUEST['id'])) ?>
    <?php
    $this->widget('application.modules.p2.components.P2AutoComplete',
        array(
        'name' => 'autoComplete',
        'searchModel' => 'P2File',
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
    <td><?php echo CHtml::link($model->name,array('/p2/p2File/image','id'=>$model->id,'preset'=>'original'),array('target'=>'_blank')); ?>
        <br/>
      <?php echo CHtml::image(CController::createUrl('/p2/p2File/image',array('id'=>$model->id,'preset'=>'fckbrowse')))?>
</td>
</tr>
<tr>
	<th class="label"><?php echo CHtml::encode($model->getAttributeLabel('filePath')); ?>
</th>
    <td><?php echo CHtml::encode($model->filePath); ?>
</td>
</tr>
<tr>
	<th class="label"><?php echo CHtml::encode($model->getAttributeLabel('fileType')); ?>
</th>
    <td><?php echo CHtml::encode($model->fileType); ?>
</td>
</tr>
<tr>
	<th class="label"><?php echo CHtml::encode($model->getAttributeLabel('fileInfo')); ?>
</th>
    <td><?php echo CHtml::encode($model->fileInfo); ?>
</td>
</tr>
<tr>
	<th class="label"><?php echo CHtml::encode($model->getAttributeLabel('p2_infoId')); ?>
</th>
    <td><?php echo CHtml::encode($model->p2_infoId); ?>
</td>
</tr>
</table>
