export default class PushWidgetActions {
    confirmClick() {
        OneSignal.push(['registerForPushNotifications']);
    }
    denyClick() {
        writeCookie('push-widget-hide', true, 365);
    }
};