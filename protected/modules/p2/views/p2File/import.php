<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
*/

?>
<h3>Import Files</h3>
<div class="yiiForm form">
    <?php if($result == null): ?>
        <?php echo CHtml::beginForm('', 'post') ?>
        <?php echo CHtml::dropDownList('dir', null, $directories); ?>
    <div class="simple">
            <?php echo CHtml::label('files', 'files') ?>
        <div>
                <?php echo CHtml::checkBoxList('files', null, $files) ?>
        </div>
    </div>
    <div class="action">
            <?php echo CHtml::submitButton() ?>
    </div>
        <?php echo CHtml::endForm() ?>
    <?php else: ?>
        <?php echo $result ?>
    <?php endif;?>
</div>