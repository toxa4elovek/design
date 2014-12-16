$(document).ready(function () {
    var pitchid = '';

    $('.enable-editor').tinymce({
        // Location of TinyMCE script
        script_url: '/js/tiny_mce/tiny_mce.js',
        // General options
        theme: "advanced",
        plugins: "autolink,lists,style,visualchars,paste",
        // Theme options
        theme_advanced_buttons1: "styleselect,link,unlink,bullist,numlist,charmap",
        theme_advanced_buttons2: "",
        theme_advanced_buttons3: "",
        theme_advanced_toolbar_location: "top",
        theme_advanced_toolbar_align: "left",
        theme_advanced_statusbar_location: "bottom",
        theme_advanced_resizing: true,
        content_css: "/css/brief_wysiwyg.css",
        language: "ru",
        height: "240",
        width: '538',
        relative_urls: false,
        remove_script_host: false,
        paste_auto_cleanup_on_paste: true,
        paste_remove_styles: true,
        paste_remove_styles_if_webkit: true,
        paste_strip_class_attributes: true,
        paste_preprocess: function (pl, o) {
            if ((jQuery(o.content).text() == '') && (o.content != '')) {
                var text = o.content
            } else {
                var text = jQuery(o.content).text()
            }
            o.content = text
        },
        onchange_callback: function (editor) {
            isUndo = true;
        },
        style_formats: [
            {title: 'Основной текст', inline: 'span', classes: "regular"},
            {title: 'Заголовок', inline: 'span', classes: "greyboldheader"},
            {title: 'Дополнение', inline: 'span', classes: "supplement2"},
        ],
        setup: function (ed) {
            // Set placeholder
            var tinymce_placeholder = $('#' + ed.id);
            var attr = tinymce_placeholder.attr('data-placeholder');
            if (typeof attr !== 'undefined' && attr !== false) {
                var is_default = false;

                ed.onInit.add(function (ed) {

                    var doc = ed.getDoc();

                    tinymce.dom.Event.add(doc, 'blur', function (e) {
                        if (ed.getContent().length === 0) {
                            ed.setContent(attr);
                            is_default = true;
                        }
                    });
                    // get the current content
                    var cont = ed.getContent();

                    // If its empty and we have a placeholder set the value
                    if (cont.length == 0) {
                        ed.setContent(tinymce_placeholder.attr("data-placeholder"));

                        // Get updated content
                        cont = tinymce_placeholder.attr("data-placeholder");
                    }

                    // convert to plain text and compare strings
                    is_default = (cont == tinymce_placeholder.attr("data-placeholder"));
                    // nothing to do
                    if (!is_default) {
                        return;
                    }
                });
                ed.onChange.add(function () {
                    is_default = false;
                });
                ed.onMouseDown.add(function (ed, e) {
                    if (is_default) {
                        ed.setContent('');
                    }
                });

            }
        }
    });

    /* Download Form Select */
    if ((window.File != null) && (window.FileList != null)) {
        $('#new-download').show();
    } else {
        $('#old-download').show();
    }

    // steps menu
    $('.steps-link').click(function () {
        var stepNum = $(this).data('step');
        var isBilled = $('#billed').val();
        var existsNotPublshed = (Cart.id != 0) && (isBilled == 0);
        var notExists = (Cart.id == 0);
        if ($('input[name="isGuaranteed"]:checked').length == 0) {
            $.scrollTo($('#award'), {duration: 600, onAfter: function () {
                    alert('Необходимо уточнить, оставляете ли вы питч без гарантий или создаете гарантированный питч.');
                }
            });
            return false;
        }
        if (stepNum == 3) {
            if (false === $('input[name=tos]').prop('checked')) {
                alert('Вы должны принять правила и условия');
                return false;
            }
        }
        if (Cart.validatetype == 1) {
            if ((stepNum == 3) && ((notExists == true) || (existsNotPublshed == true))) {
                if (Cart.prepareData()) {
                    Cart.saveData();
                } else {
                    $.scrollTo($('.wrong-input').parent(), {duration: 600});
                }
                return false;
            }
        } else if (Cart.validatetype == 2) {
            if (($('#phonenumber').val() == '') || ($('#phonenumber').val() == '+7 XXX XXX XX XX') || ($('#phonenumber').val() == '+7 ХХХ ХХХ ХХ ХХ')) {
                alert('Оставьте свой телефон, чтобы мы могли с вами связаться');
                return false;
            } else {
                if ((stepNum == 3) && ((notExists == true) || (existsNotPublshed == true))) {
                    if (Cart.prepareData()) {
                        Cart.saveData();
                    } else {
                        $.scrollTo($('.wrong-input').parent(), {duration: 600});
                    }
                    return false;
                }
            }

        }
        if (stepNum == 3) {
            /*if(Cart.prepareData()) {
             Cart.saveData();
             }  */
        } else {
            $('.middle').not('#step' + stepNum).hide();
            $('#step' + stepNum).show();
            $.scrollTo($('#header-bg'), {duration: 600});
            if (stepNum == 2) {
                //console.log($('#sliderset').length);
                /*sliders*/
                $(".slider").each(function (index, object) {
                    var value = 5;
                    if (typeof (slidersValue) != "undefined") {

                        value = slidersValue[index];
                    }
                    $(object).slider({
                        disabled: false,
                        value: value,
                        min: 1,
                        max: 9,
                        step: 1,
                        slide: function (event, ui) {
                            var rightOpacity = (((ui.value - 1) * 0.08) + 0.36).toFixed(2);
                            var leftOpacity = (1 - ((ui.value - 1) * 0.08)).toFixed(2);
                            $(ui.handle).parent().parent().next().css('opacity', rightOpacity);
                            $(ui.handle).parent().parent().prev().css('opacity', leftOpacity);
                        }
                    })
                });

            }
        }

        return false;
    })


    /*sliders*/
    $(".slider").each(function (index, object) {
        var value = 5;
        if (typeof (slidersValue) != "undefined") {

            value = slidersValue[index];
        }
        $(object).slider({
            disabled: false,
            value: value,
            min: 1,
            max: 9,
            step: 1,
            slide: function (event, ui) {
                var rightOpacity = (((ui.value - 1) * 0.08) + 0.36).toFixed(2);
                var leftOpacity = (1 - ((ui.value - 1) * 0.08)).toFixed(2);
                $(ui.handle).parent().parent().next().css('opacity', rightOpacity);
                $(ui.handle).parent().parent().prev().css('opacity', leftOpacity);
            }
        })
    });

    function checkPromocode() {
        var value = $('#promocode').val();
        if ($('#promocode').prop('disabled') || value.length == 0) {
            return false;
        }
        $.post('/promocodes/check.json', {"code": value}, function (response) {
            if (response == 'false') {
                $('#hint').text('Промокод неверен!');
            } else {
                $('#hint').text('Промокод активирован!');
                if ((response.type == 'pinned') || (response.type == 'misha')) {
                    Cart.addOption("“Прокачать” бриф", 0);
                    $('input[type=checkbox]', '#pinned-block').attr('checked', 'checked');
                    $('input[type=checkbox]', '#pinned-block').data('optionValue', '0');
                    $('.label', '#pinned-block').text('+0.-').addClass('unfold');
                } else if (response.type == 'discount') {
                    Cart.transferFeeDiscount = 700;
                    Cart.updateFees();
                    Cart._renderCheck();
                } else if (response.type == 'in_twain') {
                    Cart.feeRatesReCalc(2);
                    Cart.updateFees();
                    Cart._renderCheck();
                }
                Cart.promocodes.push(value);
            }
        });
    }
    checkPromocode();

    $('#promocode').live('keyup', function () {
        checkPromocode();
    });

    $(document).on('focus', '.wrong-input', function () {
        $(this).removeClass('wrong-input');
    });
    $('input', '.extensions').change(function () {
        $('.extensions').removeClass('wrong-input');
    });

    $('input', '#list-job-type').change(function () {
        $('#list-job-type').removeClass('wrong-input');
    });

    $('#sliderset').show();

    $('#expert-trigger').click(function () {
        if ($('#experts-checkbox').is(':checked') == false) {
            $('.experts').toggle();
        }
        return false;
    });

    $('#award').numeric({"negative": false, "decimal": false}, function () {
        var input = $('#award');
        var minAward = input.data('minimalAward');
        $('#indicator').removeClass('low normal good');
        if (minAward > input.val()) {
            input.val(minAward);
            input.addClass('initial-price');
            $('#indicator').addClass('low');
            Cart.transferFee = feeRates.low;
        } else {
            input.removeClass('initial-price');
            drawIndicator(input, input.val());
        }
    });

    $('#award').keyup(function () {
        var input = $(this);

        input.removeClass('initial-price');
        if ((input.val() == '') && (!input.is(':focus'))) {
            input.val(0);
        }
        if (input.val() == '') {
            var value = 0;
        } else {
            var value = input.val();
        }
        drawIndicator(input, value);
        Cart.updateOption($(this).data('optionTitle'), value);
    })

    $('#award').click(function () {
        $(this).removeClass('initial-price');
        if ($('#sub-site').length == 0) {
            if ($('#award').val() == $(this).data('minimalAward')) {
                $('#award').val('');
            }
        }
    });

    // award size
    $('#award').blur(function () {
        if ($(this).val() == '') {
            $(this).val($(this).data('minimalAward'))
        }
        if ($(this).val() == $(this).data('minimalAward')) {
            //$(this).addClass('initial-price');
        }
        Cart.updateOption($(this).data('optionTitle'), $('#award').val());
    });

    // simple options
    $('.single-check').change(function () {
        $(this).parent().parent().parent().children('.label').toggleClass('unfold');
        if ($(this).is(':checked')) {
            Cart.addOption($(this).data('optionTitle'), $(this).data('optionValue'));

        } else {
            Cart.removeOption($(this).data('optionTitle'));
        }
    });

    // multi options
    $('.multi-check').change(function () {
        $(this).parent().parent().parent().children('.label').toggleClass('unfold');
        $('.experts').show();
        var firstExpert = $('.experts').children().first();
        var firstCheckbox = $('input', firstExpert);
        $(firstCheckbox).attr('checked', 'checked');
        if ($(this).is(':checked')) {
            Cart.addOption('экспертное мнение', $(firstCheckbox).data('optionValue'));
            $('#expert-label').html('+' + $(firstCheckbox).data('optionValue') + '.-');
        } else {
            Cart.removeOption('экспертное мнение');
            $('.expert-check', '.experts').removeAttr('checked');
            $('.experts').hide();
        }
    })

    $('.expert-check', '.experts').change(function () {
        if ($(this).is(':checked')) {
            if (!$('#expert-label').hasClass('unfold')) {
                $('#expert-label').addClass('unfold');
            }
            if ($('.multi-check').is(':checked') == false) {
                $('.multi-check').attr('checked', 'checked');
            }
            if (typeof (Cart.getOption($(this).data('optionTitle'))) == 'undefined') {
                var newValue = $(this).data('optionValue');
            } else {
                var newValue = $(this).data('optionValue') + Cart.getOption($(this).data('optionTitle'));
            }
            $('#expert-label').html('+' + newValue + '.-');
            Cart.addOption($(this).data('optionTitle'), newValue);
        } else {
            console.log($(this).data('optionTitle'))
            console.log($(this).data('optionValue'));
            console.log(Cart.getOption($(this).data('optionTitle')))
            console.log(Cart.content);
            var newValue = Cart.getOption($(this).data('optionTitle')) - $(this).data('optionValue');
            console.log(newValue);
            $('#expert-label').html('+' + newValue + '.-');
            Cart.updateOption($(this).data('optionTitle'), newValue);
            if ($('.expert-check:checked', '.experts').length == 0) {
                Cart.removeOption('экспертное мнение');
                $('#expert-label').removeClass('unfold');
                $('#experts-checkbox').removeAttr('checked');
            }
        }
    });

    /**/

    $('.short-time-limit').change(function () {
        var value = $(this).data('optionValue');
        var key = $(this).data('optionTitle');
        if (value == "0") {
            $('#timelimit-label').removeClass('unfold');
            Cart.removeOption(key);
        } else {
            Cart.addOption(key, value);
            $('#timelimit-label').addClass('unfold').html('+' + Cart.decoratePrice(value));
        }
    });

    /**/

    $('#full-description').keyup(function () {
        var chars = $(this).val().length;
        $('#indicator-desc').removeClass('low normal good');
        if (chars < $(this).data('normal')) {
            $('#indicator-desc').addClass('low');
        } else if (chars < $(this).data('high')) {
            $('#indicator-desc').addClass('normal');
        } else {
            $('#indicator-desc').addClass('good');
        }
    })

    /**/

    $('#hide-check').click(function () {
        $(this).parent().removeClass('expanded');
        return false;
    })

    $('#show-check').click(function () {
        $(this).parent().addClass('expanded');
        return false;
    })


    /*
     $( ".slider" ).slider({
     value: 5,
     min: 1,
     max: 9,
     step: 1,
     slide: function( event, ui ) {
     
     var rightOpacity = (((ui.value-1) * 0.08) + 0.36).toFixed(2);
     var leftOpacity = (1 - ((ui.value-1) * 0.08)).toFixed(2);
     $(ui.handle).parent().parent().next().css('opacity', rightOpacity);
     $(ui.handle).parent().parent().prev().css('opacity', leftOpacity);
     }
     })*/
    var canSave = false;
    function sleep(milliseconds) {
        var start = new Date().getTime();
        for (var i = 0; i < 1e7; i++) {
            if ((new Date().getTime() - start) > milliseconds) {
                break;
            }
        }
    }
    $('#save').click(function () {
        if (false === $('input[name=tos]').prop('checked')) {
            alert('Вы должны принять правила и условия');
        } else {
            if (Cart.prepareData()) {
                if (uploader.damnUploader('itemsCount') > 0) {
                    $('#loading-overlay').modal({
                        containerId: 'spinner',
                        opacity: 80,
                        close: false
                    });
                    uploader.damnUploader('startUpload')
                } else {
                    Cart.saveData();
                }
            } else {
                $.scrollTo($('.wrong-input').parent(), {duration: 600});
            }
        }
        return false;
    })

    var fileIds = [];
    var uploader = $("#fileupload").damnUploader({
        url: '/pitchfiles/add.json',
        onSelect: function (file) {
            onSelectHandler.call(this, file, fileIds, Cart);
        }  // See app.js
    });

    $('input[name="phone-brief"]').change(function () {
        var phone = $(this).val();
        $('input[name="phone-brief"]').val(phone);
    });

    $(document).on('click', '.filezone-delete-link', function () {
        var givenId = +$(this).parent().attr('data-id');
        if (Cart.id) {
            if (givenId) { // File came from database
                $.post('/pitchfiles/delete', {'id': givenId}, function (response) {
                    if (response != 'true') {
                        alert('При удалении файла произошла ошибка');
                    }
                    var position = $.inArray(givenId, Cart.fileIds);
                    Cart.fileIds.splice(position, 1);
                    Cart.saveFileIds();
                });
            } else {
                uploader.damnUploader('cancel', $(this).attr('data-delete-id'));
            }
            $(this).parent().remove();
            return false;
        } else if ($(this).data('imfromiframe') == true) {
            $.post('/pitchfiles/delete', {'id': givenId}, function (response) {
                if (response != 'true') {
                    alert('При удалении файла произошла ошибка');
                }
                var position = $.inArray(givenId, Cart.fileIds);
                Cart.fileIds.splice(position, 1);
            });
            $(this).parent().remove();
            return false;
        } else {
            uploader.damnUploader('cancel', $(this).attr('data-delete-id'));
            $(this).parent().remove();
            return false;
        }
    });

    $('#uploadButton').click(function () {
        //$('#fileuploadform').fileupload('uploadByClick');
        return false;
    });

    /*$('.sub-radio').change(function() {
     var minValue = $(this).data('minValue');
     
     $('#award').data('minimalAward', minValue);
     $('#award').blur();
     })*/

    $('#sub-site').keyup(function () {
        $(this).addClass('freeze');
        $(this).change();
    });

    $('input[name=package-type]').on('change', function () {
        var newMinValue = $(this).data('minValue');
        var award = $('#award');
        award.data('lowDef', newMinValue);
        var defLow = award.data('lowDef');
        var defNormal = award.data('normalDef');
        var defHigh = award.data('highDef');
        var mult = $(this).data('mult');
        if ((typeof (mult) == undefined) || (!mult)) {
            mult = parseInt(award.data('lowDef')) / 2;
        }
        var extraNormal = (defNormal - defLow);
        var extraHigh = (defHigh - defLow);
        var minValue = defLow;
        award.data('minimalAward', minValue);
        award.blur();
    })

    $('#sub-site').numeric({"negative": false, "decimal": false}, function () {

    });

    $('#sub-site').change(function () {
        var value = $(this).val();
        if ($(this).hasClass('freeze')) {
            $(this).removeClass('freeze');
            if (($(this).val() == '') || ($(this).val() == 0)) {
                value = 1;
            }
        } else {
            if (($(this).val() == '') || ($(this).val() == 0)) {
                $(this).val('1');
                value = 1;
            }
        }
        var award = $('#award');
        var defLow = award.data('lowDef');
        var defNormal = award.data('normalDef');
        var defHigh = award.data('highDef');
        var mult = $(this).data('mult');
        if ((typeof (mult) == undefined) || (!mult)) {
            mult = parseInt(award.data('lowDef')) / 2;
        }
        var extraNormal = (defNormal - defLow);
        var extraHigh = (defHigh - defLow);

        var minValue = ((value - 1) * mult) + defLow;
        $('#labelPrice').text(minValue);
        award.data('minimalAward', minValue);
        award.data('low', minValue);
        award.data('normal', minValue + extraNormal);
        award.data('high', minValue + extraHigh);
        //$('#award').data('lowDef', minValue );
        //$('#award').data('highDef', minValue + 18000);
        //$('#award').data('normalDef', minValue + 8000);
        award.blur();
    })

    $('#sub-site').focus(function () {
        $(this).removeClass('initial-price');
        $(this).removeClass('freeze');
    });

    $('input[name=isGuaranteed]').on('change', function () {
        var radioButton = $(this);
        if (radioButton.val() == 1) {
            Cart.addOption(radioButton.data('optionTitle'), radioButton.data('optionValue'));
        } else {
            Cart.removeOption(radioButton.data('optionTitle'));
        }
    })

    var count = $('.sub-check:checked').length;
    if (count == 1) {
        $('.sub-check:checked').attr('disabled', 'disabled');
    }

    $('.sub-check').change(function () {
        var price = $('#copybaseminprice').val();
        var count = $('.sub-check:checked').length;
        if (count < 2) {
            $('.sub-check:checked').attr('disabled', 'disabled');
        } else {
            $('.sub-check:checked').removeAttr('disabled');
        }
        switch (count) {
            case 1:
                var mod = 1;
                break;
            case 2:
                var mod = 1.5;
                break;
            case 3:
                var mod = 1.75;
                break;
        }
        var input = $('#award');
        /*var value = input.val();
         if(input.val() == '') {
         value = input.attr('value');
         }
         var modded = mod * value;*/
        var newMinimum = parseInt($('#copybaseminprice').val() * mod);
        var minValue = $(this).data('minValue');
        input.data('minimalAward', newMinimum);
        input.blur();
    })

    $('#phonebrief').change(function () {
        if ($(this).attr('checked') == 'checked') {
            Cart.validatetype = 2;
        } else {
            Cart.validatetype = 1;
        }
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

    $(document).on('click', 'td.s3_text, td.s3_h', function () {
        $('.rb1', $(this).prevAll(':last')).click();
    });

    $('.rb1').change(function () {
        switch ($(this).data('pay')) {
            case 'payanyway':
                $("#paybutton-payanyway").fadeIn(100);
                $("#paybutton-paymaster").css('background', '#a2b2bb');
                $("#paymaster-images").show();
                $("#paymaster-select").hide();
                $('#s3_kv').hide();
                break;
            case 'paymaster':
                $("#paybutton-paymaster").removeAttr('style');
                $("#paybutton-payanyway").fadeOut(100);
                $("#paymaster-images").hide();
                $("#paymaster-select").show();
                $('#s3_kv').hide();
                break;
            case 'offline':
                $("#paybutton-payanyway").fadeOut(100);
                $("#paybutton-paymaster").css('background', '#a2b2bb');
                $("#paymaster-images").show();
                $("#paymaster-select").hide();
                $('#s3_kv').show();
                break;
        }
    });

    $('.rb-face', '#s3_kv').change(function () {
        if ($(this).data('pay') == 'offline-fiz') {
            $('.pay-fiz').show();
            $('.pay-yur').hide();
        } else {
            $('.pay-fiz').hide();
            $('.pay-yur').show();
        }
    });

    $('#bill-fiz').submit(function (e) {
        e.preventDefault();
        if (checkRequired($(this))) {
            $.scrollTo($('.wrong-input', $(this)).parent(), {duration: 600});
        } else {
            $.post($(this).attr('action') + '.json', {
                'id': $('#fiz-id').val(),
                'name': $('#fiz-name').val(),
                'individual': $('#fiz-individual').val(),
                'inn': 0,
                'kpp': 0,
                'address': 0
            }, function (result) {
                if (result.error == false) {
                    window.location = '/pitches/getpdf/godesigner-pitch-' + $('#fiz-id').val() + '.pdf';
                }
            });
        }
    });

    $('#bill-yur').submit(function (e) {
        e.preventDefault();
        if (checkRequired($(this))) {
            $.scrollTo($('.wrong-input', $(this)).parent(), {duration: 600});
        } else {
            $.post($(this).attr('action') + '.json', {
                'id': $('#yur-id').val(),
                'name': $('#yur-name').val(),
                'individual': $('#yur-individual').val(),
                'inn': $('#yur-inn').val(),
                'kpp': $('#yur-kpp').val(),
                'address': $('#yur-address').val()
            }, function (result) {
                if (result.error == false) {
                    window.location = '/pitches/getpdf/godesigner-pitch-' + $('#yur-id').val() + '.pdf';
                }
            });
        }
    });

    // Unpin check
    $(window).on('scroll', function () {
        var diff = $(window).scrollTop() - $('header').offset().top - 440;
        if (diff > 0) {
            $('.summary-price').offset({top: $('header').offset().top + 668});
        }
    });

    /**/
    Cart = new FeatureCart;
    Cart.init();

    /* Pitch Init from various options */
    if (window.location.pathname.indexOf('brief') != -1) { // Pitch create only
        if ((typeof (fillBrief) != 'undefined') && (fillBrief) && ($('#phonebrief').attr('checked') != 'checked')) {
            $('#phonebrief').click();
        }
        var pitchInitOptions = $.deparam(window.location.search.substr(1));
        if (pitchInitOptions.award) {
            $('#award').focus().val(pitchInitOptions.award).blur();
        }
        if (pitchInitOptions.name) {
            $('input[name=title]').focus().val(pitchInitOptions.name);
        }
        if ((pitchInitOptions.fillBrief == "true") && ($('#phonebrief').attr('checked') != 'checked')) {
            $('#phonebrief').click();
        }
    }

    if ($('#sub-site').val() > 1) {
        var award = $('#award');
        var defLow = award.data('lowDef');
        var defNormal = award.data('normalDef');
        var defHigh = award.data('highDef');
        var mult = $('#sub-site').data('mult');
        if ((typeof (mult) == undefined) || (!mult)) {
            mult = parseInt(award.data('lowDef')) / 2;
        }
        var extraNormal = (defNormal - defLow);
        var extraHigh = (defHigh - defLow);

        var minValue = (($('#sub-site').val() - 1) * mult) + defLow;
        award.data('minimalAward', minValue);
        award.data('low', minValue);
        award.data('normal', minValue + extraNormal);
        award.data('high', minValue + extraHigh);
        award.blur();
    }

    checkReferal();
    drawIndicator($('#award'), $('#award').val());

});

function checkReferal() {
    if (($('#referal').val() > 0) && ($('#referalId').val() != 0)) {
        Cart.referalDiscount = $('#referal').val();
        Cart.referalId = $('#referalId').val();
        Cart.addOption("Скидка", -$('#referal').val());
    }
}

function checkRequired(form) {
    var required = false;
    $.each($('[required]', form), function (index, object) {
        if (($(this).attr('id') == 'yur-kpp') && ($('#yur-inn').val().length == 10)) {
            $(this).val('');
            return true;
        }
        if (($(this).val() == $(this).data('placeholder')) || ($(this).val().length == 0)) {
            $(this).addClass('wrong-input');
            required = true;
            return true; // Continue next element
        }
        if (($(this).data('length')) && ($(this).data('length').length > 0)) {
            var arrayLength = $(this).data('length');
            if (-1 == $.inArray($(this).val().length, arrayLength)) {
                $(this).addClass('wrong-input');
                required = true;
                return true;
            }
        }
        if (($(this).data('content')) && ($(this).data('content').length > 0)) {
            if ($(this).data('content') == 'numeric') {
                // Numbers only
                if (/\D+/.test($(this).val())) {
                    $(this).addClass('wrong-input');
                    required = true;
                    return true;
                }
            }
            if ($(this).data('content') == 'symbolic') {
                // Symbols only
                if (/[^a-zа-я\s]/i.test($(this).val())) {
                    $(this).addClass('wrong-input');
                    required = true;
                    return true;
                }
            }
            if ($(this).data('content') == 'mixed') {
                // Symbols and Numbers
                if (!(/[a-zа-я0-9]/i.test($(this).val()))) {
                    $(this).addClass('wrong-input');
                    required = true;
                    return true;
                }
            }
        }
    });
    return required;
}

function drawIndicator(input, value) {
    $('#indicator').removeClass('low normal good');
    var fullPx = $('#indicator').width();
    var normalPx = $('#indicator').data('normal');
    var highPx = $('#indicator').data('high');
    var line = $('.line', '#indicator');
    if (value < input.data('normal')) {
        var ratio = (input.data('normal') - input.data('low')) / (value - input.data('low'));
        $('#indicator').addClass('low');
        Cart.transferFee = feeRates.low;
        var width = normalPx / ratio;
        if (width < 10) {
            width = 10;
        }
        line.width(width);
    } else if (value < input.data('high')) {
        var ratio = (input.data('high') - input.data('normal')) / (value - input.data('normal'));
        $('#indicator').addClass('normal');
        Cart.transferFee = feeRates.normal;
        line.width(normalPx + ((highPx - normalPx) / ratio));
    } else {
        var ratio = (input.data('high') * 3 - input.data('high')) / (value - input.data('high'));
        $('#indicator').addClass('good');
        Cart.transferFee = feeRates.good;
        var width = highPx + ((fullPx - highPx) / ratio);
        if (width > (fullPx - 10)) {
            width = fullPx - 10;
        }
        line.width(width);
    }
}

//$('input[name=category_id]').val()

/* Class */

function FeatureCart() {
    var self = this;
    this.id = 0;
    this.total = 0;
    this.content = {};
    this.container = $('#check-tag');
    this.priceTag = $('#total-tag');
    this.award = $('#award');
    this.awardKey = 'Награда Дизайнеру';
    this.data = {};
    this.fileIds = [];
    this.specificTemplates = [];
    this.validatetype = 1;
    this.transferFee = feeRates.low;
    this.transferFeeDiscount = 0;
    this.transferFeeKey = 'Сбор GoDesigner';
    this.transferFeeFlag = 0;
    this.referalDiscount = 0;
    this.referalId = 0;
    this.mode = 'add';
    this.promocodes = [];
    this.init = function () {
        if (($('#pitch_id').length > 0) && (typeof ($('#pitch_id').val()) != 'undefined')) {
            self.id = $('#pitch_id').val();
            this.mode = 'edit';
        }
        var initVal = $('#award').val();
        if ($('#award').val() == '') {
            initVal = $('#award').attr('value');
        }
        if (self.id > 0) {
            self.addOption('Награда Дизайнеру', initVal);
        } else {
            //self.updateOption('Заполнение брифа', 0);
        }
        if ($('#discount').length > 0) {
            self.transferFeeDiscount = 700;
        }
        if (self.mode == 'edit') {
            if (window.location.hash != '#step3') {
                $.get('/receipts/view/' + self.id + '.json', function (response) {
                    if ($('#referal').val() > 0) {
                        this.referalDiscount = $('#referal').val();
                        response.Discount = {name: "Скидка", value: -this.referalDiscount};
                    }
                    self.fillCheck(response);
                    self._renderCheck();
                });
            }
        } else {
            self._renderCheck();
        }
        if ($('#phonebrief').attr('checked') == 'checked') {
            self.validatetype = 2;
        }
        $.each($('li', '#filezone'), function (index, object) {
            self.fileIds.push($(object).data('id'));
        })

        if (window.location.hash == '#step3') {
            $.get('/receipts/view/' + self.id + '.json', function (response) {
                if ($('#referal').val() > 0) {
                    this.referalDiscount = $('#referal').val();
                    response.Discount = {name: "Скидка", value: -this.referalDiscount};
                }
                self.fillCheck(response);
                self._renderCheck();
                if (self.prepareData()) {
                    self.saveData();
                }
            });
        }
    };
    this.fillCheck = function (data) {
        self.content = {};
        $.each(data, function (index, object) {
            if (object.name == 'Экспертное мнение') {
                $('#expert-label').html('+' + object.value + '.-');
            }
            var feeOption = object.name.indexOf(self.transferFeeKey);
            if (feeOption != -1) {
                var percent = object.name.substr(self.transferFeeKey.length + 1, object.name.length - self.transferFeeKey.length - 2);
                if (percent.length > 0) {
                    self.transferFee = (percent.replace(',', '.') / 100).toFixed(3);
                } else { // For older pitches
                    self.transferFee = feeRates.good;
                }
                object.name = self.transferFeeKey;
            }
            if ((object.value != 0)) {
                self.updateOption(object.name, parseInt(object.value));
            } else if ((object.name == 'Заполнение брифа')) {
                //self.updateOption(object.name, parseInt(object.value));
            }
        })
    };
    this.addOption = function (key, value) {
        self.content[key] = value;
        self.updateFees();
        self._renderCheck();
    };
    this.updateOption = function (key, value) {
        if (typeof (self.content[key]) != "undefined") {
            self.content[key] = value;
            self.updateFees();
            self._renderCheck();
        } else {
            self.addOption(key, value);
            self.updateFees();
            self._renderCheck();
        }
    };
    this.updateFees = function () {
        var total = self._calculateOptionsWithoutFee();
        var award = self.getOption(self.awardKey);
        var commision = Math.round(award * this.transferFee) - this.transferFeeDiscount;
        if (commision < 0) {
            commision = 0;
        }
        self.content[self.transferFeeKey] = commision;
    }
    this.getOption = function (key) {
        if (typeof (self.content[key]) != "undefined") {
            return parseInt(self.content[key]);
        } else {
            return 0;
        }
    };
    this.getTimelimit = function () {
        return $('.short-time-limit:checked').data('optionPeriod');
    };
    this.removeOption = function (key) {
        delete self.content[key];
        self.updateFees();
        self._renderCheck();
    };
    this.prepareData = function () {
        var features = {
            'award': self.getOption(self.awardKey),
            'private': self.getOption('Закрытый питч'),
            'social': self.getOption('Рекламный Кейс'),
            'experts': self._expertArray(),
            'email': self.getOption('Email рассылка'),
            'pinned': self.getOption('“Прокачать” бриф'),
            'timelimit': self.getOption('Установлен срок'),
            'brief': self.getOption('Заполнение брифа'),
            'guaranteed': self.getOption('Гарантированный питч'),
            'timelimitOption': self.getTimelimit()
        };
        var commonPitchData = {
            'id': self.id,
            'title': $('input[name=title]').val(),
            'category_id': $('input[name=category_id]').val(),
            'industry': $('input[name=industry]').val() || '',
            'jobTypes': self._jobArray(),
            'business-description': $('textarea[name=business-description]').val(),
            'description': $('textarea[name=description]').val(),
            'fileFormats': self._formatArray(),
            'fileFormatDesc': $('textarea[name=format-description]').val(),
            'filesId': self.fileIds,
            'phone-brief': $('input[name=phone-brief]').val(),
            'materials': $('input[name=materials]:checked').val(),
            'materials-limit': $('input[name=materials-limit]').val(),
            'promocode': $.unique(this.promocodes),
            'referalDiscount': this.referalDiscount,
            'referalId': this.referalId
        };
        self.data = {
            "features": features,
            "commonPitchData": commonPitchData,
            "specificPitchData": self.getSpecificData()
        };
        return self.validateData();
    };
    this.validateData = function () {
        var result = true;
        if (self.validatetype == 1) {
            if ((self.data.commonPitchData.title == '') || ($('input[name=title]').data('placeholder') == self.data.commonPitchData.title)) {
                $('input[name=title]').addClass('wrong-input');
                result = false;
            }
            if ((self.data.commonPitchData.description == '') || ($('textarea[name=description]').data('placeholder') == self.data.commonPitchData.description)) {
                $('textarea[name=description]').addClass('wrong-input');
                result = false;
            }
            if (self.data.commonPitchData.fileFormats.length == 0) {
                $('.extensions').addClass('wrong-input');
                result = false;
            }
            if (self.data.commonPitchData.jobTypes.length == 0) {
                $('#list-job-type').addClass('wrong-input');
                result = false;
            }
        }
        return result;
    };
    this.saveData = function () {
        if (self.data.features.award == 0) {
            alert('Укажите награду для дизайнера!');
        } else {
            $.post('/pitches/add.json', self.data, function (response) {
                if (response == 'redirect') {
                    window.location = '/users/registration';
                }
                if (response == 'noaward') {
                    alert('Укажите награду для дизайнера!');
                    return false;
                }
                self.id = response;
                $('#pitch-id').val(self.id);
                $('#fiz-id').val(self.id);
                $('#yur-id').val(self.id);

                pitchid = self.id;

                $.get('/transactions/getsigndata/' + self.id + '.json', function (response) {
                    $('.middle').not('#step3').hide();
                    $('#step3').show();
                    $.scrollTo($('#header-bg'), {duration: 600});
                    if (response.category == 10) {
                        $('.paymaster-section').remove();
                    } else {
                        // Paymaster
                        $('input[name=LMI_PAYMENT_AMOUNT]').val(response.total);
                        if ($('input[name=LMI_PAYMENT_NO]').length > 0) {
                            $('input[name=LMI_PAYMENT_NO]').val(response.id);
                        } else {
                            $('div', '#pmwidgetForm').append('<input type="hidden" name="LMI_PAYMENT_NO" value="' + response.id + '">');
                        }
                        $('.pmamount').html('<strong>Сумма:&nbsp;</strong> ' + response.total + '&nbsp;RUB');
                        $('.pmwidget').addClass('mod');
                        $('h1.pmheader', '.pmwidget').addClass('mod');
                    }
                    // Payanyway
                    $('input[name=MNT_AMOUNT]').val(response.total)
                    $('input[name=MNT_TRANSACTION_ID]').val(response.id)

                    // Bill
                    $('#pdf-link').attr('href', '/pitches/getpdf/godesigner-pitch-' + self.id + '.pdf');
                })
            });
        }
    }

    this.saveFileIds = function () {
        $.post('/pitches/updatefiles.json', {"fileids": self.fileIds, "id": self.id}, function () {

        });
    }

    this.getSpecificData = function () {
        var specificPitchData = {};
        $('input.specific-prop, textarea.specific-prop').each(function (index, object) {
            specificPitchData[$(object).attr('name')] = $(object).val()
        });
        $('input.specific-group:checked').each(function (index, object) {
            specificPitchData[$(object).attr('name')] = $(object).val()
        });
        if ($('.slider').length > 0) {
            specificPitchData[$('.sliderul').data('name')] = self._logoProperites();
        }
        if ($('.look-variants').length) {
            specificPitchData["logoType"] = self._logoTypeArray()
        }
        return specificPitchData;
    }
    this.decoratePrice = function (price) {
        price += '.-';
        return price;
    }
    this.feeRatesReCalc = function (divider) {
        feeRates.low = (Math.floor(feeRatesOrig.low * 1000 / divider) / 1000).toFixed(3);
        feeRates.normal = (Math.floor(feeRatesOrig.normal * 1000 / divider) / 1000).toFixed(3);
        feeRates.good = (Math.floor(feeRatesOrig.good * 1000 / divider) / 1000).toFixed(3);
        var input = $('#award');
        drawIndicator(input, input.val());
    }
    this._logoProperites = function () {
        var array = new Array();
        $.each($('.slider'), function (i, object) {
            array.push($(object).slider('value'));
        })
        return array;
    }
    this._logoTypeArray = function () {
        var array = new Array();
        var checkedExperts = $('input:checked', '.look-variants');
        $.each(checkedExperts, function (index, object) {
            array.push($(object).data('id'));
        })
        return array;
    },
            this._formatArray = function () {
                var array = new Array();
                var checkedExperts = $('input:checked', '.extensions');
                $.each(checkedExperts, function (index, object) {
                    array.push($(object).data('value'));
                });
                return array;
            },
            this._jobArray = function () {
                var array = new Array();
                var checkedJob = $('input:checked', '#list-job-type');
                $.each(checkedJob, function (index, object) {
                    array.push($(object).val());
                });
                return array;
            },
            this._expertArray = function () {
                var array = new Array();
                var checkedExperts = $('input:checked', '.experts');
                $.each(checkedExperts, function (index, object) {
                    array.push($(object).data('id'));
                })
                return array;
            };
    /*this._getTimelimitDays = function() {
     if(self.getOption('Поджимают сроки') > 0) {
     return $('.short-time-limit').data('optionPeriod');
     }else {
     return 0;
     }
     }*/
    this._renderCheck = function () {
        self._renderTotal();
        self._renderOptions();
    };
    this._renderTotal = function () {
        var priceTag = self._calculateTotal() + '.-';
        self.priceTag.html(priceTag);
    };
    this._renderOptions = function () {
        var html = '';
        $.each(self.content, function (key, value) {
            if (key == self.transferFeeKey) {
                html += '<li><span>' + key + ' <div>' + (self.transferFee * 100).toFixed(1) + '%</div></span><small>' + value + '.-</small></li>';
            } else {
                html += '<li><span>' + key + '</span><small>' + value + '.-</small></li>';
            }
        });
        self.container.html(html);
    };
    this._calculateOptions = function () {
        var optionsTotal = 0;
        $.each(self.content, function (key, value) {
            if (key != self.transferFeeKey) {
                optionsTotal += parseInt(value);
            }
        });
        if (typeof (self.content[self.transferFeeKey]) != 'undefined') {
            optionsTotal += self.content[self.transferFeeKey];
        }
        //
        //optionsTotal
        return optionsTotal;
    };
    this._calculateOptionsWithoutFee = function () {
        var optionsTotal = 0;
        $.each(self.content, function (key, value) {
            if (key != self.transferFeeKey) {
                optionsTotal += parseInt(value);
            }
        });
        //
        return optionsTotal;
    };
    this._calculateTotal = function () {
        self.total = self._calculateOptions();
        if (self.total < 0) {
            self.total = 0;
        }
        return self.total;
    };

}
;
