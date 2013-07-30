$(document).ready(function() {

    $(document).on('change', 'input[type=file]', function() {
        var fileInput = '<input type="file" name="file[]" class="wincommentfileupload"/>';
        if($(this).next('input[type=file]').length == 0) {
            $(this).after(fileInput);
        }
    });

    $('#nofile').click(function() {
        $('#nofiles-warning').modal({
            containerId: 'generic-popup',
            opacity: 80,
            closeClass: 'popup-close'
        });
        return false;
    });

    $('#confirm').click(function(){
        $('#important-confirm').modal({
            containerId: 'generic-popup',
            opacity: 80,
            closeClass: 'popup-close'
        });
        return false;
    });

    $('#confirmWinner').click(function() {
        window.location = ($('#confirm').attr('href'));
    });
    
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
        $.post('/wincomments/edit/' + id + '.json', {"text": newcomment}, function(response) {
            var newText = response;
            var section = textarea.parent().parent().parent().parent();
            $('.edit-link-in-comment', section).data('text', newcomment);
            $('.comment-container', section).html(newText);
            section.children().show();
            $('.hiddenform', section).hide();
            editcommentflag = false;
        });
        return false;
    });

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
            }
        }
    });

    $('.replyto, .mention-link').click(function() {
        replyTo($(this));
        return false;
    });
    
    $('section', '.center_block').on('mouseenter', function() {
        $('.toolbar', this).fadeIn(200);
    });
    $('section', '.center_block').on('mouseleave', function() {
        $('.toolbar', this).fadeOut(200);
    });
    
    function replyTo(target) {
        var el = $('#newComment');
        if (el.val().match(/@\W*\s\W\.,/) == null) {
            var prepend = '@' + target.data('commentTo') + ', ';
            var newText = prepend + el.val();
            el.focus().val(newText);
        }
    }
});
