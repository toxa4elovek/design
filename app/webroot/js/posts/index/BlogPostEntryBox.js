var BlogPostEntryBox = new React.createClass({
    postsViewLink: '/posts/view/',
    postsEditLink: '/posts/edit',
    postsDeleteLink: '/posts/delete/',
    getInitialState: function() {
        return {isEditor: this.props.isEditor, isAuthor: this.props.isAuthor}
    },
    componentDidMount: function() {
        var tagList = React.findDOMNode(this.refs["tag-list"]);
        var tagLinksList = $('a', tagList);
        var tagListLength = tagLinksList.length;
        $.each(tagLinksList, function(index, object) {
            if(index + 1 < tagListLength) {
                $(object).after(' &bull; ');
            }
        });
    },
    deleteLinkOnClick: function(e) {
        if (confirm("Точно удалить статью?")) {
            return true;
        } else {
            e.preventDefault();
        }
    },
    render: function() {
        var link = this.postsViewLink + this.props.post.id;
        var editLink = this.postsEditLink + this.props.post.id;
        var deleteLink = this.postsDeleteLink + this.props.post.id;
        var tagStringArray = [];
        var postDateObj = new Date(this.props.post.timezoneCreated);
        var postDate = ('0' + postDateObj.getDate()).slice(-2) + '.' + ('0' + (postDateObj.getMonth() + 1)).slice(-2) + '.' + postDateObj.getFullYear();
        var postTime = ('0' + postDateObj.getHours()).slice(-2) + ':' + ('0' + (postDateObj.getMinutes())).slice(-2);
        var actionList = [];
        if (this.props.post.tags) {
            var tagsArray = this.props.post.tags.split('|');
            for(var i = 0; i < tagsArray.length; i++) {
                tagStringArray.push(React.createElement(BlogPostTagLink, {tag: tagsArray[i]}));
            }
        }
        if((this.state.isAuthor == 1) || (this.state.isEditor == 1)) {
            actionList.push(React.createElement("a", {target: "_blank", className: "more-editor", href: editLink}, "редактировать"));
        }
        if(this.state.isEditor == 1) {
            actionList.push(React.createElement("a", {target: "_blank", onClick: this.deleteLinkOnClick, className: "more-editor delete-post", 
                               href: deleteLink}, "удалить"));
        }
        actionList.push(React.createElement("a", {className: "more", href: link}, "Подробнее"));
        var postTitleLink = React.createElement("a", {href: link}, this.props.post.title);
        if(this.props.post.published == 0) {
            postTitleLink = React.createElement("a", {href: link, className: "not-published"}, this.props.post.title);
        }
        return (
            React.createElement("div", {className: "blog-post-entry-box", key: this.props.post.id}, 
                React.createElement("div", null, 
                    React.createElement("div", {className: "blog-post-image-container"}, 
                        React.createElement("img", {className: "blog-post-image", src: this.props.post.imageurl, alt: "{this.props.post.title}"})
                    ), 
                    React.createElement("div", {className: "blog-post-description-container"}, 
                        React.createElement("h2", {className: "largest-header-blog"}, 
                            postTitleLink
                        ), 
                        React.createElement("div", {className: "blog-post-information"}, postDate, " • ", postTime, " • ", React.createElement("span", {ref: "tag-list"}, tagStringArray.map(function(object) {
                                return object;
                            })
                            )
                        ), 
                        React.createElement("div", {className: "blog-post-preview"}, 
                            React.createElement("p", {className: "regular"}, "Все средства хороши для продвижения бизнеса, но у этого фотогеничного ресурса есть ряд своих преимуществ.   ")
                        ), 
                        React.createElement("div", {className: "blog-post-links"}, 
                            actionList.map(function(link) {
                                return link;
                            })
                        )
                    ), 
                    React.createElement("div", {className: "clear"})
                ), 
                React.createElement("div", {className: "blog-post-separator"})
            )
        )
    }
});
