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
    }
};