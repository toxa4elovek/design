var LinkStyledAsButton = React.createClass({displayName: "LinkStyledAsButton",
    render: function() {
        var className = 'button clean-style-button ' + this.props.class;
        return (
            React.createElement("a", {href: this.props.link, className: className}, this.props.text)
        )
    }
});