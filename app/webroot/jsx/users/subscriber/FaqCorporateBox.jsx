class FaqCorporateBox extends React.Component{
    render() {
        return (
            <ul>
                <li className="faq-corporate-title">FAQ</li>
                { this.props.data.map(function(object){
                    return <FaqQuestionRow key={object.id} object={object} />})
                }
        </ul>)
    }
}