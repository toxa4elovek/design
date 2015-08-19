<div class="wrapper">

    <?=$this->view()->render(array('element' => 'header'), array('logo' => 'logo'))?>

    <div class="middle">
        <div class="middle_inner">
            <div class="content group">
                <div id="content_help" style="width:620px;">
                    <section class="howitworks">
                        <h1 style="margin-bottom: 30px;" class="js-blog-index-title"><?php echo (empty($search)) ? 'Наш блог' : 'Результат поиска'; ?></h1>
                        <img id="search-ajax-loader" src="/img/blog-ajax-loader.gif">
                        <?php if (count($posts) == 0):?>
                            <div style="text-align: center;">
                                <h2 class="largest-header" style="line-height: 2em;">УПС, НИЧЕГО НЕ НАШЛИ!</h2>
                                <p class="large-regular">Попробуйте еще раз, изменив запрос.</p>
                            </div>
                        <?php endif; ?>
                        <main id="blog-posts">

                        </main>
                        <script type="text/jsx">
                            var posts = <?php echo json_encode($postsList) ?>;
                            var isEditor = <?= (int) $this->user->isEditor()?>;
                            var isAuthor = <?= (int) $this->user->isAuthor()?>;
                            var BlogPostTagLink = new React.createClass({
                                postsSearchByTagLink: '/posts?tag=',
                                render: function() {
                                    var link = this.postsSearchByTagLink + encodeURIComponent(this.props.tag);
                                    return (
                                        <a className="blogtaglink" href={link}>{this.props.tag}</a>
                                    );
                                }
                            });

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
                                                    <img className="blog-post-image" src={this.props.post.imageurl} alt="{this.props.post.title}" />
                                                </div>
                                                <div className="blog-post-description-container">
                                                    <h2 className="largest-header-blog">
                                                        {postTitleLink}
                                                    </h2>
                                                    <div className="blog-post-information">{postDate} • {postTime} • <span ref="tag-list">{tagStringArray.map(function(object) {
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
                            var BlogPostList = new React.createClass({
                                render: function() {
                                    return (
                                        <div>
                                            {this.props.posts.map(function(post) {
                                                return (
                                                    <BlogPostEntryBox post={post} isAuthor={isAuthor} isEditor={isEditor}/>
                                                )
                                            })}
                                        </div>
                                    )
                                }
                            });

                            React.render(
                                <BlogPostList posts={posts}/>,
                                document.getElementById('blog-posts')
                            );

                        </script>
                        <?php
                        $currentIndex = 1;
                        $count = count($posts);
                        foreach($posts as $post):?>

                        <?php endforeach?>
                    </section>
                </div>
                <div id="right_sidebar_help" style="width:200px;">
                    <form id="post-search">
                        <input type="text" id="blog-search" name="search" value="<?=$search?>" class="text">
                        <input type="submit" class="blog-submit" value="">
                    </form>
                    <?=$this->view()->render(array('element' => 'posts/categories_menu'))?>
                    <div id="current_pitch">
                    <?php echo $this->stream->renderStream(10, false);?>
                    </div>
                </div>
            </div><!-- /content -->
            <div id="blog-ajax-wrapper" style="display: none;">
                <div id="blog-ajax-loader">&nbsp;</div>
            </div>
            <div class="onTopMiddle">&nbsp;</div>
        </div><!-- /middle_inner -->
        <div id="under_middle_inner"></div><!-- /under_middle_inner -->
    </div><!-- /middle -->

</div><!-- .wrapper -->
<div class="onTop">&nbsp;</div>
<?=$this->html->script(array('jquery.timeago', 'posts/index'), array('inline' => false))?>
<?=$this->html->style(array('/help', '/blog', '/css/common/clear.css','/css/posts/index.css'), array('inline' => false))?>
