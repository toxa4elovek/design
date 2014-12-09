
var isBusy = false;
var page = 1;
$(window).on('scroll', function () {
    if ((($('#middle').height() - 200) - $(window).scrollTop() < 500) && !isBusy) {
        isBusy = true;
        $('#officeAjaxLoader').show();
        page += 1;
        $.get('/solutions/logosale/' + page + '.json', function (response) {
            var html = '';
            $.each(response.solutions, function (index, solution) {
                html += addSolution(solution);
            });
            var $prependEl = $(html);
            $prependEl.hide();
            $prependEl.appendTo('.list_portfolio').slideDown('slow');
            $('#officeAjaxLoader').hide();
            if (response.count > 0) {
                isBusy = false;
            } else {
                isBusy = true;
            }
        });
    }
});

function getImageCount(images) {
    if (images && typeof (images[0]) != 'undefined') {
        return images.length;
    }
    else {
        return 1;
    }
}

function addSolution(solution) {
    var picCounter2 = 0;
    var html = '';
    if ($.isEmptyObject(solution.images)) {
        return true;
    }
    if (solution.images.solution_galleryLargeSize && typeof solution.images.solution_galleryLargeSize[0] != 'undefined') {
        picCounter2 = solution.images.solution_galleryLargeSize.length;
    } else if (typeof solution.images.solution_galleryLargeSize == 'undefined') {
        solution.images.solution_galleryLargeSize = solution.images.solution;
        if ($.isArray(solution.images.solution_galleryLargeSize)) {
            picCounter2 = solution.images.solution_galleryLargeSize.length;
        }
    }
    var multiclass = (picCounter2 > 1) ? ' class=multiclass' : '';
    html += '<li id="li_' + solution.id + '"' + multiclass + '>\
                        <div class="photo_block">';
    if (getImageCount(solution.images.solution_galleryLargeSize) > 1) {
        html += '<div class="image-count">' + getImageCount(solution.images.solution_solutionView) + '</div>'
    }
    html += '<a style="display:block;" data-solutionid="' + solution.id + '" class="imagecontainer" href="/pitches/viewsolution/' + solution.id + '">';

    if (solution.images.solution_galleryLargeSize && typeof solution.images.solution_galleryLargeSize[0] == 'undefined') {
        html += '<img rel="#' + solution.num + '"  width="180" height="135" src="' + solution.images.solution_galleryLargeSize.weburl + '">';
    } else {
        var picCounter = 0;
        $.each(solution.images.solution_galleryLargeSize, function (index, img) {
            var display = (picCounter > 0) ? 'display:none;' : 'opacity:1;';
            html += '<img class="multi"  width="180" height="135" style="position: absolute;left:10px;top:9px;z-index:1;' + display + '" rel="#' + solution.num + '" src="' + img.weburl + '">';
            picCounter++;
        });
    }
    if (Math.floor((Math.random() * 100) + 1) <= 50) {
        var tweetLike = 'Мне нравится этот дизайн! А вам?';
    } else {
        var tweetLike = 'Из всех мне нравится этот дизайн';
    }
    html += '</a>\
                <div class="photo_opt">\
                    <div class="" style="display: block; float:left;">\
                        <span class="rating_block">\
                            <div class="ratingcont" data-default="<?= $solution->rating ?>" data-solutionid="' + solution.id + '" style="float: left; height: 9px; background: url(/img/' + solution.rating + '-rating.png) repeat scroll 0% 0% transparent; width: 56px;"></div>\
                        </span>\
                        <span class="like_view" style="margin-top:2px;">\
                            <img src="/img/looked.png" alt="" class="icon_looked"><span>' + solution.views + '</span>\
                        </span>\
                    </div>\
                    <ul style="margin-left: 78px;" class="right">\
                        <li class="like-hoverbox" style="float: left; margin-top: 0px; padding-top: 0px; height: 15px; padding-right: 0px; margin-right: 0px; width: 38px;">\
                            <a href="#" style="float:left" class="like-small-icon" data-id="' + solution.id + '"><img src="/img/like.png" alt="количество лайков"></a>\
                            <span class="underlying-likes" style="color: rgb(205, 204, 204); font-size: 10px; vertical-align: middle; display: block; float: left; height: 16px; padding-top: 5px; margin-left: 2px;" data-id="' + solution.id + '" rel="http://www.godesigner.ru/pitches/viewsolution/' + solution.id + '">' + solution.likes + '</span>\
                            <div class="sharebar" style="padding:0 0 4px !important;background:url(/img/tooltip-bg-bootom-stripe.png) no-repeat scroll 0 100% transparent !important;position:relative;z-index:10000;display: none; left: -10px; right: auto; top: 20px;height: 178px;width:288px;">\
                                <div class="tooltip-wrap" style="height: 140px; background: url(/img/tooltip-top-bg.png) no-repeat scroll 0 0 transparent !important;padding:39px 10px 0 16px !important">\
                                    <div class="body" style="display: block;">\
                                        <table width="100%">\
                                            <tbody>\
                                                <tr height="35">\
                                                    <td width="137" valign="middle">\
                                                        <a id="facebook' + solution.id + '" class="socialite facebook-like" href="http://www.facebook.com/sharer.php?u=http://www.godesigner.ru/pitches/viewsolution/' + solution.id + '" data-href="http://www.godesigner.ru/pitches/viewsolution/' + solution.id + '" data-send="false" data-layout="button_count">\
                                                            Share on Facebook\
                                                        </a>\
                                                    </td>\
                                                    <td width="137" valign="middle">\
                                                        <a id="twitter' + solution.id + '" class="socialite twitter-share" href="" data-url="http://www.godesigner.ru/pitches/viewsolution/' + solution.id + '?utm_source=twitter&utm_medium=tweet&utm_content=like-tweet&utm_campaign=sharing" data-text="' + tweetLike + '" data-lang="ru" data-hashtags="Go_Deer">\
                                                            Share on Twitter\
                                                        </a>\
                                                    </td>\
                                                </tr>\
                                                <tr height="35">\
                                                    <td valign="middle">\
                                                        <a href="http://www.tumblr.com/share" title="Share on Tumblr" style="display:inline-block; text-indent:-9999px; overflow:hidden; width:81px; height:20px; background:url(http://platform.tumblr.com/v1/share_1.png) top left no-repeat transparent;">Share on Tumblr</a>\
                                                    </td>\
                                                    <td valign="middle">\
                                                        <a href="http://pinterest.com/pin/create/button/?url=http%3A%2F%2Fwww.godesigner.ru%2Fpitches%2Fviewsolution%2F<?= $solution->id ?>&media=' + encodeURIComponent('http://www.godesigner.ru' + solution.images.solution_solutionView[0]) + '&description=%D0%9E%D1%82%D0%BB%D0%B8%D1%87%D0%BD%D0%BE%D0%B5%20%D1%80%D0%B5%D1%88%D0%B5%D0%BD%D0%B8%D0%B5%20%D0%BD%D0%B0%20%D1%81%D0%B0%D0%B9%D1%82%D0%B5%20GoDesigner.ru" class="pin-it-button" count-layout="horizontal"><img border="0" src="//assets.pinterest.com/images/PinExt.png" title="Pin It" /></a>\
                                                    </td>\
                                                </tr>\
                                            </tbody>\
                                        </table>\
                                    </div>\
                                </div>\
                            </div>\
                        </li>\
                        <li style="padding-left:0;margin-left:0;float: left; padding-top: 1px; height: 16px; margin-top: 0;width:30px">\
                            <span class="bottom_arrow">\
                                <a href="#" class="solution-menu-toggle"><img src="/img/marker5_2.png" alt=""></a>\
                            </span>\
                        </li>\
                    </ul>\
                </div>\
            </div>\
            <div class="selecting_numb"><span class="price">19000 р.</span><span class="new-price">9500р.-</span></div>\
                <div class="solution_menu" style="display: none;">\
                    <ul class="solution_menu_list">\
                        <li class="sol_hov"><a href="/solutions/buy/' + solution.id + '.json" class="hide-item">Купить</a></li>\
                        <li class="sol_hov"><a href="/solutions/warn/' + solution.id + '.json" class="warning" data-solution-id="' + solution.id + '">Пожаловаться</a></li>\
                    </ul>\
                </div>\
        </li>';
    console.log(solution.id);
    return html;
}


$(document).on('mouseover', '.solution-menu-toggle', function () {
    $('img', $(this)).attr('src', '/img/marker-green.png');
    $('body').one('click', function () {
        $('.solution_menu.temp').fadeOut(200, function () {
            $(this).remove();
        });
    });
    var container = $(this).closest('.photo_block');
    var menu = container.siblings('.solution_menu');
    var offset = container.offset();
    menu = menu.clone();
    menu.addClass('temp');
    $('body').append(menu);
    menu.offset({top: offset.top + 178, left: offset.left + 47});
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


$(document).on('click', '.imagecontainer', function (e) {
    if (/designers/.test(window.location.pathname)) {
        return true;
    }
    if (window.history.pushState) {
        window.history.pushState('object or string', 'Title', this.href); // @todo Check params
    } else {
        window.location = $(this).attr('href');
        return false;
    }
    $('.solution-overlay-dummy').clone().appendTo('body').addClass('solution-overlay');
    $('#pitch-panel').hide();
    beforeScrollTop = $(window).scrollTop();
    $('.wrapper', 'body').first().addClass('wrapper-frozen');
    $.browser.chrome = /chrome/.test(navigator.userAgent.toLowerCase());
    if (!$.browser.chrome) {
        $('.solution-overlay').css('z-index', '50');
    }
    $('.solution-overlay').show();
    if (allowComments) {
        $('.allow-comments', '.solution-left-panel').show();
    }
    var queryParam = '';
    if (document.URL.indexOf('?') != -1) {
        queryParam = document.URL.slice(document.URL.indexOf('?'));
    }
    var urlJSON = window.location.pathname + '.json' + queryParam;
    fetchSolution(urlJSON);
    return false;
});



$('a#goSearch').on('click', function () {
    var search = $('#searchTerm');
    console.log(search.val().length);
    console.log(search.hasClass('placeholder'));
    if (search.val().length > 0 && !search.hasClass('placeholder')) {
        $.post('search_logo.json', {'search': search.val()}, function (response) {
            var html = '';
            $.each(response.solutions, function (index, solution) {
                html += addSolution(solution);
            });
            $('.list_portfolio').empty();
            var $prependEl = $(html);
            $prependEl.hide();
            $prependEl.appendTo('.list_portfolio').slideDown('slow');
        });
    }
    return false;
});


$(document).on('mouseleave', '.solution-menu-toggle', function () {
    $('img', $(this)).attr('src', '/img/marker5_2.png');
});

var cycle = {};
$(document).on('mouseenter', '.photo_block', function () {
    if ($(this).children('.imagecontainer').children().length > 1) {
        cycle[$(this).children('.imagecontainer').data('solutionid')] = true;
        var image = $(this).children('.imagecontainer').children(':visible');
        cycleImage(image, $(this).children('.imagecontainer'));
    }
});

$(document).on('mouseleave', '.photo_block', function () {
    cycle[$(this).children('.imagecontainer').data('solutionid')] = false;
});

function cycleImage(image, parent, prev) {
    if (cycle[parent.data('solutionid')] == true) {
        image.fadeOut(300);
        var nextImage = image.next();
        if (nextImage.length == 0) {
            nextImage = parent.children().first();
        }
        nextImage.fadeIn(300);
        setTimeout(function () {
            cycleImage(nextImage, parent, image)
        }, 1500);
    }
}



function fetchSolution(urlJSON) {
    // Reset layout
    $(window).scrollTop(0);
    $('.solution-images').html('<div style="text-align:center;height:220px;padding-top:180px"><img alt="" src="/img/blog-ajax-loader.gif"></div>');
    $('.author-avatar').attr('src', '/img/default_small_avatar.png');
    $('.rating-image', '.solution-rating').removeClass('star0 star1 star2 star3 star4 star5');
    $('.description-more').hide();
    $('#newComment', '.solution-left-panel').val('');
    $('.solution-images').html('<div style="text-align:center;height:220px;padding-top:180px"><img alt="" src="/img/blog-ajax-loader.gif"></div>');
    solutionThumbnail = '';
    $.getJSON(urlJSON, function (result) {
        $('span#date').text('Опубликовано ' + result.date);
        // Navigation
        $('.solution-prev-area').attr('href', '/pitches/viewsolution/' + result.prev); // @todo Next|Prev unclearly
        $('.solution-next-area').attr('href', '/pitches/viewsolution/' + result.next); // @todo ¿Sorting?

        // Left Panel
        $('.solution-images').html('');
        console.log(result.pitch.title);
        $('.solution-left-panel .solution-title').children('h1').html(result.pitch.title + '<br> Цена: <span class="price"> 18000 р. с учетом сборов</span> <span class="new-price">9500 р.-</span>');
        if ((result.solution.images.solution) && (result.pitch.category_id != 7)) {
            // Main Images
            if (typeof (result.solution.images.solution_gallerySiteSize) != 'undefined') {
                viewsize = result.solution.images.solution_gallerySiteSize;
                work = result.solution.images.solution_solutionView
            } else {
                // case when we don't have gallerySiteSize image size
                viewsize = result.solution.images.solution;
                work = result.solution.images.solution
            }
            if ($.isArray(result.solution.images.solution)) {
                $.each(work, function (idx, field) {
                    $('.solution-images').append('<a href="' + viewsize[idx].weburl + '" target="_blank"><img src="' + field.weburl + '" class="solution-image" /></a>');
                });
            } else {
                $('.solution-images').append('<a href="' + viewsize.weburl + '" target="_blank"><img src="' + work.weburl + '" class="solution-image" /></a>');
            }
            // Thumbnail Image
            if (typeof (result.solution.images.solution_galleryLargeSize) != 'undefined') {
                viewsize = result.solution.images.solution_galleryLargeSize;
            } else {
                // case when we don't have gallerySiteSize image size
                viewsize = result.solution.images.solution;
            }
            if ($.isArray(viewsize)) {
                solutionThumbnail = viewsize[0].weburl;
            } else {
                solutionThumbnail = viewsize.weburl;
            }
        } else {
            $('.solution-images').append('<div class="preview"> \
                    <span>' + result.solution.description + '</span> \
                </div>');
        }

        var firstImage = $('.solution-image', '.solution-overlay').first().parent();
        if (currentUserId == result.pitch.user_id) { // isClient
            var ratingWidget = $('<div class="separator-rating"> \
                    <div class="separator-left"></div> \
                    <div class="rating-widget"><span class="left">выставьте</span> \
                            <span id="star-widget"></span> \
                    <span class="right">рейтинг</span></div> \
                    <div class="separator-right"></div> \
                    </div>');
            if (firstImage.length > 0) {
                ratingWidget.insertAfter(firstImage);
            } else {
                ratingWidget.insertAfter('.preview:visible');
                $('.separator-rating').css({"margin-top": "20px", "margin-bottom": "20px"});
            }

            $("#star-widget").raty({
                path: '/img',
                hintList: ['не то!', 'так себе', 'возможно', 'хорошо', 'отлично'],
                starOff: 'solution-star-off.png',
                starOn: 'solution-star-on.png',
                start: result.solution.rating,
                click: function (score, evt) {
                    $.post('/solutions/rating/' + $('input[name=solution_id]').val() + '.json',
                            {"id": result.solution.id, "rating": score}, function (response) {
                        $('.rating-image', '.solution-rating').removeClass('star0 star1 star2 star3 star4 star5');
                        $('.rating-image', '.solution-rating').addClass('star' + score);
                        var $underlyingRating = $('.ratingcont', '#li_' + $('.isField.number', '.solution-overlay').text());
                        if ($underlyingRating.length > 0) {
                            $underlyingRating.css('background-image', 'url(/img/' + score + '-rating.png)');
                            $underlyingRating.data('default', score);
                        }
                    });
                }
            });

        } else if (currentUserId == result.solution.user_id) {
            // Solution Author views nothing
        } else { // Any User
            if ((result.pitch.status != 1) && (result.pitch.status != 2)) {
                var already = ''
                if (result.likes == true) {
                    already = ' already'
                }
                $('<div class="like-wrapper"><div class="left">поддержи</div> \
                       <a class="like-widget' + already + '" data-id="' + result.solution.id + '"></a> \
                       <div class="right">автора</div></div>').insertAfter($('.solution-image').last().parent());

                $('.like-widget[data-id=' + result.solution.id + ']').click(function () {
                    $(this).toggleClass('already');
                    var counter = $('.value-likes')
                    var solutionId = $(this).data('id')
                    if ($(this).hasClass('already')) {
                        $.post('/solutions/like/' + solutionId + '.json', {"uid": currentUserId}, function (response) {
                            counter.text(parseInt(response.likes));
                            $('.underlying-likes[data-id=' + result.solution.id + ']').text(parseInt(response.likes));
                        });
                    } else {
                        $.post('/solutions/unlike/' + solutionId + '.json', {"uid": currentUserId}, function (response) {
                            counter.text(parseInt(response.likes));
                            $('.underlying-likes[data-id=' + result.solution.id + ']').text(parseInt(response.likes));
                        });
                    }
                    return false
                });
            }
        }

        $('#newComment', '.solution-left-panel').val('#' + result.solution.num + ', ');
        solutionId = result.solution.id;

        if (result.comments) {
            $('.solution-comments').html(fetchCommentsNew(result));
            solutionTooltip();
        }

        // Right Panel
        $('.number', '.solution-number').text(result.solution.num || '');
        $('.rating-image', '.solution-rating').addClass('star' + result.solution.rating).attr('data-rating', result.solution.rating);
        if (result.userAvatar) {
            $('.author-avatar').attr('src', result.userAvatar);
        } else {
            $('.author-avatar').attr('src', '/img/default_small_avatar.png');
        }
        $('.author-name').attr('href', '/users/view/' + result.solution.user_id).text(result.solution.user.first_name + ' ' + result.solution.user.last_name.substring(0, 1) + '.');
        if (result.userData && result.userData.city) {
            $('.author-from').text(result.userData.city); // @todo Try to unserialize userdata on Clientside
        } else {
            $('.author-from').text('');
        }

        if (result.pitch.category_id != 7) {
            var desc = result.solution.description;
            var viewLength = 100; // Description string cut length parameter
            if (desc.length > viewLength) {
                var descBefore = desc.slice(0, viewLength - 1);
                descBefore = descBefore.substr(0, Math.min(descBefore.length, descBefore.lastIndexOf(" ")));
                var descAfter = desc.slice(descBefore.length);
                $('.solution-description').html(descBefore);
                $('.description-more').show(500);
                $('.description-more').on('click', function () {
                    $('.solution-description').append(descAfter);
                    descAfter = '';
                    $('.description-more').hide();
                });
            } else {
                $('.solution-description').html(result.solution.description);
            }
            if (result.solution.description != '') {
                $('span#date').after('<br />');
            }
        } else {
            $('.solution-description').html('');
            $('.solution-about').next().hide();
            $('.solution-about').hide();
            $('.solution-share').next().hide();
            $('.solution-share').hide();
            var html = '<div class="attach-wrapper">';
            if (result.solution.images.solution) {
                if ($.isArray(result.solution.images.solution)) {
                    $.each(result.solution.images.solution, function (index, object) {
                        html += '<a target="_blank" href="/solutionfiles' + object.weburl + '" class="attach">' + object.originalbasename + '</a><br>'
                    })
                } else {
                    html += '<a href="/solutionfiles' + result.solution.images.solution.weburl + '" class="attach">' + result.solution.images.solution.originalbasename + '</a>'
                }
                html += '</div>';
                $('.solution-description').prev().html('ФАЙЛЫ')
                $('.solution-description').html(html);
            }
        }

        // Copyrighted Materials
        var copyrightedHtml = '<div class="solution-copyrighted"><!--  --></div>';
        if ((result.solution.copyrightedMaterial == 1) && ((currentUserId == result.pitch.user_id) || (currentUserId == result.solution.user_id) || (isCurrentAdmin))) {
            copyrightedHtml = copyrightedInfo(result.copyrightedInfo);
        }
        $('.solution-copyrighted').replaceWith(copyrightedHtml);

        $('.value-views', '.solution-stat').text(result.solution.views || 0);
        $('.value-likes', '.solution-stat').text(result.solution.likes || 0);
        $('.value-comments', '.solution-stat').text(result.comments.length || 0);

        if (result.pitch.category_id != 7) {

            var media = '';
            if ($.isArray(result.solution.images.solution_solutionView)) {
                media = result.solution.images.solution_solutionView[0].weburl
            } else {
                media = result.solution.images.solution_solutionView.weburl
            }
            // Twitter like solution message
            var tweetLike = 'Мне нравится этот дизайн! А вам?';
            if (Math.floor((Math.random() * 100) + 1) <= 50) {
                tweetLike = 'Из всех ' + result.pitch.ideas_count + ' мне нравится этот дизайн';
            }
            $('.solution-share').html('<h2>ПОДЕЛИТЬСЯ</h2> \
                <div class="body" style="display: block;"> \
                <table width="100%"> \
                    <tbody> \
                        <tr height="35"> \
                            <td width="137" valign="middle">\
                                <a id="facebook_pop' + result.solution.id + '" class="socialite facebook-like fb-like" href="http://www.facebook.com/sharer.php?u=http://www.godesigner.ru/pitches/viewsolution/' + result.solution.id + '" data-href="http://www.godesigner.ru/pitches/viewsolution/' + result.solution.id + '" data-send="false" data-layout="button_count"> \
                                    Share on Facebook \
                                </a> \
                            </td> \
                            <td width="137" valign="middle"> \
                                <div id="vk_like" style="height: 22px; width: 100px; background-image: none; position: relative; clear: both; background-position: initial initial; background-repeat: initial initial;"><iframe name="fXD5a766" frameborder="0" src="http://vk.com/widget_like.php?app=2950889&amp;width=100%&amp;_ver=1&amp;page=0&amp;url=http%3A%2F%2Fwww.godesigner.ru%2Fpitches%2Fviewsolution%2F' + result.solution.id + '%3Fsorting%3Dcreated&amp;type=mini&amp;verb=0&amp;title=%D0%9B%D0%BE%D0%B3%D0%BE%D1%82%D0%B8%D0%BF%20%D0%BA%D0%BE%D0%BC%D0%BF%D0%B0%D0%BD%D0%B8%D0%B8%20%22%D0%9F%D1%80%D0%BE%D1%84%D0%B5%D1%81%D1%81%D0%B8%D0%BE%D0%BD%D0%B0%D0%BB%D1%8C%D0%BD%D0%B0%D1%8F%20%D0%B7%D0%B0%D1%89%D0%B8%D1%82%D0%B0%22%20%7C%20GoDesigner&amp;description=&amp;image=http%3A%2F%2Fwww.godesigner.ru%2Fsolutions%2Fd8e8955043c662a8f8144674efa995f6_galleryLargeSize.jpg&amp;text=&amp;h=22&amp;13fa381efb0" width="100%" height="22" scrolling="no" id="vkwidget1" style="overflow: hidden; height: 22px; width: 100px; z-index: 150;"></iframe></div> \
                            </td> \
                        </tr> \
                        <tr height="35"> \
                            <td valign="middle"> \
                                <a id="twitter_pop' + result.solution.id + '" class="socialite twitter-share twitter-share-button" href="" data-url="http://www.godesigner.ru/pitches/viewsolution/' + result.solution.id + '?utm_source=twitter&utm_medium=tweet&utm_content=like-tweet&utm_campaign=sharing" data-text="' + tweetLike + '" data-lang="ru" data-hashtags="Go_Deer"> \
                                    Share on Twitter \
                                </a> \
                            </td> \
                            <td valign="middle"> \
                                <a href="//pinterest.com/pin/create/button/?url=http%3A%2F%2Fwww.godesigner.ru%2Fpitches%2Fviewsolution%2F' + result.solution.id + '&amp;media=http%3A%2F%2Fwww.godesigner.ru%2F' + media + '&amp;description=%D0%9E%D1%82%D0%BB%D0%B8%D1%87%D0%BD%D0%BE%D0%B5%20%D1%80%D0%B5%D1%88%D0%B5%D0%BD%D0%B8%D0%B5%20%D0%BD%D0%B0%20%D1%81%D0%B0%D0%B9%D1%82%D0%B5%20GoDesigner.ru" target="_blank" data-pin-log="button_pinit" data-pin-config="beside"><img src="//assets.pinterest.com/images/pidgets/pin_it_button.png" /></a> \
                            </td> \
                        </tr> \
                        <tr height="35"> \
                            <td valign="middle"><iframe frameborder="0" scrolling="no" class="surfinbird__like_iframe" src="//surfingbird.ru/button?layout=common&amp;url=http%3A%2F%2Fwww.godesigner.ru%2Fpitches%2Fviewsolution%2F' + result.solution.id + '%3Fsorting%3Dcreated&amp;caption=%D0%A1%D0%B5%D1%80%D1%84&amp;referrer=http%3A%2F%2Fwww.godesigner.ru%2Fpitches%2Fview%2F101057&amp;current_url=http%3A%2F%2Fwww.godesigner.ru%2Fpitches%2Fviewsolution%2F' + result.solution.id + '%3Fsorting%3Dcreated" style="width: 120px; height: 20px;"></iframe><a target="_blank" class="surfinbird__like_button __sb_parsed__" data-surf-config="{layout: "common", width: "120", height: 20}" href="http://surfingbird.ru/share"></a></td> \
                        </tr> \
                        <tr height="35"> \
                            <!--td valign="middle"><script src="//platform.linkedin.com/in.js" type="text/javascript"></script> \
                                <span class="IN-widget" style="line-height: 1; vertical-align: baseline; display: inline-block; text-align: center;"><span style="padding: 0px !important; margin: 0px !important; text-indent: 0px !important; display: inline-block !important; vertical-align: baseline !important; font-size: 1px !important;"><span id="li_ui_li_gen_1372837769632_0"><a id="li_ui_li_gen_1372837769632_0-link" href="javascript:void(0);"><span id="li_ui_li_gen_1372837769632_0-logo">in</span><span id="li_ui_li_gen_1372837769632_0-title"><span id="li_ui_li_gen_1372837769632_0-mark"></span><span id="li_ui_li_gen_1372837769632_0-title-text">Share</span></span></a></span></span><span style="padding: 0px !important; margin: 0px !important; text-indent: 0px !important; display: inline-block !important; vertical-align: baseline !important; font-size: 1px !important;"><span id="li_ui_li_gen_1372837769647_1-container" class="IN-right IN-hidden"><span id="li_ui_li_gen_1372837769647_1" class="IN-right"><span id="li_ui_li_gen_1372837769647_1-inner" class="IN-right"><span id="li_ui_li_gen_1372837769647_1-content" class="IN-right">0</span></span></span></span></span></span><script type="IN/Share+init" data-counter="right"></script></td--> \
                            <td valign="middle"><a href="http://www.tumblr.com/share" title="Share on Tumblr" style="display:inline-block; text-indent:-9999px; overflow:hidden; width:81px; height:20px; background:url(http://platform.tumblr.com/v1/share_1.png) top left no-repeat transparent;">Share on Tumblr</a></td><td></td> \
                        </tr> \
                    </tbody> \
                </table> \
                </div>');
        }

        if ((currentUserId == result.solution.user_id) || isCurrentAdmin) {
            $('.solution-abuse').html('<a class="abuse warning" href="/solutions/warn/' + result.solution.id + '.json" data-solution-id="' + result.solution.id + '">Пожаловаться</a> \
                    <a class="delete-solution-popup hide" data-solution="' + result.solution.id + '" href="/solutions/delete/' + result.solution.id + '.json">Удалить</a>');
        } else {
            $('.solution-abuse').html('<a class="abuse warning" href="/solutions/warn/' + result.solution.id + '.json" data-solution-id="' + result.solution.id + '">Пожаловаться</a>');
        }
        FB.XFBML.parse();
        $.getScript('/js/pitches/gallery.twitter.js', function () {
            if (typeof (twttr) != 'undefined') {
                twttr.widgets.load();
            }
        });
    });
}

$('body, .solution-overlay').on('click', '.solution-title, .solution-popup-close', function (e) {
    hideSolutionPopup();
    return false;
});

function hideSolutionPopup() {
    if ($('.solution-overlay').is(':visible')) {
        $(window).scrollTop(beforeScrollTop);
        $('#pitch-panel').show();
        $('.wrapper', 'body').first().removeClass('wrapper-frozen');
        $('.solution-overlay').hide().remove();
    }
}

$('.rb1').change(function () {
    switch ($(this).data('pay')) {
        case 'payanyway':
            $("#paybutton-payanyway").removeAttr('style');
            $("#paybutton-paymaster").css('background', '#a2b2bb');
            $("#paymaster-images").show();
            $("#paymaster-select").hide();
            break;
        case 'paymaster':
            $("#paybutton-paymaster").removeAttr('style');
            $("#paybutton-payanyway").css('background', '#a2b2bb');
            $("#paymaster-images").hide();
            $("#paymaster-select").show();
            break;
        case 'offline':
            $("#paybutton-payanyway").fadeOut(100);
            $("#paybutton-paymaster").css('background', '#a2b2bb');
            $("#paymaster-images").show();
            $("#paymaster-select").hide();
            $('#s3_kv').show();
            break;
    }
});