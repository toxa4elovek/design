'use strict';

var _createClass = (function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ('value' in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; })();

var _get = function get(_x, _x2, _x3) { var _again = true; _function: while (_again) { var object = _x, property = _x2, receiver = _x3; desc = parent = getter = undefined; _again = false; if (object === null) object = Function.prototype; var desc = Object.getOwnPropertyDescriptor(object, property); if (desc === undefined) { var parent = Object.getPrototypeOf(object); if (parent === null) { return undefined; } else { _x = parent; _x2 = property; _x3 = receiver; _again = true; continue _function; } } else if ('value' in desc) { return desc.value; } else { var getter = desc.get; if (getter === undefined) { return undefined; } return getter.call(receiver); } } };

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError('Cannot call a class as a function'); } }

function _inherits(subClass, superClass) { if (typeof superClass !== 'function' && superClass !== null) { throw new TypeError('Super expression must either be null or a function, not ' + typeof superClass); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, enumerable: false, writable: true, configurable: true } }); if (superClass) Object.setPrototypeOf ? Object.setPrototypeOf(subClass, superClass) : subClass.__proto__ = superClass; }

var ProfSelectBox = (function (_React$Component) {
    _inherits(ProfSelectBox, _React$Component);

    function ProfSelectBox() {
        _classCallCheck(this, ProfSelectBox);

        _get(Object.getPrototypeOf(ProfSelectBox.prototype), 'constructor', this).call(this);
        this.onChange = this.onChange.bind(this);
    }

    _createClass(ProfSelectBox, [{
        key: 'onChange',
        value: function onChange() {
            var input = this.refs.radioInput;
            var value = input.checked ? 1 : 0;
            var info = this.props.data;
            var userCompanySection = $('.user-company-section');
            var userDetailsSection = $('.user-details-section');
            var data = {};
            console.log('change');
            if ('is_company' === info.name && input.checked) {
                userCompanySection.show();
                $.scrollTo(userCompanySection, {
                    axis: 'y',
                    duration: 500
                });
            } else {
                userCompanySection.hide();
            }
            if (('isDesigner' === info.name || 'isCopy' === info.name) && input.checked) {
                userDetailsSection.show();
            } else {
                userDetailsSection.hide();
            }
            data[info.name] = value;
            $.post('/users/update.json', data);
        }
    }, {
        key: 'render',
        value: function render() {
            var info = this.props.data;
            var checked = false;
            if (1 == info.isDesigner) {
                checked = true;
            }
            var input = React.createElement('input', { ref: 'radioInput', onChange: this.onChange, type: 'radio', name: 'prof', className: info.name, value: '1', defaultChecked: checked });
            return React.createElement(
                'div',
                { className: 'radio-container', style: { "marginLeft": info['margin-left'] } },
                React.createElement(
                    'label',
                    null,
                    input,
                    info.title
                ),
                React.createElement('input', { type: 'hidden', name: info.name, value: '0' })
            );
        }
    }]);

    return ProfSelectBox;
})(React.Component);