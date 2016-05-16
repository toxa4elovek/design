'use strict';

$(document).ready(function () {
    $('.time').timeago();
    var currentTag = getParameterByName('tag');
    var authorId = getParameterByName('author');
    var url = '/posts.json?tag=';
    if (currentTag) {
        url += currentTag;
    }
    var currentPage = 1;
    var searchQuery = getParameterByName('search');
    if (searchQuery) {
        url = '/posts/search.json?search=' + searchQuery;
    }
    if (authorId) {
        url += '&author=' + authorId;
    }

    /*
     * Initialize endless scroll
     */
    function scrollInit() {
        $(window).on('scroll', function () {
            if ($(document).height() - $(window).scrollTop() - $(window).height() < 200) {
                $(window).off('scroll');
                Tip.scrollHandler();
                $('#blog-ajax-wrapper').show();
                ++currentPage;
                $.getJSON(url + '&page=' + currentPage, function (result) {
                    $('#blog-ajax-wrapper').hide();
                    $('.blog-post-entry-box').last().append('<div class="blog-post-separator"></div>');
                    ReactDOM.render(React.createElement(BlogPostList, { posts: posts }), document.getElementById('blog-posts'));
                    posts = posts.concat(result.postsList);
                    $(window).off('scroll');
                    Tip.visibility();
                    scrollInit();
                });
            }
        });
    }

    var Tip = new TopTip();
    Tip.init();
    scrollInit();

    $(window).on('resize', function () {
        Tip.resize();
    });

    // Search
    $(document).on('submit', '#post-search', function () {
        if ($('#blog-search').val().length == 0) {
            return false;
        }
        $(window).off('scroll');
        $('div', '.howitworks').remove();
        $('#blog-ajax-wrapper').hide();
        $('#search-ajax-loader').show();
        $.get('/posts/search.json', $(this).serialize(), function (result) {
            $('#search-ajax-loader').hide();
            url = '/posts/search.json?search=' + $('#blog-search').val();
            currentPage = 1;
            $('.js-blog-index-title').text('Результат поиска');
            posts = result.postsList;
            ReactDOM.render(React.createElement(BlogPostList, { posts: posts }), document.getElementById('blog-posts'));
            if (typeof result != 'undefined' && Object.keys(result.posts).length > 0) {
                scrollInit();
            }
        });
        return false;
    });

    $(document).on('focus', '#blog-search', function () {
        $('#post-search').addClass('active');
    });
    $(document).on('blur', '#blog-search', function () {
        $('#post-search').removeClass('active');
    });

    ReactDOM.render(React.createElement(BlogPostList, { posts: posts }), document.getElementById('blog-posts'));
});

/*
 * Class TopTip
 */
function TopTip() {
    var self = this;
    this.element = $('.onTop');
    this.middleInner = $('.middle_inner');
    this.init = function () {
        this.resize();
        this.element.on('click', function () {
            $('html, body').animate({ scrollTop: 0 }, 600);
        });
        $('.onTopMiddle').on('click', function () {
            $('html, body').animate({ scrollTop: 0 }, 600);
        });
        this.scrollHandler();
        this.hide();
    };
    this.show = function () {
        this.element.stop().animate({ 'bottom': '0' }, 200);
    };
    this.hide = function () {
        this.element.stop().animate({ 'bottom': '-105' }, 200);
    };
    this.resize = function () {
        var offsetLeft = self.middleInner.offset().left + self.middleInner.width() - 90;
        this.element.offset({ left: offsetLeft });
    };
    this.scrollHandler = function () {
        $(window).on('scroll', function () {
            self.visibility();
        });
    };
    this.visibility = function () {
        if ($(window).scrollTop() / $(window).height() > 2) {
            // number is a screens to scroll before Tip appear
            var middleBottom = self.middleInner.offset().top + self.middleInner.height() > $(window).scrollTop() + $(window).height();
            if (!middleBottom) {
                this.element.hide();
                $('.onTopMiddle').show();
            } else {
                $('.onTopMiddle').hide();
                this.element.show();
            }
            self.show();
        } else {
            self.hide();
        }
    };
}