const CommentsDispatcher = new Flux.Dispatcher();
const TextareaDispatcher = new Flux.Dispatcher();
const CommentsActions = {
    selectPersonForComment: function(object) {
        TextareaDispatcher.dispatch({
            actionType: 'person-for-comment-selected',
            person: object
        });
    },
    userNeedUserAutosuggest: function(props) {
        CommentsDispatcher.dispatch({
            actionType: 'start-autosuggest',
            props: props
        });
    },
    userStoppedAutosuggest: function(selector) {
        CommentsDispatcher.dispatch({
            actionType: 'stop-autosuggest',
            query: '',
            props: selector
        });
    }
};