<?php
    echo CHtml::label('Период обновления', 'monitor-refresh-period') . ": ". CHtml::textField('monitor-refresh-period', '5');
?>
<div class="monitor-info" >
    <?php $this->widget('CMonitor', array('data' => $monitorData)); ?>
</div>