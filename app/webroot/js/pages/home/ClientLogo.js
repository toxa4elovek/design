'use strict';

var _createClass = (function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; })();

Object.defineProperty(exports, "__esModule", {
    value: true
});

var _react = require('react');

var _react2 = _interopRequireDefault(_react);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _possibleConstructorReturn(self, call) { if (!self) { throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); } return call && (typeof call === "object" || typeof call === "function") ? call : self; }

function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function, not " + typeof superClass); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, enumerable: false, writable: true, configurable: true } }); if (superClass) Object.setPrototypeOf ? Object.setPrototypeOf(subClass, superClass) : subClass.__proto__ = superClass; }

var ClientLogo = (function (_React$Component) {
    _inherits(ClientLogo, _React$Component);

    function ClientLogo() {
        _classCallCheck(this, ClientLogo);

        var _this = _possibleConstructorReturn(this, Object.getPrototypeOf(ClientLogo).call(this));

        _this.animationSpeed = 300;
        _this.mouseEnter = _this.mouseEnter.bind(_this);
        _this.mouseLeave = _this.mouseLeave.bind(_this);
        return _this;
    }

    _createClass(ClientLogo, [{
        key: 'mouseEnter',
        value: function mouseEnter(event) {
            this.targetToggle(event, 'enter');
        }
    }, {
        key: 'mouseLeave',
        value: function mouseLeave(event) {
            this.targetToggle(event, 'leave');
        }
    }, {
        key: 'targetToggle',
        value: function targetToggle(event, type) {
            var target = $(event.target);
            var onImage = target;
            var image = target.prev();
            var imageOpacity = 0;
            var onImageOpacity = 1;
            if ('on' != target.data('image-status')) {
                image = target;
                onImage = target.prev();
            }
            if ('leave' === type) {
                imageOpacity = 1;
                onImageOpacity = 0;
            }
            image.animate({ opacity: imageOpacity }, this.animationSpeed);
            onImage.animate({ opacity: onImageOpacity }, this.animationSpeed);
        }
    }, {
        key: 'render',
        value: function render() {
            var link = '/pitches/view/' + this.props.id;
            return _react2.default.createElement(
                'li',
                { className: 'partner-logo-item', style: { width: this.props.width, marginRight: '25px' } },
                _react2.default.createElement(
                    'a',
                    { onMouseOver: this.mouseEnter, onMouseOut: this.mouseLeave, target: '_blank', className: 'hoverlogo', href: link },
                    _react2.default.createElement('img', { style: { paddingTop: this.props.paddingTop + 'px' }, className: 'image-on', 'data-image-status': 'off', src: this.props.imageOff, alt: this.props.title }),
                    _react2.default.createElement('img', { style: { paddingTop: this.props.paddingTop + 'px' }, className: 'image-off', 'data-image-status': 'on', src: this.props.imageOn, alt: this.props.title })
                )
            );
        }
    }]);

    return ClientLogo;
})(_react2.default.Component);

exports.default = ClientLogo;