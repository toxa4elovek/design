var FaqQuestionRow = React.createClass({
    render: function() {
        var link = "http://www.godesigner.ru/answers/view/" + this.props.object.id;
        return (<li><a href={link}>{this.props.object.title}</a></li>)
    }
});