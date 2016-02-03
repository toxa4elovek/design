"use strict";

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

var ReceiptAccessor = function () {
    function ReceiptAccessor() {
        _classCallCheck(this, ReceiptAccessor);
    }

    _createClass(ReceiptAccessor, [{
        key: "add",
        value: function add(array, value) {
            array = this.removeByName(array, value.name);
            array.push(value);
            return array;
        }
    }, {
        key: "removeByName",
        value: function removeByName(array, name) {
            var indexToRemove = 0;
            array.forEach(function (row) {
                if (row.name == name) {
                    indexToRemove = array.indexOf(row);
                }
            });
            if (indexToRemove > 0) {
                array.splice(indexToRemove, 1);
            }
            return array;
        }
    }, {
        key: "get",
        value: function get(array, name) {
            var value = null;
            array.forEach(function (row) {
                if (row.name == name) {
                    value = row.value;
                }
            });
            return value;
        }
    }, {
        key: "exists",
        value: function exists(array, name) {
            var result = false;
            array.forEach(function (row) {
                if (row.name == name) {
                    result = true;
                }
            });
            return result;
        }
    }], [{
        key: "add",
        value: function add(array, value) {
            array = this.removeByName(array, value.name);
            array.push(value);
            return array;
        }
    }, {
        key: "removeByName",
        value: function removeByName(array, name) {
            var indexToRemove = 0;
            array.forEach(function (row) {
                if (row.name == name) {
                    indexToRemove = array.indexOf(row);
                }
            });
            if (indexToRemove > 0) {
                array.splice(indexToRemove, 1);
            }
            return array;
        }
    }, {
        key: "get",
        value: function get(array, name) {
            var value = null;
            array.forEach(function (row) {
                if (row.name == name) {
                    value = row.value;
                }
            });
            return value;
        }
    }, {
        key: "exists",
        value: function exists(array, name) {
            var result = false;
            array.forEach(function (row) {
                if (row.name == name) {
                    result = true;
                }
            });
            return result;
        }
    }]);

    return ReceiptAccessor;
}();