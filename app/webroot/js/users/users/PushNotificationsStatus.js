"use strict";

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

Object.defineProperty(exports, "__esModule", {
    value: true
});

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _possibleConstructorReturn(self, call) { if (!self) { throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); } return call && (typeof call === "object" || typeof call === "function") ? call : self; }

function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function, not " + typeof superClass); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, enumerable: false, writable: true, configurable: true } }); if (superClass) Object.setPrototypeOf ? Object.setPrototypeOf(subClass, superClass) : subClass.__proto__ = superClass; }

var PushNotificationsStatus = exports.PushNotificationsStatus = function (_React$Component) {
    _inherits(PushNotificationsStatus, _React$Component);

    function PushNotificationsStatus() {
        _classCallCheck(this, PushNotificationsStatus);

        return _possibleConstructorReturn(this, Object.getPrototypeOf(PushNotificationsStatus).apply(this, arguments));
    }

    _createClass(PushNotificationsStatus, [{
        key: "render",
        value: function render() {
            /*let items = [];
            $.each(this.props, function(i, value) {
                items.push(value);
            });*/
            console.log(this.props);
            return React.createElement(
                "label",
                { className: "regular", style: { "fontWeight": "normal" } },
                React.createElement("input", { style: { "marginTop": 0, "marginBottom": '2px' }, type: "checkbox", name: "email_newsolonce" }),
                "о новых решениях к моим проектам раз в день"
            );
        }
    }]);

    return PushNotificationsStatus;
}(React.Component);