"use strict";

var _createClass = (function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; })();

var _get = function get(_x, _x2, _x3) { var _again = true; _function: while (_again) { var object = _x, property = _x2, receiver = _x3; desc = parent = getter = undefined; _again = false; if (object === null) object = Function.prototype; var desc = Object.getOwnPropertyDescriptor(object, property); if (desc === undefined) { var parent = Object.getPrototypeOf(object); if (parent === null) { return undefined; } else { _x = parent; _x2 = property; _x3 = receiver; _again = true; continue _function; } } else if ("value" in desc) { return desc.value; } else { var getter = desc.get; if (getter === undefined) { return undefined; } return getter.call(receiver); } } };

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function, not " + typeof superClass); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, enumerable: false, writable: true, configurable: true } }); if (superClass) Object.setPrototypeOf ? Object.setPrototypeOf(subClass, superClass) : subClass.__proto__ = superClass; }

var BalanceBox = (function (_React$Component) {
    _inherits(BalanceBox, _React$Component);

    function BalanceBox() {
        _classCallCheck(this, BalanceBox);

        _get(Object.getPrototypeOf(BalanceBox.prototype), "constructor", this).apply(this, arguments);
    }

    _createClass(BalanceBox, [{
        key: "componentDidMount",
        value: function componentDidMount() {}
    }, {
        key: "render",
        value: function render() {
            var subscriptionStatus = React.createElement(
                "span",
                null,
                "не действителен"
            );
            if (this.props.isSubscriptionActive) {
                subscriptionStatus = React.createElement(
                    "span",
                    null,
                    "Тариф «",
                    this.props.plan.title,
                    "»",
                    React.createElement("br", null),
                    "действителен до ",
                    this.props.expirationDate
                );
            }
            return React.createElement(
                "div",
                null,
                React.createElement(
                    "h6",
                    null,
                    "Ваш текущий счет"
                ),
                React.createElement(
                    "span",
                    null,
                    this.props.companyName
                ),
                React.createElement("br", null),
                subscriptionStatus,
                React.createElement("br", null),
                React.createElement(
                    "span",
                    { className: "balance" },
                    this.props.balance,
                    "р."
                )
            );
        }
    }, {
        key: "updateBalance",
        value: function updateBalance() {
            $.get('/users/subscriber.json').done((function (data) {
                this.setProps(data);
            }).bind(this));
        }
    }]);

    return BalanceBox;
})(React.Component);