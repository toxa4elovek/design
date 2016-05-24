'use strict';

var _reactDom = require('react-dom');

var _PushWidget = require('./common/pushWidget/PushWidget.jsx');

var _PushWidget2 = _interopRequireDefault(_PushWidget);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

OneSignal.push(["init", {
    appId: "46001cba-49be-4cc5-945a-bac990a6d995",
    autoRegister: false,
    safari_web_id: 'web.onesignal.auto.5a33fe23-ccc7-4feb-afe0-cf26b0b7b29c',
    welcomeNotification: {
        "disable": true
    }
}]);

if ($('#push-notifications').length == 1 && typeof getCookie('push-widget-hide') == 'undefined') {
    OneSignal.push(function () {
        if (!OneSignal.isPushNotificationsSupported()) {
            return;
        }
        OneSignal.getNotificationPermission(function (permission) {
            if (permission === 'default') {
                (0, _reactDom.render)(React.createElement(_PushWidget2.default, { service: OneSignal }), document.getElementById('push-notifications'));
            }
        });
    });
}