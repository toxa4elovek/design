var NewProjectBox = React.createClass({displayName: "NewProjectBox",
    render: function() {
        return (
            React.createElement("div", null, 
                React.createElement("label", null, "Сумма вознаграждения победителю (от 500р)", React.createElement("span", null, "*")), 

                React.createElement("input", {className: "price-input", type: "text", name: "price", placeholder: "500"}), 
                React.createElement("label", null, "название проекта", React.createElement("span", null, "*")), 

                React.createElement("input", {type: "text", name: "title", className: "title-input", placeholder: "Бланк для письма"})
            )
        )
    }
});