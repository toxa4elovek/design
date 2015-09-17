"use strict";

var _createClass = (function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; })();

var _get = function get(_x, _x2, _x3) { var _again = true; _function: while (_again) { var object = _x, property = _x2, receiver = _x3; desc = parent = getter = undefined; _again = false; if (object === null) object = Function.prototype; var desc = Object.getOwnPropertyDescriptor(object, property); if (desc === undefined) { var parent = Object.getPrototypeOf(object); if (parent === null) { return undefined; } else { _x = parent; _x2 = property; _x3 = receiver; _again = true; continue _function; } } else if ("value" in desc) { return desc.value; } else { var getter = desc.get; if (getter === undefined) { return undefined; } return getter.call(receiver); } } };

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function, not " + typeof superClass); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, enumerable: false, writable: true, configurable: true } }); if (superClass) Object.setPrototypeOf ? Object.setPrototypeOf(subClass, superClass) : subClass.__proto__ = superClass; }

var NewProjectBox = (function (_React$Component) {
    _inherits(NewProjectBox, _React$Component);

    function NewProjectBox() {
        _classCallCheck(this, NewProjectBox);

        _get(Object.getPrototypeOf(NewProjectBox.prototype), "constructor", this).apply(this, arguments);

        this.minimalPrice = 500;
    }

    _createClass(NewProjectBox, [{
        key: "componentDidMount",
        value: function componentDidMount() {
            var input = $(this.refs.award);
            input.numeric({
                "negative": false,
                "decimal": false
            });
        }
    }, {
        key: "onBlurHandler",
        value: function onBlurHandler(e) {
            if (e.target.value < this.minimalPrice) {
                e.target.value = this.minimalPrice;
            }
        }
    }, {
        key: "render",
        value: function render() {
            return React.createElement(
                "div",
                null,
                React.createElement(
                    "label",
                    null,
                    "Сумма вознаграждения победителю (от 500р)",
                    React.createElement(
                        "span",
                        null,
                        "*"
                    )
                ),
                React.createElement("input", { ref: "award", onBlur: this.onBlurHandler.bind(this), className: "price-input", type: "text", name: "price", placeholder: "500" }),
                React.createElement(
                    "label",
                    null,
                    "название проекта",
                    React.createElement(
                        "span",
                        null,
                        "*"
                    )
                ),
                React.createElement("input", { type: "text", name: "title", className: "title-input", placeholder: "Бланк для письма" }),
                React.createElement("div", { className: "clear" }),
                React.createElement(
                    "label",
                    { className: "date-label" },
                    "дата окончания приёма работ"
                ),
                React.createElement(
                    "label",
                    { className: "time-label" },
                    "время"
                ),
                React.createElement("div", { className: "clear" }),
                React.createElement("input", { type: "text", "data-field": "day", className: "date-input" }),
                React.createElement("input", { type: "text", "data-field": "month", className: "date-input" }),
                React.createElement("input", { type: "text", "data-field": "year", className: "date-input year" }),
                React.createElement("input", { type: "text", "data-field": "hours", className: "date-input" }),
                React.createElement("input", { type: "text", "data-field": "minutes", className: "date-input last-block" }),
                React.createElement("div", { className: "clear" }),
                React.createElement(
                    "a",
                    { href: "#", className: "button silver-button clean-style-button create-project-button" },
                    "создать проект"
                )
            );
        }
    }]);

    return NewProjectBox;
})(React.Component);