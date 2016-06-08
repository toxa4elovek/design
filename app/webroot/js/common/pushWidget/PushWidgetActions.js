'use strict';

Object.defineProperty(exports, "__esModule", {
  value: true
});

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

var PushWidgetActions = function () {
  function PushWidgetActions() {
    _classCallCheck(this, PushWidgetActions);
  }

  _createClass(PushWidgetActions, [{
    key: 'confirmClick',
    value: function confirmClick() {
      OneSignal.push(['registerForPushNotifications']);
    }
  }, {
    key: 'denyClick',
    value: function denyClick() {
      writeCookie('push-widget-hide', true, 365);
    }
  }]);

  return PushWidgetActions;
}();

exports.default = PushWidgetActions;