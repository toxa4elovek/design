;(function() {
    $('#scroll-button').on('click', function() {
        $.scrollTo(
            $('section.lp-table'),
            {duration: 300}
        );
        return false;
    });
    const discountEndDateInput = $('input[name=discount_end_date]');
    if(discountEndDateInput.length > 0) {
        const discountEndDate = $(discountEndDateInput).val();
        ReactDOM.render(
            <CountdownTimer data={discountEndDate} />,
            document.getElementById('timerMount')
        );
    }
    setTimeout(function() {
        Chatra('show');
        Chatra('openChat');
    }, 30000);
})();