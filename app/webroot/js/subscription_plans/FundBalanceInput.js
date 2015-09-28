"use strict";

var _createClass = (function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; })();

var _get = function get(_x, _x2, _x3) { var _again = true; _function: while (_again) { var object = _x, property = _x2, receiver = _x3; desc = parent = getter = undefined; _again = false; if (object === null) object = Function.prototype; var desc = Object.getOwnPropertyDescriptor(object, property); if (desc === undefined) { var parent = Object.getPrototypeOf(object); if (parent === null) { return undefined; } else { _x = parent; _x2 = property; _x3 = receiver; _again = true; continue _function; } } else if ("value" in desc) { return desc.value; } else { var getter = desc.get; if (getter === undefined) { return undefined; } return getter.call(receiver); } } };

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function, not " + typeof superClass); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, enumerable: false, writable: true, configurable: true } }); if (superClass) Object.setPrototypeOf ? Object.setPrototypeOf(subClass, superClass) : subClass.__proto__ = superClass; }

var FundBalanceInput = (function (_React$Component) {
    _inherits(FundBalanceInput, _React$Component);

    function FundBalanceInput() {
        _classCallCheck(this, FundBalanceInput);

        _get(Object.getPrototypeOf(FundBalanceInput.prototype), "constructor", this).apply(this, arguments);
<<<<<<< HEAD

        this.walletKey = 'Пополнение счёта';
=======
>>>>>>> 3e8050002ec4901a5268a9909d19572875d5fa8e
    }

    _createClass(FundBalanceInput, [{
        key: "componentDidMount",
        value: function componentDidMount() {
            var input = $(this.refs.input);
            input.numeric({
                "negative": false,
                "decimal": false
            }, function () {});
        }
    }, {
        key: "onChangeHandle",
        value: function onChangeHandle(e) {
<<<<<<< HEAD
            e.target.value = e.target.value || 0;
            PaymentActions.updateFundBalanceInput(parseInt(e.target.value));
        }
    }, {
        key: "__getReceiptValue",
        value: function __getReceiptValue(receipt) {
            var value = 0;
            receipt.forEach((function (row) {
                if (row.name == this.walletKey) {
                    value = row.value;
                }
            }).bind(this));
            return value;
        }
    }, {
        key: "render",
        value: function render() {
            var initialValue = this.__getReceiptValue(this.props.payload.receipt);
            return React.createElement(
                "div",
                null,
                React.createElement("input", { ref: "input", type: "text", onChange: this.onChangeHandle, onKeyDown: this.onKeydownHandle, defaultValue: initialValue, className: "fund-balance-input", placeholder: "8000" })
=======
            PaymentActions.updateFundBalanceInput(parseInt(e.target.value));
        }
    }, {
        key: "render",
        value: function render() {
            return React.createElement(
                "div",
                null,
                React.createElement("input", { ref: "input", type: "text", onChange: this.onChangeHandle, onKeyDown: this.onKeydownHandle, className: "fund-balance-input", placeholder: "9000" })
>>>>>>> 3e8050002ec4901a5268a9909d19572875d5fa8e
            );
        }
    }]);

    return FundBalanceInput;
})(React.Component);