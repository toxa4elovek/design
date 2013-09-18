$(document).ready(function(){

    // грузим экстра картинки...
    $.each(extraimages, function(index, object) {
        var block = $('a[data-solutionid=' + index +']');
        for(i=0;i < object.length; i++) {
            block.append('<img class="multi" width="180" height="135" style="position: absolute;left:10px;top:9px;z-index:1;display:none;" src="' + object[i] + '" alt="">');
        }
    })

    function preload(arrayOfImages) {
        $(arrayOfImages).each(function(){
            $('<img/>')[0].src = this;
            // Alternatively you could use:
            // (new Image()).src = this;
        });
    }

    // Usage:

    preload([
        '/img/0-rating.png',
        '/img/1-rating.png',
        '/img/2-rating.png',
        '/img/3-rating.png',
        '/img/4-rating.png',
        '/img/5-rating.png',
        '/img/like.png',
        '/img/like_hover.png',
        '/img/order1_hover.png',
        '/img/order2_hover.png'
    ]);

  //Переключение активной страницы в Главном меню
  $('.main_nav a').click(function(){
	 $('.main_nav a').removeClass('active');
	 $(this).addClass('active');
  });

    VK.init({apiId: 2950889, onlyWidgets: true});

    // gplus
    window.___gcfg = {lang: 'ru'};

    (function() {
        var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
        po.src = 'https://apis.google.com/js/plusone.js';
        var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
    })();

  //Hover на кнопке "full_pitch"
  $('p.full_pitch a').hover(function(){
	 $(this).parent().siblings('h2').children().css('color','#648fa4');
  },function(){
	 $(this).parent().siblings('h2').children().css('color','#666');
  });


    $('.order1').on('mouseover', function() {
        $('img', this).attr('src', '/img/order1_hover.png');
    })
    $('.order1').on('mouseout', function() {
        $('img', this).attr('src', '/img/order1.png');
    })

    $('.order2').on('mouseover', function() {
        $('img', this).attr('src', '/img/order2_hover.png');
    })
    $('.order2').on('mouseout', function() {
        $('img', this).attr('src', '/img/order2.png');
    })

  //Цвет фона для текущих питчей
  $('#current_pitch ul li:odd').css({backgroundColor: '#2f313a'});

    $('.ratingchange').on('mouseenter', function(){
        $(this).parent().css('background', 'url(/img/' + $(this).data('rating') + '-rating.png) repeat scroll 0% 0% transparent');
    })

    $('.ratingcont').on('mouseleave', function() {
        $(this).css('background', 'url(/img/' + $(this).data('default') + '-rating.png) repeat scroll 0% 0% transparent');
    })

    $('.ratingchange').on('click', function() {
        var id = $(this).parent().data('solutionid');
        var rating = $(this).data('rating');
        var self = $(this);
        $.post('/solutions/rating/' + id + '.json',
            {"id": id, "rating": rating}, function(response) {
                self.parent().data('default', rating);
                self.parent().css('background', 'url(/img/' + rating + '-rating.png) repeat scroll 0% 0% transparent');
            });
        return false;
    })

    // socialite
    //Socialite.load();


  //Добавление оценки(звездочки)
  $('.global_info ul li a').toggle(function(){
	 $(this).css({backgroundPosition: '0 -9px'});
  },function(){
	 $(this).css({backgroundPosition: 'left top'});
  });

  //Большая карусель
	$('#big_carousel').jCarouselLite({
		auto: 5000,
		speed: 1500,
		btnPrev: "#prev",
		btnNext: "#next"
	});

  //Маленькая карусель
	$('#carousel_small').jCarouselLite({
		auto: 5000,
		speed: 1500,
		btnPrev: "#prev2",
		btnNext: "#next2",
		visible: 1
	});

  // Появление блока с инфой, при наведении на элементы главной карусели
  $('.main_carou ul li').hover(function(){
  	$(this).html('<div class="info_block"></div>')
  },function(){

  });

   $(document).on('click', '.solution-menu-toggle', function() {
       $('body').one('click',function() {
           $('.solution_menu').hide();
       });
       var menu = $(this).parent().parent().parent().parent().parent().next().next();
       menu.toggle();
       return false;
   });

   $(document).on('click', '.select-winner', function() {
       var item = $(this).parent().parent().parent().prev().prev().clone();
       $('#winner-num').text('#' + $(this).data('num'));
       $('#winner-num').attr('href', '/pitches/viewsolution/' + $(this).data('solutionid'));
       $('#winner-user-link').text($(this).data('user'));
       $('#winner-user-link').attr('href', '/users/view/' + $(this).data('userid'));
       $('#confirmWinner').data('url', $(this).attr('href'));
       $('#replacingblock').replaceWith(item);
       $('#popup-final-step').modal({
           containerId: 'final-step',
           opacity: 80,
           closeClass: 'popup-close'
       });

       /*
       $.get($(this).attr('href'), function(response) {
           if(response.result != false) {
               if(response.result.nominated) {
                   window.location = '/users/nominated';
                   $('.select-winner-li').remove();
               }
           }
       });*/
       return false;
   });

    $(document).on('click', '#confirmWinner', function() {
        var url = $(this).data('url');
        $.get(url, function(response) {
            if(response.result != false) {
                if(response.result.nominated) {
                    window.location = '/users/nominated';
                    $('.select-winner-li').remove();
                }
            }
       });
    });

    $(document).on('click', '.delete-solution', function() {
        var link = $(this);
        var newSolutionCount = parseInt($('#hidden-solutions-count').val()) - 1;
        var word = formatString(newSolutionCount, {'string':'решен', 'first':'ие', 'second':'ия', 'third':'ий'});
        var newString = newSolutionCount + ' ' + word;
        $.get($(this).attr('href'), function(response) {
            if(response.result != false) {
                link.parent().parent().parent().parent().remove();
                $('#solutions', 'ul').html(newString);
                $('#hidden-solutions-count').val(newSolutionCount);
            }
        });
        return false;
    });

    function formatString(value, strings) {
        root = strings['string'];
        if((value == 1) || (value.toString().match(/[^1]1$/))) {
            var index = 'first';
        }else if ((value >= 2) && (value <= 4)) {
            var index = 'second';
        }else {
            var index = 'third';
        }
        var string = root + strings[index];
        return string;
    }

    $('#createComment').click(function() {
        if (isCommentValid($('#newComment').val())) { // See app.js
            return true;
        }
        alert('Введите текст комментария!');
        return false;
    });

    fetchPitchComments();
    inlineActions();

    editcommentflag = false;
    $(document).keyup(function(e) {
        if (e.keyCode == 27) {
            if (editcommentflag == true) {
                e.stopPropagation();
                editcommentflag = false;
                $.each($('.hiddenform:visible'), function(index, object) {
                    var section = $(object).parent();
                    section.children().show();
                    $(object).hide();
                });
                enableToolbar();
            } else {
                e.preventDefault();
                hideSolutionPopup();
            }
        }
    });

    // Keys navigation
    $(document).keydown(function(e) {
        if ($('.solution-overlay').is(':hidden')) {
            return true;
        }
        if (e.target instanceof HTMLInputElement || e.target instanceof HTMLTextAreaElement) {
            return true;
        }
        if (e.keyCode == 37) { // left
            $('.solution-prev-area').click();
        }
        if (e.keyCode == 39) { // right
            $('.solution-next-area').click();
        }
    });

    $('.like-small-icon').click(function(){

        var likesNum = $(this).next();
        var likeLink = $(this);
        likesNum.html(parseInt(likesNum.html()));
        var sharebar = likesNum.next();
        var solutionId = $(this).data('id');
        Socialite.load($(this).next().next(), [
            $('#facebook' + solutionId)[0],
            $('#twitter' + solutionId)[0]
        ]);
        $('body').one('click',function() {
            $('.sharebar').fadeOut(300);
        });
        $.get('/solutions/like/' + $(this).data('id') + '.json', function(response) {
            likesNum.html(response.likes);
            likeLink.off('click');
            sharebar.fadeIn(300);
            likeLink.off('mouseover');
            likeLink.on('click', function() {
                $('body').one('click',function() {
                    sharebar.fadeOut(300);
                });
                sharebar.fadeIn(300);
                return false;

            });
        });
        return false;
    });

    //iframes();

    function iframes() {
        if ($('iframe').length < 2) {
            window.setTimeout(iframes, 200);
        }else {
            $('iframe').css('width', '40px').css('height', '14px');
            window.setTimeout(iframes, 200);
        }
    }


    $('.like-hoverbox').mouseenter(function(){
        $('img:first', $(this)).attr('src', '/img/like_hover.png');
    });

    $('.like-hoverbox').mouseleave( function() {
        $('img:first', $(this)).attr('src', '/img/like.png');
    });

    $('.solution-link, .number_img_gallery').click(function() {
        if(($('#newComment').val().match(/^#\d/ig) == null) && ($('#newComment').val().match(/@\W*\s\W\.,/) == null)){
            var prepend = $(this).data('commentTo') + ', ';
            var newText = prepend + $('#newComment').val();
            $('#newComment').val(newText);
        }
        return false;
    });

    $('.solution-menu-toggle').mouseover(function(){
        $('img', $(this)).attr('src', '/img/marker5_2_hover.png');
    });

    $('.solution-menu-toggle').mouseleave( function() {
        $('img', $(this)).attr('src', '/img/marker5_2.png');
    });

    $('.solution-link-menu').click(function() {
        if(($('#newComment').val().match(/^#\d/ig) == null) && ($('#newComment').val().match(/@\W*\s\W\.,/) == null)){
            var prepend = $(this).data('commentTo') + ', ';
            var newText = prepend + $('#newComment').val();
            $('#newComment').val(newText);
            $(this).parent().parent().parent().hide();
        }
        return false;
    });


    $(document).on('mouseover', '.hidedummy', function() {
        $(this).css('background-image', '')
        if($('.imagecontainer', this).children().length > 1) {
            $(this).parent().css('background-image', '')
        }
    })

    $(document).on('mouseout', '.hidedummy', function() {
        $(this).css('background-image', 'url(/img/copy-inv.png)')
        if($('.imagecontainer', this).children().length > 1) {
            $(this).parent().css('background-image', 'url(/img/copy-inv.png)')
        }
    })

    $(document).on('click', '.hide-item', function() {
        var link = $(this);
        var num = link.data('to');
        var block = link.parent().parent().parent().parent()
        var listofitems = $('.list_portfolio');
        $.get($(this).attr('href'), function(response) {
            if($('.imagecontainer', block).children().length == 1) {
                $('.imagecontainer', block).wrap('<div class="hidedummy" style="background-image: url(/img/copy-inv.png)"/>');
            }else {
                $('.photo_block', block).css('background', 'url(/img/copy-inv.png) 10px 10px no-repeat white');
            }
            $('.imagecontainer', block).css('opacity', 0.1)
            link.replaceWith('<a data-to="' + num + '" class="unhide-item" href="/solutions/unhide/' + num + '.json">Сделать видимой</a>');
            listofitems.append(block);
        })
        return false;
    })

    $(document).on('click', '.unhide-item', function() {
        var link = $(this);
        var block = link.parent().parent().parent().parent()
        var num = link.data('to')
        var listofitems = $('.list_portfolio');
        $.get($(this).attr('href'), function(response) {
            if($('.imagecontainer', block).children().length == 1) {
                var dummy = $('.hidedummy', block)
                var child = dummy.children(':first')
                child.css('opacity', 1)
                dummy.replaceWith(child)
            }else {
                $('.photo_block', block).css('background', '');
                $('.imagecontainer', block).css('opacity', 1)
            }
            $('.solution_menu', block).hide()
            link.replaceWith('<a data-to="' + num + '" class="hide-item" href="/solutions/hide/' + num + '.json">С глаз долой</a>');
        })
        return false;
    })

    $('#sort-by-rating').hover(function() {
        $('img', this).attr('src', '/img/star_press.png');
    }, function() {
        $('img', this).attr('src', '/img/star_sort.png');
    })

    $('#sort-by-likes').hover(function() {
        $('img', this).attr('src', '/img/like_press.png');
    }, function() {
        $('img', this).attr('src', '/img/like_sort.png');
    })

    $('#sort-by-created').hover(function() {
        $('img', this).attr('src', '/img/time_press.png');
    }, function() {
        $('img', this).attr('src', '/img/time_sort.png');
    })

    var cycle = {};
    $('.photo_block').on('mouseenter', function() {

        if($(this).children('.imagecontainer').children().length > 1) {
            cycle[$(this).children('.imagecontainer').data('solutionid')] = true;
            var image = $(this).children('.imagecontainer').children(':visible');
            cycleImage(image, $(this).children('.imagecontainer'));
        }
    })

    $('.photo_block').on('mouseleave', function() {
        cycle[$(this).children('.imagecontainer').data('solutionid')] = false;
    })

    function cycleImage(image, parent, prev) {
        if(cycle[parent.data('solutionid')] == true) {
            image.fadeOut(300);
            var nextImage = image.next();
            if(nextImage.length == 0) {
                nextImage = parent.children().first();
            }
            nextImage.fadeIn(300);
            setTimeout(function() {
                cycleImage(nextImage, parent, image)
            }, 1500);
            //    cycleImage(nextImage, parent, image)
            //    5000
        }
    }

    $('#client-only-toggle').change(function() {
        if($(this).attr('checked')) {
            $.each($('section[data-type="designer"]', '.messages_gallery'), function(index, object) {
                var comment = $(object);
                comment.hide();
                var separator = comment.next('.separator');
                if((separator) && (separator.length == 1)) {
                    separator.hide();
                }
            })
        }else {
            $.each($('section[data-type="designer"]', '.messages_gallery'), function(index, object) {
                var comment = $(object);
                comment.show();
                var separator = comment.next('.separator');
                if((separator) && (separator.length == 1)) {
                    separator.show();
                }
            })
        }
    })

    /*
     * View Solution Overlay
     */
    var solutionId = '';
    $('.imagecontainer').on('click', function(e) {
        e.preventDefault();
        e.stopPropagation();
        window.history.pushState('object or string', 'Title', this.href); // @todo Check params
        $('#pitch-panel').hide();
        $('.wrapper', 'body').first().addClass('wrapper-frozen');
        $.browser.chrome = /chrome/.test(navigator.userAgent.toLowerCase());
        if(!$.browser.chrome){
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
    $('.solution-overlay').on('click', function(e) {
        e.stopPropagation();
        if (!$(e.target).is('.solution-overlay')) return;
        hideSolutionPopup();
        return false;
    });
    $('.solution-title').on('click', function(e) {
        e.preventDefault();
        hideSolutionPopup();
        return false;
    });

    $('.solution-prev-area, .solution-next-area').on('mouseover', function(e) {
        $(this).prev().addClass('active');
    });
    $('.solution-prev-area, .solution-next-area').on('mouseout', function(e) {
        $(this).prev().removeClass('active');
    });
    $('.solution-prev-area, .solution-next-area').on('click', function(e) {
        e.preventDefault();
        e.stopPropagation();
        window.history.pushState('object or string', 'Title', this.href); // @todo Check params
        var urlJSON = this.href + '.json';
        fetchSolution(urlJSON);
    });

    $('#createComment', '.solution-left-panel').on('click', function(e) {
        e.preventDefault();
        if (isCommentValid($('#newComment', '.solution-left-panel').val())) { // See app.js
            $.post('/comments/add.json', {
                'text': $('#newComment', '.solution-left-panel').val(),
                'solution_id': solutionId,
                'comment_id': '',
                'pitch_id': pitchNumber,
                'fromAjax': 1
            }, function(result) {
                var commentData = {};
                var expertsObj = result.experts || {};
                commentData.commentId = result.comment.id;
                commentData.commentUserId = result.comment.user_id;
                commentData.commentText = result.comment.text;
                commentData.commentPlainText = result.comment.originalText.replace(/"/g, "\'");
                commentData.commentType = (result.comment.user_id == result.comment.pitch.user_id) ? 'client' : 'designer';
                commentData.isExpert = isExpert(result.comment.user_id, expertsObj);

                if (result.comment.pitch.user_id == result.comment.user_id) {
                    commentData.messageInfo = 'message_info2';
                } else if (result.comment.user.isAdmin == "1") {
                    commentData.messageInfo = 'message_info4';
                    commentData.isAdmin = result.comment.user.isAdmin;
                } else if (commentData.isExpert) {
                    commentData.messageInfo = 'message_info5';
                }else {
                    commentData.messageInfo = 'message_info1';
                }

                if (result.userAvatar) {
                    commentData.userAvatar = result.userAvatar;
                } else {
                    commentData.userAvatar = '/img/default_small_avatar.png';
                }

                commentData.commentAuthor = result.comment.user.first_name + ' ' + result.comment.user.last_name.substring(0, 1) + '.';
                commentData.isCommentAuthor = (currentUserId == result.comment.user_id) ? true : false;

                // Date Time
                var postDateObj = getProperDate(result.comment.created);
                commentData.postDate = ('0' + postDateObj.getDate()).slice(-2) + '.' + ('0' + (postDateObj.getMonth() + 1)).slice(-2) + '.' + ('' + postDateObj.getFullYear()).slice(-2);
                commentData.postTime = ('0' + postDateObj.getHours()).slice(-2) + ':' + ('0' + (postDateObj.getMinutes())).slice(-2);

                $('.solution-comments').prepend(populateComment(commentData));
                $('.pitch-comments section:first').prepend('<div class="separator"></div>');
                $('.new-comment-here').replaceWith(populatePitchComment(commentData));
                $('.separator', '.pitch-comments section:first').remove();
                $('#newComment', '.solution-left-panel').val('#' + result.result.solution_num + ', ');
                inlineActions();
            });
        } else {
            alert('Введите текст комментария!');
            return false;
        }
    });

    function hideSolutionPopup() {
        if ($('.solution-overlay').is(':visible')) {
            window.history.pushState('object or string', 'Title', '/pitches/view/' + pitchNumber); // @todo Check params
            $('#pitch-panel').show();
            $('.wrapper', 'body').first().removeClass('wrapper-frozen');
            $('.solution-overlay').hide();
        }
    }

    /*
     * Fetch solution via JSON and populate layout
     */
    function fetchSolution(urlJSON) {
        // Reset layout
        $(window).scrollTop(0);
        $('.solution-images').html('<div style="text-align:center;height:220px;padding-top:180px"><img alt="" src="/img/blog-ajax-loader.gif"></div>');
        $('.author-avatar').attr('src', '/img/default_small_avatar.png');
        $('.rating-image', '.solution-rating').removeClass('star0 star1 star2 star3 star4 star5');
        $('.description-more').hide();
        $('#newComment', '.solution-left-panel').val('');
        $('.solution-images').html('<div style="text-align:center;height:220px;padding-top:180px"><img alt="" src="/img/blog-ajax-loader.gif"></div>');
        $.getJSON(urlJSON, function(result) {

            // Navigation
            $('.solution-prev-area').attr('href', '/pitches/viewsolution/' + result.prev); // @todo Next|Prev unclearly
            $('.solution-next-area').attr('href', '/pitches/viewsolution/' + result.next); // @todo ¿Sorting?

            // Left Panel
            $('.solution-images').html('');
            if ((result.solution.images.solution) && (result.pitch.category_id != 7)) {
                if(typeof(result.solution.images.solution_gallerySiteSize) != 'undefined') {
                    viewsize = result.solution.images.solution_gallerySiteSize;
                    work = result.solution.images.solution_solutionView
                }else {
                    // case when we don't have gallerySiteSize image size
                    viewsize = result.solution.images.solution;
                    work = result.solution.images.solution
                }
                if ($.isArray(result.solution.images.solution)) {
                    $.each(work, function(idx, field) {
                        $('.solution-images').append('<a href="' + viewsize[idx].weburl + '" target="_blank"><img src="' + field.weburl + '" class="solution-image" /></a>');
                    });
                }else {
                    $('.solution-images').append('<a href="' + viewsize.weburl + '" target="_blank"><img src="' + work.weburl + '" class="solution-image" /></a>');
                }
            }else {
                $('.solution-images').append('<div class="preview"> \
                    <span>' + result.solution.description + '</span> \
                </div>');
            }

            var firstImage = $('.solution-image').first().parent();
            if (currentUserId == result.pitch.user_id) { // isClient
                $('<div class="separator-rating"> \
                <div class="separator-left"></div> \
                <div class="rating-widget"><span class="left">выставьте</span> \
                        <span id="star-widget"></span> \
                <span class="right">рейтинг</span></div> \
                <div class="separator-right"></div> \
                </div>').insertAfter(firstImage);
                $("#star-widget").raty({
                    path: '/img',
                    starOff: 'solution-star-off.png',
                    starOn : 'solution-star-on.png',
                    start: result.solution.rating,
                    click: function(score, evt) {
                        $.post('/solutions/rating/' + $('input[name=solution_id]').val() + '.json',
                        {"id": result.solution.id, "rating": score}, function(response) {
                            $('.rating-image', '.solution-rating').removeClass('star0 star1 star2 star3 star4 star5');
                            $('.rating-image', '.solution-rating').addClass('star' + score);
                        });
                    }
                });
            } else if (currentUserId == result.solution.user_id) {
             // Solution Author views nothing
            } else { // Any User
                var already = ''
                if(result.likes == true) {
                    already = ' already'
                }
                $('<div class="like-wrapper"><div class="left">поддержи</div> \
                   <a class="like-widget'+ already + '" data-id="' + result.solution.id + '"></a> \
                   <div class="right">автора</div></div>').insertAfter($('.solution-image').last().parent());


                $('.like-widget[data-id=' + result.solution.id + ']').click(function() {
                    $(this).toggleClass('already');
                    if($(this).hasClass('already')) {
                        var counter = $('.value-likes')
                        var solutionId = $(this).data('id')
                        counter.html(parseInt(counter.html()) + 1);
                        $.post('/solutions/like/' + solutionId + '.json', {"uid": currentUserId}, function(response) {
                        });
                    }else {
                        var counter = $('.value-likes')
                        var solutionId = $(this).data('id')
                        counter.html(parseInt(counter.html()) - 1);
                        $.post('/solutions/unlike/' + solutionId + '.json', {"uid": currentUserId}, function(response) {
                        });
                    }
                    return false
                });
            }

            $('#newComment', '.solution-left-panel').val('#' + result.solution.num + ', ');
            solutionId = result.solution.id;

            if (result.comments) {
                $('.solution-comments').html(fetchComments(result));
            }

            // Right Panel
            $('.number', '.solution-number').text(result.solution.num || '');
            $('.rating-image', '.solution-rating').addClass('star' + result.solution.rating);
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
                    $('.solution-description').text(descBefore);
                    $('.description-more').show(500);
                    $('.description-more').on('click', function() {
                        $('.solution-description').append(descAfter);
                        descAfter = '';
                        $('.description-more').hide();
                    });
                } else {
                    $('.solution-description').text(result.solution.description);
                }
                if(result.solution.description == '') {
                    $('.solution-about').next().hide();
                    $('.solution-about').hide();
                }else {
                    $('.solution-about').next().show();
                    $('.solution-about').show();
                }
            }else {
                $('.solution-description').html('');
                var html = '<div class="attach-wrapper">';
                if (result.solution.images.solution) {
                    if ($.isArray(result.solution.images.solution)) {
                        $.each(result.solution.images.solution, function(index, object) {
                            html += '<a target="_blank" href="' + object.weburl + '" class="attach">' + object.originalbasename + '</a><br>'
                        })
                    }else {
                        html += '<a href="' + result.solution.images.solution.weburl + '" class="attach">' + result.solution.images.solution.originalbasename + '</a>'
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
            }else {
                media = result.solution.images.solution_solutionView.weburl
            }
            // Twitter like solution message
            var tweetLike = 'Отличное решение на сайте GoDesigner.ru:';
            if (Math.floor((Math.random() * 100) + 1) <= 50) {
                tweetLike = 'Из всех ' + result.pitch.ideas_count + ' мне нравится этот дизайн';
            }
            $('.solution-share').html('<h2>ПОДЕЛИТЬСЯ</h2> \
                <div class="body" style="display: block;"> \
                <table width="100%"> \
                    <tbody> \
                        <tr height="35"> \
                            <td width="137" valign="middle">\
                                <a id="facebook_pop' + result.solution.id + '" class="socialite facebook-like" href="http://www.facebook.com/sharer.php?u=http://www.godesigner.ru/pitches/viewsolution/' + result.solution.id + '" data-href="http://www.godesigner.ru/pitches/viewsolution/' + result.solution.id + '" data-send="false" data-layout="button_count"> \
                                    Share on Facebook \
                                </a> \
                            </td> \
                            <td width="137" valign="middle"> \
                                <div id="vk_like" style="height: 22px; width: 100px; background-image: none; position: relative; clear: both; background-position: initial initial; background-repeat: initial initial;"><iframe name="fXD5a766" frameborder="0" src="http://vk.com/widget_like.php?app=2950889&amp;width=100%&amp;_ver=1&amp;page=0&amp;url=http%3A%2F%2Fwww.godesigner.ru%2Fpitches%2Fviewsolution%2F' + result.solution.id + '%3Fsorting%3Dcreated&amp;type=mini&amp;verb=0&amp;title=%D0%9B%D0%BE%D0%B3%D0%BE%D1%82%D0%B8%D0%BF%20%D0%BA%D0%BE%D0%BC%D0%BF%D0%B0%D0%BD%D0%B8%D0%B8%20%22%D0%9F%D1%80%D0%BE%D1%84%D0%B5%D1%81%D1%81%D0%B8%D0%BE%D0%BD%D0%B0%D0%BB%D1%8C%D0%BD%D0%B0%D1%8F%20%D0%B7%D0%B0%D1%89%D0%B8%D1%82%D0%B0%22%20%7C%20GoDesigner&amp;description=&amp;image=http%3A%2F%2Fwww.godesigner.ru%2Fsolutions%2Fd8e8955043c662a8f8144674efa995f6_galleryLargeSize.jpg&amp;text=&amp;h=22&amp;13fa381efb0" width="100%" height="22" scrolling="no" id="vkwidget1" style="overflow: hidden; height: 22px; width: 100px; z-index: 150;"></iframe></div> \
                            </td> \
                        </tr> \
                        <tr height="35"> \
                            <td valign="middle"> \
                                <a id="twitter_pop' + result.solution.id + '" class="socialite twitter-share" href="" data-url="http://www.godesigner.ru/pitches/viewsolution/' + result.solution.id + '?utm_source=twitter&utm_medium=tweet&utm_content=like-tweet&utm_campaign=sharing" data-text="' + tweetLike + '" data-lang="ru" data-hashtags="Go_Deer"> \
                                    Share on Twitter \
                                </a> \
                            </td> \
                            <td valign="middle"> \
                                <a href="//pinterest.com/pin/create/button/?url=http%3A%2F%2Fwww.godesigner.ru%2Fpitches%2Fviewsolution%2F' + result.solution.id + '&amp;media=http%3A%2F%2Fwww.godesigner.ru%2F' + media + '&amp;description=%D0%9E%D1%82%D0%BB%D0%B8%D1%87%D0%BD%D0%BE%D0%B5%20%D1%80%D0%B5%D1%88%D0%B5%D0%BD%D0%B8%D0%B5%20%D0%BD%D0%B0%20%D1%81%D0%B0%D0%B9%D1%82%D0%B5%20GoDesigner.ru" target="_blank" data-pin-log="button_pinit" data-pin-config="beside"><img src="//assets.pinterest.com/images/pidgets/pin_it_button.png" /></a> \
                            </td> \
                        </tr> \
                        <tr height="35"> \
                            <td valign="middle"><iframe frameborder="0" scrolling="no" class="surfinbird__like_iframe" src="//surfingbird.ru/button?layout=common&amp;url=http%3A%2F%2Fwww.godesigner.ru%2Fpitches%2Fviewsolution%2F' + result.solution.id + '%3Fsorting%3Dcreated&amp;caption=%D0%A1%D0%B5%D1%80%D1%84&amp;referrer=http%3A%2F%2Fwww.godesigner.ru%2Fpitches%2Fview%2F101057&amp;current_url=http%3A%2F%2Fwww.godesigner.ru%2Fpitches%2Fviewsolution%2F' + result.solution.id + '%3Fsorting%3Dcreated" style="width: 120px; height: 20px;"></iframe><a target="_blank" class="surfinbird__like_button __sb_parsed__" data-surf-config="{layout: "common", width: "120", height: 20}" href="http://surfingbird.ru/share"></a></td> \
                            <td valign="middle"><div id="___plusone_0" style="text-indent: 0px; margin: 0px; padding: 0px; background-color: transparent; border-style: none; float: none; line-height: normal; font-size: 1px; vertical-align: baseline; display: inline-block; width: 106px; height: 24px; background-position: initial initial; background-repeat: initial initial;"><iframe frameborder="0" hspace="0" marginheight="0" marginwidth="0" scrolling="no" style="position: static; top: 0px; width: 106px; margin: 0px; border-style: none; left: 0px; visibility: visible; height: 24px;" tabindex="0" vspace="0" width="100%" id="I0_1372837769255" name="I0_1372837769255" src="https://apis.google.com/_/+1/fastbutton?bsv&amp;hl=ru&amp;origin=http%3A%2F%2Fwww.godesigner.ru&amp;url=http%3A%2F%2Fwww.godesigner.ru%2Fpitches%2Fviewsolution%2F' + result.solution.id + '%3Fsorting%3Dcreated&amp;ic=1&amp;jsh=m%3B%2F_%2Fscs%2Fapps-static%2F_%2Fjs%2Fk%3Doz.gapi.ru.fjfk_NiG5Js.O%2Fm%3D__features__%2Fam%3DEQ%2Frt%3Dj%2Fd%3D1%2Frs%3DAItRSTPAdsSDioxjaY0NLzoPJdX-TT1dfg#_methods=onPlusOne%2C_ready%2C_close%2C_open%2C_resizeMe%2C_renderstart%2Concircled%2Conload&amp;id=I0_1372837769255&amp;parent=http%3A%2F%2Fwww.godesigner.ru&amp;pfname=&amp;rpctoken=95865586" allowtransparency="true" data-gapiattached="true" title="+1"></iframe></div></td> \
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

            if (currentUserId == result.pitch.user_id) {
                var html = '<a class="abuse warning" href="/solutions/warn/' + result.solution.id + '.json" data-solution-id="' + result.solution.id + '">Пожаловаться</a>';
                if (result.solution.hidden == 1) {
                    html += '<a class="client-show" href="#" data-id="' + result.solution.id + '">Показать</a>';
                }else {
                    html += '<a class="client-hide" href="#" data-id="' + result.solution.id + '">Скрыть</a>';
                }
                $('.solution-abuse').html(html);
            }else if((currentUserId == result.solution.user_id) || isCurrentAdmin) {
                $('.solution-abuse').html('<a class="abuse warning" href="/solutions/warn/' + result.solution.id + '.json" data-solution-id="' + result.solution.id + '">Пожаловаться</a> \
                    <a class="delete-solution-popup hide" data-solution="' + result.solution.id + '" href="/solutions/delete/' + result.solution.id + '.json">Удалить</a>');
            }else {
                $('.solution-abuse').html('<a class="abuse warning" href="/solutions/warn/' + result.solution.id + '.json" data-solution-id="' + result.solution.id + '">Пожаловаться</a>');
            }
            inlineActions();
            Socialite.load($('.solution-share'), [
                                                  $('#facebook_pop' + result.solution.id)[0],
                                                  $('#twitter_pop' + result.solution.id)[0]
                                              ]);
        });
    }
});

/*
 * Fetch and populate Comments via AJAX on the pitch solutions gallery page
 */
function fetchPitchComments() {
    $.getJSON('/pitches/getcomments/' + pitchNumber + '.json', function(result) {
        if (result.comments) {
            var commentsTitle = '<div class="separator" style="width: 810px; margin-left: 30px; margin-top: 25px;"></div> \
                                <div class="comment" style="width:35%;float:left;">КОММЕНТАРИИ</div> \
                                <div class="checkbox-input" style="margin-right:45px;"><input type="checkbox" id="client-only-toggle" style="font-size:14px;vertical-align: text-top;" /> <span class="supplement">показывать только комментарии заказчика</span></div> \
                                <div style="clear:both;"></div> \
                                <div class="new-comment-here"></div>';
            $('.pitch-comments').html(fetchComments(result));
            $('.pitch-comments').prepend(commentsTitle);
            $('.separator', '.pitch-comments section:first').remove();

            inlineActions();
        } else {
            $('.ajax-loader', '.pitch-comments').remove();
        }
    });
}

/*
 * Comments In Pitch View
 */
function populatePitchComment(data) {
    var toolbar = '';
    var manageToolbar = '<a href="/comments/delete/' + data.commentId + '" style="float:right;" class="delete-link-in-comment ajax">Удалить</a> \
                        <a href="#" style="float:right;" class="edit-link-in-comment" data-id="' + data.commentId + '" data-text="' + data.commentPlainText + '">Редактировать</a>';
    var userToolbar = '<a href="#" data-comment-id="' + data.commentId + '" data-comment-to="' + data.commentAuthor + '" class="replyto reply-link-in-comment" style="float:right;">Ответить</a> \
                      <a href="#" data-comment-id="' + data.commentId + '" data-url="/comments/warn.json" class="warning-comment warn-link-in-comment" style="float:right;">Пожаловаться</a>';
    if (data.isCommentAuthor) {
        toolbar = manageToolbar;
    } else if (currentUserId) {
        toolbar = userToolbar;
    }
    if (isCurrentAdmin == 1) {
        toolbar = manageToolbar + userToolbar;
    }
    var avatarElement = '';
    if (!data.isAdmin) {
        avatarElement = '<a href="/users/view/' + data.commentUserId + '"> \
                        <img src="' + data.userAvatar + '" alt="Портрет пользователя" width="41" height="41"> \
                        </a>';
    }
    return '<div class="new-comment-here"></div> \
            <section data-id="' + data.commentId + '" data-type="' + data.commentType + '"> \
                <div class="separator"></div> \
                <div class="' + data.messageInfo + '" style="margin-top:20px;">'
                + avatarElement +
                '<a href="#" data-comment-id="' + data.commentId + '" data-comment-to="' + data.commentAuthor + '" class="replyto"> \
                    <span>' + data.commentAuthor + '</span><br /> \
                    <span style="font-weight: normal;">' + data.postDate + ' ' + data.postTime + '</span> \
                </a> \
                <div class="clr"></div> \
                </div> \
                <div data-id="' + data.commentId + '" class="message_text" style="margin-top:15px;"> \
                    <span class="regular comment-container">'
                        + data.commentText +
                    '</span> \
                </div> \
                <div style="width:810px;float:right;margin-top: 6px;margin-right: 95px;padding-bottom: 2px;height:18px;"> \
                    <div class="toolbar" style="display: none;">'
                    + toolbar +
                '</div></div> \
                <div class="clr"></div> \
                <div class="hiddenform" style="display:none"> \
                    <section> \
                        <form style="margin-bottom: 25px;" action="/comments/edit/' + data.commentId + '" method="post"> \
                            <textarea name="text" data-id="' + data.commentId + '"></textarea> \
                            <input type="button" src="/img/message_button.png" value="Отправить" class="button editcomment" style="margin-left:16px;margin-bottom:5px; width: 200px;"><br> \
                            <span style="margin-left:25px;" class="supplement3">Нажмите Esс, чтобы отменить</span> \
                            <div class="clr"></div> \
                        </form> \
                    </section> \
                </div> \
            </section>';
}

/*
 * Various actions running after DOM rebuild
 */
function inlineActions() {


    $('.edit-link-in-comment').click(function(e) {
        e.preventDefault();
        var section = $(this).parent().parent().parent();
        section.children().not('.separator').hide();
        var hiddenform = $('.hiddenform', section);
        hiddenform.show();
        var text = $(this).data('text');
        $('textarea', hiddenform).val(text);
        editcommentflag = true;
        return false;
    });

    $('.editcomment').click(function() {
        var textarea = $(this).prev();
        var newcomment = textarea.val();
        var id = textarea.data('id');
        $.post('/comments/edit/' + id + '.json', {"text": newcomment}, function(response) {
            var newText = response;
            var section = textarea.parent().parent().parent().parent();
            $('.edit-link-in-comment', 'section[data-id=' + id + ']').data('text', newcomment);
            $('.comment-container', 'section[data-id=' + id + ']').html(newText);
            section.children().show();
            $('.hiddenform', section).hide();
            editcommentflag = false;
            inlineActions();
        });
        return false;
    });

    $('.replyto').click(function() {
        var el = $('#newComment');
        var anchor = $('#comment-anchor');
        if ($('.allow-comments').is(':visible')) {
            el = $('#newComment', '.allow-comments');
            anchor = $('.allow-comments');
        }
        if((el.val().match(/^#\d/ig) == null) && (el.val().match(/@\W*\s\W\.,/) == null)){
            $('input[name=comment_id]').val($(this).data('commentId'));
            var prepend = '@' + $(this).data('commentTo') + ', ';
            var newText = prepend + el.val();
            el.val(newText);
            $.scrollTo(anchor, {duration:250});
        }
        return false;
    });

    $('.createCommentForm').click(function() {
        var position = $(this).offset();
        position.top -= 115;
        //$('#tooltip-bubble', $(this)).css(position).fadeIn(200);
    });

    $('textarea').blur(function() {
        $($(this).prev('#tooltip-bubble')).fadeOut(200);
    });

    $('textarea').keydown(function() {
        $($(this).prev('#tooltip-bubble')).fadeOut(200);
    });

    $('.hoverimage[data-comment-to]').tooltip({
        tooltipID: 'tooltip2',
        tooltipSource: 'rel',
        width: '205px',
        correctPosX: 40,
        //positionTop: 0,
        borderSize: '0px',
        tooltipPadding: 0,
        tooltipBGColor: 'transparent'
    });

    $('.delete-solution-popup').on('click', function(e) {
        e.preventDefault();
        if (confirm('Действительно удалить решение?')) {
            hideSolutionPopup();
            $('.delete-solution[data-solution="' + $(this).data('solution') + '"]').click();
        }
    });

    function hideSolutionPopup() {
        if ($('.solution-overlay').is(':visible')) {
            window.history.pushState('object or string', 'Title', '/pitches/view/' + pitchNumber); // @todo Check params
            $('#pitch-panel').show();
            $('.wrapper', 'body').first().removeClass('wrapper-frozen');
            $('.solution-overlay').hide();
        }
    }

    enableToolbar();
    mentionLinks();
    solutionShowHide();
    warningModal();
}

/*
 * Enable Comment-to Action
 */
function mentionLinks() {
    $('.mention-link').click(function(e) {
        e.preventDefault();
        var el = $('#newComment');
        if ($('.allow-comments').is(':visible')) {
            el = $('#newComment', '.allow-comments');
        }
        if((el.val().match(/^#\d/ig) == null) && (el.val().match(/@\W*\s\W\.,/) == null)) {
            $('input[name=comment_id]').val('');
            var prepend = '@' + $(this).data('commentTo') + ', ';
            var newText = prepend + el.val();
            el.val(newText);
        }
        return false;
    });
}
