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

    $('#createCommentForm').click(function() {
        var position = $(this).offset();
        position.top -= 115;
        $('#tooltip-bubble').css(position).fadeIn(200);
    });

    $('textarea').blur(function() {
        $('#tooltip-bubble').fadeOut(200);
    });

    $('textarea').keydown(function() {
        $('#tooltip-bubble').fadeOut(200);
    });

    $('.hoverimage[data-comment-to]').tooltip({
        tooltipID: 'tooltip',
        tooltipSource: 'rel',
        width: '200px',
        correctPosX: 40,
        //positionTop: 0,
        borderSize: '0px',
        tooltipPadding: 0,
        tooltipBGColor: 'transparent'
    })
  
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
        if(($('#warn-solution').val().length > 0) && ($('#warn-comment').val() != 'ВАША ЖАЛОБА')) {
            $.post(url, {"text": $('#warn-solution').val()}, function(response) {
                $('.popup-close').click()
            });
        }else {
            alert('Введите текст жалобы!');
        }
    });

    $('section', '.messages_gallery').hover(function() {
        $('.toolbar', this).fadeIn(150);
    }, function() {
        $('.toolbar', this).fadeOut(150);
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
        if(($('#warn-comment').val().length > 0) && ($('#warn-comment').val() != 'ВАША ЖАЛОБА')) {
            $.post(url, {"text": $('#warn-comment').val(), "id": id}, function(response) {
                $('.popup-close').click()
            });
        }else {
            alert('Введите текст жалобы!');
        }
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
    
/*
	$('#createComment').click(function() {
		$.post('/comments/add.json', $('#createCommentForm').serialize(), function(response) {
			//console.log(response);
		})
    });

    $('.delete-comment').click(function() {
    	$.post('/comments/delete/' + $(this).data('id') + '.json', function(response) {
    		//console.log(response);
    	})
    })
  */

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

    $('.replyto').click(function() {
        if(($('#newComment').val().match(/^#\d/ig) == null) && ($('#newComment').val().match(/@\W*\s\W\.,/) == null)){
            $('input[name=comment_id]').val($(this).data('commentId'))
            var prepend = '@' + $(this).data('commentTo') + ', ';
            var newText = prepend + $('#newComment').val();
            $('#newComment').val(newText);
            $.scrollTo($('#comment-anchor'), {duration:250});
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



    /*$('a', '.menu').live('click', function(){
        console.log($(this).data('page'));
        $.get('/pitches/details/100515', function(response) {
            $('.wrapper').replaceWith(response);
        })
        return false;
    })*/


});