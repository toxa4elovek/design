$(function(){

     $('.take, .fill').click(function(){
         var link = $(this).children('a').attr('href');
         if(link == '/pitches/create') {
             _gaq.push(['_trackEvent', 'Создание проекта', 'Пользователь перешел на выбор категории', 'Ссылка "Заказчику" на главной']);
         }

        window.location = link;
    });

    $('#to_use_text').hover(function() {
        $('#to_use_link').addClass('hover');
    }, function() {
        $('#to_use_link').removeClass('hover');
    });

    $('#to_use_link').hover(function() {
        $('#to_use_text').addClass('hover');
    }, function() {
        $('#to_use_text').removeClass('hover');
    });

    $('#experts-zone').on('hover', 'a', function() {
        var id = $(this).data('id');
        $('a[data-id="' + id + '"]', '#experts-zone').css('color', '#ff585d');
    })

    $('#experts-zone').on('mouseout', 'a', function() {
        var id = $(this).data('id');
        $('a[data-id="' + id + '"]', '#experts-zone').css('color', '#666666');
    })

    $('#experts_text').hover(function() {
        $('#experts_link').addClass('hover');
        $('#experts_text').css('color', '#ff585d');
    }, function() {
        $('#experts_link').removeClass('hover');
        $('#experts_text').css('color', '#666666');
    });

    $('#experts_link').hover(function() {
        $('#experts_text').addClass('hover');
        $('#experts_text').css('color', '#ff585d');
    }, function() {
        $('#experts_text').removeClass('hover');
        $('#experts_text').css('color', '#666666');
    });

    $('#finished, #video').hover(function() {
        $('img', this).hide();
    }, function() {
        $('img', this).show();
    });

    $('#slides').slides({
        preload: false,
        play: 5000,
        pause: 2500,
        hoverPause: true,
        animationStart: function(current){
          $('.caption').animate({
            bottom:-35
          },100);
          if (window.console && console.log) {
            // example return of current slide number
            //console.log('animationStart on slide: ', current);
          };
        },
        animationComplete: function(current){
          $('.caption').animate({
            bottom:0
          },200);
          if (window.console && console.log) {
            // example return of current slide number
            //console.log('animationComplete on slide: ', current);
          };
        },
        slidesLoaded: function() {
          $('.caption').animate({
            bottom:0
          },300);
        }
    });

    $('#pitch-table').height($('.wap_table').height());

    function changeBanner() {
        var $el = $('div:visible', '#bannerblock');
        var $elNext = $el.next();
        if ($elNext.length == 0) {
            $elNext = $el.prevAll().last();
        }
        $el.fadeOut(300);
        $elNext.fadeIn(300);
    }
    setInterval(changeBanner, 7500);
    $('.talkhoverzone').on('mouseover', function() {
        $('a', $(this)).css('color', '#ff585d');
    })

    $('.talkhoverzone').on('mouseout', function() {
        $('a', $(this)).css('color', '#666666');
    })

    $('.front_catalog li').hover(function() {
        $('.more_info', $(this)).fadeIn(300);
    },
    function() {
        $('.more_info', $(this)).fadeOut(300, function() {
                $(this).hide();
            });
    });

    $('#video').click(function() {
        $('#popup-final-step').modal({
            containerId: 'video-container',
            opacity: 80,
            closeClass: 'popup-close'
        });
        return false;
    });

    expertsRandom();

    $('a', '.logosale_search-block').click(function() {
        $(this).html('Поиск <img src="/img/transparent-loader.gif" alt="" style="position: relative;top: 6px;left: 25px;">');
        $(this).css({"text-align": "left", "padding-left": "25px", "width": "119px"});
        $('#logosale_form').submit();
        return false;
    });

    ReactDOM.render(
        React.createElement(ClientsLogosShowCase, {data: logos}), document.getElementById('clients-logos')
    );

});

function expertsRandom() {
    var limit = 3;
    var expertsArray = [];
    $('li.expert_enabled').each(function(idx, obj) {
        expertsArray.push($(obj).data('expert_id'));
    });
    // Shuffle Array. Taken from http://css-tricks.com/snippets/javascript/shuffle-array
    expertsArray.sort(function() { return 0.5 - Math.random() });
    
    for (var i = 0; i < limit; i++) {
        $('li.expert-' + expertsArray[i], '#experts-zone').show();
    }
}

