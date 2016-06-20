'use strict';

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _possibleConstructorReturn(self, call) { if (!self) { throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); } return call && (typeof call === "object" || typeof call === "function") ? call : self; }

function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function, not " + typeof superClass); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, enumerable: false, writable: true, configurable: true } }); if (superClass) Object.setPrototypeOf ? Object.setPrototypeOf(subClass, superClass) : subClass.__proto__ = superClass; }

var CountdownTimer = function (_React$Component) {
    _inherits(CountdownTimer, _React$Component);

    function CountdownTimer(props) {
        _classCallCheck(this, CountdownTimer);

        var _this = _possibleConstructorReturn(this, Object.getPrototypeOf(CountdownTimer).call(this));

        _this.deadLine = props.data;
        return _this;
    }

    _createClass(CountdownTimer, [{
        key: 'componentDidMount',
        value: function componentDidMount() {
            this.setInterval(this.tick, 1000);
        }
    }, {
        key: 'setInterval',
        value: function (_setInterval) {
            function setInterval() {
                return _setInterval.apply(this, arguments);
            }

            setInterval.toString = function () {
                return _setInterval.toString();
            };

            return setInterval;
        }(function () {
            setInterval(function () {
                this.setState({ deadLine: moment().format('YYYY-MM-DD HH:mm:ss') });
            }.bind(this), 1000);
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
}(React.Component);