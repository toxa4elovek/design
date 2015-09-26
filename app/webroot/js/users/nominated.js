$(document).ready(function() {

    $('.photo_block').mouseenter(function(){
        $('a.end-pitch-hover', this).fadeIn(300);
    })

    $('.photo_block').mouseleave(function(){
        $('a.end-pitch-hover', this).fadeOut(300);
    })
});