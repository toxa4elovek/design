'use strict';

var _createClass = (function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; })();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _possibleConstructorReturn(self, call) { if (!self) { throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); } return call && (typeof call === "object" || typeof call === "function") ? call : self; }

function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function, not " + typeof superClass); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, enumerable: false, writable: true, configurable: true } }); if (superClass) Object.setPrototypeOf ? Object.setPrototypeOf(subClass, superClass) : subClass.__proto__ = superClass; }

var PhoneNumberInput = (function (_React$Component) {
  _inherits(PhoneNumberInput, _React$Component);

  function PhoneNumberInput() {
    _classCallCheck(this, PhoneNumberInput);

    var _this = _possibleConstructorReturn(this, Object.getPrototypeOf(PhoneNumberInput).call(this));

    _this.placeholderValue = '7 911 123 45 67';
    return _this;
  }

  _createClass(PhoneNumberInput, [{
    key: 'onBlur',
    value: function onBlur(e) {
      if (e.target.value === '') {
        e.target.value = this.placeholderValue;
      }
      var currentValue = e.target.value;
      PaymentActions.submitNewPhoneNumber(currentValue);
    }
  }, {
    key: 'onFocus',
    value: function onFocus(e) {
      if (e.target.value == this.placeholderValue) {
        e.target.value = '';
      }
      return true;
    }
  }, {
    key: 'onChange',
    value: function onChange(e) {
      var newValue = e.target.value;
      PaymentActions.updatePhoneNumberInput(newValue);
    }
  }, {
    key: 'render',
    value: function render() {
      var initialValue = this.props.phoneNumber;
      var styles = { 'color': '#4f5159' };
      if (initialValue === this.placeholderValue) {
        styles = { 'color': '#ccc' };
      }
      return React.createElement(
        'div',
        null,
        React.createElement('input', {
          style: styles,
          ref: 'input',
          type: 'text',
          onChange: this.onChange.bind(this),
          onFocus: this.onFocus.bind(this),
          onBlur: this.onBlur.bind(this),
          defaultValue: initialValue,
          className: 'phone-number-input' })
      );
    }
  }]);

  return PhoneNumberInput;
})(React.Component);