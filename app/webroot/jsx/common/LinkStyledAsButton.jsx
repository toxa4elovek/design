var LinkStyledAsButton = React.createClass({
    render: function() {
        var className = 'button clean-style-button ' + this.props.class;
        return (
            <a href={this.props.link} className={className}>{this.props.text}</a>
        )
    }
});