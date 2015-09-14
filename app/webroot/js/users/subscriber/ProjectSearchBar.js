'use strict';

var _createClass = (function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ('value' in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; })();

var _get = function get(_x, _x2, _x3) { var _again = true; _function: while (_again) { var object = _x, property = _x2, receiver = _x3; desc = parent = getter = undefined; _again = false; if (object === null) object = Function.prototype; var desc = Object.getOwnPropertyDescriptor(object, property); if (desc === undefined) { var parent = Object.getPrototypeOf(object); if (parent === null) { return undefined; } else { _x = parent; _x2 = property; _x3 = receiver; _again = true; continue _function; } } else if ('value' in desc) { return desc.value; } else { var getter = desc.get; if (getter === undefined) { return undefined; } return getter.call(receiver); } } };

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError('Cannot call a class as a function'); } }

function _inherits(subClass, superClass) { if (typeof superClass !== 'function' && superClass !== null) { throw new TypeError('Super expression must either be null or a function, not ' + typeof superClass); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, enumerable: false, writable: true, configurable: true } }); if (superClass) Object.setPrototypeOf ? Object.setPrototypeOf(subClass, superClass) : subClass.__proto__ = superClass; }

var ProjectSearchBar = (function (_React$Component) {
    _inherits(ProjectSearchBar, _React$Component);

    function ProjectSearchBar() {
        _classCallCheck(this, ProjectSearchBar);

        _get(Object.getPrototypeOf(ProjectSearchBar.prototype), 'constructor', this).call(this);
        this.placeholder = "найдите свой  проект по ключевому слову или типу";
        this.arrowLinkClick = this.arrowLinkClick.bind(this);
        this.searchButtonClick = this.searchButtonClick.bind(this);
        this.handleFocus = this.handleFocus.bind(this);
        this.handleBlur = this.handleBlur.bind(this);
    }

    _createClass(ProjectSearchBar, [{
        key: 'arrowLinkClick',
        value: function arrowLinkClick(event) {
            var arrow = $(this.refs.arrowIndicator);
            var dir = arrow.data('dir');
            var imageUrl = '/img/filter-arrow-up.png';
            if ('up' == dir) {
                arrow.data('dir', 'down');
            } else {
                imageUrl = '/img/filter-arrow-down.png';
                arrow.data('dir', 'up');
            }
            arrow.attr('src', imageUrl);
            event.preventDefault();
        }
    }, {
        key: 'searchButtonClick',
        value: function searchButtonClick(event) {
            event.preventDefault();
        }
    }, {
        key: 'handleFocus',
        value: function handleFocus() {
            var container = $(this.refs.filterContainer);
            var textField = $(this.refs.searchInput);
            container.css({ "border": '4px solid #E7E7E7', "box-shadow": 'none' });
            textField.css('box-shadow', 'none');
        }
    }, {
        key: 'handleBlur',
        value: function handleBlur() {
            var container = $(this.refs.filterContainer);
            var textField = $(this.refs.searchInput);
            container.css({ "border": '4px solid #F3F3F3', "box-shadow": '0 1px 2px rgba(0, 0, 0, 0.2) inset' });
            textField.css('box-shadow', '1px 1px 2px rgba(0, 0, 0, 0.2) inset');
        }
    }, {
        key: 'render',
        value: function render() {
            return React.createElement(
                'table',
                null,
                React.createElement(
                    'tbody',
                    null,
                    React.createElement(
                        'tr',
                        null,
                        React.createElement(
                            'td',
                            null,
                            React.createElement(
                                'div',
                                { ref: 'filterContainer', className: 'search-box-container' },
                                React.createElement('ul', { className: 'tags', id: 'filterbox' }),
                                React.createElement('input', { ref: 'searchInput', type: 'text', placeholder: this.placeholder, onFocus: this.handleFocus, onBlur: this.handleBlur, onChange: this.handleChange, className: 'placeholder' }),
                                React.createElement(
                                    'a',
                                    { href: '#', onClick: this.arrowLinkClick, className: 'arrow-container' },
                                    React.createElement('img', { ref: 'arrowIndicator', className: 'arrow-down', src: '/img/filter-arrow-down.png', 'data-dir': 'up', alt: 'Раскрыть меню' })
                                ),
                                React.createElement('a', { href: '#', id: 'filterClear' })
                            )
                        ),
                        React.createElement(
                            'td',
                            null,
                            React.createElement(
                                'a',
                                { href: '#', onClick: this.searchButtonClick, className: 'button clean-style-button start-search' },
                                'Поиск'
                            )
                        )
                    )
                )
            );
        }
    }]);

    return ProjectSearchBar;
})(React.Component);