$(document).ready(function() {

    // Scrolling
    function checkScrollers() {
        $.each($('.designer_wrapper'), function(idx, obj) {
            if ($(obj).width() < $('ul', $(obj)).first().width()) {
                $('.scroll_right', $(obj)).show();
            }
        });
    }
    checkScrollers();
    
    $(document).on('mousedown', '.scroll_right', function() {
        var $wrapper = $(this).parent();
        var self = $(this);
        var $opp = self.prev();
        var $el = $wrapper.children('ul');
        designerInterval = setInterval(function() {
            $el.animate({left: '-=5'}, 5);
            if ($el.position().left < 0) {
                $opp.show();
            }
            if ($el.width() + $el.position().left < $wrapper.width() + 10) {
                self.hide();
                clearInterval(designerInterval);
                $el.animate({right: -($el.width() - $el.parent().width())}, 0);
            }
        }, 15);
    });
    $(document).on('mousedown', '.scroll_left', function() {
        var $wrapper = $(this).parent();
        var self = $(this);
        var $opp = self.next();
        var $el = $wrapper.children('ul');
        designerInterval = setInterval(function() {
            $el.animate({left: '+=5'}, 5);
            if ($el.width() + $el.position().left > $wrapper.width()) {
                $opp.show();
            }
            if ($el.position().left > 0) {
                self.hide();
                clearInterval(designerInterval);
                $el.animate({left: 0}, 0);
            }
        }, 15);
    });
    $(document).on('mouseup', '.scroll_right, .scroll_left', function() {
        clearInterval(designerInterval);
    });

    // Designers buttons
    $(document).on('click', '.next_part_design', function(e) {
        e.preventDefault();
        $('.button_more').css('opacity', 0);
        $('.gallery_postload_loader').show();
        var data = {
            count: $('.designer_row').length
        };
        var gallerySorting = getParameterByName('sorting');
        if (gallerySorting.length > 0) {
            data.sorting = gallerySorting;
        }
        $.get('/pitches/designers/' + $('input[name=pitch_id]').val(), data, function(response) {
            var designersCount = $($(response)[0]).val();
            obj = $('<div/>').html(response).contents(); // http://stackoverflow.com/a/11047751
            obj.each(function(index) {
                if ($(this).is('li')) {
                    $(this).css('opacity', '0');
                }
            });
            obj.appendTo('.portfolio_gallery.designers_tab');
            obj.each(function(index) {
                if ($(this).is('li')) {
                    $(this).animate({opacity:1}, 500);
                }
            });
            $('.gallery_postload_loader').hide();
            if ($('.designer_row').length < designersCount) {
                $('.button_more').css('opacity', 1);
            } else {
                $('.gallery_postload').hide();
                $('.pre-comment-separator').fadeIn();
                checkSeparator();
            }
            checkScrollers();
            loadExtraimages();
        });
    });
    $(document).on('click', '.rest_part_design', function(e) {
        e.preventDefault();
        $('.button_more').css('opacity', 0);
        $('.gallery_postload_loader').show();
        var data = {
            count: $('.designer_row').length,
            rest: 1
        };
        var gallerySorting = getParameterByName('sorting');
        if (gallerySorting.length > 0) {
            data.sorting = gallerySorting;
        }
        $.get('/pitches/designers/' + $('input[name=pitch_id]').val(), data, function(response) {
            obj = $('<div/>').html(response).contents(); // http://stackoverflow.com/a/11047751
            obj.each(function(index) {
                if ($(this).is('li')) {
                    $(this).css('opacity', '0');
                }
            });
            obj.appendTo('.portfolio_gallery.designers_tab');
            obj.each(function(index) {
                if ($(this).is('li')) {
                    $(this).animate({opacity:1}, 500);
                }
            });
            $('.gallery_postload').hide();
            checkScrollers();
            $('.pre-comment-separator').fadeIn();
            checkSeparator();
            loadExtraimages();
        });
    });
});
