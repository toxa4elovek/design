"use strict";

var _createClass = (function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; })();

var _get = function get(_x, _x2, _x3) { var _again = true; _function: while (_again) { var object = _x, property = _x2, receiver = _x3; desc = parent = getter = undefined; _again = false; if (object === null) object = Function.prototype; var desc = Object.getOwnPropertyDescriptor(object, property); if (desc === undefined) { var parent = Object.getPrototypeOf(object); if (parent === null) { return undefined; } else { _x = parent; _x2 = property; _x3 = receiver; _again = true; continue _function; } } else if ("value" in desc) { return desc.value; } else { var getter = desc.get; if (getter === undefined) { return undefined; } return getter.call(receiver); } } };

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function, not " + typeof superClass); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, enumerable: false, writable: true, configurable: true } }); if (superClass) Object.setPrototypeOf ? Object.setPrototypeOf(subClass, superClass) : subClass.__proto__ = superClass; }

var TopPanelContainer = (function (_BaseComponent) {
    _inherits(TopPanelContainer, _BaseComponent);

    function TopPanelContainer() {
        _classCallCheck(this, TopPanelContainer);

        _get(Object.getPrototypeOf(TopPanelContainer.prototype), "constructor", this).apply(this, arguments);
    }

    _createClass(TopPanelContainer, [{
        key: "render",
        value: function render() {
            var data = this.props.data;
            return React.createElement(
                "div",
                { id: "pitch-panel" },
                React.createElement(
                    "div",
                    { className: "conteiner" },
                    React.createElement(
                        "div",
                        { className: "content" },
                        React.createElement(
                            "table",
                            { className: "all-pitches", id: "header-table" },
                            React.createElement(
                                "tbody",
                                null,
                                data.map(function (row, index) {
                                    if ((index + 1) % 2) {
                                        row.tableClass = 'odd';
                                    } else {
                                        row.tableClass = 'even';
                                    }
                                    return React.createElement(TopPanelRow, { key: row.id, data: row });
                                })
                            )
                        ),
                        React.createElement(
                            "p",
                            { className: "pitch-buttons-legend" },
                            React.createElement(
                                "a",
                                { href: "http://www.godesigner.ru/answers/view/6" },
                                React.createElement("i", { id: "help" }),
                                "Какие способы оплаты вы принимаете?"
                            )
                        )
                    )
                )
            );
        }
    }]);

    return TopPanelContainer;
})(BaseComponent);