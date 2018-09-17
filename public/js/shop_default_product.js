webpackJsonp([3],{

/***/ 294:
/***/ (function(module, exports, __webpack_require__) {

"use strict";


var _vue = __webpack_require__(4);

var _vue2 = _interopRequireDefault(_vue);

var _axios = __webpack_require__(6);

var _axios2 = _interopRequireDefault(_axios);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

_vue2.default.component('modal', {
    template: '#modal-template',
    props: ['cart'],
    mounted: function mounted() {
        $('.js-scroll-popup').mCustomScrollbar({
            axis: 'y',
            scrollInertia: 400
        });
    }
});

var processingAjaxRequestSbmToCart = null;

var app = new _vue2.default({
    el: '#app',
    data: {
        cart: [],
        showModal: false
    },
    methods: {
        addToCart: function addToCart() {
            var self = this;

            var passToCart = {
                offer_id: $('#offer_id').val(),
                item_count: 1,
                item_sku: 'product'
            };

            var loadPopupData = function loadPopupData() {
                var requestConfig = {
                    headers: {
                        "Accept": "application/json",
                        "X-Requested-With": "XMLHttpRequest"
                    }
                };

                _axios2.default.get('/cart', requestConfig).then(function (response) {
                    self.cart = [];
                    
                    if (response.data.data.cartSetCases.length) {   
                        self.cart = self.cart.concat(response.data.data.cartSetCases);
                    }
                    if (response.data.data.cartSetProducts.length) {
                        self.cart = self.cart.concat(response.data.data.cartSetProducts);
                    }

                    //self.toggleModal();
					location.href = '/cart';
                }).catch(function (response) {
                    console.error(response);
                });
            };

            if (processingAjaxRequestSbmToCart) {
                alert('Дождитесь добавления в корзину');
                return;
            }

            processingAjaxRequestSbmToCart = $.ajax({
                method: 'POST',
                url: '/cart/put/product/1',
                data: passToCart,
                success: function success(answer) {
                    processingAjaxRequestSbmToCart = null;
                    $('#offer-in-cart').show();
                    $('.js-add-to-cart-container').hide();
                    var currentVal = parseInt($('.js-cart-counter').val());
                    if (isNaN(currentVal)) {
                        currentVal = 0;
                    }
                    $('.js-cart-counter').val(currentVal + 1);
                    loadPopupData();
                },
                error: function error() {
                    alert('ошибка добавления в корзину');
                    processingAjaxRequestSbmToCart = null;
                }
            });
        },
        toggleModal: function toggleModal() {
            this.showModal = true;
            $('body').addClass('is-nav');
            $('.js-scroll-popup').mCustomScrollbar({
                axis: 'y',
                scrollInertia: 400
            });
        }
    }
});

$(function () {
    if ($('.js-slider-for').length) {
        $('.js-slider-for').slick({
            slidesToShow: 1,
            slidesToScroll: 1,
            arrows: false,
            fade: true,
            asNavFor: '.js-slider-nav',
            responsive: [{
                breakpoint: 700,
                settings: {
                    dots: true,
                    fade: false
                }
            }]
        });

        $('.js-slider-nav').slick({
            slidesToShow: 4,
            slidesToScroll: 1,
            asNavFor: '.js-slider-for',
            dots: false,
            arrows: false,
            infinite: true,
            centerMode: false,
            variableWidth: true,
            focusOnSelect: true
        });
    }

    var processingAjaxRequestSbmToCart = null;
    $('.js-add-to-cart').click(function () {
        var passToCart = {
            offer_id: $('#offer_id').val(),
            item_count: 1,
            item_sku: 'product'
        };

        if (processingAjaxRequestSbmToCart) {
            alert('Дождитесь добавления в корзину');
            return;
        }

        processingAjaxRequestSbmToCart = $.ajax({
            method: 'POST',
            url: '/cart/put/product/1',
            data: passToCart,
            success: function success(answer) {
                processingAjaxRequestSbmToCart = null;
                $('#offer-in-cart').show();
                $('.js-add-to-cart-container').hide();
                var currentVal = parseInt($('.js-cart-counter').val());
                if (isNaN(currentVal)) {
                    currentVal = 0;
                }
                $('.js-cart-counter').val(currentVal + 1);
            },
            error: function error() {
                alert('ошибка добавления в корзину');
                processingAjaxRequestSbmToCart = null;
            }
        });
    });
});

/***/ })

},[294]);