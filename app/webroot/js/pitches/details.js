$(document).ready(function() {
    $( ".slider" ).each(function(index, object) {
        var value = logoProperties[index];
        $(object).slider({
            disabled: true,
            value: value,
            min: 1,
            max: 9,
            step: 1,
            slide: function( event, ui ) {
                var rightOpacity = (((ui.value-1) * 0.08) + 0.36).toFixed(2);
                var leftOpacity = (1 - ((ui.value-1) * 0.08)).toFixed(2);
                $(ui.handle).parent().parent().next().css('opacity', rightOpacity);
                $(ui.handle).parent().parent().prev().css('opacity', leftOpacity);
            }
        })
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

    $('.time').timeago();

    $('.print-link').on('mouseover', function() {
        $('img', this).attr('src', '/img/print_brief_button2.png');
    })
    $('.print-link').on('mouseout', function() {
        $('img', this).attr('src', '/img/print_brief_button.png');
    })

    $('.fav-plus').on('mouseover', function() {
        $('img', this).attr('src', '/img/plusb_2.png');
    })
    $('.fav-plus').on('mouseout', function() {
        $('img', this).attr('src', '/img/plusb.png');
    })

    $('.all-pitches-link').on('mouseover', function() {
        $('img', this).attr('src', '/img/all_pitches_2.png');
    })
    $('.all-pitches-link').on('mouseout', function() {
        $('img', this).attr('src', '/img/all_pitches.png');
    })

    $('.next-pitch-link').on('mouseover', function() {
        $('img', this).attr('src', '/img/next_2.png');
    })
    $('.next-pitch-link').on('mouseout', function() {
        $('img', this).attr('src', '/img/next.png');
    })

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

    // gplus
    window.___gcfg = {lang: 'ru'};

    (function() {
        var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
        po.src = 'https://apis.google.com/js/plusone.js';
        var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
    })();

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

// Usage:
/*
    preload([

    ]);
*/

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

    $('#createComment').click(function() {
        if (isCommentValid($('#newComment').val())) { // See app.js
            return true;
        }
        alert('Введите текст комментария!');
        return false;
    });


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

    /*
    $( ".slider" ).slider({
        value: 5,
        min: 1,
        max: 9,
        step: 1,
        slide: function( event, ui ) {
            var rightOpacity = (((ui.value-1) * 0.08) + 0.36).toFixed(2);
            var leftOpacity = (1 - ((ui.value-1) * 0.08)).toFixed(2);
            $(ui.handle).parent().parent().next().css('opacity', rightOpacity);
            $(ui.handle).parent().parent().prev().css('opacity', leftOpacity);
        }
    })
    */
})