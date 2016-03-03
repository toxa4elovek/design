$(document).ready(function () {
    $('input[name=licensed_work]').change(function () {
        if ($(this).val() == "0") {
            $('#fine').show();
            $('#works').hide();
        } else if ($(this).val() == "1") {
            $('#works').show();
            $('#fine').hide();
        }
    });

    var tags = new Bloodhound({
        datumTokenizer: Bloodhound.tokenizers.obj.whitespace('name'),
        queryTokenizer: Bloodhound.tokenizers.whitespace,
        remote: '/pitches/getags/?name=%QUERY'
    });

    tags.initialize();

    $('#searchTerm').typeahead(null, {
        name: 'tags',
        displayKey: 'name',
        source: tags.ttAdapter()
    }).on('typeahead:selected', function (obj, val) {
        var box = '<li style="margin-left:6px;">' + val.name + '<a class="removeTag" href="#"><img src="/img/delete-tag.png" alt="" style="padding-top: 4px;"></a></li>';
        $(box).appendTo('#filterbox');
        $('#searchTerm').val('');
        if ($('#filterbox').children().length == 5) {
            $('#filterContainer').removeClass('error-searhTerm');
        }
        recalculateBox();
    })

    $('#solution').bind("keypress", function (e) {
        if (e.keyCode == 13) {
            e.preventDefault();
            return false;
        }
    });

    var recalculateBox = function () {

        var baseWidth = 524,
                filterbox = $('#filterbox');
        if(filterbox.children().length == 5) {
            $('#searchTerm').val('');
            $('#searchTerm').attr("disabled", "disabled");
            $('#searchTerm').css("opacity", "0");
            return false
        }
        $.each(filterbox.children(), function (index, object) {
            baseWidth -= $(object).width() + 50;
        });
        if(baseWidth < 144) {
            baseWidth = 144;
        }
        $('#searchTerm').width(baseWidth);
    };

    $('#searchTerm').keyboard('space', function () {
        $('#searchTerm').typeahead('close');
        if ($(this).val() != '') {
            var box = '<li style="margin-left:6px;">' + $(this).val().replace(/[^A-Za-zА-Яа-яЁё0-9-]/g, "").trim() + '<a class="removeTag" href="#"><img src="/img/delete-tag.png" alt="" style="padding-top: 4px;"></a></li>';
            $(this).val('');
            var filter = $('#filterbox');
            $(box).appendTo(filter);
            if ($(filter).children().length == 5) {
                $('#filterContainer').removeClass('error-searhTerm');
            }
            $('.tt-dropdown-menu').hide();
            recalculateBox();
        }
    }).keyboard('enter', function () {
        $('#searchTerm').typeahead('close');
        if ($(this).val() != '') {
            var box = '<li style="margin-left:6px;">' + $(this).val().replace(/[^A-Za-zА-Яа-яЁё0-9-]/g, "").trim() + '<a class="removeTag" href="#"><img src="/img/delete-tag.png" alt="" style="padding-top: 4px;"></a></li>';
            $(this).val('');
            var filter = $('#filterbox');
            $(box).appendTo(filter);
            if ($(filter).children().length == 5) {
                $('#filterContainer').removeClass('error-searhTerm');
            }
            $('.tt-dropdown-menu').hide();
            recalculateBox();
        }
        return false;
    });

    $(document).on('click', '.removeTag', function (e) {
        e.preventDefault();
        $('#searchTerm').removeAttr("disabled");
        $('#searchTerm').css("opacity", "1");
        $('#searchTerm').val('');
        $('#searchTerm').focus();
        $(this).parent().remove();
    });

    $('#show-types').on('click', function () {
        var job_type = $('#job-type');
        $('#list-job-type').toggle('fast', function () {
            if (job_type.html() == '+') {
                job_type.html('&minus;');
            } else {
                job_type.html('+');
            }
        });
    });

    $('#searchTerm').keyboard('backspace', function () {
        if (($('#filterbox li').length > 0) && ($('#searchTerm').val() == '')) {
            $('.removeTag', '#filterbox li:last').click()
            $('#searchTerm').removeAttr("disabled");
            $('#searchTerm').css("opacity", "1");
            $('#searchTerm').val('');
            $('#searchTerm').focus();
        }
    })

    $(document).on('click', '#plusbutton', function () {
        var parent = $(this).parent();
        if (($(this).prev().prev('input').val() == '') || ($(this).prev().prev('input').val() == 'http://')) {
            alert('Укажите адрес используемых допольнительных материалов!');
            return false;
        }
        $(this).remove();
        var counter = $('#works').children().length + 1;
        parent.after('<div style="height:1px;clear:both"></div><div style="margin-top:10px;">' +
                '<input placeholder="Название изображения" value="" type="text" style="width:138px;margin-left:0px;margin-right:14px;float:left;text-transform:none;" name="filename[' + counter + ']">' +
                '<input placeholder="http://" value="" type="text" style="width:200px;margin-left:0px;margin-right:22px;float:left;text-transform:none;" name="source[' + counter + ']" >' +
                '<label style="color:#666666;width:65px;display:block;float:left;margin-right:40px;">' +
                '<input type="checkbox" name="needtobuy[' + counter + ']" style="width:14px;display:block;float:left;margin-top:10px;"><span style="width:46px;display:block;float:left;margin-top:5px;">Нужно<br>покупать</span></label>' +
                '<input type="button" class="button" value="+" id="plusbutton" style="width:40px;float:left;display:block;padding-left:25px;padding-right:25px;font-size:20px;">' +
                '</div>');
    });

    // Dropzone Scroll
    $("#scroller").draggable({
        drag: function () {
            var y = $('#scroller').css('top');
            y = parseInt(y.substring(0, y.length - 2));
            var mod = ($('.upload-dropzone', '.upload-dropzone-wrapper')[0].scrollHeight - 290) / 180;
            $('.uploadable-wrapper', '.upload-dropzone').css('top', -Math.round(y * mod) + 'px');
        },
        axis: "y",
        containment: "parent"
    });

    // Uploader's Drag'n'drop
    $(document).on('dragover', function (e) {
        e.preventDefault();
        $('.upload-dropzone-wrapper').removeClass('upload-empty');
        $('.upload-dropzone-wrapper, .upload-dropzone').addClass('active');
        $('#fakebutton, #truebutton').hide();
    });
    $(document).on('dragleave', function (e) {
        e.preventDefault();
        addCallback();
    });
    $(document).on('drop', function (e) {
        e.preventDefault();
        addCallback();
    });
    $(document).on('click', '.thumbnail-close', function () {
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
        }).done(function () {
            boxFileNames.splice($.inArray($el.data('filename'), boxFileNames), 1);
            $wrapper.fadeOut(200, function () {
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
        update: function (e, ui) {
            var initPosition = ui.item.find('.upload-progressbar').data('position');
            var newPosition = ui.item.index() + 1;
            reSortable[initPosition] = newPosition;
            // Move Up
            if (newPosition > startPosition) {
                $.each(reSortable, function (idx, val) {
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
                $.each(reSortable, function (idx, val) {
                    if (idx == initPosition || val == 0) {
                        return true;
                    }
                    if (val < startPosition && val >= newPosition) {
                        reSortable[idx]++;
                    }
                });
            }
        },
        start: function (e, ui) {
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
        add: function (e, data) {
            $('.upload-dropzone-wrapper').css('border', '');
            setTimeout(function () {
                addCallback();
            }, 200);
            if ((data.files.length > 0) && (data.files[0].name.match(/(\.|\/)(gif|jpe?g|png)$/i))) {

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
                    setTimeout(function () {
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
            } else {
                return false;
            }
        },
        progress: function (e, data) {
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
        done: function (e, data) {
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
        progressall: function (e, data) {
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

    $(document).on('focus', '.wrong-input', function () {
        $(this).removeClass('wrong-input');
    });

    $(document).on('focus', '.error-searhTerm', function () {
        $(this).removeClass('error-searhTerm');
    });

    $('#uploadSolution').click(function () {
        //return false;

        if ($('.uploadable-wrapper', '.upload-dropzone').length == 0) {
            var offset = $('.upload-dropzone').offset();
            $('.upload-dropzone-wrapper').css('border', '1px solid red');
            $.scrollTo(offset.top - 30, {duration: 600});
            return false;
        }

        if ($('#filterbox').children().length < 5) {
            $('#filterContainer').addClass('error-searhTerm');
            var offset = $('#searchTerm').offset()
            $.scrollTo(offset.top - 30, {duration: 600});
            return false;
        }

        $('#reSortable').val(reSortable);
        // Check if copyrighted material not empty
        if (($('input[name=licensed_work][value=1]').prop('checked')) && isAddressEmpty()) {
            alert('Укажите адрес используемых допольнительных материалов!');
            return false;
        }

        if(($('input[type=radio]:checked').length
            == 0)) {
            $('a[href="#invalid"]').click();
        }

        $('#close_tos').on('click', function() {
            $('.mobile-close').click();
            return false;
        })

        $('#agree').on('click', function() {
            $('.mobile-close').click();
            $('input[name=tos]').prop('checked', 'checked');
            $('#uploadSolution').click();
            return false;
        })

        if (($('input[name=tos]').attr('checked') != 'checked') && ($('#filename').html() != 'Файл не выбран')) {
            $('#popup-need-agree-tos').modal({
                containerId: 'spinner',
                opacity: 80,
                closeClass: 'mobile-close',
                onShow: function () {
                    $('#popup-need-agree-tos').fadeTo(600, 1);
                }
            });
        } else {
            var job = [];
            $.each($('#filterbox').children(), function (i, v) {
                var txt = $(v).text().split('/');
                if ($.isArray(txt)) {
                    $.each(txt, function (i, x) {
                        job.push(x);
                    });
                } else {
                    job.push($(v).text());
                }
            });
            if ($('#tags').length > 0) {
                $('#tags').attr('value', job.join(','));
            } else {
                $('<input />').attr('id', 'tags').attr('type', 'hidden').attr('name', 'tags').attr('value', job.join(',')).appendTo('#solution');
            }
            return true;
        }
        return false;
    });

    if (($('#panel').length > 0) && ($('#uploadtype').length == 0)) {
        $('.fileinput-button').css('top', '525px');
    }

    $('input[type=file]').on('mouseenter', function () {
        $('#fakebutton').addClass('buttonhover');
    });

    $('.tooltip').tooltip({
        tooltipID: 'tooltip',
        width: '282px',
        correctPosX: 45,
        positionTop: -180,
        borderSize: '0px',
        tooltipPadding: 0,
        tooltipBGColor: 'transparent'
    })
    
});

function checkScrollbar() {
    var $el = $('.upload-dropzone', '.upload-dropzone-wrapper');
    var scroll = 0;
    setTimeout(function () {
        scroll = ($el[0].scrollHeight - $el.height());
        if (scroll > 0) {
            $('#scrollerarea', '.upload-dropzone-wrapper').fadeIn();
        } else {
            $('#scrollerarea', '.upload-dropzone-wrapper').fadeOut();
            $('.uploadable-wrapper', '.upload-dropzone').css('top', '0');
            $('#scroller').animate({top: 0});
        }
    }, 500);
}

function isAddressEmpty() {
    var res = false;
    $('input[name^=source]').each(function () {
        if ($(this).val() == '' || $(this).val() == 'http://') {
            $(this).addClass('wrong-input');
            res = true;
            return false;
        }
    });
    return res;
}
