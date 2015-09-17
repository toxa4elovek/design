;(function (ReactDOM, userInfo) {
    ReactDOM.render(
        <FaqCorporateBox data={questions} />,
        document.getElementById('faq-corporate')
    );
    ReactDOM.render(
        <NewProjectBox/>,
        document.getElementById('new-project')
    );
    ReactDOM.render(
        <BalanceBox balance={userInfo.balance} isSubscriptionActive={userInfo.isSubscriptionActive} companyName={userInfo.companyName} expirationDate={userInfo.expirationDate}/>,
        document.getElementById('balance')
    );
    ReactDOM.render(
        <FundBalanceButton/>,
        document.getElementById('fund-balance-button')
    );
    ReactDOM.render(
        <ProjectSearchResultsTable/>,
        document.getElementById('subscribe-project-search-results')
    );
    ReactDOM.render(
        <ProjectSearchBar/>,
        document.getElementById('subscribe-project-search')
    );
    $('.date-input').datetimepicker();

    $(".date-input").on("dp.change", function(e) {
        let formattedDate = '';
        $('.date-input[data-field=day]').val(e.date.format('DD'));
        $('.date-input[data-field=month]').val(e.date.format('MM'));
        $('.date-input[data-field=year]').val(e.date.format('YYYY'));
        $('.date-input[data-field=hours]').val(e.date.format('HH'));
        $('.date-input[data-field=minutes]').val(e.date.format('mm'));
    });
}) (ReactDOM, userInfo);