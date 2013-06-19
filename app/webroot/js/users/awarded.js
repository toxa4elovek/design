$(document).ready(function() {

    $('.photo_block').live('mouseenter', function(){
        $('a.end-pitch-hover', this).fadeIn(300);
    })

    $('.photo_block').live('mouseleave', function(){
        $('a.end-pitch-hover', this).fadeOut(300);
    })
});