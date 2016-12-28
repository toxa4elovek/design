"use strict";

var _createClass = (function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; })();

var _get = function get(_x, _x2, _x3) { var _again = true; _function: while (_again) { var object = _x, property = _x2, receiver = _x3; desc = parent = getter = undefined; _again = false; if (object === null) object = Function.prototype; var desc = Object.getOwnPropertyDescriptor(object, property); if (desc === undefined) { var parent = Object.getPrototypeOf(object); if (parent === null) { return undefined; } else { _x = parent; _x2 = property; _x3 = receiver; _again = true; continue _function; } } else if ("value" in desc) { return desc.value; } else { var getter = desc.get; if (getter === undefined) { return undefined; } return getter.call(receiver); } } };

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function, not " + typeof superClass); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, enumerable: false, writable: true, configurable: true } }); if (superClass) Object.setPrototypeOf ? Object.setPrototypeOf(subClass, superClass) : subClass.__proto__ = superClass; }

var TopPanelRow = (function (_React$Component) {
    _inherits(TopPanelRow, _React$Component);

    function TopPanelRow() {
        _classCallCheck(this, TopPanelRow);

        _get(Object.getPrototypeOf(TopPanelRow.prototype), "constructor", this).apply(this, arguments);
    }

    _createClass(TopPanelRow, [{
        key: "__formatMoney",
        value: function __formatMoney(amount) {
            amount = amount.replace(/(.*)\.00/, "$1");
            while (amount.match(/\w\w\w\w/)) {
                amount = amount.replace(/^(\w*)(\w\w\w)(\W.*)?$/, "$1 $2$3");
            }
            return amount;
        }
    }, {
        key: "render",
        value: function render() {
            var data = this.props.data;
            var rowClassName = 'selection coda ' + data.tableClass;
            var status = '';
            console.log(data);

            return React.createElement(
                "tr",
                { "data-id": data.id, className: rowClassName },
                React.createElement("td", null),
                React.createElement(
                    "td",
                    { className: "pitches-name mypitches" },
                    React.createElement(
                        "a",
                        { href: "http://godesigner.ru/pitches/view/104899" },
                        data.title
                    )
                ),
                React.createElement(
                    "td",
                    { className: "pitches-status mypitches" },
                    React.createElement(
                        "a",
                        { href: "http://godesigner.ru/pitches/edit/104899" },
                        status
                    )
                ),
                React.createElement(
                    "td",
                    { className: "price mypitches" },
                    this.__formatMoney(data.price),
                    ".-                                "
                ),
                React.createElement(
                    "td",
                    { className: "pitches-edit mypitches" },
                    React.createElement(
                        "a",
                        { href: "http://godesigner.ru/pitches/edit/104899#step3", className: "mypitch_pay_link buy", title: "оплатить" },
                        "оплатить"
                    ),
                    React.createElement(
                        "a",
                        { href: "http://godesigner.ru/pitches/edit/104899", className: "edit mypitch_edit_link", title: "редактировать" },
                        "редактировать"
                    ),
                    React.createElement(
                        "a",
                        { "data-id": "104899", href: "http://godesigner.ru/pitches/delete/104899", className: "delete deleteheader mypitch_delete_link", title: "удалить" },
                        "удалить"
                    )
                )
            );
        }
    }]);

    return TopPanelRow;
})(React.Component);