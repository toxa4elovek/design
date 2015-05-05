$(document).ready(function() {
    $('.fastpitch').click(function(e) {
        if ($('input[name="phone"]').val() != '') {
            $.post('/pitches/addfastpitch.json', {
                'phone': $('input[name="phone"]').val(),
                'date': $('input[name=time]:checked').data('date')
            }, function(response) {
                if (response) {
                    window.location.href = $.parseJSON(response);
                }
            });
        } else {
            alert('Оставьте свой телефон, чтобы мы могли с вами связаться');
        }
    });
    
    $('#more').on('click',function(){
        $('.date-hide').show();
        $('#fastpitch').css('margin-top','10px');
    });
});