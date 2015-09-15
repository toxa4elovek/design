"use strict";

var _createClass = (function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; })();

var _get = function get(_x, _x2, _x3) { var _again = true; _function: while (_again) { var object = _x, property = _x2, receiver = _x3; desc = parent = getter = undefined; _again = false; if (object === null) object = Function.prototype; var desc = Object.getOwnPropertyDescriptor(object, property); if (desc === undefined) { var parent = Object.getPrototypeOf(object); if (parent === null) { return undefined; } else { _x = parent; _x2 = property; _x3 = receiver; _again = true; continue _function; } } else if ("value" in desc) { return desc.value; } else { var getter = desc.get; if (getter === undefined) { return undefined; } return getter.call(receiver); } } };

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function, not " + typeof superClass); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, enumerable: false, writable: true, configurable: true } }); if (superClass) Object.setPrototypeOf ? Object.setPrototypeOf(subClass, superClass) : subClass.__proto__ = superClass; }

var PaymentPaymaster = (function (_React$Component) {
    _inherits(PaymentPaymaster, _React$Component);

    function PaymentPaymaster() {
        _classCallCheck(this, PaymentPaymaster);

        _get(Object.getPrototypeOf(PaymentPaymaster.prototype), "constructor", this).apply(this, arguments);
    }

    _createClass(PaymentPaymaster, [{
        key: "paymentSystemClick",
        value: function paymentSystemClick(id) {
            var amInput = $("#pmOpenAmount");
            if (0 < amInput.length && !amInput.val().match(/^\s*\d+.?\d*\s*$/)) {
                alert('Укажите, пожалуйста, корректную сумму платежа');
                return false;
            }
            $("#pmwidgetPS").val(id);
            $("#pmwidgetForm").submit();
        }
    }, {
        key: "render",
        value: function render() {
            var paySystems = this.props.payload.paySystems;
            var total = this.props.payload.total + '.00';
            var projectId = this.props.payload.projectId;
            return React.createElement(
                "div",
                { className: "payment paymaster" },
                React.createElement("input", { type: "radio", name: "payment-options", "data-pay": "paymaster" }),
                React.createElement(
                    "span",
                    { className: "description" },
                    "Оплата электронными деньгами через PayMaster"
                ),
                React.createElement("img", { className: "imageblock", src: "/img/s3_paymaster.png", alt: "Дебетовые и кредитные карты" }),
                React.createElement(
                    "div",
                    { className: "widget" },
                    React.createElement(
                        "div",
                        { className: "pmwidget pmwidgetDone", style: { "width": "471px" } },
                        React.createElement("a", { onClick: this.paymentSystemClick.bind(this), href: "http://paymaster.ru/", className: "pmlogo" }),
                        React.createElement(
                            "h1",
                            { className: "pmheader" },
                            " Выберите способ оплаты"
                        ),
                        React.createElement(
                            "p",
                            { className: "pmdesc" },
                            React.createElement(
                                "strong",
                                null,
                                "Описание:"
                            ),
                            " Оплата проекта"
                        ),
                        React.createElement(
                            "p",
                            { className: "pmamount" },
                            React.createElement(
                                "strong",
                                null,
                                "Сумма: "
                            ),
                            total,
                            " RUB"
                        ),
                        React.createElement(
                            "label",
                            { className: "pmpaymenttype" },
                            " Способ оплаты:"
                        ),
                        React.createElement(
                            "div",
                            { className: "payList", style: { "height": "auto", "overflow": "hidden" } },
                            paySystems.map(function (system) {
                                return React.createElement(PaymentPaymasterPaySystem, {
                                    onMouseEnter: this.paymentSystemClick.bind(this),
                                    key: system.id,
                                    total: total,
                                    projectId: projectId,
                                    paySystem: system,
                                    clickCallback: this.paymentSystemClick
                                });
                            }, this),
                            React.createElement("div", { className: "clearfix" })
                        ),
                        React.createElement(
                            "form",
                            { id: "pmwidgetForm", method: "POST", action: "https://paymaster.ru/Payment/Init" },
                            React.createElement(
                                "div",
                                null,
                                React.createElement("input", { type: "hidden", name: "LMI_MERCHANT_ID", value: "d5d2e177-6ed1-4e5f-aac6-dd7ea1c16f60" }),
                                React.createElement("input", { type: "hidden", name: "LMI_CURRENCY", value: "RUB" }),
                                React.createElement("input", { type: "hidden", name: "LMI_PAYMENT_AMOUNT", id: "pmOpenAmount", value: total }),
                                React.createElement("input", { type: "hidden", name: "LMI_PAYMENT_NO", value: projectId }),
                                React.createElement("input", { type: "hidden", name: "LMI_PAYMENT_DESC", value: "Оплата проекта" }),
                                React.createElement("input", { type: "hidden", name: "LMI_PAYMENT_SYSTEM", id: "pmwidgetPS", ref: "inputPaymentSystem" })
                            )
                        )
                    )
                )
            );
        }
    }]);

    return PaymentPaymaster;
})(React.Component);