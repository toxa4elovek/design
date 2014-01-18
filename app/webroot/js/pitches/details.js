$(document).ready(function() {
    $( ".slider" ).each(function(index, object) {
        var value = logoProperties[index];
        $(object).slider({
            disabled: true,
            value: value,
            min: 1,
            max: 9,
            step: 1,
            slide: function( event, ui ) {
                var rightOpacity = (((ui.value-1) * 0.08) + 0.36).toFixed(2);
                var leftOpacity = (1 - ((ui.value-1) * 0.08)).toFixed(2);
                $(ui.handle).parent().parent().next().css('opacity', rightOpacity);
                $(ui.handle).parent().parent().prev().css('opacity', leftOpacity);
            }
        })
    });

    $('.order1').on('mouseover', function() {
        $('img', this).attr('src', '/img/order1_hover.png');
    })
    $('.order1').on('mouseout', function() {
        $('img', this).attr('src', '/img/order1.png');
    })

    $('.order2').on('mouseover', function() {
        $('img', this).attr('src', '/img/order2_hover.png');
    })
    $('.order2').on('mouseout', function() {
        $('img', this).attr('src', '/img/order2.png');
    })

    $('.time').timeago();

    $('.print-link').on('mouseover', function() {
        $('img', this).attr('src', '/img/print_brief_button2.png');
    })
    $('.print-link').on('mouseout', function() {
        $('img', this).attr('src', '/img/print_brief_button.png');
    })

    $('.fav-plus').on('mouseover', function() {
        $('img', this).attr('src', '/img/plusb_2.png');
    })
    $('.fav-plus').on('mouseout', function() {
        $('img', this).attr('src', '/img/plusb.png');
    })

    $('.all-pitches-link').on('mouseover', function() {
        $('img', this).attr('src', '/img/all_pitches_2.png');
    })
    $('.all-pitches-link').on('mouseout', function() {
        $('img', this).attr('src', '/img/all_pitches.png');
    })

    $('.next-pitch-link').on('mouseover', function() {
        $('img', this).attr('src', '/img/next_2.png');
    })
    $('.next-pitch-link').on('mouseout', function() {
        $('img', this).attr('src', '/img/next.png');
    })

    $('#client-only-toggle').change(function() {
        if($(this).attr('checked')) {
            $.each($('section[data-type="designer"]', '.messages_gallery'), function(index, object) {
                var comment = $(object);
                comment.hide();
                var separator = comment.next('.separator');
                if((separator) && (separator.length == 1)) {
                    separator.hide();
                }
            })
        }else {
            $.each($('section[data-type="designer"]', '.messages_gallery'), function(index, object) {
                var comment = $(object);
                comment.show();
                var separator = comment.next('.separator');
                if((separator) && (separator.length == 1)) {
                    separator.show();
                }
            })
        }
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

    function preload(arrayOfImages) {
        $(arrayOfImages).each(function(){
            $('<img/>')[0].src = this;
            // Alternatively you could use:
            // (new Image()).src = this;
        });
    }

// Usage:
/*
    preload([

    ]);
*/
    
    fetchPitchComments();
    enableToolbar();
    warningModal(); // See app.js
});
