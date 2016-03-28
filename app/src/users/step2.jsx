;(function($){
    $(document).ready(function() {

        TextareaDispatcher.register(function(eventPayload) {
            if (eventPayload.actionType === 'person-for-comment-selected') {
                startedWatchingTextarea = false;
                const textarea = eventPayload.person.selector;
                let text = textarea.val();
                let enteredQueryLength = Math.abs(enteredNameQuery.length) * -1;
                let textForRegExp =  text.slice(0, enteredQueryLength);
                const replacement = `$1@${eventPayload.person.name}, `;
                const regExp = new RegExp(`^(${textForRegExp})(${enteredNameQuery})`, 'ig');
                const updatedText = text.replace(regExp, replacement);
                textarea.val(updatedText);
                textarea.focus();
                enteredNameQuery = '';
                CommentsActions.userStoppedAutosuggest(eventPayload.person.selector);
            }
        });

        /**
         * Диспетчер событий
         */
        CommentsDispatcher.register(function(eventPayload) {
            if (eventPayload.actionType === 'start-autosuggest') {
                let changeSelected = null;
                let selected = null;
                if(typeof(eventPayload.props.changeSelected) != 'undefined') {
                    changeSelected = eventPayload.props.changeSelected;
                }
                if(typeof(eventPayload.props.selected) != 'undefined') {
                    selected = eventPayload.props.selected;
                }
                const props = {
                    "active": true,
                    "selector": eventPayload.props.selector,
                    "query": eventPayload.props.query,
                    "changeSelected": changeSelected,
                    "users": autosuggestUsers,
                    "selected": selected
                };
                ReactDOM.render(
                    <UserAutosuggest data={props}/>,
                    eventPayload.props.selector.next()[0]
                );
            }
            if (eventPayload.actionType === 'stop-autosuggest') {
                let props = {
                    "active": false,
                    "selector": eventPayload.props
                };
                ReactDOM.render(
                    <UserAutosuggest data={props}/>,
                    eventPayload.props.next()[0]
                );
            }
        });

        let startedWatchingTextarea = false;
        let enteredNameQuery = '';
        $(document).on('keydown', 'textarea[data-user-autosuggest=true]', function(e) {
            const charCode = (typeof e.which == "number") ? e.which : e.keyCode;
            const selector = $(this);
            if((charCode == 8) && (startedWatchingTextarea) && (enteredNameQuery.length == 0)) {
                startedWatchingTextarea = false;
                enteredNameQuery = '';
                CommentsActions.userStoppedAutosuggest(selector);
            }
            if((charCode == 38) && (startedWatchingTextarea)) {
                CommentsActions.userNeedUserAutosuggest({
                    'selector': selector,
                    'query': enteredNameQuery,
                    'changeSelected': -1
                });
            }
            if((charCode == 40) && (startedWatchingTextarea)) {
                CommentsActions.userNeedUserAutosuggest({
                    'selector': selector,
                    'query': enteredNameQuery,
                    'changeSelected': 1
                });
            }
            if((charCode == 8) && (startedWatchingTextarea)) {
                enteredNameQuery = enteredNameQuery.slice(0, -1);
                CommentsActions.userNeedUserAutosuggest({'selector': selector, 'query': enteredNameQuery});
            }
            if((charCode == 13) && (startedWatchingTextarea)) {
                $('li[data-selected="true"]', '.userAutosuggest').click();
                return false;
            }
        });
        $(document).on('keypress', 'textarea[data-user-autosuggest=true]', function(e) {
            const charCode = (typeof e.which == "number") ? e.which : e.keyCode;
            const selector = $(this);
            if(charCode == 64) {
                CommentsActions.userNeedUserAutosuggest({'selector': selector, 'query': ''});
                startedWatchingTextarea = true;
                enteredNameQuery = '@';
            }else if(startedWatchingTextarea) {
                if (e.which !== 0 && !e.ctrlKey && !e.metaKey && !e.altKey) {
                    enteredNameQuery += String.fromCharCode(charCode);
                    CommentsActions.userNeedUserAutosuggest({'selector': selector, 'query': enteredNameQuery});
                }
            }
        });

        $('#nofile').click(function() {
            $('#nofiles-warning').modal({
                containerId: 'generic-popup',
                opacity: 80,
                closeClass: 'popup-close'
            });
            return false;
        });

        $('#confirm').click(function(){
            $('#important-confirm').modal({
                containerId: 'generic-popup',
                opacity: 80,
                closeClass: 'popup-close'
            });
            return false;
        });

        $('#confirmWinner').click(function() {
            window.location = ($('#confirm').attr('href'));
        });

        $(document).on('click', '.edit-link-in-comment', function(e) {
            e.preventDefault();
            var section = $(this).parent().parent().parent();
            section.children().not('.separator').hide();
            var hiddenform = $('.hiddenform', section);
            hiddenform.show();
            var text = $(this).data('text');
            $('textarea', hiddenform).html(text).text();
            editcommentflag = true;
            return false;
        });

        $(document).on('click', '.editcomment', function() {
            var textarea = $(this).prev();
            var newcomment = textarea.val();
            var id = textarea.data('id');
            $.post('/wincomments/edit/' + id + '.json', {"text": newcomment}, function(response) {
                var newText = response;
                var section = textarea.parent().parent().parent().parent();
                $('.edit-link-in-comment', section).data('text', newcomment);
                $('.comment-container', section).html(newText);
                section.children().show();
                $('.hiddenform', section).hide();
                editcommentflag = false;
            });
            return false;
        });

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
                }
            }
        });

        $('.replyto, .mention-link').click(function() {
            replyTo($(this));
            return false;
        });

        $(document).on('mouseenter', '.comments-container section', function() {
            $('.toolbar', $(this)).fadeIn(200);
        });
        $(document).on('mouseleave', '.comments-container section', function() {
            $('.toolbar', $(this)).fadeOut(200);
        });

        function replyTo(target) {
            var el = $('#newComment');
            if (el.val().match(/@\W*\s\W\.,/) == null) {
                var prepend = '@' + target.data('commentTo') + ', ';
                var newText = prepend + el.val();
                el.focus().val(newText);
            }
        }

        $('#wincomment').fileupload({
            dataType: 'html',
            autoUpload: false,
            singleFileUploads: false,
            dropZone: null,
            add: function(e, data) {
                if (data.files.length > 0) {
                    e.data.fileupload.myData = data;
                    var html = '';
                    $.each(data.files, function(index, object) {
                        html += '<li class="fakelist">' + object.name + '</li>';
                    });
                    $('#filelist').html(html);
                }else {
                    return false;
                }
            },
            done: function(e, data) {
                var completed = 100;
                fillProgress(completed);
                location.reload(true);
            },
            progressall: function(e, data) {
                if (data.total > 0) {
                    var completed = Math.round(data.loaded / data.total * 100);
                    fillProgress(completed);
                }
            },
            send: function(e, data) {
                $('#loading-overlay').modal({
                    containerId: 'spinner',
                    opacity: 80,
                    close: false
                });
            }
        });

        $('#wincomment').submit(function(e) {
            e.preventDefault();
            $('#wincomment').fileupload('uploadByClickNoCheckInplace', $(this), placeWincomment);
        });

        // Delete Comment
        $(document).on('click', '.delete-link-in-comment', function() {
            $(this).closest('section').fadeOut(400, function() { $(this).remove(); });
            $.get($(this).attr('href'));
            return false;
        });
    });
})($);

/*
 * Filling progressbar with completed value
 */
function fillProgress(completed) {
    completed = (completed > 95) ? 100 : completed;
    $('#progressbar').text(completed + '%');
    var progresspx = Math.round(3.4 * completed);
    if(progresspx > 330) {
        progresspx == 330;
    }
    $('#filler').css('width', progresspx);
    if(completed > 95) {
        setTimeout(function() {
            $('#progressbarimage').css('background', 'url(/img/indicator_full.png)');
        }, 500);
    }
}

function placeWincomment(result) {
    var commentData = prepareWinCommentData(result);
    var newComment = populateWincomment(commentData);
    $('.comments-container').prepend($(newComment));
}

function prepareWinCommentData(result) {
    var commentData = {};
    var expertsObj = result.experts || {};
    commentData.commentId = result.comment.id;
    commentData.commentUserId = result.comment.user_id;
    var actualText = result.comment.text
    if(result.comment.text.match(/[-a-zA-Z0-9@:%_\+.~#?&//=]{2,256}\.[a-z]{2,4}\b(\/*[-a-zA-Z0-9\(\)@:;|%_\+.~#?&//=]*)?/)) {
        actualText = result.comment.text.replace(/(^|\s|\()([-a-zA-Z0-9:%_\+.~#?&//=]{2,256}\.[a-z]{2,4}\b(\/*[-a-zA-Z0-9\(\)@:;|%_\+.~#?&//=]*)?)/g, '$1<a href="$2" target="_blank">$2</a>');
    }

    commentData.commentText = actualText;
    commentData.commentOriginalText = result.comment.originalText;
    //commentData.commentPlainText = result.comment.originalText.replace(/"/g, "\'");
    commentData.commentType = (result.comment.user_id == result.comment.solution.user_id) ? 'designer' : 'client';
    commentData.isExpert = isExpert(result.comment.user_id, expertsObj);
    //commentData.isClosedPitch = (result.comment.pitch.status != 0) ? 1 : 0;

    if (commentData.commentType == 'designer') {
        commentData.messageInfo = 'message_info1';
    } else if (result.comment.user.isAdmin == "1") {
        commentData.messageInfo = 'message_info4';
        commentData.isAdmin = result.comment.user.isAdmin;
    } else if (commentData.isExpert) {
        commentData.messageInfo = 'message_info5';
    }else {
        commentData.messageInfo = 'message_info2';
    }

    if (result.userAvatar) {
        commentData.userAvatar = result.userAvatar;
    } else {
        commentData.userAvatar = '/img/default_small_avatar.png';
    }

    if(result.comment.user.first_name.trim().match(/\s/)) {
        var splitted = result.comment.user.first_name.trim().split(' ');
        result.comment.user.first_name = splitted[0];
        if(result.comment.user.last_name == '') {
            result.comment.user.last_name = splitted[1]
        }
    }
    if(result.comment.user.last_name.trim().match(/\s/) && result.comment.user.first_name == '') {
        var splitted = result.comment.user.last_name.trim().split(' ');
        result.comment.user.first_name = splitted[0];
        result.comment.user.last_name = splitted[1];
    }
    commentData.commentAuthor = result.comment.user.first_name + (((result.comment.user.last_name == null) || (result.comment.user.last_name.length == 0)) ? '' : (' ' + result.comment.user.last_name.substring(0, 1) + '.'));
    if((result.comment.user.is_company == 1) && (result.comment.user.short_company_name != '') && (result.comment.user.isAdmin == 0)) {
        commentData.commentAuthor = result.comment.user.short_company_name;
    }
    commentData.isCommentAuthor = (currentUserId == result.comment.user_id) ? true : false;

    // Date Time
    var postDateObj = getProperDate(result.comment.created);
    commentData.postDate = ('0' + postDateObj.getDate()).slice(-2) + '.' + ('0' + (postDateObj.getMonth() + 1)).slice(-2) + '.' + ('' + postDateObj.getFullYear()).slice(-2);
    commentData.postTime = ('0' + postDateObj.getHours()).slice(-2) + ':' + ('0' + (postDateObj.getMinutes())).slice(-2);
    return commentData;
}

/*
 * Populate each comment layout
 */
function populateWincomment(data) {
    var toolbar = '';
    var manageToolbar = '<a href="/wincomments/delete/' + data.commentId + '" style="float:right;" class="delete-link-in-comment ">Удалить</a>';
    var answerTool = ' display: none;';
    if (data.needAnswer == 1) {
        answerTool = '';
    }
    /*if (isCurrentAdmin != 1 && isClient != 1 && data.isClosedPitch) {
     answerTool = ' display: none;';
     }*/
    var userToolbar = '<a href="#" data-comment-id="' + data.commentId + '" data-comment-to="' + data.commentAuthor + '" class="replyto reply-link-in-comment" style="float:right;' + answerTool + '">Ответить</a>';
    var editToolbar = '<a href="#" style="float:right;" class="edit-link-in-comment" data-id="' + data.commentId  + '" data-text="' + data.commentOriginalText + '">Редактировать</a>';
    if (data.isCommentAuthor) {
        toolbar = manageToolbar + editToolbar;
    } else if (currentUserId) {
        toolbar = userToolbar;
    }
    if (isCurrentAdmin == 1) {
        toolbar = manageToolbar + userToolbar + editToolbar;
    }
    var avatarElement = '';
    if (!data.isAdmin) {
        avatarElement = '<a href="/users/view/' + data.commentUserId + '"> \
                            <img src="' + data.userAvatar + '" alt="Портрет пользователя" width="41" height="41"> \
                            </a>';
    }

    return '<section data-id="' + data.commentId + '" data-type="' + data.commentType + '"> \
                    <div class="message_inf"> \
                    <div class="' + data.messageInfo + '" style="margin-top:20px;margin-left:0;">'
        + avatarElement +
        '<a href="/users/view/' + data.commentUserId + '" data-comment-id="' + data.commentId + '" data-comment-to="' + data.commentAuthor + '"> \
                        <span>' + data.commentAuthor + '</span><br /> \
                        <span style="font-weight: normal;">' + data.postDate + ' ' + data.postTime + '</span> \
                    </a> \
                    <div class="clr"></div> \
                    </div> \
                    </div> \
                    <div class="message_inf2" style="margin-bottom: 10px;"> \
                    <div data-id="' + data.commentId + '" class="message_text2"> \
                        <span class="regular comment-container">'
        + data.commentText +
        '</span> \
    </div> \
    </div> \
    <div style="width: 810px; float: right; margin-top: 6px; margin-right: 5px; padding-bottom: 2px; height: 18px;"> \
    <div class="toolbar" style="display: none;">'
        + toolbar +
        '</div></div> \
        <div class="clr"></div> \
        <div class="hiddenform" style="display:none"> \
            <section> \
                <form style="margin-bottom: 25px;" action="/wincomments/edit/' + data.commentId + '" method="post"> \
                                <textarea name="text" data-id="' + data.commentId + '"></textarea> \
                                <input type="button" src="/img/message_button.png" value="Отправить" class="button editcomment" style="margin: 15px 15px 5px 16px; width: 200px;"><br> \
                                <span style="margin-left:25px;" class="supplement3">Нажмите Esс, чтобы отменить</span> \
                                <div class="clr"></div> \
                            </form> \
                        </section> \
                    </div> \
                    <div class="separator" style="margin-top: 0px;"></div> \
                </section>';
}