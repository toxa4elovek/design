var NewProjectBox = React.createClass({displayName: "NewProjectBox",
    render: function() {
        return (
            React.createElement("div", null, 
                React.createElement("label", null, "Сумма вознаграждения победителю (от 500р)", React.createElement("span", null, "*")), 

                React.createElement("input", {className: "price-input", type: "text", name: "price", placeholder: "500"}), 
                React.createElement("label", null, "название проекта", React.createElement("span", null, "*")), 

                React.createElement("input", {type: "text", name: "title", className: "title-input", placeholder: "Бланк для письма"}), 

                React.createElement("div", {className: "clear"}), 
                React.createElement("label", {className: "date-label"}, "дата окончания приёма работ"), 
                React.createElement("label", {className: "time-label"}, "время"), 
                React.createElement("div", {className: "clear"}), 

                React.createElement("input", {type: "text", className: "date-input"}), 
                React.createElement("input", {type: "text", className: "date-input"}), 
                React.createElement("input", {type: "text", className: "date-input year"}), 

                React.createElement("input", {type: "text", className: "date-input"}), 
                React.createElement("input", {type: "text", className: "date-input last-block"}), 
                React.createElement("div", {className: "clear"}), 

                React.createElement("a", {href: "#", className: "button silver-button clean-style-button"}, "создать проект")

            )
        )
    }
});