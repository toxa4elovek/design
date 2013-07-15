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
    })

    $('#confirmWinner').click(function() {
        window.location = ($('#confirm').attr('href'));
    });
    
    $('.replyto').click(function() {
        var el = $('#newComment');
        if(el.val().match(/@\W*\s\W\.,/) == null) {
            var prepend = '@' + $(this).data('commentTo') + ', ';
            var newText = prepend + el.val();
            el.focus().val(newText);
        }
        return false;
    });

})