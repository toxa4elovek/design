(function (ReactDOM, userInfo) {
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
}) (ReactDOM, userInfo);