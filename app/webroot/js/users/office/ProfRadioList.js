var ProfRadioList = new React.createClass({
    render: function() {
        return (
            React.createElement("div", {className: "profselectbox-container"}, 
                this.props.data.map(function(props) {
                    return (
                        React.createElement(ProfSelectBox, {data: props})
                    )
                })
            )
        )
    }
});