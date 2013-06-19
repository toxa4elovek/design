$(function(){

    function preload(arrayOfImages) {
        $(arrayOfImages).each(function(){
            $('<img/>')[0].src = this;
            // Alternatively you could use:
            // (new Image()).src = this;
        });
    }

    preload([
        '/img/partners/logo_tutdesign_on.png',
        '/img/partners/surfinbird_on.png',
        '/img/partners/play_on.png',
        '/img/partners/zucker_on.png',
        '/img/partners/trends_on.png',
    ]);

    $('.take, .fill').click(function(){
        window.location = $(this).children('a').attr('href')
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
          },00);
        }
    });

    $('#pitch-table').height($('.wap_table').height());

    $('.hoverlogo').on({

        mouseenter: function () {
            var link = $(this);
            var image = $("img", $(this));
            var onImage =  $('.nonhoverlogo', link.parent());
            image.animate({opacity: 0}, 300, function () {
            });
            onImage.animate({opacity: 1}, 300, function () {
            });
        },

        mouseleave: function () {
            var link = $(this);
            var image = $("img", $(this));
            var onImage =  $('.nonhoverlogo', link.parent());
            image.animate({opacity: 1}, 300, function () {
            });
            onImage.animate({opacity: 0}, 300, function () {
            });

        }

    });

    function changeBanner() {
        $.each($('#bannerblock').children(), function(index, object) {
            var banner = $(object);
            if(banner.css('display') == 'none') {
                banner.fadeIn(300);
            }else{
                banner.fadeOut(300);
            }
        })
    }
    setInterval(changeBanner, 7500);
    $('.talkhoverzone').on('mouseover', function() {
        $('a', $(this)).css('color', '#ff585d');
    })

    $('.talkhoverzone').on('mouseout', function() {
        $('a', $(this)).css('color', '#666666');
    })



    $('.front_catalog li').children('img').on('mouseover', function() {
        $(this).parent().children('.more_info').fadeIn(300);
    })

    $('#special_banner').on('mouseenter', function() {
        $('#special_link').fadeIn(300); 
    })

    $('#brief_banner').on('mouseenter', function() {
        $('#brief_link').fadeIn(300); 
    })

    $('.more_info').on('mouseout', function() {
        $(this).fadeOut(300);
    })

    $('#video').click(function() {
        $('#popup-final-step').modal({
            containerId: 'video-container',
            opacity: 80,
            closeClass: 'popup-close'
        });
        return false;
    });

});