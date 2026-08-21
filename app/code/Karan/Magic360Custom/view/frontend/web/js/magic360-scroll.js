define([
    'jquery'
], function ($) {
    'use strict';

    return function (config, element) {
        var $element = $(element);
        var lastScrollTop = 0;
        var rotation = 0;

        $(window).on('scroll', function () {
            var st = $(window).scrollTop();
            var delta = st - lastScrollTop;
            lastScrollTop = st;

            rotation = (rotation + delta * 0.8) % 360;
            $element.css('transform', 'rotate(' + rotation + 'deg)');
        });
    };
});
