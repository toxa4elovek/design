"use strict";

var ManagersControlPanelDispatcher = new Flux.Dispatcher();
var ManagersControlPanelActions = {
  sendAction: function sendAction(action, value) {
    console.log("action: " + action);
    console.log("value: " + value);
    ManagersControlPanelDispatcher.dispatch({
      action: action,
      value: value
    });
  }
};