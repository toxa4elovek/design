class BlogPostList extends React.Component{
    displayName = 'BlogPostList';
    __removeLastSeparator() {
        $('.blog-post-separator').last().remove();
    }
    componentDidUpdate() {
        this.__removeLastSeparator();
    }
    componentDidMount() {
        this.__removeLastSeparator();
    }
    render() {
        return (
            <div>
                {this.props.posts.map(function(post) {
                    return (
                        <BlogPostEntryBox key={post.id} post={post} isAuthor={isAuthor} isEditor={isEditor}/>
                    )
                })}
                <div id="blog-ajax-wrapper">
                    <div id="blog-ajax-loader">&nbsp;</div>
                </div>
            </div>
        )
    }
}
