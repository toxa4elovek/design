'use strict';

var CommentsDispatcher = new Flux.Dispatcher();
var TextareaDispatcher = new Flux.Dispatcher();
var CommentsActions = {
    selectPersonForComment: function selectPersonForComment(object) {
        TextareaDispatcher.dispatch({
            actionType: 'person-for-comment-selected',
            person: object
        });
    },
    userNeedUserAutosuggest: function userNeedUserAutosuggest(props) {
        CommentsDispatcher.dispatch({
            actionType: 'start-autosuggest',
            props: props
        });
    },
    userStoppedAutosuggest: function userStoppedAutosuggest(selector) {
        CommentsDispatcher.dispatch({
            actionType: 'stop-autosuggest',
            query: '',
            props: selector
        });
    }
};