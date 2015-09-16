class PaymentWire extends BasePaymentSystem{
    paymentSystemName = 'payment-wire';
    constructor() {
        super();
        this.activateFiz = this.activateFiz.bind(this);
        this.activateYur = this.activateYur.bind(this);
        this.onSubmitFizHandler = this.onSubmitFizHandler.bind(this);
        this.onSubmitYurHandler = this.onSubmitYurHandler.bind(this);
    }
    onSubmitFizHandler(e) {
        const form = $(e.target);
        const projectId = parseInt(this.refs['fiz-id'].value);
        const name = this.refs['fiz-name'].value;
        const individualStatus = this.refs['fiz-individual'].value;
        const data = {
            'id': projectId,
            'name': name,
            'individual': individualStatus,
            'inn': 0,
            'kpp': 0,
            'address': 0
        };
        e.preventDefault();
        if (this.checkRequired(form)) {
            $.scrollTo($('.wrong-input', form).parent(), {duration: 600});
        } else {
            $.post(form.attr('action') + '.json', data, function (result) {
                if (result.error == false) {
                    window.location = '/pitches/getpdf/godesigner-pitch-' + projectId + '.pdf';
                }
            });
        }
    }
    onSubmitYurHandler(e) {
        const form = $(e.target);
        const projectId = parseInt(this.refs['yur-id'].value);
        const name = this.refs['yur-name'].value;
        const individualStatus = this.refs['yur-individual'].value;
        const inn = this.refs['yur-inn'].value;
        const kpp = this.refs['yur-kpp'].value;
        const address = this.refs['yur-address'].value;
        const data = {
            'id': projectId,
            'name': name,
            'individual': individualStatus,
            'inn': inn,
            'kpp': kpp,
            'address': address
        };
        e.preventDefault();
        if (this.checkRequired(form)) {
            $.scrollTo($('.wrong-input', form).parent(), {duration: 600});
        } else {
            $.post(form.attr('action') + '.json', data, function (result) {
                if (result.error == false) {
                    window.location = '/pitches/getpdf/godesigner-pitch-' + projectId + '.pdf';
                }
            });
        }
    }
    activateFiz() {
        $(this.refs.fiz).show();
        $(this.refs.yur).hide();
    }
    activateYur() {
        $(this.refs.fiz).hide();
        $(this.refs.yur).show();
    }
    checkRequired(form) {
        let required = false;
        $.each($('[data-required="true"]', form), function () {
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
    render() {
        const checked = this.props.selected;
        const projectId = this.props.payload.projectId;
        let widgetStyle = {"display": "none"};
        if(checked === true) {
            widgetStyle = {"display": "block"};
        }
        return (
            <div className="wire">
                <div className="payment" onClick={this.onClickHandler}>
                    <input ref="radio" checked={checked} onChange={this.onClickHandler}
                           type="radio" name="payment-options"
                           data-pay="wire"/>
                    <img className="imageblock"
                         src="/img/s3_rsh.png" alt="Безналичный платеж через банк"/>
                                            <span className="description">Перевод на
                                                расчетный счёт<br />(Безналичный платеж через банк)
                                            </span>
                </div>

                <div className="wire-data-inputs" style={widgetStyle}>
                    <label><input type="radio" name="radio-face" className="rb-face" data-pay="offline-fiz" onClick={this.activateFiz} /> ФИЗИЧЕСКОЕ ЛИЦО</label>
                    <label><input type="radio" name="radio-face" className="rb-face" data-pay="offline-yur" onClick={this.activateYur}/> ЮРИДИЧЕСКОЕ ЛИЦО</label>
                    <div className="pay-fiz" ref="fiz" style={{"display": "none"}}>
                        <p>Заполните поля, скачайте счёт на оплату и оплатите его. С помощью него вы можете сделать безналичный перевод через банк.</p>
                        <form onSubmit={this.onSubmitFizHandler} action="/bills/save" method="post">
                            <input type="hidden" name="fiz-id" ref="fiz-id" value={projectId} />
                            <input type="hidden" name="fiz-individual" ref="fiz-individual" value="1" />
                            <input type="text" name="fiz-name" ref="fiz-name" placeholder="Иванов Иван Иванович" data-placeholder="Иванов Иван Иванович" data-required="true" data-content="symbolic" className="placeholder" />
                            <img src="/img/arrow-bill-download.png" className="arrow-bill-download" />
                            <input type="submit" value="Скачать счёт" className="button third" style={{"width": "420px"}} />
                            <div className="clr"></div>
                        </form>
                        <p>Мы активируем ваш проект на сайте в течение рабочего дня после поступления денег, и тогда он появится в <a href="/pitches">общем списке</a>.
                            Пока вы можете просмотреть ваш проект в <a href="/users/mypitches">личном кабинете</a>.</p>
                    </div>
                    <div className="pay-yur" ref="yur" style={{"display": "none"}}>
                        <p>Заполните поля, скачайте счёт на оплату и оплатите его. С помощью него вы можете сделать безналичный перевод через банк.</p>
                        <form onSubmit={this.onSubmitYurHandler} action="/bills/save" method="post">
                            <input type="hidden" name="yur-id" ref="yur-id" value={projectId} />
                            <input type="hidden" name="yur-individual" ref="yur-individual" value="0" />

                            <label className="required">Наименование организации</label>
                            <input type="text" name="yur-name" ref="yur-name" placeholder="OOO «КРАУД МЕДИА»" data-placeholder="OOO «КРАУД МЕДИА»" data-required="true" data-content="mixed" className="placeholder" />

                            <label className="required">ИНН</label>
                            <input type="text" name="yur-inn" ref="yur-inn" placeholder="123456789012" data-placeholder="123456789012" data-required="true" data-content="numeric" data-length="[10,12]" className="placeholder" />

                            <label>КПП</label>
                            <input type="text" name="yur-kpp" ref="yur-kpp" placeholder="123456789" data-placeholder="123456789" data-required="true" data-content="numeric" data-length="[9]" className="placeholder" />

                            <label className="required">Юридический адрес</label>
                            <input type="text" name="yur-address" ref="yur-address" placeholder="199397, Санкт-Петербург, ул. Беринга, д. 27" data-placeholder="199397, Санкт-Петербург, ул. Беринга, д. 27" data-required="true" data-content="mixed" className="placeholder" />

                            <p>Мы активируем ваш абонентский кабинет на сайте или зачислим средства на счёт в течение рабочего дня после поступления денег.</p>
                            <p>Закрывающие документы вы получите на e-mail сразу после того, как завершите проект. Распечатайте их, подпишите и поставьте печать.
                                Отправьте их нам в двух экземплярах по почте (199397, Россия, Санкт-Петербург, ул. Беринга, д. 27).
                                В ответном письме вы получите оригиналы документов с нашей печатью.</p>
                            <input type="submit" value="Скачать счёт" className="button third" style={{"width": "420px"}} />
                            <div className="clr"></div>
                        </form>
                    </div>
                </div>
            </div>
        );
    }
}