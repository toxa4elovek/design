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
    }
};