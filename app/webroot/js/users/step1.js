$(document).ready(function() {


    $('#step2-link-saveform').click(function() {
        if(($('input[name="cashintype"]:checked').data('pay') == 'cards') && ($('input[name="accountnum"]').val().length < 20)) {
            alert('Введите номер своего счета, он должен содержать не меньше 20 символов!');
            return false;
        }else if (($('input[name="cashintype"]:checked').data('pay') == 'cards') && ($('input[name="inn"]').val().length != 12)) {
            alert('Введите свой личный ИНН!');
            return false;
        }
        var href = $(this).attr('href');
        $.post('/users/savePaymentData.json', $('#worker-payment-data').serialize(), function(response) {
            window.location = href;
        })
        return false;
    });

    $('.rb1').change(function() {
        if($(this).data('pay') == 'cards') {
            $('#cards').show();
            $('#wmr').hide();
        }else {
            $('#cards').hide();
            $('#wmr').show();
        }
    });

    $('.tooltip').tooltip({
        tooltipID: 'tooltip',
        width: '282px',
        correctPosX: 45,
        positionTop: -180,
        borderSize: '0px',
        tooltipPadding: 0,
        tooltipBGColor: 'transparent'
    })

    $('#step2-link-saveform').mouseover(function() {
        $('span', this).css('color', '#50525a');
    });

    $('#step2-link-saveform').mouseout(function() {
        $('span', this).css('color', '#BABABA');
    });
})