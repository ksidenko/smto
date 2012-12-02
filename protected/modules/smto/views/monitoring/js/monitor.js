$(function() {
    var refresh_period = $('#monitor-refresh-period');

    function updateData () {
        $.ajax({
            url: '/smto/monitoring/monitor',
            method: 'POST',
            dataType: 'json',
            //data: params_,
            success: function (data) {
                var flash_error = $('div.flash-error');

                if (data.errors == '') {
                    flash_error.slideUp().html('');
                } else {
                    flash_error.html(data.errors).slideDown();
                    //$('.monitor-info').html('');
                }
                $('.monitor-info').html(data.html);
            },
            error: function ($data) {
                //alert($data);
            }
        });

        var s = parseInt(refresh_period.val());
        if (s < 1) {
            s = 5;
        }
        setTimeout(updateData, s * 1000);
    }

    updateData ();
});