<h1><?php echo Yii::t('P2Module.p2','Welcome to phundament').', '.Yii::app()->user->name.'!' ?></h1>

<?php if(YII_DEBUG===true): ?>
<div class="ui-corner-all ui-state-error" style="padding: .7em; margin: 1em 0">
        <?php echo P2Helper::juiIcon("alert"); ?>
    <strong>WARNING: YII_DEBUG === true</strong>
    DO NOT USE IN PRODUCTION MODE!
</div>
<?php endif; ?>

<div class="span-18">
    <?php $this->widget(
        'application.modules.p2.components.cellmanager.P2CellManager',
        array(
        'id'=>'mainCell',
        'varyByRequestParam' => 'pageName'
        )
        ) ?>
</div>


<div class="span-6 last">
    <?php echo CHtml::beginForm() ?>
    <h2><?php echo Yii::t('P2Module.p2','Quick Search') ?></h2>
    <p>
        <b><?php echo CHtml::link(Yii::t('P2Module.p2','HTML'),array('/p2/p2Html')) ?></b><br/>
        <?php
        $this->widget('application.modules.p2.components.P2AutoComplete',
            array(
            'name' => 'autoComplete',
            'searchModel' => 'P2Html',
            'mode' => P2AutoComplete::MODE_UPDATE,
            'class' => 'ui-widget',)
        );
        ?>
    </p>
    <p>
        <b><?php echo CHtml::link(Yii::t('P2Module.p2','Files'),array('/p2/p2File')) ?></b><br/>
        <?php
        $this->widget('application.modules.p2.components.P2AutoComplete',
            array(
            'name' => uniqid('autoComplete'),
            'searchModel' => 'P2File',
            'mode' => P2AutoComplete::MODE_UPDATE,
            'class' => 'ui-widget',)
        );
        ?>
    </p>
    <p>
        <b><?php echo CHtml::link(Yii::t('P2Module.p2','Pages'),array('/p2/p2Page')) ?></b><br/>
        <?php
        $this->widget('application.modules.p2.components.P2AutoComplete',
            array(
            'name' => uniqid('autoComplete'),
            'searchModel' => 'P2Page',
            'mode' => P2AutoComplete::MODE_UPDATE,
            'class' => 'ui-widget',)
        );
        ?></p>
    <p>
        <b><?php echo CHtml::link(Yii::t('P2Module.p2','Cells'),array('/p2/p2Cell')) ?></b><br/>
        <?php
        $this->widget('application.modules.p2.components.P2AutoComplete',
            array(
            'name' => uniqid('autoComplete'),
            'searchModel' => 'P2Cell',
            'mode' => P2AutoComplete::MODE_UPDATE,
            'class' => 'ui-widget',)
        );
        ?>
    </p>

    <h2><?php echo Yii::t('P2Module.p2','Common Tasks') ?></h2>
    <ul>
        <li><?php echo CHtml::link(Yii::t('P2Module.p2','Go to frontend'), Yii::app()->homeUrl); ?></li>
        <li><?php echo CHtml::link(Yii::t('P2Module.p2','Upload a file'), array('/p2/p2File/create')); ?></li>
    </ul>



    <h2>Sitemap (all)</h2>
    <?php echo P2Page::renderTree(1, null, null, 'normal'); ?>
    <?php echo CHtml::endForm() ?>
</div>

<?php echo P2Helper::clearfloat() ?>