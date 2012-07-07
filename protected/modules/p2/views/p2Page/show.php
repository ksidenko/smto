<h2><?php echo Yii::t('P2Module.p2','Pages').' - '.Yii::t('P2Module.p2','Show').' #'.$model->id; ?></h2>

<div class="actionBar">
    <?php $this->widget('application.modules.p2.components.P2CrudActionBar', array('id'=>$_REQUEST['id'],            'extraLinks' => array(
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
    <tr>
        <th class="label"><?php echo CHtml::encode($model->getAttributeLabel('name')); ?>
        </th>
        <td><?php echo CHtml::link($model->name,array('/p2/p2Page/view',P2Page::PAGE_ID_KEY=>$model->id)); ?>
        </td>
    </tr>
    <tr>
        <th class="label"><?php echo CHtml::encode($model->getAttributeLabel('parentId')); ?>
        </th>
        <td><?php echo CHtml::encode($model->parentId); ?>
        </td>
    </tr>
    <tr>
        <th class="label"><?php echo CHtml::encode($model->getAttributeLabel('descriptiveName')); ?>
        </th>
        <td><?php echo CHtml::encode($model->descriptiveName); ?>
        </td>
    </tr>
    <tr>
        <th class="label"><?php echo CHtml::encode($model->getAttributeLabel('url')); ?>
        </th>
        <td><?php echo CHtml::encode($model->url); ?>
        </td>
    </tr>
    <tr>
        <th class="label"><?php echo CHtml::encode($model->getAttributeLabel('layout')); ?>
        </th>
        <td><?php echo CHtml::encode($model->layout); ?>
        </td>
    </tr>
    <tr>
        <th class="label"><?php echo CHtml::encode($model->getAttributeLabel('view')); ?>
        </th>
        <td><?php echo CHtml::encode($model->view); ?>
        </td>
    </tr>
    <tr>
        <th class="label"><?php echo CHtml::encode($model->getAttributeLabel('replaceMethod')); ?>
        </th>
        <td><?php echo CHtml::encode($model->replaceMethod); ?>
        </td>
    </tr>
    <tr>
        <th class="label"><?php echo CHtml::encode($model->getAttributeLabel('rank')); ?>
        </th>
        <td><?php echo CHtml::encode($model->rank); ?>
        </td>
    </tr>
    <tr>
        <th class="label"><?php echo CHtml::encode($model->getAttributeLabel('p2_infoId')); ?>
        </th>
        <td><?php echo CHtml::encode($model->p2_infoId); ?>
        </td>
    </tr>
</table>
