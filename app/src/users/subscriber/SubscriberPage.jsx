class SubscriberPage extends React.Component {
    render() {
        const data = this.props.data;
        const payload = data.payload;
        const questions = data.questions;
        const userInfo = data.userInfo;
        const settings = data.settings;
        const bottomShadow = {"boxShadow": "0px 10px 15px -15px rgba(0,0,0,0.45)"};
        return (
            <div>
                <div>
                    <div style={{"float": "left", "width": "400px"}}>
                        <section id="balance" style={bottomShadow}>
                            <BalanceBox data={userInfo} />
                        </section>
                        <section id="fund-balance-button">
                            <FundBalanceButton/>
                        </section>
                        <aside id="faq-corporate" style={bottomShadow}>
                            <FaqCorporateBox data={questions} />
                        </aside>
                    </div>
                    <section id="new-project" style={bottomShadow}>
                        <NewProjectBox/>
                    </section>
                </div>
                <div className="clear"></div>
                <div id="searchFilter">
                    <ProjectSearch payload={payload} settings={settings}/>
                </div>
            </div>
        );
    }
}
