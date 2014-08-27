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

    // Dropzone Scroll
    $( "#scroller" ).draggable({
        drag: function() {
            var y = $('#scroller').css('top');
            y = parseInt(y.substring(0, y.length - 2));
            var mod = ($('.upload-dropzone', '.upload-dropzone-wrapper')[0].scrollHeight - 290) / 180;
            $('.uploadable-wrapper', '.upload-dropzone').css('top', -Math.round(y * mod) + 'px');
        },
        axis: "y",
        containment: "parent"
    });
    
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
    $(document).on('click', '.thumbnail-close', function() {
        var $wrapper = $(this).closest('.uploadable-wrapper');
        var $el = $wrapper.find('.upload-progressbar'); 
        $el.css('transition', 'width 3s');
        $el.css('width', '20%');
        var data = {
                nonce: $('#uploadnonce').val(),
                name: $el.data('filename'),
                position: $el.data('position'),
            };
            $.ajax({
                url: '/solutionfiles/delete.json/',
                type: 'POST',
                data: data,
                global: false,
                dataType: 'json'
            }).done(function() {
                boxFileNames.splice($.inArray($el.data('filename'), boxFileNames), 1);
                $wrapper.fadeOut(200, function() {
                    reSortable[$el.data('position')] = 0;
                    $wrapper.remove();
                    if ($('.uploadable-wrapper', '.upload-dropzone').length == 0) {
                        $('.upload-dropzone-wrapper').removeClass('upload-empty');
                        $('#fakebutton, #truebutton').show();
                    }
                    checkScrollbar();
                });
            });
    });

    var reSortable = [];
    var startPosition = 0;
    $('.upload-dropzone').sortable({
        items: '> div.uploadable-wrapper.ready',
        update: function(e, ui) {
            var initPosition = ui.item.find('.upload-progressbar').data('position');
            var newPosition = ui.item.index() + 1;
            reSortable[initPosition] = newPosition;
            // Move Up
            if (newPosition > startPosition) {
                $.each(reSortable, function(idx, val) {
                    if (idx == initPosition || val == 0) {
                        return true;
                    }
                    if (val > startPosition && val <= newPosition) {
                        reSortable[idx]--;
                    }
                });
            }
            // Move Down
            if (newPosition < startPosition) {
                $.each(reSortable, function(idx, val) {
                    if (idx == initPosition || val == 0) {
                        return true;
                    }
                    if (val < startPosition && val >= newPosition) {
                        reSortable[idx]++;
                    }
                });
            }
        },
        start: function(e, ui) {
            startPosition = ui.item.index() + 1;
        },
        placeholder: "sortable-placeholder",
        scroll: false,
        opacity: 0.8,
        tolerance: 'pointer',
        containment: 'parent'
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
    var filePosition = 0;
    var nowLoading = 0;
    var boxFileNames = [];
    $('#solutionfiles').fileupload({
        dataType: 'html',
        dropZone: $('.upload-dropzone'),
        add: function(e, data) {
            setTimeout(function() { addCallback(); }, 200 );
            if((data.files.length > 0) && (data.files[0].name.match(/(\.|\/)(gif|jpe?g|png)$/i))) {

                // Check files already in dropbox
                if ($.inArray(data.files[0].name, boxFileNames) == -1) {
                    boxFileNames.push(data.files[0].name);
                } else {
                    return false;
                }
                filePosition++;
                $('#fileposition').val(filePosition);
                e.data.fileupload.myData = data;
                // Check URL.createObjectURL() support
                var URL = window.URL && window.URL.createObjectURL ? window.URL :
                    window.webkitURL && window.webkitURL.createObjectURL ? window.webkitURL :
                    null;
                if (URL) {
                    var $html = $('<div class="uploadable-wrapper"> \
                                       <div class="thumbnail-container"> \
                                           <img src="' + URL.createObjectURL(data.files[0]) + '" style="display: none;" class="thumbnail" /> \
                                           <div class="thumbnail-close"></div> \
                                       </div> \
                                       <div class="upload-progressbar-wrapper"> \
                                           <div class="upload-progressbar" data-filename="' + data.files[0].name + '" data-position="' + filePosition + '"></div> \
                                       </div> \
                                   </div>');
                    $html.insertBefore('#truebutton');
                    var $image = $html.find('.thumbnail');
                    setTimeout(function() {
                        if ($image.width() >= $image.height()) {
                            $image.width('180');
                            $image.height('auto');
                            $image.css('margin-top', (135 - $image.height()) / 2);
                        } else {
                            $image.width('180');
                            $image.height('auto');
                        }
                        $image.show();
                    }, 300);
                    reSortable[filePosition] = filePosition;
                } else {
                    // Not supported
                }
                checkScrollbar();
                $(this).fileupload('uploadByAuto');
            }else {
                return false;
            }
        },
        progress: function(e, data) {
            if (data.total > 0) {
                var percent = data.total / 100;
                var completed = data.loaded / percent;
                var $el = $('.upload-progressbar[data-filename="' + data.files[0].name + '"]');
                if (completed >= 50) {
                    $el.css('width', '80%');
                } else {
                    $el.css('width', Math.round(completed) / 100 * loadPercentage + '%');
                }
            }
        },
        done: function(e, data) {
            // Done each
            var $el = $('.upload-progressbar[data-filename="' + data.files[0].name + '"]');
            var $wrapper = $el.closest('.uploadable-wrapper');
            $wrapper.addClass('ready');
            $el.css('transition', 'width .3s');
            $el.css('width', '100%');
            $el.parent().fadeOut();
            nowLoading--;
            if (nowLoading == 0) {
                $('#uploadSolution').fadeIn();
            }
        },
        progressall: function(e, data) {
            // Overall progress
        },
        send: function (e, data) {
            nowLoading++;
            $('#uploadSolution').fadeOut();
        }
    });

    $('a[href="#invalid"]').fancybox({
        'autoScale': true,
        'speedIn': 0,
        'speedOut': 0,
        'autoDimensions': true,
        'centerOnScroll': true  // as MattBall already said, remove the comma
    });

    $('#uploadSolution').click(function() {
        if ($('.uploadable-wrapper', '.upload-dropzone').length == 0) {
            alert('Вы не выбрали файл для загрузки!');
            return false;
        }
        $('#reSortable').val(reSortable);
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

function checkScrollbar() {
    var $el = $('.upload-dropzone', '.upload-dropzone-wrapper');
    var scroll = 0;
    setTimeout(function() {
        scroll = ($el[0].scrollHeight - $el.height());
        if ( scroll > 0) {
            $('#scrollerarea', '.upload-dropzone-wrapper').fadeIn(); 
        } else {
            $('#scrollerarea', '.upload-dropzone-wrapper').fadeOut();
            $('.uploadable-wrapper', '.upload-dropzone').css('top', '0');
            $('#scroller').animate({top:0});
        }
    }, 500);
}

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
