class FaqCorporateBox extends React.Component{
    render() {
        return (
            <ul>
                <li className="faq-corporate-title">FAQ</li>
                { this.props.data.map(function(row){
                    return <FaqQuestionRow key={row.id} row={row} />})
                }
            </ul>
        )
    }
}