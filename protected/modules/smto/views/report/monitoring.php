<?php
$this->breadcrumbs=array(
	'Мониторинг параметров станков',
);?>
<h1>Мониторинг параметров станков</h1>
<?php 
    //$this->renderPartial('_search');
    $plotH = 150;
    $plotW = 400;
    $machineId = isset($_POST['machineId']) ? $_POST['machineId'] : 2;
    $translate = array(
        'da_max' => 'Аналоговый сигнал, максимальные значения',
        'da_avg' => 'Аналоговый сигнал, средние значения',
        'dd' => 'Цифровой сигнал',
        'dd_change' => 'Цифровой сигнал, кол-во изменений значений',
    );
    foreach($translate as $key => $value) {
        for($i = 1; $i <=4; $i++ ) {
            $translate[$key . '_' . $i] = $value;
        }
    }
    //$dt_start = strtotime('2011-06-05 11:41:20');
    $dt_start = strtotime(date('Y/m/d H:i'));
?>
<script>
    var translate = <?php echo json_encode($translate);?>;
</script>

<div class="form">
    <?php $form=$this->beginWidget('CActiveForm'); ?>

    
    <?php echo CHtml::label('Станок','machineId'); ?>
    <?php
        $machinesData = Machine::model()->real_records()->with('groups')->cache(600)->findAll(array('order' => 't.name, t.code'));
        $data = array();
        foreach($machinesData as $machineData) {
            foreach($machineData->groups as $group) {
                $data[$group->name][$machineData->id] = $machineData->place_number . ' ' . $machineData->name;
            }
        }
        //$data=CHtml::listData($data, 'id', 'name', 'groups.name');
        //echo $form->dropDownList($model,'machineId',$data);
        echo CHtml::dropDownList('machineId',array($machineId), $data);
    ?>
    <?php //echo $form->dropDownList($model, 'machineId',CHtml::listData(Machine::model()->findAll(array('order' => 'name')), 'id', 'name')) ?>

    <?php echo CHtml::label('Обновлять графики', 'update_plots');?><?php echo CHtml::checkBox('update_plots', 1);?><br>
    <?php echo CHtml::label('Период обновления, сек', 'update_interval');?><?php echo CHtml::textField('update_interval', 5, array('size' => 3));?>
    <?php echo CHtml::hiddenField('dt_start', $dt_start);?>
    <?php echo CHtml::hiddenField('dt_delta_sec', 2);?>

    <div class="row submit">
        <?php echo CHtml::submitButton('Отобразить'); ?>
    </div>
 
<?php $this->endWidget(); ?>
</div><!-- form -->

<table>
    <tr>
        <td>
    <H1><?php echo $translate['da_max'];?> 1-4</H1>
    <!--da_max1-4-->
    <?php foreach(range(1,4) as $i) {?>
    <div class="machine_plot"> 
        <?php echo CHtml::hiddenField('machine_id', $machineId);?>
        <?php echo CHtml::hiddenField('name', "da_max$i");?>
        <div id="plot_<?php echo $machineId?>_da_max<?php echo $i;?>" class="graph" style="width: <?php echo $plotW?>px; height: <?php echo $plotH?>px;"></div>
    </div>
    <?php } ?>
        </td>
        <td>
    <H1><?php echo $translate['da_avg'];?> 1-4</H1>
    <!--da_avg1-4-->
    <?php foreach(range(1,4) as $i) {?>
    <div class="machine_plot"> 
        <?php echo CHtml::hiddenField('machine_id', $machineId);?>
        <?php echo CHtml::hiddenField('name', "da_avg$i");?>
        <?php //echo CHtml::hiddenField('dt_start', $dt_start);?>
        <div id="plot_<?php echo $machineId?>_da_avg<?php echo $i;?>" class="graph" style="width: <?php echo $plotW?>px; height: <?php echo $plotH?>px;"></div>
    </div>
    <?php } ?>
        </td>
    </tr>
    
    <tr>
        <td>
    <H1><?php echo $translate['dd'];?> 1-4</H1>
    <!--dd1-4-->
    <?php foreach(range(1,4) as $i) {?>
    <div class="machine_plot"> 
        <?php echo CHtml::hiddenField('machine_id', $machineId);?>
        <?php echo CHtml::hiddenField('name', "dd$i");?>
        <?php //echo CHtml::hiddenField('dt_start', $dt_start);?>
        <div id="plot_<?php echo $machineId?>_dd<?php echo $i;?>" class="graph" style="width: <?php echo $plotW?>px; height: <?php echo $plotH?>px;"></div>
    </div>
    <?php } ?>
        </td>
        <td>
    <H1><?php echo $translate['dd_change'];?> 1-4</H1>
    <!--dd_change1-4-->
    <?php foreach(range(1,4) as $i) {?>
    <div class="machine_plot"> 
        <?php echo CHtml::hiddenField('machine_id', $machineId);?>
        <?php echo CHtml::hiddenField('name', "dd_change$i");?>
        <?php //echo CHtml::hiddenField('dt_start', $dt_start);?>
        <div id="plot_<?php echo $machineId?>_dd_change<?php echo $i;?>" class="graph" style="width: <?php echo $plotW?>px; height: <?php echo $plotH?>px;"></div>
    </div>
    <?php } ?>
        </td>
    </tr>    
</table>
<?php 
$this->widget('application.extensions.EFlot.EFlotGraphWidget');
//$format_func = <<<EOD
//js:function(label, series){
//    return '<div style="font-size:8pt;text-align:center;padding:2px;color:white;">'+label+'<br/>'+Math.round(series.percent)+'%</div>';}
//EOD;
//$this->widget('application.extensions.EFlot.EFlotGraphWidget', 
//    array(
//        'data'=>array(
//            array('label'=>'cows', 'data'=>20),
//            array('label'=>'sheep', 'data'=>20),
//            array('label'=>'chickens', 'data'=>30),
//        ),
//        'options'=>array(
//            'series'=> array('pie'=>array(
//                'show'=>true,
//                'radius'=> 3/4,
//                'formatter'=> $format_func,
//                ),
//            ),
//            'legend'=> array('show'=>false),
//        ),
//        'htmlOptions'=>array('style'=>'width:400px;height:400px;')
//    )
//);
?>
