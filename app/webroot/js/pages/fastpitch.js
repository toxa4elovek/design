$(document).ready(function() {
    $('#fastpitch').click(function() {
        if ($('input[name="phone"]').val() != '') {
            $.post('/pitches/addfastpitch.json', {"phone": $('input[name="phone"]').val()}, function(response) {
                if (response) {
                    window.location.href = $.parseJSON(response);
                }
            });
        } else {
            alert('Оставьте свой телефон, чтобы мы могли с вами связаться');
        }
    });
})