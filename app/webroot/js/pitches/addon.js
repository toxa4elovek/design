$(document).ready(function() {


    var pitchid = '';


    $('.steps-link').click(function() {
        var stepNum = $(this).data('step');
        if(Cart.validatetype == 1) {
            if(stepNum == 3) {
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
                if(stepNum == 3) {
                    if(Cart.prepareData()) {
                        Cart.saveData();
                    }else {
                        alert('Не все обязательные поля заполнены');
                    }
                    return false;
                }
            }

        }

        $('.middle').not('#step' + stepNum).hide();
        $('#step' + stepNum).show();
        return false;
    })

    $('#prolong-checkbox').click(function() {
        if($('#prolong-checkbox').attr('checked')) {
            $('#sub-prolong').show();
        }else {
            $('#sub-prolong').hide();
        }
    })


    $('#sub-prolong').numeric({ "negative" : false, "decimal": false }, function(){

    });

    $('#sub-prolong').change(function() {
        if(($(this).val() == '') || ($(this).val() == 0)) {
            $(this).val('1');
        }
        $(this).css('color', '#4F5159');
        newValue = $(this).val() * 1950;
        $('#prolong-label').html('+' + newValue + '.-');
        Cart.updateOption($(this).data('optionTitle'), newValue);
    })

    $('#sub-prolong').keyup(function() {
        $(this).css('color', '#4F5159');
        newValue = $(this).val() * 1950;
        $('#prolong-label').html('+' + newValue + '.-');
        Cart.updateOption($(this).data('optionTitle'), newValue);
    })

    $('#sub-prolong').focus(function(){
        $(this).removeClass('initial-price');
    })

    // simple options
    $('.single-check').change(function() {
        $(this).parent().parent().parent().children('.label').toggleClass('unfold');

        if($(this).is(':checked')) {
            Cart.addOption($(this).data('optionTitle'), $(this).data('optionValue'));

        }else {
            if($(this).attr('id') == 'prolong-checkbox') {
                $(this).val(1)
                $(this).css('color', '');
                $(this).addClass('initial-price');
            }
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
            var newValue = Cart.getOption($(this).data('optionTitle')) - $(this).data('optionValue');
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

    $('#hide-check').click(function() {
        $(this).parent().removeClass('expanded');
        return false;
    })

    $('#show-check').click(function() {
        $(this).parent().addClass('expanded');
        return false;
    })

    $('.rb1').change(function() {
        switch ($(this).data('pay')) {
            case 'paymaster':
                $("#paybutton-paymaster").removeAttr('style');
                $("#paybutton-online").css('background', '#a2b2bb');
                $("#paymaster-images").hide();
                $("#paymaster-select").show();
                $('#s3_kv').hide();
                break;
            case 'offline':
                $("#paybutton-online").css('background', '#a2b2bb');
                $("#paybutton-paymaster").css('background', '#a2b2bb');
                $("#paymaster-images").show();
                $("#paymaster-select").hide();
                $('#s3_kv').show();
                break;
        }
    });

    var Cart = new FeatureCart;
    Cart.init();

})


function FeatureCart() {
    var self = this;
    this.id = 0;
    this.addonid = 0;
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
    this.transferFeeKey = 'Сбор GoDesigner';
    this.transferFeeFlag = 0;
    this.mode = 'add';
    this.init = function() {
        if(typeof($('#pitch_id').val()) != 'undefined') {
            self.id = $('#pitch_id').val();
            this.mode = 'edit';
        }
        var initVal = $('#award').val();
        if($('#award').val() == '') {
            initVal = $('#award').attr('value');
        }
        if(self.id > 0) {
            //self.addOption('Награда Дизайнеру', initVal);
        }else {
            //self.updateOption('Заполнение брифа', 0);
        }
        if(self.mode == 'edit') {
            if(window.location.hash != '#step3') {
                /*$.get('/receipts/view/' + self.id + '.json', function(response) {
                    self.fillCheck(response, true);
                    self._renderCheck(true);
                    if($('#phonebrief').attr('checked')) {
                        self.addOption('Заполнение брифа', 1750)
                    }
                    if($('#prolong-checkbox').attr('checked')) {
                        self.addOption('продлить срок', 1950)
                    }
                    if($('#experts-checkbox').attr('checked')) {
                        self.addOption('экспертное мнение', 1000)
                    }
                });
                */
                if($('#phonebrief').attr('checked')) {
                    self.addOption('Заполнение брифа', 1750)
                }
                if($('#prolong-checkbox').attr('checked')) {
                    self.addOption('продлить срок', 1950)
                }
                if($('#experts-checkbox').attr('checked')) {
                    self.addOption('экспертное мнение', 1000)
                }
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
    this.fillCheck = function(data, original) {
        self.content = {};
        var orig = original;
        $.each(data, function(index, object) {
            if(object.name == 'Экспертное мнение') {
                $('#expert-label').html('+' + object.value + '.-');
            }
            if((object.value != 0)) {
                self.updateOption(object.name, parseInt(object.value), orig);
            }else if((object.name == 'Заполнение брифа')) {
                self.updateOption(object.name, parseInt(object.value), orig);
            }
        })
    };
    this.addOption = function(key, value) {
        self.content[key] = value;
        self.updateFees();
        self._renderCheck();
    };
    this.updateOption = function(key, value, original) {
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
        //self.content[self.transferFeeKey] = Math.round(award * this.transferFee);
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
            'experts': self._expertArray(),
            'prolong': self._getProlong(),
            'brief': self.getOption('Заполнение брифа')
        };
        var commonPitchData = {
            'id': self.id,
            'addonid': self.addonid,
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
        }
        return result;
    }
    this.saveData = function() {
        if(self.data.features.award == 0) {
            alert('Укажите награду для дизайнера!');
        }else{
            $.post('/addons/add.json', self.data, function(response) {
                self.addonid = response


                $('#addon-id').val(response);

                $.get('/transactions/getaddondata/' + response + '.json', function(response) {
                    $('.middle').not('#step3').hide();
                    $('#step3').show();

                    $('input[name=LMI_PAYMENT_AMOUNT]').val(response.total);
                    if ($('input[name=LMI_PAYMENT_NO]').length > 0) {
                        $('input[name=LMI_PAYMENT_NO]').val(response.id);
                    } else {
                        $('div', '#pmwidgetForm').append('<input type="hidden" name="LMI_PAYMENT_NO" value="' + response.id + '">');
                    }
                    $('.pmamount').html('<strong>Сумма:&nbsp;</strong> ' + response.total + '&nbsp;RUB');
                    $('.pmwidget').addClass('mod');
                    $('h1.pmheader', '.pmwidget').addClass('mod');


                    $('#order-id').val(response.id);
                    $('#order-total').val(response.total);
                    $('#order-timestamp').val(response.timestamp);
                    $('#order-sign').val(response.sign);
                    $('#pdf-link').attr('href', '/addons/getpdf/godesigner-pitch-' + self.addonid + '.pdf');
                })


            });
        }
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
    this._getProlong = function() {
        return $('#sub-prolong').val();
    }
    /*this._getTimelimitDays = function() {
     if(self.getOption('Поджимают сроки') > 0) {
     return $('.short-time-limit').data('optionPeriod');
     }else {
     return 0;
     }
     }*/
    this._renderCheck = function(grayed) {
        self._renderTotal();
        self._renderOptions(grayed);
    };
    this._renderTotal = function() {
        var priceTag = self._calculateTotal() + '.-';
        self.priceTag.html(priceTag);
    };
    this._renderOptions = function(grayed) {
        var html = '';
        $.each(self.content, function(key, value) {
            if(grayed == true) {
                html += '<li><span style="color: #c1c1c1">' + key +'</span><small style="color: #c1c1c1">' + value + '.-</small></li>';
            }else {
                html += '<li><span>' + key +'</span><small>' + value + '.-</small></li>';
            }
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
            //optionsTotal += self.content[self.transferFeeKey];
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

