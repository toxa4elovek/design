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

    $('.edit-link-in-comment').click(function(){
        var section = $(this).parent().parent().parent();
        section.children().hide();
        var hiddenform = $('.hiddenform', section);
        hiddenform.show();
        var text = $(this).data('text');
        $('textarea', hiddenform).val(text);
        editcommentflag = true;
        return false;
    })

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
        })
        return false;
    })

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

    $('.mention-link').click(function() {
        if(($('#newComment').val().match(/^#\d/ig) == null) && ($('#newComment').val().match(/@\W*\s\W\.,/) == null)){
            $('input[name=comment_id]').val('');
            var prepend = '@' + $(this).data('commentTo') + ', ';
            var newText = prepend + $('#newComment').val();
            $('#newComment').val(newText);
        }
        return false;
    });

    $(document).on('click', '.select-winner', function() {
        $('#confirmWinner').data('url', $(this).attr('href'));
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

    $(document).on('click', '.warning', function() {
        $('#sendWarn').data('url', $(this).attr('href'));
        $('#popup-warning').modal({
            containerId: 'final-step',
            opacity: 80,
            closeClass: 'popup-close'
        });
        return false;
    });

    $(document).on('click', '#sendWarn', function() {
        var url = $(this).data('url');
        if($('#warn-solution').val().length > 0) {
            $.post(url, {"text": $('#warn-solution').val()}, function(response) {
                $('.popup-close').click()
            });
        }
    });


    $(document).on('click', '.warning-comment', function() {
        $('#sendWarnComment').data('url', $(this).data('url'));
        $('#sendWarnComment').data('commentId', $(this).data('commentId'));
        $('#popup-warning-comment').modal({
            containerId: 'final-step',
            opacity: 80,
            closeClass: 'popup-close'
        });
        return false;
    });

    $(document).on('click', '#sendWarnComment', function() {
        var url = $(this).data('url');
        var id = $(this).data('commentId');
        if($('#warn-comment').val().length > 0) {
            $.post(url, {"text": $('#warn-comment').val(), "id": id}, function(response) {
                $('.popup-close').click()
            });
        }
    });

    $('section', '.messages_gallery').hover(function() {
        $('.toolbar', this).fadeIn(150);
    }, function() {
        $('.toolbar', this).fadeOut(150);
    });


    $(document).on('click', '.delete-solution', function() {
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
})