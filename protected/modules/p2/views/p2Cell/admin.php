<h2><?php echo Yii::t('P2Module.p2','Cells').' - '.Yii::t('P2Module.p2','Administration'); ?></h2>

<div class="actionBar">
    <?php $this->widget('application.modules.p2.components.P2CrudActionBar') ?>
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
  <thead>
  <tr>
    <th><?php echo $sort->link('id'); ?></th>
    <th>Actions</th>
    <th><?php echo $sort->link('classPath'); ?></th>
    <th><?php echo $sort->link('rank'); ?></th>
    <th><?php echo $sort->link('cellId'); ?></th>
    <th><?php echo $sort->link('moduleId'); ?></th>
    <th><?php echo $sort->link('controllerId'); ?></th>
    <th><?php echo $sort->link('actionName'); ?></th>
    <th><?php echo $sort->link('requestParam'); ?></th>
    <th><?php echo $sort->link('sessionParam'); ?></th>
    <th><?php echo $sort->link('cookieParam'); ?></th>
    <th><?php echo $sort->link('applicationParam'); ?></th>
    <th><?php echo $sort->link('moduleParam'); ?></th>
    <th><?php echo $sort->link('p2_infoId'); ?></th>
  </tr>
  </thead>
  <tbody>
<?php foreach($models as $n=>$model): ?>
  <tr class="<?php echo $n%2?'even':'odd';?>">
    <td><?php echo CHtml::link($model->id,array('show','id'=>$model->id)); ?></td>

    <td>
      <?php echo CHtml::link('Update',array('update','id'=>$model->id,'return_url' => P2Helper::return_url())); ?>
      <?php echo CHtml::linkButton('Delete',array(
      	  'submit'=>'',
      	  'params'=>array('command'=>'delete','id'=>$model->id),
      	  'confirm'=>"Are you sure to delete #{$model->id}?",
          'return_url' => P2Helper::return_url())); ?>
    </td>

    <td><?php echo CHtml::encode($model->classPath); ?></td>
    <td><?php echo CHtml::encode($model->rank); ?></td>
    <td><?php echo CHtml::encode($model->cellId); ?></td>
    <td><?php echo CHtml::encode($model->moduleId); ?></td>
    <td><?php echo CHtml::encode($model->controllerId); ?></td>
    <td><?php echo CHtml::encode($model->actionName); ?></td>
    <td><?php echo CHtml::encode($model->requestParam); ?></td>
    <td><?php echo CHtml::encode($model->sessionParam); ?></td>
    <td><?php echo CHtml::encode($model->cookieParam); ?></td>
    <td><?php echo CHtml::encode($model->applicationParam); ?></td>
    <td><?php echo CHtml::encode($model->moduleParam); ?></td>
    <td><?php $this->widget('P2InfoInputWidget', array('owner'=>$model,'display'=>'table')) ?></td>
  </tr>
<?php endforeach; ?>
  </tbody>
</table>
<br/>
<?php $this->widget('CLinkPager',array('pages'=>$pages)); ?>