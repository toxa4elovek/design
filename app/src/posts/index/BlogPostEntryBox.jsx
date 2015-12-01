class BlogPostEntryBox extends React.Component{
    displayName = 'BlogPostEntryBox';
    postsViewLink = '/posts/view/';
    postsEditLink = '/posts/edit/';
    postsDeleteLink = '/posts/delete/';
    constructor(props) {
        super(props);
        this.state = {isEditor: props.isEditor, isAuthor: props.isAuthor};
    }

    /**
     * Метод выывается, когда компонент
     */
    componentDidMount() {
        var tagList = this.refs["tag-list"];
        var tagLinksList = $('a', tagList);
        var tagListLength = tagLinksList.length;
        $.each(tagLinksList, function(index, object) {
            if(index + 1 < tagListLength) {
                $(object).after(' &bull; ');
            }
        });
    }
    deleteLinkOnClick(e) {
        if (confirm("Точно удалить статью?")) {
            return true;
        } else {
            e.preventDefault();
        }
    }
    render() {
        const link = this.postsViewLink + this.props.post.id;
        const editLink = this.postsEditLink + this.props.post.id;
        const deleteLink = this.postsDeleteLink + this.props.post.id;
        let tagStringArray = [];
        const publishedTime = moment(this.props.post.created, "YYYY-MM-DD HH:mm:ss").format('D.MM.YYYY • HH:mm');
        let actionList = [];
        const shortStory = $(this.props.post.short).text();
        if (this.props.post.tags) {
            let tagsArray = this.props.post.tags.split('|');
            for(let i = 0; i < tagsArray.length; i++) {
                tagStringArray.push(<BlogPostTagLink key={tagsArray[i]} tag={tagsArray[i]} />);
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
        let postTitleLink = <a href={link}>{this.props.post.title}</a>;
        if(this.props.post.published == 0) {
            postTitleLink = <a href={link} className="not-published">{this.props.post.title}</a>;
        }
        const currentMoscowTime = moment().tz('Europe/Kaliningrad');
        if(!currentMoscowTime.isAfter(moment(this.props.post.timezonedCreated))) {
            postTitleLink = <a href={link} className="not-published">{this.props.post.title}</a>;
        }
        return (
            <div key={this.props.post.id} className="blog-post-entry-box">
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
                            <p className="regular">{shortStory}</p>
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
}
