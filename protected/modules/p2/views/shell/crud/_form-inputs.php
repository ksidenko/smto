<?php foreach($columns as $name=>$column): ?>
<div class="row">
        <?php echo "<?php echo ".$this->generateInputLabel($modelClass,$column)."; ?>\n"; ?>
        <?php echo "<?php echo ".$this->generateInputField($modelClass,$column)."; ?>\n"; ?>
</div>
<?php endforeach; ?>

<?php
$model = new $modelClass;
foreach($model->relations() AS $name => $relation):
    if ($relation[0] === CActiveRecord::BELONGS_TO) continue;
?>
    <div class="row">
        <?php echo "<?php echo ".$this->generateRelationLabel($modelClass,$name)."; ?>\n"; ?>
        <?php echo "<?php echo ".$this->generateRelationField($modelClass,$name,$relation)."; ?>\n"; ?>
    </div>
<?php endforeach; ?>

<?php echo "<?php echo P2Helper::clearfloat(); ?>"; ?>