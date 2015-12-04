$(document).ready(function() {

    let isOwner = false;
    const isOwnerInput = $('input[name=is_owner]');
    if((isOwnerInput.length == 1) && (isOwnerInput.val() == 1)) {
        isOwner = true;
    }

    let projectPublished = false;
    const projectPublishedInput = $('input[name=project_published]');
    if((projectPublishedInput.length == 1) && (projectPublishedInput.val() == 1)) {
        projectPublished = true;
    }

    let projectStatusActive = false;
    const projectStatusInput = $('input[name=project_status]');
    if((projectStatusInput.length == 1) && (projectStatusInput.val() == 0)) {
        projectStatusActive = true;
    }

    let projectStatusChooseWinner = false;
    const projectAwardedInput = $('input[name=project_awarded]');
    if(((projectStatusInput.length == 1) && (projectStatusInput.val() == 1)) && (
        (projectAwardedInput.length == 1) && (projectAwardedInput.val() == 0)
    )) {
        projectStatusChooseWinner = true;
    }

    let projectExpert = false;
    const projectExpertInput = $('input[name=project_expert]');
    if((projectExpertInput.length == 1) && (projectExpertInput.val() == 1)) {
        projectExpert = true;
    }

    if(typeof(needConfirmation) == 'undefined') {
        needConfirmation = false;
    }
    if(isOwner && !projectExpert && projectStatusChooseWinner && (typeof(getCookie('expert_tutorial')) == 'undefined')) {
        const tutorial = new EnjoyHint(
            {
                onSkip: function(){
                    writeCookie('expert_tutorial', true, 90);
                },
                onEnd: function() {
                    writeCookie('expert_tutorial', true, 90);
                }
            }
        );
        const steps = [
            {
                "next .helpexpert" : 'Сложности с выбором? Опытный эксперт подскажет<br/> правильный выбор',
                "showSkip": true,
                "showNext": true,
                "nextButton" : {"text": "Нет, спасибо!"},
                "skipButton" : {"text": "Подробнее"},
                "selectorClick": '.helpexpert'
            }
        ];
        tutorial.set(steps);
        tutorial.run();
    }

    if(!needConfirmation && isOwner && projectStatusActive && projectPublished && (typeof(getCookie('tutorial')) == 'undefined')) {
        const tutorial = new EnjoyHint(
            {
                onSkip: function(){
                    writeCookie('tutorial', true, 90);
                },
                onEnd: function() {
                    writeCookie('tutorial', true, 90);
                }
            }
        );
        const steps = [
            {
                "next .helptools" : 'Узнайте свои возможности при проведении проекта',
                "showSkip": true,
                "showNext": true,
                "nextButton" : {"text": "Далее"},
                "skipButton" : {"text": "Узнать"},
                "selectorClick": '.helptools'
            },
            {
                "next .helppinned" : 'Мало решений? Позвольте нам привлечь больше<br>дизайнеров к своему проекту!',
                "showSkip": true,
                "showNext": true,
                "nextButton" : {"text": "Нет, спасибо!"},
                "skipButton" : {"text": "Подробнее"},
                "selectorClick": '.helppinned'
            },
            {
                "next .helpbrief" : 'Правильно заполненное ТЗ — залог эффективной работы',
                "showSkip": true,
                "showNext": true,
                "nextButton" : {"text": "Далее"},
                "skipButton" : {"text": "Подробнее"},
                "selectorClick": '.helpbrief'
            }
        ];
        tutorial.set(steps);
        tutorial.run();
    }

    // Navigation
    $(document).on('click', '.ajaxgallery', function(e) {
        e.preventDefault();
        if (window.history.pushState) {
            window.history.pushState('object or string', 'Title', this.href); // @todo Check params
            gallerySwitch.historyChange();
        } else {
            window.location = $(this).attr('href');
        }
    });
    if(window.location.href.match(/pitches\/designers/)) {
        $(window).on('popstate', function() {
            //gallerySwitch.historyChange();
        });
    }

    $('#resend').click(function() {
        $('#mailsent').show();
        $.get('/users/resend.json');
        return false;
    });

    $(document).on('click', '.add_solution', function() {
        if ((!$(this).hasClass('needWait')) && (!$(this).hasClass('needConfirm'))) {
            return true;
        }
        if($(this).hasClass('needConfirm')) {
            $('#popup-need-confirm-email').modal({
                containerId: 'gotest-popup_gallery',
                opacity: 80,
                closeClass: 'gotest-close'
            });
        }else if ($(this).hasClass('needWait')) {
            $('#popup-need-wait').modal({
                containerId: 'gotest-popup_gallery',
                opacity: 80,
                closeClass: 'gotest-close'
            });
        }
        return false;
    });

    gallerySwitch.tabInit();
    enableToolbar();

    // details.js start
    /* ==============*/
    $('#client-only-toggle').change(function() {
        if($(this).attr('checked')) {
            $.each($('section[data-type="designer"]', '.messages_gallery'), function(index, object) {
                var comment = $(object);
                comment.hide();
                var separator = comment.next('.separator');
                if((separator) && (separator.length == 1)) {
                    separator.hide();
                }
            });
        }else {
            $.each($('section[data-type="designer"]', '.messages_gallery'), function(index, object) {
                var comment = $(object);
                comment.show();
                var separator = comment.next('.separator');
                if((separator) && (separator.length == 1)) {
                    separator.show();
                }
            });
        }
    });

    // details.js end
    /* ==============*/
    // designers.js start

    // Scrolling
    $(document).on('click', '.scroll_right', function() {
        var $wrapper = $(this).parent();
        var self = $(this);
        var $opp = self.prev();
        var $el = $wrapper.children('ul');
        $el.animate({left: '-=216'}, 500, function() {
            if ($el.position().left < 0) {
                $opp.show();
            }
            if ($el.width() + $el.position().left < $wrapper.width() + 10) {
                self.hide();
                $el.animate({right: -($el.width() - $el.parent().width())}, 0);
            }
        });
    });
    $(document).on('click', '.scroll_left', function() {
        var $wrapper = $(this).parent();
        var self = $(this);
        var $opp = self.next();
        var $el = $wrapper.children('ul');
        $el.animate({left: '+=216'}, 500, function() {
            if ($el.width() + $el.position().left > $wrapper.width()) {
                $opp.show();
            }
            if ($el.position().left >= 0) {
                self.hide();
                $el.animate({left: 0}, 0);
            }
        });
    });

    // Designers buttons
    $(document).on('click', '.next_part_design', function(e) {
        e.preventDefault();
        $('.button_more').css('opacity', 0);
        $('.gallery_postload_loader').show();
        var data = {
            count: $('.designer_row').length
        };
        var $search = $('#designer-name-search');
        if (($search.val().length > 0) && ($search.val() != $search.data('placeholder'))) {
            data.search = $search.val();
        }
        var gallerySorting = getParameterByName('sorting');
        if (gallerySorting.length > 0) {
            data.sorting = gallerySorting;
        }
        $.get('/pitches/designers/' + $('input[name=pitch_id]').val(), data, function(response) {
            var designersCount = $($(response)[0]).val();
            obj = $('<div/>').html(response).contents(); // http://stackoverflow.com/a/11047751
            obj.each(function(index) {
                if ($(this).is('li')) {
                    $(this).css('opacity', '0');
                }
            });
            obj.appendTo('.portfolio_gallery.designers_tab');
            obj.each(function(index) {
                if ($(this).is('li')) {
                    $(this).animate({opacity:1}, 500);
                }
            });
            $('.gallery_postload_loader').hide();
            $('.button_more').css('opacity', 1);
            if ($('.designer_row').length >= designersCount) {
                $('.gallery_postload').hide();
                $('.pre-comment-separator').fadeIn();
                checkSeparator();
            }
            checkScrollers();
        });
    });
    $(document).on('click', '.rest_part_design', function(e) {
        e.preventDefault();
        $('.button_more').css('opacity', 0);
        $('.gallery_postload_loader').show();
        var data = {
            count: $('.designer_row').length,
            rest: 1
        };
        var $search = $('#designer-name-search');
        if (($search.val().length > 0) && ($search.val() != $search.data('placeholder'))) {
            data.search = $search.val();
        }
        var gallerySorting = getParameterByName('sorting');
        if (gallerySorting.length > 0) {
            data.sorting = gallerySorting;
        }
        $.get('/pitches/designers/' + $('input[name=pitch_id]').val(), data, function(response) {
            obj = $('<div/>').html(response).contents(); // http://stackoverflow.com/a/11047751
            obj.each(function(index) {
                if ($(this).is('li')) {
                    $(this).css('opacity', '0');
                }
            });
            obj.appendTo('.portfolio_gallery.designers_tab');
            obj.each(function(index) {
                if ($(this).is('li')) {
                    $(this).animate({opacity:1}, 500);
                }
            });
            $('.gallery_postload_loader').hide();
            $('.button_more').css('opacity', 1);
            $('.gallery_postload').hide();
            checkScrollers();
            $('.pre-comment-separator').fadeIn();
            checkSeparator();
        });
    });

    // Search
    function searchCallback($search, forced) {
        var forced = forced || false;
        var $form = $search.parent();
        var $container = $('.portfolio_gallery.designers_tab');
        var $buttons = $('.gallery_postload');
        if ($buttons.is(':visible') || forced) {
            $container.html('<img id="search-ajax-loader" src="/img/blog-ajax-loader.gif" style="margin: 0 0 100px 400px;">');
            $buttons.hide();
            $.get('/pitches/designers/' + $('input[name=pitch_id]').val(), $form.serialize(), function(response) {
                var designersCount = $($(response)[0]).val();
                $('#search-ajax-loader').remove();
                if (designersCount != 0) {
                    obj = $('<div/>').html(response).contents(); // http://stackoverflow.com/a/11047751
                    obj.each(function(index) {
                        if ($(this).is('li')) {
                            $(this).css('opacity', '0');
                        }
                    });
                    obj.appendTo($container);
                    obj.each(function(index) {
                        if ($(this).is('li')) {
                            $(this).animate({opacity:1}, 500);
                        }
                    });
                    if ($('.designer_row').length < designersCount) {
                        $buttons.show();
                    } else {
                        $('.pre-comment-separator').fadeIn();
                        checkSeparator();
                    }
                    checkScrollers();
                } else {
                    $container.html('<div class="ooopsSign" style="text-align: center; margin-bottom: 50px;"><h2 class="largest-header" style="line-height: 2em;">УПС, НИЧЕГО НЕ НАШЛИ!</h2><p class="large-regular">Попробуйте еще раз, изменив запрос.</p></div>');
                }
            });
        } else {
            var pattern = $search.val().toLowerCase();
            $('.designer_row').each(function(idx, obj) {
                var name = $(obj).find('.designer_name').text().toLowerCase();
                if (name.indexOf(pattern) == -1) {
                    $(obj).fadeOut(200);
                } else {
                    $('.ooopsSign', $container).remove();
                    $(obj).fadeIn(200);
                }
            });
            setTimeout(function() {
                if ($('.designer_row:visible').length == 0) {
                    $('.ooopsSign', $container).remove();
                    $container.append('<div class="ooopsSign" style="text-align: center; margin-bottom: 50px;"><h2 class="largest-header" style="line-height: 2em;">УПС, НИЧЕГО НЕ НАШЛИ!</h2><p class="large-regular">Попробуйте еще раз, изменив запрос.</p></div>');
                }
            }, 400);
        }
    }

    $(document).on('submit', '#designers-search', function() {
        var $search = $('#designer-name-search');
        if (($search.val().length == 0) || ($search.val() == $search.data('placeholder'))) {
            $search.val('');
        }
        searchCallback($search, true);
        return false;
    });

    // Live Search
    $(document).on('keyup', '#designer-name-search', function(e) {
        clearTimeout($.data(this, 'timer'));
        if (e.keyCode == 13) {
            return true;
        }

        var c = (e.keyCode);
        var isWordCharacter = checkSymbol(c);
        var isBackspaceOrDelete = (e.keyCode == 8 || e.keyCode == 46 || e.keyCode == 16);

        if (!isWordCharacter && !isBackspaceOrDelete) {
            return false;
        }
        var $search = $(this);
        var forced = false;
        if (($search.val().length == 0) || ($search.val() == $search.data('placeholder'))) {
            $search.val('');
            forced = true;
        }
        $(this).data('timer', setTimeout(function() {
            searchCallback($search, forced);
        }, 600));
        return true;
    });

    $(document).on('focus', '#designer-name-search', function() {
        $('#designers-search').addClass('active');
    });
    $(document).on('blur', '#designer-name-search', function() {
        $('#designers-search').removeClass('active');
    });

    function checkSymbol(code) {
        var all = [32, 48, 49, 50, 51, 52, 53, 54, 55, 56, 57, 65, 66, 67, 68, 69, 70, 71, 72, 73, 74, 75, 76, 77, 78, 79, 80, 81, 82, 83, 84, 85, 86, 87, 88, 89, 90, 97, 98, 99, 100, 101, 102, 103, 104, 105, 106, 107, 108, 109, 110, 111, 112, 113, 114, 115, 116, 117, 118, 119, 120, 121, 122, 176, 177, 178, 179, 180, 181, 182, 183, 184, 185, 186, 187, 188, 189, 190, 191, 192, 193, 194, 195, 196, 197, 198, 199, 200, 201, 202, 203, 204, 205, 206, 207, 208, 209, 210, 211, 212, 213, 214, 215, 216, 217, 218, 219, 220, 221, 222, 223, 224, 225, 226, 227, 228, 229, 230, 231, 232, 233, 234, 235, 236, 237, 238, 239]
        if ($.inArray(code, all) == -1) {
            return false;
        }
        return true;
    }
    // designers.js end
    /* ==============*/
});

function checkScrollers() {
    $.each($('.designer_wrapper'), function(idx, obj) {
        if ($(obj).width() < $('ul', $(obj)).first().width()) {
            $('.scroll_right', $(obj)).show();
        }
    });
}

var gallerySwitch = (function() {
    // Make Tab Active
    var activateTab = function($el) {
        $('.tabs-curve').find('li').removeClass('active');
        $el.parent().addClass('active');
    };
    // Gallery Tab Init
    var initGallery = function() {

        if((typeof(needConfirmation) != 'undefined') && (needConfirmation)) {
            $('#phone-confirm').modal({
                containerId: 'spinner',
                opacity: 80,
                closeClass: 'mobile-close',
                escClose: false,
                onShow: function onShow() {
                    $('.modalCloseImg').hide();
                    $('#phone-confirm').fadeTo(600, 1);
                },
                onClose: function onClose() {
                    $('.simplemodal-container').fadeOut(800);
                    $('#simplemodal-overlay').fadeOut(800, function () {
                        $.modal.close();
                    });
                }
            });
        }

        $(document).on('click', '#save-mobile', function() {
            var form = $('#mobile-form');
            var phonenumber = $('input[name=phone]', form).val();
            var data = form.serialize();
            $.post('/users/update.json', data, function(response) {
                var paragraph = $('.confirm-message', '.user-mobile-section');
                var text = '';
                if(response == 'false') {
                    text = 'К сожалению, мы не сможем подтвердить ваш телефон. Пожалуйста, укажите другой номер.';
                    $('#phone-confirm').css('height', '445px');
                    paragraph.text(text).show();
                }else if(response == 'limit') {
                    text = 'К сожалению, Вы превысили лимит отправки сообщений. Попробуйте снова через час.';
                    $('#phone-confirm').css('height', '445px');
                    paragraph.text(text).show();
                }else {
                    if (response.indexOf('error') != -1) {
                        text = 'Произошел сбой доставки SMS-сообщения. Попробуйте позже.';
                        paragraph.text(text).show();
                    } else {
                        text = 'Для подтверждения номера +' + phonenumber + ' введите код, который пришел по смс.';
                        $('.top-text').text(text);
                        $('.phone-input-container').hide();
                        var saveMobile = $('#save-mobile');
                        saveMobile.hide();
                        $('input[name=phone_code]').show();
                        $('ul', '#mobile-form').show();
                        var confirmMobile = $('#confirm-mobile').show();
                        confirmMobile.prev().show();
                        confirmMobile.show();
                        $('.number').text('+ ' + phonenumber).show();
                        $('.resend-code').show().css('display', 'block');
                        $('.help').css('margin-top', '68px');
                    }
                }
            });
            return false;
        });

        $(document).on('click', '.resend-code', function() {
            var data = {"resendcode": true};
            $.post('/users/update.json', data, function(response) {
                var paragraph = $('.confirm-message', '.user-mobile-section');
                var text = '';
                if(response == 'limit') {
                    text = 'К сожалению, Вы превысили лимит отправки сообщений. Попробуйте снова через час.';

                }else{
                    if (response.indexOf('error') != -1) {
                        text = 'Произошел сбой доставки SMS-сообщения. Попробуйте позже.';
                    } else {
                        text = 'Код подтверждения отправлен повторно на номер ваш номер.';
                    }
                }
                paragraph.text(text).show();
                $('.help').css('margin-top', '8px');
            });
            return false;
        });

        $(document).on('click', '.remove-number-link', function(){
            var data = {"removephone": true};
            $.post('/users/update.json', data);
            $('.confirm-message').hide();
            $('ul', '#mobile-form').hide();
            $('#confirm-mobile').prev().hide();
            $('#confirm-mobile').hide();
            $('.phone-input-container').show();
            $('#save-mobile').next().show();
            $('#save-mobile').show();
            $('.resend-code').show();
            $('.remove-number-link').text('Удалить номер');
            $('.resend-code').hide();
            $('h2', '#phone-confirm').text('Подтвердите номер');
            $('.top-text').html('Пожалуйста, укажите сотовый для экстренной связи.<br/>Команда GoDesignеr не расглашает данные третьим<br/>лицам и никогда не использует номер для спама.').show();
            return false;
        });

        $(document).on('click', '#confirm-mobile', function() {
            var data = {"code": $('input[name=phone_code]', '.user-mobile-section').val()};
            $.post('/users/update.json', data, function(response) {
                var paragraph = $('.confirm-message', '.user-mobile-section');
                if(response == 'false') {
                    text = 'Вы ввели неверный код.';
                    paragraph.text(text).show();
                    $('.help').css('margin-top', '8px');
                }else {
                    $('.mobile-close').show();
                    $('h2', '#phone-confirm').text('Спасибо, номер подтвержден!');
                    paragraph.hide();
                    $('#confirm-mobile').prev().hide();
                    $('#confirm-mobile').hide();
                    $('.remove-number-link').text('Удалить/поменять номер');
                    $('.remove-number').css('margin-right', '0');
                    $('.remove-number').css('margin-right', '0 !important');
                    $('.resend-code').hide();
                    $('.number').show();
                    $('.top-text').hide();
                    $('.help').css('margin-top', '237px');
                }
            });
            return false;
        });


        // Refresh Comments
        fetchPitchComments();
        // Pancake
        try {
            if($('#placeholder').length > 0) {
                renderFloatingBlock();
            }
        }catch(err) {

        }
        var floatingBlockHeight = $('#floatingblock').height()
        var offset = $('#floatingblock').offset();
        $(window).scroll(function() {
            var currentPosition = $(window).scrollTop() + floatingBlockHeight;
            var obj = $('#floatingblock');
            if (currentPosition < offset.top) {
                //console.log(currentPosition + ' vs ' + offset.top);
                //$('#floatingblock').addClass('fixed');
            } else {
                //console.log('removing fixed' + currentPosition);
                obj.removeClass('fixed');
            }
            var height = $(window).height();
            var scrollTop = $(window).scrollTop();
            var pos = obj.position();
            if (height + scrollTop > pos.top) {
                $('#dinamic').fadeOut(150);
            }
            else {
                $('#dinamic').fadeIn(150);
            }
        });
        $('.social-likes').socialLikes();
        // Plot
        $( "#scroller" ).draggable({ drag: function() {
            var x = $('#scroller').css('left');
            x = parseInt(x.substring(0, x.length - 2));
            var mod = ($('.konvajs-content', '#container').width() - 476) / 350;
            $('.konvajs-content', '#container').css('right', Math.round(x * mod) + 'px');
        }, axis: "x", containment: "parent"});
    };
    // Details Tab Init
    var initDetails = function() {
        $('.time').timeago();
        var logoProperties = $.parseJSON(decodeURIComponent($('#logo_properties').data('props')));
        $( ".slider" ).each(function(index, object) {
            var value = parseInt(logoProperties[index]);
            $(object).slider({
                disabled: true,
                value: value,
                min: 1,
                max: 9,
                step: 1
            });
        });
        // Refresh Comments
        fetchPitchComments();
        // Reinit Socials
        setTimeout(function() {
            $('.social-likes').socialLikes();
        }, 2000);

        $('#pitch_rating').raty({
            path: '/img',
            hintList: ['не то!', 'так себе', 'возможно', 'хорошо', 'отлично'],
            starOn: 'solution-star-on.png',
            starOff: 'solution-star-off.png',
            size: 24,
            readOnly: $('#pitch_rating').data('read'),
            start: $('#pitch_rating').data('rating'),
            click: function(score, evt) {
                $.post('/rating/save.json',
                    {"id": $(this).data('pitchid'), "rating": score}, function(response) {
                    });
                $('#take-part').show('fast');
            }
        });

        $('.btn-success').on('click', function() {
            {
                $.post('/rating/takePart.json', {"id": $(this).data('pitchid')});
                $('#take-part').hide('fast');
            }
        });

    };
    // Designers Tab Init
    var initDesigners = function() {
        checkScrollers();
    };
    return {
        historyChange: function() {
            var url = window.location.pathname;
            activateTab($('a[href="' + window.location.pathname + '"]'));
            $(window).off('scroll');
            var $container = $('.gallery_container');
            $container.html('<img id="search-ajax-loader" src="/img/blog-ajax-loader.gif" style="margin: 60px 0 100px 400px;">');
            $.get(url, {fromTab: true}, function(response) {
                var $replacement = $(response).find('.gallery_container');
                $container.hide().html($replacement).fadeIn();
                gallerySwitch.tabInit();
            });
        },
        tabInit: function() {
            if (window.location.pathname.indexOf('view') != -1) { // Gallery Tab Init
                initGallery();
            } else if (window.location.pathname.indexOf('details') != -1) { // Details Tab Init
                initDetails();
            } else if (window.location.pathname.indexOf('designers') != -1) { // Designers Tab Init
                initDesigners();
            }
        }
    };
}());

$(document).mouseup(function(e) {
    var container = $("#take-part");
    if (container.has(e.target).length === 0) {
        container.hide('fast');
    }
});