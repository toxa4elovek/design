<div class="wrapper">

    <?=$this->view()->render(array('element' => 'header'), array('logo' => 'logo'))?>

    <div class="middle">
        <div class="middle_inner">
            <div class="content group">
                <div id="content_help" style="width:620px;">
                    <section>
                        <h1  class="page-title-with-flag js-blog-index-title"><?php echo (empty($search)) ? 'Наш блог' : 'Результат поиска'; ?></h1>
                        <img id="search-ajax-loader" src="/img/blog-ajax-loader.gif">
                        <?php if (count($posts) == 0):?>
                            <div style="text-align: center;">
                                <h2 class="largest-header" style="line-height: 2em;">УПС, НИЧЕГО НЕ НАШЛИ!</h2>
                                <p class="large-regular">Попробуйте еще раз, изменив запрос.</p>
                            </div>
                        <?php endif; ?>
                        <main id="blog-posts"></main>
                        <script>
                            var posts = <?php echo json_encode($postsList) ?>;
                            var isEditor = <?= (int) $this->user->isEditor()?>;
                            var isAuthor = <?= (int) $this->user->isAuthor()?>;
                        </script>
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
<?=$this->html->script(array(
    'jquery.timeago',
    'moment.min.js',
    'moment-timezone-with-data-2010-2020.min.js',
    'posts/index/BlogPostTagLink.js',
    'posts/index/BlogPostList.js',
    'posts/index/BlogPostEntryBox.js',
    'posts/index'
), array('inline' => false))?>
<?=$this->html->style(array(
    '/help',
    '/blog',
    '/css/common/page-title-with-flag.css',
    '/css/common/clear.css',
    '/css/posts/index.css'
), array('inline' => false))?>
