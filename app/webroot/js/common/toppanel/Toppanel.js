"use strict";

var _createClass = (function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; })();

var _get = function get(_x, _x2, _x3) { var _again = true; _function: while (_again) { var object = _x, property = _x2, receiver = _x3; desc = parent = getter = undefined; _again = false; if (object === null) object = Function.prototype; var desc = Object.getOwnPropertyDescriptor(object, property); if (desc === undefined) { var parent = Object.getPrototypeOf(object); if (parent === null) { return undefined; } else { _x = parent; _x2 = property; _x3 = receiver; _again = true; continue _function; } } else if ("value" in desc) { return desc.value; } else { var getter = desc.get; if (getter === undefined) { return undefined; } return getter.call(receiver); } } };

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function, not " + typeof superClass); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, enumerable: false, writable: true, configurable: true } }); if (superClass) Object.setPrototypeOf ? Object.setPrototypeOf(subClass, superClass) : subClass.__proto__ = superClass; }

var Toppanel = (function (_BaseComponent) {
    _inherits(Toppanel, _BaseComponent);

    function Toppanel() {
        _classCallCheck(this, Toppanel);

        _get(Object.getPrototypeOf(Toppanel.prototype), "constructor", this).apply(this, arguments);
    }

    _createClass(Toppanel, [{
        key: "render",
        value: function render() {
            return React.createElement(
                "div",
                { id: "pitch-panel" },
                React.createElement(
                    "div",
                    { className: "conteiner" },
                    React.createElement(
                        "div",
                        { className: "content" },
                        React.createElement(
                            "table",
                            { className: "all-pitches", id: "header-table" },
                            React.createElement(
                                "tbody",
                                null,
                                React.createElement(
                                    "tr",
                                    { "data-id": "104569", className: "selection even coda" },
                                    React.createElement(
                                        "td",
                                        null,
                                        React.createElement("img", { data: "1", className: "pitches-image", src: "/solutions/b/b9/b9d/b9d44418c6ea89a5fd934f322c2b5951_tutdesign.png" })
                                    ),
                                    React.createElement(
                                        "td",
                                        { className: "pitches-name mypitches" },
                                        React.createElement(
                                            "a",
                                            { href: "http://godesigner.ru/pitches/view/104569" },
                                            "Логотип для швейного производства «Российская Фабрика Текстиля»"
                                        )
                                    ),
                                    React.createElement(
                                        "td",
                                        { colSpan: "2", className: "pitches-status mypitches" },
                                        React.createElement(
                                            "a",
                                            { className: "pitches-finish", href: "http://godesigner.ru/users/step4/154023" },
                                            "Перейти",
                                            React.createElement("br", null),
                                            "на завершающий этап"
                                        )
                                    ),
                                    React.createElement(
                                        "td",
                                        { className: "price mypitches" },
                                        "10 000.-                                "
                                    )
                                ),
                                React.createElement(
                                    "tr",
                                    { "data-id": "104885", className: "selection odd coda" },
                                    React.createElement("td", null),
                                    React.createElement(
                                        "td",
                                        { className: "pitches-name mypitches" },
                                        React.createElement(
                                            "a",
                                            { href: "http://godesigner.ru/pitches/view/104885" },
                                            "Еуые"
                                        )
                                    ),
                                    React.createElement(
                                        "td",
                                        { className: "pitches-status mypitches" },
                                        React.createElement(
                                            "a",
                                            { href: "http://godesigner.ru/pitches/details/104885" },
                                            "Текущий проект"
                                        )
                                    ),
                                    React.createElement(
                                        "td",
                                        { className: "price mypitches" },
                                        "29 500.-                                "
                                    ),
                                    React.createElement(
                                        "td",
                                        { className: "pitches-edit mypitches" },
                                        React.createElement(
                                            "a",
                                            { href: "http://godesigner.ru/pitches/edit/104885", className: "edit mypitch_edit_link", title: "редактировать" },
                                            "редактировать"
                                        )
                                    )
                                ),
                                React.createElement(
                                    "tr",
                                    { "data-id": "104897", className: "selection even coda" },
                                    React.createElement("td", null),
                                    React.createElement(
                                        "td",
                                        { className: "pitches-name mypitches" },
                                        React.createElement(
                                            "a",
                                            { href: "http://godesigner.ru/pitches/view/104897" },
                                            "131"
                                        )
                                    ),
                                    React.createElement(
                                        "td",
                                        { className: "pitches-status mypitches" },
                                        React.createElement(
                                            "a",
                                            { href: "http://godesigner.ru/pitches/edit/104897" },
                                            "Ожидание оплаты"
                                        )
                                    ),
                                    React.createElement(
                                        "td",
                                        { className: "price mypitches" },
                                        "24 700.-                                "
                                    ),
                                    React.createElement(
                                        "td",
                                        { className: "pitches-edit mypitches" },
                                        React.createElement(
                                            "a",
                                            { href: "http://godesigner.ru/pitches/edit/104897#step3", className: "mypitch_pay_link buy", title: "оплатить" },
                                            "оплатить"
                                        ),
                                        React.createElement(
                                            "a",
                                            { href: "http://godesigner.ru/pitches/edit/104897", className: "edit mypitch_edit_link", title: "редактировать" },
                                            "редактировать"
                                        ),
                                        React.createElement(
                                            "a",
                                            { "data-id": "104897", href: "http://godesigner.ru/pitches/delete/104897", className: "delete deleteheader mypitch_delete_link", title: "удалить" },
                                            "удалить"
                                        )
                                    )
                                ),
                                React.createElement(
                                    "tr",
                                    { "data-id": "104898", className: "selection odd coda" },
                                    React.createElement("td", null),
                                    React.createElement(
                                        "td",
                                        { className: "pitches-name mypitches" },
                                        React.createElement(
                                            "a",
                                            { href: "http://godesigner.ru/pitches/view/104898" },
                                            "Test"
                                        )
                                    ),
                                    React.createElement(
                                        "td",
                                        { className: "pitches-status mypitches" },
                                        React.createElement(
                                            "a",
                                            { href: "http://godesigner.ru/pitches/edit/104898" },
                                            "Ожидание оплаты"
                                        )
                                    ),
                                    React.createElement(
                                        "td",
                                        { className: "price mypitches" },
                                        "24 700.-                                "
                                    ),
                                    React.createElement(
                                        "td",
                                        { className: "pitches-edit mypitches" },
                                        React.createElement(
                                            "a",
                                            { href: "http://godesigner.ru/pitches/edit/104898#step3", className: "mypitch_pay_link buy", title: "оплатить" },
                                            "оплатить"
                                        ),
                                        React.createElement(
                                            "a",
                                            { href: "http://godesigner.ru/pitches/edit/104898", className: "edit mypitch_edit_link", title: "редактировать" },
                                            "редактировать"
                                        ),
                                        React.createElement(
                                            "a",
                                            { "data-id": "104898", href: "http://godesigner.ru/pitches/delete/104898", className: "delete deleteheader mypitch_delete_link", title: "удалить" },
                                            "удалить"
                                        )
                                    )
                                ),
                                React.createElement(
                                    "tr",
                                    { "data-id": "104899", className: "selection even coda" },
                                    React.createElement("td", null),
                                    React.createElement(
                                        "td",
                                        { className: "pitches-name mypitches" },
                                        React.createElement(
                                            "a",
                                            { href: "http://godesigner.ru/pitches/view/104899" },
                                            "Test"
                                        )
                                    ),
                                    React.createElement(
                                        "td",
                                        { className: "pitches-status mypitches" },
                                        React.createElement(
                                            "a",
                                            { href: "http://godesigner.ru/pitches/edit/104899" },
                                            "Ожидание оплаты"
                                        )
                                    ),
                                    React.createElement(
                                        "td",
                                        { className: "price mypitches" },
                                        "24 700.-                                "
                                    ),
                                    React.createElement(
                                        "td",
                                        { className: "pitches-edit mypitches" },
                                        React.createElement(
                                            "a",
                                            { href: "http://godesigner.ru/pitches/edit/104899#step3", className: "mypitch_pay_link buy", title: "оплатить" },
                                            "оплатить"
                                        ),
                                        React.createElement(
                                            "a",
                                            { href: "http://godesigner.ru/pitches/edit/104899", className: "edit mypitch_edit_link", title: "редактировать" },
                                            "редактировать"
                                        ),
                                        React.createElement(
                                            "a",
                                            { "data-id": "104899", href: "http://godesigner.ru/pitches/delete/104899", className: "delete deleteheader mypitch_delete_link", title: "удалить" },
                                            "удалить"
                                        )
                                    )
                                )
                            )
                        ),
                        React.createElement(
                            "p",
                            { className: "pitch-buttons-legend" },
                            React.createElement(
                                "a",
                                { href: "http://godesigner.ru/answers/view/6" },
                                React.createElement("i", { id: "help" }),
                                "Какие способы оплаты вы принимаете?"
                            ),
                            "            "
                        )
                    )
                )
            );
        }
    }]);

    return Toppanel;
})(BaseComponent);