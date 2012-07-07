<h2>Managing P2Log</h2>

<div class="actionBar">
<?php $this->widget(
    'application.modules.p2.components.P2CrudActionBar') ?>
    <?php echo P2Helper::clearfloat() ?>
</div>
<table class="dataGrid">
  <thead>
  <tr>
    <th><?php echo $sort->link('id'); ?></th>
    <th>Actions</th>
    <th><?php echo $sort->link('description'); ?></th>
    <th><?php echo $sort->link('action'); ?></th>
    <th><?php echo $sort->link('model'); ?></th>
    <th><?php echo $sort->link('modelId'); ?></th>
    <th><?php echo $sort->link('changes'); ?></th>
    <th><?php echo $sort->link('createdBy'); ?></th>
    <th><?php echo $sort->link('createdAt'); ?></th>
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

    <td><?php echo CHtml::encode($model->description); ?></td>
    <td><?php echo CHtml::encode($model->action); ?></td>
    <td><?php echo CHtml::encode($model->model); ?></td>
    <td><?php echo CHtml::encode($model->modelId); ?></td>
    <td><?php echo CHtml::encode($model->changes); ?></td>
    <td><?php echo CHtml::encode($model->createdBy); ?></td>
    <td><?php echo CHtml::encode($model->createdAt); ?></td>
  </tr>
<?php endforeach; ?>
  </tbody>
</table>
<br/>
<?php $this->widget('CLinkPager',array('pages'=>$pages)); ?>