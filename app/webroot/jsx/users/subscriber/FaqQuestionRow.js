var FaqQuestionRow = React.createClass({
    render: function() {
        var link = "http://www.godesigner.ru/answers/view/" + object.id;
        return (<li><a href={link}>{object.title}</a></li>)
    }
});