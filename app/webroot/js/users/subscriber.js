'use strict';

;(function (ReactDOM, userInfo) {
    ReactDOM.render(React.createElement(FaqCorporateBox, { data: questions }), document.getElementById('faq-corporate'));
    ReactDOM.render(React.createElement(NewProjectBox, null), document.getElementById('new-project'));
    ReactDOM.render(React.createElement(BalanceBox, { balance: userInfo.balance, isSubscriptionActive: userInfo.isSubscriptionActive, companyName: userInfo.companyName, expirationDate: userInfo.expirationDate }), document.getElementById('balance'));
    ReactDOM.render(React.createElement(FundBalanceButton, null), document.getElementById('fund-balance-button'));
    ReactDOM.render(React.createElement(ProjectSearchResultsTable, null), document.getElementById('subscribe-project-search-results'));
    ReactDOM.render(React.createElement(ProjectSearchBar, null), document.getElementById('subscribe-project-search'));
})(ReactDOM, userInfo);