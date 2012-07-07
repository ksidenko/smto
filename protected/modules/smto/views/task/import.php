<?php
$this->breadcrumbs=array(
	'Импорт данных со станков',
);

$this->menu=array(
	array('label'=>'Create Task', 'url'=>array('create')),
	array('label'=>'Manage Task', 'url'=>array('admin')),
);
?>

<table>
<?php foreach($model as $machine) { ?>
    <tr>
    <td width="10" ><?=$machine['id']?></td>
    <td width="200"><?=$machine['name']?></td>
    <?php

        if (isset($tasks[$machine['id']]->status)) {
            switch($tasks[$machine['id']]->status) {
                case 'start':
                    $action = 'stop';
                    break;
                case 'stop':
                    $action = 'start';
                    break;
                default:
                    $action = 'start';
            }
        } else {
            $action = 'start';
        }
        $linkHref = '/smto/task/import/?action=' . $action . '&machine_id=' . $machine['id'];
    ?>
    <td width="50"><a href="<?=$linkHref?>" ><?=$action?></a></td>
    </tr>
<?php } ?>
</table>

