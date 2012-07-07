<table class="p2SelectTable">
<?php foreach($models AS $model): ?>
    <tr>
        <td onclick="$('<?php echo $updateSelector ?>').val('<?php echo $model->id ?>')"><?php echo $model->descriptiveName ?></td>
    </tr>
<?php endforeach;?>
</table>
