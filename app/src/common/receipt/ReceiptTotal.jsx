class ReceiptTotal extends React.Component {
    render() {
        let total = this.props.total;
        total += '.-';
        return (
            <span>{total}</span>
        )
    }
}