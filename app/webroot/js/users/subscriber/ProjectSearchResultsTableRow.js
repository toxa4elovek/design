"use strict";

var _createClass = (function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; })();

var _get = function get(_x, _x2, _x3) { var _again = true; _function: while (_again) { var object = _x, property = _x2, receiver = _x3; desc = parent = getter = undefined; _again = false; if (object === null) object = Function.prototype; var desc = Object.getOwnPropertyDescriptor(object, property); if (desc === undefined) { var parent = Object.getPrototypeOf(object); if (parent === null) { return undefined; } else { _x = parent; _x2 = property; _x3 = receiver; _again = true; continue _function; } } else if ("value" in desc) { return desc.value; } else { var getter = desc.get; if (getter === undefined) { return undefined; } return getter.call(receiver); } } };

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function, not " + typeof superClass); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, enumerable: false, writable: true, configurable: true } }); if (superClass) Object.setPrototypeOf ? Object.setPrototypeOf(subClass, superClass) : subClass.__proto__ = superClass; }

var ProjectSearchResultsTableRow = (function (_React$Component) {
    _inherits(ProjectSearchResultsTableRow, _React$Component);

    function ProjectSearchResultsTableRow() {
        _classCallCheck(this, ProjectSearchResultsTableRow);

        _get(Object.getPrototypeOf(ProjectSearchResultsTableRow.prototype), "constructor", this).apply(this, arguments);
    }

    _createClass(ProjectSearchResultsTableRow, [{
        key: "render",
        value: function render() {
            return React.createElement(
                "tr",
                { "data-id": "105783", className: "odd" },
                React.createElement(
                    "td",
                    { className: "td-title" },
                    React.createElement(
                        "a",
                        { href: "/pitches/view/105783", className: "newpitchfont" },
                        "Логотип для проекта PublicDictionary"
                    )
                ),
                React.createElement(
                    "td",
                    { className: "idea" },
                    "73"
                ),
                React.createElement(
                    "td",
                    { className: "pitches-time" },
                    "6 дней 7 часов"
                ),
                React.createElement(
                    "td",
                    { className: "price" },
                    "-20 000 Р.-"
                )
            );
        }
    }]);

    return ProjectSearchResultsTableRow;
})(React.Component);