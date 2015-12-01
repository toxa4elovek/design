'use strict';

;(function () {
    var maxHeight = 0;
    var counter = 0;
    var list = [];
    var advantagesBlockChilder = $('#advantages').children();
    var total = advantagesBlockChilder.length;
    $.each(advantagesBlockChilder, function (index, object) {
        if ($('h2', object).height() > maxHeight) {
            maxHeight = $('h2', object).height();
        }
        list.push(object);
        counter += 1;
        total -= 1;
        if (counter == 3 || total == 0) {
            $.each(list, function (index, object) {
                $('h2', object).height(maxHeight);
            });
            list = [];
            maxHeight = 0;
            counter = 0;
        }
    });
})();