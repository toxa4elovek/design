// office.js start

var this_user = $('#user_id').val();

$(document).ready(function () {
    var Tip = new TopTip;
    isBusy = 0;

    function scrollInit() {
        var header_bg = $('#header-bg'),
                pitch_panel = $('#pitch-panel'),
                header_height = $('#header-bg').height(),
                header_pos = $('#header-bg').position().top,
                windowHeight = $(window).height(),
                $box = $('#floatingLayer'),
                $parent = $('.new-content');
        if (!pitch_panel.length) {
            $('<div id="pitch-panel"></div>').insertBefore(header_bg);
            pitch_panel = $('#pitch-panel');
        }
        $(window).on('scroll', function () {
            var parentAbsoluteTop = $parent.offset().top;
            var parentAbsoluteBottom = parentAbsoluteTop + $parent.height();
            var topStop = parentAbsoluteTop + $box.height();

            if ($(window).scrollTop() > header_pos && !header_bg.hasClass('flow')) {
                header_bg.css({'position': 'fixed', 'top': '0px', 'z-index': '101'}).addClass('flow');
                pitch_panel.css('padding-bottom', header_height + 'px');
            } else if (header_bg.hasClass('flow') && $(window).scrollTop() < header_pos) {
                header_bg.css({'position': 'static'}).removeClass('flow');
                pitch_panel.css('padding-bottom', '0px');
            }

            var windowBottom = $(window).scrollTop() + windowHeight;
            if (windowBottom < topStop)
                $box.css({
                    position: 'absolute',
                    top: '0px',
                    bottom: 'auto'
                });
            else if (windowBottom >= topStop && windowBottom <= parentAbsoluteBottom)
                $box.css({
                    position: 'fixed',
                    top: 'auto',
                    bottom: '0px'
                });
            else
                $box.css({
                    position: 'absolute',
                    top: 'auto',
                    bottom: '0px'
                });

            if (($(document).height() - $(window).scrollTop() - $(window).height() < 200) && !isBusy) {
                isBusy = 1;
                Tip.scrollHandler();
                $box.css({
                    position: 'fixed',
                    top: 'auto',
                    bottom: '0px'
                });
                Updater.nextPage();
            }
        });
    }

    function TopTip() {
        var self = this;
        this.element = $('.onTop');
        this.init = function () {
            this.resize();
            this.element.on('click', function () {
                $('html, body').animate({scrollTop: 0}, 600)
            });
            $('.onTopMiddle').on('click', function () {
                $('html, body').animate({scrollTop: 0}, 600)
            });
            this.scrollHandler();
            this.hide();
        };
        this.show = function () {
            this.element.stop().animate({'bottom': '0'}, 200);
        };
        this.hide = function () {
            this.element.stop().animate({'bottom': '-105'}, 200);
        };
        this.resize = function () {
            var offsetLeft = $('.new-content').offset().left + $('.new-content').width() - 400;
            this.element.offset({left: offsetLeft});
        };
        this.scrollHandler = function () {
            $(window).on('scroll', function () {
                self.visibility();
            });
        };
        this.visibility = function () {
            if ($(window).scrollTop() / $(window).height() > 2) { // number is a screens to scroll before Tip appear
                var middleBottom = (($('.new-content').offset().top + $('.new-content').height() + 100) > ($(window).scrollTop() + $(window).height()));
                if (!middleBottom) {
                    this.element.hide();
                    $('.onTopMiddle').offset({left: $('.new-content').offset().left + $('.new-content').width() - 400});
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
//Переключение активной сттаницы в Главном меню
    $('.main_nav a').live('click', function () {
        $('.main_nav a').removeClass('active');
        $(this).addClass('active');
    });
    //Hover на кнопке "full_pitch"
    $('p.full_pitch a').live('mouseover', function () {
        $(this).parent().siblings('h2').children().css('color', '#648fa4');
    });
    $('p.full_pitch a').live('mouseout', function () {
        $(this).parent().siblings('h2').children().css('color', '#666');
    });
    if (window.location.pathname.match(/users\/feed/)) {
        var Updater = new OfficeStatusUpdater();
        Tip.init();
        scrollInit();
        Updater.init();
    }


    $(document).on('click', '.like-small-icon', function () {
        console.log($(this).next());
        var likesNum = $(this).children();
        var likeLink = $(this).next();
        likesNum.html(parseInt(likesNum.html()));
        var sharebar = likesNum.parent().next();
        var solutionId = $(this).data('id');
        Socialite.load($(this).next(), [
            $('#facebook' + solutionId)[0],
            $('#twitter' + solutionId)[0]
        ]);
        $('body').one('click', function () {
            $('.sharebar').fadeOut(300);
        });
        $.get('/solutions/like/' + $(this).data('id') + '.json', function (response) {
            likesNum.html(response.likes);
            likeLink.off('click');
            sharebar.fadeIn(300);
            likeLink.off('mouseover');
            likeLink.on('click', function () {
                $('body').one('click', function () {
                    sharebar.fadeOut(300);
                });
                sharebar.fadeIn(300);
                return false;
            });
        });
        return false;
    });
    // Solution Stars
    $(document).on('mouseenter', '.ratingchange', function () {
        $(this).parent().css('background', 'url(/img/' + $(this).data('rating') + '-rating.png) no-repeat scroll 0% 0% transparent');
    });
    $(document).on('mouseleave', '.ratingcont', function () {
        $(this).css('background', 'url(/img/' + $(this).data('default') + '-rating.png) no-repeat scroll 0% 0% transparent');
    });
    $(document).on('click', '.ratingchange', function () {
        var id = $(this).parent().data('solutionid');
        var rating = $(this).data('rating');
        var self = $(this);
        $.post('/solutions/rating/' + id + '.json',
                {"id": id, "rating": rating}, function (response) {
            self.parent().data('default', rating);
            self.parent().css('background', 'url(/img/' + rating + '-rating.png) repeat scroll 0% 0% transparent');
        });
        return false;
    });
    $(document).on('click', '.post-to-facebook', function () {
        _gaq.push(['_trackEvent', 'Тест', 'Пользователь нажал кнопку расшаривания фейсбука']);
        sendFBMessage();
        return false;
    });
    var sendFBMessage = function () {
        var dataFbWallPost = {
            method: 'stream.publish',
            message: "",
            display: 'iframe',
            caption: " ",
            name: "Мой новый заказ на лучший дизайн",
            link: 'http://www.godesigner.ru/pitches/details/' + shareid,
            description: $('.post-to-facebook').data('share-text')
        };
        FB.ui(dataFbWallPost, function () {
        });
    };
    shareid = 0;
    var initShares = function () {
        setTimeout(function () {
            $('a.twitter-share-button').attr('data-url', 'http://www.godesigner.ru/pitches/details/' + shareid);
            !function (d, s, id) {
                var js, fjs = d.getElementsByTagName(s)[0];
                if (!d.getElementById(id)) {
                    js = d.createElement(s);
                    js.id = id;
                    js.src = "//platform.twitter.com/widgets.js";
                    fjs.parentNode.insertBefore(js, fjs);
                }
            }(document, "script", "twitter-wjs");
            // Vk
            $('.vk_share_button').replaceWith(VK.Share.button(
                    {
                        url: 'http://www.godesigner.ru/pitches/details/' + shareid,
                        title: 'Мой новый заказ на лучший дизайн',
                        description: $('.vk_share_button').data('share-text'),
                        noparse: true
                    },
            {
                type: 'round_nocount',
                text: 'Поделиться'
            }
            ));
            $('#vkshare1').on('click', function () {
                _gaq.push(['_trackEvent', 'Тест', 'Пользователь нажал кнопку расшаривания ВК']);
            });
            // Twitter
            twttr.widgets.load();
            twttr.events.bind('click', function (e) {
                if (e.target.id === 'twitter-widget-1') {
                    _gaq.push(['_trackEvent', 'Тест', 'Пользователь нажал кнопку расшаривания твиттера']);
                }
            });
        }, 2000);
    };
});
function getUrlVar(key) {
    var result = new RegExp(key + "=([^&]*)", "i").exec(window.location.search);
    return result && unescape(result[1]) || "";
}

function OfficeStatusUpdater() {
    var self = this;
    // storage object for saving data
    this.storage = {};
    this.page = 1;
    this.date = '';
    this.dateTwitter;
    this.newsDate;
    this.solutionDate;
    this.pitchDate;
    this.started = 0;
    // initialisation method
    this.init = function () {
        $('.obnovlenia_box').last().addClass('last_item');
        $('time.timeago').timeago();
        self.newsDate = newsDate;
        self.dateTwitter = $('#twitterDate').data('date');
        self.solutionDate = solutionDate;
        self.date = eventsDate;
        self.pitchDate = pitchDate;
        $(document).everyTime(20000, function (i) {
            if (self.started) {
                self.autoupdate();
            }
        });
        self.started = 1;
    },
            this.autoupdate = function () {
                $.get('/events/feed.json', {"init": true, "created": self.date, "twitterDate": self.dateTwitter, "newsDate": self.newsDate, "solutionDate": self.solutionDate, "pitchDate": self.pitchDate}, function (response) {
                    if (typeof (response.news) != "undefined" && response.news != null) {
                        news = '';
                        first_el = 0;
                        $.each(response.news, function (index, object) {
                            if (first_el == 0) {
                                self.newsDate = object.created;
                            }
                            first_el++;
                            news += '<div class="design-news"><a target="_blank" href="/users/click?link=' + object.link + '&id=' + object.id + '">' + object.title + ' <br><a class="clicks" href="/users/click?link=' + object.link + '&id=' + object.id + '">' + object.host + '</a></div>';
                        });
                        if (news != '') {
                            var $prependEl = $(news);
                            $prependEl.hide();
                            $prependEl.prependTo('#content-news').slideDown('slow');
                        }
                    }
                    if (typeof (response.twitter) != "undefined" && response.twitter != '') {
                        var $prependEl = $(response.twitter);
                        $prependEl.hide();
                        self.dateTwitter = $prependEl.first().data('date');
                        $prependEl.prependTo('#content-job').slideDown('slow');
                    }
                    var html = '';
                    var solutions = '';
                    if (typeof (response.post) != "undefined" && response.post != 0) {
                        var $prependEl = $('<div class="box"> \
                                <p class="img-box"> \
                                    <a class="post-link" href="/users/click?link=' + response.post.link + '&id=' + response.post.id + '"><img class="img-post" src="' + response.post.imageurl + '"></a> \
                                </p> \
                                <p class="img-tag">' + response.post.tags + '</p> \
                                <div class="r-content post-content"> \
                                    <a class="img-post" href="/users/click?link=' + response.post.link + '&id=' + response.post.id + '"><h2>' + response.post.title + '</h2></a> \
                                    <p class="timeago"> \
                                        <time class="timeago" datetime="' + response.post.created + '">' + response.post.created + '</time> с сайта ' + response.post.host + '</p> \
                                </div> \
                            </div>');
                        $prependEl.hide();
                        $prependEl.prependTo('#updates-box-').slideDown('slow');
                        $('time.timeago').timeago();
                    }
                    if (typeof (response.pitches) != "undefined") {
                        var pitches = '';
                        pitchesCount = 0;
                        $.each(response.pitches, function (index, pitch) {
                            if (pitchesCount == 0)
                                self.pitchDate = pitch.started;
                            pitchesCount++;
                            pitches += '<div class="new-pitches"> \
                                    <div class="new-price">' + parseInt(pitch.price) + 'р.</div> \
                                    <div class="new-title"><a href="/pitches/view/' + pitch.id + '">' + pitch.title + '</a></div> \
                                </div>'
                        });
                        var $prependEl = $(pitches);
                        $prependEl.hide();
                        $prependEl.prependTo('#content-pitches').slideDown('slow');
                    }
                    if (typeof (response.solutions) != "undefined" && response.solutions != null) {
                        var solutions = '';
                        solcount = 0;
                        $.each(response.solutions, function (index, solution) {
                            if (solcount == 0)
                                self.solutionDate = solution.created;
                            solcount++;
                            if (typeof (solution.solution.images.solution_leftFeed) != "undefined") {
                                if (typeof (solution.solution.images.solution_leftFeed.length) == "undefined") {
                                    var imageurl = solution.solution.images.solution_leftFeed.weburl;
                                } else {
                                    var imageurl = solution.solution.images.solution_leftFeed[0].weburl;
                                }
                                if (Math.floor((Math.random() * 100) + 1) <= 50) {
                                    tweetLike = 'Мне нравится этот дизайн! А вам?';
                                } else {
                                    tweetLike = 'Из всех ' + solution.pitch.ideas_count + ' мне нравится этот дизайн';
                                }
                                solutions += '<div class="solutions-block"> \
                                    <a href="/pitches/viewsolution/' + solution.solution.id + '"><img src="' + imageurl + '"></a> \
                                    <div class="solution-info"> \
                                        <p class="creator-name"><a target="_blank" href="/users/view/' + solution.user_id + '">' + solution.creator + '</a></p> \
                                        <p class="ratingcont" data-default="' + solution.solution.rating + '" data-solutionid="' + solution.solution.id + '" style="height: 9px; background: url(/img/' + solution.solution.rating + '-rating.png) no-repeat scroll 0% 0% transparent;display:inline-block;width: 56px;"></p> \
                                        <a data-id="' + solution.solution.id + '" class="like-small-icon" href="#"><span>' + solution.solution.likes + '</span></a>\
                                        <div class="sharebar" style="padding:0 0 4px !important;background:url(/img/tooltip-bg-bootom-stripe.png) no-repeat scroll 0 100% transparent !important;position:absolute;z-index:10000;display: none; left: 250px; top: 27px;height: 178px;width:288px;">\
                                            <div class="tooltip-wrap" style="height: 140px; background: url(/img/tooltip-top-bg.png) no-repeat scroll 0 0 transparent !important;padding:39px 10px 0 16px !important">\
                                                <div class="body" style="display: block;">\
                                                    <table  width="100%">\
                                                        <tr height="35">\
                                                            <td width="137" valign="middle">\
                                                                <a id="facebook' + solution.solution.id + '" class="socialite facebook-like" href="http://www.facebook.com/sharer.php?u=http://www.godesigner.ru/pitches/viewsolution/' + solution.solution.id + '" data-href="http://www.godesigner.ru/pitches/viewsolution/' + solution.solution.id + '" data-send="false" data-layout="button_count">\
                                                                    Share on Facebook\
                                                                </a>\
                                                            </td>\
                                                            <td width="137" valign="middle">\
                                                                <a id="twitter' + solution.solution.id + '" class="socialite twitter-share" href="" data-url="http://www.godesigner.ru/pitches/viewsolution/' + solution.solution.id + '?utm_source=twitter&utm_medium=tweet&utm_content=like-tweet&utm_campaign=sharing" data-text="' + tweetLike + '" data-lang="ru" data-hashtags="Go_Deer">\
                                                                    Share on Twitter \
                                                                </a>\
                                                            </td>\
                                                        </tr>\
                                                        <tr height="35">\
                                                            <td valign="middle">\
                                                                <a href="http://www.tumblr.com/share" title="Share on Tumblr" style="display:inline-block; text-indent:-9999px; overflow:hidden; width:81px; height:20px; background:url(http://platform.tumblr.com/v1/share_1.png) top left no-repeat transparent;">Share on Tumblr</a> \
                                                            </td>\
                                                            <td valign="middle">\
                                                                <a href="http://pinterest.com/pin/create/button/?url=http%3A%2F%2Fwww.godesigner.ru%2Fpitches%2Fviewsolution%2F' + solution.solution.id + '&media=' + encodeURIComponent('http://www.godesigner.ru' + solution.solution.images['solution_solutionView'][0]) + '&description=%D0%9E%D1%82%D0%BB%D0%B8%D1%87%D0%BD%D0%BE%D0%B5%20%D1%80%D0%B5%D1%88%D0%B5%D0%BD%D0%B8%D0%B5%20%D0%BD%D0%B0%20%D1%81%D0%B0%D0%B9%D1%82%D0%B5%20GoDesigner.ru" class="pin-it-button" count-layout="horizontal"><img border="0" src="//assets.pinterest.com/images/PinExt.png" title="Pin It" /></a>\
                                                            </td>\
                                                        </tr>\
                                                    </table>\
                                                </div>\
                                            </div>\
                                        </div>\
                                    </div> \
                                </div>';
                            }
                        });
                        if (solutions != '') {
                            var $prependEl = $(solutions);
                            $prependEl.hide();
                            $prependEl.prependTo('#l-sidebar-office').slideDown('slow');
                        }
                    }
                    if (typeof (response.updates) != "undefined") {
                        if (response.count != 0) {
                            function sortfunction(a, b) {
                                return (a.sort - b.sort);
                            }
                            response.updates.sort(sortfunction);
                            $.each(response.updates, function (index, object) {
                                if (index == 0) {
                                    self.date = object.created;
                                }
                                if (!object.solution) {
                                    object.solution = {};
                                    object.solution.images = {};
                                    object.solution.images.solution_galleryLargeSize = {};
                                    object.solution.views = 0;
                                    object.solution.likes = 0;
                                    object.solution.images.solution_galleryLargeSize.weburl = '';
                                }
                                if (typeof (object.solution.images.solution_solutionView) != "undefined") {
                                    if (typeof (object.solution.images.solution_solutionView.length) == "undefined") {
                                        var imageurl = object.solution.images.solution_solutionView.weburl;
                                    } else {
                                        var imageurl = object.solution.images.solution_solutionView[0].weburl;
                                    }
                                }
                                if (object.type == 'PitchCreated') {
                                    var imageurl = '/img/zaglushka.jpg';
                                }
                                if (object.type == 'SolutionAdded' && object.solution != null && typeof object.solution.id != "undefined") {
                                    avatar = (typeof object.user.images['avatar_small'] != 'undefined') ? object.user.images['avatar_small'].weburl : '/img/default_small_avatar.png';
                                    html += '<div class="box"> \
                            <div class="l-img"> \
                                <img class="avatar" src="' + avatar + '"> \
                            </div> \
                            <div class="r-content"> \
                                <a href="/users/view/' + object.user_id + '">' + object.creator + '</a> предложил решение для питча <a href="/pitches/view/' + object.pitch_id + '">' + object.pitch.title + '</a>: \
                            </div> \
                            <a href="/pitches/viewsolution/' + object.solution.id + '"><img class="sol" src="' + imageurl + '"></a> \
                            <div data-id="' + object.solution.id + '" class="likes">';
                                    id = object.solution.id;
                                    user_id = $('#user_id').val();
                                    $.each(response.updates, function (index, object) {
                                        if (object.type == 'LikeAdded' && object.solution_id == id) {
                                            avatar = (typeof object.user.images['avatar_small'] != 'undefined') ? object.user.images['avatar_small'].weburl : '/img/default_small_avatar.png';
                                            txtsol = (user_id == object.user_id) ? 'ваше ' : '';
                                            html += '<div> \
                                    <div class="l-img"> \
                                        <img class="avatar" src="' + avatar + '"> \
                                    </div> \
                                    <span><a href="/users/view/' + object.user_id + '">' + object.creator + '</a> лайкнул ' + txtsol + 'решение</span> \
                                </div>';
                                        }
                                    });
                                    html += '</div></div>';
                                }

                                if (object.type == 'CommentAdded' && object.comment != null) {
                                    if (object.user.isAdmin == 1) {
                                        avatar = '/img/icon_57.png';
                                    } else {
                                        avatar = (typeof object.user.images['avatar_small'] != 'undefined') ? object.user.images['avatar_small'].weburl : '/img/default_small_avatar.png';
                                    }
                                    html += '<div class="box"> \
                            <div class="l-img"> \
                                <img class="avatar" src="' + avatar + '"> \
                            </div> \
                            <div class="r-content">';
                                    if (this_user == object.pitch.user_id || (object.comment.public == 1 && object.comment.reply_to == 0)) {
                                        html += '<a href="/users/view/' + object.user_id + '">' + object.creator + '</a> прокомментировал ваше <a href="/pitches/viewsolution/' + object.solution.id + '">решение #' + object.solution.num + '</a> для питча <a href="/pitches/view/' + object.pitch_id + '">' + object.pitch.title + '</a>: &laquo;' + object.updateText + '&raquo;';
                                        html += '</div><img class="sol" src="' + imageurl + '">';
                                    }
                                    else {
                                        html += '<a href="/users/view/' + object.user_id + '">' + object.creator + '</a> оставил комментарий в питче <a href="/pitches/view/' + object.pitch_id + '">' + object.pitch.title + '</a>: &laquo;' + object.updateText + '&raquo;';
                                        html += '</div><img class="sol" src="' + imageurl + '">';
                                    }
                                    html += '</div>';
                                }
                            });
                            var $prependEl = $(html);
                            $prependEl.hide();
                            $prependEl.prependTo('#updates-box-').slideDown('slow');
                        }
                    }
                });
            }
    this.nextPage = function () {
        self.page += 1;
        $('#officeAjaxLoader').show();
        var $formerLast = $('.box').last();
        $.get('/events/feed.json', {"init": true, "page": self.page}, function (response) {
            $('#officeAjaxLoader').hide();
            if (response.count != 0) {
                function sortfunction(a, b) {
                    return (a.sort - b.sort);
                }
                response.updates.sort(sortfunction);
                var html = '';
                $.each(response.updates, function (index, object) {
                    if (!object.solution) {
                        object.solution = {};
                        object.solution.images = {};
                        object.solution.images.solution_galleryLargeSize = {};
                        object.solution.views = 0;
                        object.solution.likes = 0;
                        object.solution.images.solution_galleryLargeSize.weburl = '';
                    }

                    if (typeof (object.solution.images.solution_solutionView) != "undefined") {
                        if (typeof (object.solution.images.solution_solutionView.length) == "undefined") {
                            var imageurl = object.solution.images.solution_solutionView.weburl;
                        } else {
                            var imageurl = object.solution.images.solution_solutionView[0].weburl;
                        }
                    }

                    if (object.type == 'SolutionAdded' && object.solution != null && typeof object.solution.id != "undefined") {
                        avatar = (typeof object.user.images['avatar_small'] != 'undefined') ? object.user.images['avatar_small'].weburl : '/img/default_small_avatar.png';
                        html += '<div class="box"> \
                            <div class="l-img"> \
                                <img class="avatar" src="' + avatar + '"> \
                            </div> \
                            <div class="r-content"> \
                                <a href="/users/view/' + object.user_id + '">' + object.creator + '</a> предложил решение для питча <a href="/pitches/view/' + object.pitch_id + '">' + object.pitch.title + '</a>: \
                            </div> \
                            <a href="/pitches/viewsolution/' + object.solution.id + '"><img class="sol" src="' + imageurl + '"></a> \
                            <div data-id="' + object.solution.id + '" class="likes">';
                        id = object.solution.id;
                        $.each(response.updates, function (index, object) {
                            if (object.type == 'LikeAdded' && object.solution_id == id) {
                                avatar = (typeof object.user.images['avatar_small'] != 'undefined') ? object.user.images['avatar_small'].weburl : '/img/default_small_avatar.png';
                                html += '<div> \
                                    <div class="l-img"> \
                                        <img class="avatar" src="' + avatar + '"> \
                                    </div> \
                                    <span><a href="/users/view/' + object.user_id + '">' + object.creator + '</a> лайкнул ваше решение</span> \
                                </div>';
                            }
                        });
                        html += '</div></div>';
                    }

                    if (object.type == 'CommentAdded' && object.comment != null) {
                        if (object.user.isAdmin == 1) {
                            avatar = '/img/icon_57.png';
                        } else {
                            avatar = (typeof object.user.images['avatar_small'] != 'undefined') ? object.user.images['avatar_small'].weburl : '/img/default_small_avatar.png';
                        }
                        html += '<div class="box"> \
                            <div class="l-img"> \
                                <img class="avatar" src="' + avatar + '"> \
                            </div> \
                            <div class="r-content">';
                        if (this_user == object.pitch.user_id || (object.comment.public == 1 && object.comment.reply_to == 0)) {
                            html += '<a href="/users/view/' + object.user_id + '">' + object.creator + '</a> оставил комментарий в питче <a href="/pitches/view/' + object.pitch_id + '">' + object.pitch.title + '</a>: &laquo;' + object.updateText + '&raquo;';
                        }
                        else {
                            html += '<a href="/users/view/' + object.user_id + '">' + object.creator + '</a> прокомментировал ваше <a href="/pitches/viewsolution/' + object.solution.id + '">решение #' + object.solution.num + '</a> для питча <a href="/pitches/view/' + object.pitch_id + '">' + object.pitch.title + '</a>: &laquo;' + object.updateText + '&raquo;';
                        }
                        html += '</div><img class="sol" src="' + imageurl + '"></div>';
                    }
                });
                var $appendEl = $(html);
                $appendEl.hide();
                $appendEl.appendTo('#updates-box-').slideDown('slow');
            }
            if (response.nextUpdates < 1) {
                isBusy = 1;
            } else {
                isBusy = 0;
            }
        });
    }
    this._priceDecorator = function (price) {
        price = price.replace(/(.*)\.00/g, "$1");
        counter = 1;
        while (price.match(/\w\w\w\w/)) {
            price = price.replace(/^(\w*)(\w\w\w)(\W.*)?$/, "$1 $2$3");
            counter++;
            if (counter > 6)
                break;
        }
        return price;
    }
}
;