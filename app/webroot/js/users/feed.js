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
var compensation = 0;

function imageLoadError(image) {
    var imagebox = $(image).parent().parent();
    var box = imagebox.parent();
    imagebox.remove();
    box.css('margin-top', '34px');
    return true;
}

var originalTexts = {};
var originalTitles = {};
var translatedTexts = {};
var translatedTitles = {};

var idOfNewsToTranslate = null;

function mycallback(text, title) {
    var box = $('.box[data-newsid="' + idOfNewsToTranslate + '"]');
    $('.img-short', box).text(text);
    $('h2', box).text(title);
    $('.translate', box).text('Показать оригинальный текст');
    $('.translate', box).data('translated', true);
}

$(document).ready(function () {
    $('.social-likes').socialLikes();

    $(".sharebar").hover(
        function() {
        }, function() {
            var sharebar = $( this );
            setTimeout(function(){
                sharebar.fadeOut(300);
            }, 500);
        }
    );

    $('input[name="gender"]').on('change', function () {
        $.post('http://www.godesigner.ru/users/gender/' + $('#user_id').val() + '.json', {gender: $(this).attr('id')}, function (response) {
        });
        setTimeout(
                function () {
                    $('#gender-box').animate({'height': 'hide'}, function () {
                        $('.new-content').removeAttr('style');
                        $('.new-middle_inner').css({'margin-top': '70px'});
                    });
                }, 1000
                );
    });
    $('.close-gender').on('click', function () {
        $(this).parent().parent().hide();
        var bannerid = $(this).data('bannerid');
        if (!isAdmin) {
            $('.new-content').css({'margin-top': '70px'});
            if (typeof bannerid !== typeof undefined && bannerid !== false) {
                var now = new Date();
                var time = now.getTime();
                time += 3600 * 1000 * 24 * 30 * 6;
                now.setTime(time);
                document.cookie = 'closedbanner' + bannerid + '=1; expires=' + now.toUTCString() + '; path=/';
            }
        }else {
            $.post('http://www.godesigner.ru/news/hide/' + bannerid + '.json', function(response) {
            })
        }
    });
    $('.img-box').hover(function () {
        $(this).next().children('.img-post').children('h2').css('color', '#ff585d');
    }, function () {
        $(this).next().children('.img-post').children('h2').css('color', '');
        ;
    });
    var Tip = new TopTip;
    isBusy = 0, isBusySolution = 0, isBusyDesNews = 0;
    function scrollInit() {
        var header_bg = $('#header-bg'),
                pitch_panel = $('#pitch-panel'),
                header_height = header_bg.height(),
                header_pos = header_bg.position().top,
                windowHeight = $(window).height(),
                $box = $('#container-design-news'),
                $parent = $('.new-content'),
                leftSidebar = $('#l-sidebar-office'),
                leftSidebarTop = leftSidebar.offset().top;
        var top = $box.offset().top;
        if (!pitch_panel.length) {
            $('<div id="pitch-panel"></div>').insertBefore(header_bg);
            pitch_panel = $('#pitch-panel');
        }

        $(window).on('scroll', function () {
            var parentAbsoluteTop = $parent.offset().top;
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
            if ((($('#center_sidebar').height() - 200) - $(window).scrollTop() < 1500) && !isBusy) {
                isBusy = 1;
                Tip.scrollHandler();
                $box.css({
                    position: 'fixed',
                    top: '35px',
                    bottom: '0px'
                });
                Updater.nextPage();
            }
            if (((leftSidebar.height() - 200) - $(window).scrollTop() < 1500) && !isBusySolution) {
                isBusySolution = 1;
                Updater.getSolutions();
            }

        });
    }

    $('#content-news').on('scroll', function () {
        if ((($(this)[0].scrollHeight - $(this).scrollTop()) < 700) && !isBusyDesNews) {
            isBusyDesNews = 1;
            Updater.nextNews(true);
        }
    });
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

    var Updater = new OfficeStatusUpdater();
    Tip.init();
    scrollInit();
    Updater.init();


    $("#content-news")
            .mouseenter(function () {
                $(this).css('overflow-y', 'scroll');
            })
            .mouseleave(function () {
                $(this).css('overflow', 'hidden');
            });
    var clickedLikesList = []

    $(document).on('click', '.hide-news', function() {
        var newsId = $(this).data('id');
        $.get('http://www.godesigner.ru/news/hide/' + $(this).data('id') + '.json', function (response) {
            $('.box[data-newsid=' + newsId + ']').hide();
        });
        return false;
    })

    $('.social-likes__button').on('click', function() {
        $(this).closest('.sharebar').fadeOut(300);
    })

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
        sharebar.fadeIn(300, function() {
            $('body').one('click', function () {
                sharebar.fadeOut(300);
            });
        });

        if (($.inArray($(this).data('id'), clickedLikesList) == -1) && (this_user != '')) {
            likesNum.html(parseInt(likesNum.html()) + 1);
            clickedLikesList.push($(this).data('id'));
        }
        if($('.like-small-icon-box[data-id=' + solutionId + ']').length > 0) {
            $('.like-small-icon-box[data-id=' + solutionId + ']').click();
        }else {
            $.get('http://www.godesigner.ru/solutions/like/' + $(this).data('id') + '.json', function (response) {
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
        }
        return false;
    });



    $(document).on('click', '.translate', function() {
        if($(this).data('translated') != true) {
            var box = $(this).closest('.box');
            var id = box.data('newsid');
            var text = $('.img-short', box).text()

            /*if(typeof(translatedTexts[id]) == 'undefined') {
                var s = document.createElement("script");
                var from = 'en';
                var to = 'ru';
                originalTexts[id] = text;
                idOfNewsToTranslate = id;
                s.src = "http://api.microsofttranslator.com/V2/Ajax.svc/Translate" +
                "?appId=Bearer " + encodeURIComponent(accessToken) +
                "&from=" + encodeURIComponent(from) +
                "&to=" + encodeURIComponent(to) +
                "&text=" + encodeURIComponent(text) +
                "&oncomplete=mycallback";
                document.body.appendChild(s);
            }else {
                mycallback(translatedTexts[id]);
            }*/
            mycallback(translatedTexts[id], translatedTitles[id]);
        }else {
            var box = $(this).closest('.box');
            var id = box.data('newsid');
            idOfNewsToTranslate = id
            var title = $('h2', box).text()
            var text = $('.img-short', box).text();
            translatedTexts[id] = text;
            translatedTitles[id] = title;
            $('h2', box).text($(this).data('original-title'));
            $('.img-short', box).text($(this).data('original-short'));
            $('.translate', box).text('Перевести');
            $('.translate', box).data('translated', false);
        }
        return false;
    })


    $(document).on('click', '.share-news-center', function() {
        var sharebar = $(this).siblings('.sharebar');
        sharebar.fadeIn(300);
        $('body').one('click', function () {
            sharebar.fadeOut(300);
        });
        return false;
    })

    $(document).on('click', '.like-small-icon-box', function () {
        var link = $(this),
                link_parent = link.parent(),
                span = link.parent().closest('.box').find('.likes').children('span'),
                txt = span.children('span').text(),
                like_span = span.children('span'),
                url = span.children('a'),
                url_backup = url.text(),
                txt_backup = txt,
                first_txt = txt.split(' ')[0],
                style = span.parent().css('display');
        var type_like = ($(this).data('news')) ? 'news' : 'solutions';
        var my_solution = ($(this).data('userid') == this_user) ? 'ваше' : '';
        if (type_like == 'news') {
            var word = 'новость'
            my_solution = ''
        } else {
            var word = 'решение'
        }
        if (link.data('vote') == '1') {
            link.html('Не нравится');
            link.data('vote', '0');
            if ((first_txt == 'лайкнул' || first_txt == 'лайкнула') && style == 'block') {
                url.last().text(url.last().text() + ', ');
                var $element = $('<a href="/users/view/' + this_user + '" data-added="1">' + userName + '</a>');
                $element.insertAfter(url.last());
                like_span.text('лайкнули ' + my_solution + ' ' + word);
            } else if (first_txt == 'лайкнули' && style == 'block') {
                if(url.filter('.show-other-likes').length > 0) {
                    var filteredLast = url.filter(':not(.show-other-likes)').last();
                    var wholikes = $('.who-likes', likes_div);
                    var numberBlock = $('.show-other-likes', wholikes);
                    var text = numberBlock.text();
                    var count = parseInt(text.match(/\d*/g));
                    var newText = (count + 1) + ' других';
                    numberBlock.text(newText);
                }else {
                    url.last().text(url.last().text() + ', ');
                    var $element = $('<a href="/users/view/' + this_user + '" data-added="1">' + userName + '</a>');
                    $element.insertAfter(url.last());
                    like_span.text('лайкнули ' + my_solution + ' ' + word);
                }
            } else if (!span.length) {
                var $element = $('<div class="likes"><span class="who-likes"><a class="show-other-likes" data-block="1" data-solid="' + $(this).data('id') + '" href="#">' + userName + '</a> <span>' + Updater.getGenderTxt('лайкнул', userGender) + ' ' + my_solution + ' ' + word + '</span></span></div>');
                $element.insertAfter(link_parent.next());
            } else if (span.length > 0 && style == 'none') {
                span.parent().show();
            }
            $.get('http://www.godesigner.ru/' + type_like + '/like/' + $(this).data('id') + '.json', function (response) {
                if (response.result == false) {
                    link.html('Нравится');
                    link.data('vote', '1');
                    url.text(url_backup);
                    like_span.text(txt_backup);
                }
            });
        } else {
            link.html('Нравится');
            link.data('vote', '1');
            var likes_div = '';
            if ((first_txt == 'лайкнул') || (first_txt == 'лайкнула')) {
                likes_div = link_parent.closest('.box').find('.likes');
                likes_div.hide();
            } else if (first_txt == 'лайкнули' && (url.filter('[data-id=' + this_user + ']').length > 0 || url.filter('[data-added=1]').length > 0)) {
                url.filter('[data-added=1]').remove();
                url.filter('[data-id=' + this_user + ']').remove();
                url = span.children('a');
                var filteredLast = url.filter(':not(.show-other-likes)').last();
                filteredLast.text(filteredLast.text().replace(/,\s/, ''));
                if (url.length <= 2) {
                    like_span.text(Updater.getGenderTxt('лайкнул', userGender) + ' ' + my_solution + ' ' + word);
                }
            } else if (url.filter('.show-other-likes').length > 1) {
                likes_div = link_parent.closest('.box').find('.likes');
                likes_div.hide();
            } else {
                // Имя пользователя скрыто в числе "ХХ других"
                var wholikes = $('.who-likes', likes_div);
                var numberBlock = $('.show-other-likes', wholikes);
                var text = numberBlock.text();
                var count = parseInt(text.match(/\d*/g));
                var newText = (count - 1) + ' других';
                numberBlock.text(newText);
            }
            $.get('http://www.godesigner.ru/' + type_like + '/unlike/' + $(this).data('id') + '.json', function (response) {
                if (response.result == false) {
                    link.html('Не нравится');
                    link.data('vote', '0');
                    url.text(url_backup);
                    like_span.text(txt_backup);
                    if (likes_div.length > 0) {
                        likes_div.show();
                    }
                } else {
                    if (likes_div.length > 0) {
                        likes_div.hide();
                    }
                }
            });
        }
        return false;
    });
    // Solution Stars
    $(document).on('mouseenter', '.ratingchange', function () {
        $(this).parent().css('background', 'url(http://www.godesigner.ru/img/' + $(this).data('rating') + '-rating.png) no-repeat scroll 0% 0% transparent');
    });
    $(document).on('mouseleave', '.ratingcont', function () {
        $(this).css('background', 'url(http://www.godesigner.ru/img/' + $(this).data('default') + '-rating.png) no-repeat scroll 0% 0% transparent');
    });
    $(document).on('click', '.ratingchange', function () {
        var id = $(this).parent().data('solutionid');
        var rating = $(this).data('rating');
        var self = $(this);
        $.post('http://www.godesigner.ru/solutions/rating/' + id + '.json',
                {"id": id, "rating": rating}, function (response) {
            self.parent().data('default', rating);
            self.parent().css('background', 'url(http://www.godesigner.ru/img/' + rating + '-rating.png) repeat scroll 0% 0% transparent');
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
        $('#winner-num-multi').attr('href', 'http://www.godesigner.ru/pitches/viewsolution/' + $(this).data('solutionid'));
        $('#winner-user-link-multi').text($(this).data('user'));
        $('#winner-user-link-multi').attr('href', 'http://www.godesigner.ru/users/view/' + $(this).data('userid'));
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
        $('#winner-num').attr('href', 'http://www.godesigner.ru/pitches/viewsolution/' + $(this).data('solutionid'));
        $('#winner-user-link').text($(this).data('user'));
        $('#winner-user-link').attr('href', 'http://www.godesigner.ru/users/view/' + $(this).data('userid'));
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
            containerId: 'final-step-multi',
            opacity: 80,
            closeClass: 'popup-close'
        });
        return false;
    });
    $(document).on('click', '.fav-user', function (e) {
        var link = $(this);
        link.text('Отписаться');
        link.addClass('unfav-user').removeClass('fav-user');
        $.get('http://www.godesigner.ru/favourites/adduser/' + $(this).data('id') + '.json', function (response) {
            if (response.result == false) {
                link.text('Подписаться');
                link.addClass('fav-user').removeClass('unfav-user');
            }
        });
        return false;
    });
    $(document).on('click', '.unfav-user', function (e) {
        var link = $(this);
        link.text('Подписаться');
        link.addClass('fav-user').removeClass('unfav-user');
        $.get('http://www.godesigner.ru/favourites/adduser/' + $(this).data('id') + '.json', function (response) {
            link.text('Отписаться');
            link.addClass('unfav-user').removeClass('fav-user');
        });
        return false;
    });
    $(document).on('click', '.show-other-likes', function (e) {
        $('#who-its-liked').empty();
        $('#likedAjaxLoader').show();
        $.get('http://www.godesigner.ru/events/liked/' + $(this).data('solid') + '.json', function (response) {
            var html = '';
            $.each(response.likes, function (index, object) {
                var avatar = (typeof object.user.images['avatar_small'] != 'undefined') ? object.user.images['avatar_small'].weburl : 'http://www.godesigner.ru/img/default_small_avatar.png',
                        liker_id = object.user_id,
                        trigger = false,
                        txt_button = 'Подписаться',
                        str_class = 'fav-user';
                $.each(response.fav, function (index, obj) {
                    if (liker_id == obj.fav_user_id) {
                        trigger = true;
                        str_class = 'unfav-user';
                        txt_button = 'Отписаться';
                        return false;
                    }
                });
                html += '<li>\
                            <img src="' + avatar + '" class="avatar">\
                            <a class="user-title" href="http://www.godesigner.ru/users/view/' + object.user_id + '">' + object.creator + '</a>\
                            <a class="' + str_class + ' favour-user order-button" data-id="' + object.user_id + '" href="#">' + txt_button + '</a>\
                        </li>';
            });
            $('#likedAjaxLoader').hide();
            var $appendEl = $(html);
            $appendEl.hide();
            $appendEl.appendTo('#who-its-liked').slideDown('fast');
        });
        $('#popup-other-likes').modal({
            containerId: 'other-likes',
            opacity: 80,
            closeClass: 'popup-close',
            onShow: function() {
                $('#other-likes').css('top', '100px');
                return true;
            }
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
        $('img', $(this)).attr('src', 'http://www.godesigner.ru/img/marker5_2_hover.png');
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
        $('img', $(this)).attr('src', 'http://www.godesigner.ru/img/marker5_2.png');
    });
    $(document).on('click', '.solution-menu-toggle', function () {
        return false;
    });
    // Delete Solution
    $(document).on('click', '.delete-solution', function (e) {
        e.preventDefault();
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


    if ((isAdmin) || (isFeedWriter)) {
        var fd = new FormData();
        var reader = new FileReader();
        var form_file = false;
        $(document).bind('keydown', function (e) {
            if (e.keyCode == 90 && e.shiftKey) {
                $('#news-add').toggle('fast');
                $('.logo').css('width', '187px');
                $('#news-add-separator').toggle('fast');
            }
        });
        $(document).on('change', '#news-file', function (e) {
            var files = e.target.files;
            $('#previewImage').empty();
            for (var i = 0, file; file = files[i]; i++) {
                if (file.type.match('image.*')) {
                    fd.append('file', file);
                    form_file = file;
                    reader.onload = function (e) {
                        var img = e.target.result;
                        $('#previewImage').append('<div class="imageContatiner"><img src="' + img + '" width="100" height="100"><div class="remove-image"></div></div>');
                    };
                    reader.readAsDataURL(file);
                }
            }
        });
        $(document).on('click', '.remove-image', function (e) {
            $(this).parent().remove();
            form_file = false;
        });
        $('#submit-news').on('click', function () {
            var button = $(this),
                    news_title = $('#news-add input[name="news-title"]'),
                    news_txt = $('#news-add textarea[name="news-description"]'),
                    news_link = $('#news-add input[name="news-link"]'),
                    news_tag = $('#news-add #news-add-tag');
            fd.append('title', news_title.hasClass('placeholder') ? '' : news_title.val());
            fd.append('link', news_link.hasClass('placeholder') ? '' : news_link.val());
            fd.append('short', news_txt.hasClass('placeholder') ? '' : news_txt.val());
            fd.append('tags', news_tag.hasClass('placeholder') ? '' : news_tag.val());
            fd.append('isBanner', $('#isBanner').is(':checked') ? 1 : 0);
            if (form_file) {
                fd.append('file', form_file);
            }
            button.text('Обработка');
            $.ajax({
                url: 'http://www.godesigner.ru/events/add.json',
                type: 'post',
                data: fd,
                contentType: false,
                processData: false,
                success: function (result) {
                    if (result.result == true) {
                        button.text('Сохранено!');
                        $('#news-add input[name="news-title"]').val('');
                        $('#news-add input[name="news-link"]').val('');
                        $('#news-add textarea[name="news-description"]').val('');
                        $('#news-add #news-add-tag').val('');
                        Updater.autoupdate();
                        fd = new FormData();
                        $('#previewImage').empty();
                    } else if (result.result == false) {
                        button.text('Ошибка');
                    } else if (typeof(result.result.news) != 'undefined') {
                        button.text('Сохранено!');
                        $('#news-add input[name="news-title"]').val('');
                        $('#news-add input[name="news-link"]').val('');
                        $('#news-add textarea[name="news-description"]').val('');
                        $('#news-add #news-add-tag').val('');
                        fd = new FormData();
                        $('#previewImage').empty();
                        html = Updater.addNews(result.result);
                        var $prependEl = $(html);
                        $prependEl.css('margin-top', '0');
                        $prependEl.hide();
                        $prependEl.prependTo('#updates-box-').slideDown('slow');
                        $('.social-likes').socialLikes();
                        $('time.timeago').timeago();
                    }
                    $('#news-add').toggle('fast');
                    $('#news-add-separator').toggle('fast');
                }
            });
            return false;
        });
        $('#show-all-fileds').on('click', function () {
            var label = $(this);
            if (label.text() == 'Свернуть') {
                $('.tt-hint').hide();
                $('#news-add input[name="news-title"]').hide();
                label.text('Показать все поля');
                label.removeClass('hide');
                $('#news-add-tag').hide();
            } else {
                $('.tt-hint').show();
                $('#news-add input[name="news-title"]').show();
                $('#news-add-tag').show();
                label.text('Свернуть');
                label.addClass('hide');
            }
        });
        var tags = new Bloodhound({
            datumTokenizer: Bloodhound.tokenizers.obj.whitespace('name'),
            queryTokenizer: Bloodhound.tokenizers.whitespace,
            remote: 'http://www.godesigner.ru/events/newstags.json?name=%QUERY'
        });
        tags.initialize();
        $('#news-add-tag').typeahead(null, {
            name: 'tags',
            displayKey: 'tags',
            source: tags.ttAdapter()
        });
    }
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
    this.jobPage = 1;
    this.pitchPage = 1;
    this.newsPage = 1;
    this.date = '';
    this.dateTwitter;
    this.newsDate;
    this.solutionDate;
    this.pitchDate;
    this.started = 0;
    // initialisation method
    this.init = function () {
        $('time.timeago').timeago();
        self.newsDate = newsDate;
        self.dateTwitter = $('#twitterDate').data('date');
        self.solutionDate = solutionDate;
        self.date = eventsDate;
        self.likesdate = likesDate;
        //self.pitchDate = pitchDate;
        $(document).everyTime(60000, function (i) {
            if (self.started) {
                self.autoupdate();
            }
        });
        $(document).everyTime(120000, function (i) {
            if (self.started) {
                self.autolikes();
            }
        });
        self.started = 1;
    },
            this.autolikes = function() {
                $.get('http://www.godesigner.ru/events/autolikes.json', {"created": self.likesdate}, function (response) {
                    self.likesdate = response.newLatestDate;
                    $.each(response.events, function(index, object) {
                        if(this_user == object.user.id) {
                            return false;
                        }
                        if($('.box[data-newsid="' + object.news_id + '"]').length == 1) {
                            var box = $('.box[data-newsid="' + object.news_id + '"]');
                            var link = $('.like-small-icon-box', box),
                                link_parent = link.parent(),
                                span = link.parent().closest('.box').find('.likes').children('span'),
                                txt = span.children('span').text(),
                                like_span = span.children('span'),
                                url = span.children('a'),
                                url_backup = url.text(),
                                txt_backup = txt,
                                first_txt = txt.split(' ')[0],
                                style = span.parent().css('display');
                            var type_like = 'news';
                            if (type_like == 'news') {
                                var word = 'новость'
                                my_solution = ''
                            } else {
                                var word = 'решение'
                            }
                            var likes_div = '';
                            if ((first_txt == 'лайкнул' || first_txt == 'лайкнула') && style == 'block') {
                                url.last().text(url.last().text() + ', ');
                                var $element = $('<a href="/users/view/' + object.user.id + '" data-added="1">' + object.creator + '</a>');
                                $element.insertAfter(url.last());
                                //url.text(userName + ', ' + url.text());
                                //url.data('added', 1);
                                like_span.text('лайкнули ' + my_solution + ' ' + word);
                            } else if (first_txt == 'лайкнули' && style == 'block') {
                                if(url.filter('.show-other-likes').length > 0) {
                                    var filteredLast = url.filter(':not(.show-other-likes)').last();
                                    var wholikes = $('.who-likes', likes_div);
                                    var numberBlock = $('.show-other-likes', wholikes);
                                    var text = numberBlock.text();
                                    var count = parseInt(text.match(/\d*/g));
                                    var newText = (count + 1) + ' других';
                                    numberBlock.text(newText);
                                }else {
                                    url.last().text(url.last().text() + ', ');
                                    var $element = $('<a href="/users/view/' + object.user.id + '" data-added="1">' + object.creator + '</a>');
                                    $element.insertAfter(url.last());
                                    like_span.text('лайкнули ' + my_solution + ' ' + word);
                                }
                            } else if (!span.length) {
                                var $element = $('<div class="likes"><span class="who-likes"><a class="show-other-likes" data-block="1" data-solid="' + object.news_id + '" href="#">' + object.creator + '</a> <span>' + self.getGenderTxt('лайкнул', object.user.gender) + ' ' + my_solution + ' ' + word + '</span></span></div>');
                                $element.insertAfter(link_parent.next());
                            } else if (span.length > 0 && style == 'none') {
                                span.parent().show();
                            }

                        }
                    })
                })
            },
            this.autoupdate = function () {
                $.get('http://www.godesigner.ru/events/feed.json', {"init": true, "created": self.date, "twitterDate": self.dateTwitter, "newsDate": self.newsDate, "solutionDate": self.solutionDate}, function (response) {
                    if (typeof (response.news) != "undefined" && response.news != null) {
                        var news = '', first_el = 0;
                        $.each(response.news, function (index, object) {
                            if (first_el == 0) {
                                self.newsDate = object.created;
                            }
                            first_el++;
                            host = parse_url_regex(object.link);
                            news += '<div class="design-news"><a target="_blank" href="http://www.godesigner.ru/users/click?link=' + object.link + '&id=' + object.id + '">' + object.title + ' <br><a class="clicks" href="http://www.godesigner.ru/users/click?link=' + object.link + '&id=' + object.id + '">' + host[3] + '</a></div>';
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
                    var html = '', solutions = '';
                    if (typeof (response.post) != "undefined" && response.post != 0) {
                        if ($('.box[data-eventid="' + response.id + '"]').length == 0) {
                            var img = (response.post.imageurl.indexOf('/', 0) == 0) ? 'http://www.godesigner.ru' : response.post.imageurl;
                            var $prependEl = $('<div class="box" data-eventid="' + response.id + '"> \
                                <p class="img-box"> \
                                    <a class="post-link" href="http://www.godesigner.ru/users/click?link=' + response.post.link + '&id=' + response.post.id + '" target="_blank"><img class="img-post" src="' + img + '"></a> \
                                </p> \
                                <div class="r-content post-content"> \
                                    <p class="img-tag">' + response.post.tags + '</p> \
                                    <a class="img-post" href="' + response.post.link + '" target="_blank"><h2>' + response.post.title + '</h2></a> \
                                    <p class="img-short">' + response.post.short + '</p> \
                                    <p class="timeago"> \
                                        <time class="timeago" datetime="' + response.post.created + '">' + response.post.created + '</time> с сайта ' + response.post.host + '</p> \
                                </div> \
                            </div>');
                            $prependEl.hide();
                            $prependEl.prependTo('#updates-box-').slideDown('slow');
                            $('time.timeago').timeago();
                        }
                    }
                    if (typeof (response.pitches) != "undefined") {
                        var pitches = '', pitchesCount = 0;
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
                        var solutions = '', solcount = 0;
                        $.each(response.solutions, function (index, solution) {
                            if (solcount == 0)
                                self.solutionDate = solution.created;
                            solcount++;
                            if ((typeof (solution.solution.images.solution_leftFeed) != "undefined") && (this_user != '')) {
                                if (typeof (solution.solution.images.solution_leftFeed.length) == "undefined") {
                                    var imageurl = solution.solution.images.solution_leftFeed.weburl;
                                } else {
                                    var imageurl = solution.solution.images.solution_leftFeed[0].weburl;
                                }
                                if (Math.floor((Math.random() * 100) + 1) <= 50) {
                                    var tweetLike = 'Мне нравится этот дизайн! А вам?';
                                } else {
                                    var tweetLike = 'Из всех ' + solution.pitch.ideas_count + ' мне нравится этот дизайн';
                                }
                                solutions += '<div class="solutions-block"> \
                                    <a href="http://www.godesigner.ru/pitches/viewsolution/' + solution.solution.id + '"><div class="left-sol" style="background: url(http://www.godesigner.ru' + imageurl + ')"></div></a> \
                                    <div class="solution-info"> \
                                        <p class="creator-name"><a target="_blank" href="http://www.godesigner.ru/users/view/' + solution.user_id + '">' + solution.creator + '</a></p> \
                                        <p class="ratingcont" data-default="' + solution.solution.rating + '" data-solutionid="' + solution.solution.id + '" style="height: 9px; background: url(http://www.godesigner.ru/img/' + solution.solution.rating + '-rating.png) no-repeat scroll 0% 0% transparent;display:inline-block;width: 56px;"></p> \
                                        <a data-id="' + solution.solution.id + '" class="like-small-icon" href="#"><span>' + solution.solution.likes + '</span></a>';
                                var shareTitle = tweetLike;
                                var url = 'http://www.godesigner.ru/pitches/viewsolution/' + solution.solution.id
                                solutions += '<div class="sharebar" style="position: absolute; display: none; top: 30px; left: 120px;"> \
                                    <div class="tooltip-block"> \
                                    <div class="social-likes" data-counters="no" data-url="' + url + '" data-title="' + shareTitle + '"> \
                                    <div class="facebook" style="display: inline-block;" title="Поделиться ссылкой на Фейсбуке" data-url="' + url + '">SHARE</div> \
                                    <div class="twitter" style="display: inline-block;" data-via="Go_Deer">TWITT</div> \
                                    <div class="vkontakte" style="display: inline-block;" title="Поделиться ссылкой во Вконтакте" data-image="' + imageurl + '" data-url="' + url + '">SHARE</div> \
                                    <div class="pinterest" style="display: inline-block;" title="Поделиться картинкой на Пинтересте" data-url="' + url + '" data-media="' + imageurl + '">PIN</div></div></div></div>';
                                solutions += '<span class="bottom_arrow">\
                                            <a href="#" class="solution-menu-toggle"><img src="http://www.godesigner.ru/img/marker5_2.png" alt=""></a>\
                                        </span>\
                                        <div class="solution_menu" style="display: none;">\
                                            <ul class="solution_menu_list" style="position:absolute;z-index:6;">';
                                if (solution.pitch.user_id == this_user && solution.pitchesCount < 1 && !solution.selectedSolutions) {
                                    solutions += '<li class="sol_hov select-winner-li" style="margin:0;width:152px;height:20px;padding:0;">\
                                                        <a class="select-winner" href="http://www.godesigner.ru/solutions/select/' + solution.solution.id + '.json" data-solutionid="' + solution.solution.id + '" data-user="' + solution.creator + '" data-num="' + solution.solution.num + '" data-userid="' + solution.solution.user_id + '">Назначить победителем</a>\
                                                    </li>';
                                } else if (solution.pitch.user_id == this_user && (solution.solution.pitch.awarded != solution.solution.id) && ((solution.solution.pitch.status == 1) || (solution.solution.pitch.status == 2)) && solution.solution.pitch.awarded != 0) {
                                    solutions += '<li class="sol_hov select-winner-li" style="margin:0;width:152px;height:20px;padding:0;">\
                                                        <a class="select-multiwinner" href="http://www.godesigner.ru/pitches/setnewwinner/' + solution.solution.id + '" data-solutionid="' + solution.solution.id + '" data-user="' + solution.creator + '" data-num="' + solution.solution.num + '" data-userid="' + solution.solution.user_id + '">Назначить ' + solution.pitchesCount + 2 + ' победителя</a>\
                                                    </li>';
                                }
                                if (solution.pitch.user_id == this_user && isAllowedToComment) {
                                    solutions += '<li class="sol_hov" style="margin:0;width:152px;height:20px;padding:0;"><a href="#" class="solution-link-menu" data-id="' + solution.solution.id + '" data-comment-to="#' + solution.solution.num + '">Комментировать</a></li>';
                                }
                                solutions += '<li class="sol_hov" style="margin:0;width:152px;height:20px;padding:0;"><a href="/solutions/warn/' + solution.solution.id + '.json" class="warning" data-solution-id="' + solution.solution.id + '">Пожаловаться</a></li>';
                                if (isAdmin) {
                                    solutions += '<li class="sol_hov" style="margin:0;width:152px;height:20px;padding:0;"><a class="delete-solution" data-solution="' + solution.solution.id + '" data-solution_num="' + solution.solution.num + '" href="http://www.godesigner.ru/solutions/delete/' + solution.solution.id + '.json">Удалить</a></li>';
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
                                if ($('.box[data-eventid="' + object.id + '"]').length > 0) {
                                    return
                                }
                                if ($('div[data-eventid="' + object.id + '"]', '.center-boxes').length > 0) {
                                    return
                                }
                                if (index == 0) {
                                    self.date = object.created;
                                }
                                if (object.solution && typeof (object.solution.images.solution_solutionView) != "undefined") {
                                    if (typeof (object.solution.images.solution_solutionView.length) == "undefined") {
                                        var imageurl = object.solution.images.solution_solutionView.weburl;
                                    } else {
                                        var imageurl = object.solution.images.solution_solutionView[0].weburl;
                                    }
                                }
                                if (object.type == 'SolutionAdded' && object.solution != null && typeof object.solution.id != "undefined") {
                                    html += self.addSolution(object, imageurl, response);
                                }

                                if (object.type == 'CommentAdded' && object.comment != null) {
                                    html += self.addComment(object, imageurl);
                                }

                                if (object.type == 'RatingAdded' && object.solution != null) {
                                    html += self.addRating(object, imageurl);
                                }

                                if (object.type == 'newsAdded' && object.news != null) {
                                    html += self.addNews(object);
                                }

                                if (object.type == 'RetweetAdded' && object.html != null) {
                                    html += object.html;
                                }
                                if (object.type == 'FavUserAdded' && object.user_fav != null && object.user != null) {
                                    html += self.addFavUserAdded(object);
                                }
                                if (object.type == 'LikeAdded' && object.solution != null) {
                                    html += self.addLikes(object, imageurl);
                                }
                            });
                            var $prependEl = $(html);
                            $prependEl.hide();
                            $prependEl.prependTo('#updates-box-').slideDown('slow');
                            $('.social-likes').socialLikes();
                            $('time.timeago').timeago();
                        }
                    }
                });
            },
            this.nextPage = function () {
                self.page += 1;
                $('#officeAjaxLoader').show();
                var $formerLast = $('.box').last();
                $.get('http://www.godesigner.ru/events/feed.json', {"init": true, "page": self.page}, function (response) {
                    $('#officeAjaxLoader').hide();
                    if (response.count != 0) {
                        function sortfunction(a, b) {
                            return (a.sort - b.sort);
                        }
                        response.updates.sort(sortfunction);
                        var html = '';
                        $.each(response.updates, function (index, object) {
                            if (object.solution && typeof (object.solution.images.solution_solutionView) != "undefined") {
                                if (typeof (object.solution.images.solution_solutionView.length) == "undefined") {
                                    var imageurl = object.solution.images.solution_solutionView.weburl;
                                } else {
                                    var imageurl = object.solution.images.solution_solutionView[0].weburl;
                                }
                            }
                            if (object.type == 'SolutionAdded' && object.solution != null && typeof object.solution.id != "undefined") {
                                html += self.addSolution(object, imageurl, response);
                            }

                            if (object.type == 'newsAdded' && object.news != null) {
                                html += self.addNews(object);
                            }

                            if (object.type == 'CommentAdded' && object.comment != null) {
                                html += self.addComment(object, imageurl);
                            }

                            if (object.type == 'RatingAdded' && object.solution != null) {
                                html += self.addRating(object, imageurl);
                            }

                            if (object.type == 'RetweetAdded' && object.html != null) {
                                html += object.html;
                            }
                            if (object.type == 'FavUserAdded' && object.user_fav != null && object.user != null) {
                                html += self.addFavUserAdded(object);
                            }
                            if (object.type == 'LikeAdded' && object.solution != null) {
                                html += self.addLikes(object, imageurl);
                            }
                        });
                        var $appendEl = $(html);
                        $appendEl.hide();
                        $appendEl.appendTo('#updates-box-').slideDown('slow');
                        $('.social-likes').socialLikes();
                        $('time.timeago').timeago();
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
                var counter = 1;
                while (price.match(/\w\w\w\w/)) {
                    price = price.replace(/^(\w*)(\w\w\w)(\W.*)?$/, "$1 $2$3");
                    counter++;
                    if (counter > 6)
                        break;
                }
                return price;
            },
            this.getGenderTxt = function (txt, gender) {
                if (gender > 1) {
                    txt += 'а';
                }
                return txt;
            },
            this.isValidImage = function (url) {
                if(url == '') {
                    return false;
                }
                return true;
            },
            this.getSolutions = function () {
                self.solPage += 1;
                $('#SolutionAjaxLoader').show();
                $.get('http://www.godesigner.ru/events/getsol.json', {"init": true, "page": self.solPage}, function (response) {
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
                                    var tweetLike = 'Мне нравится этот дизайн! А вам?';
                                } else {
                                    var tweetLike = 'Из всех ' + solution.pitch.ideas_count + ' мне нравится этот дизайн';
                                }
                                var url = 'http://www.godesigner.ru/pitches/viewsolution/' + solution.solution.id
                                var shareTitle = tweetLike;
                                solutions += '<div class="solutions-block"> \
                                    <a href="http://www.godesigner.ru/pitches/viewsolution/' + solution.solution.id + '"><div class="left-sol" style="background: url(http://www.godesigner.ru' + imageurl + ')"></div></a> \
                                    <div class="solution-info"> \
                                        <p class="creator-name"><a target="_blank" href="/users/view/' + solution.user_id + '">' + solution.creator + '</a></p> \
                                        <p class="ratingcont" data-default="' + solution.solution.rating + '" data-solutionid="' + solution.solution.id + '" style="height: 9px; background: url(http://www.godesigner.ru/img/' + solution.solution.rating + '-rating.png) no-repeat scroll 0% 0% transparent;display:inline-block;width: 56px;"></p> \
                                        <a data-id="' + solution.solution.id + '" class="like-small-icon" href="#"><span>' + solution.solution.likes + '</span></a>';
                                                                solutions += '<div class="sharebar" style="position: absolute; display: none; top: 30px; left: 120px;">\
                            <div class="tooltip-block"> \
                            <div class="social-likes" data-counters="no" data-url="' + url + '" data-title="' + shareTitle + '"> \
                            <div class="facebook" style="display: inline-block;" title="Поделиться ссылкой на Фейсбуке" data-url="' + url + '">SHARE</div> \
                            <div class="twitter" style="display: inline-block;" data-via="Go_Deer">TWITT</div> \
                            <div class="vkontakte" style="display: inline-block;" title="Поделиться ссылкой во Вконтакте" data-image="' + imageurl + '" data-url="' + url + '">SHARE</div> \
                            <div class="pinterest" style="display: inline-block;" title="Поделиться картинкой на Пинтересте" data-url="' + url + '" data-media="' + imageurl + '">PIN</div></div></div></div>';
                                        solutions += '<span class="bottom_arrow">\
                                            <a href="#" class="solution-menu-toggle"><img src="http://www.godesigner.ru/img/marker5_2.png" alt=""></a>\
                                        </span>\
                                        <div class="solution_menu" style="display: none;">\
                                            <ul class="solution_menu_list" style="position:absolute;z-index:6;">';
                                if (solution.pitch.user_id == this_user && solution.pitchesCount < 1 && !solution.selectedSolutions) {
                                    solutions += '<li class="sol_hov select-winner-li" style="margin:0;width:152px;height:20px;padding:0;">\
                                                        <a class="select-winner" href="http://www.godesigner.ru/solutions/select/' + solution.solution.id + '.json" data-solutionid="' + solution.solution.id + '" data-user="' + solution.creator + '" data-num="' + solution.solution.num + '" data-userid="' + solution.solution.user_id + '">Назначить победителем</a>\
                                                    </li>';
                                } else if (solution.pitch.user_id == this_user && (solution.solution.pitch.awarded != solution.solution.id) && ((solution.solution.pitch.status == 1) || (solution.solution.pitch.status == 2)) && solution.solution.pitch.awarded != 0) {
                                    solutions += '<li class="sol_hov select-winner-li" style="margin:0;width:152px;height:20px;padding:0;">\
                                                        <a class="select-multiwinner" href="http://www.godesigner.ru/pitches/setnewwinner/' + solution.solution.id + '" data-solutionid="' + solution.solution.id + '" data-user="' + solution.creator + '" data-num="' + solution.solution.num + '" data-userid="' + solution.solution.user_id + '">Назначить ' + solution.pitchesCount + 2 + ' победителя</a>\
                                                    </li>';
                                }
                                if (solution.pitch.user_id == this_user && isAllowedToComment) {
                                    solutions += '<li class="sol_hov" style="margin:0;width:152px;height:20px;padding:0;"><a href="#" class="solution-link-menu" data-id="' + solution.solution.id + '" data-comment-to="#' + solution.solution.num + '">Комментировать</a></li>';
                                }
                                solutions += '<li class="sol_hov" style="margin:0;width:152px;height:20px;padding:0;"><a href="http://www.godesigner.ru/solutions/warn/' + solution.solution.id + '.json" class="warning" data-solution-id="' + solution.solution.id + '">Пожаловаться</a></li>';
                                if (isAdmin) {
                                    solutions += '<li class="sol_hov" style="margin:0;width:152px;height:20px;padding:0;"><a class="delete-solution" data-solution="' + solution.solution.id + '" data-solution_num="' + solution.solution.num + '" href="http://www.godesigner.ru/solutions/delete/' + solution.solution.id + '.json">Удалить</a></li>';
                                }
                                solutions += '</ul>\
                                        </div>\
                                    </div> \
                                </div>';
                            }
                        });
                        if (solutions != '') {
                            var $prependEl = $(solutions);
                            $prependEl.appendTo('#l-sidebar-office');
                            $('#SolutionAjaxLoader').appendTo($('#l-sidebar-office'));
                            $('.social-likes').socialLikes();
                            isBusySolution = 0;
                        }
                    }
                });
            },
            this.nextNews = function (prev) {
                var content = $('#content-news');
                self.newsPage += 1;
                $.post('http://www.godesigner.ru/events/news.json', {"page": self.newsPage}, function (response) {
                    var news = '';
                    $.each(response.news, function (i, item) {
                        var host = parse_url_regex(item.link);
                        news += '<div class="design-news"><a target="_blank" href="http://www.godesigner.ru/users/click?link=' + item.link + '&id=' + item.id + '">' + item.title + ' <br><a class="clicks" href="http://www.godesigner.ru/users/click?link=' + item.link + '&id=' + item.id + '">' + host[3] + '</a></div>';
                    });
                    if (response.count < 1) {
                        isBusyDesNews = 1;
                    }
                    if (news != '') {
                        isBusyDesNews = 0;
                        var $prependEl = $(news);
                        $prependEl.hide();
                        $prependEl.appendTo(content).fadeIn(200);
                    }
                });
            },
            this.addComment = function (object, imageurl) {
                var html = '';
                if (object.user.isAdmin == 1) {
                    var avatar = 'http://www.godesigner.ru/img/icon_57.png';
                } else {
                    var avatar = (typeof object.user.images['avatar_small'] != 'undefined') ? object.user.images['avatar_small'].weburl : 'http://www.godesigner.ru/img/default_small_avatar.png';
                }
                var like_txt = object.allowLike ? 'Нравится' : 'Не нравится';
                // Если закрытй питч, или коммент не к решению, то надо скрывать картинки
                var long = false;
                if ((((object.solution) && (object.solution.id)) || (object.solution_id != 0)) && (object.solution != null) && (object.pitch.private != '1')) {
                    long = true;
                }
                if (
                    (object.updateText.match(/Дизайнеры больше не могут/))
                    ||
                    (object.updateText.match(/питч завершен и ожидает/))
                    ||
                    (object.updateText.match(/Друзья, выбран победитель/))
                    ||
                    (object.updateText.match(/Друзья, в брифе возникли изменения/))
                ){
                    return '';
                };
                html = '<div class="box" data-eventid="' + object.id + '">';
                if (long) {
                    html += '<div class="l-img l-img-box" style="padding-top: 0;"> \
                                <a target="_blank" href="http://www.godesigner.ru/users/view/' + object.user_id + '"><img class="avatar" src="' + avatar + '"></a> \
                            </div> \
                            <div class="r-content box-comment">';

                    if (this_user == object.pitch.user_id || (object.comment.public == 1 && object.comment.reply_to != 0)) {
                        html += '<a href="http://www.godesigner.ru/users/view/' + object.user_id + '">' + object.creator + '</a> ' + self.getGenderTxt('оставил', object.user.gender) + ' комментарий в питче <a href="http://www.godesigner.ru/pitches/view/' + object.pitch_id + '">' + object.pitch.title + '</a>:';
                    }
                    else {
                        html += '<a href="http://www.godesigner.ru/users/view/' + object.user_id + '">' + object.creator + '</a> ' + self.getGenderTxt('прокомментировал', object.user.gender) + ' <a href="http://www.godesigner.ru/pitches/viewsolution/' + object.solution.id + '">решение #' + object.solution.num + '</a> для питча <a href="http://www.godesigner.ru/pitches/view/' + object.pitch_id + '">' + object.pitch.title + '</a>:';
                    }
                    html += '</div>\
                            <div class="sol"><img src="http://www.godesigner.ru' + imageurl + '"></div> \
                            <div class="box-info">\
                                <a href="http://www.godesigner.ru/solutions/warn/' + object.solution.id + '.json" class="warning-box" data-solution-id="' + object.solution.id + '">Пожаловаться</a>\
                                <a data-id="' + object.solution.id + '" class="like-small-icon-box" data-userid="' + object.solution.user_id + '" data-vote="' + object.allowLike + '" data-likes="' + object.solution.likes + '" href="#">' + like_txt + '</a>\
                            </div>\
                            <div class="r-content box-comment">\
                                &laquo;' + object.updateText + '&raquo;\
                            </div>\
                        </div>';
                } else {
                    html += '<div class="l-img l-img-box" style="padding-top: 0;"> \
                                        <a target="_blank" href="http://www.godesigner.ru/users/view/' + object.user_id + '"><img class="avatar" src="' + avatar + '"></a> \
                                    </div> \
                                    <div class="r-content box-comment">';
                    if (this_user == object.pitch.user_id || (object.comment.public == 1 && object.comment.reply_to != 0) || !object.solution || !object.solution.id) {
                        html += '<a href="http://www.godesigner.ru/users/view/' + object.user_id + '">' + object.creator + '</a> ' + self.getGenderTxt('оставил', object.user.gender) + ' комментарий в питче <a href="http://www.godesigner.ru/pitches/view/' + object.pitch_id + '">' + object.pitch.title + '</a>:<br /> &laquo;' + object.updateText + '&raquo;';
                    }
                    else {
                        html += '<a href="http://www.godesigner.ru/users/view/' + object.user_id + '">' + object.creator + '</a> ' + self.getGenderTxt('прокомментировал', object.user.gender) + ' <a href="http://www.godesigner.ru/pitches/viewsolution/' + object.solution.id + '">решение #' + object.solution.num + '</a> для питча <a href="http://www.godesigner.ru/pitches/view/' + object.pitch_id + '">' + object.pitch.title + '</a>:<br /> &laquo;' + object.updateText + '&raquo;';
                    }
                    html += '<p class="timeago"><time class="timeago" datetime="' + object.created + '">' + object.created + '</time></p>\
                        </div>\
                    </div>';
                }
                return html;
            },
            this.addRating = function (object, imageurl) {
                var html = '',
                        txtsol = (this_user == object.solution.user_id) ? ' ваше ' : '',
                        avatar = (typeof object.user.images['avatar_small'] != 'undefined') ? object.user.images['avatar_small'].weburl : '/img/default_small_avatar.png';
                html += '<div class="box" data-eventid="' + object.id + '">\
                            <div class="l-img">\
                                <img class="avatar" src="http://www.godesigner.ru' + avatar + '">\
                            </div>\
                            <div class="r-content rating-content">\
                                <a target="_blank" href="http://www.godesigner.ru/users/view/' + object.user_id + '">' + object.creator + '</a> ' + self.getGenderTxt('оценил', object.user.gender) + txtsol + ' решение\
                                <div class="rating-image star' + object.solution.rating + '"></div>\
                                <div class="rating-block">\
                                    <img class="img-rate" src="http://www.godesigner.ru' + imageurl + '">\
                                </div>\
                                <p class="timeago rating-time">\
                                     <time class="timeago" datetime="' + object.created + '">' + object.created + '</time>\
                                </p>\
                                </div>\
                        </div>';
                return html;
            },
            this.addFavUserAdded = function(object) {
                var html = '';
                if(typeof(object.user.images.avatar_small) == 'undefined') {
                    if(object.user.isAdmin == '1') {
                        var avatar = 'http://www.godesigner.ru/img/icon_57.png';
                    }else {
                        var avatar = 'http://www.godesigner.ru/img/default_small_avatar.png';
                    }
                } else {
                    var avatar = object.user.images.avatar_small.weburl;
                }
                if(typeof(object.user_fav.images.avatar_small) == 'undefined') {
                    if(object.user_fav.isAdmin == '1') {
                        var avatar = 'http://www.godesigner.ru/img/icon_57.png';
                    }else {
                        var avatar = 'http://www.godesigner.ru/img/default_small_avatar.png';
                    }
                } else {
                    var avatarFav = object.user_fav.images.avatar_small.weburl;
                }
                html += '<div data-eventid="' + object.id + '" class="box"> \
                <div class="l-img"> \
                <a href="http://www.godesigner.ru/users/view/' + object.user.id + '"><img src="' + avatar + '" class="avatar"></a> \
                <a href="http://www.godesigner.ru/users/view/' + object.user_fav.id + '"><img src="' + avatarFav + '" class="avatar"></a> \
                </div> \
                <div class="r-content box-comment">';

                if(this_user == object.user.id) {
                    html += 'Вы подписались на <a href="http://www.godesigner.ru/users/view/' + object.user_fav.id + '">' + object.creator_fav + '</a>';
                }else if(this_user == object.user_fav.id) {
                    html += '<a href="http://www.godesigner.ru/users/view/' + object.user.id + '">' + object.creator + '</a> ' + self.getGenderTxt('подписан', object.user.gender) + ' на вас';
                } else{
                    html += '<a href="http://www.godesigner.ru/users/view/' + object.user.id + '">' + object.creator + '</a> ' + self.getGenderTxt('подписан', object.user.gender) + ' на <a href="http://www.godesigner.ru/users/view/' + object.user_fav + '">' + object.creator_fav + '</a>';
                }
                html += '<p class="timeago"> \
                <time datetime="' + object.created + '" class="timeago" title="' + object.created + '"></time> \
                </p> \
                </div>  \
                </div>';
                return html;
            },
            this.addNews = function (object) {
                var html = '';

                if((object.news.short.match(/fb-xfbml-parse-ignore/g)) || (object.news.short.match(/instagram-media/g)) || (object.news.short.match(/vk_post/g))) {
                    if((object.news.short.match(/vk_post/g))) {
                        var text = object.news.short.replace(/width: 500/i, "width: 600");
                    }else {
                        var text = object.news.short.replace(/data-width="500"/i, 'data-width="600"');
                        text.replace('<div id="fb-root"></div>', '');
                    }
                    html = '<div data-eventid="' + object.id + '" data-newsid="' + object.news.id + '">' + text + '</div>';
                }else{

                    if (!object.news.tags) {
                        var style = ' style="padding-top: 0px;"';
                    } else {
                        var style = '';
                    }
                    var validUrl = self.isValidImage(object.news.imageurl);
                    var boxStyle = '';
                    if(!validUrl) {
                        boxStyle = " style='margin-top: 34px'; ";
                    }
                    var img = '';
                    if(typeof(object.news.imageurl) != 'undefined') {
                        img = (object.news.imageurl.indexOf('/', 0) == 0) ? 'http://www.godesigner.ru' + object.news.imageurl  : object.news.imageurl;
                    }
                    html += '<div class="box" ' + boxStyle + 'data-eventid="' + object.id + '" data-newsid="' + object.news.id + '">';

                    if(validUrl) {
                        html += '<p class="img-box"> \
                                        <a class="post-link" href="' + object.news.link + '" target="_blank" ><img onerror="imageLoadError(this);" class="img-post" src="' + img + '"></a> \
                                    </p>'
                    }
                    html +='<div class="r-content post-content"' + style + '>';
                    if (object.news.tags) {
                        html += '<p class="img-tag">' + object.news.tags + '</p>';
                    }

                    var like_txt = object.allowLike ? 'Нравится' : 'Не нравится';

                    html += '<a class="img-post" href="' + object.news.link + '" target="_blank"><h2>' + object.news.title + '</h2></a> \
                                        <p class="img-short">' + object.news.short + '</p> \
                                        <p class="timeago"> \
                                            <time class="timeago" datetime="' + object.news.created + '">' + object.news.created + '</time> с сайта ' + object.host;
                    if(object.news.original_title != '') {
                        html += '<span style="font-size: 20px;position: relative;top: 2px;margin-left: 2px;margin-right: 2px;">·</span> переведено автоматически';
                    }
                    html += '</p>'
                    html +=                '</div>';

                        html += '<div class="box-info" style="margin-top: 0;">';
                    if(this_user != '') {
                        html += '<a style="padding-left: 0;padding-right: 10px;" data-news="1" data-id="' + object.news.id + '" class="like-small-icon-box" data-userid="' + this_user + '" data-vote="' + object.allowLike + '" data-likes="' + object.news.liked + '" href="#">' + like_txt + '</a>';
                        html += '<span style="font-size: 28px;position: relative;top: 4px;">·</span>';
                    }
                    html += '<a style="padding-left: 5px;padding-right: 10px; font-size: 14px;" class="share-news-center" href="#">Поделиться</a>';
                    var shareTitle = object.news.title;
                    var url = 'http://www.godesigner.ru/news?event=' + object.id;
                    if(this_user != '') {
                        var left = '120px';
                    }else {
                        var left = '50px';
                    }
                    html += '<div class="sharebar" style="position: absolute; display: none; top: 30px; left: ' + left + ';"> \
                        <div class="tooltip-block"> \
                        <div class="social-likes" data-counters="no" data-url="' + url + '" data-title="' + shareTitle + '"> \
                        <div class="facebook" style="display: inline-block;" title="Поделиться ссылкой на Фейсбуке" data-url="' + url + '">SHARE</div> \
                        <div class="twitter" style="display: inline-block;" data-via="Go_Deer">TWITT</div> \
                        <div class="vkontakte" style="display: inline-block;" title="Поделиться ссылкой во Вконтакте" data-image="' + object.news.imageurl + '" data-url="' + url + '">SHARE</div>';
                    if(validUrl) {
                        html += '<div class="pinterest" style="display: inline-block;" title="Поделиться картинкой на Пинтересте" data-url="' + url + '" data-media="' + object.news.imageurl + '">PIN</div>';
                    }
                    html += '</div> \
                        </div> \
                        </div>';
                    if(object.news.original_title != '') {
                        html += '<span style="font-size: 28px;position: relative;top: 4px;">·</span>';
                        html += '<a data-translated="true" data-original-title="' + object.news.original_title + '" data-original-short="' + object.news.original_short + '" style="padding-left: 5px;padding-right: 10px; font-size: 14px;" class="translate" href="#">Показать оригинальный текст</a>';
                    }
                    if(isAdmin) {
                        html += '<span style="font-size: 28px;position: relative;top: 4px;">·</span>';
                        html += '<a style="padding-left: 5px; font-size: 14px;" data-id="' + object.news.id + '" class="hide-news" href="#">Удалить новость</a>';
                    }
                    html += '</div>';

                    html += '<div data-id="' + object.news.id + '" class="likes">';
                    var likes_count = 0;

                    if (object.news.liked) {
                        $.each(object.news.likes, function (index, like) {
                            likes_count++;
                            var likes = parseInt(object.news.liked);
                            if (likes > 4) {
                                if (likes_count == 1) {
                                    html += '<span class="who-likes">';
                                }
                                if (likes_count == 4) {
                                    var other = likes - likes_count;
                                    html += '<a data-id="' + like.user_id + '" target="_blank" href="http://www.godesigner.ru/users/view/' + like.user_id + '">' + like.creator + '</a>' + ' и <a class="show-other-likes" data-solid="' + object.news.id + '" href="#">' + other + ' других</a> <span>лайкнули новость</span></span>';
                                    return false;
                                } else {
                                    html += '<a data-id="' + like.user_id + '" target="_blank" href="http://www.godesigner.ru/users/view/' + like.user_id + '">' + like.creator + ', </a>';
                                }
                            } else if ((likes >= 2) && (likes <= 4)) {
                                if (likes_count == 1) {
                                    html += '<span class="who-likes">';
                                }
                                if (likes_count != likes) {
                                    html += '<a data-id="' + like.user_id + '" target="_blank" href="http://www.godesigner.ru/users/view/' + like.user_id + '">' + like.creator + ', </a>';
                                }
                                if (likes_count == likes) {
                                    html += '<a data-id="' + like.user_id + '" target="_blank" href="http://www.godesigner.ru/users/view/' + like.user_id + '">' + like.creator + '</a>';
                                    html += ' <span>лайкнули новость</span></span>';
                                }
                            } else if (likes < 2) {
                                html += '<span class="who-likes"><a target="_blank" data-id="' + like.user_id + '" href="http://www.godesigner.ru/users/view/' + like.user_id + '">' + like.creator + '</a> <span>' + self.getGenderTxt('лайкнул', like.user.gender) + ' новость</span></span>';
                            }

                        });
                    }
                html += '</div></div>';
                }
                return html;
            },
            this.addLikes = function(object, imageurl) {
                var html = '';
                txtsol = (this_user == object.solution.user_id) ? 'ваше ' : '',
                    avatar = (typeof object.user.images['avatar_small'] != 'undefined') ? object.user.images['avatar_small'].weburl : '/img/default_small_avatar.png';
                if (object.user.isAdmin == 1) {
                    var avatar = '/img/icon_57.png';
                }
                html += '<div class="box" data-eventid="' + object.id + '">\
                            <div class="l-img">\
                                <img class="avatar" src="http://www.godesigner.ru' + avatar + '">\
                            </div>\
                            <div class="r-content rating-content">\
                                <a target="_blank" href="http://www.godesigner.ru/users/view/' + object.user_id + '">' + object.creator + '</a> ' + self.getGenderTxt('лайкнул', object.user.gender) + txtsol + ' решение\
                                <div class="rating-image" style="background: none;"></div>\
                                <div class="rating-block">\
                                    <img class="img-rate" src="http://www.godesigner.ru' + imageurl + '">\
                                </div>\
                                <p class="timeago rating-time">\
                                     <time class="timeago" datetime="' + object.created + '">' + object.created + '</time>\
                                </p>\
                                </div>\
                        </div>';
                return html;
            },
            this.addSolution = function (object, imageurl, response) {
                var html = '';
                if((object.pitch.private == 1) && (this_user != object.solution.user_id)) {
                    return html;
                }
                var avatar = (typeof object.user.images['avatar_small'] != 'undefined') ? object.user.images['avatar_small'].weburl : 'http://www.godesigner.ru/img/default_small_avatar.png';
                var like_txt = object.allowLike ? 'Нравится' : 'Не нравится';
                html += '<div class="box" data-eventid="' + object.id + '"> \
                            <div class="l-img"> \
                                <a target="_blank" href="http://www.godesigner.ru/users/view/' + object.user_id + '"><img class="avatar" src="' + avatar + '"></a> \
                            </div> \
                            <div class="r-content"> \
                                <a href="http://www.godesigner.ru/users/view/' + object.user_id + '">' + object.creator + '</a> ' + self.getGenderTxt('предложил', object.user.gender) + ' решение для питча <a href="/pitches/view/' + object.pitch_id + '">' + object.pitch.title + '</a>: \
                            </div> \
                            <a href="http://www.godesigner.ru/pitches/viewsolution/' + object.solution.id + '"><div class="sol"><img src="' + imageurl + '"></div></a> \
                            <div class="box-info">\
                                <a href="http://www.godesigner.ru/solutions/warn/' + object.solution.id + '.json" class="warning-box" data-solution-id="' + object.solution.id + '">Пожаловаться</a>\
                                <a data-id="' + object.solution.id + '" class="like-small-icon-box" data-userid="' + object.solution.user_id + '" data-vote="' + object.allowLike + '" data-likes="' + object.solution.likes + '" href="#">' + like_txt + '</a>\
                            </div>\
                            <div data-id="' + object.solution.id + '" class="likes">';
                var likes_count = 0;
                $.each(object.likes, function (index, like) {
                    likes_count++;
                    var my_solution = (object.solution.user_id == this_user) ? 'ваше' : '';
                    var likes = parseInt(object.solution.likes);
                    if (likes > 4) {
                        if (likes_count == 1) {
                            html += '<span class="who-likes">';
                        }
                        if (likes_count == 4) {
                            var other = likes - likes_count;
                            html += '<a data-id="' + like.user_id + '" target="_blank" href="http://www.godesigner.ru/users/view/' + like.user_id + '">' + like.creator + '</a>' + ' и <a class="show-other-likes" data-solid="' + object.solution.id + '" href="#">' + other + ' других</a> <span>лайкнули решение</span></span>';
                            return false;
                        } else {
                            html += '<a data-id="' + like.user_id + '" target="_blank" href="http://www.godesigner.ru/users/view/' + like.user_id + '">' + like.creator + ', </a>';
                        }
                    } else if ((likes >= 2) && (likes <= 4)) {
                        if (likes_count == 1) {
                            html += '<span class="who-likes">';
                        }
                        if (likes_count != likes) {
                            html += '<a data-id="' + like.user_id + '" target="_blank" href="http://www.godesigner.ru/users/view/' + like.user_id + '">' + like.creator + ', </a>';
                        }
                        if (likes_count == likes) {
                            html += '<a data-id="' + like.user_id + '" target="_blank" href="http://www.godesigner.ru/users/view/' + like.user_id + '">' + like.creator + '</a>';
                            html += ' <span>лайкнули решение</span></span>';
                        }
                    } else if (likes < 2) {
                        html += '<span class="who-likes"><a target="_blank" data-id="' + like.user_id + '" href="http://www.godesigner.ru/users/view/' + like.user_id + '">' + like.creator + '</a> <span>' + self.getGenderTxt('лайкнул', like.user.gender) + ' решение</span></span>';
                    }
                });
                html += '</div></div>';
                return html;
            };
}
function parse_url_regex(url) {
    var parse_url = /^(?:([A-Za-z]+):)?(\/{0,3})([0-9.\-A-Za-z]+)(?::(\d+))?(?:\/([^?#]*))?(?:\?([^#]*))?(?:#(.*))?$/;
    var result = parse_url.exec(url);
    return result;
}