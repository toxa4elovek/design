;(function() {
    const experts = $('.experts');
    const Cart = new AdvancedCart();
    const types = [
        {"id": 1,"value": "design", "label": "Проект на дизайн", "checked": !payload.isCopywriting},
        {"id": 2, "value": "copyrighting", "label": "Проект на копирайтинг", "checked": payload.isCopywriting}
    ];

    let endStep1DatePicker = $('.first-datepick');
    let finishChooseOfWinnerPicker = $('.second-datepick');

    ReactDOM.render(
        <Receipt data={payload.receipt}/>,
        document.getElementById('receipt-container')
    );

    ReactDOM.render(
        <ProjectRewardInput payload={payload}/>,
        document.getElementById('project-reward')
    );

    ReactDOM.render(
        <ProjectTypesRadioWrapper data={types} />,
        document.getElementById('radios-container')
    );

    if(endStep1DatePicker.length > 0) {
        endStep1DatePicker.on('mouseover', function() {
            $(this).siblings( "a").css("text-decoration", "underline");
        });
        endStep1DatePicker.on('mouseout', function() {
            $(this).siblings( "a").css("text-decoration", "none");
        });
        const finishDateMoment = moment($('input[name=finishDate]').val());
        endStep1DatePicker.datetimepicker({ locale: 'ru', widgetPositioning: {
            horizontal: 'left',
            vertical: 'bottom'
        }});
        endStep1DatePicker.data("DateTimePicker").date(finishDateMoment);
        endStep1DatePicker.on("dp.change", function (e) {
            $('input[name=finishDate]').val(e.date.format('YYYY-MM-DD HH:mm:00'));
            $('.day_cal', '.finishDate').text(e.date.format('DD'));
            $('.month', '.finishDate').text(e.date.format('MMMM'));
            $('.weekday_time', '.finishDate').text(e.date.format('dd, HH:mm'));
            if(!$('.chooseWinnerFinishDate').hasClass('editable_calendar')) {
                let newChooseWinnerFinishDate = e.date;
                newChooseWinnerFinishDate.add(4, 'days');
                finishChooseOfWinnerPicker.data('DateTimePicker').date(newChooseWinnerFinishDate);
            }
        });

    }

    if(finishChooseOfWinnerPicker.length > 0) {
        const chooseWinnerFinishDateMoment = moment($('input[name=chooseWinnerFinishDate]').val());
        finishChooseOfWinnerPicker.datetimepicker({ locale: 'ru', defaultDate: chooseWinnerFinishDateMoment, widgetPositioning: {
            horizontal: 'left',
            vertical: 'bottom'
        }});
        finishChooseOfWinnerPicker.data("DateTimePicker").date(chooseWinnerFinishDateMoment);
        finishChooseOfWinnerPicker.on("dp.change", function (e) {
            $('input[name=chooseWinnerFinishDate]').val(e.date.format('YYYY-MM-DD HH:mm:00'));
            $('.day_cal', '.chooseWinnerFinishDate').text(e.date.format('DD'));
            $('.month', '.chooseWinnerFinishDate').text(e.date.format('MMMM'));
            $('.weekday_time', '.chooseWinnerFinishDate').text(e.date.format('dd, HH:mm'));
        });
        if(finishChooseOfWinnerPicker.hasClass('editable')) {
            finishChooseOfWinnerPicker.on('mouseover', function() {
                $(this).siblings( "a").css("text-decoration", "underline");
            });
            finishChooseOfWinnerPicker.on('mouseout', function() {
                $(this).siblings( "a").css("text-decoration", "none");
            });
        }else {
            finishChooseOfWinnerPicker.css({"width": 0, "height": 0});
        }
    }


    /**
     * Обработка кликов для множественного выбора (эксперты)
     */
    $('.multi-check').change(function () {
        $(this).parent().parent().parent().children('.label').toggleClass('unfold');
        experts.show();
        const firstExpert = experts.children().first();
        const firstCheckbox = $('input', firstExpert);
        $(firstCheckbox).attr('checked', 'checked');
        if ($(this).is(':checked')) {
            const newRow = {"name": 'экспертное мнение', "value": parseInt($(this).data('optionValue'))};
            payload.receipt = ReceiptAccessor.add(payload.receipt, newRow);
            $('#expert-label').html($(firstCheckbox).data('optionValue') + 'р.');
            var offset = $(this).closest('.ribbon').offset();
            $.scrollTo(offset.top - 20, { duration: 600 });
        } else {
            const title = 'экспертное мнение';
            payload.receipt = ReceiptAccessor.removeByName(payload.receipt, title);
            $('.expert-check', '.experts').removeAttr('checked');
            experts.hide();
        }
        SubscribedBriefActions.updateReceipt();
    });

    /**
     * Обработка кликов по конкретным экспертам
     */
    $('.expert-check', '.experts').change(function () {
        const expertLabel = $('#expert-label');
        const multiCheck = $('.multi-check');
        const currentExpertTotal = ReceiptAccessor.get(payload.receipt, $(this).data('optionTitle'));
        if ($(this).is(':checked')) {
            if (!expertLabel.hasClass('unfold')) {
                expertLabel.addClass('unfold');
            }
            if (multiCheck.is(':checked') == false) {
                multiCheck.attr('checked', 'checked');
            }
            let newValue = 0;
            if (currentExpertTotal === null) {
                newValue = $(this).data('optionValue');
            } else {
                newValue = $(this).data('optionValue') + currentExpertTotal;
            }
            expertLabel.html('+' + newValue + '.-');
            const newRow = {"name": $(this).data('optionTitle'), "value": parseInt(newValue)};
            ReceiptAccessor.add(payload.receipt, newRow);
        } else {
            const newValue = currentExpertTotal - $(this).data('optionValue');
            const editedRow = {"name": $(this).data('optionTitle'), "value": parseInt(newValue)};
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

    /**
     * Обработка кликов обычной доп опции
     */
    $('.single-check').change(function () {
        $(this).parent().parent().parent().children('.label').toggleClass('unfold');
        if ($(this).is(':checked')) {
            const newRow = {"name": $(this).data('optionTitle'), "value": parseInt($(this).data('optionValue'))};
            payload.receipt = ReceiptAccessor.add(payload.receipt, newRow);
        } else {
            const title = $(this).data('optionTitle');
            payload.receipt = ReceiptAccessor.removeByName(payload.receipt, title);
        }
        SubscribedBriefActions.updateReceipt();
    });

    /**
     * Обработка изменения нажатия по "заполнить бриф"
     */
    $('#phonebrief').change(function () {
        if ($(this).attr('checked') == 'checked') {
            $('#explanation_brief').show();
            var offset = $(this).closest('.ribbon').offset();
            $.scrollTo(offset.top - 20, { duration: 600 });
            //Cart.validatetype = 2;
        } else {
            //Cart.validatetype = 1;
            $('#explanation_brief').hide();
        }
    });

    /**
     * Обработка изменения нажатия по "скрытый проект"
     */
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

    /**
     * Обработка изменения нажатия по "прокачать бриф"
     */
    $('#pinproject').change(function () {
        if ($(this).attr('checked') == 'checked') {
            $('#explanation_pinned').show();
            var offset = $(this).closest('.ribbon').offset();
            $.scrollTo(offset.top - 20, { duration: 600 });
        } else {
            $('#explanation_pinned').hide();
        }
    });

    /**
     * Обработка изменения нажатия по "промокод"
     */
    $('#promocodecheck').change(function () {
        if ($(this).attr('checked') == 'checked') {
            $('#explanation_promo').show();
            var offset = $(this).closest('.ribbon').offset();
            $.scrollTo(offset.top - 20, { duration: 600 });
        } else {
            $('#explanation_promo').hide();
        }
    });

    /**
     * Обработка изменения нажатия по "рекламный кейс"
     */
    $('#createad').change(function () {
        if ($(this).attr('checked') == 'checked') {
            $('#explanation_ad').show();
            var offset = $(this).closest('.ribbon').offset();
            $.scrollTo(offset.top - 20, { duration: 600 });
        } else {
            $('#explanation_ad').hide();
        }
    });

    /**
     * Раскрытыие доп информации
     */
    $('.expand_extra').on('click', function () {
        const extraOptions = $('.extra_options');
        extraOptions.toggle();
        if (extraOptions.is(":visible")) {
            $(this).text('– Дополнительная информация');
        } else {
            $(this).text('+ Дополнительная информация');
        }
        return false;
    });

    /**
     * Обработка клика переключения шага
     */
    $('.steps-link').click(function () {
        const stepNum = $(this).data('step');

        _gaq.push(['_trackEvent', 'Создание проекта', 'Пользователь перешел на второй шаг брифа']);
        $('.middle').not('#step' + stepNum).hide();
        $('#step' + stepNum).show();
        $.scrollTo($('#header-bg'), { duration: 600 });

        if (stepNum == 2) {
            // Инициализация слайдеров
            $(".slider").each(function (index, object) {
                let value = 5;
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

    const tosCheckBox = $('input[name=tos]');
    let busy = false;
    $('#save').click(function () {
        SubscribedBriefActions.lockButton();
        if (false === tosCheckBox.prop('checked')) {
            appendTosCheck();
            SubscribedBriefActions.unlockButton();
        } else {
            if (Cart.prepareData(endStep1DatePicker, finishChooseOfWinnerPicker)) {
                if (uploader.damnUploader('itemsCount') > 0) {
                    $('#loading-overlay').modal({
                        containerId: 'spinner',
                        opacity: 80,
                        close: false
                    });
                    uploader.damnUploader('startUpload');
                    SubscribedBriefActions.unlockButton();

                } else {
                    Cart.saveData(false);
                    _gaq.push(['_trackEvent', 'Создание проекта', 'Пользователь сохранил черновик']);
                }
            } else {
                const offset = $('.wrong-input').parent().offset();
                $.scrollTo(offset.top - 10, {duration: 600});
                SubscribedBriefActions.unlockButton();
            }
        }
        return false;
    });

    $('#save-and-pay').on('click', function(event) {
        if(busy === true) {
            return false;
        }
        $( this ).off(event);
        if (false === tosCheckBox.prop('checked')) {
            appendTosCheck();
        } else {
            if (Cart.prepareData(endStep1DatePicker, finishChooseOfWinnerPicker)) {
                if (uploader.damnUploader('itemsCount') > 0) {
                    $('#loading-overlay').modal({
                        containerId: 'spinner',
                        opacity: 80,
                        close: false
                    });
                    uploader.damnUploader('startUpload');
                } else {
                    Cart.saveData(true);
                    _gaq.push(['_trackEvent', 'Создание проекта', 'Пользователь сохранил черновик']);
                }
            } else {
                const offset = $('.wrong-input').parent().offset();
                $.scrollTo(offset.top - 10, {duration: 600});
            }
        }
        return false;
    });

    /**
     * Закрываем окно, если закрывается попап с соглашением
     */
    $('#close_tos').on('click', function() {
        $('.mobile-close').click();
        return false;
    });

    /**
     * Если человек согласен с соглашением, сохраняем проект
     */
    $('#agree').on('click', function() {
        $('.mobile-close').click();
        tosCheckBox.prop('checked', 'checked');
        $('#save').click();
        return false;
    });

    $(window).on('scroll', function () {
        const header = $('header');
        if($(window).scrollTop() > header.offset().top) {
            $('.summary-price').css('position', 'fixed').css('top', '150px');
        }else {
            $('.summary-price').css('position', 'absolute').css('top', (header.offset().top + 155) + 'px');
        }
    });

    $(document).on('click', '.filezone-delete-link', function () {
        const givenId = +$(this).parent().attr('data-id');
        if (Cart.projectId) {
            if (givenId) { // File came from database
                $.post('/pitchfiles/delete', {'id': givenId}, function (response) {
                    if (response != 'true') {
                        alert('При удалении файла произошла ошибка');
                    }
                    const position = $.inArray(givenId, Cart.fileIds);
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
                const position = $.inArray(givenId, Cart.fileIds);
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

    /**
     * Готовим загрузчик файлов
     * @type {Array}
     */
    let fileIds = [];
    const uploader = $("#fileupload").damnUploader({
        url: '/pitchfiles/add.json',
        onSelect: function onSelect(file) {
            onSelectHandler.call(this, file, fileIds, Cart);
        }
    });

    /**
     * Инициализуерм редактор
     */
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
                const chars = ed.getContent().length;
                const indicator = $('#indicator-desc');
                indicator.removeClass('low normal good');
                const textArea = $('#full-description');
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

    /**
     * Диспетчер событий
     */
    SubscribedBriefDispatcher.register(function(eventPayload) {
        if (eventPayload.actionType === 'reward-input-updated') {
            let total = 0;
            payload.receipt.forEach(function (row) {
                if (row.name == "Награда Дизайнеру") {
                    row.value = eventPayload.newValue;
                }
                total += row.value;
            });
            ReactDOM.render(
                <Receipt data={payload.receipt}/>,
                document.getElementById('receipt-container')
            );
        }
        if (eventPayload.actionType === 'update-receipt') {
            ReactDOM.render(
                <Receipt data={payload.receipt}/>,
                document.getElementById('receipt-container')
            );
        }
        if (eventPayload.actionType === 'lock-pay-button') {
            busy = true;
        }
        if (eventPayload.actionType === 'unlock-pay-button') {
            busy = false;
        }
    });

    /**
     * Функция вызывает попап для согласия с соглашением
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

})();