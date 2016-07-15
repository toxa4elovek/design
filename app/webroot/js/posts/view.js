'use strict';

function _defineProperty(obj, key, value) { if (key in obj) { Object.defineProperty(obj, key, { value: value, enumerable: true, configurable: true, writable: true }); } else { obj[key] = value; } return obj; }

$(document).ready(function () {
  $('.time').timeago();

  $('.relatedpost').on('mouseover', function () {
    $(this).css('background-color', '#e9e9e9');
    $('.title', this).css('color', '#fa565b');
  });

  $('.relatedpost').on('mouseout', function () {
    $(this).css('background-color', '');
    $('.title', this).css('color', '#999999');
  });
  var solutionsToCheck = [];
  $('.howitworks img').each(function (index, object) {
    var parent = $(object).parent();
    var url = parent.attr('href');
    var solutionUrl = /godesigner.ru\/pitches\/viewsolution\/(\d+)$/;
    if (parent.is('a')) {
      var solutionId = solutionUrl.exec(url)[1];
      if (solutionUrl.test(url)) {
        solutionsToCheck.push(solutionId);
      }
      parent[0].setAttribute('data-solution-id', solutionId);
      object.setAttribute('data-solution-id', solutionId);
    }
    $.unique(solutionsToCheck);
  });

  $.get('/solutions/get_logosale_status.json', {
    'solutionsIds': solutionsToCheck
  }, function (response) {
    if (response.status === 200) {
      response.data.forEach(function (solution) {
        if (solution.ready === true) {
          var imageContainer = $('a[data-solution-id=' + solution.id + ']');
          var url = 'https://www.godesigner.ru/pitches/viewsolution/' + solution.id;
          imageContainer.css({ 'position': 'relative' });
          imageContainer.append('<a href="' + url + '" class="buy-solution button">Купить лого за 9500 руб.</a>');
        }
      });
    }
  });

  $(document).on('focus', '#blog-search', function () {
    $('#post-search').addClass('active');
  });
  $(document).on('blur', '#blog-search', function () {
    $('#post-search').removeClass('active');
  });
  spanlist = $('p, span');
  $.each(spanlist, function (idx, obj) {
    var $rama = $(obj).find('img');
    if ($rama.length > 1) {
      $.each($rama, function (idx, img) {
        parent = $(img).parent();
        if (parent.is('a')) {
          $(img).insertBefore(parent);
          parent.remove();
        }
      });
      $rama.wrapAll('<div class="fotorama" data-nav="false" data-maxwidth="100%" />');
      // 1. Initialize fotorama manually.
      var classFotorama = $(this).find('.fotorama');
      var $fotoramaDiv = classFotorama.on('fotorama:showend', function (e, fotorama, extra) {
        var classList = $(e.target).attr('class').split(/\s+/);
        $.each(classList, function (index, item) {
          if (item !== 'fotorama') {
            fotoramaId = item;
          }
        });
        var arrowBlock = $('div[data-fotorama="' + fotoramaId + '"]');
        if (arrowBlock.length != 0) {
          $('.page', arrowBlock).text(fotorama.activeFrame.i);
        }
      }).fotorama();
      var classList = $fotoramaDiv.attr('class').split(/\s+/);
      $.each(classList, function (index, item) {
        if (item !== 'fotorama') {
          fotoramaId = item;
        }
      });

      // 2. Get the API object.
      var fotorama = $fotoramaDiv.data('fotorama');
      var arrows = $(this).find('.fotorama__arr');
      if (arrows.length > 0) {
        arrows.remove();
      }
      $('<div class="fotorama_arrows" data-fotorama="' + fotoramaId + '"><span class="fotorama__arr--prev button round prev"><div class="arrow-left"></div></span><span class="page">1</span><span id="fotorama_separator">/</span><span class="count"></span><span class="fotorama__arr--next button round next"><div class="arrow-right"></div></span></div>').insertAfter(classFotorama);
      $(this).find('.count').append($rama.length);
    }
  });

  $('.fotorama__arr--prev').click(function () {
    fotorama = $(this).closest('p').find('.fotorama').data('fotorama');
    fotorama.show('<');
    var pageObject = $(this).closest('div').find('.page');
    var page = parseInt(pageObject.text());
    if (page > 1) {
      pageObject.text(page - 1);
    } else {
      fotorama.show('>>');
      pageObject.text(fotorama.size);
    }
  });
  $('.fotorama__arr--next').click(function () {
    fotorama = $(this).closest('p').find('.fotorama').data('fotorama');
    fotorama.show('>');
    var pageObject = $(this).closest('div').find('.page');
    var page = parseInt(pageObject.text());
    if (page < fotorama.size) {
      pageObject.text(page + 1);
    } else {
      fotorama.show('0');
      pageObject.text(1);
    }
  });

  $('.social-likes').socialLikes();(function ($) {
    $.fn.ctrlCmd = function (key) {
      var allowDefault = true;

      if (!$.isArray(key)) {
        key = [key];
      }

      return this.keydown(function (e) {
        for (var i = 0, l = key.length; i < l; i++) {
          if (e.keyCode === key[i].toUpperCase().charCodeAt(0) && e.metaKey) {
            allowDefault = false;
          }
        }
        return allowDefault;
      });
    };

    $.fn.disableSelection = function () {
      var _attr$css;

      this.ctrlCmd(['a', 'c']);

      return this.attr('unselectable', 'on').css((_attr$css = { '-moz-user-select': '-moz-none'
      }, _defineProperty(_attr$css, '-moz-user-select', 'none'), _defineProperty(_attr$css, '-o-user-select', 'none'), _defineProperty(_attr$css, '-khtml-user-select', 'none'), _defineProperty(_attr$css, '-webkit-user-select', 'none'), _defineProperty(_attr$css, '-ms-user-select', 'none'), _defineProperty(_attr$css, 'user-select', 'none'), _attr$css)).bind('selectstart', false);
    };
  })(jQuery);

  $('#content_help').disableSelection();

  $('img', '#content_help').bind('contextmenu', function (e) {
    return false;
  });
});

function clearData() {
  window.clipboardData.setData('text', '');
}
function cldata() {
  if (window.clipboardData) {
    window.clipboardData.clearData();
  }
}

setInterval('cldata();', 1000);