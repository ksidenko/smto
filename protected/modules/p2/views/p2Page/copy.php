<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

?>
<div class="yiiForm form">
<?php echo CHtml::beginForm() ?>

<h2>Copy page</h2>

<h3>Select</h3>
<?php echo CHtml::dropDownList('from', Yii::app()->request->getPost('from'), CHtml::listData(P2Page::model()->findAll(array('order'=>'descriptiveName')),'id','descriptiveName','P2Info.language')); ?>
 to (as a child of)
<?php echo CHtml::dropDownList('to', Yii::app()->request->getPost('to'), CHtml::listData(P2Page::model()->localized()->findAll(array('order'=>'descriptiveName')),'id','descriptiveName','Parent.descriptiveName')); ?>
<?php echo CHtml::submitButton('Check contents') ?>


<?php if($step == 1): ?>
<h3>Page</h3>
<div class="page">
    <b><?php echo $page->descriptiveName ?> (<?php echo $page->name ?>)</b><br/>
    <?php echo $page->layout ?><br/>
    <?php echo $page->view ?>
</div>

<h3>Cells</h3>
<div class="widgets">
    <?php foreach($cells AS $cell): ?>
        <?php echo CHtml::link('#'.$cell->id, array('/p2/p2Cell/update','id'=>$cell->id), array('target'=>'_blank')).' '.$cell->cellId ?>: <?php echo $cell->classPath ?><br/>
    <?php endforeach; ?>
</div>

<h3>Copy</h3>
<div class="infoSummary">
    Check this box, if you really want to copy these contents to a new page:</div>
    <?php echo CHtml::checkBox('copyNow', false) ?> Copy contents to <b><?php echo Yii::app()->language ?></b>
    <br/><br/>
        <?php echo CHtml::submitButton('Start copying now!') ?>

<?php endif; ?>


<?php if($step == 2): ?>
    <?php echo $result ?>
<?php endif; ?>


<?php echo CHtml::endForm() ?>

</div>