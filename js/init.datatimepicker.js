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

    $( "[id$=dtStart],[id$=dtEnd]" ).datetimepicker({
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
});