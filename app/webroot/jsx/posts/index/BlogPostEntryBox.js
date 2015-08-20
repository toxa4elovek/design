
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
        var publishedTime = moment(this.props.post.created, "YYYY-MM-DD HH:mm:ss").format('D.MM.YYYY • HH:mm');
        var actionList = [];
        if (this.props.post.tags) {
            var tagsArray = this.props.post.tags.split('|');
            for(var i = 0; i < tagsArray.length; i++) {
                tagStringArray.push(<BlogPostTagLink tag={tagsArray[i]} />);
            }
        }
        if((this.state.isAuthor == 1) || (this.state.isEditor == 1)) {
            actionList.push(<a target="_blank" className="more-editor" href={editLink}>редактировать</a>);
        }
        if(this.state.isEditor == 1) {
            actionList.push(<a target="_blank" onClick={this.deleteLinkOnClick} className="more-editor delete-post"
                               href={deleteLink}>удалить</a>);
        }
        actionList.push(<a className="more" href={link}>Подробнее</a>);
        var postTitleLink = <a href={link}>{this.props.post.title}</a>;
        if(this.props.post.published == 0) {
            postTitleLink = <a href={link} className="not-published">{this.props.post.title}</a>;
        }
        return (
            <div className="blog-post-entry-box" key={this.props.post.id}>
                <div>
                    <div className="blog-post-image-container">
                        <img className="blog-post-image" src={this.props.post.imageurl} alt={this.props.post.title} />
                    </div>
                    <div className="blog-post-description-container">
                        <h2 className="largest-header-blog">
                            {postTitleLink}
                        </h2>
                        <div className="blog-post-information">{publishedTime} • <span ref="tag-list">{tagStringArray.map(function(object) {
                                return object;
                            })}
                            </span>
                        </div>
                        <div className="blog-post-preview">
                            <p className="regular">Все средства хороши для продвижения бизнеса, но у этого фотогеничного ресурса есть ряд своих преимуществ. &nbsp;&nbsp;</p>
                        </div>
                        <div className="blog-post-links">
                            {actionList.map(function(link) {
                                return link;
                            })}
                        </div>
                    </div>
                    <div className="clear"></div>
                </div>
                <div className="blog-post-separator"></div>
            </div>
        )
    }
});
