$(document).ready(function() {
    $('#resend').click(function() {
        $.get('/users/resend.json', function(response) {
            $('#mailsent').show();
        })
        return false;
    })
})