$(document).ready(function() {
	$("#registration").validate({
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
     		}
   		},
   		highlight: function(element, errorClass) {
     		$(element).fadeOut(function() {
       			$(element).fadeIn();
     		});
  		}
	});

    $('#UserEmail').blur(function(){
        $.post('/users/checkform.json', {"email": this.value}, function(response) {
            return (response.data);
        })
    });

  $('#login-button').click(function() {
      $("#reg-section").fadeOut(100, function () {
          $("#login-section").fadeIn(100);
      });

      return false;
  });

  $('#reg-button').click(function() {
      $("#login-section").fadeOut(100, function () {
          $("#reg-section").fadeIn(100);
      });
      return false;
  });

    $('#requesthelplink').on('click', function() {
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
            setTimeout(function() { appendSocials(); }, 5000);
        }
        if (needSocialWrite) {
            $.post('/users/addsocial.json');
        }
        $('#feedback-link').show();
        $('#feedback-link').live('mouseover', function() {
            $(this).css('left', '0');
        })

        $('#feedback-link').live('mouseout', function() {
            $(this).css('left', '-5px');
        })
    }else {
        $('#feedback-link').hide();
    }


    $('input[name=case]').val('fu27fwkospf');

    $('#reqsend').on('click', function() {
        $('#reqmessage').text()
        if($('#reqtarget').val() == '0') {
            alert('Выберите адресата!');
        }else if(($('#reqemail').val() == '') || ($('#reqemail').val() == 'ВАШ EMAIL')) {
            alert('Укажите email для связи!');
        }else if(($('#reqmessage').val() == '') || ($('#reqmessage').val() == 'ОПИШИТЕ ПРОБЛЕМУ И ЗАДАЙТЕ ВОПРОС')) {
            alert('Введите ваше сообщение!');
        } else {
        //var sendobj = {"name": $('#reqname').val(), "email": $('#email').val(), "message": $('#reqmessage').val()}

            var sendobj = {
                "name": $('#reqname').val(),
                "email": $('#reqemail').val(),
                "target": $('#reqtarget').val(),
                "message": $('#reqmessage').val(),
                "case": $('input[name=case]').val(),
                "to": $('#reqto').val()
            };
            $.post('/users/requesthelp.json', sendobj, function(response) {
                if(response.success == 'true') {
                    $('#reqmainform').hide();
                    $('#reqformthankyou').show();
                }else {
                }
            })
        }
        return false;
    })

    $('#reqto').on('keyup', function() {

        $('#contactlist').show();
        return false;
    })

    $(".reqlink").on('click', function() {
        $('#reqtarget').val($(this).data('id'));
        $('#reqto').val($(this).text());
        $('#reqto').removeClass('placeholder');
        $('#contactlist').hide();
        return false;
    })

    $('html:not(#contactlist)').on('click', function() {
        if($('#loading-overlay2').is(':visible')) {
            $('#contactlist').hide();
        }
    })

    $('.close-request').on('click', function() {
        $.modal.close();
        return false;
    })

    $('#requesthelpselector, .requestli, #reqto').on('mouseover', function() {
        $('img', '#requesthelpselector').attr('src', '/img/request_help_menu_button_hover.png')
        $('#contactlist').show();
        return false;
    })

    $('#requesthelpselector, .requestli, #reqto').on('mouseout', function() {
        $('img', '#requesthelpselector').attr('src', '/img/requestselector.png')
        $('#contactlist').hide();
        return false;
    })
    
	$('.facebook-logon').click(function() {
		FB.login(function(response) {
	    	if (response.authResponse) {
	    	    var accessToken = response.authResponse.accessToken; 
	     		FB.api('/me', function(response) {
                    var fbResponse = response;
                    var registerUrl = '/register.json';
                    response.accessToken = accessToken;
                    if(($('#invite').length == 1) && ($('#invite').val() != '')) {
                        registerUrl += '?invite=' + $('#invite').val();
                    }
                    $.post(registerUrl, response, function(response) {
                        var ourResponse = response;
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
                        if (ourResponse.redirect != false) {
                            window.location = ourResponse.redirect;
                        } else {
                            window.location = '/';
                        }
                    });
	       			/*FB.logout(function(response) {
	         			console.log('Logged out.');
	       			});*/
	     		});
	   		}
	 	}, {scope: 'email,publish_stream,publish_actions'});
	});

    $('#fav').live('click', function() {
        var link = $(this)
        var data = { "pitch_id": link.data('pitchid')};
        var type= link.data('type');
        if(type == 'add') {
            var newtype = 'remove';
            var cssClass = 'fav-minus';
            var src = '/img/minus.png';
        }else{
            var newtype = 'add';
            var cssClass = 'fav-plus';
            var src = '/img/plus2.png';
        }
        $.post('/favourites/'+ type + '.json', data, function(response) {
            link.data('type', newtype);
            console.log($('img', link).attr('src'))
            if($('img', link).attr('src') != '/img/plusb.png' && $('img', link).attr('src') != '/img/plusb_2.png') {
                $('img', link).removeClass().addClass(cssClass).attr('src', src);
            }
        });
        return false;
    })

    $('.fav-plus').live('mouseover', function() {
        $(this).attr('src', '/img/plus2.png');
    })

    $('.fav-plus').live('mouseout', function() {
        $(this).attr('src', '/img/plus 2.png');
    })

    $('.fav-minus').live('mouseover', function() {
        $(this).attr('src', '/img/minus2.png');
    })

    $('.fav-minus').live('mouseout', function() {
        $(this).attr('src', '/img/minus.png');
    })

    $('.deleteheader').click(function(){
        var id = $(this).data('id');
        $.post('/pitches/delete/' + id + '.json', function() {
            $('tr[data-id="' + id + '"]').hide();
        })
        /*$.post('/pitches/delete/' + id + '.json', function() {
            $('tr[data-id="' + id + '"]').hide();
            $('.popup-close').click();
        })*/
        return false;
    })

    $('.favourites').hover(function() {
        $(this).attr('src', '/img/social_plus.png');
    }, function() {
        $(this).attr('src', '/img/1.gif');
    })

    $('.facebook').hover(function() {
        $(this).attr('src', '/img/social_fb.png');
    }, function() {
        $(this).attr('src', '/img/1.gif');
    })

    $('.twitter').hover(function() {
        $(this).attr('src', '/img/social_twitter.png');
    }, function() {
        $(this).attr('src', '/img/1.gif');
    })

    $('#closepanel').click(function() {
        $('#panel').hide();
        $('.conteiner').css('margin-top', 0);
        $('.main').css('margin-top', 0);
        $('.conteiners').css('margin-top', 0);
        $('.middle_inner').css('margin-top', 0);
        if(($('#slides').length > 0) || ($('.conteiner').length > 0) || ($('div.about_pitch').length == 1)){
            $('header').css('margin-bottom', '89px');
        }else {
            $('header').css('margin-bottom', '0');
        }
        $('body').removeClass('show-panel');
    })

    $('#closepanel').mouseover(function() {
        $(this).css('background', 'url("/img/panel/close-btn2.png") repeat scroll 0 0 transparent');
    })

    $('#closepanel').mouseout(function() {
        $(this).css('background', 'url("/img/panel/close-btn.png") repeat scroll 0 0 transparent');
    })
    if($('#panel').length == 1) {
        $('.middle_inner, .main').css('margin-top', '13px');
    }else {
        /*$('.conteiner').css('margin-top', 0);
        $('.main').css('margin-top', 0);
        $('.conteiners').css('margin-top', 0);
        $('.middle_inner').css('margin-top', 0);*/
        if(($('#pitch-panel').length != 1) || ($('#slides').length > 0) || ($('.conteiner').length > 0) || ($('div.about_pitch').length == 1)){
            if($('#worker-payment-data').length != 1) {
                //$('header').css('margin-bottom', '89px');
                if(($('.shadow').length != 0) || ($('.about_pitch').length != 0)){
                    $('header').css('margin-bottom', '89px');
                }else{
                    if($('#pitch-panel').length == 1){
                        $('.conteiner').css('margin-top', 0);
                        $('.main').css('margin-top', 0);
                        $('.conteiners').css('margin-top', 0);
                        $('.middle_inner').not('.user_view').css('margin-top', 0);
                    }
                }
            }

        }else {
            $('header').css('margin-bottom', '0');
        }
    }

    $('.header-menu-item').on('mouseover', function() {
        $(this).removeClass('header-menu-item').addClass('header-menu-item-higlighted');
    })
    $('.header-menu-item').on('mouseout', function() {
        $(this).removeClass('header-menu-item-higlighted').addClass('header-menu-item');
    })
    var menuOpen = false;
    $('.avatar-top, .name-top').on('mouseover', function(){
        menuOpen = true;
        $('#menu_arrow').css('padding-top', '5px').attr('src', '/img/arrow_down_header.png');
        $('.header-menu').fadeIn(300);
    })
    $('.header-menu').on('mouseenter', function() {
        menuOpen = true;
    });
    $('.avatar-top, .name-top, .header-menu').on('mouseleave', function() {
        menuOpen = false;
        setTimeout(function() {
            if(menuOpen == false){
                $('.header-menu').fadeOut(300, function() {
                    $('#menu_arrow').css('padding-top', '3px').attr('src', '/img/arrow_header_up.png');
                });
            }
        }, 100);
    });
    if($('.breadcrumbs-view', $('#pitch-title')).height() > 36) {
        $('#pitch-title').height($('.breadcrumbs-view', $('#pitch-title')).height() + 10)
    }



    //Цвет фона для текущих питчей
    //$('#current_pitch ul li:odd').css({backgroundColor: '#2f313a'});
    
    // Add New Comment Form Handler
    $('.createComment').on('click', function(e) {
        e.preventDefault();
        var textarea = $(this).closest('form').find('textarea');
        if (typeof(solutionId) == undefined) {
            var solutionId = 0;
        }
        if (isCommentValid(textarea.val())) {
            var is_public = $(this).data('is_public');
            $.post('/comments/add.json', {
                'text': textarea.val(),
                'solution_id': solutionId,
                'comment_id': '',
                'pitch_id': pitchNumber,
                'public': is_public,
                'fromAjax': 1
            }, function(result) {
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
                }
            });
        } else {
            alert('Введите текст комментария!');
            return false;
        }
    });
    
});

window.fbAsyncInit = function() {
    FB.init({
    	appId      : '202765613136579', // App ID
        channelUrl : '//godesigner.ru/channel.html', // Channel File
        status     : true, // check login status
        cookie     : true, // enable cookies to allow the server to access the session
        oauth      : true, // enable OAuth 2.0
        xfbml      : true  // parse XFBML
    });
    /*
    FB.Event.subscribe('edge.create',
        function(response) {
            var uid = '';
            FB.getLoginStatus(function(response) {
                if (response.status === 'connected') {
                    uid = response.authResponse.userID;
                }
            });
            var solutionId = $('#solution_id').val();
            $.post('/solutions/like/' + solutionId + '.json', {"uid": uid}, function(response) {
            });
        }
    );
    FB.Event.subscribe('edge.remove',
        function(response) {
            var uid = '';
            FB.getLoginStatus(function(response) {
                if (response.status === 'connected') {
                    uid = response.authResponse.userID;
                }
            });
            var solutionId = $('#solution_id').val();
            if(solutionId) {
                $.post('/solutions/unlike/' + solutionId + '.json', {"uid": uid}, function(response) {
                });
            }
        }
    );*/
};

// Load the SDK Asynchronously
//var testLoc = window.location.pathname.match(/\/pitches\/view\//);
//if(testLoc != null) {
(function(d){
    var js, id = 'facebook-jssdk'; if (d.getElementById(id)) {return;}
    js = d.createElement('script'); js.id = id; js.async = true;
    js.src = "//connect.facebook.net/en_US/all.js";
    d.getElementsByTagName('head')[0].appendChild(js);
}(document));
//}

/*
 * Pitch files upload/delete handler
 */
function onSelectHandler(file, fileIds, Cart) {
    if($('#filename').html() != 'Файл не выбран') {
        $('#filezone').html($('#filezone').html() + '<li data-id=""><a style="float:left;width:200px" class="filezone-filename" href="#">' + file.name + '</a><a class="filezone-delete-link" style="float:right;width:100px;margin-left:0" href="#">удалить</a><div style="clear:both"></div></li>');
    }else {
        $('#filezone').html($('#filezone').html() + '<li data-id=""><a style="float:left;width:100px" class="filezone-filename" href="#">' + file.name + '</a><a style="float:right;width:100px;margin-left:0" class="filezone-delete-link" href="#">удалить</a><div style="clear:both"></div></li>');
    }
    
    var self = this;
    var uploadId = this.damnUploader('addItem', {
        file: file,
            onProgress: function(percents) {
                $('#progressbar').text(percents + '%');
                var progresspx = Math.round(3.4 * percents);
                if(progresspx > 330) {
                    progresspx == 330;
                }
                $('#filler').css('width', progresspx);
                if(percents > 95) {
                    $('#progressbarimage').css('background', 'url(/img/indicator_full.png)');
                }else {
                    $('#progressbarimage').css('background', 'url(/img/indicator_empty.png)');
                }
            },
            onComplete: function(successfully, data, errorCode) {
                var dataObj = $.parseJSON(data);
                fileIds.push(dataObj.id);
                if ((successfully) && (data.match(/(\d*)/))) {
                    //alert('Файл '+file.name+' загружен, полученные данные: '+data);
                } else {
                    alert('Ошибка при загрузке. Код ошибки: '+errorCode); // errorCode содержит код HTTP-ответа, либо 0 при проблеме с соединением
                }
                if(self.damnUploader('itemsCount') == 0) {
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
    $('#warn-comment, #warn-solution').on('focus', function() {
        $(this).removeAttr('placeholder');
    });
    $('#warn-comment, #warn-solution').on('blur', function() {
        $(this).attr('placeholder', warnPlaceholder); 
    });
    
    // Warn Comment
    $('body, .solution-overlay').on('click', '.warning-comment', function() {
        $('#sendWarnComment').data('url', $(this).data('url'));
        $('#sendWarnComment').data('commentId', $(this).data('commentId'));
        $('#popup-warning-comment').modal({
            containerId: 'final-step',
            opacity: 80,
            closeClass: 'popup-close',
            onShow: function() {
                $('#warn-comment').val('');
            }
        });
        return false;
    });
    $('#sendWarnComment').on('click', function() {
        var url = $(this).data('url');
        var id = $(this).data('commentId');
        if(($('#warn-comment').val().length > 0) && ($('#warn-comment').val() != warnPlaceholder)) {
            $.post(url, {"text": $('#warn-comment').val(), "id": id}, function(response) {
                $('.popup-close').click();
            });
        }else {
            alert('Введите текст жалобы!');
        }
    });

    // Warn Solution
    $('body, .solution-overlay').on('click', '.warning', function(e) {
        e.preventDefault();
        $('#sendWarn').data('url', $(this).attr('href'));
        $('#popup-warning').modal({
            containerId: 'final-step',
            opacity: 80,
            closeClass: 'popup-close',
            onShow: function() {
                $('#warn-solution').val('');
            }
        });
        return false;
    });
    $('#sendWarn').on('click', function() {
        var url = $(this).data('url');
        if(($('#warn-solution').val().length > 0) && ($('#warn-solution').val() != warnPlaceholder)) {
            $.post(url, {"text": $('#warn-solution').val()}, function(response) {
                $('.popup-close').click();
            });
        }else {
            alert('Введите текст жалобы!');
        }
    });
}

/*
 * Fetch Comments data from response. Hierarchial
 */
function fetchCommentsNew(result) {
    var fetchedComments = '';
    $.each(result.comments, function(idx, comment) {
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
    
    if (result.pitch.user_id == comment.user_id) {
        commentData.messageInfo = 'message_info2';
    } else if (comment.user.isAdmin == "1") {
        commentData.messageInfo = 'message_info4';
        commentData.isAdmin = comment.user.isAdmin;
    } else if (commentData.isExpert) {
        commentData.messageInfo = 'message_info5';
    }else {
        commentData.messageInfo = 'message_info1';
    }
    
    commentData.userAvatar = comment.avatar;
    
    commentData.commentAuthor = comment.user.first_name + ((comment.user.last_name.length == 0) ? '' : (' ' + comment.user.last_name.substring(0, 1) + '.'));
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
    var answerTool = '<a href="#" data-comment-id="' + data.commentId + '" data-comment-to="' + data.commentAuthor + '" class="replyto reply-link-in-comment" style="float:right; display: none;">Ответить</a>';
    if (data.needAnswer == 1) {
        answerTool = '<a href="#" data-comment-id="' + data.commentId + '" data-comment-to="' + data.commentAuthor + '" class="replyto reply-link-in-comment" style="float:right;">Ответить</a>';
    }
    if ((data.isChild == 1) || (data.hasChild == 1)) {
        answerTool = '<a href="#" data-comment-id="' + data.commentId + '" data-comment-to="' + data.commentAuthor + '" class="replyto reply-link-in-comment" style="float:right; display: none;">Ответить</a>';
    }
    var userToolbar = answerTool + '<a href="#" data-comment-id="' + data.commentId + '" data-url="/comments/warn.json" class="warning-comment warn-link-in-comment" style="float:right;">Пожаловаться</a>';
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
        avatarElement = '<a href="/users/view/' + data.commentUserId + '"> \
                        <img src="' + data.userAvatar + '" alt="Портрет пользователя" width="41" height="41"> \
                        </a>';
    }
    var sectionClass = '';
    if (data.isChild == 1) {
        sectionClass = 'is-child ';
    }
    return '<section class="' + sectionClass + '" data-id="' + data.commentId + '" data-type="' + data.commentType + '"> \
                <div class="separator"></div> \
                <div class="' + data.messageInfo + '">'
                + avatarElement +
                '<a href="#" data-comment-id="' + data.commentId + '" data-comment-to="' + data.commentAuthor + '" class="replyto"> \
                    <span>' + data.commentAuthor + '</span><br /> \
                    <span style="font-weight: normal;">' + data.postDate + ' ' + data.postTime + '</span> \
                </a> \
                <div class="clr"></div> \
                </div> \
                <div data-id="' + data.commentId + '" class="message_text"> \
                    <span class="regular comment-container">'
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

/*
 * Solution Actions for Pitch owner
 */
function solutionShowHide() {
    $('.client-hide').on('click', function(e) {
        e.preventDefault();
        var link = $(this);
        $.get('/solutions/hide/' + $(this).data('id') + '.json', function(response) {
            link.replaceWith('<a class="client-show" href="#" data-id="' + link.data('id') + '">Сделать видимой</a>');
            solutionShowHide();
        });
        return false;
    });
    
    $('.client-show').on('click', function(e) {
        e.preventDefault();
        var link = $(this);
        $.get('/solutions/unhide/' + $(this).data('id') + '.json', function(response) {
            link.replaceWith('<a class="client-hide" href="#" data-id="' + link.data('id')  + '">С глаз долой</a>');
            solutionShowHide();
        });
        return false;
    });
}

/**
 * Solution Copyrighted Materials Info
 */
function copyrightedInfo(data) {
    var res = '';
    $.each(data.filename, function(idx, filename) {
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
            res = true;
            break;
        }
    }
    return res;
}

/*
 * Fetch and populate Comments via AJAX on the pitch solutions gallery page
 */
function fetchPitchComments() {
    $.getJSON('/pitches/getcommentsnew/' + pitchNumber + '.json', function(result) {
        if (result.comments) {
            var commentsTitle = '<div class="separator" style="width: 810px; margin-left: 30px; margin-top: 25px;"></div>';
            $('.pitch-comments').html(fetchCommentsNew(result));
            $('.pitch-comments').prepend(commentsTitle);
            $('.separator', '.pitch-comments section:first').remove();
        } else {
            $('.ajax-loader', '.pitch-comments').remove();
        }
    });
}

/*
 * Comments Toolbar
 */
function enableToolbar() {
    $(document).on('mouseenter', '.pitch-comments section, .solution-comments section', function() {
        $('.toolbar', this).fadeIn(200);
    });
    $(document).on('mouseleave', '.pitch-comments section, .solution-comments section', function() {
        $('.toolbar', this).fadeOut(200);
    });
    
    // Reply to Question
    $('body, .solution-overlay').on('click', '.replyto', function() {
        toggleAnswer($(this));
        return false;
    });
    
    // Send Answer Comment
    $('body, .solution-overlay').on('click', '.answercomment', function(event) {
        addAnswerComment($(event.target));
    });

    // Delete Comment
    $('body, .solution-overlay').on('click', '.delete-link-in-comment.ajax', function(e) {
        e.preventDefault();
        commentDeleteHandler($(e.target));
        return false;
    });
    
    // Edit Comment
    var editcommentflag = false;
    $('body, .solution-overlay').on('click', '.edit-link-in-comment', function(e) {
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
    $('body, .solution-overlay').on('click', '.editcomment', function() {
        var section = $(this).closest('section[data-id]');
        var textarea = section.find('textarea');
        var newcomment = textarea.val();
        var id = textarea.data('id');
        $.post('/comments/edit/' + id + '.json', {"text": newcomment}, function(response) {
            var newText = response;
            $('.edit-link-in-comment', 'section[data-id=' + id + ']').data('text', newcomment);
            $('.comment-container', 'section[data-id=' + id + ']').html(newText);
            section.children().show();
            $('.hiddenform', section).hide();
            editcommentflag = false;
        });
        return false;
    });
    
    // Solutions Tooltips
    $('.hoverimage[data-comment-to]').tooltip({
        tooltipID: 'tooltip2',
        tooltipSource: 'hidden',
        width: '205px',
        correctPosX: 40,
        //positionTop: 0,
        borderSize: '0px',
        tooltipPadding: 0,
        tooltipBGColor: 'transparent'
    });

    // Escaping
    $(document).keyup(function(e) {
        if (e.keyCode == 27) {
            if (editcommentflag == true) {
                e.stopPropagation();
                editcommentflag = false;
                $.each($('.hiddenform:visible'), function(index, object) {
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
    $('body, .solution-overlay').on('click', '.select-winner-popup', function() {
        var item = $('.select-winner[data-solutionid=' + $(this).data('solutionid') + ']').parent().parent().parent().prev().prev().clone();
        $('#winner-num').text('#' + $(this).data('num'));
        $('#winner-num').attr('href', '/pitches/viewsolution/' + $(this).data('solutionid'));
        $('#winner-user-link').text($(this).data('user'));
        $('#winner-user-link').attr('href', '/users/view/' + $(this).data('userid'));
        $('#confirmWinner').data('url', $(this).attr('href'));
        if (item.length > 0) {
            $('#replacingblock').replaceWith(item);
        }
        $('#popup-final-step').modal({
            containerId: 'final-step',
            opacity: 80,
            closeClass: 'popup-close'
        });
        return false;
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
        onShow: function() {
            $(document).off('click', '#sendDeleteComment');
        }
    });
    
 // Delete Comment Popup Form
    $(document).on('click', '#sendDeleteComment', function() {
        var form = $(this).parent().parent();
        if (!$('input[name=reason]:checked', form).length || !$('input[name=penalty]:checked', form).length) {
            $('#popup-delete-comment').addClass('wrong-input');
            return false;
        }
        $(document).off('click', '#sendDeleteComment');
        var data = form.serialize();
        $.post(form.attr('action') + '.json', data).done(function(result) {
            commentDelete(link, section, id);
            $('.popup-close').click();
        });
        return false;
    });
}

// Instant Delete Comment
function commentDelete(link, section, id) {
    $.post(link.attr('href') + '.json', function(result) {
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
    return new Date(d[0], (d[1]-1), d[2], t[0], t[1], t[2]);
}

/*
 * Append Socials Modal
 */
function appendSocials() {
    $('#socials-modal').modal({
        containerId: 'spinner',
        opacity: 80,
        close: false,
        onShow: function() {
            $('#socials-modal').fadeTo(600, 1);
            VK.Widgets.Group("vk_groups", {mode: 0, width: '300', height: '290', color1: 'FFFFFF', color2: '2B587A', color3: '5B7FA6'}, 36153921);
        },
        onClose: function() {
            $('.simplemodal-container').fadeOut(800, function() { $(this).remove(); });
            $('#simplemodal-overlay').fadeOut(800, function() { $(this).remove(); });
            $('#socials-modal').removeClass('simplemodal-data');
            $.modal.impl.d = {};
        }
    });
}

/*
 * Show/Hide Answer Form
 */
function toggleAnswer(link) {
    if (link.hasClass('active')) {
        link.closest('section').next('.answer-section').slideUp(600, function() {
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
                    <div class="message_text" style="margin-top: 0;"> \
                        <div class="hiddenform"> \
                            <section> \
                                <form style="margin-left: 0;" action="/comments/add.json" method="post"> \
                                    <textarea name="text" data-question-id="' + link.data('comment-id') + '">@' + link.data('comment-to') + ', </textarea><br> \
                                    <input type="button" src="/img/message_button.png" value="Публиковать вопрос и ответ для всех" class="button answercomment" data-is_public="1" style="margin: 15px 8px 15px 0; font-size: 11px; padding-left: 28px"> \
                                    <input type="button" src="/img/message_button.png" value="Ответить только дизайнеру" class="button answercomment" data-is_public="0" style="margin: 15px 0 15px 8px; font-size: 11px;"> \
                                    <div class="clr"></div> \
                                </form> \
                            </section> \
                        </div> \
                    </div> \
                    <div class="clr"></div> \
                </section>');
    var section = link.closest('section');
    el.insertAfter(section).slideDown(600, function() {
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

    if (result.comment.pitch.user_id == result.comment.user_id) {
        commentData.messageInfo = 'message_info2';
    } else if (result.comment.user.isAdmin == "1") {
        commentData.messageInfo = 'message_info4';
        commentData.isAdmin = result.comment.user.isAdmin;
    } else if (commentData.isExpert) {
        commentData.messageInfo = 'message_info5';
    }else {
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

    commentData.commentAuthor = result.comment.user.first_name + ((result.comment.user.last_name.length == 0) ? '' : (' ' + result.comment.user.last_name.substring(0, 1) + '.'));
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
    if (typeof(solutionId) == undefined) {
        var solutionId = 0;
    }
    if (isCommentValid(textarea.val())) {
        var currentSection = button.closest('.answer-section');
        var answerLink = currentSection.prev().find('.reply-link-in-comment');
        currentSection.animate({opacity: .3}, 500, function() {
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
        }).done(function(result) {
            var commentData = preparePitchCommentData(result);
            var el = populateComment(commentData);
            currentSection.animate({opacity: 0}, 500, function() {
                $(this).replaceWith(el);
                answerLink.removeClass('active').text('Ответить').hide();
                if ($('.solution-overlay').is(':visible')) {
                    var sectionPitch = $('.messages_gallery section[data-id=' + textarea.data('question-id') + ']');
                    $(el).insertAfter(sectionPitch);
                    var answerLinkPitch = sectionPitch.find('.reply-link-in-comment');
                    answerLinkPitch.removeClass('active').text('Ответить').hide();
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
        $('#pitch-panel').show();
        $('.wrapper', 'body').first().removeClass('wrapper-frozen');
        $('.solution-overlay').hide();
    }
}
