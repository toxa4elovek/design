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

    $.each($('p'), function(idx, obj) {
        var $rama = $(obj).children('img');
        if ($rama.length > 1) {
            $rama.wrapAll( '<div class="fotorama" data-nav="false"  data-width="500" data-maxwidth="100%" data-ratio="500/333" />');
        }
    });
    // 1. Initialize fotorama manually.
    var $fotoramaDiv = $('.fotorama').fotorama();
    // 2. Get the API object.
    var fotorama = $fotoramaDiv.data('fotorama');

    if ($('.fotorama__arr').length > 0) {
        $('.fotorama__arr').remove();
    }
    $('<div class="fotorama_arrows"><span class="fotorama__arr fotorama__arr--prev"></span><span class="page">1</span> / <span class="count"></span><span class="fotorama__arr fotorama__arr--next"></span></div>').insertAfter(".fotorama");
	$('.count').append(fotorama.size);

    $('.fotorama__arr--prev').click(function () {
		fotorama = $(this).closest('p').find('.fotorama').data('fotorama');
        fotorama.show('<');
		var pageObject=$(this).closest('div').find('.page');
		var page=parseInt(pageObject.text());
		if(page>1){
			pageObject.text(page-1);
		}
		
    });
    $('.fotorama__arr--next').click(function () {
		fotorama = $(this).closest('p').find('.fotorama').data('fotorama');
        fotorama.show('>');
		var pageObject=$(this).closest('div').find('.page');
		var page=parseInt(pageObject.text());
		if(page<fotorama.size){
			pageObject.text(page+1);
		}
    });

    // gplus
    window.___gcfg = {lang: 'ru'};

    (function() {
        var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
        po.src = 'https://apis.google.com/js/plusone.js';
        var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
    })();

    //vk like
    VK.init({apiId: 2950889, onlyWidgets: true});
    VK.Widgets.Like("vk_like", {type: "mini"});

})