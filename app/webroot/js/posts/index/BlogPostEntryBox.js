'use strict';

var _createClass = (function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ('value' in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; })();

var _get = function get(_x, _x2, _x3) { var _again = true; _function: while (_again) { var object = _x, property = _x2, receiver = _x3; desc = parent = getter = undefined; _again = false; if (object === null) object = Function.prototype; var desc = Object.getOwnPropertyDescriptor(object, property); if (desc === undefined) { var parent = Object.getPrototypeOf(object); if (parent === null) { return undefined; } else { _x = parent; _x2 = property; _x3 = receiver; _again = true; continue _function; } } else if ('value' in desc) { return desc.value; } else { var getter = desc.get; if (getter === undefined) { return undefined; } return getter.call(receiver); } } };

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError('Cannot call a class as a function'); } }

function _inherits(subClass, superClass) { if (typeof superClass !== 'function' && superClass !== null) { throw new TypeError('Super expression must either be null or a function, not ' + typeof superClass); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, enumerable: false, writable: true, configurable: true } }); if (superClass) Object.setPrototypeOf ? Object.setPrototypeOf(subClass, superClass) : subClass.__proto__ = superClass; }

var BlogPostEntryBox = (function (_React$Component) {
    _inherits(BlogPostEntryBox, _React$Component);

    function BlogPostEntryBox(props) {
        _classCallCheck(this, BlogPostEntryBox);

        _get(Object.getPrototypeOf(BlogPostEntryBox.prototype), 'constructor', this).call(this, props);
        this.displayName = 'BlogPostEntryBox';
        this.postsViewLink = '/posts/view/';
        this.postsEditLink = '/posts/edit/';
        this.postsDeleteLink = '/posts/delete/';
        this.state = { isEditor: props.isEditor, isAuthor: props.isAuthor };
    }

    _createClass(BlogPostEntryBox, [{
        key: 'componentDidMount',
        value: function componentDidMount() {
            var tagList = this.refs["tag-list"];
            var tagLinksList = $('a', tagList);
            var tagListLength = tagLinksList.length;
            $.each(tagLinksList, function (index, object) {
                if (index + 1 < tagListLength) {
                    $(object).after(' &bull; ');
                }
            });
        }
    }, {
        key: 'deleteLinkOnClick',
        value: function deleteLinkOnClick(e) {
            if (confirm("Точно удалить статью?")) {
                return true;
            } else {
                e.preventDefault();
            }
        }
    }, {
        key: 'render',
        value: function render() {
            var link = this.postsViewLink + this.props.post.id;
            var editLink = this.postsEditLink + this.props.post.id;
            var deleteLink = this.postsDeleteLink + this.props.post.id;
            var tagStringArray = [];
            var publishedTime = moment(this.props.post.created, "YYYY-MM-DD HH:mm:ss").format('D.MM.YYYY • HH:mm');
            var actionList = [];
            var shortStory = $(this.props.post.short).text();
            if (this.props.post.tags) {
                var tagsArray = this.props.post.tags.split('|');
                for (var i = 0; i < tagsArray.length; i++) {
                    tagStringArray.push(React.createElement(BlogPostTagLink, { key: tagsArray[i], tag: tagsArray[i] }));
                }
            }
            if (this.state.isAuthor == 1 || this.state.isEditor == 1) {
                actionList.push(React.createElement(
                    'a',
                    { target: '_blank', className: 'more-editor', href: editLink },
                    'редактировать'
                ));
            }
            if (this.state.isEditor == 1) {
                actionList.push(React.createElement(
                    'a',
                    { target: '_blank', onClick: this.deleteLinkOnClick, className: 'more-editor delete-post',
                        href: deleteLink },
                    'удалить'
                ));
            }
            actionList.push(React.createElement(
                'a',
                { className: 'more', href: link },
                'Подробнее'
            ));
            var postTitleLink = React.createElement(
                'a',
                { href: link },
                this.props.post.title
            );
            if (this.props.post.published == 0) {
                postTitleLink = React.createElement(
                    'a',
                    { href: link, className: 'not-published' },
                    this.props.post.title
                );
            }
            var currentDateTime = moment();
            if (!currentDateTime.isAfter(moment(this.props.post.created, "YYYY-MM-DD HH:mm:ss"))) {
                postTitleLink = React.createElement(
                    'a',
                    { href: link, className: 'not-published' },
                    this.props.post.title
                );
            }
            return React.createElement(
                'div',
                { className: 'blog-post-entry-box', key: this.props.post.id },
                React.createElement(
                    'div',
                    null,
                    React.createElement(
                        'div',
                        { className: 'blog-post-image-container' },
                        React.createElement('img', { className: 'blog-post-image', src: this.props.post.imageurl, alt: this.props.post.title })
                    ),
                    React.createElement(
                        'div',
                        { className: 'blog-post-description-container' },
                        React.createElement(
                            'h2',
                            { className: 'largest-header-blog' },
                            postTitleLink
                        ),
                        React.createElement(
                            'div',
                            { className: 'blog-post-information' },
                            publishedTime,
                            ' • ',
                            React.createElement(
                                'span',
                                { ref: 'tag-list' },
                                tagStringArray.map(function (object) {
                                    return object;
                                })
                            )
                        ),
                        React.createElement(
                            'div',
                            { className: 'blog-post-preview' },
                            React.createElement(
                                'p',
                                { className: 'regular' },
                                shortStory
                            )
                        ),
                        React.createElement(
                            'div',
                            { className: 'blog-post-links' },
                            actionList.map(function (link) {
                                return link;
                            })
                        )
                    ),
                    React.createElement('div', { className: 'clear' })
                ),
                React.createElement('div', { className: 'blog-post-separator' })
            );
        }
    }]);

    return BlogPostEntryBox;
})(React.Component);