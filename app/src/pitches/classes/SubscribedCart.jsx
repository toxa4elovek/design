class AdvancedCart {
    data = {};
    filesId = null;
    projectId = null;
    receiptAccessor = null;
    constructor() {
        this.receiptAccessor = new ReceiptAccessor();
    }
    getOption(key) {
        return this.receiptAccessor.get(payload.receipt, key);
    }
    prepareData() {
        const features = {
            'award': this.getOption('Награда Дизайнеру'),
            'private': this.getOption('Скрыть проект'),
            'social': this.getOption('Рекламный Кейс'),
            'experts': this._expertArray(),
            'pinned': this.getOption('«Прокачать» проект'),
            'brief': this.getOption('Заполнение брифа')
        };
        const commonPitchData = {
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
        const specificData = this.__getSpecificData();
        this.data = {
            "receipt": payload.receipt,
            "features": features,
            "commonPitchData": commonPitchData,
            "specificPitchData": specificData
        };
        return this.validateData();
    }
    validateData() {
        var result = true;
        if (this.validatetype == 1) {
            const projectTitleInput = $('input[name=title]');
            if ((this.data.commonPitchData.title == '') || (projectTitleInput.data('placeholder') == this.data.commonPitchData.title)) {
                projectTitleInput.addClass('wrong-input');
                result = false;
            }
        }
        return result;
    }
    saveData (simplesave) {
        if (this.data.features.award == 0) {
            alert('Укажите награду для дизайнера!');
        } else {
            $.post('/pitches/add_subscribed.json', this.data, function (response) {
                this.id = response;
                if(typeof(simplesave) == 'undefined') {
                    if (response == 'redirect') {
                        window.location = '/users/registration';
                    }
                    if (response == 'noaward') {
                        alert('Укажите награду для дизайнера!');
                        return false;
                    }

                }else {
                    const viewUrl = `http://www.godesigner.ru/pitches/view/${this.id}`;
                    const editUrl = `http://www.godesigner.ru/pitches/edit/${this.id}`;
                    const deleteUrl = `http://www.godesigner.ru/pitches/delete/${this.id}`;
                    const title = this.data.commonPitchData.title;
                    const award = this._priceDecorator(this.data.features.award);
                    const pitchPanelSelector = '#pitch-panel';
                    const pitchPanel = $(pitchPanelSelector);
                    let scroll = false;
                    let evenClass = '';
                    if((pitchPanel.length == 1) && ($('tr', '#pitch-panel').length % 2 == 0)) {
                        evenClass = 'even';
                    }
                    const row =
                        `<tr data-id="${this.id}" class="selection ${evenClass} coda">
                            <td></td>
                            <td class="pitches-name mypitches">
                                <a href="${viewUrl}">${title}</a>
                            </td>
                            <td class="pitches-status mypitches">
                                <a href="${editUrl}">Ожидание оплаты</a>
                            </td>
                            <td class="price mypitches">${award}.-</td>
                            <td class="pitches-edit mypitches">
                                <!--a href="${editUrl}#step3" class="mypitch_pay_link buy" title="оплатить">оплатить</a-->
                                <a href="${editUrl}" class="edit mypitch_edit_link" title="редактировать">редактировать</a>
                                <a data-id="${this.id}" href="${deleteUrl}" class="delete deleteheader mypitch_delete_link" title="удалить">удалить</a>
                            </td>
                        </tr>`;
                    if((pitchPanel.length == 1) && ($('tr[data-id="' + this.id + '"]', '#pitch-panel').length == 0)) {
                        $('#header-table').append(row);
                        scroll = true;
                    }else if ($('tr[data-id="' + this.id + '"]', '#pitch-panel').length == 0) {
                        const panel =
                            `<div id="pitch-panel">
                                <div class="conteiner">
                                    <div class="content">
                                        <table class="all-pitches" id="header-table">
                                            <tbody>
                                                ${row}
                                            </tbody>
                                        </table>
                                        <p class="pitch-buttons-legend">
                                            <a href="http://www.godesigner.ru/answers/view/73">
                                                <i id="help"></i>Как мотивировать дизайнеров
                                            </a>
                                        </p>
                                    </div>
                                </div>
                            </div>`;
                        $('.wrapper').first().prepend(panel);
                        scroll = true;
                    }
                    if(scroll) {
                        $.scrollTo($(pitchPanelSelector), {duration: 600});
                    }
                }
            }.bind(this));
        }
    }
    __getSpecificData() {
        let specificPitchData = {};
        $('input.specific-prop, textarea.specific-prop').each(function (index, object) {
            specificPitchData[$(object).attr('name')] = $(object).val()
        });
        $('input.specific-group:checked').each(function (index, object) {
            specificPitchData[$(object).attr('name')] = $(object).val()
        });
        if ($('.slider').length > 0) {
            specificPitchData[$('.sliderul').data('name')] = this._logoProperites();
        }
        if ($('.look-variants').length) {
            specificPitchData["logoType"] = this._logoTypeArray();
        }
        return specificPitchData;
    }
    _logoProperites() {
        let array = [];
        $.each($('.slider'), function (i, object) {
            array.push($(object).slider('value'));
        });
        return array;
    }
    _logoTypeArray() {
        let array = [];
        const checkedExperts = $('input:checked', '.look-variants');
        $.each(checkedExperts, function (index, object) {
            array.push($(object).data('id'));
        });
        return array;
    }
    _formatArray() {
        let array = [];
        const checkedExperts = $('input:checked', '.extensions');
        $.each(checkedExperts, function (index, object) {
            array.push($(object).data('value'));
        });
        return array;
    }
    _jobArray() {
        let array = [];
        const checkedJob = $('input:checked', '#list-job-type');
        $.each(checkedJob, function (index, object) {
            array.push($(object).val());
        });
        return array;
    }
    _expertArray() {
        let array = [];
        const checkedExperts = $('input:checked', '.experts');
        $.each(checkedExperts, function (index, object) {
            array.push($(object).data('id'));
        });
        return array;
    };
    _priceDecorator(inputString) {
        let price = inputString.toString();
        price = price.replace(/(.*)\.00/g, "$1");
        let counter = 1;
        while(price.match(/\w\w\w\w/)) {
            price = price.replace(/^(\w*)(\w\w\w)(\W.*)?$/, "$1 $2$3");
            counter ++;
            if(counter > 6) break;
        }
        return price;
    }
}