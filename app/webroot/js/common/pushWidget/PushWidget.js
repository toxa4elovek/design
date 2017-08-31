'use strict';

Object.defineProperty(exports, "__esModule", {
  value: true
});

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

var _react = require('react');

var _react2 = _interopRequireDefault(_react);

var _PushWidget = require('./PushWidget.css');

var _PushWidget2 = _interopRequireDefault(_PushWidget);

var _PushWidgetActions = require('./PushWidgetActions.jsx');

var _PushWidgetActions2 = _interopRequireDefault(_PushWidgetActions);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _possibleConstructorReturn(self, call) { if (!self) { throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); } return call && (typeof call === "object" || typeof call === "function") ? call : self; }

function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function, not " + typeof superClass); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, enumerable: false, writable: true, configurable: true } }); if (superClass) Object.setPrototypeOf ? Object.setPrototypeOf(subClass, superClass) : subClass.__proto__ = superClass; }

var PushWidget = function (_React$Component) {
  _inherits(PushWidget, _React$Component);

  function PushWidget(props) {
    _classCallCheck(this, PushWidget);

    var _this = _possibleConstructorReturn(this, (PushWidget.__proto__ || Object.getPrototypeOf(PushWidget)).call(this, props));

    _this.confirmButtonClick = _this.confirmButtonClick.bind(_this);
    _this.denyButtonClick = _this.denyButtonClick.bind(_this);
    _this.fluxActions = new _PushWidgetActions2.default();
    _this.state = {
      'display': 'block'
    };
    return _this;
  }

  _createClass(PushWidget, [{
    key: 'confirmButtonClick',
    value: function confirmButtonClick() {
      this.fluxActions.confirmClick();
      this.setState({
        'display': 'none'
      });
      return false;
    }
  }, {
    key: 'denyButtonClick',
    value: function denyButtonClick() {
      this.fluxActions.denyClick();
      this.setState({
        'display': 'none'
      });
      return false;
    }
  }, {
    key: 'render',
    value: function render() {
      return _react2.default.createElement(
        'div',
        { style: this.state, className: _PushWidget2.default['push-widget-box'] },
        _react2.default.createElement(
          'header',
          null,
          _react2.default.createElement(
            'h3',
            null,
            '\u041F\u043E\u043A\u0430\u0437\u044B\u0432\u0430\u0442\u044C \u0432\u0430\u0436\u043D\u044B\u0435',
            _react2.default.createElement('br', null),
            ' \u043D\u043E\u0432\u043E\u0441\u0442\u0438 \u0432 \u0431\u0440\u0430\u0443\u0437\u0435\u0440\u0435?'
          )
        ),
        _react2.default.createElement(
          'main',
          null,
          _react2.default.createElement(
            'span',
            { onClick: this.confirmButtonClick, className: _PushWidget2.default['push-widget-box-confirm-button'] },
            '\u0414\u0430, \u0445\u043E\u0447\u0443 \u0437\u043D\u0430\u0442\u044C!'
          ),
          _react2.default.createElement(
            'span',
            { onClick: this.denyButtonClick, className: _PushWidget2.default['push-widget-box-deny-button'] },
            '\u043D\u0435 \u043D\u0443\u0436\u043D\u043E'
          )
        )
      );
    }
  }]);

  return PushWidget;
}(_react2.default.Component);

exports.default = PushWidget;