<h2><?php echo Yii::t('P2Module.p2','HTML').' - '.Yii::t('P2Module.p2','Administration'); ?></h2>


<div class="actionBar">
    <?php $this->widget('application.modules.p2.components.P2CrudActionBar') ?>
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
  <thead>
  <tr>
    <th><?php echo $sort->link('id'); ?></th>
    <th>Actions</th>
    <th><?php echo $sort->link('name'); ?></th>
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

    <td>    
    <p><b><?php echo CHtml::encode($model->name); ?></b></p>
    <?php echo CHtml::encode(strip_tags($model->html)); ?>
    
    </td>
    <td><?php $this->widget('P2InfoInputWidget', array('owner'=>$model,'display'=>'table')) ?></td>
  </tr>
<?php endforeach; ?>
  </tbody>
</table>
<br/>
<?php $this->widget('CLinkPager',array('pages'=>$pages)); ?>