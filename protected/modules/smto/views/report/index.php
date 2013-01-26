<?php $timeRange = $model->getTimeRanges();?>
<div class="form">
    <?php $form=$this->beginWidget('CActiveForm'); ?>
 
    <?php echo $form->errorSummary($model); ?>

    <table width="500px" >
        <tr>
            <td width="150" >
                <?php echo $form->dropDownList($model,'timeRange', $timeRange['labels']); ?>
                <?php //echo $form->label($model,'dtStart'); ?>
            </td>
            <td width="400" >
                <?php echo $form->textField($model,'dtStart') ?>
                <?php //echo $form->label($model,'dtEnd'); ?>
                <?php echo $form->textField($model,'dtEnd') ?>
            </td>
            <td width="200" >
                <?php echo $form->label($model,'timetableId'); ?>
            </td>
            <td width="200" >
                <?php echo $form->dropDownList($model,'timetableId', array(0 => 'День') + CHtml::listData(Timetable::model()->findAll(array('order' => 'id asc')), 'id', 'name')) ?>
            </td>
        </tr>
        <tr>
            <td>
                <?php echo $form->label($model,'machineReportType'); ?>
                <?php //echo $form->dropDownList($model,'machineReportType', $model::$arrMachineReportType) ?>
            </td>
            <td>
                <?php
                $machinesData = Machine::model()->real_records()->with('groups')->cache(600)->findAll(array('order' => 't.name, t.code'));
                $data = array();
                foreach($machinesData as $machineData) {
                    foreach($machineData->cache(600)->groups as $group) {
                        $data[$group->name][$machineData->id] = $machineData->full_name;
                    }
                }
                //$data=CHtml::listData($data, 'id', 'name', 'groups.name');
                echo $form->dropDownList($model,'machineId',array( '_' => array('separate' => 'Отдельный', 'join' => 'Объединенный') ) + $data);

                ?>
            </td>

            <td>
                <?php echo $form->label($model,'operatorId'); ?>
            </td>
            <td>
                <?php echo $form->dropDownList($model,'operatorId',
                array(
                    'all' => 'Все',
                    'by_pass' => "По карточкам",
                    'not_register' => 'Не зарегистрированные',
                ) + CHtml::listData(Operator::model()->findAll(array('order' => 'full_name')), 'id', 'full_name')) ?>
            </td>

<!--        <tr>-->
<!---->
<!--        <tr>-->
<!--            -->
<!--        </tr>-->
<!---->
<!--        <tr style="display: none;" >-->
<!--            <td>-->
<!--                --><?php //echo $form->label($model,'graphReportType'); ?>
<!--            </td>-->
<!--            <td>-->
<!--                --><?php //echo $form->dropDownList($model,'graphReportType', ReportConstructor::$arrGraphType) ?>
<!--            </td>-->
<!--        </tr>-->
<!---->
<!--        <tr>-->

        </tr>
    </table>

    <div class="row submit">
        <?php echo CHtml::submitButton('Отобразить'); ?>
    </div>
 
<?php $this->endWidget(); ?>
</div><!-- form -->

<script>
    var timeRange = <?php echo json_encode($timeRange); ?>;

    $(function() {

        $( ".machine_tabs" ).tabs().show();

        var dtStart = $("[id$=dtStart]"),
            dtEnd = $("[id$=dtEnd]");

        dtStart.datetimepicker('setDate', dtStart.val() );
        dtEnd.datetimepicker('setDate', dtEnd.val() );

        $('[id$=timeRange]').change(function(){
            var v = $(this).val();
            var s = timeRange['values'][v]['start_date'];
            var e = timeRange['values'][v]['end_date'];

            dtStart.datetimepicker('setDate', s );
            dtEnd.datetimepicker('setDate', e );
        });
    });
</script>

<?php if ( isset($chartDataJSON['reports']) ) { ?>

<?php foreach($machineIds as $machineId){  ?>
<!--<div style="margin-top:10px"></div>-->
<div class="row" style="padding: 10px 0px;">

    <div id="tabs_<?php echo $machineId; ?>" class="machine_tabs" style="display: none;">
        <ul>
            <li><a href="#tabs-report-main">Работа / Простой</a></li>
            <li><a href="#tabs-report-work">Работа</a></li>
            <li><a href="#tabs-report-not-work">Простой</a></li>
        </ul>
        <div id="tabs-report-main">
            <table style="border: 0px;margin: 0;" >
                <tr>
                    <td><div id="machine_chart_report_main_<?php echo $machineId; ?>" style="width: 300px; height:250px;"></div></td>
                    <td><div id="machine_chart_report_main_legend_<?php echo $machineId; ?>" style="width: 100%; height:100%;; float:left"></div></td>
                </tr>
            </table>
        </div>
        <div id="tabs-report-work">
            <table style="border: 0px;margin: 0;" >
                <tr>
                    <td><div id="machine_chart_report_work_<?php echo $machineId; ?>" style="width: 300px; height:250px;"></div></td>
                    <td><div id="machine_chart_report_work_legend_<?php echo $machineId; ?>" style="width: 100%; height:100%;; float:left"></div></td>
                </tr>
            </table>
        </div>
        <div id="tabs-report-not-work">
            <table style="border: 0px;margin: 0;" >
                <tr>
                    <td><div id="machine_chart_report_not_work_<?php echo $machineId; ?>" style="width: 300px; height:250px;"></div></td>
                    <td><div id="machine_chart_report_not_work_legend_<?php echo $machineId; ?>" style="width: 100%; height:100%;; float:left"></div></td>
                </tr>
            </table>
        </div>
    </div>

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


        for(var i in machineIds) {
            var id = machineIds[i];

            var options = {
                series: {
                    pie: {
                        show: true,
                        radius: '110',
                        offset:{
                            top:0,
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
                    show: true,
                    noColumns: 1, // number of colums in legend table
                    labelFormatter: function (label, series) { return label.split(',').slice(1).join(','); } , // fn: string -> string
                    labelBoxBorderColor: "#ccc", // border color for the little label boxes
                    container: $('#machine_chart_report_main_legend_'+id), // container (as jQuery object) to put legend in, null means default on top of graph
                    position: "ne", // position of default legend container within plot
                    margin: 5, // distance from grid edge to default legend container within plot
                    //backgroundColor: null, // null means auto-detect
                    backgroundOpacity: 0 // set to 0 to avoid background
                }
            };


//            var data = [];
//            data[0] = { label: "Series 0", color: '#FF0000', data: 100 };
//            data[1] = { label: "Series 1", color: '#00FF00', data: 150 };
//            data[2] = { label: "Series 2", color: '#0000FF', data: 200 };
//            $.plot($('#'+'machine_chart_report_main_' + id), data, options);

            var s = $("#ReportConstructor_dtStart");
            var e = $("#ReportConstructor_dtEnd");
            var url = '';
            url += '/smto/report/liniar/?ReportLinearConstructor[dtStart]='+ s.val();
            url += '&ReportLinearConstructor[dtEnd]='+ e.val();
            url += '&ReportLinearConstructor[machineId]='+id;
            var link2Report = $('<a></a>');
            link2Report.attr('title', 'Посмотреть на линейном отчете');
            link2Report.attr('href', url);
            link2Report.attr('target', 'blank_');

            link2Report.html(dataJsonStatesMain[id]['chart']['caption']);

            if (typeof dataJsonStatesMain[id] !== 'undefined' && dataJsonStatesMain[id]['data'].length > 0) {
                var id_ = $('#'+'machine_chart_report_main_' + id);
                id_.before(link2Report);

                options.legend.container = $('#machine_chart_report_main_legend_'+id);
                var plot = $.plot(id_, dataJsonStatesMain[id]['data'], options);

//                function pieHover(event, pos, obj)
//                {
//                    var o = plot.getOptions();
//                    var c = o.legend.container;
//                    if (!obj)
//                        return;
//                    //percent = parseFloat(obj.series.percent).toFixed(2);
//                    //$("#hover").html('<span style="font-weight: bold; color: '+obj.series.color+'">'+obj.series.label+' ('+percent+'%)</span>');
//
//                    var td = c.find('table .legendLabel:eq('+obj.seriesIndex+')');
//                    if (td && !td.hasClass('selectLegend')) {
//                        td.addClass('selectLegend');
//                    }
//                }

//                options.legend.container.mouseover({a:12, b:"abc"}, function(e){
//                    var b = 1;
//                    b = b + 1;
//
//                });

                function pieClick(event, pos, obj)
                {
                    if (!obj)
                        return;
                    percent = parseFloat(obj.series.percent).toFixed(2);
                    var machine_id = $(event.target).attr('id').split('_').pop();

                    var state_code = obj.series.label.split(',')[0];

                    if (state_code == 'work')
                        $('#tabs_'+machine_id).tabs('select', 'tabs-report-work');
                    else
                        $('#tabs_'+machine_id).tabs('select', 'tabs-report-not-work');

                    //alert(''+obj.series.label+': '+percent+'%');
                }
                id_.bind("plotclick", pieClick);
                //id_.bind("plothover", pieHover);

            }

            if (typeof dataJsonStatesWork[id] !== 'undefined' && dataJsonStatesWork[id]['data'].length > 0) {
                var id_ = $('#'+'machine_chart_report_work_' + id);
                id_.before(link2Report.clone());

                options.legend.container = $('#machine_chart_report_work_legend_'+id);
                $.plot(id_, dataJsonStatesWork[id]['data'], options);
            }

            if (typeof dataJsonStatesNotWork[id] !== 'undefined' && dataJsonStatesNotWork[id]['data'].length > 0) {
                var id_ = $('#'+'machine_chart_report_not_work_' + id);
                id_.before(link2Report.clone());

                options.legend.container = $('#machine_chart_report_not_work_legend_'+id);
                $.plot(id_, dataJsonStatesNotWork[id]['data'], options);
            }
        }
    });
<?php } else if ($isRunSearch) {
    echo "Нет данных за выбранный период с <b>{$model->dtStart}</b> по <b>{$model->dtEnd}</b>";
}
?>
</script>
