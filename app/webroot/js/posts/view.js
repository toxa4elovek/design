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