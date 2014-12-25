$(document).ready(function () {

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
            console.log(o.content);
            if ((jQuery(o.content).text() == '') && (o.content != '')) {
                var text = o.content
            } else {
                //var text = jQuery(o.content).html()
            }
            //o.content = text
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

                    var doc = ed.getDoc(),
                            dom = ed.dom,
                            el = doc.content_editable ? ed.getBody() : (tinymce.isGecko ? doc : ed.getWin());

                    tinymce.dom.Event.add(el, 'blur', function (e) {
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
    var chars = $('#full-description').val().length;
    $('#indicator-desc').removeClass('low normal good');
    if (chars < 1000) {
        $('#indicator-desc').addClass('low');
    } else if (chars < 2500) {
        $('#indicator-desc').addClass('normal');
    } else {
        $('#indicator-desc').addClass('good');
    }
    $('#full-description').keyup(function () {
        var chars = $(this).val().length;
        $('#indicator-desc').removeClass('low normal good');
        if (chars < 1000) {
            $('#indicator-desc').addClass('low');
        } else if (chars < 2500) {
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

    /**/
    $(document).on('focus', '.wrong-input', function () {
        $(this).removeClass('wrong-input');
    });
    $('input', '.extensions').change(function () {
        $('.extensions').removeClass('wrong-input');
    });

    $('input', '#list-job-type').change(function () {
        $('#list-job-type').removeClass('wrong-input');
    });

    $('#save').click(function () {
        if ((($('input[name=tos]').attr('checked') != 'checked') || ($('input[type=radio]:checked').length == 0)) && ($('input[name=tos]').length == 1)) {
            alert('Вы должны принять правила и условия');
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
                    Cart.saveData();
                }
            } else {
                $.scrollTo($('.wrong-input').parent(), {duration: 600});
            }
        }
        return false;
    });

    var fileIds = [];
    var uploader = $("#fileupload").damnUploader({
        url: '/pitchfiles/add.json',
        onSelect: function (file) {
            onSelectHandler.call(this, file, fileIds, Cart);
        } // See app.js
    });

    $('input[name="phone-brief"]').change(function () {
        var phone = $(this).val();
        $('input[name="phone-brief"]').val(phone);
    });

    $(document).on('click', '.filezone-delete-link', function () {
        if (Cart.id) {
            var givenId = +$(this).parent().attr('data-id');
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
        } else {
            uploader.damnUploader('cancel', $(this).attr('data-delete-id'));
            $(this).parent().remove();
            return false;
        }
    })

    $('#uploadButton').click(function () {

        $('#fileuploadform').fileupload('uploadByClick');
        return false;
    });

    $('.sub-radio').change(function () {
        var minValue = $(this).data('minValue');
        $('#award').data('minimalAward', minValue);
        $('#award').blur();
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


    $('.rb1').change(function () {
        if ($(this).data('pay') == 'online') {
            $("#paybutton").removeAttr('style');
            $('#s3_kv').hide();
        } else {
            $("#paybutton").css('background', '#a2b2bb');
            $('#s3_kv').show();
        }
    });

    /**/
    Cart = new FeatureCart;
    Cart.init();

});
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
    this.transferFeeKey = 'Сбор GoDesigner';
    this.transferFeeFlag = 0;
    this.mode = 'add';
    this.init = function () {
        if (typeof ($('#pitch_id').val()) != 'undefined') {
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
            self.updateOption('Заполнение брифа', 0);
        }
        if (self.mode == 'edit') {
            $.get('/receipts/view/' + self.id + '.json', function (response) {
                self.fillCheck(response);
                self._renderCheck();
            });
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
            if (self.prepareData()) {
                self.saveData();
            }
        }
    };
    this.fillCheck = function (data) {
        self.content = {};
        $.each(data, function (index, object) {
            var feeOption = object.name.indexOf(self.transferFeeKey);
            if (feeOption != -1) {
                var percent = object.name.substr(self.transferFeeKey.length + 1, 4);
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
                self.updateOption(object.name, parseInt(object.value));
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
        self.content[self.transferFeeKey] = Math.round(award * this.transferFee);
    }
    this.getOption = function (key) {
        if (typeof (self.content[key]) != "undefined") {
            return parseInt(self.content[key]);
        } else {
            return 0;
        }
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
            'timelimit': self.getOption('Поджимают сроки'),
            'brief': self.getOption('Заполнение брифа')
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
            'materials-limit': $('input[name=materials-limit]').val()
        };
        self.data = {
            "features": features,
            "commonPitchData": commonPitchData,
            "specificPitchData": self.getSpecificData()
        };
        return self.validateData();
    }
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
    }
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
                window.location = '/pitches/details/' + self.id;
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
        $('input.specific-group[data-selected~="true"]').each(function (index, object) {
            specificPitchData[$(object).attr('name')] = $(object).val()
        });
        if ($('.slider').length > 0) {
            specificPitchData[$('.sliderul').data('name')] = self._logoProperites();
        }
        if ($('.look-variants').length) {
            specificPitchData["logoType"] = self._logoTypeArray()
        }
        return specificPitchData;
    },
            this.decoratePrice = function (price) {
                price += '.-';
                return price;
            },
            this._logoProperites = function () {
                var array = new Array();
                $.each($('.slider'), function (i, object) {
                    array.push($(object).slider('value'));
                })
                return array;
            },
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
                })
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
        return self.total;
    };

}
;