$(document).ready(function() {
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

    $('#wincomment').fileupload({
        dataType: 'html',
        autoUpload: false,
        singleFileUploads: false,
        dropZone: null,
        add: function(e, data) {
            if (data.files.length > 0) {
                e.data.fileupload.myData = data;
                var html = '';
                $.each(data.files, function(index, object) {
                    html += '<li class="fakelist">' + object.name + '</li>';
                });
                $('#filelist').html(html);
            }else {
                return false;
            }
        },
        done: function(e, data) {
            var completed = 100;
            fillProgress(completed);
            location.reload(true);
        },
        progressall: function(e, data) {
            if (data.total > 0) {
                var completed = Math.round(data.loaded / data.total * 100);
                fillProgress(completed);
            }
        },
        send: function(e, data) {
            $('#loading-overlay').modal({
                containerId: 'spinner',
                opacity: 80,
                close: false
            });
        }
    });

    $('#wincomment').submit(function(e) {
        e.preventDefault();
        $('#wincomment').fileupload('uploadByClickNoCheckInplace', $(this));
    });
    
    // Delete Comment
    $(document).on('click', '.delete-link-in-comment', function() {
        $(this).closest('section').fadeOut(400, function() { $(this).remove(); });
        $.get($(this).attr('href'));
        return false;
    });
});

/*
 * Filling progressbar with completed value
 */
function fillProgress(completed) {
    completed = (completed > 95) ? 100 : completed;
    $('#progressbar').text(completed + '%');
    var progresspx = Math.round(3.4 * completed);
    if(progresspx > 330) {
        progresspx == 330;
    }
    $('#filler').css('width', progresspx);
    if(completed > 95) {
        setTimeout(function() {
            $('#progressbarimage').css('background', 'url(/img/indicator_full.png)');
        }, 500);
    }
}
