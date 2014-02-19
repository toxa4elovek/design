$(document).ready(function() {
    $('.time').timeago();
    
    var currentTag = getParameterByName('tag');
    var tagQueryString = '';
    if (currentTag) {
        tagQueryString = '&tag=' + currentTag;
    }
    var currentPage = 1;
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
                $.getJSON('/posts.json?page=' + currentPage + tagQueryString, function(result) {
                    $('#blog-ajax-wrapper').hide();
                    if (result.posts.length == 0) { // No more posts
                        return false;
                    }
                    var keys = [];
                    for (i in result.posts) { keys.push(i); }
                    keys.sort().reverse();
                    var postsObj = result.posts;
                    var currentIndex = 1;
                    $.each(keys, function(idx, key) {
                        var field = postsObj[key];
                        //Title
                        if (field.published == 1) { // && (strtotime($post->created) < time()))
                            var title = '<a style="text-transform:uppercase;" href="/posts/view/' + field.id + '">' + field.title + '</a>';
                        } else {
                            var title = '<a style="text-transform:uppercase;color:#ccc;" href="/posts/view/' + field.id + '">' + field.title + '</a>';
                        }
                        
                        // Tags
                        var tagString = '';
                        var tagStringArray = [];
                        if (field.tags) {
                            var tagsArray = field.tags.split('|');
                            for(var i = 0; i < tagsArray.length; i++) {
                                tagStringArray.push('<a class="blogtaglink" href="/posts?tag=' + encodeURIComponent(tagsArray[i]) + '">' + tagsArray[i] + '</a>');
                            }
                            var tagString = tagStringArray.join(' &bull; ');
                        }
                        
                        // Date Time
                        var dateCreated = field.created.replace(' ', 'T'); // FF & IE date string parsing
                        var postDateObj = new Date(dateCreated);
                        var postDate = ('0' + postDateObj.getDate()).slice(-2) + '.' + ('0' + (postDateObj.getMonth() + 1)).slice(-2) + '.' + postDateObj.getFullYear();
                        var postTime = ('0' + postDateObj.getHours()).slice(-2) + ':' + ('0' + (postDateObj.getMinutes())).slice(-2);
                        
                        // Editor
                        var editor = ''
                        if (result.editor == 1) {
                            var editor = '<a target="_blank" class="more-editor" href="/posts/edit/' + field.id + '" style="">редактировать</a>';
                            editor += '<a target="_blank" class="more-editor delete-post" href="/posts/delete/' + field.id + '" style="">удалить</a>';
                        }
                        $(".howitworks").append(
                                '<div> \
                                <div style="float:left;width:249px;height:185px;background-image: url(/img/frame.png);margin-top:15px;"> \
                                    <img style="margin-top:4px;margin-left:4px;" width="240" height="175" src="' + field.imageurl + '" alt=""/> \
                                </div> \
                                <div style="float:left; width:330px; margin-left: 40px;"> \
                                    <h2 class="largest-header-blog">'
                                        + title +
                                    '</h2> \
                                    <p style="text-transform:uppercase;font-size:11px;color:#666666">' + postDate + ' &bull; ' + postTime + ' &bull; ' + tagString + '</p> \
                                    <div class="regular" style="margin-top:10px">'
                                        + field.short +
                                    '</div> \
                                    <div style="height:1px;width:200px;margin-bottom:10px;"></div>'
                                    + editor +
                                    '<a style="" class="more" href="/posts/view/' + field.id + '">Подробнее</a> \
                                </div> \
                                <div style="float:left;width:500px;margin-bottom: 20px; height:1px;"></div> \
                            </div>'
                        );
                        if(currentIndex == keys.length) {

                        }else {
                            currentIndex += 1;
                            $(".howitworks").append('<div style="clear:both;height:3px; background: url(/img/sep.png) repeat-x scroll 0 0 transparent;width:588px;margin-bottom:20px;"></div>');
                        }

                    });
                    Tip.visibility();
                    scrollInit();
                });
            }
        });
    }
    var Tip = new TopTip;
    Tip.init();
    scrollInit();
    
    $(window).on('resize', function() { Tip.resize(); });

    $( ".delete-post" ).on( "click", function() {
        if (confirm("Точно удалить статью?")) {
            return true;
        } else {
            return false;
        }
    });
    
    $(document).on('submit', '#post-search', function() {
        $.get($(this).attr('action') + '.json', $(this).serialize(), function(result) {
            console.log(result);
        });
        return false;
    });

});

/*
 * Class TopTip
 */
function TopTip() {
    var self = this;
    this.element = $('.onTop');
    this.init = function() {
        this.resize();
        this.element.on('click', function() { $('html, body').animate({ scrollTop: 0 }, 600 ) });
        $('.onTopMiddle').on('click', function() { $('html, body').animate({ scrollTop: 0 }, 600 ) });
        this.scrollHandler();
        this.hide();
    }
    this.show = function() {
        this.element.stop().animate({'bottom':'0'}, 200);
    }
    this.hide = function() {
        this.element.stop().animate({'bottom':'-105'}, 200);
    }
    this.resize = function() {
        var offsetLeft = $('.middle_inner').offset().left + $('.middle_inner').width() - 90;
        this.element.offset({left:offsetLeft});
    }
    this.scrollHandler = function() {
        $(window).on('scroll', function() {
            self.visibility();
        });
    }
    this.visibility = function() {
        if ($(window).scrollTop() / $(window).height() > 2) { // number is a screens to scroll before Tip appear
            var middleBottom = (($('.middle_inner').offset().top + $('.middle_inner').height()) > ($(window).scrollTop() + $(window).height()));
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
    }
}

function getParameterByName(name) {
    name = name.replace(/[\[]/, "\\\[").replace(/[\]]/, "\\\]");
    var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
        results = regex.exec(location.search);
    return results == null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
}