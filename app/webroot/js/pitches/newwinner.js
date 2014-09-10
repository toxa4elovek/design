$(document).ready(function() {
    $('#prolong-checkbox').click(function() {
        if ($('#prolong-checkbox').attr('checked')) {
            $('#sub-prolong').show();
        } else {
            $('#sub-prolong').hide();
        }
    })
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
        }
    });
});