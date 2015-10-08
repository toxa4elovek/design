class FundBalanceButton extends React.Component{
    render() {
        const style = {"fontSize": "11px"};
        return (
            <a className="button fund-balance clean-style-button"
                href="/subscription_plans/subscriber"
                style={style}
                target='_blank'
            >
                пополнить счёт
            </a>
        );
    }
}