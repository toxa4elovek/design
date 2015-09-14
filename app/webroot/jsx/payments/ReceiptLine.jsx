class ReceiptLine extends React.Component{
    render() {
        const row = this.props.row;
        const amount = row.amount + '.-';
        return (
            <li>
                <span>{row.name}</span>
                <small>{amount}</small>
            </li>
        )
    }
}