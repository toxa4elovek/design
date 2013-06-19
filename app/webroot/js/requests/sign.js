$(document).ready(function() {
    $('#submit').click(function() {
        if(($('input[name=first_name]').val() == '') || ($('input[name=last_name]').val() == '')) {
            alert('Оставьте свои настоящие имя и фамилию');
            return false
        }
        if($('input[name=tos]').attr('checked') != 'checked') {
            alert('Вы должны согласиться с условиями соглашения о неразглашении!');
            return false;
        }
        return true;
    })

    $('#expand').click(function() {
        $('#tos').removeAttr('style');
        $(this).hide();
        $('#shrink').show();
        return false;
    })

    $('#shrink').click(function(){
        $('#tos').css('height', '300px').css('overflow', 'scroll');
        $('#expand').show();
        $(this).hide();
        return false;
    })
})