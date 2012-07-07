<h2>Managing P2User</h2>

<div class="actionBar">
    <?php $this->widget('application.modules.p2.components.P2CrudActionBar') ?>
    <?php
    $this->widget('application.modules.p2.components.P2AutoComplete',
        array(
        'name' => 'autoComplete',
        'searchModel' => 'P2User',
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
    <th><?php echo $sort->link('status'); ?></th>
    <th><?php echo $sort->link('name'); ?></th>
    <th><?php echo $sort->link('firstName'); ?></th>
    <th><?php echo $sort->link('lastName'); ?></th>
    <th><?php echo $sort->link('eMail'); ?></th>
    <th><?php echo $sort->link('password'); ?></th>
    <th><?php echo $sort->link('status'); ?></th>
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

    <td><?php echo CHtml::encode($model->getStatusName()); ?></td>
    <td><?php echo CHtml::encode($model->name); ?></td>
    <td><?php echo CHtml::encode($model->firstName); ?></td>
    <td><?php echo CHtml::encode($model->lastName); ?></td>
    <td><?php echo CHtml::encode($model->eMail); ?></td>
    <td><?php echo CHtml::encode($model->password); ?></td>
    <td><?php echo CHtml::encode($model->status); ?></td>
  </tr>
<?php endforeach; ?>
  </tbody>
</table>
<br/>
<?php $this->widget('CLinkPager',array('pages'=>$pages)); ?>
