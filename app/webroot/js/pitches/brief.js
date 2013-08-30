$(document).ready(function() {

    var pitchid = '';

    // steps menu
    $('.steps-link').click(function() {
        var stepNum = $(this).data('step');
        var isBilled = $('#billed').val();
        var existsNotPublshed = (Cart.id != 0) && (isBilled == 0);
        var notExists = (Cart.id == 0);
        if($('input[name="isGuaranteed"]:checked').length == 0) {
            alert('Необходимо уточнить, оставляете ли вы питч без гарантий или создаете гарантированный питч.');
            return false;
        }
        if(Cart.validatetype == 1) {
            if((stepNum == 3) && ((notExists == true)|| (existsNotPublshed == true))) {
                if(Cart.prepareData()) {
                    Cart.saveData();
                }else {
                    alert('Не все обязательные поля заполнены');
                }
                return false;
            }
        }else if(Cart.validatetype == 2) {
            if(($('#phonenumber').val() == '') || ($('#phonenumber').val() == '+7 XXX XXX XX XX') || ($('#phonenumber').val() == '+7 ХХХ ХХХ ХХ ХХ')) {
                alert('Оставьте свой телефон, чтобы мы могли с вами связаться');
                return false;
            }else {
                if((stepNum == 3) && ((notExists == true)|| (existsNotPublshed == true))) {
                    if(Cart.prepareData()) {
                        Cart.saveData();
                    }else {
                        alert('Не все обязательные поля заполнены');
                    }
                    return false;
                }
            }

        }
        if(stepNum == 3) {
            /*if(Cart.prepareData()) {
             Cart.saveData();
             }  */
        }else{
            $('.middle').not('#step' + stepNum).hide();
            $('#step' + stepNum).show();
            if(stepNum == 2) {
                //console.log($('#sliderset').length);
                /*sliders*/
                $( ".slider" ).each(function(index, object) {
                    var value = 5;
                    if(typeof(slidersValue) != "undefined") {

                        value = slidersValue[index];
                    }
                    $(object).slider({
                        disabled: false,
                        value: value,
                        min: 1,
                        max: 9,
                        step: 1,
                        slide: function( event, ui ) {
                            var rightOpacity = (((ui.value-1) * 0.08) + 0.36).toFixed(2);
                            var leftOpacity = (1 - ((ui.value-1) * 0.08)).toFixed(2);
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
    $( ".slider" ).each(function(index, object) {
        var value = 5;
        if(typeof(slidersValue) != "undefined") {

            value = slidersValue[index];
        }
        $(object).slider({
            disabled: false,
            value: value,
            min: 1,
            max: 9,
            step: 1,
            slide: function( event, ui ) {
                var rightOpacity = (((ui.value-1) * 0.08) + 0.36).toFixed(2);
                var leftOpacity = (1 - ((ui.value-1) * 0.08)).toFixed(2);
                $(ui.handle).parent().parent().next().css('opacity', rightOpacity);
                $(ui.handle).parent().parent().prev().css('opacity', leftOpacity);
            }
        })
    });

    function checkPromocode() {
        var value = $('#promocode').val();
        $.post('/promocodes/check.json', {"code": value}, function(response){
            if(response == 'false') {
                $('#hint').text('Промокод неверен!');
            }else{
                $('#hint').text('Промокод активирован!');
                if(response.type == 'pinned') {
                    Cart.addOption("“Прокачать” бриф", 0);
                    $('input[type=checkbox]', '#pinned-block').attr('checked', 'checked');
                    $('input[type=checkbox]', '#pinned-block').data('optionValue', '0');
                    $('.label', '#pinned-block').text('+0.-').addClass('unfold');
                }else if(response.type == 'discount') {
                    Cart.transferFeeDiscount = 700;
                    Cart.updateFees();
                    Cart._renderCheck();
                }
            }
        });
    }
    checkPromocode();
    
    $('#promocode').live('keyup', function() {
        checkPromocode();
    });
    
    $(document).on('focus', '.wrong-input', function() {
        $(this).removeClass('wrong-input');
    });

    $('#sliderset').show();

    $('#expert-trigger').click(function() {
        if($('#experts-checkbox').is(':checked') == false) {
            $('.experts').toggle();
        }
        return false;
    });

    $('#award').numeric({ "negative" : false, "decimal": false }, function(){
        var input = $('#award');
        var minAward = input.data('minimalAward');
        $('#indicator').removeClass('low normal good');
        //console.log('award numeric ' + minAward);
        if(minAward > input.val()) {
            input.val(minAward);
            input.addClass('initial-price');
            $('#indicator').addClass('low');
        }else {
            input.removeClass('initial-price');
            if(input.val() < input.data('normal')) {
                $('#indicator').addClass('low');
            }else if (input.val() < input.data('high')){
                $('#indicator').addClass('normal');
            }else {
                $('#indicator').addClass('good');
            }
        }
    });

    $('#award').keyup(function() {
        var input = $(this);
        var minAward = input.data('minimalAward');
        $('#indicator').removeClass('low normal good');

        input.removeClass('initial-price');
        if((input.val() == '') && (!input.is(':focus'))) {
            input.val(0);
        }
        if(input.val() == '') {
            var value = 0;
        }else {
            var value = input.val();
        }
        if(value < input.data('normal')) {
            $('#indicator').addClass('low');
        }else if (value < input.data('high')){
            $('#indicator').addClass('normal');
        }else {
            $('#indicator').addClass('good');
        }
        Cart.updateOption($(this).data('optionTitle'), value);
    })

    $('#award').click(function() {
        $(this).removeClass('initial-price');
        if($('#sub-site').length == 0)  {
            if($('#award').val() == $(this).data('minimalAward'))  {
                $('#award').val('');
            }
        }
    });

    // award size
    $('#award').blur(function() {
        if($(this).val() == '') {
            $(this).val($(this).data('minimalAward'))
        }
        if($(this).val() == $(this).data('minimalAward')) {
            //$(this).addClass('initial-price');
        }
        Cart.updateOption($(this).data('optionTitle'), $('#award').val());
    });

    // simple options
    $('.single-check').change(function() {
        $(this).parent().parent().parent().children('.label').toggleClass('unfold');
        if($(this).is(':checked')) {
            Cart.addOption($(this).data('optionTitle'), $(this).data('optionValue'));

        }else {
            Cart.removeOption($(this).data('optionTitle'));
        }
    });

    // multi options
    $('.multi-check').change(function() {
        $(this).parent().parent().parent().children('.label').toggleClass('unfold');
        $('.experts').show();
        var firstExpert = $('.experts').children().first();
        var firstCheckbox = $('input', firstExpert);
        $(firstCheckbox).attr('checked', 'checked');
        if($(this).is(':checked')) {
            Cart.addOption('экспертное мнение', $(firstCheckbox).data('optionValue'));
            $('#expert-label').html('+' + $(firstCheckbox).data('optionValue') + '.-');
        }else{
            Cart.removeOption('экспертное мнение');
            $('.expert-check', '.experts').removeAttr('checked');
            $('.experts').hide();
        }
    })

    $('.expert-check', '.experts').change(function() {
        if($(this).is(':checked')) {
            if(!$('#expert-label').hasClass('unfold')) {
                $('#expert-label').addClass('unfold');
            }
            if($('.multi-check').is(':checked') == false) {
                $('.multi-check').attr('checked', 'checked');
            }
            if(typeof(Cart.getOption($(this).data('optionTitle'))) == 'undefined') {
                var newValue = $(this).data('optionValue');
            }else {
                var newValue = $(this).data('optionValue') + Cart.getOption($(this).data('optionTitle'));
            }
            $('#expert-label').html('+' + newValue + '.-');
            Cart.addOption($(this).data('optionTitle'), newValue);
        }else {
            console.log($(this).data('optionTitle'))
            console.log($(this).data('optionValue'));
            console.log(Cart.getOption($(this).data('optionTitle')))
            console.log(Cart.content);
            var newValue = Cart.getOption($(this).data('optionTitle')) - $(this).data('optionValue');
            console.log(newValue);
            $('#expert-label').html('+' + newValue + '.-');
            Cart.updateOption($(this).data('optionTitle'), newValue);
            if($('.expert-check:checked', '.experts').length == 0) {
                Cart.removeOption('экспертное мнение');
                $('#expert-label').removeClass('unfold');
                $('#experts-checkbox').removeAttr('checked');
            }
        }
    });

    /**/

    $('.short-time-limit').change(function(){
        var value = $(this).data('optionValue');
        var key = $(this).data('optionTitle');
        if(value == "0"){
            $('#timelimit-label').removeClass('unfold');
            Cart.removeOption(key);
        }else {
            Cart.addOption(key, value);
            $('#timelimit-label').addClass('unfold').html('+' + Cart.decoratePrice(value));
        }
    });

    /**/

    $('#full-description').keyup(function(){
        var chars = $(this).val().length;
        $('#indicator-desc').removeClass('low normal good');
        if(chars < $(this).data('normal')) {
            $('#indicator-desc').addClass('low');
        }else if (chars < $(this).data('high')){
            $('#indicator-desc').addClass('normal');
        }else {
            $('#indicator-desc').addClass('good');
        }
    })

    /**/

    $('#hide-check').click(function() {
        $(this).parent().removeClass('expanded');
        return false;
    })

    $('#show-check').click(function() {
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
            if ((new Date().getTime() - start) > milliseconds){
                break;
            }
        }
    }
    $('#save').click(function() {
        if((($('input[name=tos]').attr('checked') != 'checked') || ($('input[type=radio]:checked').length == 0)) && ($('input[name=tos]').length == 1)) {
            alert('Вы должны принять правила и условия');
        }else {
            if(Cart.prepareData()) {
                if(uploader.damnUploader('itemsCount') > 0) {
                    $('#loading-overlay').modal({
                        containerId: 'spinner',
                        opacity: 80,
                        close: false
                    });
                    uploader.damnUploader('startUpload')
                }else {
                    Cart.saveData();
                }
            }else {
                alert('Не все обязательные поля заполнены');
            }
        }
        return false;
    })

    /*
    $('#fileuploadform').fileupload({
        dataType: 'json',
        add: function(e, data) {
            console.log(data);
            e.data.fileupload.myData = data;
            $('#filename').html(data.files[0].name);
        },
        done: function (e, data) {
            $.modal.close();
            var li = '<li><a href="' + data.result.weburl + '" class="filezone-filename" target="_blank">' + data.result.basename + '</a>' +
                '<p>' + data.result['file-description'] + '</p>' +
                '<a href="/pitchfiles/delete/' + data.result.id + '.json" class="filezone-delete-link">удалить</a></li>';
            $("#filezone").append(li);
            $('#fileupload-description').val('');
            $('#filename').html('Файл не выбран');
            Cart.fileIds.push(data.result.id);
        },
        send: function(e, data) {
            $('#loading-overlay').modal({
                containerId: 'spinner',
                opacity: 80,
                close: false
            });
        },
        progressall: function(e, data) {
            if(data.total > 0) {
                var percent = data.total / 100;
                var completed = Math.round(data.loaded / percent);
                $('#progressbar').text(completed + '%');
            }
        }
    });*/

    var fileIds = [];
    var placeholder = $('#fileupload-description').attr('placeholder');
    var uploader = $("#fileupload").damnUploader({
        url: '/pitchfiles/add.json',
        onSelect: function(file) { onSelectHandler.call(this, file, placeholder, fileIds, Cart); }  // See app.js
    });

    $('input[name="phone-brief"]').change(function() {
        var phone = $(this).val();
        $('input[name="phone-brief"]').val(phone);
    });

    $(document).on('click', '.filezone-delete-link', function() {
        if (Cart.id) {
            var givenId = +$(this).parent().attr('data-id'); 
            if (givenId) { // File came from database
                $.post('/pitchfiles/delete', {'id': givenId}, function(response) {
                    if(response != 'true') {
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
        } else {
            uploader.damnUploader('cancel', $(this).attr('data-delete-id'));
            $(this).parent().remove();
            return false;
        }
    })

    $('#uploadButton').click(function() {
        //$('#fileuploadform').fileupload('uploadByClick');
        return false;
    });

    /*$('.sub-radio').change(function() {
        var minValue = $(this).data('minValue');

        $('#award').data('minimalAward', minValue);
        $('#award').blur();
    })*/

    $('#sub-site').keyup(function() {
        var input = $(this);
        var award = $('#award')
        var defLow = award.data('lowDef');
        var defNormal = award.data('normalDef');
        var defHigh = award.data('highDef');
        var mult = $(this).data('mult');
        if((typeof(mult) == undefined) || (!mult)) {
            mult = parseInt(award.data('lowDef')) / 2;
        }
        var extraNormal = (defNormal - defLow);
        var extraHigh = (defHigh - defLow);

        var minValue = (($(this).val() - 1) * mult) + defLow;
        $('#award').data('minimalAward', minValue);
        $('#award').blur();
    })

    $('input[name=package-type]').on('change', function() {
        var newMinValue = $(this).data('minValue');
        var award = $('#award');
        var input = $('#sub-site');
        award.data('lowDef', newMinValue);
        var defLow = award.data('lowDef');
        var defNormal = award.data('normalDef');
        var defHigh = award.data('highDef');
        var mult = $(this).data('mult');
        if((typeof(mult) == undefined) || (!mult)) {
            mult = parseInt(award.data('lowDef')) / 2;
        }
        var extraNormal = (defNormal - defLow);
        var extraHigh = (defHigh - defLow);
        var numberOfItems = input.val();
        if(numberOfItems == '') {
            numberOfItems = input.attr('placeholder');
        }
        var minValue = ((numberOfItems - 1) * mult) + defLow;
        award.data('minimalAward', minValue);
        award.blur();
    })

    $('#sub-site').numeric({ "negative" : false, "decimal": false }, function(){

    });

    $('#sub-site').change(function() {
        if(($(this).val() == '') || ($(this).val() == 0)) {
            $(this).val('1');
        }
        var award = $('#award');
        var defLow = award.data('lowDef');
        var defNormal = award.data('normalDef');
        var defHigh = award.data('highDef');
        var mult = $(this).data('mult');
        if((typeof(mult) == undefined) || (!mult)) {
            mult = parseInt(award.data('lowDef')) / 2;
        }
        var extraNormal = (defNormal - defLow);
        var extraHigh = (defHigh - defLow);

        var minValue = (($(this).val() - 1) * mult) + defLow;
        award.data('minimalAward', minValue);
        award.data('low', minValue);
        award.data('normal', minValue + extraNormal);
        award.data('high', minValue + extraHigh);
        //$('#award').data('lowDef', minValue );
        //$('#award').data('highDef', minValue + 18000);
        //$('#award').data('normalDef', minValue + 8000);
        award.blur();

    })

    $('#sub-site').focus(function(){
        $(this).removeClass('initial-price');
    })


    $('input[name=isGuaranteed]').on('change', function() {
        var radioButton = $(this);
        if(radioButton.val() == 1) {
            Cart.addOption(radioButton.data('optionTitle'), radioButton.data('optionValue'));
        }else {
            Cart.removeOption(radioButton.data('optionTitle'));
        }
    })

    var count = $('.sub-check:checked').length;
    if(count == 1) {
        $('.sub-check:checked').attr('disabled', 'disabled');
    }

    $('.sub-check').change(function() {
        var price = $('#copybaseminprice').val();
        var count = $('.sub-check:checked').length;
        if(count < 2) {
            $('.sub-check:checked').attr('disabled', 'disabled');
        }else {
            $('.sub-check:checked').removeAttr('disabled');
        }
        switch (count) {
            case 1: var mod = 1; break;
            case 2: var mod = 1.5; break;
            case 3: var mod = 1.75; break;
        }
        var input =  $('#award');
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

    $('#phonebrief').change(function(){
        if($(this).attr('checked') == 'checked') {
            Cart.validatetype = 2;
        }else {
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


    $('.rb1').change(function() {
        if($(this).data('pay') == 'online') {
            $("#paybutton").removeAttr('style');
            $('#s3_kv').hide();
        }else {
            $("#paybutton").css('background', '#a2b2bb');
            $('#s3_kv').show();
        }
    });

    $('.rb-face', '#s3_kv').change(function() {
    if ($(this).data('pay') == 'offline-fiz') {
        $('.pay-fiz').show();
        $('.pay-yur').hide();
    } else {
        $('.pay-fiz').hide();
        $('.pay-yur').show();
    }
    });

    $('#bill-fiz').submit(function(e) {
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
            }, function(result) {
                if (result.error == false) {
                    window.location = '/pitches/getpdf/godesigner-pitch-' + $('#fiz-id').val() + '.pdf';
                }
            });
        }
    });

    $('#bill-yur').submit(function(e) {
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
            }, function(result) {
                if (result.error == false) {
                    window.location = '/pitches/getpdf/godesigner-pitch-' + $('#yur-id').val() + '.pdf';
                }
            });
        }
    });

    /**/
    var Cart = new FeatureCart;
    Cart.init();
    if ((typeof(fillBrief) != 'undefined') && (fillBrief)) {
        $('#phonebrief').click();
    }

    if($('#sub-site').val() > 1) {
        var award = $('#award');
        var defLow = award.data('lowDef');
        var defNormal = award.data('normalDef');
        var defHigh = award.data('highDef');
        var mult = $('#sub-site').data('mult');
        if((typeof(mult) == undefined) || (!mult)) {
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
});

function checkRequired(form) {
    var required = false;
    $.each($('[required]', form), function(index, object) {
        if (($(this).val() == $(this).data('placeholder')) || ($(this).val().length == 0)) {
            $(this).addClass('wrong-input');
            required = true;
        }
    });
    return required;
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
    this.transferFee = 0.145;
    this.transferFeeDiscount = 0;
    this.transferFeeKey = 'Сбор GoDesigner';
    this.transferFeeFlag = 0;
    this.mode = 'add';
    this.init = function() {
        if(($('#pitch_id').length > 0) && (typeof($('#pitch_id').val()) != 'undefined')) {
            self.id = $('#pitch_id').val();
            this.mode = 'edit';
        }
        var initVal = $('#award').val();
        if($('#award').val() == '') {
            initVal = $('#award').attr('value');
        }
        if(self.id > 0) {
            self.addOption('Награда Дизайнеру', initVal);
        }else {
            //self.updateOption('Заполнение брифа', 0);
        }
        if($('#discount').length > 0) {
            self.transferFeeDiscount = 700;
        }
        if(self.mode == 'edit') {
            if(window.location.hash != '#step3') {
                $.get('/receipts/view/' + self.id + '.json', function(response) {
                    self.fillCheck(response);
                    self._renderCheck();
                });
            }
        }else {
            self._renderCheck();
        }
        if($('#phonebrief').attr('checked') == 'checked') {
            self.validatetype = 2;
        }
        $.each($('li', '#filezone'), function(index, object) {
            self.fileIds.push($(object).data('id'));
        })

        if(window.location.hash == '#step3') {
            $.get('/receipts/view/' + self.id + '.json', function(response) {
                self.fillCheck(response);
                self._renderCheck();
                if(self.prepareData()) {
                    self.saveData();
                }
            });
        }
    };
    this.fillCheck = function(data) {
        self.content = {};
        $.each(data, function(index, object) {
            if(object.name == 'Экспертное мнение') {
                $('#expert-label').html('+' + object.value + '.-');
            }
            if((object.value != 0)) {
                self.updateOption(object.name, parseInt(object.value));
            }else if((object.name == 'Заполнение брифа')) {
                //self.updateOption(object.name, parseInt(object.value));
            }
        })
    };
    this.addOption = function(key, value) {
        self.content[key] = value;
        self.updateFees();
        self._renderCheck();
    };
    this.updateOption = function(key, value) {
        if(typeof(self.content[key]) != "undefined"){
            self.content[key] = value;
            self.updateFees();
            self._renderCheck();
        }else {
            self.addOption(key, value);
            self.updateFees();
            self._renderCheck();
        }
    };
    this.updateFees = function() {
        var total = self._calculateOptionsWithoutFee();
        var award = self.getOption(self.awardKey);
        var commision = Math.round(award * this.transferFee) - this.transferFeeDiscount;
        if(commision < 0) {
            commision = 0;
        }
        self.content[self.transferFeeKey] = commision;
    }
    this.getOption = function(key) {
        if(typeof(self.content[key]) != "undefined"){
            return parseInt(self.content[key]);
        }else {
            return 0;
        }
    };
    this.getTimelimit = function() {
        return $('.short-time-limit:checked').data('optionPeriod');
    };
    this.removeOption = function(key) {
        delete self.content[key];
        self.updateFees();
        self._renderCheck();
    };
    this.prepareData = function() {
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
            'industry': $('input[name=industry]').val(),
            'business-description': $('textarea[name=business-description]').val(),
            'description': $('textarea[name=description]').val(),
            'fileFormats': self._formatArray(),
            'fileFormatDesc': $('textarea[name=format-description]').val(),
            'filesId': self.fileIds,
            'phone-brief': $('input[name=phone-brief]').val(),
            'materials': $('input[name=materials]:checked').val(),
            'materials-limit': $('input[name=materials-limit]').val(),
            'promocode': $('#promocode').val()
        };
        self.data = {
            "features": features,
            "commonPitchData" : commonPitchData,
            "specificPitchData" : self.getSpecificData()
        };
        return self.validateData();
    }
    this.validateData = function() {
        var result = true;
        if(self.validatetype == 1) {
            if((self.data.commonPitchData.title == '') || ($('input[name=title]').attr('placeholder') == self.data.commonPitchData.title)) {
                result = false;
            }
            if((self.data.commonPitchData.industry == '') || ($('input[name=industry]').attr('placeholder') == self.data.commonPitchData.industry)) {
                result = false;
            }
            if(self.data.commonPitchData.fileFormats.length == 0) {
                result = false;
            }
        }
        return result;
    }
    this.saveData = function() {
        if(self.data.features.award == 0) {
            alert('Укажите награду для дизайнера!');
        }else{
            $.post('/pitches/add.json', self.data, function(response) {
                if(response == 'redirect') {
                    window.location = '/users/registration';
                }
                if(response == 'noaward') {
                    alert('Укажите награду для дизайнера!');
                    return false;
                }
                self.id = response;
                $('#pitch-id').val(self.id);
                
                pitchid = self.id;

                $.get('/transactions/getsigndata/' + self.id + '.json', function(response) {
                    $('.middle').not('#step3').hide();
                    $('#step3').show();
                    $('#order-id').val(response.id);
                    $('#order-total').val(response.total);
                    $('#order-timestamp').val(response.timestamp);
                    $('#order-sign').val(response.sign);
                    $('#pdf-link').attr('href', '/pitches/getpdf/godesigner-pitch-' + self.id + '.pdf');
                })
            });
        }
    }
    
    this.saveFileIds = function() {
            $.post('/pitches/updatefiles.json', {"fileids": self.fileIds, "id": self.id}, function() {

            });
    }
    
    this.getSpecificData = function() {
        var specificPitchData = {};
        $('input.specific-prop, textarea.specific-prop').each(function(index, object){
            specificPitchData[$(object).attr('name')] = $(object).val()
        });
        $('input.specific-group:checked').each(function(index, object){
            specificPitchData[$(object).attr('name')] = $(object).val()
        });
        if($('.slider').length > 0) {
            specificPitchData[$('.sliderul').data('name')] = self._logoProperites();
        }
        if($('.look-variants').length) {
            specificPitchData["logoType"] = self._logoTypeArray()
        }
        return specificPitchData;
    }
    this.decoratePrice = function(price) {
        price += '.-';
        return price;
    }
    this._logoProperites = function() {
        var array = new Array();
        $.each($('.slider'), function(i, object) {
            array.push($(object).slider('value'));
        })
        return array;
    }
    this._logoTypeArray = function() {
        var array = new Array();
        var checkedExperts = $('input:checked', '.look-variants');
        $.each(checkedExperts, function(index, object) {
            array.push($(object).data('id'));
        })
        return array;
    }
    this._formatArray = function() {
        var array = new Array();
        var checkedExperts = $('input:checked', '.extensions');
        $.each(checkedExperts, function(index, object) {
            array.push($(object).data('value'));
        })
        return array;
    }
    this._expertArray = function() {
        var array = new Array();
        var checkedExperts = $('input:checked', '.experts');
        $.each(checkedExperts, function(index, object) {
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
    this._renderCheck = function() {
        self._renderTotal();
        self._renderOptions();
    };
    this._renderTotal = function() {
        var priceTag = self._calculateTotal() + '.-';
        self.priceTag.html(priceTag);
    };
    this._renderOptions = function() {
        var html = '';
        $.each(self.content, function(key, value) {
            html += '<li><span>' + key +'</span><small>' + value + '.-</small></li>';
        });
        self.container.html(html);
    };
    this._calculateOptions = function() {
        var optionsTotal = 0;
        $.each(self.content, function(key, value) {
            if(key != self.transferFeeKey) {
                optionsTotal += parseInt(value);
            }
        });
        if(typeof(self.content[self.transferFeeKey]) != 'undefined') {
            optionsTotal += self.content[self.transferFeeKey];
        }
        //
        //optionsTotal
        return optionsTotal;
    };
    this._calculateOptionsWithoutFee = function() {
        var optionsTotal = 0;
        $.each(self.content, function(key, value) {
            if(key != self.transferFeeKey) {
                optionsTotal += parseInt(value);
            }
        });
        //
        return optionsTotal;
    };
    this._calculateTotal = function() {
        self.total = self._calculateOptions();
        return self.total;
    };

};
