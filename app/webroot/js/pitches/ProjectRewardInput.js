"use strict";

var _createClass = (function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; })();

var _get = function get(_x, _x2, _x3) { var _again = true; _function: while (_again) { var object = _x, property = _x2, receiver = _x3; desc = parent = getter = undefined; _again = false; if (object === null) object = Function.prototype; var desc = Object.getOwnPropertyDescriptor(object, property); if (desc === undefined) { var parent = Object.getPrototypeOf(object); if (parent === null) { return undefined; } else { _x = parent; _x2 = property; _x3 = receiver; _again = true; continue _function; } } else if ("value" in desc) { return desc.value; } else { var getter = desc.get; if (getter === undefined) { return undefined; } return getter.call(receiver); } } };

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function, not " + typeof superClass); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, enumerable: false, writable: true, configurable: true } }); if (superClass) Object.setPrototypeOf ? Object.setPrototypeOf(subClass, superClass) : subClass.__proto__ = superClass; }

var ProjectRewardInput = (function (_React$Component) {
    _inherits(ProjectRewardInput, _React$Component);

    function ProjectRewardInput() {
        _classCallCheck(this, ProjectRewardInput);

        _get(Object.getPrototypeOf(ProjectRewardInput.prototype), "constructor", this).apply(this, arguments);

        this.walletKey = 'Награда Дизайнеру';
        this.minimalPrice = 500;
    }

    _createClass(ProjectRewardInput, [{
        key: "componentDidMount",
        value: function componentDidMount() {
            var input = $(this.refs.input);
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
                SubscribedBriefActions.updateWinnerReward(parseInt(e.target.value));
            }
        }
    }, {
        key: "onChangeHandle",
        value: function onChangeHandle(e) {
            e.target.value = e.target.value || 0;
            SubscribedBriefActions.updateWinnerReward(parseInt(e.target.value));
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
            var style = {
                "float": "left",
                "display": "block",
                "width": "250px",
                "height": "74px",
                "marginTop": "4px",
                "fontFamily": "Arial, serif",
                "fontSize": "37px",
                "lineHeight": "37px",
                "color": "#000000" };
            return React.createElement(
                "div",
                null,
                React.createElement("input", { ref: "input", type: "text", onBlur: this.onBlurHandler.bind(this), onChange: this.onChangeHandle, onKeyDown: this.onKeydownHandle, defaultValue: initialValue, placeholder: "8000", style: style })
            );
        }
    }]);

    return ProjectRewardInput;
})(React.Component);