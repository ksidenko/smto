<h2><?php echo Yii::t('P2Module.p2','Pages').' - '.Yii::t('P2Module.p2','Update').' #'.$model->id; ?></h2>

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
<?php echo CHtml::link('Review page [^]', array('/p2/p2Page/view',P2Page::PAGE_ID_KEY=>$model->id), array('target'=>'_blank')); ?>
<?php echo $this->renderPartial('_form', array(
	'model'=>$model,
	'update'=>true,
)); ?>