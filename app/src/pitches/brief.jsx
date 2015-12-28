$(document).ready(function () {
    const awardInput = $('#award');

    tinymce.init({
        selector: ".enable-editor",
        content_css: "/css/brief_wysiwyg.css",
        language: "ru",
        height: "240",
        width: '538',
        relative_urls: false,
        remove_script_host: false,
        menubar: false,
        plugins: ["link,lists,charmap,paste"],
        toolbar: "styleselect,link,bullist,numlist,charmap",
        style_formats: [
            {title: 'Основной текст', inline: 'span', classes: "regular"},
            {title: 'Заголовок', inline: 'span', classes: "greyboldheader"},
            {title: 'Дополнение', inline: 'span', classes: "supplement2"}
        ],
        setup: function(ed) {
            ed.on('keyup', function() {
                var chars = ed.getContent().length;
                var indicator = $('#indicator-desc');
                indicator.removeClass('low normal good');
                var textarea = $('#full-description');
                const tooltipBriefDescription = $('#tooltip-brief-description');
                if (chars < textarea.data('normal')) {
                    indicator.addClass('low');
                    tooltipBriefDescription.fadeIn();
                } else if (chars < textarea.data('high')) {
                    indicator.addClass('normal');
                    tooltipBriefDescription.fadeOut();
                } else {
                    indicator.addClass('good');
                    tooltipBriefDescription.fadeOut();
                }
            });
        }
    });

    /* Download Form Select */
    if ((window.File != null) && (window.FileList != null)) {
        $('#new-download').show();
    } else {
        $('#old-download').show();
    }

    var input = $('#searchTerm');
    if (input.hasClass('placeholder')) {
        input.removeClass('placeholder');
    }

    // steps menu
    $('.steps-link').click(function () {
        var stepNum = $(this).data('step');
        var isBilled = $('#billed').val();
        var existsNotPublshed = (Cart.id != 0) && (isBilled == 0);
        var notExists = (Cart.id == 0);
        if ($('input[name="isGuaranteed"]:checked').length == 0) {
            $.scrollTo(awardInput, {duration: 600, onAfter: function () {
                    alert('Необходимо уточнить, оставляете ли вы проект без гарантий или создаете гарантированный проект.');
                }
            });
            return false;
        }
        if (stepNum == 3) {
            if (false === $('input[name=tos]').prop('checked')) {
                appendTosCheck();
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
            const phoneNumberInput = $('#phonenumber');
            if ((phoneNumberInput.val() == '') || (phoneNumberInput.val() == '+7 XXX XXX XX XX') || (phoneNumberInput.val() == '+7 ХХХ ХХХ ХХ ХХ')) {
                phoneNumberInput.addClass('wrong-input');
                var offset = $('#explanation_brief').prev().offset();
                $.scrollTo(offset.top - 20, {duration: 600});
                return false;
            } else {
                if ((stepNum == 3) && ((notExists == true) || (existsNotPublshed == true))) {
                    if (Cart.prepareData()) {
                        Cart.saveData();
                        _gaq.push(['_trackEvent', 'Создание проекта', 'Пользователь перешел на третий шаг брифа']);
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
            _gaq.push(['_trackEvent', 'Создание проекта', 'Пользователь перешел на второй шаг брифа']);
            $('.middle').not('#step' + stepNum).hide();
            $('#step' + stepNum).show();
            $.scrollTo($('#header-bg'), {duration: 600});
            if (stepNum == 2) {
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
    });


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
        });
    });

    const promoCodeInput = $('#promocode');

    function checkPromocode() {
        var value = promoCodeInput.val();
        if (promoCodeInput.prop('disabled') || value.length == 0) {
            return false;
        }
        $.post('/promocodes/check.json', {"code": value}, function (response) {
            const promoHint = $('#promo-hint');
            if (response == 'false') {
                if(value != '8888') {
                    promoHint.css('display', 'inline-block').text('Промокод неверен!');
                }
            } else {
                promoHint.css('display', 'inline-block').text('Промокод активирован!');
                if ((response.type == 'pinned') || (response.type == 'misha')) {
                    Cart.addOption("«Прокачать» проект", 0);
                    $('input[type=checkbox]', '#pinned-block').attr('checked', 'checked');
                    $('input[type=checkbox]', '#pinned-block').data('optionValue', '0');
                    $('.label', '#pinned-block').text('0р.').addClass('unfold');
                } else if (response.type == 'discount') {
                    Cart.transferFeeDiscount = 700;
                    Cart.updateFees();
                    Cart._renderCheck();
                } else if (response.type == 'in_twain') {
                    Cart.feeRatesReCalc(2);
                    Cart.updateFees();
                    Cart._renderCheck();
                } else if(response.type == 'custom_discount') {
                    Cart.transferFeeDiscountPercentage = response.data;
                    Cart.updateFees();
                    Cart._renderCheck();
                }
                Cart.promocodes.push(value);
            }
        });
    }
    checkPromocode();

    $('.expand_extra').on('click', function() {
        const extraOptions = $('.extra_options');
        extraOptions.toggle();
        if(extraOptions.is(":visible")) {
            $(this).text('– Дополнительная информация')
        }else {
            $(this).text('+ Дополнительная информация')
        }
        return false;
    });

    promoCodeInput.live('keyup', function () {
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

    const experts = $('.experts');

    $('#expert-trigger').click(function () {
        if ($('#experts-checkbox').is(':checked') == false) {
            experts.toggle();
        }
        return false;
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
        experts.show();
        var firstExpert = experts.children().first();
        var firstCheckbox = $('input', firstExpert);
        $(firstCheckbox).attr('checked', 'checked');
        if ($(this).is(':checked')) {
            Cart.addOption('экспертное мнение', $(firstCheckbox).data('optionValue'));
            $('#expert-label').html($(firstCheckbox).data('optionValue') + 'р.');
            var offset = $(this).closest('.ribbon').offset();
            $.scrollTo(offset.top - 20, {duration: 600});
        } else {
            Cart.removeOption('экспертное мнение');
            $('.expert-check', '.experts').removeAttr('checked');
            experts.hide();
        }
    });

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
            var newValue = Cart.getOption($(this).data('optionTitle')) - $(this).data('optionValue');
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

    $('#hide-check').click(function () {
        $(this).parent().removeClass('expanded');
        return false;
    });

    $('#show-check').click(function () {
        $(this).parent().addClass('expanded');
        return false;
    });

    /*
     * Append Mobile Modal
     */
    function appendTosCheck() {
        $('#popup-need-agree-tos').modal({
            containerId: 'spinner',
            opacity: 80,
            closeClass: 'mobile-close',
            onShow: function () {
                $('#popup-need-agree-tos').fadeTo(600, 1);
            }
        });
    }

    $('#close_tos').on('click', function() {
        $('.mobile-close').click();
        return false;
    });

    $('#agree').on('click', function() {
        $('.mobile-close').click();
        $('input[name=tos]').prop('checked', 'checked');
        $('#save').click();
        return false;
    });

    $('.savedraft').click(function () {
        if (false === $('input[name=tos]').prop('checked')) {
            appendTosCheck();
        } else {
            if (Cart.prepareData()) {
                if (uploader.damnUploader('itemsCount') > 0) {
                    $('#loading-overlay').modal({
                        containerId: 'spinner',
                        opacity: 80,
                        close: false
                    });
                    uploader.damnUploader('startUpload');
                } else {
                    Cart.saveData(false);
                    _gaq.push(['_trackEvent', 'Создание проекта', 'Пользователь сохранил черновик']);
                }
            } else {
                var offset = $('.wrong-input').parent().offset();
                $.scrollTo(offset.top - 10, {duration: 600});
            }
        }
        return false;
    });

    $('#save').click(function () {
        if (false === $('input[name=tos]').prop('checked')) {
            alert('Вы должны принять правила и условия');
        } else {
            if (Cart.prepareData()) {
                if (uploader.damnUploader('itemsCount') > 0) {
                    $('#loading-overlay').modal({
                        containerId: 'gotest-popup_gallery',
                        opacity: 80,
                        close: false
                    });
                    uploader.damnUploader('startUpload')
                } else {
                    Cart.saveData();
                }
                _gaq.push(['_trackEvent', 'Создание проекта', 'Пользователь перешел на третий шаг брифа']);
            } else {
                var offset = $('.wrong-input').parent().offset();
                $.scrollTo(offset.top - 10, {duration: 600});
            }
        }
        return false;
    });

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

    $('#sub-site').keyup(function () {
        $(this).addClass('freeze');
        $(this).change();
    });

    $('input[name=package-type]').on('change', function () {
        var newMinValue = $(this).data('minValue');
        var award = awardInput;
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
        recalcMinAwardWithNumOfPagesChange();
        award.change();
    });

    $('#sub-site').numeric({"negative": false, "decimal": false}, function () {
    });

    function recalcMinAwardWithNumOfPagesChange() {
        var $subsiteElement = $('#sub-site')
        var value = $subsiteElement.val();
        if ($subsiteElement.hasClass('freeze')) {
            $subsiteElement.removeClass('freeze');
            if (($subsiteElement.val() == '') || ($subsiteElement.val() == 0)) {
                value = 1;
            }
        } else {
            if (($subsiteElement.val() == '') || ($subsiteElement.val() == 0)) {
                $subsiteElement.val('1');
                value = 1;
            }
        }
        var award = awardInput;
        var defLow = award.data('lowDef');
        var defNormal = award.data('normalDef');
        var defHigh = award.data('highDef');
        var mult = $subsiteElement.data('mult');
        if ((typeof (mult) == undefined) || (!mult)) {
            mult = parseInt(award.data('lowDef')) / 2;
        }
        var extraNormal = (defNormal - defLow);
        var extraHigh = (defHigh - defLow);
        var minValue = ((value - 1) * mult) + defLow;
        $('#labelPrice').text(minValue + 'Р.');
        award.data('minimalAward', minValue);
        award.data('low', minValue);
        //award.data('lowDef', minValue);
        award.data('normal', minValue + extraNormal);
        award.data('high', minValue + extraHigh);
        //$('#award').data('lowDef', minValue );
        //$('#award').data('highDef', minValue + 18000);
        //$('#award').data('normalDef', minValue + 8000);
        award.blur();
    }

    // AWARD

    awardInput.numeric({"negative": false, "decimal": false}, function () {
        //console.log('Inside numeric value is - ' + awardInput.val());
    });

    const indicator = $('#indicator');

    // award size
    awardInput.blur(function () {
        var input = $(this);
        var minimalAward = input.data('minimalAward');
        //console.log('blur - current award input value - ' + input.val())
        //console.log('blur - current minimal award - ' + minimalAward)
        if (input.val() == '') {
            //console.log('Award input is empty, setting value as minimumAward - ' + minimalAward);
            input.val(minimalAward)
            award = minimalAward;
        }
        var award = 0;
        indicator.removeClass('low normal good');
        if(awardInput.hasClass('placeholder')) {
            awardInput.val(Calculator.getMiddlePrice());
        }
        if (minimalAward > input.val()) {
            //console.log('minimalAward is more than award value' - minimalAward);
            input.val(minimalAward);
            input.addClass('initial-price');
            indicator.addClass('normal');
            Cart.transferFee = feeRates.normal;
        } else {
            input.removeClass('initial-price');
            award = input.val();
            //console.log('Value is exists - ' + award);
        }
        if(award <= 14980) {
            $('#fastpitch-tooltip').fadeIn();
        }else {
            $('#fastpitch-tooltip').fadeOut();
        }
        //console.log('redrawing indicator and updating cart - ' + award);
        drawIndicator(input, award);
        Cart.updateOption($(this).data('optionTitle'), award);
        //console.log('Value after blur - ' + awardInput.val())
        //console.log('blur end- current minimal award - ' + minimalAward)
    });

    function formatMoney(value) {
        value = value.replace(/(.*)\.00/g, "$1");
        let counter = 1;
        while(value.match(/\w\w\w\w/)) {
            value = value.replace(/^(\w*)(\w\w\w)(\W.*)?$/, "$1 $2$3");
            counter ++;
            if(counter > 6) break;
        }
        return value;
    }

    awardInput.keyup(function () {
        const input = $(this);

        input.removeClass('initial-price');
        if ((input.val() == '') && (!input.is(':focus'))) {
            input.val(0);
        }
        let award = 0;
        if (input.val() == '') {
            award = 0;
        } else {
            award = input.val();
        }
        if(Cart.id) {
            let row = $('tr[data-id=' + Cart.id + ']', '.all-pitches');
            if(row.length > 0) {
                let tdItem = $('.price', row);
                let formattedAward = formatMoney(award);
                let newText = formattedAward + '.-';
                tdItem.text(newText);
            }

        }
        drawIndicator(input, award);
        Cart.updateOption($(this).data('optionTitle'), award);
    });

    awardInput.click(function () {
        var input = $(this);
        input.removeClass('initial-price');
        if ($('#sub-site').length == 0) {
            //console.log('No sub-site');
            if (input.val() == input.data('minimalAward')) {
                input.val('');
            }
        }
        var minimalAward = input.data('minimalAward')
        //console.log('MinimalAward in click - ' + minimalAward)
        //console.log(awardInput.attr('placeholder'))
        //console.log(awardInput.attr('value'))
        //console.log('Inside award click - ' + awardInput.val());
    });

    var Calculator = new AwardCalculator();

    $('#sub-site').change(function () {
        //console.log('Changing sub-site...');
        recalcMinAwardWithNumOfPagesChange();
        var updatedAverageAward = awardInput.data('minimalAward');
        //console.log('MIddle price from calculator - ' + Calculator.getMiddlePrice())
        //console.log('Updated minimalAward value - ' + updatedAverageAward);
        if(awardInput.hasClass('placeholder')) {
            awardInput.val(Calculator.getMiddlePrice());
        }
        //console.log('Current value of award input - ' + awardInput.val());
        drawIndicator(awardInput, awardInput.val());
        awardInput.blur();
    });

    $('#sub-site').focus(function () {
        $(this).removeClass('initial-price');
        $(this).removeClass('freeze');
    });

    $('input[name=isGuaranteed]').on('change', function () {
        var radioButton = $(this);
        if (radioButton.val() == 1) {
            Cart.addOption(radioButton.data('optionTitle'), radioButton.data('optionValue'));
            $('#guaranteedTooltip').show();
            $('#nonguaranteedTooltip').hide();
        } else {
            Cart.removeOption(radioButton.data('optionTitle'));
            $('#nonguaranteedTooltip').show();
            $('#guaranteedTooltip').hide();
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
        var input = awardInput;
        var newMinimum = parseInt($('#copybaseminprice').val() * mod);
        var minValue = $(this).data('minValue');
        $('#labelPrice').text(newMinimum + 'Р.');
        input.data('minimalAward', newMinimum);
        input.blur();
    })

    $('#phonebrief').change(function () {
        if ($(this).attr('checked') == 'checked') {
            $('#explanation_brief').show();
            var offset = $(this).closest('.ribbon').offset();
            $.scrollTo(offset.top - 20, {duration: 600});
            Cart.validatetype = 2;
        } else {
            Cart.validatetype = 1;
            $('#explanation_brief').hide();
        }
    });

    $('#hideproject').change(function () {
        if ($(this).attr('checked') == 'checked') {
            $('.visibility-eye-tooltip').show();
            var offset = $(this).closest('.ribbon').offset();
            $.scrollTo(offset.top - 20, {duration: 600});
            $('#explanation_closed').show();
        } else {
            $('.visibility-eye-tooltip').hide();
            $('#explanation_closed').hide();
        }
    });

    $('#pinproject').change(function () {
        if ($(this).attr('checked') == 'checked') {
            $('#explanation_pinned').show();
            var offset = $(this).closest('.ribbon').offset();
            $.scrollTo(offset.top - 20, {duration: 600});
        } else {
            $('#explanation_pinned').hide();
        }
    });

    $('#promocodecheck').change(function () {
        if ($(this).attr('checked') == 'checked') {
            $('#explanation_promo').show();
            var offset = $(this).closest('.ribbon').offset();
            $.scrollTo(offset.top - 20, {duration: 600});
        } else {
            $('#explanation_promo').hide();
        }
    });

    $('#createad').change(function () {
        if ($(this).attr('checked') == 'checked') {
            $('#explanation_ad').show();
            var offset = $(this).closest('.ribbon').offset();
            $.scrollTo(offset.top - 20, {duration: 600});
        } else {
            $('#explanation_ad').hide();
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
                $("#paybutton-payture").hide();
                $('#s3_kv').hide();
                break;
            case 'payture':
                $("#paybutton-payture").fadeIn(100);
                $("#paybutton-payture");
                $("#paymaster-images").show();
                $("#paymaster-select").hide();
                $('#s3_kv').hide();
                break;
            case 'paymaster':
                $("#paybutton-paymaster").removeAttr('style');
                $("#paybutton-payanyway").fadeOut(100);
                $("#paymaster-images").hide();
                $("#paymaster-select").show();
                $("#paybutton-payture").hide();
                $('#s3_kv').hide();
                break;
            case 'offline':
                $("#paybutton-payanyway").fadeOut(100);
                $("#paybutton-paymaster").css('background', '#a2b2bb');
                $("#paymaster-images").show();
                $("#paymaster-select").hide();
                $("#paybutton-payture").hide();
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
        var award = awardInput.val();
        const fastPitchTooltip = $('#fastpitch-tooltip');
        if((award <= 14980) && (award != '')) {
            if(fastPitchTooltip.not(':visible').length == 1) {
                fastPitchTooltip.fadeIn();
            }
        }else {
            if(fastPitchTooltip.is(':visible').length == 1) {
                fastPitchTooltip.fadeOut();
            }
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
            awardInput.focus().val(pitchInitOptions.award).blur();
        }
        if (pitchInitOptions.name) {
            $('input[name=title]').focus().val(pitchInitOptions.name);
        }
        if ((pitchInitOptions.fillBrief == "true") && ($('#phonebrief').attr('checked') != 'checked')) {
            $('#phonebrief').click();
        }
    }

    if ($('#sub-site').val() > 1) {
        var award = awardInput;
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
    var value = awardInput.val();
    if(awardInput.val() == '') {
        value = awardInput.attr('placeholder');
    }
    drawIndicator(awardInput, value);

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
        /*if (($(this).attr('id') == 'yur-kpp') && ($('#yur-inn').val().length == 10)) {
            $(this).val('');
            return true;
        }*/
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
    this.awardKey = ($('input[name=category_id]').val() == 7) ? 'Награда копирайтеру' : 'Награда Дизайнеру';
    this.data = {};
    this.fileIds = [];
    this.specificTemplates = [];
    this.validatetype = 1;
    this.transferFee = feeRates.normal;
    this.transferFeeDiscount = 0;
    this.transferFeeDiscountPercentage = 0;
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
        var initVal = this.award.val();
        if (this.award.val() == '') {
            initVal = this.award.attr('value');
        }
        self.addOption(self.awardKey, initVal);
        var radioButton = $('#guaranteedTrue');
        radioButton.prop("checked", true) ;
        Cart.addOption(radioButton.data('optionTitle'), radioButton.data('optionValue'));
        if (self.id > 0) {
            var awardName = ($('input[name=category_id]').val() == 7) ? 'Награда копирайтеру' : 'Награда Дизайнеру';
            self.addOption(awardName, initVal);
        } else {
            //self.updateOption('Заполнение брифа', 0);
        }
        if ($('#discount').length > 0) {
            self.transferFeeDiscount = 700;
        }
        if ($('#custom_discount').length > 0) {
            self.transferFeeDiscountPercentage = $('#custom_discount').val();
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
        });

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
    this._priceDecorator = function(price) {
        price = price.toString();
        price = price.replace(/(.*)\.00/g, "$1");
        counter = 1;
        while(price.match(/\w\w\w\w/)) {
            price = price.replace(/^(\w*)(\w\w\w)(\W.*)?$/, "$1 $2$3");
            counter ++;
            if(counter > 6) break;
        }
        return price;
    }
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
        self._calculateOptionsWithoutFee();
        var award = self.getOption(self.awardKey);
        var commision = Math.round(award * this.transferFee) - this.transferFeeDiscount;
        if(this.transferFeeDiscountPercentage != 0) {
            let decimal = this.transferFeeDiscountPercentage / 100;
            let amount = Math.round(commision * decimal);
            commision -= amount;
        }
        if (commision < 0) {
            commision = 0;
        }
        self.content[self.transferFeeKey] = commision;
    };
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
            'private': self.getOption('Скрыть проект'),
            'social': self.getOption('Рекламный Кейс'),
            'experts': self._expertArray(),
            'email': self.getOption('Email рассылка'),
            'pinned': self.getOption('«Прокачать» проект'),
            'timelimit': self.getOption('Установлен срок'),
            'brief': self.getOption('Заполнение брифа'),
            'guaranteed': self.getOption('Гарантированный проект'),
            'timelimitOption': self.getTimelimit()
        };
        var commonPitchData = {
            'id': self.id,
            'title': $('input[name=title]').val(),
            'category_id': $('input[name=category_id]').val(),
            'industry': $('input[name=industry]').val() || '',
            'jobTypes': self._jobArray(),
            'business-description': $('textarea[name=business-description]').val(),
            'description': tinyMCE.activeEditor.getContent(),
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
            /*
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
            */
        }
        return result;
    };
    this.saveData = function (simplesave) {
        if (self.data.features.award == 0) {
            alert('Укажите награду для дизайнера!');
        } else {
            $.post('/pitches/add.json', self.data, function (response) {
                if(typeof(simplesave) == 'undefined') {
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
                        $('input[name=MNT_AMOUNT]').val(response.total);
                        $('input[name=MNT_TRANSACTION_ID]').val(response.id);

                        // Payture
                        $('#paybutton-payture').attr('href', '/payments/startpayment/' + self.id);

                        // Bill
                        $('#pdf-link').attr('href', '/pitches/getpdf/godesigner-pitch-' + self.id + '.pdf');
                    })
                }else {
                    var title = Cart.data.commonPitchData.title;
                    var award = Cart._priceDecorator(Cart.data.features.award);
                    var id = response;
                    var scroll = false;
                    if(($('#pitch-panel').length == 1) && ($('tr[data-id="' + id + '"]', '#pitch-panel').length == 0)) {
                        if($('tr', '#pitch-panel').length % 2 == 0) {
                            var evenClass = 'even';
                        }else {
                            var evenClass = ''
                        }
                        var row =
                        '<tr data-id="' + id + '" class="selection ' + evenClass + ' coda"><td></td> \
                        <td class="pitches-name mypitches"><a href="https://www.godesigner.ru/pitches/view/' + id + '">' + title + '</a></td> \
                        <td class="pitches-status mypitches"><a href="https://www.godesigner.ru/pitches/edit/' + id + '">Ожидание оплаты</a></td> \
                        <td class="price mypitches">' + award +'.-</td>\
                        <td class="pitches-edit mypitches"> \
                        <a href="https://www.godesigner.ru/pitches/edit/' + id + '#step3" class="mypitch_pay_link buy" title="оплатить">оплатить</a> \
                        <a href="https://www.godesigner.ru/pitches/edit/' + id + '" class="edit mypitch_edit_link" title="редактировать">редактировать</a> \
                        <a data-id="' + id + '" href="https://www.godesigner.ru/pitches/delete/' + id + '" class="delete deleteheader mypitch_delete_link" title="удалить">удалить</a> \
                        </td></tr>';
                        $('#header-table').append(row);
                        scroll = true;
                    }else if ($('tr[data-id="' + id + '"]', '#pitch-panel').length == 0) {
                        var panel =
                            '<div id="pitch-panel"><div class="conteiner"><div class="content"> \
                            <table class="all-pitches" id="header-table"><tbody> \
                            <tr data-id="' + id + '" class="selection even coda"><td></td> \
                        <td class="pitches-name mypitches"><a href="https://www.godesigner.ru/pitches/view/' + id + '">' + title + '</a></td> \
                        <td class="pitches-status mypitches"><a href="https://www.godesigner.ru/pitches/edit/' + id + '">Ожидание оплаты</a></td> \
                        <td class="price mypitches">' + award +'.-</td>\
                        <td class="pitches-edit mypitches"> \
                        <a href="https://www.godesigner.ru/pitches/edit/' + id + '#step3" class="mypitch_pay_link buy" title="оплатить">оплатить</a> \
                        <a href="https://www.godesigner.ru/pitches/edit/' + id + '" class="edit mypitch_edit_link" title="редактировать">редактировать</a> \
                        <a data-id="' + id + '" href="https://www.godesigner.ru/pitches/delete/' + id + '" class="delete deleteheader mypitch_delete_link" title="удалить">удалить</a> \
                        </td></tr></tbody></table> \
                        <p class="pitch-buttons-legend"><a href="https://www.godesigner.ru/answers/view/73"><i id="help"></i>Как мотивировать дизайнеров</a></p> \
                        </div></div></div>';
                        $('.wrapper').first().prepend(panel);
                        scroll = true;
                    }
                    if(scroll) {
                        $.scrollTo($('#pitch-panel'), {duration: 600});
                    }
                }
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
        var input = this.award;
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