class AdvancedCart {
    constructor() {
        this.receiptAccessor = new ReceiptAccessor();
        this.projectId = projectId;
        this.fileIds = [];
        this.data = {};
        $.each($('li', '#filezone'), function (index, object) {
            this.fileIds.push($(object).data('id'));
        }.bind(this));
    }
    getOption(key) {
        return this.receiptAccessor.get(payload.receipt, key);
    }
    isOptionExists(key) {
        return this.receiptAccessor.exists(payload.receipt, key);
    }
    prepareData(finishDatePicker, chooseWinnerDatePicker) {
        let features = {};
        if(this.isOptionExists('Награда Дизайнеру')) {
            features['award'] = this.getOption('Награда Дизайнеру');
        }
        if(this.isOptionExists('Скрыть проект')) {
            features['private'] = 1;
        }
        if(this.isOptionExists('Рекламный Кейс')) {
            features['social'] = this.getOption('Рекламный Кейс');
        }
        features['experts'] = this._expertArray();
        if(this.isOptionExists('«Прокачать» проект')) {
            features['pinned'] = 1;
        }
        if(this.isOptionExists('Заполнение брифа')) {
            features['brief'] = 1;
        }
        const finishDateMoment = finishDatePicker.data("DateTimePicker").date();
        const chooseWinnerFinishDateMoment = chooseWinnerDatePicker.data("DateTimePicker").date();
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
            'fileIds': this.fileIds,
            'phone-brief': $('input[name=phone-brief]').val(),
            'materials': $('input[name=materials]:checked').val(),
            'materials-limit': $('input[name=materials-limit]').val(),
            'finishDate': finishDateMoment.format('YYYY-MM-DD HH:mm:00'),
            'chooseWinnerFinishDate': chooseWinnerFinishDateMoment.format('YYYY-MM-DD HH:mm:00')
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
    saveFileIds() {
        $.post('/pitches/updatefiles.json', {"fileids": this.fileIds, "id": this.projectId});
    }
    saveData (pay) {
        if (this.data.features.award == 0) {
            alert('Укажите награду для дизайнера!');
            SubscribedBriefActions.unlockButton();
        } else {
            if(pay) {
                this.data.actionType = 'pay';
            }else {
                this.data.actionType = 'saveDraft';
            }
            $.post('/pitches/add_subscribed.json', this.data, function (response) {
                if(typeof(response.success) == 'undefined') {
                    if(response.error == 'need to fill balance') {
                        alert(`Для оплаты необходимо пополнить кошелёк на сумму ${response.needToFillAmount} р.!` );
                        window.location = `/subscription_plans/subscriber?amount=${response.needToFillAmount}`;
                    }else {
                        alert('При сохранении проекта возникла ошибка, пожалуйста, свяжитесь со службой поддержки.');
                    }
                }
                this.projectId = response.success;
                if(typeof(response.redirect) != 'undefined') {
                    window.location = `${response.redirect}`;
                }
                const viewUrl = `/pitches/view/${this.projectId}`;
                const editUrl = `/pitches/edit/${this.projectId}`;
                const deleteUrl = `/pitches/delete/${this.projectId}`;
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
                    `<tr data-id="${this.projectId}" class="selection ${evenClass} coda">
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
                            <a data-id="${this.projectId}" href="${deleteUrl}" class="delete deleteheader mypitch_delete_link" title="удалить">удалить</a>
                        </td>
                    </tr>`;
                if((pitchPanel.length == 1) && ($('tr[data-id="' + this.projectId + '"]', '#pitch-panel').length == 0)) {
                    $('#header-table').append(row);
                    scroll = true;
                }else if ($('tr[data-id="' + this.projectId + '"]', '#pitch-panel').length == 0) {
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
                                        <a href="https://www.godesigner.ru/answers/view/73">
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
                SubscribedBriefActions.unlockButton();
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
        if($('input[name=subscription-type]:checked').length > 0) {
            if($('input[name=subscription-type]:checked').val() == 'copyrighting') {
                specificPitchData['isCopyrighting'] = true;
            }else {
                specificPitchData['isCopyrighting'] = false;
            }

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