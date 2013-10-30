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
    // gplus
    window.___gcfg = {lang: 'ru'};

    (function() {
        var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
        po.src = 'https://apis.google.com/js/plusone.js';
        var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
    })();

    var editcommentflag = false;

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
                $('.edit-link-in-comment', section).data('text', newcomment);
                $('.comment-container', section).html(newText);
                section.children().show();
                $('.hiddenform', section).hide();
                editcommentflag = false;
                enableToolbar();
            })
            return false;
        });

        $('.replyto').click(function() {
            if ($('.allow-comments').is(':visible')) {
                var el = $('#newComment', '.allow-comments');
                var anchor = $('.allow-comments');
            } else {
                var el = $('#newComment');
                var anchor = $('#comment-anchor');
            }
            if((el.val().match(/^#\d/ig) == null) && (el.val().match(/@\W*\s\W\.,/) == null)){
                $('input[name=comment_id]').val($(this).data('commentId'))
                var prepend = '@' + $(this).data('commentTo') + ', ';
                var newText = prepend + el.val();
                el.val(newText);
                $.scrollTo(anchor, {duration:250});
            }
            return false;
        });

        $('.client-comment').click(function() {
            $.scrollTo($('#newComment', '.allow-comments'), {duration:250});
            return false;
        });

        $('.select-winner-popup').click(function() {
            $('#winner-num').text('#' + $(this).data('num'));
            $('#winner-num').attr('href', '/pitches/viewsolution/' + $(this).data('solutionid'));
            $('#winner-user-link').text($(this).data('user'));
            $('#winner-user-link').attr('href', '/users/view/' + $(this).data('userid'));
            $('#confirmWinner').data('url', $(this).attr('href'));
            $('#popup-final-step').modal({
                containerId: 'final-step',
                opacity: 80,
                closeClass: 'popup-close'
            });
            return false;
        });

        $('.mention-link').click(function() {
            if(($('#newComment').val().match(/^#\d/ig) == null) && ($('#newComment').val().match(/@\W*\s\W\.,/) == null)){
                $('input[name=comment_id]').val('');
                var prepend = '@' + $(this).data('commentTo') + ', ';
                var newText = prepend + $('#newComment').val();
                $('#newComment').val(newText);
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
            tooltipSource: 'hidden',
            width: '205px',
            correctPosX: 40,
            //positionTop: 0,
            borderSize: '0px',
            tooltipPadding: 0,
            tooltipBGColor: 'transparent'
        });

        solutionShowHide();
        warningModal();
    }

    $(document).keyup(function(e) {

        if ((e.keyCode == 27) && (editcommentflag == true)) {
            editcommentflag = false;
            $.each($('.hiddenform:visible'), function(index, object) {
                var section = $(object).parent();
                section.children().show();
                $(object).hide();
            })
        }
    });

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

    $('#newComment').click(function() {
        var position = $(this).offset();
        position.top -= 360;
        position.left -= 560;
        $('#tooltip-bubble').css(position).fadeIn(200);
    });

    $('textarea').blur(function() {
        $('#tooltip-bubble').fadeOut(200);
    });

    $('textarea').keydown(function() {
        $('#tooltip-bubble').fadeOut(200);
    });

    //$('form').keypress(function(e){
        //if ( e.which == 13 ) e.preventDefault();
    //});


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

    $('.replyto').click(function() {
        if(($('#newComment').val().match(/^#\d/ig) == null) && ($('#newComment').val().match(/@\W*\s\W\.,/) == null)){
            $('input[name=comment_id]').val($(this).data('commentId'))
            var prepend = '@' + $(this).data('commentTo') + ', ';
            var newText = prepend + $('#newComment').val();
            $.scrollTo($('#comment-anchor'), {duration:250});
            $('#newComment').val(newText);
        }
        return false;
    })

    $('#createComment').click(function() {
        if (isCommentValid($('#newComment').val())) { // See app.js
            return true;
        }
        alert('Введите текст комментария!');
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

    $(document).on('click', '.client-hide', function() {
        var link = $(this)
        $.get('/solutions/hide/' + $(this).data('id') + '.json', function(response) {
            link.replaceWith('<a class="client-show" href="#" data-id="' + link.data('id') + '">Показать</a>')
        })
        return false;
    })

    $(document).on('click', '.client-show', function() {
        var link = $(this)
        $.get('/solutions/unhide/' + $(this).data('id') + '.json', function(response) {
            link.replaceWith('<a class="client-hide" href="#" data-id="' + link.data('id')  + '">Скрыть</a>')
        })
        return false;
    })
    
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
        $.getJSON(urlJSON, function(result) {

            // Navigation
            $('.solution-prev-area').attr('href', '/pitches/viewsolution/' + result.prev); // @todo Next|Prev unclearly
            $('.solution-next-area').attr('href', '/pitches/viewsolution/' + result.next); // @todo ¿Sorting?
            $('.solution-images').html('');
            // Left Panel
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
            }
            
            $('#newComment', '.solution-left-panel').val('#' + result.solution.num + ', ');
            solutionId = result.solution.id;
            
            if (result.comments) {
                $('.solution-comments').html(fetchComments(result));
                
                enableToolbar();

                $('.delete-link-in-comment.ajax').on('click', function(e) {
                    e.preventDefault();
                    var section = $(this).parent().parent().parent();
                    $.post($(this).attr('href') + '.json', function(result) {
                        if (result == 'true') {
                            section.remove();
                        }
                    });
                });
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
                    descBefore = descBefore.substr(0, Math.min(descBefore.length, descBefore.lastIndexOf(" ")))
                    var descAfter = desc.slice(descBefore.length);
                    $('.solution-description').html(descBefore);
                    $('.description-more').show(500);
                    $('.description-more').on('click', function() {
                        $('.solution-description').append(descAfter);
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
            } else {
                $('.solution-description').html('');
                var html = '<div class="attach-wrapper">';
                if (result.solution.images.solution) {
                    if ($.isArray(result.solution.images.solution)) {
                        $.each(result.solution.images.solution, function(index, object) {
                            html += '<a target="_blank" href="' + object.weburl + '" class="attach">' + object.originalbasename + '</a><br>'
                        })
                    }else {
                        html = '<a href="' + result.solution.images.solution.weburl + '" class="attach">' + result.solution.images.solution.originalbasename + '</a>'
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
                var html = '<a class="abuse warning" href="/solutions/warn/' + result.solution.id + '.json" data-solution-id="' + result.solution.id + '">Пожаловаться</a>';
                if (result.solution.hidden == 1) {
                    html += '<a class="client-show" href="#" data-id="' + result.solution.id + '">Показать</a>';
                }else {
                    html += '<a class="client-hide" href="#" data-id="' + result.solution.id + '">Скрыть</a>';
                }
                if (result.selectedsolution != true) {
                    html += '<a class="select-winner-popup" href="/solutions/select/' + result.solution.id + '.json" data-solutionid="' + result.solution.id + '" data-user="' + result.solution.user.first_name + ' ' + result.solution.user.last_name.substring(0, 1) + '." data-num="' + result.solution.num + '" data-userid="' + result.solution.user_id + '">Назначить победителем</a>';
                }
                html += '<a class="client-comment" href="#">Комментировать</a>';
                $('.solution-abuse').html(html);
            }else if((currentUserId == result.solution.user_id) || isCurrentAdmin) {
                $('.solution-abuse').html('<a class="abuse warning" href="/solutions/warn/' + result.solution.id + '.json" data-solution-id="' + result.solution.id + '">Пожаловаться</a> \
                    <a class="hide" href="/solutions/delete/' + result.solution.id + '.json">Удалить</a>');
            }else {
                $('.solution-abuse').html('<a class="abuse warning" href="/solutions/warn/' + result.solution.id + '.json" data-solution-id="' + result.solution.id + '">Пожаловаться</a>');
            }
            
            inlineActions();
            Socialite.load($('.solution-share'), [
                                                  $('#facebook' + result.solution.id)[0],
                                                  $('#twitter' + result.solution.id)[0]
                                              ]);

        });
    }
});
