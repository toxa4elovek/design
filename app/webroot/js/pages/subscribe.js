'use strict';

;(function ($) {
    $('#scroll-button').on('click', function () {
        $.scrollTo($('section.lp-table'), { duration: 300 });
        return false;
    });
})($);