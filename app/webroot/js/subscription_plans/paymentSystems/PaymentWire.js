'use strict';

var _createClass = (function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ('value' in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; })();

var _get = function get(_x, _x2, _x3) { var _again = true; _function: while (_again) { var object = _x, property = _x2, receiver = _x3; desc = parent = getter = undefined; _again = false; if (object === null) object = Function.prototype; var desc = Object.getOwnPropertyDescriptor(object, property); if (desc === undefined) { var parent = Object.getPrototypeOf(object); if (parent === null) { return undefined; } else { _x = parent; _x2 = property; _x3 = receiver; _again = true; continue _function; } } else if ('value' in desc) { return desc.value; } else { var getter = desc.get; if (getter === undefined) { return undefined; } return getter.call(receiver); } } };

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError('Cannot call a class as a function'); } }

function _inherits(subClass, superClass) { if (typeof superClass !== 'function' && superClass !== null) { throw new TypeError('Super expression must either be null or a function, not ' + typeof superClass); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, enumerable: false, writable: true, configurable: true } }); if (superClass) Object.setPrototypeOf ? Object.setPrototypeOf(subClass, superClass) : subClass.__proto__ = superClass; }

var PaymentWire = (function (_BasePaymentSystem) {
    _inherits(PaymentWire, _BasePaymentSystem);

    function PaymentWire() {
        _classCallCheck(this, PaymentWire);

        _get(Object.getPrototypeOf(PaymentWire.prototype), 'constructor', this).call(this);
        this.paymentSystemName = 'payment-wire';
        this.activateFiz = this.activateFiz.bind(this);
        this.activateYur = this.activateYur.bind(this);
        this.onSubmitFizHandler = this.onSubmitFizHandler.bind(this);
        this.onSubmitYurHandler = this.onSubmitYurHandler.bind(this);
    }

    _createClass(PaymentWire, [{
        key: 'onSubmitFizHandler',
        value: function onSubmitFizHandler(e) {
            var form = $(e.target);
            var projectId = parseInt(this.refs['fiz-id'].value);
            var name = this.refs['fiz-name'].value;
            var individualStatus = this.refs['fiz-individual'].value;
            var data = {
                'id': projectId,
                'name': name,
                'individual': individualStatus,
                'inn': 0,
                'kpp': 0,
                'address': 0
            };
            e.preventDefault();
            if (this.checkRequired(form)) {
                $.scrollTo($('.wrong-input', form).parent(), { duration: 600 });
            } else {
                $.post(form.attr('action') + '.json', data, function (result) {
                    if (result.error == false) {
                        window.location = '/pitches/getpdf/godesigner-pitch-' + projectId + '.pdf';
                    }
                });
            }
        }
    }, {
        key: 'onSubmitYurHandler',
        value: function onSubmitYurHandler(e) {
            var form = $(e.target);
            var projectId = parseInt(this.refs['yur-id'].value);
            var name = this.refs['yur-name'].value;
            var individualStatus = this.refs['yur-individual'].value;
            var inn = this.refs['yur-inn'].value;
            var kpp = this.refs['yur-kpp'].value;
            var address = this.refs['yur-address'].value;
            var data = {
                'id': projectId,
                'name': name,
                'individual': individualStatus,
                'inn': inn,
                'kpp': kpp,
                'address': address
            };
            e.preventDefault();
            if (this.checkRequired(form)) {
                $.scrollTo($('.wrong-input', form).parent(), { duration: 600 });
            } else {
                $.post(form.attr('action') + '.json', data, function (result) {
                    if (result.error == false) {
                        window.location = '/pitches/getpdf/godesigner-pitch-' + projectId + '.pdf';
                    }
                });
            }
        }
    }, {
        key: 'activateFiz',
        value: function activateFiz() {
            $(this.refs.fiz).show();
            $(this.refs.yur).hide();
        }
    }, {
        key: 'activateYur',
        value: function activateYur() {
            $(this.refs.fiz).hide();
            $(this.refs.yur).show();
        }
    }, {
        key: 'checkRequired',
        value: function checkRequired(form) {
            var required = false;
            $.each($('[data-required="true"]', form), function () {
                if ($(this).attr('id') == 'yur-kpp' && $('#yur-inn').val().length == 10) {
                    $(this).val('');
                    return true;
                }
                if ($(this).val() == $(this).data('placeholder') || $(this).val().length == 0) {
                    $(this).addClass('wrong-input');
                    required = true;
                    return true;
                }
                if ($(this).data('length') && $(this).data('length').length > 0) {
                    var arrayLength = $(this).data('length');
                    if (-1 == $.inArray($(this).val().length, arrayLength)) {
                        $(this).addClass('wrong-input');
                        required = true;
                        return true;
                    }
                }
                if ($(this).data('content') && $(this).data('content').length > 0) {
                    if ($(this).data('content') == 'numeric') {
                        if (/\D+/.test($(this).val())) {
                            $(this).addClass('wrong-input');
                            required = true;
                            return true;
                        }
                    }
                    if ($(this).data('content') == 'symbolic') {
                        if (/[^a-zа-я\s]/i.test($(this).val())) {
                            $(this).addClass('wrong-input');
                            required = true;
                            return true;
                        }
                    }
                    if ($(this).data('content') == 'mixed') {
                        if (!/[a-zа-я0-9]/i.test($(this).val())) {
                            $(this).addClass('wrong-input');
                            required = true;
                            return true;
                        }
                    }
                }
            });
            return required;
        }
    }, {
        key: 'render',
        value: function render() {
            var data = this.props.payload.userData;
            var checked = this.props.selected;
            var projectId = this.props.payload.projectId;
            var widgetStyle = { "display": "none" };
            if (checked === true) {
                widgetStyle = { "display": "block" };
            }
            return React.createElement(
                'div',
                { className: 'wire' },
                React.createElement(
                    'div',
                    { className: 'payment', onClick: this.onClickHandler },
                    React.createElement('input', { ref: 'radio', checked: checked, onChange: this.onClickHandler,
                        type: 'radio', name: 'payment-options',
                        'data-pay': 'wire' }),
                    React.createElement('img', { className: 'imageblock',
                        src: '/img/s3_rsh.png', alt: 'Безналичный платеж через банк' }),
                    React.createElement(
                        'span',
                        { className: 'description' },
                        'Перевод на расчетный счёт',
                        React.createElement('br', null),
                        '(Безналичный платеж через банк)'
                    )
                ),
                React.createElement(
                    'div',
                    { className: 'wire-data-inputs', style: widgetStyle },
                    React.createElement(
                        'label',
                        null,
                        React.createElement('input', { type: 'radio', name: 'radio-face', className: 'rb-face', 'data-pay': 'offline-fiz', onClick: this.activateFiz }),
                        ' ФИЗИЧЕСКОЕ ЛИЦО'
                    ),
                    React.createElement(
                        'label',
                        null,
                        React.createElement('input', { type: 'radio', name: 'radio-face', className: 'rb-face', 'data-pay': 'offline-yur', onClick: this.activateYur }),
                        ' ЮРИДИЧЕСКОЕ ЛИЦО'
                    ),
                    React.createElement(
                        'div',
                        { className: 'pay-fiz', ref: 'fiz', style: { "display": "none" } },
                        React.createElement(
                            'p',
                            null,
                            'Заполните поля, скачайте счёт на оплату и оплатите его. С помощью него вы можете сделать безналичный перевод через банк.'
                        ),
                        React.createElement(
                            'form',
                            { onSubmit: this.onSubmitFizHandler, action: '/bills/save', method: 'post' },
                            React.createElement('input', { type: 'hidden', name: 'fiz-id', ref: 'fiz-id', value: projectId }),
                            React.createElement('input', { type: 'hidden', name: 'fiz-individual', ref: 'fiz-individual', value: '1' }),
                            React.createElement('input', { type: 'text', defaultValue: data.company_name, name: 'fiz-name', ref: 'fiz-name', placeholder: 'Иванов Иван Иванович', 'data-placeholder': 'Иванов Иван Иванович', 'data-required': 'true', 'data-content': 'symbolic', className: 'placeholder' }),
                            React.createElement('img', { src: '/img/arrow-bill-download.png', className: 'arrow-bill-download' }),
                            React.createElement('input', { type: 'submit', value: 'Скачать счёт', className: 'button third', style: { "width": "420px" } }),
                            React.createElement('div', { className: 'clr' })
                        ),
                        React.createElement(
                            'p',
                            null,
                            'Мы активируем ваш проект на сайте в течение рабочего дня после поступления денег, и тогда он появится в ',
                            React.createElement(
                                'a',
                                { href: '/pitches' },
                                'общем списке'
                            ),
                            '. Пока вы можете просмотреть ваш проект в ',
                            React.createElement(
                                'a',
                                { href: '/users/mypitches' },
                                'личном кабинете'
                            ),
                            '.'
                        )
                    ),
                    React.createElement(
                        'div',
                        { className: 'pay-yur', ref: 'yur', style: { "display": "none" } },
                        React.createElement(
                            'p',
                            null,
                            'Заполните поля, скачайте счёт на оплату и оплатите его. С помощью него вы можете сделать безналичный перевод через банк.'
                        ),
                        React.createElement(
                            'form',
                            { onSubmit: this.onSubmitYurHandler, action: '/bills/save', method: 'post' },
                            React.createElement('input', { type: 'hidden', name: 'yur-id', ref: 'yur-id', value: projectId }),
                            React.createElement('input', { type: 'hidden', name: 'yur-individual', ref: 'yur-individual', value: '0' }),
                            React.createElement(
                                'label',
                                { className: 'required' },
                                'Наименование организации'
                            ),
                            React.createElement('input', { type: 'text', defaultValue: data.company_name, name: 'yur-name', ref: 'yur-name', placeholder: 'OOO «КРАУД МЕДИА»', 'data-placeholder': 'OOO «КРАУД МЕДИА»', 'data-required': 'true', 'data-content': 'mixed', className: 'placeholder' }),
                            React.createElement(
                                'label',
                                { className: 'required' },
                                'ИНН'
                            ),
                            React.createElement('input', { type: 'text', defaultValue: data.inn, name: 'yur-inn', ref: 'yur-inn', placeholder: '123456789012', 'data-placeholder': '123456789012', 'data-required': 'true', 'data-content': 'numeric', 'data-length': '[10,12]', className: 'placeholder' }),
                            React.createElement(
                                'label',
                                null,
                                'КПП'
                            ),
                            React.createElement('input', { type: 'text', defaultValue: data.kpp, name: 'yur-kpp', ref: 'yur-kpp', placeholder: '123456789', 'data-placeholder': '123456789', 'data-required': 'true', 'data-content': 'numeric', 'data-length': '[9]', className: 'placeholder' }),
                            React.createElement(
                                'label',
                                { className: 'required' },
                                'Юридический адрес'
                            ),
                            React.createElement('input', { type: 'text', defaultValue: data.address, name: 'yur-address', ref: 'yur-address', placeholder: '199397, Санкт-Петербург, ул. Беринга, д. 27', 'data-placeholder': '199397, Санкт-Петербург, ул. Беринга, д. 27', 'data-required': 'true', 'data-content': 'mixed', className: 'placeholder' }),
                            React.createElement(
                                'p',
                                null,
                                'Мы активируем ваш абонентский кабинет на сайте или зачислим средства на счёт в течение рабочего дня после поступления денег.'
                            ),
                            React.createElement(
                                'p',
                                null,
                                'Закрывающие документы вы получите на e-mail сразу после того, как завершите проект. Распечатайте их, подпишите и поставьте печать. Отправьте их нам в двух экземплярах по почте (199397, Россия, Санкт-Петербург, ул. Беринга, д. 27). В ответном письме вы получите оригиналы документов с нашей печатью.'
                            ),
                            React.createElement('input', { type: 'submit', value: 'Скачать счёт', className: 'button third', style: { "width": "420px" } }),
                            React.createElement('div', { className: 'clr' })
                        )
                    )
                )
            );
        }
    }]);

    return PaymentWire;
})(BasePaymentSystem);