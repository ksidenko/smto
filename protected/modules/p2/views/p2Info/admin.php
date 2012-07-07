<h2>Managing P2Info</h2>

<?php $this->widget(
    'application.modules.p2.components.P2CrudActionBar') ?>
<table class="dataGrid">
  <thead>
  <tr>
    <th><?php echo $sort->link('id'); ?></th>
    <th>Actions</th>
    <th><?php echo $sort->link('model'); ?></th>
    <th><?php echo $sort->link('language'); ?></th>
    <th><?php echo $sort->link('status'); ?></th>
    <th><?php echo $sort->link('type'); ?></th>
    <th><?php echo $sort->link('createdBy'); ?></th>
    <th><?php echo $sort->link('createdAt'); ?></th>
    <th><?php echo $sort->link('modifiedBy'); ?></th>
    <th><?php echo $sort->link('modifiedAt'); ?></th>
    <th><?php echo $sort->link('begin'); ?></th>
    <th><?php echo $sort->link('end'); ?></th>
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

    <td><?php echo CHtml::encode($model->model); ?></td>
    <td><?php echo CHtml::encode($model->language); ?></td>
    <td><?php echo CHtml::encode($model->status); ?></td>
    <td><?php echo CHtml::encode($model->type); ?></td>
    <td><?php echo CHtml::encode($model->createdBy); ?></td>
    <td><?php echo CHtml::encode($model->createdAt); ?></td>
    <td><?php echo CHtml::encode($model->modifiedBy); ?></td>
    <td><?php echo CHtml::encode($model->modifiedAt); ?></td>
    <td><?php echo CHtml::encode($model->begin); ?></td>
    <td><?php echo CHtml::encode($model->end); ?></td>
  </tr>
<?php endforeach; ?>
  </tbody>
</table>
<br/>
<?php $this->widget('CLinkPager',array('pages'=>$pages)); ?>