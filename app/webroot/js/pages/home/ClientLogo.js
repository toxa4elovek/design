'use strict';

var _createClass = (function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ('value' in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; })();

var _get = function get(_x, _x2, _x3) { var _again = true; _function: while (_again) { var object = _x, property = _x2, receiver = _x3; desc = parent = getter = undefined; _again = false; if (object === null) object = Function.prototype; var desc = Object.getOwnPropertyDescriptor(object, property); if (desc === undefined) { var parent = Object.getPrototypeOf(object); if (parent === null) { return undefined; } else { _x = parent; _x2 = property; _x3 = receiver; _again = true; continue _function; } } else if ('value' in desc) { return desc.value; } else { var getter = desc.get; if (getter === undefined) { return undefined; } return getter.call(receiver); } } };

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError('Cannot call a class as a function'); } }

function _inherits(subClass, superClass) { if (typeof superClass !== 'function' && superClass !== null) { throw new TypeError('Super expression must either be null or a function, not ' + typeof superClass); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, enumerable: false, writable: true, configurable: true } }); if (superClass) Object.setPrototypeOf ? Object.setPrototypeOf(subClass, superClass) : subClass.__proto__ = superClass; }

var ClientLogo = (function (_React$Component) {
    _inherits(ClientLogo, _React$Component);

    function ClientLogo() {
        _classCallCheck(this, ClientLogo);

        _get(Object.getPrototypeOf(ClientLogo.prototype), 'constructor', this).call(this);
        this.animationSpeed = 300;
        this.mouseEnter = this.mouseEnter.bind(this);
        this.mouseLeave = this.mouseLeave.bind(this);
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
            return React.createElement(
                'li',
                { className: 'partner-logo-item', style: { width: this.props.width, marginRight: '25px' } },
                React.createElement(
                    'a',
                    { onMouseOver: this.mouseEnter, onMouseOut: this.mouseLeave, target: '_blank', className: 'hoverlogo', href: link },
                    React.createElement('img', { style: { paddingTop: this.props.paddingTop + 'px' }, className: 'image-on', 'data-image-status': 'off', src: this.props.imageOff, alt: this.props.title }),
                    React.createElement('img', { style: { paddingTop: this.props.paddingTop + 'px' }, className: 'image-off', 'data-image-status': 'on', src: this.props.imageOn, alt: this.props.title })
                )
            );
        }
    }]);

    return ClientLogo;
})(React.Component);