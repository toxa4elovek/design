'use strict';

var _createClass = (function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ('value' in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; })();

var _get = function get(_x, _x2, _x3) { var _again = true; _function: while (_again) { var object = _x, property = _x2, receiver = _x3; desc = parent = getter = undefined; _again = false; if (object === null) object = Function.prototype; var desc = Object.getOwnPropertyDescriptor(object, property); if (desc === undefined) { var parent = Object.getPrototypeOf(object); if (parent === null) { return undefined; } else { _x = parent; _x2 = property; _x3 = receiver; _again = true; continue _function; } } else if ('value' in desc) { return desc.value; } else { var getter = desc.get; if (getter === undefined) { return undefined; } return getter.call(receiver); } } };

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError('Cannot call a class as a function'); } }

function _inherits(subClass, superClass) { if (typeof superClass !== 'function' && superClass !== null) { throw new TypeError('Super expression must either be null or a function, not ' + typeof superClass); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, enumerable: false, writable: true, configurable: true } }); if (superClass) Object.setPrototypeOf ? Object.setPrototypeOf(subClass, superClass) : subClass.__proto__ = superClass; }

var PaymentPaymasterPaySystem = (function (_React$Component) {
    _inherits(PaymentPaymasterPaySystem, _React$Component);

    function PaymentPaymasterPaySystem() {
        _classCallCheck(this, PaymentPaymasterPaySystem);

        _get(Object.getPrototypeOf(PaymentPaymasterPaySystem.prototype), 'constructor', this).apply(this, arguments);
    }

    _createClass(PaymentPaymasterPaySystem, [{
        key: 'onClickHandler',
        value: function onClickHandler(event) {
            event.preventDefault();
            this.props.clickCallback(this.props.paySystem.id);
        }
    }, {
        key: 'render',
        value: function render() {
            var paySystem = this.props.paySystem;
            var link = 'https://paymaster.ru/Payment/Init?LMI_PAYMENT_SYSTEM=' + paySystem.id + '&amp;LMI_MERCHANT_ID=d5d2e177-6ed1-4e5f-aac6-dd7ea1c16f60&amp;LMI_CURRENCY=RUB&amp;LMI_PAYMENT_AMOUNT=' + this.props.total + '&amp;LMI_PAYMENT_NO=' + this.props.projectId + '&amp;LMI_PAYMENT_DESC=%d0%9e%d0%bf%d0%bb%d0%b0%d1%82%d0%b0+%d0%bf%d1%80%d0%be%d0%b5%d0%ba%d1%82%d0%b0';
            return React.createElement(
                'a',
                { onClick: this.onClickHandler.bind(this), href: link, rel: paySystem.id, className: 'pm-item paySystem', title: paySystem.title },
                React.createElement('img', { src: paySystem.logo, alt: '', title: paySystem.title })
            );
        }
    }]);

    return PaymentPaymasterPaySystem;
})(React.Component);