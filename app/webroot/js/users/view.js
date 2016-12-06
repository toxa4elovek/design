'use strict';

;(function ($) {
  $(document).on('click', '.banhammer', function () {
    var data = {
      'id': $('#user_id').val(),
      'term': $(this).data('term')
    };
    $.post('/users/ban.json', data, function (response) {
      alert('Пользователь не может оставлять комментарии до ' + response.silenceUntil);
    });
  });

  $(document).on('click', '.allowcomment', function () {
    var data = {
      'id': $('#user_id').val()
    };
    $.post('/users/unban.json', data, function () {
      alert('Пользователь теперь может оставлять комментарии');
    });
  });

  $(document).on('click', '.block', function () {
    var data = {
      'id': $('#user_id').val()
    };
    $.post('/users/block.json', data, function () {
      $('.block').hide();
      $('.unblock').show();
      alert('Пользователь заблокирован');
    });
  });

  $(document).on('click', '.unblock', function () {
    var data = {
      'id': $('#user_id').val()
    };
    $.post('/users/unblock.json', data, function () {
      $('.unblock').hide();
      $('.block').show();
      alert('Пользователь разблокирован');
    });
  });

  $(document).on('click', '.fav-user', function () {
    var link = $(this);
    link.text('Отписаться').addClass('unfav-user').removeClass('fav-user');
    $.get('/favourites/adduser/' + $(this).data('id') + '.json', function (response) {
      if (response.result == false) {
        link.text('Подписаться');
        link.addClass('fav-user').removeClass('unfav-user');
      }
    });
    return false;
  });

  $(document).on('click', '.unfav-user', function () {
    var link = $(this);
    link.text('Подписаться').addClass('fav-user').removeClass('unfav-user');
    $.get('/favourites/removeUser/' + $(this).data('id') + '.json', function (response) {
      if (response.result == false) {
        link.text('Отписаться');
        link.addClass('unfav-user').removeClass('fav-user');
      }
    });
    return false;
  });

  $(document).on('click', '#invite-user', function () {
    $('#popup-invite').modal({
      containerId: 'spinner',
      opacity: 80,
      closeClass: 'mobile-close'
    });
    return false;
  });

  $('#invite').on('click', function () {
    $('.mobile-close').click();
    return false;
  });

  $('#gotest-close').on('click', function () {
    $('.mobile-close').click();
    return false;
  });
})($);