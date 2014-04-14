$(document).ready(function() {
    $(document).on('click', '.ajaxgallery', function(e) {
        e.preventDefault();
        $('.tabs-curve').find('li').removeClass('active');
        $(this).parent().addClass('active');
        var url = $(this).attr('href');
        var $container = $('.gallery_container');
        $container.html('<img id="search-ajax-loader" src="/img/blog-ajax-loader.gif" style="margin: 60px 0 100px 400px;">');
        $.get(url, function(response) {
            var $replacement = $(response).find('.gallery_container');
            $container.fadeOut(200, function() { $(this).html($replacement); });
            $container.fadeIn(200, function() {  });
        });
    });
});
