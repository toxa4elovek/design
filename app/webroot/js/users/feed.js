// office.js start

$(document).ready(function () {

    var Tip = new TopTip;
    isBusy = 0;
    function scrollInit() {
        $(window).on('scroll', function () {
            if (($(document).height() - $(window).scrollTop() - $(window).height() < 200) && !isBusy) {
                //$(window).off('scroll');
                isBusy = 1;
                Tip.scrollHandler();
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
        var likesNum = $(this);
        var likeLink = $(this).next();
        likesNum.html(parseInt(likesNum.html()));
        var sharebar = likesNum.next();
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
                    if (typeof (response.news) != "undefined") {
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
                    if (typeof (response.post) != "undefined") {
                        var $prependEl = $('<div class="box"> \
                            <a href="' + response.post.link + '"><img class="img-post" src="' + response.post.imageurl + '"></a> \
                            <div class="r-content"> \
                                <a href="' + response.post.link + '"><h2>' + response.post.title + '</h2></a> \
                                <time class="timeago" datetime="' + response.post.created + '">' + response.post.created + '</time> \
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
                    if (typeof (response.solutions) != "undefined") {
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

                                solutions += '<div class="solutions-block"> \
                                    <a href="/pitches/viewsolution/' + solution.solution.id + '"><img src="' + imageurl + '"></a> \
                                    <div> \
                                        <p class="creator-name">><a target="_blank" href="/users/view/' + solution.user_id + '">' + solution.creator + '</a></p> \
                                        <p class="ratingcont" data-default="' + solution.solution.rating + '" data-solutionid="' + solution.solution.id + '" style="height: 9px; background: url(/img/' + solution.solution.rating + '-rating.png) no-repeat scroll 0% 0% transparent;display:inline-block;width: 56px;"></p> \
                                        <p class="fb_like"> \
                                            <a data-id="' + solution.solution.id + '" class="like-small-icon" href="#">' + solution.solution.likes + '</a> \
                                        </p> \
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
                                if (object.type == 'SolutionAdded') {
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

                                if (object.type == 'CommentAdded') {
                                    avatar = (typeof object.user.images['avatar_small'] != 'undefined') ? object.user.images['avatar_small'].weburl : '/img/default_small_avatar.png';
                                    html += '<div class="box"> \
                            <div class="l-img"> \
                                <img class="avatar" src="' + avatar + '"> \
                            </div> \
                            <div class="r-content">';
                                    if (object.comment.public && !object.comment.reply_to) {
                                        html += '<a href="/users/view/' + object.user_id + '">' + object.creator + '</a> прокомментировал ваше <a href="/pitches/viewsolution/' + object.solution.id + '">решение #' + object.solution.num + '</a> для питча <a href="/pitches/view/' + object.pitch_id + '">' + object.pitch.title + '</a>: &laquo;' + object.updateText + '&raquo; </div>';
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
                var solutions = '';
                $.each(response.updates, function (index, object) {
                    if (!object.solution) {
                        object.solution = {};
                        object.solution.images = {};
                        object.solution.images.solution_galleryLargeSize = {};
                        object.solution.views = 0;
                        object.solution.likes = 0;
                        object.solution.images.solution_galleryLargeSize.weburl = '';
                    }

                    var newclass = '';
                    if (Date.parse(object.jsCreated) > offsetDate) {
                        newclass = ' newevent ';
                    }

                    if (object.type == 'PitchCreated') {
                        newclass = ' newpitchstream ';
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

                    if (object.type == 'SolutionAdded') {
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

                    if (object.type == 'CommentAdded') {
                        avatar = (typeof object.user.images['avatar_small'] != 'undefined') ? object.user.images['avatar_small'].weburl : '/img/default_small_avatar.png';
                        html += '<div class="box"> \
                            <div class="l-img"> \
                                <img class="avatar" src="' + avatar + '"> \
                            </div> \
                            <div class="r-content">';
                        if (object.comment.public == 1 && object.comment.reply_to == 0) {
                            html += '<a href="/users/view/' + object.user_id + '">' + object.creator + '</a> оставил комментарий в питче <a href="/pitches/view/' + object.pitch_id + '">' + object.pitch.title + '</a>: &laquo;' + object.updateText + '&raquo; </div>';
                        }
                        else {
                            html += '<a href="/users/view/' + object.user_id + '">' + object.creator + '</a> прокомментировал ваше <a href="/pitches/viewsolution/' + object.solution.id + '">решение #' + object.solution.num + '</a> для питча <a href="/pitches/view/' + object.pitch_id + '">' + object.pitch.title + '</a>: &laquo;' + object.updateText + '&raquo;';
                            html += '</div><img class="sol" src="' + imageurl + '">';
                        }
                        html += '</div>';
                    }
                });

                var $appendEl = $(html);
                $appendEl.hide();
                $formerLast.removeClass('last_item');
                $appendEl.appendTo('#updates-box-').slideDown('slow', function () {
                    $('.box').last().addClass('last_item');
                });

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