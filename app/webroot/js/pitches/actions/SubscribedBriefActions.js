'use strict';

var SubscribedBriefDispatcher = new Flux.Dispatcher();
var SubscribedBriefActions = {
    updateWinnerReward: function updateWinnerReward(value) {
        SubscribedBriefDispatcher.dispatch({
            actionType: 'reward-input-updated',
            newValue: value
        });
    },
    updateReceipt: function updateReceipt() {
        SubscribedBriefDispatcher.dispatch({
            actionType: 'update-receipt'
        });
    },
    lockButton: function lockButton() {
        SubscribedBriefDispatcher.dispatch({
            actionType: 'lock-pay-button'
        });
    },
    unlockButton: function unlockButton() {
        SubscribedBriefDispatcher.dispatch({
            actionType: 'unlock-pay-button'
        });
    }
};