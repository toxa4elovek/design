$(document).ready(function() {
    testSubmitForce = false;

    $(document).on('submit', '#quiz_form', function(e) {
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
            });
        })
        .fail(function(response) {

        });
        return false;
    });

    $(document).on('click', '.js-start-test', function(e) {
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
        /* make the API call */
        sendFBMessage();
        return false;
        FB.api(
            "/1310636360/feed",
            "POST",
            {
                message: 'Message',
                picture: 'http://www.godesigner.ru/img/4.jpg',
                caption: "Caption",
                name: "Name",
                link: "http://www.godesigner.ru/",
                description: "Description field",
            },
            function (response) {
                console.log(response);
                if (response && !response.error) {
                    /* handle the result */
                }
            }
        );
        return false;
    });

    var sendFBMessage = function() {
        var dataFbWallPost = {
            method: 'stream.publish',
            message: "Message.",
            display: 'iframe',
            caption: "Caption",
            name: "Name",
            picture: 'http://www.permadi.com/tutorial/permadi.png',
            link: "http://www.godesigner.ru/",
            description: "Description field",
        };
        FB.ui(dataFbWallPost, function() {  });
    }

    setTimeout(function() { $('.vk_share_button').replaceWith(VK.Share.button(
        {
          url: 'http://www.godesigner.ru',
          title: 'Заголовок',
          description: 'Описание',
          image: 'http://www.godesigner.ru/img/4.jpg',
          noparse: true
        },
        {
            type: 'round_nocount',
            text: 'Поделиться'
        }
        ));
    }, 2000);

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
