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

    $('#reqto').on('click', function() {
        $('#contactlist').show();
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

    $('#requesthelpselector').on('click', function() {
        $('#contactlist').toggle();
        return false;
    })

	$('.facebook-logon').click(function() {
		FB.login(function(response) {
	    	if (response.authResponse) {
	     		FB.api('/me', function(response) {
                    var fbResponse = response;
                    var registerUrl = '/register.json';

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
                        if((ourResponse.redirect != false) && (ourResponse.redirect == '/users/banned')){
                            window.location = ourResponse.redirect;
                        }else{
                            $.get('/users/avatar.json', fbResponse, function(response){
                                if(ourResponse.redirect != false) {
                                    window.location = ourResponse.redirect;
                                }else {
                                    window.location = '/';
                                }

                                //
                            })
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
                        $('.middle_inner').css('margin-top', 0);
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

    $('#feedback-link').live('mouseover', function() {
        $(this).css('left', '0');
    })

    $('#feedback-link').live('mouseout', function() {
        $(this).css('left', '-5px');
    })

    $('.mypitch_edit_link', '#header-table').on('mouseover', function() {
        $('img', $(this)).attr('src', '/img/pencil_red.png');
    });

    $('.mypitch_edit_link', '#header-table').on('mouseout', function() {
        $('img', $(this)).attr('src', '/img/edit_icon_white.png');
    });

    $('.mypitch_delete_link', '#header-table').on('mouseover', function() {
        $('img', $(this)).attr('src', '/img/kreuz_red.png');
    });

    $('.mypitch_delete_link', '#header-table').on('mouseout', function() {
        $('img', $(this)).attr('src', '/img/delete_icon_white.png');
    });

    $('.mypitch_pay_link', '#header-table').on('mouseover', function() {
        $('img', $(this)).attr('src', '/img/buy_red.png');
    });

    $('.mypitch_pay_link', '#header-table').on('mouseout', function() {
        $('img', $(this)).attr('src', '/img/buy_icon_white.png');
    })


    //Цвет фона для текущих питчей
    //$('#current_pitch ul li:odd').css({backgroundColor: '#2f313a'});

})


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
    js.src = "//connect.facebook.net/ru_RU/all.js";
    d.getElementsByTagName('head')[0].appendChild(js);
}(document));
//}