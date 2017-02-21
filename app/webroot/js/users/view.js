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
    if (currentActiveProjects.length > 1 && $('p', '#popup-invite').length === 1) {
      var string = '';
      currentActiveProjects.forEach(function (project, index) {
        var checked = '';
        if (index === 0) {
          checked = 'checked="checked"';
        }
        string += '<label style="display: block; text-align: left; position: relative; padding-left: 25px;"><input type="radio" value="' + project.id + '" name="selectedProject" ' + checked + ' style="top: 5px; left: 0; position: absolute; outline: none;" /> ' + project.title + '</label>';
      });
      $('p', '#popup-invite').after('<p>' + string + '</p>');
    } else if (currentActiveProjects.length === 1) {
      var project = currentActiveProjects[0];
      $('p', '#popup-invite').after('<input type="hidden" value="' + project.id + '" name="selectedProject"/>');
    }
    $('#popup-invite').modal({
      containerId: 'spinner',
      opacity: 80,
      closeClass: 'mobile-close'
    });
    return false;
  });

  $('#invite').one('click', function () {
    $('#invite').css({
      'width': '205px',
      'cursor': 'default'
    }).text('Отправляем приглашение...');
    var projectId = 0;
    if ($('input[name=selectedProject]').length > 1) {
      projectId = $('input[name=selectedProject]:checked').val();
    } else {
      projectId = $('input[name=selectedProject]').val();
    }
    var data = {
      'designerId': $('#user_id').val(),
      'projectId': projectId
    };
    $.post('/invites/invite.json', data, function (response) {
      $('#invite').text('Приглашение отправлено!');
      $('#gotest-close', '#popup-invite').text('Закрыть');
    });
    return false;
  });

  $('#gotest-close').on('click', function () {
    $('.mobile-close').click();
    return false;
  });
})($);