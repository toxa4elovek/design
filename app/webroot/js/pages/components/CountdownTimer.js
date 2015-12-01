'use strict';

var _createClass = (function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ('value' in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; })();

var _get = function get(_x, _x2, _x3) { var _again = true; _function: while (_again) { var object = _x, property = _x2, receiver = _x3; desc = parent = getter = undefined; _again = false; if (object === null) object = Function.prototype; var desc = Object.getOwnPropertyDescriptor(object, property); if (desc === undefined) { var parent = Object.getPrototypeOf(object); if (parent === null) { return undefined; } else { _x = parent; _x2 = property; _x3 = receiver; _again = true; continue _function; } } else if ('value' in desc) { return desc.value; } else { var getter = desc.get; if (getter === undefined) { return undefined; } return getter.call(receiver); } } };

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError('Cannot call a class as a function'); } }

function _inherits(subClass, superClass) { if (typeof superClass !== 'function' && superClass !== null) { throw new TypeError('Super expression must either be null or a function, not ' + typeof superClass); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, enumerable: false, writable: true, configurable: true } }); if (superClass) Object.setPrototypeOf ? Object.setPrototypeOf(subClass, superClass) : subClass.__proto__ = superClass; }

var CountdownTimer = (function (_React$Component) {
    _inherits(CountdownTimer, _React$Component);

    function CountdownTimer(props) {
        _classCallCheck(this, CountdownTimer);

        _get(Object.getPrototypeOf(CountdownTimer.prototype), 'constructor', this).call(this);
        this.deadLine = props.data;
    }

    _createClass(CountdownTimer, [{
        key: 'componentDidMount',
        value: function componentDidMount() {
            this.setInterval(this.tick, 1000);
        }
    }, {
        key: 'setInterval',
        value: (function (_setInterval) {
            function setInterval() {
                return _setInterval.apply(this, arguments);
            }

            setInterval.toString = function () {
                return _setInterval.toString();
            };

            return setInterval;
        })(function () {
            setInterval((function () {
                this.setState({ deadLine: moment().format('YYYY-MM-DD HH:mm:ss') });
            }).bind(this), 1000);
        })
    }, {
        key: 'render',
        value: function render() {
            var eventTime = moment(this.deadLine, 'YYYY-MM-DD HH:mm:ss').format('x');
            var currentTime = moment().format("x");
            var diffTime = eventTime - currentTime;
            var duration = moment.duration(diffTime, 'milliseconds');
            var days = duration.days();
            var hours = duration.hours();
            if (hours.toString().length < 2) {
                hours = '0' + hours;
            }
            var minutes = duration.minutes();
            if (minutes.toString().length < 2) {
                minutes = '0' + minutes;
            }
            var seconds = duration.seconds();
            if (seconds.toString().length < 2) {
                seconds = '0' + seconds;
            }
            var timeString = days + ' дн. ' + hours + ':' + minutes + ':' + seconds;
            if (days == 0) {
                timeString = hours + ':' + minutes + ':' + seconds;
            }
            return React.createElement(
                'div',
                null,
                React.createElement(
                    'p',
                    { style: { "color": "#666" } },
                    'Предложение действительно',
                    React.createElement('br', null),
                    React.createElement(
                        'span',
                        { style: { "color": "#f14965" } },
                        timeString
                    )
                )
            );
        }
    }]);

    return CountdownTimer;
})(React.Component);