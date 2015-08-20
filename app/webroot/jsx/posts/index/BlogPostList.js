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
            <div>
                {this.props.posts.map(function(post) {
                    return (
                        <BlogPostEntryBox post={post} isAuthor={isAuthor} isEditor={isEditor}/>
                    )
                })}
                <div id="blog-ajax-wrapper">
                    <div id="blog-ajax-loader">&nbsp;</div>
                </div>
            </div>
        )
    }
});
