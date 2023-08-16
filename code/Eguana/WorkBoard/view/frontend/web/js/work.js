define(['jquery',
    'slick',
    'domReady!'
],function ($) {
    "use strict";

    $(document).ready(function() {
        $('.work-wrapper').slick({
            infinite: true,
            slidesToShow: 1,
            speed: 300,
            autoplay: false,
            useTransform: false,
            prevArrow: $('.custom-pager .prev'),
            nextArrow: $('.custom-pager .next')
        });

        $('.custom-pager .pause').on('click', function () {
            $('.work-wrapper').slick('slickPause');
            $('.custom-pager .buttons > button').removeClass('active');
            $('.custom-pager .play').addClass('active');
        });

        $('.custom-pager .play').on('click', function () {
            $('.work-wrapper').slick('slickPlay');
            $('.custom-pager .buttons > button').removeClass('active');
            $('.custom-pager .pause').addClass('active');
            $('.slick-active .work-title').addClass('active');
        });

        $('.work-filter > button').on('click', function () {
            const category = $(this).attr('id'),
                listClass = '.work-list li.'+category;

            $('.work-filter > button').removeClass('active');
            $('.work-list li').removeClass('active');
            $(this).addClass('active');

            if (category === 'all') {
                $('.work-list li').addClass('active');
            } else {
                $(listClass).addClass('active');
            }
        });

        $('.work-wrapper').on('afterChange', function(event, slick, currentSlide, nextSlide){
            $('.work-wrapper .work-title').removeClass('active');
            $("[data-slick-index='" + currentSlide + "'] .work-title").addClass('active');
        });
    });
});