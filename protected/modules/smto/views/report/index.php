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

        <tr style="display: none;" >
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

<?php if ( isset($chartDataJSON['reports']) ) { ?>

<!--<div id="fixme" >&nbsp;</div>-->
<?php $this->widget('application.extensions.EFlot.EFlotGraphWidget',array(
//    'id' => 'fixme',
)); ?>

<?php foreach($machineIds as $machineId){  ?>
<!--<div style="margin-top:10px"></div>-->
<div class="row" style="padding: 10px 0px;">
    <table style="border: 1px dashed gainsboro;" >
        <tr>
            <td><div id="machine_chart_report_main_<?php echo $machineId; ?>" style="width: 290px; height:340px;"></div></td>
            <td><div id="machine_chart_report_work_<?php echo $machineId; ?>" style="width: 290px; height:340px;"></div></td>
            <td><div id="machine_chart_report_not_work_<?php echo $machineId; ?>" style="width: 290px; height:340px;"></div></td>
        </tr>
    </table>
<!--    <div style="clear:both"></div>-->
</div>
<?php } ?>

<?php
//echo '<pre>' . print_r($chartDataJSON, true) . '</pre>';die();
?>
<script type="text/javascript">
    var machineIds = <?php echo json_encode($machineIds); ?>;

    var dataJsonStatesMain = <?php echo json_encode($chartDataJSON['reports']['report-main']) ?>;
    var dataJsonStatesWork = <?php echo json_encode($chartDataJSON['reports']['report-work']) ?>;
    var dataJsonStatesNotWork = <?php echo json_encode($chartDataJSON['reports']['report-not_work']) ?>;

    $(function() {
        //2012-07-18 16:40
        //$.datepicker.formatDate('yyyy-mm-dd', new Date());

        //$( "#ReportConstructor_dtStart,#ReportConstructor_dtEnd" ).datepicker();

        for(var i in machineIds) {
            var id = machineIds[i];

            var options = {
                series: {
                    pie: {
                        show: true,
                        radius: '110',
                        offset:{
                            top:50,
                            left:0
                        },

                        label: {
                            show: true,
                            radius: '90',
                            threshold: 0.025,
                            formatter: function(label, series){
                                //return '<div style="font-size:8pt;text-align:center;padding:2px;color:white;">'+label+'<br/>'+Math.round(series.percent)+'%</div>';
                                return '<div style="font-size:8pt;text-align:center;padding:2px;color:white;">'+Math.round(series.percent)+'%</div>';
                            },
                            background: {
                                opacity: 0.5,
                                color: '#000'
                            }
                        }
                    }
                },
                grid: {
                    hoverable: true,
                    clickable: true
                },
                legend: {
                    show: true
                }
            };

//            var data = [];
//            data[0] = { label: "Series 0", color: '#FF0000', data: 100 };
//            data[1] = { label: "Series 1", color: '#00FF00', data: 150 };
//            data[2] = { label: "Series 2", color: '#0000FF', data: 200 };
//            $.plot($('#'+'machine_chart_report_main_' + id), data, options);

            if (typeof dataJsonStatesMain[id] !== 'undefined' && dataJsonStatesMain[id]['data'].length > 0) {
                var id_ = $('#'+'machine_chart_report_main_' + id);
                id_.before('<label><b>Работа</b><br>' + dataJsonStatesMain[id]['chart']['caption'] + '</label>');
                $.plot(id_, dataJsonStatesMain[id]['data'], options);

//                function pieHover(event, pos, obj)
//                {
//                    if (!obj)
//                        return;
//                    percent = parseFloat(obj.series.percent).toFixed(2);
//                    $("#hover").html('<span style="font-weight: bold; color: '+obj.series.color+'">'+obj.series.label+' ('+percent+'%)</span>');
//                }
//
//                function pieClick(event, pos, obj)
//                {
//                    if (!obj)
//                        return;
//                    percent = parseFloat(obj.series.percent).toFixed(2);
//                    alert(''+obj.series.label+': '+percent+'%');
//                }

//                id_.bind("plothover", pieHover);
//                id_.bind("plotclick", pieClick);
            }

            if (typeof dataJsonStatesWork[id] !== 'undefined' && dataJsonStatesWork[id]['data'].length > 0) {
                var id_ = $('#'+'machine_chart_report_work_' + id);
                id_.before('<label><b>Работа / холостой ход</b><br>' + dataJsonStatesWork[id]['chart']['caption'] + '</label>');
                $.plot(id_, dataJsonStatesWork[id]['data'], options);
            }
            if (typeof dataJsonStatesNotWork[id] !== 'undefined' && dataJsonStatesNotWork[id]['data'].length > 0) {
                var id_ = $('#'+'machine_chart_report_not_work_' + id);
                id_.before('<label><b>Простой</b><br>' + dataJsonStatesNotWork[id]['chart']['caption'] + '</label>');
                $.plot(id_, dataJsonStatesNotWork[id]['data'], options);
            }
        }
    });
<?php } ?>
</script>
