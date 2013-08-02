$(document).ready(function() {

    var ajaxZoneInit = $('#ajaxzone').html();
    $(document).on('keyup', 'input[name=search]', function() {
        var input = $(this);
        var search = input.val();
        if(search.length > 2) {
            $.get('/answers?search=' + encodeURIComponent(search) + '&ajax=true', function(response) {
                $('#ajaxzone').html($('#ajaxzone', response).html());
                $('.answer-expand-link').click().hide();
            });
        } else {
            $('#ajaxzone').html(ajaxZoneInit);
            $('.answer-expand-link').attr('data-expanded', 1).click().show();
        }
        return true;
    });

    $(document).on('click', '.answer-expand-link', function() {
        if($(this).attr('data-expanded') != 1) {
            $(this).attr('data-expanded', 1);
            var answerBlock = $(this).parent().prev('.answer-expand');
            $(this).removeClass('av').addClass('avup');
            answerBlock.animate({height: ((answerBlock.children().length / 2) * 36)+'px'}, 500);
        } else {
            $(this).attr('data-expanded', 0);
            var answerBlock = $(this).parent().prev('.answer-expand');
            $(this).removeClass('avup').addClass('av');
            answerBlock.animate({height: '107px'}, 500);
        }
        return false;
    });
});