class SubscriberPage extends React.Component {
    render() {
        const data = this.props.data;
        const payload = data.payload;
        const questions = data.questions;
        const userInfo = data.userInfo;
        const settings = data.settings;
        return (
            <div>
                <div>
                    <div style={{"float": "left", "width": "400px"}}>
                        <section id="balance">
                            <BalanceBox data={userInfo} />
                        </section>
                        <section id="fund-balance-button">
                            <FundBalanceButton/>
                        </section>
                        <aside id="faq-corporate">
                            <FaqCorporateBox data={questions} />
                        </aside>
                    </div>
                    <section id="new-project">
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
