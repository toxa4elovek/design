$(document).ready(function () {
    $('.banhammer').click(function () {
        $.post('/users/ban.json', {"id": $('#user_id').val(), "term": $(this).data('term')}, function (response) {
            alert('Пользователь не может оставлять комментарии до ' + response.silenceUntil);
        });
    })

    $('.allowcomment').click(function () {
        $.post('/users/unban.json', {"id": $('#user_id').val()}, function (response) {
            alert('Пользователь теперь может оставлять комментарии');
        });
    })

    $('.block').click(function () {
        $.post('/users/block.json', {"id": $('#user_id').val()}, function (response) {
            $('.block').hide();
            $('.unblock').show();
            alert('Пользователь заблокирован');
        });
    })

    $('.unblock').click(function () {
        $.post('/users/unblock.json', {"id": $('#user_id').val()}, function (response) {
            $('.unblock').hide();
            $('.block').show();
            alert('Пользователь разблокирован');
        });
    })

    $(document).on('click', '.fav-user', function (e) {
        var link = $(this);
        link.text('Отписаться');
        link.addClass('unfav-user').removeClass('fav-user');
        $.get('/favourites/adduser/' + $(this).data('id') + '.json', function (response) {
            if (response.result == false) {
                link.text('Подписаться');
                link.addClass('fav-user').removeClass('unfav-user');
            }
        });
        return false;
    });

    $(document).on('click', '.unfav-user', function (e) {
        var link = $(this);
        link.text('Подписаться');
        link.addClass('fav-user').removeClass('unfav-user');
        $.get('/favourites/removeUser/' + $(this).data('id') + '.json', function (response) {
            if (response.result == false) {
                link.text('Отписаться');
                link.addClass('unfav-user').removeClass('fav-user');
            }
        });
        return false;
    });
})