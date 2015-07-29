$(document).ready(function () {
    function preload(arrayOfImages) {
        $(arrayOfImages).each(function () {
            $('<img/>')[0].src = this;
            // Alternatively you could use:
            // (new Image()).src = this;
        });
    }

    preload([
        '/img/partners/logo_tutdesign_on.png',
        '/img/partners/surfinbird_on.png',
        '/img/partners/clodo_on.png',
        '/img/partners/zucker_on.png',
        '/img/partners/trends_on.png',
        '/img/fb_on.png',
        '/img/tw_on.png',
        '/img/vk_on.png',
        '/img/instagram_on.png'
    ]);

    $('#UserShortCompanyName').keyup(function () {
        var charsEntered = $(this).val().length;
        var counterElement = $('.character-count');
        var maximumChars = counterElement.data('maxchars');
        var diff = maximumChars - charsEntered;
        counterElement.text(diff);
        if ((maximumChars - charsEntered) < 0) {
            counterElement.addClass('red');
        } else {
            counterElement.removeClass('red');
        }
    });

    var registrationElement = $("#registration");
    registrationElement.validate({
        /*debug: true,*/
        rules: {
            // simple rule, converted to {required:true}
            first_name: "required",
            // compound rule
            email: {
                required: true,
                email: true
            },
            password: {
                required: true,
                minlength: 5
            },
            confirm_password: {
                required: true,
                minlength: 5,
                equalTo: "#UserConfirmPassword"
            },
            short_company_name: {
                required: {
                    depends: function(element) {
                        return $('input[value=company]').is(':checked')
                    }
                },
                minlength: 5,
                maxlength: $('.character-count').data('maxchars')
            }
        },
        messages: {
            first_name: {
                required: "Имя обязательно"
            },
            last_name: {
                required: "Фамилия обязательна"
            },
            email: {
                required: "Email обязателен",
                email: "Email обязателен"
            },
            password: {
                required: "Пароль обязателен",
                minlength: "Надо больше символов"
            },
            confirm_password: {
                required: "Подтвердите пароль",
                minlength: "Надо больше символов",
                equalTo: "Пароли не совпадают"
            },
            short_company_name: {
                required: 'Название обязательно',
                minlength: "Надо больше символов",
                maxlength: "Надо меньше символов"
            }
        },
        highlight: function (element, errorClass) {
            $(element).fadeOut(function () {
                $(element).fadeIn();
            });
        }
    });

    registrationElement.on('submit', function (e) {
        e.preventDefault();
        if($('.error:visible').length == 0) {
            $.post($(this).attr('action') + '.json', $(this).serialize(), function (response) {
                if (response.who_am_i == 'designer') {
                    user_popup_register();
                } else {
                    window.location.href = response.redirect;
                }
            });
        }
    });

    $('input[name=who_am_i]').on('change', function() {
        var selectedUserType = $(this).val();
        if(selectedUserType == 'company') {
            $('.short-company-name').show();
            $('.character-count').show();
        }else {
            $('.short-company-name').hide();
            $('.character-count').hide();
        }
    })

    $('#UserEmail').blur(function () {
        $.post('/users/checkform.json', {"email": this.value}, function (response) {
            return (response.data);
        })
    });

    $('#login-button').click(function () {
        $("#reg-section").fadeOut(100, function () {
            $("#login-section").fadeIn(100);
        });

        return false;
    });

    $('#reg-button').click(function () {
        $("#login-section").fadeOut(100, function () {
            $("#reg-section").fadeIn(100);
        });
        return false;
    });

    $('#requesthelplink').on('click', function () {
        $('#loading-overlay2').modal({
            containerId: 'spinner',
            opacity: 80,
            close: false
        });
        $('.simplemodal-wrap').css('overflow', 'visible');
        $('#reqname').focus();
        return false;
    })

    /* Social networks widgets modal*/
    if (!$.browser.mobile) {
        if (showSocialPopup) {
            setTimeout(function () {
                appendSocials();
            }, 5000);
        }
        if (needSocialWrite) {
            $.post('/users/addsocial/' + needSocialWrite + '.json');
        }
        // Mobile Popup
        if (showMobilePopup) {
            setTimeout(function () {
                appendMobile();
            }, 5000);
        }
        if (showMailPopup) {
            setTimeout(function () {
                appendEmailConfirm();
            }, 1000);
        }
        $('#feedback-link').show();
        $('#feedback-link').live('mouseover', function () {
            $(this).css('left', '0');
        })

        $('#feedback-link').live('mouseout', function () {
            $(this).css('left', '-5px');
        })
    } else {
        $('#feedback-link').hide();
    }

    $('input[name=case]').val('fu27fwkospf');

    $('#reqsend').on('click', function () {
        $('#reqmessage').text()
        if ($('#reqtarget').val() == '0') {
            alert('Выберите адресата!');
        } else if (($('#reqemail').val() == '') || ($('#reqemail').val() == 'ВАШ EMAIL')) {
            alert('Укажите email для связи!');
        } else if (($('#reqmessage').val() == '') || ($('#reqmessage').val() == 'ОПИШИТЕ ПРОБЛЕМУ И ЗАДАЙТЕ ВОПРОС')) {
            alert('Введите ваше сообщение!');
        } else {
            //var sendobj = {"name": $('#reqname').val(), "email": $('#email').val(), "message": $('#reqmessage').val()}

            var sendobj = {
                "name": $('#reqname').val(),
                "email": $('#reqemail').val(),
                "target": $('#reqtarget').val(),
                "message": $('#reqmessage').val(),
                "case": $('input[name=case]').val(),
                "to": $('#reqto').val(),
                "info": clientInfo()
            };
            $.post('/users/requesthelp.json', sendobj, function (response) {
                if (response.success == 'true') {
                    $('#reqmainform').hide();
                    $('#reqformthankyou').show();
                } else {
                }
            })
        }
        return false;
    })

    $('#reqto').on('keyup', function () {

        $('#contactlist').show();
        return false;
    })

    $(".reqlink").on('click', function () {
        $('#reqtarget').val($(this).data('id'));
        $('#reqto').val($(this).text());
        $('#reqto').removeClass('placeholder');
        $('#contactlist').hide();
        return false;
    })

    $('html:not(#contactlist)').on('click', function () {
        if ($('#loading-overlay2').is(':visible')) {
            $('#contactlist').hide();
        }
    })

    $('.close-request').on('click', function () {
        $.modal.close();
        return false;
    })

    $('#requesthelpselector, .requestli, #reqto').on('mouseover', function () {
        $('img', '#requesthelpselector').attr('src', '/img/request_help_menu_button_hover.png')
        $('#contactlist').show();
        return false;
    })

    $('#requesthelpselector, .requestli, #reqto').on('mouseout', function () {
        $('img', '#requesthelpselector').attr('src', '/img/requestselector.png')
        $('#contactlist').hide();
        return false;
    });

    $('.vkontakte-logon').click(function () {
        window.location.href = '/vkontakte';
    });

    $('.facebook-logon').click(function () {
        FB.login(function (response) {
            if (response.authResponse) {
                var accessToken = response.authResponse.accessToken;
                FB.api('/me', function (response) {
                    var fbResponse = response;
                    var registerUrl = '/register.json';
                    response.accessToken = accessToken;
                    if (($('#invite').length == 1) && ($('#invite').val() != '')) {
                        registerUrl += '?invite=' + $('#invite').val();
                    }
                    $.post(registerUrl, response, function (response) {
                        var ourResponse = response;
                        if (ourResponse.newuser == true) {
                            $('#popup-after-facebook').modal({
                                containerId: 'after-fb-popup',
                                opacity: 80,
                                closeClass: 'gotest-close',
                                onClose: function () {
                                    user_set_status($('#setStatus'));
                                }
                            });
                        } else {
                            if (ourResponse.redirect != false) {
                                window.location = ourResponse.redirect;
                            } else {
                                window.location = '/';
                            }
                        }
                        /*if(ourResponse.newuser == false) {
                         var params = {};
                         params['message'] = 'Test Message';
                         params['name'] = 'Dmitriy';
                         params['description'] = 'My test message';
                         params['link'] = 'http://godesigner.ru/';
                         params['picture'] = 'http://summer-mourning.zoocha.com/uploads/thumb.png';
                         params['caption'] = 'My Label';
                         FB.api('/me/feed', 'post', params, function(response) {
                         if (!response || response.error) {
                         console.log(response);
                         alert('Error occured - ' + response.error);
                         
                         } else {
                         alert('Published to stream - you might want to delete it now!');
                         }
                         });
                         }*/
                    });
                    /*FB.logout(function(response) {
                     console.log('Logged out.');
                     });*/
                });
            }
        }, {scope: 'email,publish_actions'});
    });

    $('#setStatus').on('submit', function (e) {
        e.preventDefault();
        user_set_status($(this));
    });

    var user_set_status = function ($form) {
        $('.simplemodal-container, .simplemodal-overlay').fadeOut();
        $.modal.close();
        $.post($form.attr('action') + '.json', $form.serialize(), function (response) {
            if ((response.result === true) && (response.status == 'designer')) {
                user_popup_register();
            } else {
                window.location.href = '/';
            }
        }, 'json');
    };

    var user_popup_register = function () {
        $('#popup-register').modal({
            containerId: 'gotest-popup',
            opacity: 80,
            closeClass: 'gotest-close',
            onClose: function () {
                $('.simplemodal-container, .simplemodal-overlay').fadeOut();
                $.modal.close();
                window.location.href = '/pitches';
            }
        });
    };

    $(document).on('mouseover', '.removeTag', function() {
        $('img', this).attr('src', '/img/delete-tag-hover.png');
    });

    $(document).on('mouseout', '.removeTag', function() {
        $('img', this).attr('src', '/img/delete-tag.png');
    });

    $(document).on('click', '#fav', function () {
        var link = $(this)
        var data = {"pitch_id": link.data('pitchid')};
        var type = link.data('type');
        if (type == 'add') {
            var newtype = 'remove';
            var newText = 'Перестать следить';
        } else {
            var newtype = 'add';
            var newText = 'Следить за проектом';
        }
        if(link.hasClass('rss-img')) {
            link.text(newText);
        }else {
            if(link.css('background-image').match(/follow_the_pitch/)) {
                link.addClass('fav-minus');
                link.css('background-image', 'http://www.godesigner.ru/img/stop_follow.png');
            }else if (link.css('background-image').match(/stop_follow/)) {
                link.removeClass('fav-minus');
                link.addClass('fav-plus');
                link.css('background-image', 'http://www.godesigner.ru/img/follow_the_pitch.png');
            }
        }
        $.post('/favourites/' + type + '.json', data, function (response) {
            link.data('type', newtype);
        });
        return false;
    })

    $('.deleteheader').click(function () {
        var id = $(this).data('id');
        $.post('/pitches/delete/' + id + '.json', function () {
            $('tr[data-id="' + id + '"]').hide();
            var visibleRows = $('tr:visible', '#header-table').length
            if (visibleRows == 0) {
                $('#pitch-panel').hide();
            }
        })
        return false;
    })

    $('.favourites').hover(function () {
        $(this).attr('src', '/img/social_plus.png');
    }, function () {
        $(this).attr('src', '/img/1.gif');
    })

    $('.facebook').hover(function () {
        $(this).attr('src', '/img/social_fb.png');
    }, function () {
        $(this).attr('src', '/img/1.gif');
    })

    $('.twitter').hover(function () {
        $(this).attr('src', '/img/social_twitter.png');
    }, function () {
        $(this).attr('src', '/img/1.gif');
    })

    $('#closepanel').click(function () {
        $('#panel').hide();
        $('.conteiner').css('margin-top', 0);
        $('.main').css('margin-top', 0);
        $('.conteiners').css('margin-top', 0);
        $('.middle_inner').css('margin-top', 0);
        if (($('#slides').length > 0) || ($('.conteiner').length > 0)) {
            $('header').css('margin-bottom', '89px');
        } else {
            $('header').css('margin-bottom', '0');
        }
        $('body').removeClass('show-panel');
    })

    $('#closepanel').mouseover(function () {
        $(this).css('background', 'url("/img/panel/close-btn2.png") repeat scroll 0 0 transparent');
    })

    $('#closepanel').mouseout(function () {
        $(this).css('background', 'url("/img/panel/close-btn.png") repeat scroll 0 0 transparent');
    })

    $('.header-menu-item').on('mouseover', function () {
        $(this).removeClass('header-menu-item').addClass('header-menu-item-higlighted');
    })
    $('.header-menu-item').on('mouseout', function () {
        $(this).removeClass('header-menu-item-higlighted').addClass('header-menu-item');
    })
    var menuOpen = false;
    $('.avatar-top, .name-top').on('mouseover', function () {
        menuOpen = true;
        $('#menu_arrow').css('padding-top', '5px').attr('src', '/img/arrow_down_header.png');
        $('.header-menu').fadeIn(300);
    })
    $('.header-menu').on('mouseenter', function () {
        menuOpen = true;
    });
    $('.avatar-top, .name-top, .header-menu').on('mouseleave', function () {
        menuOpen = false;
        setTimeout(function () {
            if (menuOpen == false) {
                $('.header-menu').fadeOut(300, function () {
                    $('#menu_arrow').css('padding-top', '3px').attr('src', '/img/arrow_header_up.png');
                });
            }
        }, 100);
    });
    if ($('.breadcrumbs-view', $('#pitch-title')).height() > 36) {
        $('#pitch-title').height($('.breadcrumbs-view', $('#pitch-title')).height() + 10)
    }

    // Countdown
    if($("#countdown").length > 0) {
        $("#countdown").countdown({
            date: $("#countdown").data('deadline'), // Change this to your desired date to countdown to
            format: "on",
            showEmptyDays: 'off'
        });
    }

    if($(".countdown").length > 0) {
        $(".countdown").countdownheader({
            format: "on",
            showEmptyDays: 'off'
        });
    }

    $('#changeEmail').validate({
        errorPlacement: function (error, element) {
            if ($('input[name="email"]').val() && $('input[name="confirmEmail"]').val()) {
                element.after(error);
            }
        },
        rules: {
            email: {
                required: true,
                email: true,
                equalTo: "#conf_email",
                remote: {
                    url: "/users/checkform.json",
                    type: "post",
                    dataFilter: function (data) {
                        var json = JSON.parse(data);
                        if (json.data === false) {
                            return '"true"';
                        }
                        return '"Email уже используется"';
                    }
                }
            },
            confirmEmail: {
                required: true
            }
        },
        messages: {
            email: {
                required: "Email обязателен",
                email: "Email обязателен",
                equalTo: "Адреса не совпадают"
            },
            confirmEmail: {
                required: "Email обязателен"
            }
        },
        highlight: function (element, errorClass) {
            $(element).fadeOut(function () {
                $(element).fadeIn();
            });
        },
        submitHandler: function (form) {
            $.post("/users/profile.json", {email: $('#first_email').val()}, function (data) {
                $('.simplemodal-container').fadeOut(800);
                $('#simplemodal-overlay').fadeOut(800, function () {
                    $.modal.close();
                });
            });
        }
    });
//    $('#confirmEmail').on('click', function() {
//        if (($('input[name="emails"]').val()) && ($('input[name="emails"]').val() == $('input[name="confirmEmail"]').val())) {
//            if (filter.test($('input[name="emails"]').val()) || filter.test($('input[name="confirmEmail"]').val())) {
//                $.post("/users/profile.json", { email:$('input[name="emails"]').val() },function(data){
//                    $('.simplemodal-container').fadeOut(800);
//                    $('#simplemodal-overlay').fadeOut(800, function() {
//                        $.modal.close();
//                    });
//                });
//            }
//        }
//    });
    $(document).on('click', '.popup-decline', function() {
        $('#popup-title').text('«' + $(this).data('title') + '»');
        $('#popup-title').attr('href', '/pitches/view/' + $(this).data('pitchid'));
        $('#popup-num').text('#' + $(this).data('solutionnum'));
        $('#popup-num').attr('href', '/pitches/viewsolution/' + $(this).data('solutionid'));
        $('.change-mind').data('solutionid', $(this).data('solutionid'))
        $('#popip-active-id').val($(this).data('pitchid'));
        $('#popup-decline-warning').modal({
            containerId: 'spinner',
            opacity: 80,
            closeClass: 'mobile-close'
        });
        return false;
    })

    $('.accept-confirm').on('click', function() {
        // пользователь отказался
        var id = $('#popip-active-id').val();
        $.get('/pitches/decline/' + id + '.json', function(response) {
        });
        // убираем строчку в верхней панели
        $('tr[data-id="' + id + '"]', '#pitch-panel').hide();
        // убираем всю панель, если в ней не осталось видимых строчек
        if($('tr:visible', '#pitch-panel').length == 0) {
            $('#pitch-panel').hide();
        }
        // если мы находимы на странице заверешния, делаем редирект
        if(window.location.href.indexOf("users/step") > -1) {
            window.location.href = 'http://www.godesigner.ru/users/mypitches';
        }
        $('.mobile-close').click();
        return false;
    })

    $('.change-mind').on('click', function() {
        // пользователь согласился продать
        var id = $('#popip-active-id').val();
        var solutionid = $(this).data('solutionid')
        $.get('/pitches/accept/' + id + '.json', function(response) {
            // переносим его на завершение
            if(window.location.href.indexOf("users/step2") > -1) {
            }else {
                window.location.href = 'http://www.godesigner.ru/users/step2/' + solutionid;
            }
        });
        $('.mobile-close').click();
        return false;
    })

});

window.fbAsyncInit = function () {
    FB.init({
        appId: '202765613136579', // App ID
        channelUrl: '//godesigner.ru/channel.html', // Channel File
        status: true, // check login status
        cookie: true, // enable cookies to allow the server to access the session
        oauth: true, // enable OAuth 2.0
        xfbml: true,  // parse XFBM
        version: 'v2.0'
    });

    $(document).on('click', '.top-button', function() {
        _gaq.push(['_trackEvent', 'Создание проекта', 'Пользователь перешел на выбор категории', 'Кнопка "Создать проект"']);
        return true;
    });

    $(document).on('click', '.bottom-link-footer', function() {
        _gaq.push(['_trackEvent', 'Создание проекта', 'Пользователь перешел на выбор категории', 'Ссылка "Создать проект" в футере']);
        return true;
    });

    $(document).on('click', '.mainpage-create-project', function() {
        _gaq.push(['_trackEvent', 'Создание проекта', 'Пользователь перешел на выбор категории', 'Ссылка "Заказчику" на главной']);
        return true;
    });

    $(document).on('click', '.create-project-how-it-works', function() {
        _gaq.push(['_trackEvent', 'Создание проекта', 'Пользователь перешел на выбор категории', 'Ссылка "Заполнить бриф" на странице "Как это работает?"']);
        return true;
    })

};

(function (d) {
    var js, id = 'facebook-jssdk';
    if (d.getElementById(id)) {
        return;
    }
    js = d.createElement('script');
    js.id = id;
    js.async = true;
    js.src = "//connect.facebook.net/en_US/all.js";
    d.getElementsByTagName('head')[0].appendChild(js);
}(document));

/*
 * Pitch files upload/delete handler
 */
function onSelectHandler(file, fileIds, Cart) {
    if ($('#filename').html() != 'Файл не выбран') {
        $('#filezone').html($('#filezone').html() + '<li data-id=""><a style="float:left;width:200px" class="filezone-filename" href="#">' + file.name + '</a><a class="filezone-delete-link" style="float:right;width:100px;margin-left:0" href="#">удалить</a><div style="clear:both"></div></li>');
    } else {
        $('#filezone').html($('#filezone').html() + '<li data-id=""><a style="float:left;width:100px" class="filezone-filename" href="#">' + file.name + '</a><a style="float:right;width:100px;margin-left:0" class="filezone-delete-link" href="#">удалить</a><div style="clear:both"></div></li>');
    }

    var self = this;
    var uploadId = this.damnUploader('addItem', {
        file: file,
        onProgress: function (percents) {
            $('#progressbar').text(percents + '%');
            var progresspx = Math.round(3.4 * percents);
            if (progresspx > 330) {
                progresspx == 330;
            }
            $('#filler').css('width', progresspx);
            if (percents > 95) {
                $('#progressbarimage').css('background', 'url(/img/indicator_full.png)');
            } else {
                $('#progressbarimage').css('background', 'url(/img/indicator_empty.png)');
            }
        },
        onComplete: function (successfully, data, errorCode) {
            var dataObj = $.parseJSON(data);
            fileIds.push(dataObj.id);
            if ((successfully) && (data.match(/(\d*)/))) {
                //alert('Файл '+file.name+' загружен, полученные данные: '+data);
            } else {
                alert('Ошибка при загрузке. Код ошибки: ' + errorCode); // errorCode содержит код HTTP-ответа, либо 0 при проблеме с соединением
            }
            if (self.damnUploader('itemsCount') == 0) {
                $.merge(Cart.fileIds, fileIds);
                Cart.saveData();
                $.modal.close();
            }
        }
    });

    var lastChild = $('#filezone').children(':last');
    var link = $('.filezone-delete-link', lastChild).attr('data-delete-id', uploadId);

    return false; // отменить стандартную обработку выбора файла
}

/*
 * New Comment field validation. Check if real text in field.
 */
function isCommentValid(text) {
    var regex = '#\\d+,'; // #15,
    regex += '|#\\d+\\s{1},', // #15 ,
            regex += '|#\\d+', // #15
            regex += '|@\\S+\\s{1}\\S{1}\\.,', // @Дмитрий Н.,
            regex += '|@\\S+\\s\\S{1}\\. ,', // @Дмитрий Н. ,
            regex += '|@\\S+\\s\\S{1}', // @Дмитрий Н
            re = new RegExp(regex, 'g');
    var newString = text.replace(re, '');
    if (newString.match(/\S/)) {
        return true;
    }
    return false;
}

/*
 * Warning Modal
 */
function warningModal() {
    // Warn Placeholder behavior
    var warnPlaceholder = 'ВАША ЖАЛОБА';
    $('#warn-comment, #warn-solution').on('focus', function () {
        $(this).removeAttr('placeholder');
    });
    $('#warn-comment, #warn-solution').on('blur', function () {
        $(this).attr('placeholder', warnPlaceholder);
    });

    // Warn Comment
    $('body, .solution-overlay').on('click', '.warning-comment', function () {
        $('#sendWarnComment').data('url', $(this).data('url'));
        $('#sendWarnComment').data('commentId', $(this).data('commentId'));
        $('#popup-warning-comment').modal({
            containerId: 'final-step',
            opacity: 80,
            closeClass: 'popup-close',
            onShow: function () {
                $('#warn-comment').val('');
            }
        });
        return false;
    });
    $('#sendWarnComment').on('click', function () {
        var url = $(this).data('url');
        var id = $(this).data('commentId');
        if (($('#warn-comment').val().length > 0) && ($('#warn-comment').val() != warnPlaceholder)) {
            $.post(url, {"text": $('#warn-comment').val(), "id": id}, function (response) {
                warningThanks();
            });
        } else {
            alert('Введите текст жалобы!');
        }
    });

    // Warn Solution
    $('body, .solution-overlay').on('click', '.warning', function (e) {
        e.preventDefault();
        $('#sendWarn').data('url', $(this).attr('href'));
        $('#popup-warning').modal({
            containerId: 'final-step',
            opacity: 80,
            closeClass: 'popup-close',
            onShow: function () {
                $('#warn-solution').val('');
            }
        });
        return false;
    });
    $('#sendWarn').on('click', function () {
        var url = $(this).data('url');
        if (($('#warn-solution').val().length > 0) && ($('#warn-solution').val() != warnPlaceholder)) {
            $.post(url, {"text": $('#warn-solution').val()}, function (response) {
                warningThanks();
            });
        } else {
            alert('Введите текст жалобы!');
        }
    });
}

/*
 * Fetch Comments data from response. Hierarchial
 */
function fetchCommentsNew(result) {
    var fetchedComments = '';
    $.each(result.comments, function (idx, comment) {
        var commentData = prepareCommentData(comment, result);
        fetchedComments += populateComment(commentData);
        if (comment.child) {
            var commentChildData = prepareCommentData(comment.child, result);
            fetchedComments += populateComment(commentChildData);
        }
    });
    return fetchedComments;
}

function prepareCommentData(comment, result) {
    var commentData = {};
    var expertsObj = result.experts || {};
    commentData.commentId = comment.id;
    commentData.commentUserId = comment.user.id;
    commentData.commentText = comment.text;
    commentData.commentPlainText = comment.originalText.replace(/"/g, "\'");
    commentData.commentType = (comment.user_id == result.pitch.user_id) ? 'client' : 'designer';
    commentData.isExpert = isExpert(comment.user_id, expertsObj);
    commentData.isClosedPitch = (result.pitch.status != 0) ? 1 : 0;
    commentData.publicClass = (comment.public == 1) ? ' public-comment' : ' private-comment';

    if (result.pitch.user_id == comment.user_id) {
        commentData.messageInfo = 'message_info2';
    } else if (comment.user.isAdmin == "1") {
        commentData.messageInfo = 'message_info4';
        commentData.isAdmin = comment.user.isAdmin;
    } else if (commentData.isExpert) {
        commentData.messageInfo = 'message_info5';
    } else {
        commentData.messageInfo = 'message_info1';
    }

    commentData.userAvatar = comment.avatar;
    commentData.commentAuthor = comment.user.first_name + (((comment.user.last_name == null) || (comment.user.last_name.length == 0)) ? '' : (' ' + comment.user.last_name.substring(0, 1) + '.'));
    commentData.isCommentAuthor = (currentUserId == comment.user_id) ? true : false;

    // Date Time
    var postDateObj = getProperDate(comment.created);
    commentData.postDate = ('0' + postDateObj.getDate()).slice(-2) + '.' + ('0' + (postDateObj.getMonth() + 1)).slice(-2) + '.' + ('' + postDateObj.getFullYear()).slice(-2);
    commentData.postTime = ('0' + postDateObj.getHours()).slice(-2) + ':' + ('0' + (postDateObj.getMinutes())).slice(-2);
    commentData.relImageUrl = '';
    if (comment.solution_url) {
        commentData.relImageUrl = comment.solution_url.solution_solutionView.weburl;
    }
    if (comment.isChild == 1) {
        commentData.isChild = 1;
    }
    if (comment.hasChild) {
        commentData.hasChild = 1;
    }
    if (comment.needAnswer) {
        commentData.needAnswer = 1;
    }
    return commentData;
}

/*
 * Populate each comment layout
 */
function populateComment(data) {
    var toolbar = '';
    var manageToolbar = '<a href="/comments/delete/' + data.commentId + '" style="float:right;" class="delete-link-in-comment ajax">Удалить</a> \
                        <a href="#" style="float:right;" class="edit-link-in-comment" data-id="' + data.commentId + '" data-text="' + data.commentPlainText + '">Редактировать</a>';
    var answerTool = ' display: none;';
    if (data.needAnswer == 1) {
        answerTool = '';
    }
    if (isCurrentAdmin != 1 && isClient != 1 && data.isClosedPitch) {
        answerTool = ' display: none;';
    }
    var userToolbar = '<a href="#" data-comment-id="' + data.commentId + '" data-comment-to="' + data.commentAuthor + '" class="replyto reply-link-in-comment" style="float:right;' + answerTool + '">Ответить</a> \
                        <a href="#" data-comment-id="' + data.commentId + '" data-url="/comments/warn.json" class="warning-comment warn-link-in-comment" style="float:right;">Пожаловаться</a>';
    if (data.isCommentAuthor) {
        toolbar = manageToolbar;
    } else if (currentUserId) {
        toolbar = userToolbar;
    }
    if (isCurrentAdmin == 1) {
        toolbar = manageToolbar + userToolbar;
    }
    var avatarElement = '';
    if (!data.isAdmin) {
        if (!data.isExpert) {
            avatarElement = '<a href="/users/view/' + data.commentUserId + '"> \
                            <img src="' + data.userAvatar + '" alt="Портрет пользователя" width="41" height="41"> \
                            </a>';
        } else {
            avatarElement = '<a href="/experts/view/' + data.isExpert + '"> \
                            <img src="' + data.userAvatar + '" alt="Портрет пользователя" width="41" height="41"> \
                            </a>';
        }
    }
    var sectionClass = '';
    if (data.isChild == 1) {
        sectionClass = 'is-child ';
    }
    if(data.publicClass == ' private-comment') {
        var commentTitle = "Этот комментарий приватный";
        var commentImage = 'private-comment-eye.png';
    }else {
        var commentTitle = "Этот комментарий виден всем";
        var commentImage = 'public-comment-eye.png';
    }
    return '<section class="' + sectionClass + '" data-id="' + data.commentId + '" data-type="' + data.commentType + '"> \
                <div class="separator"></div> \
                <div class="' + data.messageInfo + '">'
            + avatarElement +
            '<a href="/users/view/' + data.commentUserId + '" data-comment-id="' + data.commentId + '" data-comment-to="' + data.commentAuthor + '"> \
                    <span>' + data.commentAuthor + '</span><br /> \
                    <span style="font-weight: normal;">' + data.postDate + ' ' + data.postTime + '</span> \
                </a> \
                <div class="clr"></div> \
                </div> \
                <div data-id="' + data.commentId + '" class="message_text' + data.publicClass + '"> \
                    <a href="#" class="tooltip_comments" title="' + commentTitle + '" style="position: absolute; \
    top: 0;right: 0;"><img src="/img/' + commentImage + '" alt="' + commentTitle + '"></a><span class="regular comment-container">'
            + data.commentText +
            '</span> \
                </div> \
                <div class="toolbar-wrapper"><div class="toolbar">'
            + toolbar +
            '</div></div> \
                <div class="clr"></div> \
                <div class="hiddenform" style="display:none"> \
                    <section> \
                        <form style="margin-bottom: 25px;" action="/comments/edit/' + data.commentId + '" method="post"> \
                            <textarea name="text" data-id="' + data.commentId + '"></textarea> \
                            <input type="button" src="/img/message_button.png" value="Отправить" class="button editcomment" style="margin-left:16px;margin-bottom:5px; width: 200px;"><br> \
                            <span style="margin-left:25px;" class="supplement3">Нажмите Esс, чтобы отменить</span> \
                            <div class="clr"></div> \
                        </form> \
                    </section> \
                </div> \
            </section>';
}

/**
 * Solution Copyrighted Materials Info
 */
function copyrightedInfo(data) {
    var res = '';
    $.each(data.filename, function (idx, filename) {
        res += '<p>' + filename + '<br />';
        res += (data.source[idx]) ? '<a href="/urls/' + data.source[idx] + '">http://godesigner.ru/urls/' + data.source[idx] + '</a><br />' : '';
        res += (data.needtobuy[idx] == 'on') ? '<span class="alert">нужно покупать</span>' : 'не нужно покупать';
        res += '</p>';
    });
    res = '<div class="solution-info solution-copyrighted chapter"> \
               <h2>ДОП. МАТЕРИАЛЫ</h2>'
            + res +
            '<div class="separator"></div> \
          </div>';
    return res;
}

function isExpert(user, expertsObj) {
    var res = false;
    for (var key in expertsObj) {
        if (expertsObj[key].user_id == user) {
            res = expertsObj[key].id;
            break;
        }
    }
    return res;
}

/*
 * Fetch and populate Comments via AJAX on the pitch solutions gallery page
 */
function fetchPitchComments() {
    $.getJSON('/pitches/getcommentsnew/' + pitchNumber + '.json', function (result) {
        if (result.comments) {
            var $commentsTitle = $('<div class="separator" style="width: 810px; margin-left: 30px; margin-top: 25px;"></div>');
            if ($('#newComment').length == 0 && $('.gallery_postload').is(':visible')) {
                $commentsTitle.hide();
            }
            $('.pitch-comments').html(fetchCommentsNew(result));
            $('.pitch-comments').prepend($commentsTitle);
            $('.separator', '.pitch-comments section:first').remove();
        } else {
            $('.ajax-loader', '.pitch-comments').remove();
        }
        solutionTooltip();
        $('.tooltip_comments').tooltip({
            tooltipID: 'tooltip_comments',
            width: '282px',
            positionLeft: -30,
            positionTop: -180,
            borderSize: '0px',
            tooltipPadding: 0,
            tooltipBGColor: 'transparent'
        });
    });
}

/*
 * Comments Toolbar
 */
function enableToolbar() {
    $(document).on('mouseenter', '.pitch-comments section, .solution-comments section', function () {
        $('.toolbar', this).fadeIn(200);
    });
    $(document).on('mouseleave', '.pitch-comments section, .solution-comments section', function () {
        $('.toolbar', this).fadeOut(200);
    });

    // Reply to Question
    $('body, .solution-overlay').on('click', '.replyto', function () {
        toggleAnswer($(this));
        return false;
    });

    // Send Answer Comment
    $('body, .solution-overlay').on('click', '.answercomment', function (event) {
        addAnswerComment($(event.target));
    });

    // Delete Comment
    $('body, .solution-overlay').on('click', '.delete-link-in-comment.ajax', function (e) {
        e.preventDefault();
        commentDeleteHandler($(e.target));
        return false;
    });

    // Edit Comment
    var editcommentflag = false;
    $('body, .solution-overlay').on('click', '.edit-link-in-comment', function (e) {
        e.preventDefault();
        var section = $(this).parent().parent().parent();
        section.children().not('.separator').hide();
        var hiddenform = $('.hiddenform', section);
        hiddenform.show();
        var text = $(this).data('text');
        $('textarea', hiddenform).val(text);
        editcommentflag = true;
        return false;
    });
    $('body, .solution-overlay').on('click', '.editcomment', function () {
        var section = $(this).closest('section[data-id]');
        var textarea = section.find('textarea');
        var newcomment = textarea.val();
        var id = textarea.data('id');
        $.post('/comments/edit/' + id + '.json', {"text": newcomment}, function (response) {
            var newText = response;
            $('.edit-link-in-comment', 'section[data-id=' + id + ']').data('text', newcomment);
            $('.comment-container', 'section[data-id=' + id + ']').html(newText);
            section.children().show();
            $('.hiddenform', section).hide();
            editcommentflag = false;
        });
        return false;
    });

    // Escaping
    $(document).keyup(function (e) {
        if (e.keyCode == 27) {
            if (editcommentflag == true) {
                e.stopPropagation();
                editcommentflag = false;
                $.each($('.hiddenform:visible'), function (index, object) {
                    var section = $(object).parent();
                    section.children().show();
                    $(object).hide();
                });
            } else {
                e.preventDefault();
                hideSolutionPopup();
            }
        }
    });

    // Select Winner Solution
    $('body, .solution-overlay').on('click', '.select-winner-popup', function () {
        $('#winner-num').text('#' + $(this).data('num'));
        $('#winner-num').attr('href', '/pitches/viewsolution/' + $(this).data('solutionid'));
        $('#winner-user-link').text($(this).data('user'));
        $('#winner-user-link').attr('href', '/users/view/' + $(this).data('userid'));
        $('#confirmWinner').data('url', $(this).attr('href'));
        var item = $('.select-winner[data-solutionid=' + $(this).data('solutionid') + ']').parent().parent().parent().prev().prev();
        if (item.length > 0) {
            item = item.clone();
        } else {
            item = $('<div id="replacingblock" class="photo_block"> \
                        <a href="#" onClick="return false;"><img alt="" src="' + solutionThumbnail + '"></a> \
                        <div class="photo_opt"> \
                        <span class="rating_block"><img alt="" src="/img/' + $('.rating-image', '.solution-rating').attr('data-rating') + '-rating.png"></span> \
                        <span class="like_view" style="margin-top:1px;"><img class="icon_looked" alt="" src="/img/looked.png"><span>' + $('.isField.value-views').text() + '</span> \
                        <a data-id="57" class="like-small-icon" href="#"><img alt="" src="/img/like.png"></a><span>' + $('.isField.value-likes').text() + '</span></span> \
                        <span class="bottom_arrow"><a class="solution-menu-toggle" href="#"><img alt="" src="/img/marker5_2.png"></a></span> \
                    </div>');
        }
        $('#replacingblock').replaceWith(item);
        $('#popup-final-step').modal({
            containerId: 'final-step',
            opacity: 80,
            closeClass: 'popup-close'
        });
        return false;
    });

    // Solution Actions for Pitch owner
    $('body, .solution-overlay').on('click', '.client-hide', function (e) {
        e.preventDefault();
        var link = $(this);
        var underlyingHide = $('.hide-item[data-to=' + $('.isField.number', '.solution-overlay').text() + ']');
        if (underlyingHide.length > 0) {
            underlyingHide.click();
        } else {
            $.get('/solutions/hide/' + $(this).data('id') + '.json', function (response) {
            });
        }
        link.replaceWith('<a class="client-show" href="#" data-id="' + link.data('id') + '">Сделать видимой</a>');
        return false;
    });

    $('body, .solution-overlay').on('click', '.client-show', function (e) {
        e.preventDefault();
        var link = $(this);
        var underlyingUnhide = $('.unhide-item[data-to=' + $('.isField.number', '.solution-overlay').text() + ']');
        if (underlyingUnhide.length > 0) {
            underlyingUnhide.click();
        } else {
            $.get('/solutions/unhide/' + $(this).data('id') + '.json', function (response) {
            });
        }
        link.replaceWith('<a class="client-hide" href="#" data-id="' + link.data('id') + '">С глаз долой</a>');
        return false;
    });

    // Add New Comment Form Handler
    $('body, .solution-overlay').on('click', '.createComment, #rating_comment_send', function (e) {
        e.preventDefault();
        var textarea = $(this).closest('form').find('textarea');
        var form = $(this).closest('form');
        var button = $(this);
        var addSolution = $(this).data('solution_id');
        addSolution = (typeof (addSolution) != 'undefined') ? addSolution + ', ' : '';
        if (typeof (solutionId) == 'undefined') {
            var solutionId = 0;
        }
        if (isCommentValid(textarea.val())) {
            var is_public = $(this).data('is_public');
            button.css('color', '#9bafba');
            if(is_public) {
                var loader = $('.public-loader', form);
            }else {
                var loader = $('.private-loader', form);
            }
            loader.show();
            $.post('/comments/add.json', {
                'text': addSolution + textarea.val(),
                'solution_id': solutionId,
                'comment_id': '',
                'pitch_id': pitchNumber,
                'public': is_public,
                'fromAjax': 1
            }, function (result) {
                var commentData = preparePitchCommentData(result);
                // ViewSolution or Popup
                if ($('.solution-comments').length > 0) {
                    $('.solution-comments').prepend(populateComment(commentData));
                    if (result.result.solution_num) {
                        textarea.val('#' + result.result.solution_num + ', ');
                    } else {
                        textarea.val('');
                    }
                }
                // Pitch Gallery
                if ($('.pitch-comments').length > 0) {
                    $('.pitch-comments section:first').prepend('<div class="separator"></div>');
                    $(populateComment(commentData)).insertAfter('.pitch-comments .separator:first');
                    $('.separator', '.pitch-comments section:first').remove();
                    textarea.val('');
                }
                button.css('color', '#ffffff');
                loader.hide();
            });
        } else {
            alert('Введите текст комментария!');
            return false;
        }
    });

    // Comment Textarea Tooltip
    if (isClient) {
        $('.createCommentForm').on('focus', '#newComment', function () {
            var el = $('<div id="tooltip-bubble"> \
                            <div style="background:url(/img/tooltip-bottom-bg2.png) no-repeat scroll 0 100% transparent; padding: 10px 10px 22px 16px;height:100px;"> \
                                <div style="" id="tooltipContent" class="supplement3"> \
                                    <p>Укажите номер комментируемого варианта, используя хештег #. Например: \
                                    #2, нравится!<br> \
                                    Обратитесь к автору решения, используя @. Например:<br> \
                                    @username, спасибо! \
                                    </p> \
                                </div> \
                            </div> \
                        </div>');
            var position = $(this).position();
            position.top -= 135;
            el.insertBefore($(this));
            el.css(position).fadeIn(200);
        });

        $('.createCommentForm').on('blur', 'textarea', function () {
            $('#tooltip-bubble').fadeOut(200, function () {
                $(this).remove();
            });
        });

        $('.createCommentForm').on('keydown', 'textarea', function () {
            $('#tooltip-bubble').fadeOut(200, function () {
                $(this).remove();
            });
        });
    }

    // Enable Comment-to Action
    $('body, .solution-overlay').on('click', '.mention-link', function (e) {
        e.preventDefault();
        var el = $('#newComment');
        if ($('.allow-comments').is(':visible')) {
            el = $('#newComment', '.allow-comments');
        }
        if ((el.val().match(/^#\d/ig) == null) && (el.val().match(/@\W*\s\W\.,/) == null)) {
            $('input[name=comment_id]').val('');
            var prepend = '@' + $(this).data('commentTo') + ', ';
            var newText = prepend + el.val();
            el.val(newText);
        }
        return false;
    });

    // Scroll to Comment Form on Viewsolution Panel
    $('body, .solution-overlay').on('click', '.client-comment', function () {
        $.scrollTo($('#newComment', '.allow-comments'), {duration: 250});
        return false;
    });

    warningModal();
}

function solutionTooltip() {
    // Solutions Tooltips
    $('.hoverimage[data-comment-to]').tooltip({
        tooltipID: 'tooltip2',
        tooltipSource: 'data',
        width: '205px',
        correctPosX: 40,
        //positionTop: 0,
        borderSize: '0px',
        tooltipPadding: 0,
        tooltipBGColor: 'transparent'
    });
}

function commentDeleteHandler(link) {
    var section = link.closest('section');
    var id = $(section).attr('data-id');


    // Delete without Moderation
    if (!isCurrentAdmin) {
        commentDelete(link, section, id);
        return false;
    }

    $('#model_id', '#popup-delete-comment').val(id);

    // Show Delete Moderation Overlay
    $('#popup-delete-comment').modal({
        containerId: 'final-step-clean',
        opacity: 80,
        closeClass: 'popup-close',
        onShow: function () {
            $(document).off('click', '#sendDeleteComment');
        }
    });

    // Delete Comment Popup Form
    $(document).on('click', '#sendDeleteComment', function () {
        var form = $(this).parent().parent();
        if (!$('input[name=reason]:checked', form).length || !$('input[name=penalty]:checked', form).length) {
            $('#popup-delete-comment').addClass('wrong-input');
            return false;
        }
        var $spinner = $(this).next();
        $spinner.addClass('active');
        $(document).off('click', '#sendDeleteComment');
        var data = form.serialize();
        $.post(form.attr('action') + '.json', data).done(function (result) {
            commentDelete(link, section, id);
            $spinner.removeClass('active');
            $('.popup-close').click();
        });
        return false;
    });
}

// Instant Delete Comment
function commentDelete(link, section, id) {
    $.post(link.attr('href') + '.json', function (result) {
        if (result == 'true') {
            if ($('.solution-overlay').is(':visible')) {
                var sectionPitch = $('.messages_gallery section[data-id=' + id + ']');
                // Enable Answer Link in Parent
                if (section.hasClass('is-child')) {
                    var parentSection = section.prev();
                    var parentSectionPitch = sectionPitch.prev();
                    parentSection.find('.reply-link-in-comment').css('display', 'block');
                    parentSectionPitch.find('.reply-link-in-comment').css('display', 'block');
                }
                // Detect and Remove Child Section
                var childSection = section.next('section.is-child');
                var childSectionPitch = sectionPitch.next('section.is-child');
                if (childSection.length > 0) {
                    childSection.remove();
                }
                if (childSectionPitch.length > 0) {
                    childSectionPitch.remove();
                }
                section.remove();
                sectionPitch.remove();
            } else {
                // Enable Answer Link in Parent
                if (section.hasClass('is-child')) {
                    var parentSection = section.prev();
                    parentSection.find('.reply-link-in-comment').css('display', 'block');
                }
                // Detect and Remove Child Section
                var childSection = section.next('section.is-child');
                if (childSection.length > 0) {
                    childSection.remove();
                }
                section.remove();
            }
            $('.separator', '.pitch-comments section:first').remove();
        }
    });
    return true;
}

/*
 * Get Proper Date object from MySQL datetime string
 */
function getProperDate(dateStr) {
    var a = dateStr.split(' ');
    var d = a[0].split('-');
    var t = a[1].split(':');
    return new Date(d[0], (d[1] - 1), d[2], t[0], t[1], t[2]);
}

/*
 * Append Socials Modal
 */
function appendSocials() {
    $('#socials-modal').modal({
        containerId: 'spinner',
        opacity: 80,
        close: false,
        onShow: function () {
            $('#socials-modal').fadeTo(600, 1);
            VK.Widgets.Group("vk_groups", {mode: 0, width: '300', height: '290', color1: 'FFFFFF', color2: '2B587A', color3: '5B7FA6'}, 36153921);
        },
        onClose: function () {
            $('.simplemodal-container').fadeOut(800, function () {
                $(this).remove();
            });
            $('#simplemodal-overlay').fadeOut(800, function () {
                $(this).remove();
            });
            $('#socials-modal').removeClass('simplemodal-data');
            $.modal.impl.d = {};
        }
    });
}

/*
 * Append Mobile Modal
 */
function appendMobile() {
    $('#mobile-popup').modal({
        containerId: 'spinner',
        opacity: 80,
        closeClass: 'mobile-close',
        onShow: function () {
            $('#mobile-popup').fadeTo(600, 1);
        },
        onClose: function () {
            $('.simplemodal-container').fadeOut(800);
            $('#simplemodal-overlay').fadeOut(800, function () {
                $.modal.close();
            });
        }
    });
}

/*
 * Append EmailConfirm Modal
 */
function appendEmailConfirm() {
    $('#popup-email-change').modal({
        containerId: 'gotest-email-warning',
        opacity: 80,
        closeClass: 'emailChange-close',
        onShow: function () {
            $('#popup-email-change').fadeTo(600, 1);
        },
        onClose: function () {
            $('.simplemodal-container').fadeOut(800);
            $('#simplemodal-overlay').fadeOut(800, function () {
                $.modal.close();
            });
        }
    });
}

/*
 * Show/Hide Answer Form
 */
function toggleAnswer(link) {
    if (link.hasClass('active')) {
        link.closest('section').next('.answer-section').slideUp(600, function () {
            $(this).remove();
            link.text('Ответить');
            link.removeClass('active');
        });
        return;
    }
    var messageInfo = 'message_info1';
    if (isCurrentExpert) {
        messageInfo = 'message_info5';
    }
    if (isCurrentAdmin) {
        messageInfo = 'message_info4';
    }
    if (isClient) {
        messageInfo = 'message_info2';
    }
    var answerButtons = '<input type="button" src="/img/message_button.png" value="Ответить" class="button answercomment" data-is_public="0" style="float: left; width: 150px;">';
    if (isCurrentExpert || isCurrentAdmin || isClient) {
        answerButtons = '<input type="button" src="/img/message_button.png" value="Публиковать вопрос и ответ для всех" class="button answercomment" data-is_public="1" style="float: left;"> \
        <input type="button" src="/img/message_button.png" value="Ответить только дизайнеру" class="button answercomment" data-is_public="0" style="float: right;">';
    }
    // Date Time
    var postDateObj = new Date();
    var postDate = ('0' + postDateObj.getDate()).slice(-2) + '.' + ('0' + (postDateObj.getMonth() + 1)).slice(-2) + '.' + ('' + postDateObj.getFullYear()).slice(-2);
    var postTime = ('0' + postDateObj.getHours()).slice(-2) + ':' + ('0' + (postDateObj.getMinutes())).slice(-2);
    var avatarElement = '';
    if (!isCurrentAdmin) {
        avatarElement = '<a href="/users/view/' + currentUserId + '"> \
                        <img src="' + currentAvatar + '" alt="Портрет пользователя" width="41" height="41"> \
                        </a>';
    }
    var el = $('<section style="display: none; position: relative;" class="answer-section"> \
                    <div class="separator"></div> \
                    <div class="' + messageInfo + '">'
            + avatarElement +
            '<a href="#" onClick="return false;"> \
                            <span>' + currentUserName + '</span><br /> \
                            <span style="font-weight: normal;">' + postDate + ' ' + postTime + '</span> \
                        </a> \
                        <div class="clr"></div> \
                    </div> \
                    <div class="message_text"> \
                        <div class="hiddenform"> \
                            <section> \
                                <form style="margin-left: 0;" action="/comments/add.json" method="post"> \
                                    <textarea name="text" data-question-id="' + link.data('comment-id') + '"></textarea><br>'
            + answerButtons +
            '<div class="clr"></div> \
                                </form> \
                            </section> \
                        </div> \
                    </div> \
                    <div class="clr"></div> \
                </section>');
    var section = link.closest('section');
    el.insertAfter(section).slideDown(600, function () {
        link.text('Не отвечать');
        link.addClass('active');
    });
}

function preparePitchCommentData(result) {
    var commentData = {};
    var expertsObj = result.experts || {};
    commentData.commentId = result.comment.id;
    commentData.commentUserId = result.comment.user_id;
    commentData.commentText = result.comment.text;
    commentData.commentPlainText = result.comment.originalText.replace(/"/g, "\'");
    commentData.commentType = (result.comment.user_id == result.comment.pitch.user_id) ? 'client' : 'designer';
    commentData.isExpert = isExpert(result.comment.user_id, expertsObj);
    commentData.isClosedPitch = (result.comment.pitch.status != 0) ? 1 : 0;
    commentData.publicClass = (result.comment.public == 1) ? ' public-comment' : ' private-comment';

    if (result.comment.pitch.user_id == result.comment.user_id) {
        commentData.messageInfo = 'message_info2';
    } else if (result.comment.user.isAdmin == "1") {
        commentData.messageInfo = 'message_info4';
        commentData.isAdmin = result.comment.user.isAdmin;
    } else if (commentData.isExpert) {
        commentData.messageInfo = 'message_info5';
    } else {
        commentData.messageInfo = 'message_info1';
    }

    if (result.userAvatar) {
        commentData.userAvatar = result.userAvatar;
    } else {
        commentData.userAvatar = '/img/default_small_avatar.png';
    }

    if (result.comment.question_id != 0) {
        commentData.isChild = 1;
    }

    commentData.commentAuthor = result.comment.user.first_name + (((result.comment.user.last_name == null) || (result.comment.user.last_name.length == 0)) ? '' : (' ' + result.comment.user.last_name.substring(0, 1) + '.'));
    commentData.isCommentAuthor = (currentUserId == result.comment.user_id) ? true : false;

    // Date Time
    var postDateObj = getProperDate(result.comment.created);
    commentData.postDate = ('0' + postDateObj.getDate()).slice(-2) + '.' + ('0' + (postDateObj.getMonth() + 1)).slice(-2) + '.' + ('' + postDateObj.getFullYear()).slice(-2);
    commentData.postTime = ('0' + postDateObj.getHours()).slice(-2) + ':' + ('0' + (postDateObj.getMinutes())).slice(-2);
    return commentData;
}

function addAnswerComment(button) {
    var textarea = button.closest('section').find('textarea');
    var is_public = button.data('is_public');
    if (typeof (solutionId) == 'undefined') {
        var solutionId = 0;
    }
    if (isCommentValid(textarea.val())) {
        var currentSection = button.closest('.answer-section');
        var answerLink = currentSection.prev().find('.reply-link-in-comment');
        currentSection.animate({opacity: .3}, 500, function () {
            var el = $('<div class="ajax-loader"></div>');
            $(this).append(el);
        });
        $.post('/comments/add.json', {
            'text': textarea.val(),
            'solution_id': solutionId,
            'question_id': textarea.data('question-id'),
            'pitch_id': pitchNumber,
            'public': is_public,
            'fromAjax': 1
        }).done(function (result) {
            var commentData = preparePitchCommentData(result);
            var el = populateComment(commentData);
            currentSection.animate({opacity: 0}, 500, function () {
                $(this).replaceWith(el);
                answerLink.removeClass('active').text('Ответить');
                if ($('.solution-overlay').is(':visible')) {
                    var sectionPitch = $('.messages_gallery section[data-id=' + textarea.data('question-id') + ']');
                    $(el).insertAfter(sectionPitch);
                    var answerLinkPitch = sectionPitch.find('.reply-link-in-comment');
                    answerLinkPitch.removeClass('active').text('Ответить');
                }
            });
        });
    } else {
        alert('Введите текст комментария!');
        return false;
    }
}

function hideSolutionPopup() {
    if ($('.solution-overlay').is(':visible')) {
        window.history.pushState('object or string', 'Title', '/pitches/view/' + pitchNumber); // @todo Check params
        $(window).scrollTop(beforeScrollTop);
        $('#pitch-panel').show();
        $('.wrapper', 'body').first().removeClass('wrapper-frozen');
        $('.solution-overlay').hide().remove();
    }
}

/**
 * JavaScript Client Detection
 * (C) viazenetti GmbH (Christian Ludwig)
 */
function clientInfo() {
    {

        var unknown = 'Unbekannt';

        // screen
        var screenSize = '';
        if (screen.width) {
            width = (screen.width) ? screen.width : '';
            height = (screen.height) ? screen.height : '';
            screenSize += '' + width + " x " + height;
        }

        //browser
        var nVer = navigator.appVersion;
        var nAgt = navigator.userAgent;
        var browser = navigator.appName;
        var version = '' + parseFloat(navigator.appVersion);
        var majorVersion = parseInt(navigator.appVersion, 10);
        var nameOffset, verOffset, ix;

        // Opera
        if ((verOffset = nAgt.indexOf('Opera')) != -1) {
            browser = 'Opera';
            version = nAgt.substring(verOffset + 6);
            if ((verOffset = nAgt.indexOf('Version')) != -1) {
                version = nAgt.substring(verOffset + 8);
            }
        }
        // MSIE
        else if ((verOffset = nAgt.indexOf('MSIE')) != -1) {
            browser = 'Microsoft Internet Explorer';
            version = nAgt.substring(verOffset + 5);
        }
        // Chrome
        else if ((verOffset = nAgt.indexOf('Chrome')) != -1) {
            browser = 'Chrome';
            version = nAgt.substring(verOffset + 7);
        }
        // Safari
        else if ((verOffset = nAgt.indexOf('Safari')) != -1) {
            browser = 'Safari';
            version = nAgt.substring(verOffset + 7);
            if ((verOffset = nAgt.indexOf('Version')) != -1) {
                version = nAgt.substring(verOffset + 8);
            }
        }
        // Firefox
        else if ((verOffset = nAgt.indexOf('Firefox')) != -1) {
            browser = 'Firefox';
            version = nAgt.substring(verOffset + 8);
        }
        // IE 11
        else if (browser == 'Netscape') {
            var re = new RegExp("Trident/.*rv:([0-9]{1,}[\.0-9]{0,})");
            if (re.exec(nAgt) != null) {
                version = parseFloat(RegExp.$1).toString();
                browser = 'Internet Explorer';
            }
        }
        // Other browsers
        else if ((nameOffset = nAgt.lastIndexOf(' ') + 1) < (verOffset = nAgt.lastIndexOf('/'))) {
            browser = nAgt.substring(nameOffset, verOffset);
            version = nAgt.substring(verOffset + 1);
            if (browser.toLowerCase() == browser.toUpperCase()) {
                browser = navigator.appName;
            }
        }
        // trim the version string
        if ((ix = version.indexOf(';')) != -1)
            version = version.substring(0, ix);
        if ((ix = version.indexOf(' ')) != -1)
            version = version.substring(0, ix);

        majorVersion = parseInt('' + version, 10);
        if (isNaN(majorVersion)) {
            version = '' + parseFloat(navigator.appVersion);
            majorVersion = parseInt(navigator.appVersion, 10);
        }

        // mobile version
        var mobile = /Mobile|mini|Fennec|Android|iP(ad|od|hone)/.test(nVer);

        // cookie
        var cookieEnabled = (navigator.cookieEnabled) ? true : false;

        if (typeof navigator.cookieEnabled == 'undefined' && !cookieEnabled) {
            document.cookie = 'testcookie';
            cookieEnabled = (document.cookie.indexOf('testcookie') != -1) ? true : false;
        }

        // system
        var os = unknown;
        var clientStrings = [
            {s: 'Windows 3.11', r: /Win16/},
            {s: 'Windows 95', r: /(Windows 95|Win95|Windows_95)/},
            {s: 'Windows ME', r: /(Win 9x 4.90|Windows ME)/},
            {s: 'Windows 98', r: /(Windows 98|Win98)/},
            {s: 'Windows CE', r: /Windows CE/},
            {s: 'Windows 2000', r: /(Windows NT 5.0|Windows 2000)/},
            {s: 'Windows XP', r: /(Windows NT 5.1|Windows XP)/},
            {s: 'Windows Server 2003', r: /Windows NT 5.2/},
            {s: 'Windows Vista', r: /Windows NT 6.0/},
            {s: 'Windows 7', r: /(Windows 7|Windows NT 6.1)/},
            {s: 'Windows 8.1', r: /(Windows 8.1|Windows NT 6.3)/},
            {s: 'Windows 8', r: /(Windows 8|Windows NT 6.2)/},
            {s: 'Windows NT 4.0', r: /(Windows NT 4.0|WinNT4.0|WinNT|Windows NT)/},
            {s: 'Windows ME', r: /Windows ME/},
            {s: 'Android', r: /Android/},
            {s: 'Open BSD', r: /OpenBSD/},
            {s: 'Sun OS', r: /SunOS/},
            {s: 'Linux', r: /(Linux|X11)/},
            {s: 'iOS', r: /(iPhone|iPad|iPod)/},
            {s: 'Mac OS X', r: /Mac OS X/},
            {s: 'Mac OS', r: /(MacPPC|MacIntel|Mac_PowerPC|Macintosh)/},
            {s: 'QNX', r: /QNX/},
            {s: 'UNIX', r: /UNIX/},
            {s: 'BeOS', r: /BeOS/},
            {s: 'OS/2', r: /OS\/2/},
            {s: 'Search Bot', r: /(nuhk|Googlebot|Yammybot|Openbot|Slurp|MSNBot|Ask Jeeves\/Teoma|ia_archiver)/}
        ];
        for (var id in clientStrings) {
            var cs = clientStrings[id];
            if (cs.r.test(nAgt)) {
                os = cs.s;
                break;
            }
        }

        var osVersion = unknown;

        if (/Windows/.test(os)) {
            osVersion = /Windows (.*)/.exec(os)[1];
            os = 'Windows';
        }

        switch (os) {
            case 'Mac OS X':
                osVersion = /Mac OS X (10[\.\_\d]+)/.exec(nAgt)[1];
                break;

            case 'Android':
                osVersion = /Android ([\.\_\d]+)/.exec(nAgt)[1];
                break;

            case 'iOS':
                osVersion = /OS (\d+)_(\d+)_?(\d+)?/.exec(nVer);
                osVersion = osVersion[1] + '.' + osVersion[2] + '.' + (osVersion[3] | 0);
                break;

        }
    }

    window.jscd = {
        screen: screenSize,
        browser: browser,
        browserVersion: version,
        mobile: mobile,
        os: os,
        osVersion: osVersion,
        cookies: cookieEnabled
    };
    return window.jscd;
}

// Fetch URI query param by name
function getParameterByName(name) {
    name = name.replace(/[\[]/, "\\\[").replace(/[\]]/, "\\\]");
    var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
            results = regex.exec(location.search);
    return results == null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
}
// Countdown
(function (e) {
    e.fn.countdown = function (t, n) {
        function io() {
            var eventDate = r.date;
            var currentDate = Math.floor(Date.now() / 1000);
            var seconds = eventDate - currentDate;
            var days = Math.floor(seconds / 86400);
            seconds -= days * 60 * 60 * 24;
            var hours = Math.floor(seconds / 3600);
            seconds -= hours * 60 * 60;
            var minutes = Math.floor(seconds / 60);
            seconds -= minutes * 60;
            thisEl.find(".timeRefDays").text("дн");
            thisEl.find(".timeRefHours").text(":");
            thisEl.find(".timeRefMinutes").text(":");
            thisEl.find(".timeRefSeconds").text("");
            if (eventDate <= currentDate) {
                days = 0;
                hours = 0;
                minutes = 0;
                seconds = 0;
                clearInterval(interval)
            }
            if (r["format"] == "on") {
                //days = String(days).length >= 2 ? days : "0" + days;
                hours = String(hours).length >= 2 ? hours : "0" + hours;
                minutes = String(minutes).length >= 2 ? minutes : "0" + minutes;
                seconds = String(seconds).length >= 2 ? seconds : "0" + seconds
            }
            if ((r['showEmptyDays'] != 'on') && ((days == '0') || (days == '00'))) {
                thisEl.find(".timeRefDays").text('');
                days = '';
            }
            if ((r['isTest'] == 'on') && ((hours == '0') || (hours == '00')) && ((minutes == '0') || (minutes == '00')) && (seconds < 30)) {
                $('.test-timer').addClass('warning');
                if (seconds == '0' || seconds == '00') {
                    testSubmitForce = true;
                    $('#quiz_form').trigger('submit');
                }
            }
            if (!isNaN(eventDate)) {
                thisEl.find(".days").text(days);
                thisEl.find(".hours").text(hours);
                thisEl.find(".minutes").text(minutes);
                thisEl.find(".seconds").text(seconds)
            } else {

            }
            thisEl.css({opacity: 1});
        }
        var thisEl = e(this);
        var r = {
            date: null,
            format: null
        };
        t && e.extend(r, t);
        //i();
        var interval = setInterval(io, 1e3);
    }
})(jQuery);

// Countdown
(function (e) {
    e.fn.countdownheader = function (t, n) {
        function i() {
            var eventDate = thisEl.data('deadline')
            var currentDate = Math.floor(Date.now() / 1000);
            var seconds = eventDate - currentDate;
            var days = Math.floor(seconds / 86400);
            seconds -= days * 60 * 60 * 24;
            var hours = Math.floor(seconds / 3600);
            seconds -= hours * 60 * 60;
            var minutes = Math.floor(seconds / 60);
            seconds -= minutes * 60;
            if (eventDate <= currentDate) {
                days = 0;
                hours = 0;
                minutes = 0;
                seconds = 0;
                clearInterval(interval_header)
            }
            if (r["format"] == "on") {
                //days = String(days).length >= 2 ? days : "0" + days;
                hours = String(hours).length >= 2 ? hours : "0" + hours;
                minutes = String(minutes).length >= 2 ? minutes : "0" + minutes;
                seconds = String(seconds).length >= 2 ? seconds : "0" + seconds
            }
            if ((r['showEmptyDays'] != 'on') && ((days == '0') || (days == '00'))) {
                days = '';
            }else {
                days = String(days) + ' дн.';
            }
            if (!isNaN(eventDate)) {
                var string = days + ' ' + hours + ':' + minutes + ':' + seconds
                thisEl.text(string);
            } else {
            }
            thisEl.css({opacity: 1});
        }
        var thisEl = e(this);
        var r = {
            date: null,
            format: null
        };
        t && e.extend(r, t);
        //i();
        var interval_header = setInterval(i, 1e3);
    }
})(jQuery);

function warningThanks() {
    $('.popup-close').click();
    $('#popup-warning-thanks').modal({
        containerId: 'final-step-thanks',
        opacity: 80,
        closeClass: 'popup-close',
        overlayClose: true
    });
}
