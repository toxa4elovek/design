const SubscribedBriefDispatcher = new Flux.Dispatcher();
const SubscribedBriefActions = {
    updateWinnerReward: function(value) {
        SubscribedBriefDispatcher.dispatch({
            actionType: 'reward-input-updated',
            newValue: value
        });
    },
    updateReceipt: function() {
        SubscribedBriefDispatcher.dispatch({
            actionType: 'update-receipt'
        });
    },
    lockButton: function() {
        SubscribedBriefDispatcher.dispatch({
            actionType: 'lock-pay-button'
        });
    },
    unlockButton: function() {
        SubscribedBriefDispatcher.dispatch({
            actionType: 'unlock-pay-button'
        });
    }
};