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
    });
</script>

<!--<div id="fixme" >&nbsp;</div>-->
<?php $this->widget('application.extensions.EFlot.EFlotGraphWidget',array(
//    'id' => 'fixme',
//'excanvas.min.js',
    'scriptFile'=>array('excanvas.min.js', 'jquery.flot.js','jquery.flot.selection.js', 'jquery.flot.navigate.js', 'jquery.flot.crosshair.js')
)); ?>

<?php if (count($chartData) && isset($chartData['states']) && $chartData['states']['machine_state']) { ?>

<?php
    $plotData = array(
        'machine_state' => array(),
        'operator_last_fkey' => array()
    );
    $yAxisTicks = array();

    $yAxisTicks['machine_state'] []= array(-1, '');
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

        $yAxisTicks['machine_state'] []= array($key, $machineStateInfo['info']['name']);
    }

    $yAxisTicks['machine_state'] []= array($key+1, '');

    //foreach($chartData['machine_da_value'] as $key => $machineStateInfo) {
        $arr = array();
        $arr['label'] = '';
        $arr['color'] = $chartData['machine_da_value']['info']['color'];
        $arr['yaxis'] = 2;
        $arr['lines'] = array(
            'show' => true,
            'lineWidth' => 2,
        );
        $arr['data'] = $chartData['machine_da_value']['data'];

        $plotData['machine_da_value'] []= $arr;
    //}

    $plotData['machine_state'] = array_merge($plotData['machine_state'], $plotData['machine_da_value']);

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
        $yAxisTicks['operator_last_fkey'] []= array($key, $machineStateInfo['info']['name']);
    }

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

    $yAxisTicks['operator_last_fkey'] []= array($key+1, '');

?>

<script type="text/javascript">
    var plot1, plot2;
    var data_machine_state = <?php echo json_encode($plotData['machine_state']); ?>;
    var data_operator_last_fkey = <?php echo json_encode($plotData['operator_last_fkey']); ?>;

    var options = {
        xaxis: {
            mode: 'time',
            //minTickSize: [1, "second"]
            min: <?php echo $model->startDttoJsTimestamp(); ?>,
            max: <?php echo $model->endDttoJsTimestamp(); ?>
            //tickSize:[10, "second"],
//                timeformat: "%H:%M:%S"
        },
        yaxes: [{
            position: "right",
            reserveSpace: true,
            labelWidth: 150
        },{
            show: true,
            position: "left",
            reserveSpace: true,
            labelWidth: 40,
            color: '#FF0F0F'
        }]
        ,
        grid: {
            backgroundColor: { colors: ["#fff", "#eee"] }
        },
        selection: { mode: "x" },
        crosshair: {
            mode: "x",
            color: '#35331A',
            lineWidth: 1
        }
    };

    var options_machine_state = jQuery.extend(true, {}, options);
    var options_operator_last_fkey = jQuery.extend(true, {}, options);

    function get_data_machine_state() {
        var d = data_machine_state;
        if ($('.show_da_values').is(':checked') == false) {
            d = data_machine_state.slice(0, data_machine_state.length-1);
        }
        return d;
    }

    function get_data_operator_last_fkey() {
        var d = data_operator_last_fkey;
        return d;
    }

    options_machine_state.yaxes[0].ticks = <?php echo json_encode($yAxisTicks['machine_state']); ?>;

    options_operator_last_fkey.yaxes[0].ticks = <?php echo json_encode($yAxisTicks['operator_last_fkey']); ?>;

    function plot_chart(show, options) {

        //options_machine_state.yaxes[1].show = show;

        var d1 = get_data_machine_state();
        var d2 = get_data_operator_last_fkey();

        var placeholder1 = $("#line_report_machine_state");
        var placeholder2 = $("#line_report_operator_last_fkey");

        var o1 = options_machine_state;
        var o2 = options_operator_last_fkey;

        if (options) {
            o1 = $.extend(true, {}, o1, { xaxis: { min: options.xaxis.min, max: options.xaxis.max } });
            o2 = $.extend(true, {}, o2, { xaxis: { min: options.xaxis.min, max: options.xaxis.max } });
        }
        plot1 = $.plot( placeholder1, d1, o1 );
        plot2 = $.plot( placeholder2, d2, o2 );

        function bindSelection( event, ranges) {
            var d1 = get_data_machine_state();
            var d2 = get_data_operator_last_fkey();

            plot1 = $.plot( placeholder1, d1, $.extend(true, {}, options_machine_state,      { xaxis: { min: ranges.xaxis.from, max: ranges.xaxis.to } } ));
            plot2 = $.plot( placeholder2, d2, $.extend(true, {}, options_operator_last_fkey, { xaxis: { min: ranges.xaxis.from, max: ranges.xaxis.to } } ));

            $("#reset_selection").show('fast');
        };

        placeholder1.bind("plotselected", bindSelection);
        placeholder2.bind("plotselected", bindSelection);

        $("#reset_selection").click(function () {
            var d1 = get_data_machine_state();
            var d2 = get_data_operator_last_fkey();

            plot1 = $.plot( placeholder1, d1, options_machine_state );
            plot2 = $.plot( placeholder2, d2, options_operator_last_fkey );
            $("#reset_selection").hide('fast');
        });
    }

    $(function() {
        plot_chart(false);

        $('.show_da_values').click(function(){
            var o1 = plot1.getOptions();
            plot_chart( $(this).is(':checked'), o1);
        });
    });

</script>

<?php
    $h = 230;
    $h_slave = round($h / 1.6);
?>

<p><label>Отобразить значения аналогового датчика <input type="checkbox" class="show_da_values" ></label><button id="reset_selection" style="display: none;" >Сбросить выделение</button></p>

<H3 style="padding: 20px 0 0px 50px" >Состояния станка</H3>
<div id="line_report_machine_state" style="width:800px;height:<?php echo $h; ?>px;"></div>
<H3 style="padding: 40px 0 0px 50px" >События зарегистрированные оператором</H3>
<div id="line_report_operator_last_fkey" style="width:800px;height:<?php echo $h_slave; ?>px;"></div>

<?php }  ?>

