var ProfRadioList = new React.createClass({
    render: function() {
        return (
            <div className="profselectbox-container">
                {this.props.data.map(function(props) {
                    return (
                        <ProfSelectBox data={props} />
                    )
                })}
            </div>
        )
    }
});