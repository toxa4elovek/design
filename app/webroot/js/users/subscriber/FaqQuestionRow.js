var FaqQuestionRow = React.createClass({displayName: "FaqQuestionRow",
    render: function() {
        var link = "http://www.godesigner.ru/answers/view/" + this.props.object.id;
        return (React.createElement("li", null, React.createElement("a", {href: link}, this.props.object.title)))
    }
});