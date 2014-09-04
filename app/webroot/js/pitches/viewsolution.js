$(document).ready(function() {

    //vk like
    VK.init({apiId: 2950889, onlyWidgets: true});
    VK.Widgets.Like("vk_like", {type: "mini"});

    function preload(arrayOfImages) {
        $(arrayOfImages).each(function(){
            $('<img/>')[0].src = this;
            // Alternatively you could use:
            // (new Image()).src = this;
        });
    }
    if(typeof(fileSet) != 'undefined') {
        preload(fileSet);
    }

    // gplus
    window.___gcfg = {lang: 'ru'};

    (function() {
        var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
        po.src = 'https://apis.google.com/js/plusone.js';
        var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
    })();

    var editcommentflag = false;

	$('#like').click(function(event){
        event.stopPropagation();
        $('body').one('click',function() {
            $('#sharebar').fadeOut(300);
        });
        if((typeof($('input[name=solution_id]').val()) == 'undefined') || ($('input[name=solution_id]').val() == '')) {
            $('#sharebar').fadeIn(300);
            return false;
        }
		$.get('/solutions/like/' + $('input[name=solution_id]').val() + '.json', function(response) {
			$('#like-count').html(response.likes);
            $('#sharebar').fadeIn(300);
            $('#like').off('click');
            $('#like').off('mouseover');
            $('#like').on('click', function() {
                $('body').one('click',function() {
                    $('#sharebar').fadeOut(300);
                });
                $('#sharebar').fadeIn(300);
                 return false;

            });
		});
		return false;
	});

    $('#solution-menu-toggle').mouseover(function(){
        $('img', $(this)).attr('src', '/img/big-arrow-hover.png');
    });

    $('#solution-menu-toggle').mouseleave( function() {
        $('img', $(this)).attr('src', '/img/big-arrow.png');
    });

    $('#solution-menu-toggle').click(function() {
        $('body').one('click',function() {
            $('.solution_menu').hide();
        });
        $('.solution_menu').toggle();
        return false;
    });

    /*$('html').click(function() {
        $('#sharebar').hide();
    }); */

    $('#like').mouseover(function(){
        $('img', $(this)).attr('src', '/img/likes_hover.png');
    });

    $('#like').mouseleave( function() {
        $('img', $(this)).attr('src', '/img/likes.png');
    });

    if($('li', '.group').length > 6) {
        //Большая карусель
        $('#big_carousel').jCarouselLite({
            auto: 5000,
            speed: 100,
            btnPrev: "#prev",
            btnNext: "#next",
            visible: 6
        });
    }else {
        $('#prev, #next').click(function() {
            return false;
        });
    }

    $('#prevsol').mouseover(function() {
        $('img', this).attr('src', '/img/arrow_solution_prev_hover.png');
    })

    $('#prevsol').mouseout(function() {
        $('img', this).attr('src', '/img/arrow_solution_left.png');
    })

    $('#nextsol').mouseover(function() {
        $('img', this).attr('src', '/img/arrow_solution_next_hover.png');
    })

    $('#nextsol').mouseout(function() {
        $('img', this).attr('src', '/img/arrow_solution_right.png');
    })

	if($('input[name=user_id]').val() == $('input[name=pitch_user_id]').val()) {
		$('#rating').raty({
			path: '/img',
            hintList: ['не то!', 'так себе', 'возможно', 'хорошо', 'отлично'],
			starOn:   'rating-plus.png',
	  		starOff:  'rating-null.png',
	  		start: $('input[name=rating]').val(),
	  		click: function(score, evt) {
	  			$.post('/solutions/rating/' + $('input[name=solution_id]').val() + '.json', 
	  			{"id": $('input[name=solution_id]').val(), "rating": score}, function(response) {
	  				
	  			});
	  	}});
	}else {
		$('#rating').raty({
			path: '/img',
			starOn:   'rating-plus.png',
	  		starOff:  'rating-null.png',
            hintList: ['не то!', 'так себе', 'возможно', 'хорошо', 'отлично'],
	  		readOnly: true,
	  		start: $('input[name=rating]').val()
		});  		
	}
	//$('.preview').fancybox();
	//$('#rating').raty('click', 2);


    $('.solution-link, .number_img_gallery').click(function() {
        if(($('#newComment').val().match(/^#\d/ig) == null) && ($('#newComment').val().match(/@\W*\s\W\.,/) == null)){
            var prepend = $(this).data('commentTo') + ', ';
            var newText = prepend + $('#newComment').val();
            $('#newComment').val(newText);
        }
        return false;
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

    $(document).on('click', '#confirmWinner', function() {
        var url = $(this).data('url');
        $.get(url, function(response) {
            if(response.result != false) {
                if(response.result.nominated) {
                    window.location = '/users/nominated';
                }
            }
        });
    });

    $('section', '.messages_gallery').hover(function() {
        $('.toolbar', this).fadeIn(150);
    }, function() {
        $('.toolbar', this).fadeOut(150);
    });
    
    // Delete Solution
    $(document).on('click', '.delete-solution', function() {
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
            onShow: function() {
                $('#model_id', '#popup-delete-solution').val(link.data('solution'));
                link.attr('data-pressed', 'on');
                $(document).on('click', '.popup-close', function() {
                    link.attr('data-pressed', 'off');
                });
                $(document).off('click', '#sendDeleteSolution');
            }
        });
        
        // Delete Solution Popup Form
        $(document).on('click', '#sendDeleteSolution', function() {
            var form = $(this).parent().parent();
            if (!$('input[name=reason]:checked', form).length || !$('input[name=penalty]:checked', form).length) {
                $('#popup-delete-solution').addClass('wrong-input');
                return false;
            }
            var $spinner = $(this).next();
            $spinner.addClass('active');
            $(document).off('click', '#sendDeleteSolution');
            var data = form.serialize();
            $.post(form.attr('action') + '.json', data).done(function(result) {
                link.click();
            });
            return false;
        });
        return false;
    });
    
    $(document).on('click', '.hide', function() {
        var link = $(this);
        var newSolutionCount = parseInt($('#hidden-solutions-count').val()) - 1;
        var word = formatString(newSolutionCount, {'string':'решен', 'first':'ие', 'second':'ия', 'third':'ий'});
        var newString = newSolutionCount + ' ' + word;
        $.get($(this).attr('href'), function(response) {
            /*if(response.result != false) {
                link.parent().parent().parent().parent().remove();
                $('#solutions', 'ul').html(newString);
                $('#hidden-solutions-count').val(newSolutionCount);
            }*/
            window.location = '/pitches/view/' + $('input[name="pitch_id"]').val();
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

    $('input[name=""]').click(function() {
        if(($('#newComment').val() == '') || ($('#newComment').val().match(/#(\d)+,(\s)+?$/))) {
            alert('Введите текст комментария!');
            return false;
        }
        return true;
    })

    if(typeof(fileSet) != 'undefined') {
        var currentIndex = 0;
        $('.preview').hover(function() {
            $('#image').animate({
                opacity: 0.5,
              }, 300);
            $('#left, #right, #zoom').show();
        }, function() {
            $('#image').animate({
                opacity: 1,
              }, 300);
            $('#left, #right, #zoom').hide();
        });

        $('#left').hover(function() {
            $(this).css('opacity', '0.2');
            $('#right, #zoom').css('opacity', '0');
        }, function(){
            $(this).css('opacity', '0');
        })

        $('#right').hover(function() {
            $(this).css('opacity', '0.2');
            $('#left, #zoom').css('opacity', '0');
        }, function(){
            $(this).css('opacity', '0');
        })

        $('#zoom').hover(function() {
            $(this).css('opacity', '0.2');
            $('#left, #right').css('opacity', '0');
        }, function(){
            $(this).css('opacity', '0');
        })

        var total = fileSet.length;
        $('#left').click(function() {
            if(total == fileSet.length ) {
                total = 1;
            }else {
                total = total + 1;
            }
            $('#img-num-counter').text(total);
            currentIndex -=  1;
            if(currentIndex < 0) {
                currentIndex = (fileSet.length - 1);
            }
            $('#image').attr('src', fileSet[currentIndex]);
            $('#zoom').attr('href', zoomFileSet[currentIndex]);
            return false;
        })

        $('#right').click(function() {
            total = total - 1;
            if(total == 0 ) {
                total = fileSet.length;
            }
            $('#img-num-counter').text(total);
            currentIndex +=  1;
            if(currentIndex == fileSet.length) {
                currentIndex = 0;
            }
            $('#image').attr('src', fileSet[currentIndex]);
            $('#zoom').attr('href', zoomFileSet[currentIndex]);
            return false;
        })

    }

    /*
     * View solution via json
     */
    if (allowComments) {
        $('.allow-comments', '.solution-left-panel').show();
    }
    if (document.URL.indexOf('?') != -1) {
        var queryParam = document.URL.slice(document.URL.indexOf('?'));
    } else {
        var queryParam = '';
    }
    var urlJSON = window.location.pathname + '.json' + queryParam;
    fetchSolution(urlJSON);
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
        solutionThumbnail = '';
        $.getJSON(urlJSON, function(result) {

            // Navigation
            $('.solution-prev-area').attr('href', '/pitches/viewsolution/' + result.prev); // @todo Next|Prev unclearly
            $('.solution-next-area').attr('href', '/pitches/viewsolution/' + result.next); // @todo ¿Sorting?
            $('.solution-images').html('');
            // Left Panel
            if ((result.solution.images.solution) && (result.pitch.category_id != 7)) {
                // Main Images
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
                // Thumbnail Image
                if(typeof(result.solution.images.solution_galleryLargeSize) != 'undefined') {
                    viewsize = result.solution.images.solution_galleryLargeSize;
                }else {
                    // case when we don't have gallerySiteSize image size
                    viewsize = result.solution.images.solution;
                }
                if ($.isArray(viewsize)) {
                    solutionThumbnail = viewsize[0].weburl;
                }else {
                    solutionThumbnail = viewsize.weburl;
                }
            }else {
                $('.solution-images').append('<div class="preview"> \
                    <span>' + result.solution.description + '</span> \
                </div>');
            }

            var firstImage = $('.solution-image').first().parent();
            if (currentUserId == result.pitch.user_id) { // isClient
                var ratingWidget = $('<div class="separator-rating"> \
                    <div class="separator-left"></div> \
                    <div class="rating-widget"><span class="left">выставьте</span> \
                            <span id="star-widget"></span> \
                    <span class="right">рейтинг</span></div> \
                    <div class="separator-right"></div> \
                    </div>');
                if(firstImage.length > 0){
                    ratingWidget.insertAfter(firstImage);
                }else {
                    ratingWidget.insertAfter('.preview');
                    $('.separator-rating').css({"margin-top": "20px", "margin-bottom": "20px"});
                }
                $("#star-widget").raty({
                    path: '/img',
                    hintList: ['не то!', 'так себе', 'возможно', 'хорошо', 'отлично'],
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
                if ((result.pitch.status != 1) && (result.pitch.status != 2)) {
                    var already = ''
                    if(result.likes == true) {
                        already = ' already'
                    }

                    $('<div class="like-wrapper"><div class="left">поддержи</div> \
                       <div class="like-widget' + already + '" data-id="' + result.solution.id + '"></div> \
                       <div class="right">автора</div></div>').insertAfter($('.solution-image').last().parent());

                    $('.like-widget[data-id=' + result.solution.id + ']').click(function() {
                        $(this).toggleClass('already');
                        if($(this).hasClass('already')) {
                            var counter = $('.value-likes')
                            var solutionId = $(this).data('id')
                            $.post('/solutions/like/' + solutionId + '.json', {"uid": currentUserId}, function(response) {
                                counter.html(parseInt(response.likes));
                            });
                        }else {
                            var counter = $('.value-likes')
                            var solutionId = $(this).data('id')
                            $.post('/solutions/unlike/' + solutionId + '.json', {"uid": currentUserId}, function(response) {
                                counter.html(parseInt(response.likes));
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
                    descBefore = descBefore.substr(0, Math.min(descBefore.length, descBefore.lastIndexOf(" ")))
                    var descAfter = desc.slice(descBefore.length);
                    $('.solution-description').html(descBefore);
                    $('.description-more').show(500);
                    $('.description-more').on('click', function() {
                        $('.solution-description').append(descAfter);
                        $('.description-more').hide();
                    });
                } else {
                    $('.solution-description').html(result.solution.description);
                }
                if(result.solution.description != '') {
                    $('span#date').after('<br />');
                }
            } else {
                $('.solution-description').html('');
                var html = '<div class="attach-wrapper">';
                if (result.solution.images.solution) {
                    if ($.isArray(result.solution.images.solution)) {
                        $.each(result.solution.images.solution, function(index, object) {
                            html += '<a target="_blank" href="/solutionfiles' + object.weburl + '" class="attach">' + object.originalbasename + '</a><br>'
                        })
                    }else {
                        html = '<a href="/solutionfiles' + result.solution.images.solution.weburl + '" class="attach">' + result.solution.images.solution.originalbasename + '</a>'
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

            if (currentUserId == result.pitch.user_id) {
                var html = '';
                if (result.solution.hidden == 1) {
                    html += '<a class="client-show" href="#" data-id="' + result.solution.id + '">Сделать видимой</a>';
                }else {
                    html += '<a class="client-hide" href="#" data-id="' + result.solution.id + '">С глаз долой</a>';
                }
                html += '<a class="client-comment" href="#">Комментировать</a>';
                html += '<a class="abuse warning" href="/solutions/warn/' + result.solution.id + '.json" data-solution-id="' + result.solution.id + '">Пожаловаться</a>';
                if (result.selectedsolution != true) {
                    html += '<a class="select-winner-popup" href="/solutions/select/' + result.solution.id + '.json" data-solutionid="' + result.solution.id + '" data-user="' + result.solution.user.first_name + ' ' + result.solution.user.last_name.substring(0, 1) + '." data-num="' + result.solution.num + '" data-userid="' + result.solution.user_id + '">Назначить победителем</a>';
                }
                $('.solution-abuse').html(html);
            }else if((currentUserId == result.solution.user_id) || isCurrentAdmin) {
                $('.solution-abuse').html('<a class="abuse warning" href="/solutions/warn/' + result.solution.id + '.json" data-solution-id="' + result.solution.id + '">Пожаловаться</a> \
                    <a class="delete-solution" href="/solutions/delete/' + result.solution.id + '" data-solution="' + result.solution.id + '">Удалить</a>');
            }else {
                $('.solution-abuse').html('<a class="abuse warning" href="/solutions/warn/' + result.solution.id + '.json" data-solution-id="' + result.solution.id + '">Пожаловаться</a>');
            }
            
            enableToolbar();
            Socialite.load($('.solution-share'), [
                                                  $('#facebook' + result.solution.id)[0],
                                                  $('#twitter' + result.solution.id)[0]
                                              ]);

        });
    }
});
