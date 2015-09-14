class ProfRadioList extends React.Component{
    render() {
        return (
            <div className="profselectbox-container">
                {this.props.data.map(function(props) {
                    return (
                        <ProfSelectBox key={props.id} data={props} />
                    )
                })}
            </div>
        )
    }
}