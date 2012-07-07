<h2><?php echo Yii::t('P2Module.p2','Pages').' - '.Yii::t('P2Module.p2','Administration'); ?></h2>

<div class="actionBar">
    <?php $this->widget('application.modules.p2.components.P2CrudActionBar', array('extraLinks' => array(
            'Sitemap' => array('/p2/p2Page/sitemap')
        )
        )) ?>
    <?php
    $this->widget('application.modules.p2.components.P2AutoComplete',
        array(
        'name' => 'autoComplete',
        'searchModel' => 'P2Page',
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
    <th><?php echo $sort->link('parentId'); ?></th>
    <th><?php echo $sort->link('descriptiveName'); ?></th>
    <th><?php echo $sort->link('url'); ?></th>
    <th><?php echo $sort->link('layout'); ?></th>
    <th><?php echo $sort->link('view'); ?></th>
    <th><?php echo $sort->link('replaceMethod'); ?></th>
    <th><?php echo $sort->link('rank'); ?></th>
    <th><?php echo $sort->link('p2_infoId'); ?></th>
  </tr>
  </thead>
  <tbody>
<?php foreach($models as $n=>$model): ?>
  <tr class="<?php echo $n%2?'even':'odd';?>">
    <td><?php echo CHtml::link($model->id,array('show','id'=>$model->id)); ?></td>
    <td>
      <?php echo CHtml::link('Update',array('update','id'=>$model->id)); ?>
      <?php echo CHtml::linkButton('Delete',array(
      	  'submit'=>'',
      	  'params'=>array('command'=>'delete','id'=>$model->id),
      	  'confirm'=>"Are you sure to delete #{$model->id}?")); ?>
	</td>
    <td><?php echo CHtml::encode($model->name); ?><?php echo CHtml::link("[^]",array('/p2/p2Page/view',P2Page::PAGE_ID_KEY=>$model->id),array('target'=>'_blank')); ?></td>
    <td><?php echo CHtml::encode($model->Parent->name); ?></td>
    <td><?php echo CHtml::encode($model->descriptiveName); ?></td>
    <td><?php echo CHtml::encode($model->url); ?></td>
    <td><?php echo CHtml::encode($model->layout); ?></td>
    <td><?php echo CHtml::encode($model->view); ?></td>
    <td><?php echo CHtml::encode($model->replaceMethod); ?></td>
    <td><?php echo CHtml::encode($model->rank); ?></td>
    <td><?php $this->widget('P2InfoInputWidget', array('owner'=>$model,'display'=>'table')) ?></td>
  </tr>
<?php endforeach; ?>
  </tbody>
</table>
<br/>
<?php $this->widget('CLinkPager',array('pages'=>$pages)); ?>