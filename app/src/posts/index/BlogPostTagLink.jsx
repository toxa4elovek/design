class BlogPostTagLink extends React.Component{
    postsSearchByTagLink = '/posts?tag=';
    render() {
        const link = this.postsSearchByTagLink + encodeURIComponent(this.props.tag);
        return (
            <a className="blogtaglink" href={link}>{this.props.tag}</a>
        );
    }
}