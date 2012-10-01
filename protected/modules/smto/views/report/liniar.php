<?php $timeRange = $model->getTimeRanges();?>

<div class="form">
    <?php $form=$this->beginWidget('CActiveForm'); ?>

    <?php echo $form->errorSummary($model); ?>

    <table width="500px" >
        <tr>
            <td>
                <?php echo $form->dropDownList($model,'timeRange', $timeRange['labels']); ?>
                <?php //echo $form->label($model,'dtStart'); ?>
            </td>
            <td>
                <?php echo $form->textField($model,'dtStart') ?>
                <?php //echo $form->label($model,'dtEnd'); ?>
                <?php echo $form->textField($model,'dtEnd') ?>
            </td>
        </tr>
        <tr>
            <td>
                <?php echo $form->label($model,'machineId'); ?>
                <?php //echo $form->dropDownList($model,'machineReportType', $model::$arrMachineReportType) ?>
            </td>
            <td>
                <?php echo $form->dropDownList($model, 'machineId', CHtml::listData( Machine::model()->real_records()->findAll(array('order' => 'name')), 'id', 'name') ); ?>
            </td>
        </tr>
    </table>

    <div class="row submit">
        <?php echo CHtml::submitButton('Отобразить'); ?>

        <button id="reset_selection" style="display: none; float:right; " >Сбросить выделение</button>
        <div style="clear:both;"></div>
    </div>

    <?php $this->endWidget(); ?>
</div><!-- form -->

<script>
    $(function() {


        $.datepicker.regional['ru'] = {
            closeText: 'Закрыть',
            prevText: '<Пред',
            nextText: 'След>',
            currentText: 'Сегодня',
            monthNames: ['Январь','Февраль','Март','Апрель','Май','Июнь',
                'Июль','Август','Сентябрь','Октябрь','Ноябрь','Декабрь'],
            monthNamesShort: ['Янв','Фев','Мар','Апр','Май','Июн',
                'Июл','Авг','Сен','Окт','Ноя','Дек'],
            dayNames: ['воскресенье','понедельник','вторник','среда','четверг','пятница','суббота'],
            dayNamesShort: ['вск','пнд','втр','срд','чтв','птн','сбт'],
            dayNamesMin: ['Вс','Пн','Вт','Ср','Чт','Пт','Сб'],
            weekHeader: 'Не',
            dateFormat: 'dd.mm.yy',
            firstDay: 1,
            isRTL: false,
            showMonthAfterYear: false,
            yearSuffix: ''
        };
        $.datepicker.setDefaults($.datepicker.regional['ru']);

        $.timepicker.regional['ru'] = {
            timeOnlyTitle: 'Выберите время',
            timeText: 'Время',
            hourText: 'Часы',
            minuteText: 'Минуты',
            secondText: 'Секунды',
            millisecText: 'миллисекунды',
            currentText: 'Текущее время',
            closeText: 'Закрыть',
            ampm: false
        };
        $.timepicker.setDefaults($.timepicker.regional['ru']);

        $( "#ReportLinearConstructor_dtStart,#ReportLinearConstructor_dtEnd" ).datetimepicker({
                changeMonth: true,
                changeYear: true,
                timeFormat: 'hh:mm:ss',
                showSecond: true,
                stepHour: 1,
                stepMinute: 1,
                stepSecond: 1,
                hourGrid: 4,
                minuteGrid: 10,
                secondGrid: 10
            }
        );

        $("#ReportLinearConstructor_dtStart").datetimepicker('setDate', $("#ReportLinearConstructor_dtStart").val() );
        $("#ReportLinearConstructor_dtEnd").datetimepicker('setDate', $("#ReportLinearConstructor_dtEnd").val() );

        var timeRange = <?php echo json_encode($timeRange); ?>;

        $('#ReportLinearConstructor_timeRange').change(function(){
            var v = $(this).val();
            var s = timeRange['values'][v]['start_date'];
            var e = timeRange['values'][v]['end_date'];

            $("#ReportLinearConstructor_dtStart").datetimepicker('setDate', s );
            $("#ReportLinearConstructor_dtEnd").datetimepicker('setDate', e );
        });
    });
</script>

<!--<div id="fixme" >&nbsp;</div>-->
<?php $this->widget('application.extensions.EFlot.EFlotGraphWidget',array(
//    'id' => 'fixme',
//'excanvas.min.js',
    'scriptFile'=>array('excanvas.min.js', 'jquery.flot.js','jquery.flot.selection.js', 'jquery.flot.navigate.js', 'jquery.flot.crosshair.js', 'jquery.flot.threshold.js')
)); ?>

<?php if (count($chartData) && isset($chartData['states']) && $chartData['states']['machine_state']) { ?>

<?php
    $plotData = array(
        'machine_state' => array(),
        'operator_last_fkey' => array()
    );
    $yAxisTicks = array();

    $yAxisTicks['machine_state'] []= array(-1, '');
    $yAxisTicks['machine_state'] []= array(MachineState::STATE_MACHINE_ON, 'Включен');
    $yAxisTicks['machine_state'] []= array(MachineState::STATE_MACHINE_WORK, 'Работает');

    foreach($chartData['states']['machine_state'] as $key => $machineStateInfo) {
        $arr = array();
        $arr['label'] = '';
        $arr['color'] = $machineStateInfo['info']['color'];
        $arr['yaxis'] = 1;
        $arr['lines'] = array(
            'show' => true,
            'lineWidth' => 7,
        );
        $arr['data'] = $machineStateInfo['data'];

        $plotData['machine_state'] []= $arr;

        //$yAxisTicks['machine_state'] []= array($key, $machineStateInfo['info']['name']);
    }

    $yAxisTicks['machine_state'] []= array(MachineState::STATE_MACHINE_WORK + 2, '');

    //foreach($chartData['machine_da_value'] as $key => $machineStateInfo) {
        $arr = array();
        $arr['label'] = '';
        $arr['color'] = $chartData['machine_da_value']['info']['color'];
        $arr['yaxis'] = 1;
        $arr['lines'] = array(
            'show' => true,
            'lineWidth' => 2,
        );
        $arr['data'] = $chartData['machine_da_value']['data'];

        $arr['threshold'] = $chartData['machine_da_value']['info']['threshold'];

        $plotData['machine_detector_analog_value'] []= $arr;
        //print_r($plotData['machine_detector_analog_value']); die;
    //}

    //$plotData['machine_state'] = array_merge($plotData['machine_state'], $plotData['machine_da_value']);

    $yAxisTicks['operator_last_fkey'] []= array(0, '');
    foreach($chartData['states']['operator_last_fkey'] as $key => $machineStateInfo) {
        $arr = array();
        $arr['label'] = '';
       // $arr['color'] = isset($machineStateInfo['info']['color']) ? $machineStateInfo['info']['color'] : '';
        $arr['lines'] = array(
            'show' => true,
            'lineWidth' => 7,
        );
        $arr['data'] = $machineStateInfo['data'];

        $plotData['operator_last_fkey'] []= $arr;
        //$yAxisTicks['operator_last_fkey'] []= array($key, $machineStateInfo['info']['name']);
    }


    if (count($plotData['operator_last_fkey']) == 0) {
        $plotData['operator_last_fkey'] []= array('data' => array(1,1));
    }

    $yAxisTicks['operator_last_fkey'] = $yAxisTicks['machine_state'];

    $eventsData = MachineEvent::getList();

    foreach($eventsData as $row) {
        $key = $row->id + MachineEvent::$idOffset;
        $yAxisTicks['operator_last_fkey'] []= array($key, $row->name);
    }
    $yAxisTicks['operator_last_fkey'] []= array($key + 1, '');


    $plotData['operator_last_fkey'] = array_merge($plotData['operator_last_fkey'], $plotData['machine_state']);

//TODO
//    foreach($chartData['states']['operator'] as $key => $machineStateInfo) {
//        $arr = array();
//        $arr['label'] = '';
//        // $arr['color'] = isset($machineStateInfo['info']['color']) ? $machineStateInfo['info']['color'] : '';
//        $arr['lines'] = array(
//            'show' => true,
//            'lineWidth' => 7,
//        );
//        $arr['data'] = $machineStateInfo['data'];
//
//        $plotData['operator_last_fkey'] []= $arr;
//        $yAxisTicks['operator_last_fkey'] []= array($key, $machineStateInfo['info']['name']);
//    }

    //$yAxisTicks['operator_last_fkey'] []= array($key+1, '');
?>

<script type="text/javascript">
    var plot0, plot1, plot2;

    var data_machine_detector_analog_value = <?php echo json_encode($plotData['machine_detector_analog_value']); ?>;
    var data_machine_state = <?php echo json_encode($plotData['machine_state']); ?>;
    var data_operator_last_fkey = <?php echo json_encode($plotData['operator_last_fkey']); ?>;

    var options = {
        xaxis: {
            show: false,
            mode: 'time',
            //minTickSize: [1, "second"]
            min: <?php echo $model->startDttoJsTimestamp(); ?>,
            max: <?php echo $model->endDttoJsTimestamp(); ?>
            //tickSize:[10, "second"],
//                timeformat: "%H:%M:%S"
        },
        yaxis: {
            position: "right",
            reserveSpace: true,
            labelWidth: 150
        },
        grid: {
            backgroundColor: { colors: ["#fff", "#eee"] },
            hoverable: true,
            autoHighlight: false,
            mouseActiveRadius: 200
        },
        selection: { mode: "x" },
        crosshair: {
            mode: "x",
            color: '#35331A',
            lineWidth: 1
        },
        hooks: {
            processDatapoints: [hide_axis]
        }
    };

    var options_machine_detector_analog_value = jQuery.extend(true, {}, options);
    var options_machine_state = jQuery.extend(true, {}, options);
    var options_operator_last_fkey = jQuery.extend(true, {}, options);

    options_machine_state.xaxis.show = true;

    function hide_axis(plot, serie) {
        function formatterEmpty(val, axis) {
            return '';
        }

        if (serie.xaxis.options.show == false) {
            serie.xaxis.options.tickFormatter = formatterEmpty;
        }
    }

    function get_data_machine_detector_analog_value() {
        var d = data_machine_detector_analog_value;
        return d;
    }

//    function get_data_machine_state() {
//        var d = data_machine_state;
////        if ($('.show_da_values').is(':checked') == false) {
////            d = data_machine_state.slice(0, data_machine_state.length-1);
////        }
//        return d;
//    }

    function get_data_operator_last_fkey() {
        var d = data_operator_last_fkey;
        return d;
    }

    //options_machine_detector_analog_value.yaxis.ticks = [0,200];
    //options_machine_state.yaxis.ticks = <?php echo json_encode($yAxisTicks['machine_state']); ?>;
    options_operator_last_fkey.yaxis.ticks = <?php echo json_encode($yAxisTicks['operator_last_fkey']); ?>;

    function plot_chart(show, options) {

        var d0 = get_data_machine_detector_analog_value();
        //var d1 = get_data_machine_state();
        var d2 = get_data_operator_last_fkey();

        var placeholder0 = $("#line_report_machine_detector_analog_value");
        //var placeholder1 = $("#line_report_machine_state");
        var placeholder2 = $("#line_report_operator_last_fkey");

        var o0 = options_machine_detector_analog_value;
        //var o1 = options_machine_state;
        var o2 = options_operator_last_fkey;

        if (options) {
            o0 = $.extend(true, {}, o0, { xaxis: { min: options.xaxis.min, max: options.xaxis.max } });
            //o1 = $.extend(true, {}, o1, { xaxis: { min: options.xaxis.min, max: options.xaxis.max } });
            o2 = $.extend(true, {}, o2, { xaxis: { min: options.xaxis.min, max: options.xaxis.max } });
        }
        plot0 = $.plot( placeholder0, d0, o0 );
        //plot1 = $.plot( placeholder1, d1, o1 );
        plot2 = $.plot( placeholder2, d2, o2 );

        function bindSelection( event, ranges) {
            var d0 = get_data_machine_detector_analog_value();
            //var d1 = get_data_machine_state();
            var d2 = get_data_operator_last_fkey();

            plot0 = $.plot( placeholder0, d0, $.extend(true, {}, options_machine_detector_analog_value,      { xaxis: { min: ranges.xaxis.from, max: ranges.xaxis.to } } ));
            //plot1 = $.plot( placeholder1, d1, $.extend(true, {}, options_machine_state,      { xaxis: { min: ranges.xaxis.from, max: ranges.xaxis.to } } ));
            plot2 = $.plot( placeholder2, d2, $.extend(true, {}, options_operator_last_fkey, { xaxis: { min: ranges.xaxis.from, max: ranges.xaxis.to } } ));

            $("#reset_selection").show('fast');

            return false;
        };

        placeholder0.bind("plotselected", bindSelection);
        //placeholder1.bind("plotselected", bindSelection);
        placeholder2.bind("plotselected", bindSelection);

        $("#reset_selection").click(function () {
            var d0 = get_data_machine_detector_analog_value();
            //var d1 = get_data_machine_state();
            var d2 = get_data_operator_last_fkey();

            plot0 = $.plot( placeholder0, d0, options_machine_detector_analog_value );
            //plot1 = $.plot( placeholder1, d1, options_machine_state );
            plot2 = $.plot( placeholder2, d2, options_operator_last_fkey );

            $("#reset_selection").hide('fast');

            return false;
        });

        $.each([placeholder0, placeholder2], function(i, p) {
            p.bind("plothover", function (event, pos, item) {
                var plots = [plot0, plot2];
                plots.splice(i, 1);
                $.each( plots, function(j, elem) {
                    elem.setCrosshair(pos);
                });
            });

            p.bind("mouseout", function (event) {
                var plots = [plot0, plot2];
                plots.splice(i, 1);
                $.each( plots, function(j, elem) {
                    elem.clearCrosshair();
                });
            });
        });

        $.each([placeholder0, placeholder2], function(i, p) {
            p.bind("plotselecting", function(event, ranges) {
                if (ranges) {
                    var plots = [plot0, plot2];
                    plots.splice(i, 1);
                    $.each( plots, function(j, elem) {
                        elem.setSelection({ xaxis: ranges.xaxis }, true);
                        elem.clearCrosshair();
                    });
                }
            })
        });

    }

    $(function() {
        plot_chart(false);

        var p0 = $("#line_report_machine_detector_analog_value");

        $('.show_da_values').click(function(){
            p0.toggle();
            //console.log(p0.is(':visible'));
            //var o1 = plot1.getOptions();
            //plot_chart( p0.is(':visible'), o1);
        });

        var updateLegendTimeout = null;
        var latestPosition = null;

        function updateLegend() {
            updateLegendTimeout = null;

            var pos = latestPosition;


            var axes = plot0.getAxes();
            if (pos.x < axes.xaxis.min || pos.x > axes.xaxis.max ||
                pos.y < axes.yaxis.min || pos.y > axes.yaxis.max)
                return;

            var i, j, dataset = plot0.getData();
            for (i = 0; i < dataset.length; ++i) {
                var series = dataset[i];

                // find the nearest points, x-wise
                for (j = 0; j < series.data.length; ++j)
                    if (series.data[j][0] > pos.x)
                        break;

                // now interpolate
                var y, p1 = series.data[j - 1], p2 = series.data[j];
                if (p1 == null)
                    y = p2[1];
                else if (p2 == null)
                    y = p1[1];
                else
                    y = p1[1] + (p2[1] - p1[1]) * (pos.x - p1[0]) / (p2[0] - p1[0]);

                //console.log(pos.y.toFixed(2));

                if (latestItem) {
                    //if (previousPoint != latestItem.dataIndex) {
                    //    previousPoint = latestItem.dataIndex;

                        $("#tooltip").remove();
//                        var x = latestItem.datapoint[0].toFixed(2),
//                            y = latestItem.datapoint[1].toFixed(2);

                        showTooltip(latestItem.pageX, latestItem.pageY, y.toFixed(2));
                    //}
                } else {
                   $("#tooltip").remove();
                    previousPoint = null;
                }

                $('#legend').html(y.toFixed(2));
            }
        }

        var previousPoint = null, latestItem = null;

        $("#line_report_machine_detector_analog_value").bind("plothover",  function (event, pos, item) {
            latestPosition = pos;
            latestItem = item;
            if (!updateLegendTimeout) {
                $("#tooltip").remove();
                previousPoint = null;
                updateLegendTimeout = setTimeout(updateLegend, 50);
            }

//            if (item) {
//                if (previousPoint != item.dataIndex) {
//                    previousPoint = item.dataIndex;
//
//                    $("#tooltip").remove();
//                    var x = item.datapoint[0].toFixed(2),
//                        y = item.datapoint[1].toFixed(2);
//
//                    showTooltip(item.pageX, item.pageY, y);
//                }
//            } else {
//                $("#tooltip").remove();
//                previousPoint = null;
//            }
        });
    });


    function showTooltip(x, y, contents) {
        $('<div id="tooltip">' + contents + '</div>').css( {
            position: 'absolute',
            display: 'none',
            top: y + 5,
            left: x + 5,
            border: '1px solid #fdd',
            padding: '2px',
            'background-color': '#fee',
            opacity: 0.80
        }).appendTo("body").fadeIn(200);
    }
</script>

<?php
    $h = 220;
    $w = 940;
    $h_slave1 = round($h / 1);
    //$h_slave2 = round($h_slave1 / 1.6);
?>

<p>
<!--    <label>Отобразить значения аналогового датчика <input type="checkbox" class="show_da_values" ></label>-->
<!--<div id="legend"></div>-->
</p>

<div id="line_report_machine_detector_analog_value" style="width:<?php echo $w; ?>px;height:<?php echo $h; ?>px;"></div>
<div id="line_report_operator_last_fkey" style="width:<?php echo $w; ?>px;height:<?php echo $h_slave1; ?>px;"></div>
<!--<div id="line_report_machine_state" style="width:<?php //echo $w; ?>px;height:<?php //echo $h_slave2; ?>px; display:none;"></div>-->


<?php }  ?>

