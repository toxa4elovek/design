'use strict';

;(function () {
    var experts = $('.experts');
    var Cart = new AdvancedCart();
    var endStep1DatePicker = $('.first-datepick');
    var finishChooseOfWinnerPicker = $('.second-datepick');

    ReactDOM.render(React.createElement(Receipt, { data: payload.receipt }), document.getElementById('receipt-container'));

    ReactDOM.render(React.createElement(ProjectRewardInput, { payload: payload }), document.getElementById('project-reward'));

    if (endStep1DatePicker.length > 0) {
        var finishDateMoment = moment($('input[name=finishDate]').val());
        endStep1DatePicker.datetimepicker({ locale: 'ru' });
        endStep1DatePicker.data("DateTimePicker").date(finishDateMoment);
        endStep1DatePicker.on("dp.change", function (e) {
            $('input[name=finishDate]').val(e.date.format('YYYY-MM-DD HH:mm:00'));
            $('.day', '.finishDate').text(e.date.format('DD'));
            $('.month', '.finishDate').text(e.date.format('MMMM'));
            $('.weekday_time', '.finishDate').text(e.date.format('dd, HH:mm'));
        });
    }

    if (finishChooseOfWinnerPicker.length > 0) {
        var chooseWinnerFinishDateMoment = moment($('input[name=chooseWinnerFinishDate]').val());
        finishChooseOfWinnerPicker.datetimepicker({ locale: 'ru', defaultDate: chooseWinnerFinishDateMoment });
        finishChooseOfWinnerPicker.data("DateTimePicker").date(chooseWinnerFinishDateMoment);
        finishChooseOfWinnerPicker.on("dp.change", function (e) {
            $('input[name=chooseWinnerFinishDate]').val(e.date.format('YYYY-MM-DD HH:mm:00'));
            $('.day', '.chooseWinnerFinishDate').text(e.date.format('DD'));
            $('.month', '.chooseWinnerFinishDate').text(e.date.format('MMMM'));
            $('.weekday_time', '.chooseWinnerFinishDate').text(e.date.format('dd, HH:mm'));
        });
    }

    $('.multi-check').change(function () {
        $(this).parent().parent().parent().children('.label').toggleClass('unfold');
        experts.show();
        var firstExpert = experts.children().first();
        var firstCheckbox = $('input', firstExpert);
        $(firstCheckbox).attr('checked', 'checked');
        if ($(this).is(':checked')) {
            var newRow = { "name": 'экспертное мнение', "value": parseInt($(this).data('optionValue')) };
            payload.receipt = ReceiptAccessor.add(payload.receipt, newRow);
            $('#expert-label').html($(firstCheckbox).data('optionValue') + 'р.');
            var offset = $(this).closest('.ribbon').offset();
            $.scrollTo(offset.top - 20, { duration: 600 });
        } else {
            var title = 'экспертное мнение';
            payload.receipt = ReceiptAccessor.removeByName(payload.receipt, title);
            $('.expert-check', '.experts').removeAttr('checked');
            experts.hide();
        }
        SubscribedBriefActions.updateReceipt();
    });

    $('.expert-check', '.experts').change(function () {
        var expertLabel = $('#expert-label');
        var multiCheck = $('.multi-check');
        var currentExpertTotal = ReceiptAccessor.get(payload.receipt, $(this).data('optionTitle'));
        if ($(this).is(':checked')) {
            if (!expertLabel.hasClass('unfold')) {
                expertLabel.addClass('unfold');
            }
            if (multiCheck.is(':checked') == false) {
                multiCheck.attr('checked', 'checked');
            }
            var newValue = 0;
            if (currentExpertTotal === null) {
                newValue = $(this).data('optionValue');
            } else {
                newValue = $(this).data('optionValue') + currentExpertTotal;
            }
            expertLabel.html('+' + newValue + '.-');
            var newRow = { "name": $(this).data('optionTitle'), "value": parseInt(newValue) };
            ReceiptAccessor.add(payload.receipt, newRow);
        } else {
            var newValue = currentExpertTotal - $(this).data('optionValue');
            var editedRow = { "name": $(this).data('optionTitle'), "value": parseInt(newValue) };
            expertLabel.html('+' + newValue + '.-');
            ReceiptAccessor.add(payload.receipt, editedRow);
            if ($('.expert-check:checked', '.experts').length == 0) {
                payload.receipt = ReceiptAccessor.removeByName(payload.receipt, 'экспертное мнение');
                expertLabel.removeClass('unfold');
                $('#experts-checkbox').removeAttr('checked');
            }
        }
        SubscribedBriefActions.updateReceipt();
    });

    $('.single-check').change(function () {
        $(this).parent().parent().parent().children('.label').toggleClass('unfold');
        if ($(this).is(':checked')) {
            var newRow = { "name": $(this).data('optionTitle'), "value": parseInt($(this).data('optionValue')) };
            payload.receipt = ReceiptAccessor.add(payload.receipt, newRow);
        } else {
            var title = $(this).data('optionTitle');
            payload.receipt = ReceiptAccessor.removeByName(payload.receipt, title);
        }
        SubscribedBriefActions.updateReceipt();
    });

    $('#phonebrief').change(function () {
        if ($(this).attr('checked') == 'checked') {
            $('#explanation_brief').show();
            var offset = $(this).closest('.ribbon').offset();
            $.scrollTo(offset.top - 20, { duration: 600 });
        } else {
                $('#explanation_brief').hide();
            }
    });

    $('#hideproject').change(function () {
        if ($(this).attr('checked') == 'checked') {
            $('.visibility-eye-tooltip').show();
            var offset = $(this).closest('.ribbon').offset();
            $.scrollTo(offset.top - 20, { duration: 600 });
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
            $.scrollTo(offset.top - 20, { duration: 600 });
        } else {
            $('#explanation_pinned').hide();
        }
    });

    $('#promocodecheck').change(function () {
        if ($(this).attr('checked') == 'checked') {
            $('#explanation_promo').show();
            var offset = $(this).closest('.ribbon').offset();
            $.scrollTo(offset.top - 20, { duration: 600 });
        } else {
            $('#explanation_promo').hide();
        }
    });

    $('#createad').change(function () {
        if ($(this).attr('checked') == 'checked') {
            $('#explanation_ad').show();
            var offset = $(this).closest('.ribbon').offset();
            $.scrollTo(offset.top - 20, { duration: 600 });
        } else {
            $('#explanation_ad').hide();
        }
    });

    $('.expand_extra').on('click', function () {
        var extraOptions = $('.extra_options');
        extraOptions.toggle();
        if (extraOptions.is(":visible")) {
            $(this).text('– Дополнительная информация');
        } else {
            $(this).text('+ Дополнительная информация');
        }
        return false;
    });

    $('.steps-link').click(function () {
        var stepNum = $(this).data('step');

        _gaq.push(['_trackEvent', 'Создание проекта', 'Пользователь перешел на второй шаг брифа']);
        $('.middle').not('#step' + stepNum).hide();
        $('#step' + stepNum).show();
        $.scrollTo($('#header-bg'), { duration: 600 });

        if (stepNum == 2) {
            $(".slider").each(function (index, object) {
                var value = 5;
                if (typeof slidersValue != "undefined") {
                    value = slidersValue[index];
                }
                $(object).slider({
                    disabled: false,
                    value: value,
                    min: 1,
                    max: 9,
                    step: 1,
                    slide: function slide(event, ui) {
                        var rightOpacity = ((ui.value - 1) * 0.08 + 0.36).toFixed(2);
                        var leftOpacity = (1 - (ui.value - 1) * 0.08).toFixed(2);
                        $(ui.handle).parent().parent().next().css('opacity', rightOpacity);
                        $(ui.handle).parent().parent().prev().css('opacity', leftOpacity);
                    }
                });
            });
        }
        return false;
    });

    var tosCheckBox = $('input[name=tos]');

    $('#save').click(function () {
        if (false === tosCheckBox.prop('checked')) {
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
                $.scrollTo(offset.top - 10, { duration: 600 });
            }
        }
        return false;
    });

    $('#close_tos').on('click', function () {
        $('.mobile-close').click();
        return false;
    });

    $('#agree').on('click', function () {
        $('.mobile-close').click();
        tosCheckBox.prop('checked', 'checked');
        $('#save').click();
        return false;
    });

    var fileIds = [];
    var uploader = $("#fileupload").damnUploader({
        url: '/pitchfiles/add.json',
        onSelect: function onSelect(file) {
            onSelectHandler.call(this, file, fileIds, Cart);
        }
    });

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
        style_formats: [{ title: 'Основной текст', inline: 'span', classes: "regular" }, { title: 'Заголовок', inline: 'span', classes: "greyboldheader" }, { title: 'Дополнение', inline: 'span', classes: "supplement2" }],
        setup: function setup(ed) {
            ed.on('keyup', function () {
                var chars = ed.getContent().length;
                var indicator = $('#indicator-desc');
                indicator.removeClass('low normal good');
                var textArea = $('#full-description');
                if (chars < textArea.data('normal')) {
                    indicator.addClass('low');
                } else if (chars < textArea.data('high')) {
                    indicator.addClass('normal');
                } else {
                    indicator.addClass('good');
                }
            });
        }
    });

    SubscribedBriefDispatcher.register(function (eventPayload) {
        if (eventPayload.actionType === 'reward-input-updated') {
            var total = 0;
            payload.receipt.forEach(function (row) {
                if (row.name == "Награда Дизайнеру") {
                    row.value = eventPayload.newValue;
                }
                total += row.value;
            });
            ReactDOM.render(React.createElement(Receipt, { data: payload.receipt }), document.getElementById('receipt-container'));
        }
        if (eventPayload.actionType === 'update-receipt') {
            ReactDOM.render(React.createElement(Receipt, { data: payload.receipt }), document.getElementById('receipt-container'));
        }
    });

    function appendTosCheck() {
        $('#popup-need-agree-tos').modal({
            containerId: 'spinner',
            opacity: 80,
            closeClass: 'mobile-close',
            onShow: function onShow() {
                $('#popup-need-agree-tos').fadeTo(600, 1);
            }
        });
    }
})();