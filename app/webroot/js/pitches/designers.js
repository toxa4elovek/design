$(document).ready(function() {
    $.each($('ul.list_portfolio.designers_tab'), function(idx, obj) {
        if ($(obj).prop('scrollWidth') > $(obj).width()) {
            $('.scroll_right, .scroll_left', $(obj)).show();
        }
    });
});
