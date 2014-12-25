$(document).ready(function () {
    $('#prolong-checkbox').click(function () {
        if ($('#prolong-checkbox').attr('checked')) {
            $('#sub-prolong').show();
        } else {
            $('#sub-prolong').hide();
        }
    })
    $('#hide-check').click(function () {
        $(this).parent().removeClass('expanded');
        return false;
    })

    $('#show-check').click(function () {
        $(this).parent().addClass('expanded');
        return false;
    })
    $('.rb1').change(function () {
        switch ($(this).data('pay')) {
            case 'payanyway':
                $("#paybutton-payanyway").removeAttr('style');
                $("#paybutton-paymaster").css('background', '#a2b2bb');
                $("#paymaster-images").show();
                $("#paymaster-select").hide();
                break;
            case 'paymaster':
                $("#paybutton-paymaster").removeAttr('style');
                $("#paybutton-payanyway").css('background', '#a2b2bb');
                $("#paymaster-images").hide();
                $("#paymaster-select").show();
                break;
            case 'offline':
                $("#paybutton-payanyway").fadeOut(100);
                $("#paybutton-paymaster").css('background', '#a2b2bb');
                $("#paymaster-images").show();
                $("#paymaster-select").hide();
                $('#s3_kv').show();
                break;
        }
    });

    $('.rb-face', '#s3_kv').change(function () {
        if ($(this).data('pay') == 'offline-fiz') {
            $('.pay-fiz').show();
            $('.pay-yur').hide();
        } else {
            $('.pay-fiz').hide();
            $('.pay-yur').show();
        }
    });

    $('#bill-fiz').submit(function (e) {
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
            }, function (result) {
                if (result.error == false) {
                    window.location = '/pitches/getpdf/godesigner-pitch-' + $('#fiz-id').val() + '.pdf';
                }
            });
        }
    });

    $('#bill-yur').submit(function (e) {
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
            }, function (result) {
                if (result.error == false) {
                    window.location = '/pitches/getpdf/godesigner-pitch-' + $('#yur-id').val() + '.pdf';
                }
            });
        }
    });

    function checkRequired(form) {
        var required = false;
        $.each($('[required]', form), function (index, object) {
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
});