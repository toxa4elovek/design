$(document).ready(function() {
    $('.time').timeago();

    $('.relatedpost').on('mouseover', function() {
        $(this).css('background-color', '#e9e9e9');
        $('.title', this).css('color', '#fa565b');
    })

    $('.relatedpost').on('mouseout', function() {
        $(this).css('background-color', '');
        $('.title', this).css('color', '#999999');
    })

    $(document).on('focus', '#blog-search', function() {
        $('#post-search').addClass('active');
    });
    $(document).on('blur', '#blog-search', function() {
        $('#post-search').removeClass('active');
    });
    spanlist = $('p, span');
    $.each(spanlist, function(idx, obj) {
        var $rama = $(obj).find('img');
        if ($rama.length > 1) {
            $.each($rama, function(idx, img) {
                parent = $(img).parent();
                if (parent.is('a')) {
                    $(img).insertBefore(parent);
                    parent.remove();
                }
            });
            $rama.wrapAll('<div class="fotorama" data-nav="false" data-maxwidth="100%" />');
            // 1. Initialize fotorama manually.
            var classFotorama = $(this).find('.fotorama');
            var $fotoramaDiv = classFotorama.fotorama();
            // 2. Get the API object.
            var fotorama = $fotoramaDiv.data('fotorama');
            var arrows = $(this).find('.fotorama__arr');
            if (arrows.length > 0) {
                arrows.remove();
            }
            $('<div class="fotorama_arrows"><span class="fotorama__arr--prev button round prev"><div class="arrow-left"></div></span><span class="page">1</span><span id="fotorama_separator">/</span><span class="count"></span><span class="fotorama__arr--next button round next"><div class="arrow-right"></div></span></div>').insertAfter(classFotorama);
            $(this).find('.count').append($rama.length);
        }
    });

    $('.fotorama__arr--prev').click(function() {
        fotorama = $(this).closest('p').find('.fotorama').data('fotorama');
        fotorama.show('<');
        var pageObject = $(this).closest('div').find('.page');
        var page = parseInt(pageObject.text());
        if (page > 1) {
            pageObject.text(page - 1);
        }
    });
    $('.fotorama__arr--next').click(function() {
        fotorama = $(this).closest('p').find('.fotorama').data('fotorama');
        fotorama.show('>');
        var pageObject = $(this).closest('div').find('.page');
        var page = parseInt(pageObject.text());
        if (page < fotorama.size) {
            pageObject.text(page + 1);
        }
    });

    // gplus
    window.___gcfg = {lang: 'ru'};

    (function() {
        var po = document.createElement('script');
        po.type = 'text/javascript';
        po.async = true;
        po.src = 'https://apis.google.com/js/plusone.js';
        var s = document.getElementsByTagName('script')[0];
        s.parentNode.insertBefore(po, s);
    })();

    //vk like
    VK.init({apiId: 2950889, onlyWidgets: true});
    VK.Widgets.Like("vk_like", {type: "mini"});

    (function($){

        $.fn.ctrlCmd = function(key) {

            var allowDefault = true;

            if (!$.isArray(key)) {
                key = [key];
            }

            return this.keydown(function(e) {
                for (var i = 0, l = key.length; i < l; i++) {
                    if(e.keyCode === key[i].toUpperCase().charCodeAt(0) && e.metaKey) {
                        allowDefault = false;
                    }
                };
                return allowDefault;
            });
        };


        $.fn.disableSelection = function() {

            this.ctrlCmd(['a', 'c']);

            return this.attr('unselectable', 'on')
                .css({'-moz-user-select':'-moz-none',
                    '-moz-user-select':'none',
                    '-o-user-select':'none',
                    '-khtml-user-select':'none',
                    '-webkit-user-select':'none',
                    '-ms-user-select':'none',
                    'user-select':'none'})
                .bind('selectstart', false);
        };

    })(jQuery);

    $('#content_help').disableSelection();

    $('img', '#content_help').bind('contextmenu', function(e) {
        return false;
    });

})

function clearData(){
    window.clipboardData.setData('text','')
}
function cldata(){
    if(window.clipboardData){
        window.clipboardData.clearData();
    }
}

setInterval("cldata();", 1000);