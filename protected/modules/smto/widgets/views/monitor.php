<?php

//echo '<pre>' . print_r($this->data, true) . '</pre>'; die;

foreach($this->data['groups'] as $groupId => $groupName) {
    ?>
    <fieldset class="monitor-group" >
        <legend><?php echo $groupName; ?></legend>

        <?php
        foreach($this->data['machines'] as $machineId => $machineInfo) {
            if ( !in_array($groupId, $machineInfo['groups']) ) continue;

            $fullName = $machineInfo['full_name'];
            $name = $machineInfo['name'];
            $code = $machineInfo['code'];
            $spanNumber = $machineInfo['span_number'];
            $placeNumber = $machineInfo['place_number'];
            $ip = $machineInfo['ip'];
            $mac = $machineInfo['mac'];
            $state = $machineInfo['state'];
            $operatorFullName = $machineInfo['operator']['full_name'];
            $operatorLastFkey = $machineInfo['operator_last_fkey'];

            $code_ = '';
            if (!empty($code)) {
                 $code_ = 'инв. №: ' . $code;
            }

            $spanNumber_ = $spanNumber;

            $placeNumber_ = '';
            if (!empty($placeNumber)) {
                $placeNumber_ = 'место: ' . $placeNumber;
            }

            $div1 = CHtml::tag('div', array('class' => 'monitor-group-machine-name'), $name);
            $div2 = CHtml::tag('div', array('class' => 'monitor-group-machine-state-name'), $state['name']);
            //$div3 = CHtml::tag('div', array('class' => 'monitor-group-machine-state-name'), $code);
            $arr = array($code_, $spanNumber_, $placeNumber_);
            foreach ($arr as $key => $value) {
                if (empty($value)) {
                    unset($arr[$key]);
                }
            }
            $div3 = '';
            $div = implode(', ', $arr);
            if (empty($div)) $div = '&nbsp;';
            $div4 = CHtml::tag('div', array('class' => 'monitor-group-machine-state-name'), $div);
            $div5 = CHtml::tag('div', array('class' => 'monitor-group-machine-state-name'), $operatorFullName);
            $div_ = $div1 . $div2 . $div3 . $div4 . $div5;

            $title  = 'Наименование: ' . $fullName . PHP_EOL;
            $title .= 'Модель: ' . $name . PHP_EOL;
            $title .= 'Инв. №: ' . $code . PHP_EOL;
            $title .= 'Пролет №: ' . $spanNumber . PHP_EOL;
            $title .= 'Место на плане: ' . $placeNumber . PHP_EOL;
            $title .= 'Состояние: ' . $state['name'] . PHP_EOL;

            if ($operatorLastFkey) {
                $title .= 'Причина простоя: ' . $operatorLastFkey['name'] . PHP_EOL;
            }

            $title .= 'Оператор: ' . $operatorFullName . PHP_EOL;

            if (Yii::app()->user->checkAccess('smto-MonitoringAdministrating')) {
                $title .= '---------------' . PHP_EOL;
                $title .= 'IP: ' . $ip . PHP_EOL;
                $title .= 'MAC: ' . $mac . PHP_EOL;
            }

            $divMachineInfo = CHtml::tag('div', array(
                'class' => 'monitor-group-machine',
                'style' => 'background-color:' . $state['color'],
                'title' => $title,
                'onclick' => "document.location.href='" . $this->getUrlReport($machineId) . "'"
            ), $div_ );

            echo $divMachineInfo;
        }
        ?>
        <div style="clear:both"></div>
    </fieldset>
    <?php
}
?>
<div style="clear:both;"></div>