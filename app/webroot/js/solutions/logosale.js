$(document).on('mouseover', '.solution-menu-toggle', function () {
    $('img', $(this)).attr('src', '/img/marker-green.png');
    $('body').one('click', function () {
        $('.solution_menu.temp').fadeOut(200, function () {
            $(this).remove();
        });
    });
    var container = $(this).closest('.photo_block');
    var menu = container.siblings('.solution_menu');
    var offset = container.offset();
    menu = menu.clone();
    menu.addClass('temp');
    $('body').append(menu);
    menu.offset({top: offset.top + 178, left: offset.left + 47});
    menu.fadeIn(200);
    $(menu).on('mouseleave', function () {
        $(this).fadeOut(200, function () {
            $(this).remove();
        });
    });
    $('.solution-info').on('mouseenter', function () {
        $('.solution_menu.temp').fadeOut(200, function () {
            $(this).remove();
        });
    });
});

$(document).on('mouseleave', '.solution-menu-toggle', function () {
    $('img', $(this)).attr('src', '/img/marker5_2.png');
});