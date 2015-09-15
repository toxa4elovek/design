class PaymentTypesList extends React.Component {
    render() {
        const settings = this.props.settings;
        return (
            <div>
                {settings.map(function(item) {
                    console.log(item);
                    return item.node;
                })}
            </div>
        );
    }
}