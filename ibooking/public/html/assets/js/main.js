(function ($) {
    'use strict';

    /**
     * Initializes our event handlers.
     */
    function ibookingInit() {
        $(document).ready(function () {
            ibookingHeroSlider();
            ibookingSearchForm();
            ibookingSearchFormAdvanced();
            ibookingTooltip();
            ibookingCarouselS1();
            ibookingCarouselS2();
            ibookingPriceRangeSlider();
            ibookingPaymentCheckoutDropdown();
            ibookingCarouselWithLighbox();
            booinstaCopyToClipboard();
        });
    }

    window.ibookingHeroSlider = function() {
        var dataPluginSlick = $('[data-plugin="slick"]');
        dataPluginSlick.each(function(){
            var t = $(this);
            if (t.hasClass('slick-initialized')) {
                t.slick('unslick');
            }
            t.not('.slick-initialized').slick({
                prevArrow: "<div class='slick-prev'><i class='fal fa-arrow-left'></i></div>",
                nextArrow: "<div class='slick-next'><i class='fal fa-arrow-right'></i></div>",
                dots: true
            });
        });
    }

    function ibookingCarouselS1() {
        let slickElement = $(".carousel-s1");
        if(slickElement.length) {
            let slidesToShow = 4;
            slickElement.on('init', function (event, slick) {
                let slideCount = slick.slideCount;
                if (slideCount < slidesToShow) {
                    $(this).find(".slick-track").addClass("mx-0");
                }
            });
            slickElement.slick({
                infinite: true,
                slidesToShow: slidesToShow,
                slidesToScroll: 1,
                prevArrow: "<div class='slick-prev slick-arrow--edge'><i class='fal fa-arrow-left'></i></div>",
                nextArrow: "<div class='slick-next slick-arrow--edge'><i class='fal fa-arrow-right'></i></div>",
                responsive: [
                    {
                        breakpoint: 992,
                        settings: {
                            slidesToShow: 3,
                            slidesToScroll: 3
                        }
                    },
                    {
                        breakpoint: 768,
                        settings: {
                            slidesToShow: 2,
                            slidesToScroll: 2
                        }
                    }
                ]
            });
        }
    }
    function ibookingCarouselS2() {
        let slickElement = $(".carousel-s2");
        if(slickElement.length) {
            slickElement.slick({
                infinite: true,
                slidesToShow: 1,
                slidesToScroll: 1,
                dots: true,
                arrows: false,
                autoplay: true,
                autoplaySpeed: 5000,
                prevArrow: "<div class='slick-prev slick-arrow--edge'><i class='fal fa-arrow-left'></i></div>",
                nextArrow: "<div class='slick-next slick-arrow--edge'><i class='fal fa-arrow-right'></i></div>",
            });
        }
    }
    function ibookingCarouselWithLighbox() {
        let slickElement = $(".gmz-carousel-with-lightbox");
        if(slickElement.length) {
            let slidesToShow = 3;
            var slideCount = slickElement.data("count");
            if (slideCount < slidesToShow){
                slidesToShow = slideCount;
            }

            slickElement.slick({
                infinite: true,
                slidesToShow: slidesToShow,
                centerMode: true,
                arrows: true,
                centerPadding: '0px',
                prevArrow: "<div class='slick-prev'><i class='fal fa-arrow-left'></i></div>",
                nextArrow: "<div class='slick-next'><i class='fal fa-arrow-right'></i></div>",
                responsive: [
                    {
                        breakpoint: 992,
                        settings: {
                            slidesToShow: 2,
                            slidesToScroll: 2
                        }
                    },
                    {
                        breakpoint: 768,
                        settings: {
                            slidesToShow: 1,
                            slidesToScroll: 1,
                            arrows: false,
                            dots: true,
                        }
                    }
                ]
            });
            slickElement.magnificPopup({
                type: 'image',
                delegate: 'a:not(.slick-cloned)',
                gallery: {
                    enabled: true
                },
                callbacks: {
                    open: function() {
                    },
                    beforeClose: function() {
                        if (slidesToShow !== slideCount){
                            slickElement.slick('slickGoTo', parseInt(this.index));
                        }
                    }
                }
            });
            slickElement.find('.slick-cloned').on("click", function (e) {
                e.preventDefault();
                $('.slick-current').click();
            });
        }
        // if only 1 image
        var singleImage = $('.gmz-single-image-with-lightbox');
        if(singleImage.length) {
            singleImage.magnificPopup({
                type: 'image',
                delegate: 'a',
            });
        }
    }
    function ibookingSearchForm() {
        $('.search-form').each(function (e) {
            let t = $(this),
                form = t.closest('form'),
                checkIn = $('.check-in-field', t),
                checkInRender = $('.check-in-render', t),
                checkOut = $('.check-out-field', t),
                checkInOutRender = $('.check-out-render', t),
                startTime = $('.start-time-field', t),
                endTime = $('.end-time-field', t),
                input = $('.check-in-out-field', t);

            var sameDate = true;
            var dateGroup  = false;
            if(typeof input.data('same-date') !== 'undefined'){
                sameDate = input.data('same-date');
            }

            if(typeof input.data('date-group') !== 'undefined'){
                dateGroup = input.data('date-group');
            }

            var minDate = new Date();
            if(typeof input.data('min-date') !== 'undefined'){
                minDate = input.data('min-date');
            }

            let options = {
                autoApply: true,
                minDate: minDate,
                sameDate: sameDate
            };

            if(typeof localeDateRangePicker === 'object'){
                options.locale = localeDateRangePicker;
            }

            if(checkIn.val() === ''){
                var currentDate = new Date();
                options.startDate = currentDate;
                if(!sameDate) {
                    options.endDate = currentDate.setDate(currentDate.getDate() + 1);;
                }
            }

            input.daterangepicker(options,
                function (start, end, label) {
                    if (start !== null && end !== null) {
                        checkIn.val(start.format('YYYY-MM-DD'));

                        checkOut.val(end.format('YYYY-MM-DD'));

                        if(dateGroup == true){
                            checkInRender.text(start.format(checkInRender.data('date-format')) + ' - ' + end.format(checkInRender.data('date-format')));
                        }else{
                            checkInRender.text(start.format(checkInRender.data('date-format')));
                            checkInOutRender.text(end.format(checkInOutRender.data('date-format')));
                        }
                        input.trigger('daterangepicker_change', [start, end, label]);
                    }
                });
            checkInRender.parent().on('click',function () {
                input.trigger('click');
            });
            checkInOutRender.parent().on('click',function () {
                input.trigger('click');
            });

            var checkInTime = $('.check-in-time-field', t),
                checkInTimeRender = $('.check-in-time-render', t),
                inputTime = $('.check-in-out-time-field', t);
            let timeOptions = {
                autoApply: true,
                singleDatePicker: true,
                minDate: new Date()
            };

            if(typeof localeDateRangePicker === 'object'){
                timeOptions.locale = localeDateRangePicker;
            }

            inputTime.daterangepicker(timeOptions,
                function (start, end, label) {
                    if (start !== null && end !== null) {
                        checkInTime.val(start.format('YYYY-MM-DD'));
                        checkInTimeRender.text(start.format(checkInTimeRender.data('date-format')));
                        inputTime.trigger('daterangepicker_change', [start, end, label]);
                    }
                });
            checkInTimeRender.parent().on('click',function () {
                inputTime.trigger('click');
            });
        });
    }
    function ibookingSearchFormAdvanced(){
        var advanced = $('.search-form__advanced');
        var btn = $('.search-form__more');
        btn.off().on("click", function () {
            advanced.stop().slideToggle(400,function () {
                if(btn.find(".fal.fa-search-plus").length){
                    btn.find(".fal").removeClass("fa-search-plus").addClass("fa-search-minus");
                }else{
                    btn.find(".fal").removeClass("fa-search-minus").addClass("fa-search-plus");
                }
            });
        });
    }
    function ibookingTooltip(){
        $('[data-toggle="tooltip"]').tooltip({
            trigger : 'hover'
        })
    }
    function ibookingPriceRangeSlider(){
        $('[name="price_range"]').each(function(){
            var t = $(this);
            t.ionRangeSlider({
                type: "double",
                onFinish: function (data) {
                    t.trigger('gmz_range_changed');
                },
            });
        });
    }
    function ibookingPaymentCheckoutDropdown(){

        $("input[name=payment_method]").on('change', function () {
            $(".payment-item .card").hide('fast');
            $(".payment-item .check-payment").removeClass('active');

            var currentPaymentItem = $(this).parents('.payment-item');
            currentPaymentItem.find('.card').show('fast');
            currentPaymentItem.find('.check-payment').addClass('active');
        });
    }
    function booinstaCopyToClipboard() {
        $(".btn-copy").on("click", function () {

            var value = $(this).text();

            var el = document.createElement('textarea');
            el.value = value;
            el.setAttribute('readonly', '');
            el.style.position = 'absolute';
            el.style.left = '-9999px';
            document.body.appendChild(el);
            el.select();
            document.execCommand('copy');
            document.body.removeChild(el);

            $(this).attr('data-original-title','copied!');
            $(this).tooltip('hide').tooltip('show');

            $(this).on("mouseleave", function () {
                $(this).attr('data-original-title','copy');
            })
        });
    }

    ibookingInit();

})(jQuery);