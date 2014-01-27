$(document).ready(function() {
    $('input[name=licensed_work]').change(function() {
        if($(this).val() == "0") {
            $('#fine').show();
            $('#works').hide();
        }else if($(this).val() == "1") {
            $('#works').show();
            $('#fine').hide();
        }
    });

    $(document).on('click', '#plusbutton', function() {
        var parent = $(this).parent();
        if (($(this).prev().prev('input').val() == '') || ($(this).prev().prev('input').val() == 'http://')) {
            alert('Укажите адрес используемых допольнительных материалов!');
            return false;
        }
        $(this).remove();
        var counter = $('#works').children().length + 1;
        parent.after('<div style="height:1px;clear:both"></div><div style="margin-top:10px;">'+
            '<input placeholder="Название изображения" value="" type="text" style="width:138px;margin-left:0px;margin-right:14px;float:left;text-transform:none;" name="filename[' + counter + ']">'+
            '<input placeholder="http://" value="" type="text" style="width:200px;margin-left:0px;margin-right:22px;float:left;text-transform:none;" name="source[' + counter + ']" >'+
            '<label style="color:#666666;width:65px;display:block;float:left;margin-right:40px;">'+
            '<input type="checkbox" name="needtobuy[' + counter + ']" style="width:14px;display:block;float:left;margin-top:10px;"><span style="width:46px;display:block;float:left;margin-top:5px;">Нужно<br>покупать</span></label>'+
            '<input type="button" class="button" value="+" id="plusbutton" style="width:40px;float:left;display:block;padding-left:25px;padding-right:25px;font-size:20px;">'+
            '</div>');
    });

    $('.order1').on('mouseover', function() {
        $('img', this).attr('src', '/img/order1_hover.png');
    })
    $('.order1').on('mouseout', function() {
        $('img', this).attr('src', '/img/order1.png');
    })

    $('.order2').on('mouseover', function() {
        $('img', this).attr('src', '/img/order2_hover.png');
    })
    $('.order2').on('mouseout', function() {
        $('img', this).attr('src', '/img/order2.png');
    })
    
    // Uploader's Drag'n'drop
    $(document).on('dragover', function(e) {
        e.preventDefault();
        $('.upload-dropzone-wrapper').removeClass('upload-empty');
        $('.upload-dropzone-wrapper, .upload-dropzone').addClass('active');
        $('#fakebutton, #truebutton').hide();
    });
    $(document).on('dragleave', function(e) {
        e.preventDefault();
        addCallback();
    });
    $(document).on('drop', function(e) {
        e.preventDefault();
        addCallback();
    });
    $(document).on('click', '.uploadable-wrapper', function() {
        $(this).fadeOut(200, function() { 
            $(this).remove();
            if ($('.uploadable-wrapper', '.upload-dropzone').length == 0) {
                $('.upload-dropzone-wrapper').removeClass('upload-empty');
                $('#fakebutton, #truebutton').show();
            }
        });
    });

    function addCallback() {
        $('.upload-dropzone-wrapper, .upload-dropzone').removeClass('active');
        if ($('.uploadable-wrapper', '.upload-dropzone').length == 0) {
            $('#fakebutton, #truebutton').show();
        } else {
            $('.upload-dropzone-wrapper').addClass('upload-empty');
            $('#fakebutton, #truebutton').hide();
            $('.upload-progressbar').css('width', '100%');
        }
    }

    var loadPercentage = 30; // Progressbar percentage for loading files.
    $('#solutionfiles').fileupload({
        dataType: 'html',
        dropZone: $('.upload-dropzone'),
        add: function(e, data) {
            if((data.files.length > 0) && (data.files[0].name.match(/(\.|\/)(gif|jpe?g|png)$/i))) {
                e.data.fileupload.myData = data;
                // Check URL.createObjectURL() support
                var URL = window.URL && window.URL.createObjectURL ? window.URL :
                    window.webkitURL && window.webkitURL.createObjectURL ? window.webkitURL :
                    null;
                if (URL) {
                    $.each(data.files, function (index, file) {
                        $('.upload-dropzone').append('<div class="uploadable-wrapper"><div class="thumbnail-container"><img src="' + URL.createObjectURL(file) + '" height="135" class="thumbnail" /></div><div class="upload-progressbar-wrapper"><div class="upload-progressbar" data-filename="' + file.name + '"></div></div></div>');
                    });
                } else {
                    $.each(data.files, function (index, file) {
                        $('<p/>').text(file.name).appendTo('#file-list');
                    });
                }
                addCallback();
                $(this).fileupload('uploadByAuto');
            }else {
                return false;
            }
        },
        progress: function(e, data) {
            if (data.total > 0) {
                var percent = data.total / 100;
                var completed = Math.round(data.loaded / percent);
                var $el = $('.upload-progressbar[data-filename="' + data.files[0].name + '"]');
                $el.css('width', completed + '%');
            }
        },
        done: function(e, data) {
            // Done each
        },
        progressall: function(e, data) {
            if(data.total > 0) {
                var percent = data.total / 100;
                var completed = Math.round(data.loaded / percent);
                if (completed > 95) {
                    $('#uploadSolution').css('opacity', 1);
                }
            }
        },
        send: function (e, data) {
            $('#uploadSolution').css('opacity', 0);
        }
    });

    /*
     $('a[href="#uploading"]').fancybox({
     'autoScale': true,
     'speedIn': 0,
     'speedOut': 0,
     'autoDimensions': true,
     'centerOnScroll': true  // as MattBall already said, remove the comma
     });
     */

    $('a[href="#invalid"]').fancybox({
        'autoScale': true,
        'speedIn': 0,
        'speedOut': 0,
        'autoDimensions': true,
        'centerOnScroll': true  // as MattBall already said, remove the comma
    });

    $('#uploadSolution').click(function() {
        // Check if copyrighted material not empty
        if (($('input[name=licensed_work][value=1]').prop('checked')) && isAddressEmpty()) {
            alert('Укажите адрес используемых допольнительных материалов!');
            return false;
        }

        if(($('input[name=tos]').attr('checked') != 'checked') || ($('input[type=radio]:checked').length
            == 0) && ($('#filename').html() != 'Файл не выбран')) {
            $('a[href="#invalid"]').click();
        }else {
            return true;
        }
        return false;
    });

    if(($('#panel').length > 0) && ($('#uploadtype').length == 0)) {
        $('.fileinput-button').css('top', '525px');
    }

    $('input[type=file]').on('mouseenter', function() {
        $('#fakebutton').addClass('buttonhover');
    });
});

function isAddressEmpty() {
    var res = false;
    $('input[name^=source]').each(function() {
        if ($(this).val() == '' || $(this).val() == 'http://') {
            res = true;
            return false;
        }
    });
    return res;
}

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

/*
 * Asynchronous callback for image resize
 * Synchronous is not working in Chrome (see http://bugs.jquery.com/ticket/7464)
 */
function uploadCallback(result, i, completed, progressPerEach) {
    if (i < result.solution.length) {
        var query = {
            id: result.id,
            name: result.solution[i].name
        };
        $.ajax({
            url: '/solutionfiles/resize.json/',
            type: 'POST',
            data: query,
            global: false,
            dataType: 'json'
        }).done(function() {
            completed += progressPerEach;
            fillProgress(completed);
            i++;
            uploadCallback(result, i, completed, progressPerEach);
        });
    } else {
        setTimeout(function() {
            $('#filename').html('Файл не выбран');
            window.location = $('#redirect-value').val();
        }, 800);
    }
}
