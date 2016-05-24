'use strict';

Object.defineProperty(exports, "__esModule", {
    value: true
});

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

var _react = require('react');

var _react2 = _interopRequireDefault(_react);

var _reactCssModules = require('react-css-modules');

var _reactCssModules2 = _interopRequireDefault(_reactCssModules);

var _MainPageProjectTable = require('./MainPageProjectTable.css');

var _MainPageProjectTable2 = _interopRequireDefault(_MainPageProjectTable);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _possibleConstructorReturn(self, call) { if (!self) { throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); } return call && (typeof call === "object" || typeof call === "function") ? call : self; }

function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function, not " + typeof superClass); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, enumerable: false, writable: true, configurable: true } }); if (superClass) Object.setPrototypeOf ? Object.setPrototypeOf(subClass, superClass) : subClass.__proto__ = superClass; }

var MainPageProjectTable = function (_React$Component) {
    _inherits(MainPageProjectTable, _React$Component);

    function MainPageProjectTable() {
        _classCallCheck(this, MainPageProjectTable);

        return _possibleConstructorReturn(this, Object.getPrototypeOf(MainPageProjectTable).apply(this, arguments));
    }

    _createClass(MainPageProjectTable, [{
        key: 'render',
        value: function render() {
            var fontStyle = { "fontSize": "11px", "color": "#666666" };
            return _react2.default.createElement(
                'table',
                { styleName: 'spec_table' },
                _react2.default.createElement(
                    'tbody',
                    null,
                    _react2.default.createElement(
                        'tr',
                        null,
                        _react2.default.createElement(
                            'th',
                            { style: fontStyle },
                            'текущие проекты'
                        ),
                        _react2.default.createElement(
                            'th',
                            { styleName: 'price_th', style: fontStyle },
                            'цена'
                        ),
                        _react2.default.createElement(
                            'th',
                            { style: fontStyle },
                            'идей'
                        ),
                        _react2.default.createElement(
                            'th',
                            { styleName: 'term_th', style: fontStyle },
                            'Срок'
                        )
                    ),
                    _react2.default.createElement(
                        'tr',
                        { styleName: 'odd' },
                        _react2.default.createElement(
                            'td',
                            { styleName: 'pitches-name' },
                            _react2.default.createElement(
                                'a',
                                { href: '/pitches/view/108073' },
                                'Название для оператора сотовой связи '
                            ),
                            _react2.default.createElement('br', null)
                        ),
                        _react2.default.createElement(
                            'td',
                            null,
                            '33 000.-'
                        ),
                        _react2.default.createElement(
                            'td',
                            null,
                            '733'
                        ),
                        _react2.default.createElement(
                            'td',
                            null,
                            '1 день 23 часа'
                        )
                    ),
                    _react2.default.createElement(
                        'tr',
                        { styleName: 'even' },
                        _react2.default.createElement(
                            'td',
                            { styleName: 'pitches-name' },
                            _react2.default.createElement(
                                'a',
                                { href: '/pitches/view/108288' },
                                'Логотип для сети кофеен CITY COFFEE HOUSE'
                            ),
                            _react2.default.createElement('br', null)
                        ),
                        _react2.default.createElement(
                            'td',
                            null,
                            '8 000.-'
                        ),
                        _react2.default.createElement(
                            'td',
                            null,
                            '91'
                        ),
                        _react2.default.createElement(
                            'td',
                            null,
                            '2 дня 10 часов'
                        )
                    ),
                    _react2.default.createElement(
                        'tr',
                        { styleName: 'odd' },
                        _react2.default.createElement(
                            'td',
                            { styleName: 'pitches-name' },
                            _react2.default.createElement(
                                'a',
                                { href: '/pitches/view/108095' },
                                'Сайт - лэндинг для магазина подгузников и средств по уходу за детьми'
                            ),
                            _react2.default.createElement('br', null)
                        ),
                        _react2.default.createElement(
                            'td',
                            null,
                            '30 000.-'
                        ),
                        _react2.default.createElement(
                            'td',
                            null,
                            '10'
                        ),
                        _react2.default.createElement(
                            'td',
                            null,
                            '2 дня 17 часов'
                        )
                    )
                )
            );
        }
    }]);

    return MainPageProjectTable;
}(_react2.default.Component);

exports.default = (0, _reactCssModules2.default)(MainPageProjectTable, _MainPageProjectTable2.default);