<?php
//print_r($machineConfigData); die;

      function getValue(&$machineConfigData, $condition_number, $machine_state_id, $param, $default = ''){
          if (isset($machineConfigData[$condition_number][$machine_state_id][$param])) {
              return $machineConfigData[$condition_number][$machine_state_id][$param];
          }
          return $default;
      }

    $conditionsLabels = array(
        'Ddat1&nbsp;>&nbsp;0', 'Ddat2&nbsp;>&nbsp;0', 'Ddat3&nbsp;>&nbsp;0', 'Ddat4&nbsp;>&nbsp;0',
        'Tdat1&nbsp;>&nbsp;VAL', 'Tdat2&nbsp;>&nbsp;VAL', 'Tdat3&nbsp;>&nbsp;VAL', 'Tdat4&nbsp;>&nbsp;VAL',

        'AdatM0&nbsp;>&nbsp;VAL', 'AdatM1&nbsp;>&nbsp;VAL', 'AdatM2&nbsp;>&nbsp;VAL', 'AdatM3&nbsp;>&nbsp;VAL',
        'AdatA0&nbsp;>&nbsp;VAL', 'AdatA1&nbsp;>&nbsp;VAL', 'AdatA2&nbsp;>&nbsp;VAL', 'AdatA3&nbsp;>&nbsp;VAL',

        '(AdatM0*KM0 + <br />AdatM1*KM1 + <br />AdatM2*KM2 + <br />AdatM3*KM3)/1024',
        '(AdatA0*KA0 + <br />AdatA1*KA1 + <br />AdatA2*KA2 + <br />AdatA3*KA3)/1024',
        '1&nbsp;>&nbsp;0'
    );
?>

<script type="text/javascript">
    $(function(){
        function hightlightCell (){

            $('#MachineConfig td input').each(function(){
                if($(this).val().length > 0) {
                    $(this).addClass('selectCell');
                } else {
                    $(this).removeClass('selectCell');
                }
            })
        }

        $('#MachineConfig input').change(hightlightCell);

        hightlightCell();
    });
</script>

<p><b>Ddat</b> -  цифровой датчик </p>
<p><b>Tdat</b> -  количество изменений состояния цифрового датчика за 10 секунд</p>
<p><b>AdatM</b> - максимальное значение аналогового датчика за 10 секунд</p>
<p><b>AdatA</b> - среднее значение аналогового датчика за 10 секунд</p>

<H2>Таблица для вычисления состояния</H2>
<table id="MachineConfig"  style="border: 1px solid black; width: 800px;" rules="all" >
    <tr>
        <td width="40" >№</td>
        <td width="100" >Условие</td>
        <td width="340" >Работа</td>
        <td width="340" >Холостой ход</td>
        <td width="340" >Включен</td>
    </tr>
    <?php for($currConditionNumber = 0; $currConditionNumber <= 18; $currConditionNumber++) { ?>
    <tr>
        <td><?php echo $currConditionNumber; ?></td>
        <td><?php echo $conditionsLabels[$currConditionNumber]; ?></td>
        <td><?php
            echo CHtml::hiddenField('MachineConfig['.$currConditionNumber.']['.MachineState::STATE_MACHINE_WORK.'][condition_number]', $currConditionNumber);
            $value = getValue($machineConfigData, $currConditionNumber, MachineState::STATE_MACHINE_WORK, 'value');
            echo CHtml::textField('MachineConfig['.$currConditionNumber.']['.MachineState::STATE_MACHINE_WORK.'][value]', $value, array('size' => 2));
            $apply_number = getValue($machineConfigData, $currConditionNumber, MachineState::STATE_MACHINE_WORK, 'apply_number');
            echo CHtml::dropDownList('MachineConfig['.$currConditionNumber.']['.MachineState::STATE_MACHINE_WORK.'][apply_number]', $apply_number, MachineConfig::$arrApplyNumbers, array('multiple' => false, 'size' => 1));
        ?>
        </td>
        <td><?php
            echo CHtml::hiddenField('MachineConfig['.$currConditionNumber.']['.MachineState::STATE_MACHINE_IDLE_RUN.'][condition_number]', $currConditionNumber);
            $value = getValue($machineConfigData, $currConditionNumber, MachineState::STATE_MACHINE_IDLE_RUN, 'value');
            echo CHtml::textField('MachineConfig['.$currConditionNumber.']['.MachineState::STATE_MACHINE_IDLE_RUN.'][value]', $value, array('size' => 2));
            $apply_number = getValue($machineConfigData, $currConditionNumber, MachineState::STATE_MACHINE_IDLE_RUN, 'apply_number');
            echo CHtml::dropDownList('MachineConfig['.$currConditionNumber.']['.MachineState::STATE_MACHINE_IDLE_RUN.'][apply_number]', $apply_number, MachineConfig::$arrApplyNumbers, array('multiple' => false, 'size' => 1));
            ?></td>
        <td><?php
            echo CHtml::hiddenField('MachineConfig['.$currConditionNumber.']['.MachineState::STATE_MACHINE_ON.'][condition_number]', $currConditionNumber);
            $value = getValue($machineConfigData, $currConditionNumber, MachineState::STATE_MACHINE_ON, 'value');
            echo CHtml::textField('MachineConfig['.$currConditionNumber.']['.MachineState::STATE_MACHINE_ON.'][value]', $value, array('size' => 2));
            $apply_number = getValue($machineConfigData, $currConditionNumber, MachineState::STATE_MACHINE_ON, 'apply_number');
            echo CHtml::dropDownList('MachineConfig['.$currConditionNumber.']['.MachineState::STATE_MACHINE_ON.'][apply_number]', $apply_number, MachineConfig::$arrApplyNumbers, array('multiple' => false, 'size' => 1));
            ?></td>
    </tr>
    <?php } ?>
</table>

<pre>
    Параметры для интегральных условий 16...17
    KM0...KM3 и KA0...KA3 задаются отдельно в диапазоне 0...255


    Сначала выполняется проверка для состояния State=3,
    если результат отрицателен, для State=2,
    если результат отрицателен, для State=1,
    если результат отрицателен, считаем, что State==0 (выключен)

    При каждой проверке таблица просматривается по порядку,
    пока не встретится первое выполненное достаточное условие ( результат проверки положителен) или
    первое _не_выполненное необходимое (результат проверки - отрицателен) . Если таблицу прошли до конца -
    результат тоже положителен. Поэтому, как правило, таблица заканчивается условием "необходимо..."  (3 или 4).
    Условие 18 - фиктивное - всегда считается выполненным



    После проверок "сырое" решение фильтруется и формируется итоговое состояние StateOut
    в соответствии с параметрами S0,S1,S2,S3 и "сырыми" решениями в предыдущие 10-секундные такты:

    Если зафиксировано S3 состояний (State==3) подряд, переходим в состояние StateOut=3 (работает);
    Иначе, если зафиксировано S2 состояний (State>=2) подряд, переходим в состояние StateOut=2 (холостой ход);
    Иначе, если зафиксировано S1 состояний (State>=1) подряд, переходим в состояние StateOut=1 (включен);
    Иначе, если зафиксировано S0 состояний (State==0) подряд, переходим в состояние StateOut=0 (выключен);
    Иначе состояние StateOut остается неизменным.

</pre>