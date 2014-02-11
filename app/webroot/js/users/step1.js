$(document).ready(function() {


    $('#step2-link-saveform').click(function() {
        if($('.wrong-input').length > 0) {
            return false;
        }
        if ($('input[name="cashintype"]:checked').data('pay') == 'cards') {
            accountCheck();
            if ($('.account-check').length > 0) {
                return false;
            }
        }
        var href = $(this).attr('href');
        $.post('/users/savePaymentData.json', $('#worker-payment-data').serialize(), function(response) {
            window.location = href;
        })
        return false;
    });

    $('.rb1').change(function() {
        if($(this).data('pay') == 'cards') {
            $('#cards').show();
            $('#wmr').hide();
        }else {
            $('#cards').hide();
            $('#wmr').show();
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

    $('#step2-link-saveform').mouseover(function() {
        $('span', this).css('color', '#50525a');
    });

    $('#step2-link-saveform').mouseout(function() {
        $('span', this).css('color', '#BABABA');
    });
    
    $('input[data-validate]').focus(function() {
        $(this).removeClass('wrong-input');
    });
    
    $('input[data-validate=fio]').blur(function() {
        if (!/^[а-я]+\s[а-я]+\s[а-я]+$/i.test($(this).val())) {
            $(this).addClass('wrong-input');
            required = true;
            return true;
        }
    });
    
    $('input[data-validate=numeric]').blur(function() {
        if (/[\D\s]/i.test($(this).val())) {
            $(this).addClass('wrong-input');
            required = true;
            return true;
        }
    });
    
    $('input[name=bik], input[name=coraccount], input[name=accountnum], input[name="inn"]').on('blur', function() {
        accountCheck();
    });
    
    $(document).on('click', function() {
        if ($('.account-check').hasClass('active')) {
            $('.account-check').remove();
        }
    });
    
});

function accountCheck() {
    $('.account-check').remove();
    var resultCor = 1; //var resultCor = (fn_checkKS($('input[name=coraccount]').val())) ? 1 : 0;
    var resultAcc = (fn_checkRS($('input[name=accountnum]').val(), $('input[name=bik]').val())) ? 2 : 0;
    var result = resultCor + resultAcc;
    var message = '';
    switch (result) {
    case 0:
        message = 'Неверно указан Счёт.<br>Неверно указан Корсчёт.<br>'
        break;
    case 1:
        message = 'Неверно указан Счёт.<br>'
            break;
    case 2:
        message = 'Неверно указан Корсчёт.<br>'
            break;
    default:
        break;
    }
    var messageBik = (/^\d{9}$/.test($('input[name=bik]').val())) ? '' : 'Неверно указан БИК.<br>';
    var messageInn = (/^\d{12}$/.test($('input[name="inn"]').val()) ) ? '' : 'Неверно указан ИНН.<br>';
    message = messageBik + messageInn + message;
    if (message) {
        var el = $('<tr class="account-check"><td colspan="2">' + message + '</td></tr>');
        el.appendTo($('#step1table')).animate({'opacity': 1}, 200, function() { $(this).addClass('active'); });
    }
}

/*
 * From http://javascript.ru/forum/misc/37373-funkciya-klyuchevaniya-scheta.html
 */
function fn_bank_account(Str)  
{         
    var result = false;
    var Sum = 0;
    if (Str == 0) {
        return result;
    }
    
    //весовые коэффициенты
    var v = [7,1,3,7,1,3,7,1,3,7,1,3,7,1,3,7,1,3,7,1,3,7,1];
    
    for (var i = 0; i <= 22; i++) 
    { 
        //вычисляем контрольную сумму
        Sum = Sum + ( Number(Str.charAt(i)) * v[i] ) % 10;
    }
    
    //сравниваем остаток от деления контрольной суммы на 10 с нулём
    if(Sum % 10 == 0)
    {
        result = true;
    }
        
    return result;          
}

function fn_checkKS(Account)  
{
    return (/^\d{20}$/.test(Account));
}

/*
Проверка правильности указания расчётного счёта:
1. Для проверки контрольной суммы перед расчётным счётом добавляются три последние цифры БИКа банка.
*/
function fn_checkRS(Account,BIK)  
{
    return fn_bank_account(BIK.substr(-3,3)+Account);
}
