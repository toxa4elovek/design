'use strict';

var _createClass = (function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ('value' in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; })();

var _get = function get(_x, _x2, _x3) { var _again = true; _function: while (_again) { var object = _x, property = _x2, receiver = _x3; desc = parent = getter = undefined; _again = false; if (object === null) object = Function.prototype; var desc = Object.getOwnPropertyDescriptor(object, property); if (desc === undefined) { var parent = Object.getPrototypeOf(object); if (parent === null) { return undefined; } else { _x = parent; _x2 = property; _x3 = receiver; _again = true; continue _function; } } else if ('value' in desc) { return desc.value; } else { var getter = desc.get; if (getter === undefined) { return undefined; } return getter.call(receiver); } } };

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError('Cannot call a class as a function'); } }

function _inherits(subClass, superClass) { if (typeof superClass !== 'function' && superClass !== null) { throw new TypeError('Super expression must either be null or a function, not ' + typeof superClass); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, enumerable: false, writable: true, configurable: true } }); if (superClass) Object.setPrototypeOf ? Object.setPrototypeOf(subClass, superClass) : subClass.__proto__ = superClass; }

var BlogPostList = (function (_React$Component) {
    _inherits(BlogPostList, _React$Component);

    function BlogPostList() {
        _classCallCheck(this, BlogPostList);

        _get(Object.getPrototypeOf(BlogPostList.prototype), 'constructor', this).apply(this, arguments);

        this.displayName = 'BlogPostList';
    }

    _createClass(BlogPostList, [{
        key: '__removeLastSeparator',
        value: function __removeLastSeparator() {
            $('.blog-post-separator').last().remove();
        }
    }, {
        key: 'componentDidUpdate',
        value: function componentDidUpdate() {
            this.__removeLastSeparator();
        }
    }, {
        key: 'componentDidMount',
        value: function componentDidMount() {
            this.__removeLastSeparator();
        }
    }, {
        key: 'render',
        value: function render() {
            if (this.props.posts.length === 0) {
                return React.createElement(
                    'div',
                    null,
                    React.createElement(
                        'div',
                        { style: { "textAlign": "center" } },
                        React.createElement(
                            'h2',
                            { className: 'largest-header', style: { "lineHeight": "2em" } },
                            'УПС, НИЧЕГО НЕ НАШЛИ!'
                        ),
                        React.createElement(
                            'p',
                            { className: 'large-regular' },
                            'Попробуйте еще раз, изменив запрос.'
                        )
                    )
                );
            } else {
                return React.createElement(
                    'div',
                    null,
                    this.props.posts.map(function (post) {
                        return React.createElement(BlogPostEntryBox, { key: post.id, post: post, isAuthor: isAuthor, isEditor: isEditor });
                    }),
                    React.createElement(
                        'div',
                        { id: 'blog-ajax-wrapper' },
                        React.createElement(
                            'div',
                            { id: 'blog-ajax-loader' },
                            ' '
                        )
                    )
                );
            }
        }
    }]);

    return BlogPostList;
})(React.Component);