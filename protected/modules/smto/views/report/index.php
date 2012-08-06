<div class="form">
    <?php $form=$this->beginWidget('CActiveForm'); ?>
 
    <?php echo $form->errorSummary($model); ?>

    <table width="500px" >
        <tr>
            <td>
                <?php echo $form->label($model,'dtStart'); ?>
            </td>
            <td>
                <?php echo $form->textField($model,'dtStart') ?>
                <?php //echo $form->label($model,'dtEnd'); ?>
                <?php echo $form->textField($model,'dtEnd') ?>
            </td>
        </tr>
        <tr>
            <td>
                <?php echo $form->label($model,'operatorId'); ?>
            </td>
            <td>
                <?php echo $form->dropDownList($model,'operatorId', array(0 => 'Все', -1 => 'Не зарегистрированные') + CHtml::listData(Operator::model()->findAll(array('order' => 'full_name')), 'id', 'full_name')) ?>
            </td>
        <tr>

        <tr>
            <td>
                <?php echo $form->label($model,'machineReportType'); ?>
                <?php //echo $form->dropDownList($model,'machineReportType', $model::$arrMachineReportType) ?>
            </td>
            <td>
                <?php echo $form->dropDownList($model, 'machineId', array( 'separate' => 'Отдельный', 'join' => 'Объединенный', ) + CHtml::listData(Machine::model()->real_records()->findAll(array('order' => 'name')), 'id', 'name')) ?>
            </td>
        </tr>

        <tr>
            <td>
                <?php echo $form->label($model,'graphReportType'); ?>
            </td>
            <td>
                <?php echo $form->dropDownList($model,'graphReportType', ReportConstructor::$arrGraphType) ?>
            </td>
        </tr>

        <tr>
            <td>
                <?php echo $form->label($model,'timetableId'); ?>
            </td>
            <td>
                <?php echo $form->dropDownList($model,'timetableId', array(0 => 'День') + CHtml::listData(Timetable::model()->findAll(array('order' => 'id asc')), 'id', 'name')) ?>
            </td>
        </tr>
    </table>

    <div class="row submit">
        <?php echo CHtml::submitButton('Отобразить'); ?>
    </div>
 
<?php $this->endWidget(); ?>
</div><!-- form -->
<?php foreach($chartData as $machineId => $data){  ?>
<!--<div style="margin-top:10px"></div>-->
<div class="row" style="padding: 10px 0px;">
    <div id="machine_chart_<?php echo $machineId; ?>" style="float:left"></div>
    <div id="machine_chart_detail_<?php echo $machineId; ?>" style="float:left"></div>

<!--        <div id="chart_states_work_--><?php //echo $machineId; ?><!--" style="float:left"></div>-->
<!--        <div id="chart_states_not_work_--><?php //echo $machineId; ?><!--" style="float:left; padding-left: 10px"></div>-->
    <div style="clear:both"></div>
</div>
<?php } ?>

<?php
//echo '<pre>' . print_r($chartDataJSON, true) . '</pre>';die();
?>
<script type="text/javascript">
    var machineIds = <?php echo json_encode(array_keys($chartData)); ?>;
    var dataJsonStatesWork = <?php echo json_encode($chartDataJSON['states_work']) ?>;
    var dataJsonStatesNotWork = <?php echo json_encode($chartDataJSON['states_not_work']) ?>;

    $(function() {
        FusionCharts._fallbackJSChartWhenNoFlash();
        //FusionCharts.setCurrentRenderer('javascript');

        for(var i in machineIds) {
            var chartId = '_machine_chart_' + i;
            var chartDetailId = '_machine_chart_detail_' + i;
            var myChart = new FusionCharts('<?php echo $chartAssetsPath ?>/Charts/<?php echo $chartType ?>.swf', chartId, '350', '300', '0', '1');
            myChart.setJSONData(dataJsonStatesNotWork[i]);
            FusionCharts(chartId).configureLink ({
                    //swfUrl : "../../../../Charts/Pie3D.swf",
                    "renderAt" : chartDetailId,
                    overlayButton: { show : false }
            });
            myChart.render('machine_chart_' + i);
        }
    });
</script>
