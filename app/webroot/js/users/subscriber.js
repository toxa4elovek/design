'use strict';

;(function (ReactDOM, userInfo) {
    ReactDOM.render(React.createElement(FaqCorporateBox, { data: questions }), document.getElementById('faq-corporate'));
    ReactDOM.render(React.createElement(NewProjectBox, null), document.getElementById('new-project'));
    ReactDOM.render(React.createElement(BalanceBox, { balance: userInfo.balance, isSubscriptionActive: userInfo.isSubscriptionActive, companyName: userInfo.companyName, expirationDate: userInfo.expirationDate }), document.getElementById('balance'));
    ReactDOM.render(React.createElement(FundBalanceButton, null), document.getElementById('fund-balance-button'));
    ReactDOM.render(React.createElement(ProjectSearchResultsTable, { payload: payload }), document.getElementById('subscribe-project-search-results'));
    ReactDOM.render(React.createElement(ProjectSearchBar, null), document.getElementById('subscribe-project-search'));
    $('.date-input').datetimepicker();

    $(".date-input").on("dp.change", function (e) {
        var formattedDate = '';
        $('.date-input[data-field=day]').val(e.date.format('DD'));
        $('.date-input[data-field=month]').val(e.date.format('MM'));
        $('.date-input[data-field=year]').val(e.date.format('YYYY'));
        $('.date-input[data-field=hours]').val(e.date.format('HH'));
        $('.date-input[data-field=minutes]').val(e.date.format('mm'));
    });
})(ReactDOM, userInfo);