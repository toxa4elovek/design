$(document).ready(function() {

    $(document).on('keyup', 'input[name=search]', function() {
        var input = $(this);
        var search = input.val();
        if(search.length > 2) {
            $.get('/answers?search=' + search + '&ajax=true', function(response) {
                $('#ajaxzone').replaceWith($('#ajaxzone', response));
            })
        }
        return true;
    })
    var expanded = false;
    $(document).on('click', '.answer-expand-link', function() {
        if(!expanded) {
            expanded = true;
            var answerBlock = $(this).parent().prev('.answer-expand');
            $(this).removeClass('av').addClass('avup');
            answerBlock.animate({height: ((answerBlock.children().length / 2) * 36)+'px'}, 500);
        }else{
            expanded = false;
            var answerBlock = $(this).parent().prev('.answer-expand');
            $(this).removeClass('avup').addClass('av');
            answerBlock.animate({height: '107px'}, 500);
        }
        return false;
    })

})