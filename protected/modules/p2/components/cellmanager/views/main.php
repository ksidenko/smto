
<?php $this->beginClip('cellAdmin'); ?>
<?php $this->render('admin'); ?>
<?php $this->endClip(); ?>

<?php $this->beginClip('cellCreate'); ?>
<?php $this->render('form', array('update'=>false)); ?>
<?php $this->endClip(); ?>

<?php $this->beginClip('widgets'); ?>
<?php foreach($cells AS $cell): ?>
    <?php
    $this->render(
            'widgetAdmin',
            array(
            'cell'=>$cell,
            'widget'=>$this->instantiateWidget($cell)
            )
    ); ?>
    <?php #echo $this->prepareCellAdminContent($cell, true); ?>
<?php endforeach; ?>
<?php $this->endClip(); ?>

<div class='p2CellManager' id='<?php echo $this->id ?>'>
    <?php $this->widget(
            'system.zii.widgets.jui.CJuiTabs',
            //$tabs,
            array('tabs'=>array(
                    Yii::t('P2Module.p2','Cell').' - '.ucfirst($this->id) => array(
                            #'title' => $this->id,
                            'content' => $this->controller->clips['widgets'],),
                    P2Helper::juiIcon('plus', false) => array(
                            #'title' => 'create',
                            'content'=>$this->controller->clips['cellCreate'],),
                    P2Helper::juiIcon('gear', false) => array(
                            #'title' => 'admin',
                            'content'=>$this->controller->clips['cellAdmin'],),
            )
            )
    ); ?>
</div>
