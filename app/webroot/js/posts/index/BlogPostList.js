var BlogPostList = new React.createClass({
    displayName: 'BlogPostList',
    _removeLastSeparator: function() {
        $('.blog-post-separator').last().remove();
    },
    componentDidUpdate: function() {
        this._removeLastSeparator();
    },
    componentDidMount: function() {
        this._removeLastSeparator();
    },
    render: function() {
        return (
            React.createElement("div", null, 
                this.props.posts.map(function(post) {
                    return (
                        React.createElement(BlogPostEntryBox, {post: post, isAuthor: isAuthor, isEditor: isEditor})
                    )
                }), 
                React.createElement("div", {id: "blog-ajax-wrapper"}, 
                    React.createElement("div", {id: "blog-ajax-loader"}, " ")
                )
            )
        )
    }
});
