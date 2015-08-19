var BlogPostTagLink = new React.createClass({
    postsSearchByTagLink: '/posts?tag=',
    render: function() {
        var link = this.postsSearchByTagLink + encodeURIComponent(this.props.tag);
        return (
            React.createElement("a", {className: "blogtaglink", href: link}, this.props.tag)
        );
    }
});
