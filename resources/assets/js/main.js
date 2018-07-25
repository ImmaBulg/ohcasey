function inWindow(item, classItem) {
    var scrollTop = $(window).scrollTop(),
        windowHeight = $(window).height(),
        currentEls = $(item),
        result = [];

    currentEls.each(function () {
        var el = $(this),
            additionalOffset = -100,
            offset = el.offset().top;

        if (offset >= scrollTop && offset < scrollTop + windowHeight + additionalOffset) {
            result.push($(this).closest('.js-col-item')[0]);
        }
    });

    return $(result);
}
$(document).ready(function () {
    if ($(window).width() <= 991) {
        if (!$('.top-line').hasClass('is-hidden')) {
            $('.inner').css('margin-top', parseInt($('.top-line').height() + 50))
        } else {
            // $('.inner').css('margin-top', '30px');
        }
        $(".header").sticky({
            topSpacing: (!$('.top-line').hasClass('is-hidden')) ? $('.top-line').height() : 0,
            zIndex: 101
        }).on('sticky-end', function () {
            $('.header').css('width', $(window).width());
        });
    } else {
        $(".header").unstick();
    }

    document.getElementById('order-success') && window.metrikaGoals && window.metrikaGoals.shopCartSubmitted();
});

$(window).resize(function () {
    if ($(window).width() <= 991) {
        if ($('.top-line').hasClass('is-hidden')) {
            $(".header").sticky({
                topSpacing: 0,
                zIndex: 101
            }).on('sticky-end', function () {
                $('.header').css('width', $(window).width());
            });
        } else {
            if (!$('.top-line').hasClass('is-hidden')) {
                $('.inner').css('margin-top', $('.top-line').height())
            } else {
                // $('.inner').css('margin-top', '30px');
            }
            $(".header").sticky({
                topSpacing: (!$('.top-line').hasClass('is-hidden')) ? $('.top-line').height() : 0,
                zIndex: 101
            }).on('sticky-end', function () {
                $('.header').css('width', $(window).width());
            });
        }
    } else {
        $(".header").unstick();
    }
});

$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});


function fixOrder() {
    console.log('fix order');
    var totalOrderPos = $('.js-total-order').offset(),
        heightTotalOrder = $('.js-total-order').height(),
        footerPos = $('.js-footer').offset();

    $(window).on('scroll', function () {
        if ($(window).scrollTop() > totalOrderPos.top - 30) {
            $('.js-total-order').css({
                'position': 'fixed',
                'top': '30px',
                'bottom': 'auto'
            });
        } else {
            $('.js-total-order').css('position', 'static');
        }

        if ($(window).scrollTop() + heightTotalOrder > footerPos.top - 130) {
            $('.js-total-order').css({
                'position': 'absolute',
                'top': 'auto',
                'bottom': '45px'
            });
        }
    });

    $(window).scroll();
}

function calcImgCollect() {
    if ($('.js-collection').length) {
        var collection = $('.js-collection');
        collection.find('.js-img').each(function (i, item) {
            $(item).height($(item).width());
        });
    }
}

function calcImgCategory() {
    if ($('.js-collection-category').length) {
        if ($(window).width() > 480) {
            var collection = $('.js-collection-category');
            collection.find('.js-img').each(function (i, item) {
                $(item).height($(item).width());
            });
        } else {
            $('.js-img').height('auto');
        }
    }
}

$(function () {
    calcImgCollect();
    calcImgCategory();

    var collectItems = inWindow('.js-col-item .collection__caption');
    collectItems.addClass('is-visible');

    $(window).on('scroll', function (e) {
        var collectItems = inWindow('.js-col-item .collection__caption');
        collectItems.addClass('is-visible');
    });

    $(window).scroll();

    $('.js-scrollbar').mCustomScrollbar({
        axis: 'x',
        scrollInertia: 400
    });

    /*$('.js-nav-link').on('click', function (e) {
     if ($(this).hasClass('is-opened')) {
     $(this).removeClass('is-opened');
     $(this).closest('.js-nav-item').find('.js-nav-drop').slideUp(300);
     } else {
     $('.js-nav-drop').slideUp('fast');
     $('.js-nav-link').removeClass('is-opened');
     $(this).addClass('is-opened');
     $(this).closest('.js-nav-item').find('.js-nav-drop').slideDown(300);
     }
     e.preventDefault();
     });*/

    var timeout;
    $(window).resize(function () {
        calcImgCollect();
        calcImgCategory();

        clearTimeout(timeout);
        timeout = setTimeout(function () {
            if ($('.js-total-order').length) {
                $(window).off('scroll');
            }
            $('.js-nav-link').off('click');

            if (window.innerWidth > 680) {
                if ($('.js-total-order').length) {
                    fixOrder();
                }
            }

            if (window.innerWidth < 991) {
                console.log('init click menu link');
                $('.js-nav-link').on('click', function (e) {
                    if ($(this).hasClass('is-opened')) {
                        $(this).removeClass('is-opened');
                        $(this).closest('.js-nav-item').find('.js-nav-drop').slideUp(300);
                    } else {
                        $('.js-nav-drop').slideUp('fast');
                        $('.js-nav-link').removeClass('is-opened');
                        $(this).addClass('is-opened');
                        $(this).closest('.js-nav-item').find('.js-nav-drop').slideDown(300);
                    }
                    e.preventDefault();
                });
            }

        }, 200);
    });

    $('.js-burger').on('click', function (e) {
        $('.js-nav').toggleClass('is-visible');
        $(this).toggleClass('is-closable');
        $('body').toggleClass('is-nav');
        $('.js-nav-drop').slideUp('fast');
        $('.js-nav-link').removeClass('is-opened');
        e.preventDefault();
    });

    $('.js-close-top-line').on('click', function (e) {
        var now = new Date(),
            time = now.getTime();

        time += 3600 * 1000 * 72;
        now.setTime(time);
        document.cookie = 'topline=closed; expires=' + now.toUTCString() + '; path=/';

        $(this).closest('.js-top-line').addClass('is-hidden');
        $(".header").unstick();
        $('.inner').css('margin-top', '0');
        $(".header").sticky({
            topSpacing: 0,
            zIndex: 101
        }).on('sticky-end', function () {
            $('.header').css('width', $(window).width());
        });
        e.preventDefault();
    });

    function getCookie(name) {
        var matches = document.cookie.match(new RegExp(
            "(?:^|; )" + name.replace(/([\.$?*|{}\(\)\[\]\\\/\+^])/g, '\\$1') + "=([^;]*)"
        ));
        return matches ? decodeURIComponent(matches[1]) : undefined;
    }

    if (!getCookie('topline')) {
        $('.js-top-line').removeClass('is-hidden');
    }

    // if ($('.js-slider-for').length) {
    //     $('.js-slider-for').slick({
    //         slidesToShow: 1,
    //         slidesToScroll: 1,
    //         arrows: false,
    //         fade: true,
    //         asNavFor: '.js-slider-nav',
    //         responsive: [
    //             {
    //                 breakpoint: 700,
    //                 settings: {
    //                     dots: true,
    //                     fade: false
    //                 }
    //             }
    //         ]
    //     });

    //     $('.js-slider-nav').slick({
    //         slidesToShow: 4,
    //         slidesToScroll: 1,
    //         asNavFor: '.js-slider-for',
    //         dots: false,
    //         arrows: false,
    //         infinite: true,
    //         centerMode: false,
    //         variableWidth: true,
    //         focusOnSelect: true
    //     });
    // }

    if ($('.js-select').length) {
        $('.js-select').select2({
            minimumResultsForSearch: -1
        });

        $('.js-select').on('select2:closing', function (evt) {
            $('.select2-results__options').mCustomScrollbar("destroy");
        });

        $('.js-select').on('select2:open', function (evt) {
            // var _this = $(this);
            // $('.drop-child').parent().addClass('drop-address').width($(evt.target).siblings('.select2').width());

            setTimeout(function () {
                $('.select2-results__options').mCustomScrollbar({
                    axis: 'y',
                    scrollInertia: 400
                });

                $(document).scroll();
            }, 1);
        });
    }

    if ($('.js-select-item').length) {
        $('.js-select-item').select2({
            dropdownCssClass: 'drop-item',
            minimumResultsForSearch: -1
        });

        $('.js-select-item').on('select2:closing', function (evt) {
            $('.select2-results__options').mCustomScrollbar("destroy");
        });

        $('.js-select-item').on('select2:open', function (evt) {
            // var _this = $(this);
            // $('.drop-child').parent().addClass('drop-address').width($(evt.target).siblings('.select2').width());

            setTimeout(function () {
                $('.select2-results__options').mCustomScrollbar({
                    axis: 'y',
                    scrollInertia: 400
                });

                $(document).scroll();
            }, 1);
        });
    }

    $('.js-color').on('click', function (e) {
        $('.js-color').removeClass('is-active');
        $(this).addClass('is-active');
    });

    $('.js-cover').on('click', function (e) {
        $('.js-cover').removeClass('is-active');
        $(this).addClass('is-active');
    });

    $(document).on('click', '.js-popup-open', function (e) {
        $('body').addClass('is-nav');
        $('.js-popup').removeClass('is-visible');
        $('.js-popup[data-popup="' + $(this).attr('data-popup') + '"]').addClass('is-visible');
        e.preventDefault()
    });

    $(document).on('click', '.js-popup-close', function (e) {
        $('body').removeClass('is-nav');
        $(this).closest('.js-popup').removeClass('is-visible');
        e.preventDefault()
    });

    var url = location.href.replace(location.search, '');
    var $element = $('ul.main-nav a').filter(function () {
        return this.href == url;
    }).addClass('main-nav__link--contcructor');
    if ($element) {
        $('.js-burger').click(function () {
            // open 1 level
            $element
                .closest('li.main-nav__item')
                .find('.main-nav__link')
                .addClass('is-opened')
                .closest('.main-nav__item')
                .trigger('click')
                .find('.drop-nav')
                .show();
        });
    }

    if ($('#mapContact').length) {
        initGoogleMap();
    }

    $('.js-tab-link').on('click', function (e) {
        $('.js-tab-link').removeClass('is-active');
        $('.js-tab-content').removeClass('is-active');
        $(this).addClass('is-active');
        $('.js-tab-content[data-tab="' + $(this).attr('data-tab') + '"]').addClass('is-active');
        e.preventDefault();
    });

    $('.js-tab-head').on('click', function (e) {
        if ($(this).hasClass('is-active')) {
            $(this).removeClass('is-active');
            $(this).closest('.js-tab-column').find('.js-tab-body').removeClass('is-active');
        } else {
            $('.js-tab-head').removeClass('is-active')
            $('.js-tab-body').removeClass('is-active');
            $(this).addClass('is-active');
            $(this).closest('.js-tab-column').find('.js-tab-body').addClass('is-active');
        }
    });
    $(window).scroll();
    $(window).resize();
});

// Google
var gMap;
function initGoogleMap() {
    // var directionsDisplay = new google.maps.DirectionsRenderer({suppressMarkers: true});

    gMap = new google.maps.Map(document.getElementById('mapContact'), {
        center: {lat: 55.740123, lng: 37.661660},
        zoom: 16,
        disableDefaultUI: true
    });

    var icons = {
        start: new google.maps.MarkerImage('/img/layout/ic-pin.png'),
        end: new google.maps.MarkerImage('/img/layout/ic-pin-red.png')
    };

    var directionsService = new google.maps.DirectionsService;

    var routes = [{
        origin: new google.maps.LatLng(55.741161, 37.656735), //точка старта
        destination: new google.maps.LatLng(55.740214, 37.661694), //точка финиша
        travelMode: google.maps.DirectionsTravelMode.WALKING //режим прокладки маршрута
    }, {
        origin: new google.maps.LatLng(55.742696, 37.653580), //точка старта
        destination: new google.maps.LatLng(55.740214, 37.661694), //точка финиша
        travelMode: google.maps.DirectionsTravelMode.WALKING, //режим прокладки маршрута
        waypoints: [{location: new google.maps.LatLng(55.742545, 37.654599), stopover: false}],
    }];

    function makeMarker(position, icon) {
        new google.maps.Marker({
            position: position,
            map: gMap,
            icon: icon
        });
    }

    var directionsDisplay;

    routes.forEach(function (route) {
        var request = {
            origin: route.origin,
            destination: route.destination,
            travelMode: route.travelMode,
            waypoints: route.waypoints
        };

        directionsDisplay = new google.maps.DirectionsRenderer({suppressMarkers: true});
        directionsDisplay.setMap(gMap);

        directionsService.route(request, function (result, status) {
            if (status == google.maps.DirectionsStatus.OK) {
                directionsDisplay.setDirections(result);

                var leg = result.routes[0].legs[0];
                makeMarker(leg.start_location, icons.start);
                makeMarker(leg.end_location, icons.end);
            }
        });
    });

    directionsDisplay.setMap(gMap);
}