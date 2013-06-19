$(document).ready(function() {
    $('.banhammer').click(function() {
        $.post('/users/ban.json', {"id": $('#user_id').val(), "term": $(this).data('term')}, function(response) {
            alert('Пользователь не может оставлять комментарии до ' + response.silenceUntil);
        });
    })

    $('.allowcomment').click(function() {
        $.post('/users/unban.json', {"id": $('#user_id').val()}, function(response) {
            alert('Пользователь теперь может оставлять комментарии');
        });
    })

    $('.block').click(function() {
        $.post('/users/block.json', {"id": $('#user_id').val()}, function(response) {
            $('.block').hide();
            $('.unblock').show();
            alert('Пользователь заблокирован');
        });
    })

    $('.unblock').click(function() {
        $.post('/users/unblock.json', {"id": $('#user_id').val()}, function(response) {
            $('.unblock').hide();
            $('.block').show();
            alert('Пользователь разблокирован');
        });
    })
})