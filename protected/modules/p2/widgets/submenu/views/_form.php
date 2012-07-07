<h2>SubMenu Widget</h2>
<?php echo CHtml::beginForm('/p2/ajax/search','POST') ?>
        
<?php echo CHtml::hiddenField('modelClass',get_class($model)); ?>

<div class="yiiForm form classProps">
    <div class="simple">
        <?php echo CHtml::activeLabel($model,'startNode') ?>
        <?php $this->widget(
            'P2AutoComplete',
            array(
                'model' => $model,
                'attribute' => 'startNode',
                'searchModel' => 'P2Page'
                )
        )?>
        
    </div>
    <div class="simple">
        <?php echo CHtml::activeLabel($model,'headline') ?>
        <?php echo CHtml::activeTextField($model, 'headline') ?>
            </div>
            
</div>

        <?php echo CHtml::endForm() ?>