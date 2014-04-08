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
    function searchCallback($search, forced) {
        var forced = forced || false;
        var $form = $search.parent();
        var $container = $('.portfolio_gallery.designers_tab');
        var $buttons = $('.gallery_postload');
        if ($buttons.is(':visible') || forced) {
            $container.html('<img id="search-ajax-loader" src="/img/blog-ajax-loader.gif" style="margin: 0 0 100px 400px;">');
            $buttons.hide();
            $.get('/pitches/designers/' + $('input[name=pitch_id]').val(), $form.serialize(), function(response) {
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
                        $buttons.show();
                    } else {
                        $('.pre-comment-separator').fadeIn();
                        checkSeparator();
                    }
                    checkScrollers();
                } else {
                    $container.html('<div class="ooopsSign" style="text-align: center; margin-bottom: 50px;"><h2 class="largest-header" style="line-height: 2em;">УПС, НИЧЕГО НЕ НАШЛИ!</h2><p class="large-regular">Попробуйте еще раз, изменив запрос.</p></div>');
                }
            });
        } else {
            var pattern = $search.val().toLowerCase();
            $('.designer_row').each(function(idx, obj) {
                var name = $(obj).find('.designer_name').text().toLowerCase();
                if (name.indexOf(pattern) == -1) {
                    $(obj).fadeOut(200);
                } else {
                    $('.ooopsSign', $container).remove();
                    $(obj).fadeIn(200);
                }
            });
            setTimeout(function() {
                if ($('.designer_row:visible').length == 0) {
                    $('.ooopsSign', $container).remove();
                    $container.append('<div class="ooopsSign" style="text-align: center; margin-bottom: 50px;"><h2 class="largest-header" style="line-height: 2em;">УПС, НИЧЕГО НЕ НАШЛИ!</h2><p class="large-regular">Попробуйте еще раз, изменив запрос.</p></div>');
                }
            }, 400);
        }
    }

    $(document).on('submit', '#designers-search', function() {
        var $search = $('#designer-name-search');
        if (($search.val().length == 0) || ($search.val() == $search.data('placeholder'))) {
            $search.val('');
        }
        searchCallback($search, true);
        return false;
    });

    // Live Search
    $(document).on('keyup', '#designer-name-search', function(e) {
        clearTimeout($.data(this, 'timer'));
        if (e.keyCode == 13) {
            return true;
        }

        var c = (e.keyCode);
        var isWordCharacter = checkSymbol(c);
        var isBackspaceOrDelete = (e.keyCode == 8 || e.keyCode == 46 || e.keyCode == 16);

        if (!isWordCharacter && !isBackspaceOrDelete) {
            return false;
        }
        var $search = $(this);
        var forced = false;
        if (($search.val().length == 0) || ($search.val() == $search.data('placeholder'))) {
            $search.val('');
            forced = true;
        }
        $(this).data('timer', setTimeout(function() { searchCallback($search, forced); }, 600));
        return true;
    });

    $(document).on('focus', '#designer-name-search', function() {
        $('#designers-search').addClass('active');
    });
    $(document).on('blur', '#designer-name-search', function() {
        $('#designers-search').removeClass('active');
    });

    function checkSymbol(code) {
        //var numbers = [48, 49, 50, 51, 52, 53, 54, 55, 56, 57];
        //var latins = [65, 66, 67, 68, 69, 70, 71, 72, 73, 74, 75, 76, 77, 78, 79, 80, 81, 82, 83, 84, 85, 86, 87, 88, 89, 90, 97, 98, 99, 100, 101, 102, 103, 104, 105, 106, 107, 108, 109, 110, 111, 112, 113, 114, 115, 116, 117, 118, 119, 120, 121, 122];
        //var cyr = [176, 177, 178, 179, 180, 181, 182, 183, 184, 185, 186, 187, 188, 189, 190, 191, 192, 193, 194, 195, 196, 197, 198, 199, 200, 201, 202, 203, 204, 205, 206, 207, 208, 209, 210, 211, 212, 213, 214, 215, 216, 217, 218, 219, 220, 221, 222, 223, 224, 225, 226, 227, 228, 229, 230, 231, 232, 233, 234, 235, 236, 237, 238, 239]
        var all = [32, 48, 49, 50, 51, 52, 53, 54, 55, 56, 57, 65, 66, 67, 68, 69, 70, 71, 72, 73, 74, 75, 76, 77, 78, 79, 80, 81, 82, 83, 84, 85, 86, 87, 88, 89, 90, 97, 98, 99, 100, 101, 102, 103, 104, 105, 106, 107, 108, 109, 110, 111, 112, 113, 114, 115, 116, 117, 118, 119, 120, 121, 122, 176, 177, 178, 179, 180, 181, 182, 183, 184, 185, 186, 187, 188, 189, 190, 191, 192, 193, 194, 195, 196, 197, 198, 199, 200, 201, 202, 203, 204, 205, 206, 207, 208, 209, 210, 211, 212, 213, 214, 215, 216, 217, 218, 219, 220, 221, 222, 223, 224, 225, 226, 227, 228, 229, 230, 231, 232, 233, 234, 235, 236, 237, 238, 239]
        if ($.inArray(code, all) == -1) {
            return false;
        }
        return true;
    }
});

