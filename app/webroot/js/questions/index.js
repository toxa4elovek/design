$(document).ready(function() {
    testSubmitForce = false;

    $(document).on('submit', '#quiz_form', function(e) {
        _gaq.push(['_trackEvent', 'Тест', 'Пользователь закончил тест']);
        e.preventDefault();

        $('li', $(this)).each(function(idx, obj) {
            if ($('.radio-input:checked', $(obj)).length == 0) {
                $(obj).addClass('not-checked');
            }
        });

        if (($(this).find('.not-checked').length > 0) && !testSubmitForce) {
            $.scrollTo($('.not-checked'), {duration: 500});
            return false;
        }

        $.post($(this).attr('action'), $(this).serialize(), function() {
            
        }, 'html')
        .done(function(response) {
            $container = $('.content.group');
            $objOld = $('.howitworks');
            $objNew = $('<div/>').html(response).contents(); // http://stackoverflow.com/a/11047751
            $objOld.fadeOut(200, function() {
                $(this).remove();
                $objNew.hide().appendTo($container).fadeIn(600);
                // Refresh Share Buttons
                initShares();
            });
        })
        .fail(function(response) {

        });
        return false;
    });

    $(document).on('click', '.js-start-test', function(e) {
        _gaq.push(['_trackEvent', 'Тест', 'Пользователь начал тест']);
        e.preventDefault();
        $('.right-sidebar').fadeOut(200, function() {
            $(this).remove();
        });
        $('.howitworks.quiz').fadeOut(200, function() {
            $(this).remove();
            $('.content.group').removeClass('narrow');
            $('.nicht').toggleClass('nicht howitworks quiz').appendTo($('.content.group')).fadeIn(600);
            var testSeconds = 180 // Timer Duration in Seconds
            testCountdown(testSeconds);
        });
    });

    $(document).on('change', '.radio-input', function() {
        $(this).closest('li').removeClass('not-checked');
    });

    $(window).on('scroll', function() {
        $('.test-timer').css('top', $(window).scrollTop() + 40);
    });

    $(document).on('click', '.post-to-facebook', function() {
        _gaq.push(['_trackEvent', 'Тест', 'Пользователь нажал кнопку расшаривания фейсбука']);
        //sendFBMessage();
        return false;
    });

    var sendFBMessage = function() {
        var dataFbWallPost = {
            method: 'stream.publish',
            message: "",
            display: 'iframe',
            caption: " ",
            name: "Узнай, какой ты дизайнер на самом деле",
            picture: $('.post-to-facebook').data('share-image'),
            link: "http://www.godesigner.ru/questions/index",
            description: $('.post-to-facebook').data('share-text'),
        };
        FB.ui(dataFbWallPost, function() {  });
    }

    var initShares = function() {
        $('.social-likes').socialLikes();
        setTimeout(function() {
            $('.social-likes__button_vkontakte').on('click', function() {
                _gaq.push(['_trackEvent', 'Тест', 'Пользователь нажал кнопку расшаривания ВК']);
            })

            $('.social-likes__button_twitter').on('click', function() {
                _gaq.push(['_trackEvent', 'Тест', 'Пользователь нажал кнопку расшаривания твиттера']);
            })

            $('.social-likes__button_pinterest').on('click', function() {
                _gaq.push(['_trackEvent', 'Тест', 'Пользователь нажал кнопку расшаривания пинтереста']);
            })

            $('.social-likes__button_facebook').on('click', function() {
                _gaq.push(['_trackEvent', 'Тест', 'Пользователь нажал кнопку расшаривания фейсбука']);
            })
        }, 2000);
    }
    initShares();

    // Countdown
    var testCountdown = function(testSeconds) {
        var now = new Date();
        var deadline = Math.floor((now.getTime() / 1000) + testSeconds);
        $("#test-countdown").countdown({
            date: deadline, // Change this to your desired date to countdown to
            format: "on",
            showEmptyDays: 'off',
            isTest: 'on'
        });
    }

});

function activate(element) {
    $.get('/questions/activate/' + $(element).data('testid') + '.json', function(response) {
    })
    return true;
}
