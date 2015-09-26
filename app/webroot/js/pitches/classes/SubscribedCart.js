'use strict';

var _createClass = (function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ('value' in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; })();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError('Cannot call a class as a function'); } }

var AdvancedCart = (function () {
    function AdvancedCart() {
        _classCallCheck(this, AdvancedCart);

        this.data = {};
        this.filesId = null;
        this.projectId = null;
        this.receiptAccessor = null;

        this.receiptAccessor = new ReceiptAccessor();
    }

    _createClass(AdvancedCart, [{
        key: 'getOption',
        value: function getOption(key) {
            return this.receiptAccessor.get(payload.receipt, key);
        }
    }, {
        key: 'prepareData',
        value: function prepareData() {
            var features = {
                'award': this.getOption('Награда Дизайнеру'),
                'private': this.getOption('Скрыть проект'),
                'social': this.getOption('Рекламный Кейс'),
                'experts': this._expertArray(),
                'pinned': this.getOption('«Прокачать» проект'),
                'brief': this.getOption('Заполнение брифа')
            };
            var commonPitchData = {
                'id': this.projectId,
                'title': $('input[name=title]').val(),
                'category_id': $('input[name=category_id]').val(),
                'industry': $('input[name=industry]').val() || '',
                'jobTypes': this._jobArray(),
                'business-description': $('textarea[name=business-description]').val(),
                'description': tinyMCE.activeEditor.getContent(),
                'fileFormats': this._formatArray(),
                'fileFormatDesc': $('textarea[name=format-description]').val(),
                'filesId': this.fileIds,
                'phone-brief': $('input[name=phone-brief]').val(),
                'materials': $('input[name=materials]:checked').val(),
                'materials-limit': $('input[name=materials-limit]').val()
            };
            var specificData = this.__getSpecificData();
            this.data = {
                "receipt": payload.receipt,
                "features": features,
                "commonPitchData": commonPitchData,
                "specificPitchData": specificData
            };
            return this.validateData();
        }
    }, {
        key: 'validateData',
        value: function validateData() {
            var result = true;
            if (this.validatetype == 1) {
                var projectTitleInput = $('input[name=title]');
                if (this.data.commonPitchData.title == '' || projectTitleInput.data('placeholder') == this.data.commonPitchData.title) {
                    projectTitleInput.addClass('wrong-input');
                    result = false;
                }
            }
            return result;
        }
    }, {
        key: 'saveData',
        value: function saveData(simplesave) {
            if (this.data.features.award == 0) {
                alert('Укажите награду для дизайнера!');
            } else {
                $.post('/pitches/add_subscribed.json', this.data, (function (response) {
                    this.id = response;
                    if (typeof simplesave == 'undefined') {
                        if (response == 'redirect') {
                            window.location = '/users/registration';
                        }
                        if (response == 'noaward') {
                            alert('Укажите награду для дизайнера!');
                            return false;
                        }
                    } else {
                        var viewUrl = 'http://www.godesigner.ru/pitches/view/' + this.id;
                        var editUrl = 'http://www.godesigner.ru/pitches/edit/' + this.id;
                        var deleteUrl = 'http://www.godesigner.ru/pitches/delete/' + this.id;
                        var title = this.data.commonPitchData.title;
                        var award = this._priceDecorator(this.data.features.award);
                        var pitchPanelSelector = '#pitch-panel';
                        var pitchPanel = $(pitchPanelSelector);
                        var _scroll = false;
                        var evenClass = '';
                        if (pitchPanel.length == 1 && $('tr', '#pitch-panel').length % 2 == 0) {
                            evenClass = 'even';
                        }
                        var row = '<tr data-id="' + this.id + '" class="selection ' + evenClass + ' coda">\n                            <td></td>\n                            <td class="pitches-name mypitches">\n                                <a href="' + viewUrl + '">' + title + '</a>\n                            </td>\n                            <td class="pitches-status mypitches">\n                                <a href="' + editUrl + '">Ожидание оплаты</a>\n                            </td>\n                            <td class="price mypitches">' + award + '.-</td>\n                            <td class="pitches-edit mypitches">\n                                <!--a href="' + editUrl + '#step3" class="mypitch_pay_link buy" title="оплатить">оплатить</a-->\n                                <a href="' + editUrl + '" class="edit mypitch_edit_link" title="редактировать">редактировать</a>\n                                <a data-id="' + this.id + '" href="' + deleteUrl + '" class="delete deleteheader mypitch_delete_link" title="удалить">удалить</a>\n                            </td>\n                        </tr>';
                        if (pitchPanel.length == 1 && $('tr[data-id="' + this.id + '"]', '#pitch-panel').length == 0) {
                            $('#header-table').append(row);
                            _scroll = true;
                        } else if ($('tr[data-id="' + this.id + '"]', '#pitch-panel').length == 0) {
                            var panel = '<div id="pitch-panel">\n                                <div class="conteiner">\n                                    <div class="content">\n                                        <table class="all-pitches" id="header-table">\n                                            <tbody>\n                                                ' + row + '\n                                            </tbody>\n                                        </table>\n                                        <p class="pitch-buttons-legend">\n                                            <a href="http://www.godesigner.ru/answers/view/73">\n                                                <i id="help"></i>Как мотивировать дизайнеров\n                                            </a>\n                                        </p>\n                                    </div>\n                                </div>\n                            </div>';
                            $('.wrapper').first().prepend(panel);
                            _scroll = true;
                        }
                        if (_scroll) {
                            $.scrollTo($(pitchPanelSelector), { duration: 600 });
                        }
                    }
                }).bind(this));
            }
        }
    }, {
        key: '__getSpecificData',
        value: function __getSpecificData() {
            var specificPitchData = {};
            $('input.specific-prop, textarea.specific-prop').each(function (index, object) {
                specificPitchData[$(object).attr('name')] = $(object).val();
            });
            $('input.specific-group:checked').each(function (index, object) {
                specificPitchData[$(object).attr('name')] = $(object).val();
            });
            if ($('.slider').length > 0) {
                specificPitchData[$('.sliderul').data('name')] = this._logoProperites();
            }
            if ($('.look-variants').length) {
                specificPitchData["logoType"] = this._logoTypeArray();
            }
            return specificPitchData;
        }
    }, {
        key: '_logoProperites',
        value: function _logoProperites() {
            var array = [];
            $.each($('.slider'), function (i, object) {
                array.push($(object).slider('value'));
            });
            return array;
        }
    }, {
        key: '_logoTypeArray',
        value: function _logoTypeArray() {
            var array = [];
            var checkedExperts = $('input:checked', '.look-variants');
            $.each(checkedExperts, function (index, object) {
                array.push($(object).data('id'));
            });
            return array;
        }
    }, {
        key: '_formatArray',
        value: function _formatArray() {
            var array = [];
            var checkedExperts = $('input:checked', '.extensions');
            $.each(checkedExperts, function (index, object) {
                array.push($(object).data('value'));
            });
            return array;
        }
    }, {
        key: '_jobArray',
        value: function _jobArray() {
            var array = [];
            var checkedJob = $('input:checked', '#list-job-type');
            $.each(checkedJob, function (index, object) {
                array.push($(object).val());
            });
            return array;
        }
    }, {
        key: '_expertArray',
        value: function _expertArray() {
            var array = [];
            var checkedExperts = $('input:checked', '.experts');
            $.each(checkedExperts, function (index, object) {
                array.push($(object).data('id'));
            });
            return array;
        }
    }, {
        key: '_priceDecorator',
        value: function _priceDecorator(inputString) {
            var price = inputString.toString();
            price = price.replace(/(.*)\.00/g, "$1");
            var counter = 1;
            while (price.match(/\w\w\w\w/)) {
                price = price.replace(/^(\w*)(\w\w\w)(\W.*)?$/, "$1 $2$3");
                counter++;
                if (counter > 6) break;
            }
            return price;
        }
    }]);

    return AdvancedCart;
})();