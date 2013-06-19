$(document).ready(function() {

    $('#save').live('click', function() {
        if(($('input[name="cashintype"]:checked').data('pay') == 'cards') && ($('input[name="accountnum"]').val().length < 20)) {
            alert('Введите номер своего счета, он должен содержать не меньше 20 символов!');
            return false;
        }
        var href = $('#worker-payment-data').attr('action');
        $.post('/users/savePaymentData.json', $('#worker-payment-data').serialize(), function(response) {
            window.location = href;
        })
        return false;
    });

    $('.rb1').live('change', function() {
        if($(this).data('pay') == 'cards') {
            $('#cards').show();
            $('#wmr').hide();
        }else {
            $('#cards').hide();
            $('#wmr').show();
        }
    });

})