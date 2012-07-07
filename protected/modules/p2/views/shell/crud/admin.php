<?php
/**
 * This is the template for generating the admin view for crud.
 * The following variables are available in this template:
 * - $ID: the primary key name
 * - $modelClass: the model class name
 * - $columns: a list of column schema objects
 */
?>
<h2>
<?php echo "<?php echo Yii::t('P2Module.p2','".$modelClass."'); ?> -"?>
<?php echo " <?php echo Yii::t('P2Module.p2','Manage') ?>" ?>
</h2>

<?php echo "<div class='actionBar'><?php \$this->widget(
    'application.modules.p2.components.P2CrudActionBar') ?>"; ?>
<?php echo "<?php echo P2Helper::clearfloat(); ?>"; ?>
<?php echo "</div>" ?>

<a onclick="$('.search-form').toggle()">Advanced Search</a>
<div class="search-form" style="display:<?php echo "<?php echo(isset(\$_GET['$modelClass'])?'block':'none') ?>" ?>">
<?php echo "<?php \$this->renderPartial('_search',array(
	'model'=>\$model,
)); ?>\n"; ?>
</div><!-- search-form -->


<table class="dataGrid">
  <thead>
  <tr>
    <th><?php echo "<?php echo \$sort->link('$ID'); ?>"; ?></th>
    <th>Actions</th>
<?php foreach($columns as $column): ?>
    <?php if($column->name == $ID) continue; ?>
    <th><?php echo "<?php echo \$sort->link('{$column->name}'); ?>"; ?></th>
<?php endforeach; ?>
  </tr>
  </thead>
  <tbody>
<?php echo "<?php foreach(\$models as \$n=>\$model): ?>\n"; ?>
  <tr class="<?php echo "<?php echo \$n%2?'even':'odd';?>"; ?>">
    <td><?php echo "<?php echo CHtml::link(\$model->{$ID},array('show','id'=>\$model->{$ID})); ?>"; ?></td>

    <td>
      <?php echo "<?php echo CHtml::link('Update',array('update','id'=>\$model->{$ID},'return_url' => P2Helper::return_url())); ?>\n"; ?>
      <?php echo "<?php echo CHtml::linkButton('Delete',array(
      	  'submit'=>'',
      	  'params'=>array('command'=>'delete','id'=>\$model->{$ID}),
      	  'confirm'=>\"Are you sure to delete #{\$model->{$ID}}?\",
          'return_url' => P2Helper::return_url())); ?>\n"; ?>
    </td>

<?php foreach($columns as $column): ?>
    <?php if($column->name == $ID) continue; ?>
    <td><?php echo "<?php echo ".$this->generateOutputField($modelClass, $column, 'admin')."; ?>\n"; ?></td>
<?php endforeach; ?>
  </tr>
<?php echo "<?php endforeach; ?>\n"; ?>
  </tbody>
</table>
<br/>
<?php echo "<?php \$this->widget('CLinkPager',array('pages'=>\$pages)); ?>" ?>
