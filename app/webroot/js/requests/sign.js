;(function() {
    $(function() {
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
                $('#popup-request-sign').modal({
                    containerId: 'spinner',
                    opacity: 80,
                    closeClass: 'mobile-close',
                    onShow: function () {
                        $('#popup-need-agree-tos').fadeTo(600, 1);
                    }
                });
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
        });

        $('#shrink').click(function(){
            $('#tos').css('height', '300px').css('overflow', 'scroll');
            $('#expand').show();
            $(this).hide();
            return false;
        });

        $('.tooltip3').tooltip({
            tooltipID: 'tooltip3',
            width: '282px',
            correctPosX: 45,
            positionTop: -180,
            borderSize: '0px',
            tooltipPadding: 0,
            tooltipBGColor: 'transparent'
        });

        $('#close_tos').on('click', function() {
            $('.mobile-close').click();
            return false;
        });

        $('#agree').on('click', function() {
            $('.mobile-close').click();
            $('input[name=tos]').prop('checked', 'checked');
            $('#submit').click();
            return false;
        })

    });
})($);