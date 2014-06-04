$(document).ready(function() {
    $(document).on('submit', '#quiz_form', function(e) {
        e.preventDefault();
        $.post($(this).attr('action'), $(this).serialize(), function() {
            
        }, 'html')
        .done(function(response) {
            $container = $('.content.group');
            $objOld = $('.howitworks');
            $objNew = $('<div/>').html(response).contents(); // http://stackoverflow.com/a/11047751
            $objOld.fadeOut(600).remove();
            $objNew.hide().appendTo($container).fadeIn(600);
        })
        .fail(function(response) {
        
        });
        return false;
    });
});
