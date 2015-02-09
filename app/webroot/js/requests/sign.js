$(document).ready(function() {
    $('#submit').click(function() {
        if(($('input[name=first_name]').val() == '') || ($('input[name=last_name]').val() == '') || ($('input[name=first_name]').val() == 'Имя') || ($('input[name=last_name]').val() == 'Фамилия')) {
            if(($('input[name=first_name]').val() == '') || ($('input[name=first_name]').val() == 'Имя')) {
                $('input[name=first_name]').addClass('wrong-input');
            }
            if (($('input[name=last_name]').val() == '') || ($('input[name=last_name]').val() == 'Фамилия')) {
                $('input[name=last_name]').addClass('wrong-input');
            }
            return false;
        }
        if($('input[name=tos]').attr('checked') != 'checked') {
            alert('Вы должны согласиться с условиями соглашения о неразглашении!');
            return false;
        }
        return true;
    });

    $(document).on('focus', '.wrong-input', function () {
        $(this).removeClass('wrong-input');
    });

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

    $('.tooltip3').tooltip({
        tooltipID: 'tooltip3',
        width: '282px',
        correctPosX: 45,
        positionTop: -180,
        borderSize: '0px',
        tooltipPadding: 0,
        tooltipBGColor: 'transparent'
    })

})