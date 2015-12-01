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
        if(this.props.posts.length === 0) {
            return (
                <div>
                    <div style={{"textAlign": "center"}}>
                        <h2 className="largest-header" style={{"lineHeight": "2em"}}>УПС, НИЧЕГО НЕ НАШЛИ!</h2>
                        <p className="large-regular">Попробуйте еще раз, изменив запрос.</p>
                    </div>
                </div>
            )
        }else {
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
}
