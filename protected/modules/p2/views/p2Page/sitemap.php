<h2><?php echo Yii::t('P2Module.p2','Pages').' - '.Yii::t('P2Module.p2','Sitemap'); ?></h2>

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


<h3>Sitemap (<?php echo Yii::app()->language ?>)</h3>
<div class='p2PageMenu'>
<?php echo P2Page::renderTree(1, null, 'localized', 'admin'); ?>
</div>
