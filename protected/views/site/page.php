<?php $this->pageTitle=Yii::app()->name; ?>
<? echo __FILE__;?><br/>
<?php $this->widget('p2.components.cellmanager.P2CellManager',array(
    'id'=>'mainCell',
    'varyByRequestParam' => 'pageId'
    )) ?>

