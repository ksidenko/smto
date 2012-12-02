<?php

//echo '<pre>' . print_r($this->data, true) . '</pre>'; die;

foreach($this->data['groups'] as $groupId => $groupName) {
    ?>
    <fieldset class="monitor-group" >
        <legend><?php echo $groupName; ?></legend>

        <?php
        foreach($this->data['machines'] as $machineId => $machineInfo) {
            if ( !in_array($groupId, $machineInfo['groups']) ) continue;

            $name = $machineInfo['name'];
            $ip = $machineInfo['ip'];
            $mac = $machineInfo['mac'];
            $state = $machineInfo['state'];

            $div1 = CHtml::tag('div', array('class' => 'monitor-group-machine-name'), $name);
            $div2 = CHtml::tag('div', array('class' => 'monitor-group-machine-state-name'), $state['name']);
            $div3 = CHtml::tag('div', array(), $ip);
            $div4 = CHtml::tag('div', array(), $mac);

            $title  = 'Станок: ' . $name . PHP_EOL;
            $title .= 'Состояние: ' . $state['name'] . PHP_EOL;
            $title .= 'IP: ' . $ip . PHP_EOL;
            $title .= 'MAC: ' . $mac . PHP_EOL;

            $divMachineInfo = CHtml::tag('div', array('class' => 'monitor-group-machine', 'style' => 'background-color:' . $state['color'], 'title' => $title), $div1.$div2);

            echo $divMachineInfo;
        }
        ?>
        <div style="clear:both"></div>
    </fieldset>
    <?php
}