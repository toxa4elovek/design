import { render } from 'react-dom'
import PushWidget from './common/pushWidget/PushWidget.jsx'

// OneSignal.push(['init', {
//   appId: '46001cba-49be-4cc5-945a-bac990a6d995',
//   autoRegister: false,
//   safari_web_id: 'web.onesignal.auto.5a33fe23-ccc7-4feb-afe0-cf26b0b7b29c',
//   welcomeNotification: {
//     'disable': true
//   }
// }])
//
// if (($('#push-notifications').length === 1) && (typeof (getCookie('push-widget-hide')) == 'undefined')) {
//   OneSignal.push(function () {
//     if (!OneSignal.isPushNotificationsSupported()) {
//       return
//     }
//     OneSignal.getNotificationPermission(function (permission) {
//       if (permission === 'default') {
//         render(
//           <PushWidget service={OneSignal} />,
//           document.getElementById('push-notifications')
//         )
//       }
//     })
//   })
// }
