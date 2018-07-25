import Vue from 'vue'
import axios from 'axios'

Vue.component('modal', {
  template: '#modal-template',
  props: ['cart'],
  mounted() {
    $('.js-scroll-popup').mCustomScrollbar({
        axis: 'y',
        scrollInertia: 400
    });
  }
});

var processingAjaxRequestSbmToCart = null;

var app = new Vue({
    el: '#app',
    data: {
        cart: [],
        showModal: false
    },
    methods: {
        addToCart(){
            var self = this;

            var passToCart = {
                offer_id: $('#offer_id').val(),
                item_count: 1,
                item_sku: 'product',
            };

            var loadPopupData = function() {
                var requestConfig = {
                    headers: {
                        "Accept": "application/json",
                        "X-Requested-With": "XMLHttpRequest"
                    }
                };

                axios.get('/cart', requestConfig).then(response => {
                    self.cart = [];

                    if (response.data.data.cartSetCases.length) {
                        self.cart = self.cart.concat(response.data.data.cartSetCases);
                    }
                    if (response.data.data.cartSetProducts.length) {
                        self.cart = self.cart.concat(response.data.data.cartSetProducts);
                    }

                    self.toggleModal();
                }).catch(response => {
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
                success: function (answer) {
                    processingAjaxRequestSbmToCart = null;
                    $('#offer-in-cart').show();
                    $('.js-add-to-cart-container').hide();
                    var currentVal = parseInt($('.js-cart-counter').val());
                    if (isNaN(currentVal)) {
                        currentVal = 0;
                    }
                    $('.js-cart-counter').val((currentVal + 1));
                    loadPopupData();
                },
                error: function () {
                    alert('ошибка добавления в корзину');
                    processingAjaxRequestSbmToCart = null;
                },
            });
        },
        toggleModal() {
            this.showModal = true;
            $('body').addClass('is-nav');
            $('.js-scroll-popup').mCustomScrollbar({
                axis: 'y',
                scrollInertia: 400
            });
        }
    }
})

$(function () {
    if ($('.js-slider-for').length) {
        $('.js-slider-for').slick({
            slidesToShow: 1,
            slidesToScroll: 1,
            arrows: false,
            fade: true,
            asNavFor: '.js-slider-nav',
            responsive: [
                {
                    breakpoint: 700,
                    settings: {
                        dots: true,
                        fade: false
                    }
                }
            ]
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
    $('.js-add-to-cart').click(function() {
        var passToCart = {
            offer_id: $('#offer_id').val(),
            item_count: 1,
            item_sku: 'product',
        };

        if (processingAjaxRequestSbmToCart) {
            alert('Дождитесь добавления в корзину');
            return;
        }

        processingAjaxRequestSbmToCart = $.ajax({
            method: 'POST',
            url: '/cart/put/product/1',
            data: passToCart,
            success: function (answer) {
                processingAjaxRequestSbmToCart = null;
                $('#offer-in-cart').show();
                $('.js-add-to-cart-container').hide();
                var currentVal = parseInt($('.js-cart-counter').val());
                if (isNaN(currentVal)) {
                    currentVal = 0;
                }
                $('.js-cart-counter').val((currentVal + 1));
            },
            error: function () {
                alert('ошибка добавления в корзину');
                processingAjaxRequestSbmToCart = null;
            },
        })
    });
});