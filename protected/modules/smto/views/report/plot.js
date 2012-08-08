$(function () {
    var data = [];
    $.plots = [];

    function updateData() {
        var params = {};
        var i = 0;

        var dt_start = $('#dt_start').val();
        var dt_delta_sec = $('#dt_delta_sec').val();
        dt_start = parseInt(dt_start) + parseInt(dt_delta_sec);
        $('#dt_start').val(dt_start);

        $('.machine_plot').each(function() {
            var machine_id = $('#machine_id', this).val();
            var name = $('#name', this).val();

            params [i]= {
                'machine_id' : machine_id,
                'name' : name,
                'dt_start' : dt_start,
                'dt_delta_sec' : dt_delta_sec
            };
            i++;
        });
        var params_ = $.param({'dataCondition' : params}, false);
        
        // then fetch the data with jQuery
        function onDataReceived(series) {

            for(var machine_id in series) {
                for(var name in series[machine_id]) {
                    var id = 'plot_' + machine_id + '_' + name;
                    var data = {
                        label : name,
                        lines: {
                            show: true
                        },
                        points: {
                            show: true
                        },
                        data :  series[machine_id][name] 
                    };
                    
                    var options = {
                        legend:{
                            position: 'nw',
                            show: false,
                            margin: 10, 
                            noColumns: 7,
                            backgroundOpacity: 0.5
                        },
                        lines: {show: true},
                        points: {show: true},
                        xaxis: { 
                            mode: 'time',
                            minTickSize: [5, "second"],
                            //tickSize:[10, "second"],
                            timeformat: "%H:%M:%S"
                            /*
                            tickFormatter: function (val, axis) {
                                var objToday  = new Date(val);
                                curHour = objToday.getHours() < 10 ? "0" + objToday.getHours() : objToday.getHours();
                                curMinute = objToday.getMinutes() < 10 ? "0" + objToday.getMinutes() : objToday.getMinutes();
                                curSeconds = objToday.getSeconds() < 10 ? "0" + objToday.getSeconds() : objToday.getSeconds();
                                var today = curHour + ":" + curMinute + ":" + curSeconds;
                                return today;
                	        }
                	        */
                        }
                    };
                    if (typeof $.plots[id] == 'undefined') {
                        $.plots[id] = $.plot($('#'+id), [data], options);
                    } else {
                        var plot = $.plots[id];
                        var old_data = plot.getData();
                        old_data.data = old_data[0].data.concat(data.data);
                        if (old_data.data.length > 30) {
                            old_data.data = old_data.data.slice(1);
                        }
                            
                        plot.setData([old_data]);
                        plot.setupGrid();
                        plot.draw();
                        //$.plots[id] = $.plot($('#'+id), [old_data], options);
                    }
    
                    //var data = series[machine_id][name];
                    //data = [[0,0],];
                    //$.plot( $('#'+id), data, plot.getOptions() );
                    //
                }
            }
         }

        $.ajax({
            url: '/smto/report/monitoring',
            method: 'POST',
            dataType: 'json',
            data: params_,
            success: onDataReceived
        });
        
        var update_plots = $('#update_plots').is(':checked');
        if (update_plots) {
            var s = parseInt($('#update_interval').val());
            if (s < 1) {
                s = 1;
            }
            setTimeout(updateData, s * 1000);
        }
    };
        
    updateData();
    
    $('#update_plots').change(function(){
        if($(this).is(':checked')) {
            updateData();
        }
    });
    
        
//    // fetch one series, adding to what we got
//    var alreadyFetched = {};
//    
//    $("input.fetchSeries").click(function () {
//        var button = $(this);
//    });
//
//
//    // initiate a recurring data update
//    $("input.dataUpdate").click(function () {
//        // reset data
//        data = [];
//        alreadyFetched = {};
//        
//        $.plot(placeholder, data, options);
//
//        var iteration = 0;
//        
//        function fetchData() {
//            ++iteration;
//
//            function onDataReceived(series) {
//                // we get all the data in one go, if we only got partial
//                // data, we could merge it with what we already got
//                data = [ series ];
//                
//                $.plot($("#placeholder"), data, options);
//            }
//        
//            $.ajax({
//                // usually, we'll just call the same URL, a script
//                // connected to a database, but in this case we only
//                // have static example files so we need to modify the
//                // URL
//                url: "data-eu-gdp-growth-" + iteration + ".json",
//                method: 'GET',
//                dataType: 'json',
//                success: onDataReceived
//            });
//            
//            if (iteration < 5)
//                setTimeout(fetchData, 1000);
//            else {
//                data = [];
//                alreadyFetched = {};
//            }
//        }
//
//        setTimeout(fetchData, 1000);
//    });
});
