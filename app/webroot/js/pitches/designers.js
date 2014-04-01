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
    
    $(document).on('click', '.scroll_right', function() {
        var $wrapper = $(this).parent();
        var self = $(this);
        var $opp = self.prev();
        var $el = $wrapper.children('ul');
        $el.animate({left: '-=216'}, 500, function() {
            if ($el.position().left < 0) {
                $opp.show();
            }
            if ($el.width() + $el.position().left < $wrapper.width() + 10) {
                self.hide();
                $el.animate({right: -($el.width() - $el.parent().width())}, 0);
            }
        });
    });
    $(document).on('click', '.scroll_left', function() {
        var $wrapper = $(this).parent();
        var self = $(this);
        var $opp = self.next();
        var $el = $wrapper.children('ul');
        $el.animate({left: '+=216'}, 500, function() {
            if ($el.width() + $el.position().left > $wrapper.width()) {
                $opp.show();
            }
            if ($el.position().left >= 0) {
                self.hide();
                $el.animate({left: 0}, 0);
            }
        });
    });

    // Designers buttons
    $(document).on('click', '.next_part_design', function(e) {
        e.preventDefault();
        $('.button_more').css('opacity', 0);
        $('.gallery_postload_loader').show();
        var data = {
            count: $('.designer_row').length
        };
        var $search = $('#designer-name-search');
        if (($search.val().length > 0) && ($search.val() != $search.data('placeholder'))) {
            data.search = $search.val();
        }
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
            $('.button_more').css('opacity', 1);
            if ($('.designer_row').length >= designersCount) {
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
        var $search = $('#designer-name-search');
        if (($search.val().length > 0) && ($search.val() != $search.data('placeholder'))) {
            data.search = $search.val();
        }
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
            $('.gallery_postload_loader').hide();
            $('.button_more').css('opacity', 1);
            $('.gallery_postload').hide();
            checkScrollers();
            $('.pre-comment-separator').fadeIn();
            checkSeparator();
            loadExtraimages();
        });
    });

    // Search
    $(document).on('submit', '#designers-search', function() {
        var $search = $('#designer-name-search');
        if (($search.val().length == 0) || ($search.val() == $search.data('placeholder'))) {
            return false;
        }
        var $container = $('.portfolio_gallery.designers_tab');
        $container.html('<img id="search-ajax-loader" src="/img/blog-ajax-loader.gif" style="margin: 0 0 100px 400px;">');
        $('.gallery_postload').hide();
        $.get('/pitches/designers/' + $('input[name=pitch_id]').val(), $(this).serialize(), function(response) {
            var designersCount = $($(response)[0]).val();
            $('#search-ajax-loader').remove();
            if (designersCount != 0) {
                obj = $('<div/>').html(response).contents(); // http://stackoverflow.com/a/11047751
                obj.each(function(index) {
                    if ($(this).is('li')) {
                        $(this).css('opacity', '0');
                    }
                });
                obj.appendTo($container);
                obj.each(function(index) {
                    if ($(this).is('li')) {
                        $(this).animate({opacity:1}, 500);
                    }
                });
                if ($('.designer_row').length < designersCount) {
                    $('.gallery_postload').show();
                } else {
                    $('.pre-comment-separator').fadeIn();
                    checkSeparator();
                }
                checkScrollers();
            } else {
                $container.html('<div style="text-align: center; margin-bottom: 50px;"><h2 class="largest-header" style="line-height: 2em;">УПС, НИЧЕГО НЕ НАШЛИ!</h2><p class="large-regular">Попробуйте еще раз, изменив запрос.</p></div>');
            }
        });
        return false;
    });

    $(document).on('focus', '#designer-name-search', function() {
        $('#designers-search').addClass('active');
    });
    $(document).on('blur', '#designer-name-search', function() {
        $('#designers-search').removeClass('active');
    });
});
