// office.js start

var this_user = $('#user_id').val();
var newMessages = 0,
        isWinActive = true;
function onBlur() {
    isWinActive = false;
}
function onFocus() {
    isWinActive = true;
}

if (/*@cc_on!@*/false) { // check for Internet Explorer
    document.onfocusin = onFocus;
    document.onfocusout = onBlur;
} else {
    window.onfocus = onFocus;
    window.onblur = onBlur;
}

doc_title = $('title');
window.onfocus = function () {
    if (doc_title.text().charAt(0) == '(') {
        erase_title = doc_title.text().split(')')[1];
        erase_title = erase_title.trim();
        doc_title.text(erase_title);
    }
    newMessages = 0;
}
$(document).ready(function () {

    $('input[name="gender"]').on('change', function () {
        $.post('/users/gender/' + $('#user_id').val() + '.json', {gender: $(this).attr('id')}, function (response) {
        });
        setTimeout(
                function () {
                    $('#gender-box').animate({'height': 'toggle'}, function () {
                        $('.new-content').css({'margin-top': '70px'});
                    });
                }, 1000
                );
    });

    $('#close-gender').on('click', function () {
        $('#gender-box').hide();
        $('.new-content').css({'margin-top': '70px'});
    });

    var Tip = new TopTip;
    isBusy = 0;
    isBusySolution = 0;
    function scrollInit() {
        var header_bg = $('#header-bg'),
                pitch_panel = $('#pitch-panel'),
                header_height = $('#header-bg').height(),
                header_pos = $('#header-bg').position().top,
                windowHeight = $(window).height(),
                $box = $('#container-design-news'),
                $parent = $('.new-content'),
                leftSidebar = $('#l-sidebar-office'),
                leftSidebarTop = leftSidebar.offset().top;
        var top = $('#container-design-news').offset().top;
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

            if ($(window).scrollTop() + header_height + 5 > top) {
                $box.css({'position': 'fixed', 'top': '35px'});
            } else if (windowBottom >= topStop && $(window).scrollTop() < top) {
                $box.css({'position': 'static', 'top': '35px'});
            }

//            var windowBottom = $(window).scrollTop() + windowHeight;
//            if (windowBottom < topStop)
//                $box.css({
//                    position: 'absolute',
//                    top: '0px',
//                    bottom: 'auto'
//                });
//            else if (windowBottom >= topStop && windowBottom <= parentAbsoluteBottom)
//                $box.css({
//                    position: 'fixed',
//                    top: 'auto',
//                    bottom: '0px'
//                });
//            else
//                $box.css({
//                    position: 'absolute',
//                    top: 'auto',
//                    bottom: '0px'
//                });
            if (($(document).height() - $(window).scrollTop() - $(window).height() < 200) && !isBusy) {
                isBusy = 1;
                Tip.scrollHandler();
                $box.css({
                    position: 'fixed',
                    top: '35px',
                    bottom: '0px'
                });
                Updater.nextPage();
            }
            if ((leftSidebarTop + leftSidebar.height() - $(window).scrollTop() - $(window).height() < 50) && !isBusySolution) {
                isBusySolution = 1;
                Updater.getSolutions();
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

    var clickedLikesList = []

    $(document).on('click', '.like-small-icon', function () {
        var likesNum = $(this).children();
        var likeLink = $(this).next();
        likesNum.html(parseInt(likesNum.html()));
        var sharebar = likesNum.parent().next();
        var solutionId = $(this).data('id');
        /*Socialite.load($(this).next(), [
         $('#facebook' + solutionId)[0],
         $('#twitter' + solutionId)[0]
         ]);
         $('body').one('click', function () {
         $('.sharebar').fadeOut(300);
         });*/
        if ($.inArray($(this).data('id'), clickedLikesList) == -1) {
            likesNum.html(parseInt(likesNum.html()) + 1);
            clickedLikesList.push($(this).data('id'));
        }
        $.get('/solutions/like/' + $(this).data('id') + '.json', function (response) {
            likesNum.html(response.likes);
            /*likeLink.off('click');
             sharebar.fadeIn(300);
             likeLink.off('mouseover');
             likeLink.on('click', function () {
             console.log('second');
             likesNum.html(parseInt(likesNum.html()) - 1);
             $('body').one('click', function () {
             sharebar.fadeOut(300);
             });
             sharebar.fadeIn(300);
             return false;
             });*/
        });
        return false;
    });

    $(document).on('click', '.like-small-icon-box', function () {
        var link = $(this);
        if (link.data('vote') == '1') {
            link.html('Не нравится');
            link.data('vote', '0');
            $.get('/solutions/like/' + $(this).data('id') + '.json', function (response) {
                if (response.result == false) {
                    link.html('Нравится');
                    link.data('vote', '1');
                }
            });
        } else {
            link.html('Нравится');
            link.data('vote', '1');
            $.get('/solutions/unlike/' + $(this).data('id') + '.json', function (response) {
                if (response.result == false) {
                    link.html('Не нравится');
                    link.data('vote', '0');
                }
            });
        }
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

    $(document).on('click', '.select-multiwinner', function () {
        var num = $(this).data('num');
        var item = $('.photo_block', '#li_' + num).clone();
        $('#winner-num-multi').text('#' + num);
        $('#winner-num-multi').attr('href', '/pitches/viewsolution/' + $(this).data('solutionid'));
        $('#winner-user-link-multi').text($(this).data('user'));
        $('#winner-user-link-multi').attr('href', '/users/view/' + $(this).data('userid'));
        $('#confirmWinner-multi').data('url', $(this).attr('href'));
        $('#multi-replacingblock').replaceWith(item);
        $('#popup-final-step-multi').modal({
            containerId: 'final-step-multi',
            opacity: 80,
            closeClass: 'popup-close'
        });
        return false;
    });

    // Select Winner Solution
    $('body, .solution-overlay').on('click', '.select-winner', function (e) {
        e.preventDefault();
        $('#winner-num').text('#' + $(this).data('num'));
        $('#winner-num').attr('href', '/pitches/viewsolution/' + $(this).data('solutionid'));
        $('#winner-user-link').text($(this).data('user'));
        $('#winner-user-link').attr('href', '/users/view/' + $(this).data('userid'));
        $('#confirmWinner').data('url', $(this).attr('href'));
        var item = $('.select-winner[data-solutionid=' + $(this).data('solutionid') + ']').parent().parent().parent().prev().prev();
        if (item.length > 0) {
            item = item.clone();
        } else {
            item = $('<div id="replacingblock" class="photo_block"> \
                        <a href="#" onClick="return false;"><img alt="" src="' + solutionThumbnail + '"></a> \
                        <div class="photo_opt"> \
                        <span class="rating_block"><img alt="" src="/img/' + $('.rating-image', '.solution-rating').attr('data-rating') + '-rating.png"></span> \
                        <span class="like_view" style="margin-top:1px;"><img class="icon_looked" alt="" src="/img/looked.png"><span>' + $('.isField.value-views').text() + '</span> \
                        <a data-id="57" class="like-small-icon" href="#"><img alt="" src="/img/like.png"></a><span>' + $('.isField.value-likes').text() + '</span></span> \
                        <span class="bottom_arrow"><a class="solution-menu-toggle" href="#"><img alt="" src="/img/marker5_2.png"></a></span> \
                    </div>');
        }
        $('#replacingblock').replaceWith(item);
        $('#popup-final-step').modal({
            containerId: 'final-step',
            opacity: 80,
            closeClass: 'popup-close'
        });
        return false;
    });

    $(document).on('click', '#confirmWinner', function () {
        var url = $(this).data('url');
        $.get(url, function (response) {
            if (response.result != false) {
                if (response.result.nominated) {
                    window.location = '/users/nominated';
                    $('.select-winner-li').remove();
                }
            }
        });
    });

    $(document).on('click', '#confirmWinner-multi', function () {
        var url = $(this).data('url');
        if ($(this).data('url')) {
            window.location = url;
        }
    });

    var warnPlaceholder = 'ВАША ЖАЛОБА';
    $('#warn-comment, #warn-solution').on('focus', function () {
        $(this).removeAttr('placeholder');
    });
    $('#warn-comment, #warn-solution').on('blur', function () {
        $(this).attr('placeholder', warnPlaceholder);
    });

    // Warn Solution
    $('body, .solution-overlay').on('click', '.warning, .warning-box', function (e) {
        e.preventDefault();
        $('#sendWarn').data('url', $(this).attr('href'));
        $('#popup-warning').modal({
            containerId: 'final-step',
            opacity: 80,
            closeClass: 'popup-close',
            onShow: function () {
                $('#warn-solution').val('');
            }
        });
        return false;
    });
    $('#sendWarn').on('click', function () {
        var url = $(this).data('url');
        if (($('#warn-solution').val().length > 0) && ($('#warn-solution').val() != warnPlaceholder)) {
            $.post(url, {"text": $('#warn-solution').val()}, function (response) {
                warningThanks();
            });
        } else {
            alert('Введите текст жалобы!');
        }
    });

    // Comment Bubble
    $(document).on('click', '.solution-link-menu', function (e) {
        e.preventDefault();
        window.location = '/pitches/viewsolution/' + $(this).data('id') + '?to_comment=1';
    });
    $(document).on('mouseover', '.solution-menu-toggle', function () {
        $('img', $(this)).attr('src', '/img/marker5_2_hover.png');
        $('body').one('click', function () {
            $('.solution_menu.temp').fadeOut(200, function () {
                $(this).remove();
            });
        });
        var container = $(this).closest('.solution-info');
        var menu = container.find('.solution_menu');
        var offset = container.offset();
        menu = menu.clone();
        menu.addClass('temp');
        $('body').append(menu);
        menu.offset({top: offset.top + 36, left: offset.left + 158});
        menu.fadeIn(200);
        $(menu).on('mouseleave', function () {
            $(this).fadeOut(200, function () {
                $(this).remove();
            });
        });
        $('.solution-info').on('mouseenter', function () {
            $('.solution_menu.temp').fadeOut(200, function () {
                $(this).remove();
            });
        });
    });

    $(document).on('mouseleave', '.solution-menu-toggle', function () {
        $('img', $(this)).attr('src', '/img/marker5_2.png');
    });

    $(document).on('click', '.solution-menu-toggle', function () {
        return false;
    });
    // Delete Solution
    $(document).on('click', '.delete-solution', function (e) {
        e.preventDefault();
        console.log('111');
        // Delete without Moderation
        if (!isCurrentAdmin) {
            return true;
        }
        var link = $(this);
        if (link.attr('data-pressed') == 'on') {
            window.location = link.attr("href");
        }

        // Show Delete Moderation Overlay
        $('#popup-delete-solution').modal({
            containerId: 'final-step-clean',
            opacity: 80,
            closeClass: 'popup-close',
            onShow: function () {
                $('#model_id', '#popup-delete-solution').val(link.data('solution'));
                link.attr('data-pressed', 'on');
                $(document).on('click', '.popup-close', function () {
                    link.attr('data-pressed', 'off');
                });
                $(document).off('click', '#sendDeleteSolution');
            }
        });

        // Delete Solution Popup Form
        $(document).on('click', '#sendDeleteSolution', function () {
            var form = $(this).parent().parent();
            if (!$('input[name=reason]:checked', form).length || !$('input[name=penalty]:checked', form).length) {
                $('#popup-delete-solution').addClass('wrong-input');
                return false;
            }
            var $spinner = $(this).next();
            $spinner.addClass('active');
            $(document).off('click', '#sendDeleteSolution');
            var data = form.serialize();
            $.post(form.attr('action') + '.json', data).done(function (result) {
                link.click();
            });
            return false;
        });
        return false;
    });
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
    this.solPage = 1;
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
                            host = parse_url_regex(object.link);
                            news += '<div class="design-news"><a target="_blank" href="/users/click?link=' + object.link + '&id=' + object.id + '">' + object.title + ' <br><a class="clicks" href="/users/click?link=' + object.link + '&id=' + object.id + '">' + host[3] + '</a></div>';
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
                                <div class="r-content post-content"> \
                                    <p class="img-tag">' + response.post.tags + '</p> \
                                    <a class="img-post" href="' + response.post.link + '"><h2>' + response.post.title + '</h2></a> \
                                    <p class="img-short">' + response.post.short + '</p> \
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
                                    <a href="/pitches/viewsolution/' + solution.solution.id + '"><div class="left-sol" style="background: url(' + imageurl + ')"></div></a> \
                                    <div class="solution-info"> \
                                        <p class="creator-name"><a target="_blank" href="/users/view/' + solution.user_id + '">' + solution.creator + '</a></p> \
                                        <p class="ratingcont" data-default="' + solution.solution.rating + '" data-solutionid="' + solution.solution.id + '" style="height: 9px; background: url(/img/' + solution.solution.rating + '-rating.png) no-repeat scroll 0% 0% transparent;display:inline-block;width: 56px;"></p> \
                                        <a data-id="' + solution.solution.id + '" class="like-small-icon" href="#"><span>' + solution.solution.likes + '</span></a>\
                                        <div class="sharebar" style="padding:0 0 4px !important;background:url(/img/tooltip-bg-bootom-stripe.png) no-repeat scroll 0 100% transparent !important;position:absolute;z-index:10000;display: none; left: 121px; top: 27px;height: 178px;width:288px;">\
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
                                        <span class="bottom_arrow">\
                                            <a href="#" class="solution-menu-toggle"><img src="/img/marker5_2.png" alt=""></a>\
                                        </span>\
                                        <div class="solution_menu" style="display: none;">\
                                            <ul class="solution_menu_list" style="position:absolute;z-index:6;">';
                                if (solution.pitch.user_id == this_user && solution.pitchesCount < 1 && !solution.selectedSolutions) {
                                    solutions += '<li class="sol_hov select-winner-li" style="margin:0;width:152px;height:20px;padding:0;">\
                                                        <a class="select-winner" href="/solutions/select/' + solution.solution.id + '.json" data-solutionid="' + solution.solution.id + '" data-user="' + solution.creator + '" data-num="' + solution.solution.num + '" data-userid="' + solution.solution.user_id + '">Назначить победителем</a>\
                                                    </li>';
                                } else if (solution.pitch.user_id == this_user && (solution.solution.pitch.awarded != solution.solution.id) && ((solution.solution.pitch.status == 1) || (solution.solution.pitch.status == 2)) && solution.solution.pitch.awarded != 0) {
                                    solutions += '<li class="sol_hov select-winner-li" style="margin:0;width:152px;height:20px;padding:0;">\
                                                        <a class="select-multiwinner" href="/pitches/setnewwinner/' + solution.solution.id + '" data-solutionid="' + solution.solution.id + '" data-user="' + solution.creator + '" data-num="' + solution.solution.num + '" data-userid="' + solution.solution.user_id + '">Назначить ' + solution.pitchesCount + 2 + ' победителя</a>\
                                                    </li>';
                                }
                                if (solution.pitch.user_id == this_user && isAllowedToComment) {
                                    solutions += '<li class="sol_hov" style="margin:0;width:152px;height:20px;padding:0;"><a href="#" class="solution-link-menu" data-id="' + solution.solution.id + '" data-comment-to="#' + solution.solution.num + '">Комментировать</a></li>';
                                }
                                solutions += '<li class="sol_hov" style="margin:0;width:152px;height:20px;padding:0;"><a href="/solutions/warn/' + solution.solution.id + '.json" class="warning" data-solution-id="' + solution.solution.id + '">Пожаловаться</a></li>';
                                if (isAdmin) {
                                    solutions += '<li class="sol_hov" style="margin:0;width:152px;height:20px;padding:0;"><a class="delete-solution" data-solution="' + solution.solution.id + '" data-solution_num="' + solution.solution.num + '" href="/solutions/delete/' + solution.solution.id + '.json">Удалить</a></li>';
                                }
                                solutions += '</ul>\
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
                        newMessages += response.count;
                        if (!isWinActive && newMessages != 0) {
                            if (doc_title.text().charAt(0) == '(') {
                                erase_title = doc_title.text().split(')')[1];
                                erase_title = erase_title.trim();
                                doc_title.text('(' + newMessages + ') ' + erase_title);
                            } else {
                                doc_title.text('(' + newMessages + ') ' + doc_title.text());
                            }
                        }
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
                                    var like_txt = object.allowLike ? 'Нравится' : 'Не нравится';
                                    html += '<div class="box"> \
                            <div class="l-img"> \
                                <a target="_blank" href="/users/view/' + object.user_id + '"><img class="avatar" src="' + avatar + '"></a> \
                            </div> \
                            <div class="r-content"> \
                                <a href="/users/view/' + object.user_id + '">' + object.creator + '</a> ' + self.getGenderTxt('предложил', object.user.gender) + ' решение для питча <a href="/pitches/view/' + object.pitch_id + '">' + object.pitch.title + '</a>: \
                            </div> \
                            <a href="/pitches/viewsolution/' + object.solution.id + '"><div class="sol"><img src="' + imageurl + '"></div></a> \
                            <div class="box-info">\
                                <a href="/solutions/warn/' + object.solution.id + '.json" class="warning-box" data-solution-id="' + object.solution.id + '">Пожаловаться</a>\
                                <a data-id="' + object.solution.id + '" class="like-small-icon-box" data-vote="' + object.allowLike + '" data-likes="' + object.solution.likes + '" href="#">' + like_txt + '</a>\
                            </div>\
                            <div data-id="' + object.solution.id + '" class="likes">';
                                    id = object.solution.id;
                                    user_id = $('#user_id').val();
                                    $.each(response.updates, function (index, object) {
                                        if (object.type == 'LikeAdded' && object.solution_id == id) {
                                            avatar = (typeof object.user.images['avatar_small'] != 'undefined') ? object.user.images['avatar_small'].weburl : '/img/default_small_avatar.png';
                                            txtsol = (user_id == object.user_id) ? 'ваше ' : '';
                                            html += '<div> \
                                    <div class="l-img"> \
                                        <a target="_blank" href="/users/view/' + object.user_id + '"><img class="avatar" src="' + avatar + '"></a> \
                                    </div> \
                                    <span><a href="/users/view/' + object.user_id + '">' + object.creator + '</a> ' + self.getGenderTxt('лайкнул', object.user.gender) + ' ' + txtsol + 'решение < /span> \
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
                                    var like_txt = object.allowLike ? 'Нравится' : 'Не нравится';
                                    html += '<div class="box">\
                                    <div class="sol"><img src="' + imageurl + '"></div> \
                                    <div class="box-info">\
                                        <a href="/solutions/warn/' + object.solution.id + '.json" class="warning-box" data-solution-id="' + object.solution.id + '">Пожаловаться</a>\
                                        <a data-id="' + object.solution.id + '" class="like-small-icon-box" data-vote="' + object.allowLike + '" data-likes="' + object.solution.likes + '" href="#">' + like_txt + '</a>\
                                    </div>\
                                    <div class="l-img l-img-box"> \
                                        <a target="_blank" href="/users/view/' + object.user_id + '"><img class="avatar" src="' + avatar + '"></a> \
                                    </div> \
                                    <div class="r-content box-comment">';
                                    if (this_user == object.pitch.user_id || (object.comment.public == 1 && object.comment.reply_to != 0)) {
                                        html += '<a href="/users/view/' + object.user_id + '">' + object.creator + '</a> ' + self.getGenderTxt('прокомментировал', object.user.gender) + ' <a href="/pitches/viewsolution/' + object.solution.id + '">решение #' + object.solution.num + '</a> для питча <a href="/pitches/view/' + object.pitch_id + '">' + object.pitch.title + '</a>:<br /> &laquo;' + object.updateText + '&raquo;</div>';
                                    }
                                    else {
                                        html += '<a href="/users/view/' + object.user_id + '">' + object.creator + '</a> ' + self.getGenderTxt('оставил', object.user.gender) + ' комментарий в питче <a href="/pitches/view/' + object.pitch_id + '">' + object.pitch.title + '</a>:<br /> &laquo;' + object.updateText + '&raquo;</div>';
                                    }
                                    html += '</div>';
                                }

                                if (object.type == 'newsAdded' && object.news != null) {
                                    html += '<div class="box"> \
                                <p class="img-box"> \
                                    <a class="post-link" href="' + object.news.link + '"><img class="img-post" src="' + object.news.imageurl + '"></a> \
                                </p> \
                                <div class="r-content post-content"> \
                                    <p class="img-tag">' + object.news.tags + '</p> \
                                    <a class="img-post" href="' + object.news.link + '"><h2>' + object.news.title + '</h2></a> \
                                    <p class="img-short">' + object.news.short + '</p> \
                                    <p class="timeago"> \
                                        <time class="timeago" datetime="' + object.news.created + '">' + object.news.created + '</time> с сайта ' + object.host + '</p> \
                                </div> \
                            </div>';
                                }

                            });
                            var $prependEl = $(html);
                            $prependEl.hide();
                            $('time.timeago').timeago();
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
                        var like_txt = object.allowLike ? 'Нравится' : 'Не нравится';
                        html += '<div class="box"> \
                            <div class="l-img"> \
                                <a target="_blank" href="/users/view/' + object.user_id + '"><img class="avatar" src="' + avatar + '"></a> \
                            </div> \
                            <div class="r-content"> \
                                <a href="/users/view/' + object.user_id + '">' + object.creator + '</a> ' + self.getGenderTxt('предложил', object.user.gender) + ' решение для питча <a href="/pitches/view/' + object.pitch_id + '">' + object.pitch.title + '</a>: \
                            </div> \
                            <a href="/pitches/viewsolution/' + object.solution.id + '"><div class="sol"><img src="' + imageurl + '"></div></a>\
                            <div class="box-info">\
                                <a href="/solutions/warn/' + object.solution.id + '.json" class="warning-box" data-solution-id="' + object.solution.id + '">Пожаловаться</a>\
                                <a data-id="' + object.solution.id + '" class="like-small-icon-box" data-vote="' + object.allowLike + '" data-likes="' + object.solution.likes + '" href="#">' + like_txt + '</a>\
                            </div>\
                            <div data-id="' + object.solution.id + '" class="likes">';
                        id = object.solution.id;
                        $.each(response.updates, function (index, object) {
                            if (object.type == 'LikeAdded' && object.solution_id == id) {
                                avatar = (typeof object.user.images['avatar_small'] != 'undefined') ? object.user.images['avatar_small'].weburl : '/img/default_small_avatar.png';
                                html += '<div> \
                                    <div class="l-img"> \
                                        <a target="_blank" href="/users/view/' + object.user_id + '"><img class="avatar" src="' + avatar + '"></a> \
                                    </div> \
                                    <span><a href="/users/view/' + object.user_id + '">' + object.creator + '</a> ' + self.getGenderTxt('лайкнул', object.user.gender) + ' ваше решение</span> \
                                </div>';
                            }
                        });
                        html += '</div></div>';
                    }

                    if (object.type == 'newsAdded' && object.news != null) {
                        html += '<div class="box"> \
                                <p class="img-box"> \
                                    <a class="post-link" href="' + object.news.link + '"><img class="img-post" src="' + object.news.imageurl + '"></a> \
                                </p> \
                                <div class="r-content post-content"> \
                                    <p class="img-tag">' + object.news.tags + '</p> \
                                    <a class="img-post" href="' + object.news.link + '"><h2>' + object.news.title + '</h2></a> \
                                    <p class="img-short">' + object.news.short + '</p> \
                                    <p class="timeago"> \
                                        <time class="timeago" datetime="' + object.news.created + '">' + object.news.created + '</time> с сайта ' + object.host + '</p> \
                                </div> \
                            </div>';
                    }

                    if (object.type == 'CommentAdded' && object.comment != null) {
                        if (object.user.isAdmin == 1) {
                            avatar = '/img/icon_57.png';
                        } else {
                            avatar = (typeof object.user.images['avatar_small'] != 'undefined') ? object.user.images['avatar_small'].weburl : '/img/default_small_avatar.png';
                        }
                        var like_txt = object.allowLike ? 'Нравится' : 'Не нравится';
                        html += '<div class="box">\
                                    <div class="sol"><img src="' + imageurl + '"></div> \
                                    <div class="box-info">\
                                        <a href="/solutions/warn/' + object.solution.id + '.json" class="warning-box" data-solution-id="' + object.solution.id + '">Пожаловаться</a>\
                                        <a data-id="' + object.solution.id + '" class="like-small-icon-box" data-vote="' + object.allowLike + '" data-likes="' + object.solution.likes + '" href="#">' + like_txt + '</a>\
                                    </div>\
                                    <div class="l-img l-img-box"> \
                                        <a target="_blank" href="/users/view/' + object.user_id + '"><img class="avatar" src="' + avatar + '"></a> \
                                    </div> \
                                    <div class="r-content box-comment">';
                        if (this_user == object.pitch.user_id || (object.comment.public == 1 && object.comment.reply_to != 0)) {
                            html += '<a href="/users/view/' + object.user_id + '">' + object.creator + '</a> ' + self.getGenderTxt('прокомментировал', object.user.gender) + ' <a href="/pitches/viewsolution/' + object.solution.id + '">решение #' + object.solution.num + '</a> для питча <a href="/pitches/view/' + object.pitch_id + '">' + object.pitch.title + '</a>:<br /> &laquo;' + object.updateText + '&raquo;</div>';
                        }
                        else {
                            html += '<a href="/users/view/' + object.user_id + '">' + object.creator + '</a> ' + self.getGenderTxt('оставил', object.user.gender) + ' комментарий в питче <a href="/pitches/view/' + object.pitch_id + '">' + object.pitch.title + '</a>:<br /> &laquo;' + object.updateText + '&raquo;</div>';
                        }
                        html += '</div>';
                    }
                });
                var $appendEl = $(html);
                $appendEl.hide();
                $('time.timeago').timeago();
                $appendEl.appendTo('#updates-box-').slideDown('slow');
            }
            if (response.nextUpdates < 1) {
                isBusy = 1;
            } else {
                isBusy = 0;
            }
        });
    },
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
    this.getGenderTxt = function (txt, gender) {
        if (gender == 1) {
            txt += 'а';
        }
        return txt;
    }
    this.getSolutions = function () {
        self.solPage += 1;
        $('#SolutionAjaxLoader').show();
        $.get('/events/getsol.json', {"init": true, "page": self.solPage}, function (response) {
            $('#SolutionAjaxLoader').hide();
            if (typeof (response.solpages) != "undefined" && response.solpages != null) {
                var solutions = '';
                $.each(response.solpages, function (index, solution) {
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
                                    <a href="/pitches/viewsolution/' + solution.solution.id + '"><div class="left-sol" style="background: url(' + imageurl + ')"></div></a> \
                                    <div class="solution-info"> \
                                        <p class="creator-name"><a target="_blank" href="/users/view/' + solution.user_id + '">' + solution.creator + '</a></p> \
                                        <p class="ratingcont" data-default="' + solution.solution.rating + '" data-solutionid="' + solution.solution.id + '" style="height: 9px; background: url(/img/' + solution.solution.rating + '-rating.png) no-repeat scroll 0% 0% transparent;display:inline-block;width: 56px;"></p> \
                                        <a data-id="' + solution.solution.id + '" class="like-small-icon" href="#"><span>' + solution.solution.likes + '</span></a>\
                                        <div class="sharebar" style="padding:0 0 4px !important;background:url(/img/tooltip-bg-bootom-stripe.png) no-repeat scroll 0 100% transparent !important;position:absolute;z-index:10000;display: none; left: 121px; top: 27px;height: 178px;width:288px;">\
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
                                        <span class="bottom_arrow">\
                                            <a href="#" class="solution-menu-toggle"><img src="/img/marker5_2.png" alt=""></a>\
                                        </span>\
                                        <div class="solution_menu" style="display: none;">\
                                            <ul class="solution_menu_list" style="position:absolute;z-index:6;">';
                        if (solution.pitch.user_id == this_user && solution.pitchesCount < 1 && !solution.selectedSolutions) {
                            solutions += '<li class="sol_hov select-winner-li" style="margin:0;width:152px;height:20px;padding:0;">\
                                                        <a class="select-winner" href="/solutions/select/' + solution.solution.id + '.json" data-solutionid="' + solution.solution.id + '" data-user="' + solution.creator + '" data-num="' + solution.solution.num + '" data-userid="' + solution.solution.user_id + '">Назначить победителем</a>\
                                                    </li>';
                        } else if (solution.pitch.user_id == this_user && (solution.solution.pitch.awarded != solution.solution.id) && ((solution.solution.pitch.status == 1) || (solution.solution.pitch.status == 2)) && solution.solution.pitch.awarded != 0) {
                            solutions += '<li class="sol_hov select-winner-li" style="margin:0;width:152px;height:20px;padding:0;">\
                                                        <a class="select-multiwinner" href="/pitches/setnewwinner/' + solution.solution.id + '" data-solutionid="' + solution.solution.id + '" data-user="' + solution.creator + '" data-num="' + solution.solution.num + '" data-userid="' + solution.solution.user_id + '">Назначить ' + solution.pitchesCount + 2 + ' победителя</a>\
                                                    </li>';
                        }
                        if (solution.pitch.user_id == this_user && isAllowedToComment) {
                            solutions += '<li class="sol_hov" style="margin:0;width:152px;height:20px;padding:0;"><a href="#" class="solution-link-menu" data-id="' + solution.solution.id + '" data-comment-to="#' + solution.solution.num + '">Комментировать</a></li>';
                        }
                        solutions += '<li class="sol_hov" style="margin:0;width:152px;height:20px;padding:0;"><a href="/solutions/warn/' + solution.solution.id + '.json" class="warning" data-solution-id="' + solution.solution.id + '">Пожаловаться</a></li>';
                        if (isAdmin) {
                            solutions += '<li class="sol_hov" style="margin:0;width:152px;height:20px;padding:0;"><a class="delete-solution" data-solution="' + solution.solution.id + '" data-solution_num="' + solution.solution.num + '" href="/solutions/delete/' + solution.solution.id + '.json">Удалить</a></li>';
                        }
                        solutions += '</ul>\
                                        </div>\
                                    </div> \
                                </div>';
                    }
                });
                if (solutions != '') {
                    var $prependEl = $(solutions);
                    $prependEl.hide();
                    $prependEl.appendTo('#l-sidebar-office').slideDown('slow');
                    $('#SolutionAjaxLoader').appendTo($('#l-sidebar-office'));
                    isBusySolution = 0;
                }
            }
        })
    };
}
function parse_url_regex(url) {
    var parse_url = /^(?:([A-Za-z]+):)?(\/{0,3})([0-9.\-A-Za-z]+)(?::(\d+))?(?:\/([^?#]*))?(?:\?([^#]*))?(?:#(.*))?$/;
    var result = parse_url.exec(url);
    return result;
}