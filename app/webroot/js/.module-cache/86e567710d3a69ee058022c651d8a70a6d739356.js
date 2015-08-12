var FaqQuestionRow = React.createClass({displayName: "FaqQuestionRow",
    render: function() {
        var link = "http://www.godesigner.ru/answers/view/" + object.id;
        return (React.createElement("li", null, React.createElement("a", {href: link}, object.title)))
    },
});