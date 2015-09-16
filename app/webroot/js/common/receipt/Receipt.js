'use strict';

var _createClass = (function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ('value' in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; })();

var _get = function get(_x, _x2, _x3) { var _again = true; _function: while (_again) { var object = _x, property = _x2, receiver = _x3; desc = parent = getter = undefined; _again = false; if (object === null) object = Function.prototype; var desc = Object.getOwnPropertyDescriptor(object, property); if (desc === undefined) { var parent = Object.getPrototypeOf(object); if (parent === null) { return undefined; } else { _x = parent; _x2 = property; _x3 = receiver; _again = true; continue _function; } } else if ('value' in desc) { return desc.value; } else { var getter = desc.get; if (getter === undefined) { return undefined; } return getter.call(receiver); } } };

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError('Cannot call a class as a function'); } }

function _inherits(subClass, superClass) { if (typeof superClass !== 'function' && superClass !== null) { throw new TypeError('Super expression must either be null or a function, not ' + typeof superClass); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, enumerable: false, writable: true, configurable: true } }); if (superClass) Object.setPrototypeOf ? Object.setPrototypeOf(subClass, superClass) : subClass.__proto__ = superClass; }

var Receipt = (function (_React$Component) {
    _inherits(Receipt, _React$Component);

    function Receipt() {
        _classCallCheck(this, Receipt);

        _get(Object.getPrototypeOf(Receipt.prototype), 'constructor', this).call(this);
        this.hideLinkOnClick = this.hideLinkOnClick.bind(this);
        this.showLinkOnClick = this.showLinkOnClick.bind(this);
    }

    _createClass(Receipt, [{
        key: 'hideLinkOnClick',
        value: function hideLinkOnClick(e) {
            var aside = this.refs.receiptContainer;
            $(aside).removeClass('expanded');
            e.preventDefault();
        }
    }, {
        key: 'showLinkOnClick',
        value: function showLinkOnClick(e) {
            var aside = this.refs.receiptContainer;
            $(aside).addClass('expanded');
            e.preventDefault();
        }
    }, {
        key: 'render',
        value: function render() {
            var total = 0;
            for (var i = 0; i < this.props.data.length; i++) {
                total += this.props.data[i].value;
            }
            return React.createElement(
                'aside',
                { ref: 'receiptContainer', className: 'summary-price expanded' },
                React.createElement(
                    'h3',
                    null,
                    'Итого:'
                ),
                React.createElement(
                    'p',
                    { className: 'summary' },
                    React.createElement(
                        'strong',
                        null,
                        React.createElement(ReceiptTotal, { total: total })
                    )
                ),
                React.createElement(
                    'ul',
                    null,
                    this.props.data.map(function (object, index) {
                        return React.createElement(ReceiptLine, { key: index, row: object });
                    })
                ),
                React.createElement(
                    'a',
                    { href: '#', className: 'show', onClick: this.showLinkOnClick },
                    React.createElement(
                        'span',
                        null,
                        'Подробнее'
                    )
                ),
                React.createElement(
                    'a',
                    { href: '#', className: 'hide', onClick: this.hideLinkOnClick },
                    React.createElement(
                        'span',
                        null,
                        'Скрыть'
                    )
                )
            );
        }
    }]);

    return Receipt;
})(React.Component);