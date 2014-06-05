$(document).ready(function() {
    $(document).on('submit', '#quiz_form', function(e) {
        e.preventDefault();
        $.post($(this).attr('action'), $(this).serialize(), function() {
            
        }, 'html')
        .done(function(response) {
            $container = $('.content.group');
            $objOld = $('.howitworks');
            $objNew = $('<div/>').html(response).contents(); // http://stackoverflow.com/a/11047751
            $objOld.fadeOut(200, function() {
                $(this).remove();
                $objNew.hide().appendTo($container).fadeIn(600);
            });
        })
        .fail(function(response) {
        
        });
        return false;
    });

    $(document).on('click', '.js-start-test', function(e) {
        e.preventDefault();
        $('.right-sidebar').fadeOut(200, function() {
            $(this).remove();
        });
        $('.howitworks.quiz').fadeOut(200, function() {
            $(this).remove();
            $('.content.group').removeClass('narrow');
            $('.nicht').toggleClass('nicht howitworks quiz').appendTo($('.content.group')).fadeIn(600);
        });
    });
});
