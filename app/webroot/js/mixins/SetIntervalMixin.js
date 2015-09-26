"use strict";

var SetIntervalMixin = {
    componentWillMount: function componentWillMount() {
        this.intervals = [];
    },
    setInterval: (function (_setInterval) {
        function setInterval(_x, _x2) {
            return _setInterval.apply(this, arguments);
        }

        setInterval.toString = function () {
            return _setInterval.toString();
        };

        return setInterval;
    })(function (fn, ms) {
        this.intervals.push(setInterval(fn, ms));
    }),
    componentWillUnmount: function componentWillUnmount() {
        this.intervals.forEach(clearInterval);
    }
};