$(document).ready(function() {
    $.each($('.designer_wrapper'), function(idx, obj) {
        if ($(obj).width() < $('ul', $(obj)).first().width()) {
            $('.scroll_right', $(obj)).show();
        }
    });
    
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
            if (-$el.position().left > $wrapper.width() + 10) {
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
            if (-$el.position().left < $wrapper.width()) {
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
});
