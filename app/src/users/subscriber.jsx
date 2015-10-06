;(function () {
    ReactDOM.render(
        <SubscriberPage data={data} />,
        document.getElementById('pageMount')
    );
    const dateInput = $('.hidden-date-input');
    dateInput.datetimepicker({locale: 'ru'});
    dateInput.on("dp.change", function(e) {
        $('.date-input[data-field=day]').val(e.date.format('DD'));
        $('.date-input[data-field=month]').val(e.date.format('MM'));
        $('.date-input[data-field=year]').val(e.date.format('YYYY'));
        $('.date-input[data-field=hours]').val(e.date.format('HH'));
        $('.date-input[data-field=minutes]').val(e.date.format('mm'));
    });
}) ();