$(function() {
    $('.js-delivery').on('change', function(e) {
        $('.js-delivery-hidden').slideUp(200);
        if ($(this).prop('checked')) {
            $(this).closest('.js-delivery-item').find('.js-delivery-hidden').slideDown(200);
        }
    });

    $('.js-promo-link').on('click', function(e) {
        $(this).closest('.js-promo').addClass('is-input');
        e.preventDefault();
    });

    if ($('#map').length) {
        ymaps.ready(initYandex);
    }

    $('.js-order-btn').on('click', function(e) {
        var paymentMethodSelected = ($('.js-payment-input:checked').length > 0),
            validate = true;

        $('.js-payment-title,' +
            '#input-name,' +
            '#input-phone,' +
            '#input-email,' +
            '#input-deliveries,' +
            '#input_courier_moscow_address,' +
            '#input_pickpoint_address,' +
            '#input_courier_address,' +
            '#input_post_address' +
            '').removeClass('failure-validate');

        if (!$('#phone').val()) {
            $('#input-phone').addClass('failure-validate');
            validate = false;
        }

        if (!window.isShortShopCart) {
            if (!$('#name').val()) {
                $('#input-name').addClass('failure-validate');
                validate = false;
            }

            if (!$('#email').val()) {
                $('#input-email').addClass('failure-validate');
                validate = false;
            }

            if (!$('input[name=delivery_type]:checked').length) {
                $('#input-deliveries').addClass('failure-validate');
                validate = false;
            } else {
                switch ($('input[name=delivery_type]:checked').val()) {
                    case 'courier_moscow':
                        if (!$('input[name=courier_moscow_address]').val()) {
                            $('#input_courier_moscow_address').addClass('failure-validate');
                            validate = false;
                        }
                        break;
                    case 'pickpoint':
                        if (!$('#pickpoint_address').val()) {
                            $('#input_pickpoint_address').addClass('failure-validate');
                            validate = false;
                        }
                        break;
                    case 'courier':
                        if (!$('input[name=courier_address]').val()) {
                            $('#input_courier_address').addClass('failure-validate');
                            validate = false;
                        }
                        break;
                    case 'post':
                        if (!$('input[name=country]').val() || !$('input[name=post_code]').val() || !$('input[name=post_address]').val()) {
                            $('#input_post_address').addClass('failure-validate');
                            validate = false;
                        }
                        break;
                }
            }

            if (!paymentMethodSelected) {
                $('.js-payment-title').addClass('failure-validate').text('Выберите способ оплаты');
                validate = false;
            }
        }

        if (!validate) {
            var top = $('.failure-validate:first').offset().top;
            var body = $("html, body");
            body.stop().animate({scrollTop: top}, '500', 'swing');
            e.preventDefault();
            return false;
        }

        /** yandex && facebook reach goals**/
        // fbq('track', 'SiteOrder');
        // window.metrikaGoals.shopCartSubmitted(function () {
        //     $('#shop-form').submit();
        // });

        $('#shop-form').submit();

        return false;
    });

    $('.js-payment-input').on('change', function(e) {
        if ($(this).prop('checked')) {
            $('.js-payment-title').css('color', '#1a1a1a').text('Оплата');
        }
        if ($('.js-payment-input-online').is(':checked')) {
            $('.js-order-btn').text('оплатить');
        } else {
            $('.js-order-btn').text('оформить заказ');
        }
    });

    var $selectCount = $('.js-count-select'),
        $removeCaseBtn  = $('.js-remove-case'),
        $removeProductBtn  = $('.js-remove-product'),
        $totalPriceContainer = $('.js-price-value'),
        $totalPriceContainer2 = $('.js-price-total'),
        $cartItemsCountContainer = $('.js-cart-counter'),
        $priceWithoutDiscountContainer = $('.js-price-without-discount'),
        $discountContainer = $('.js-discount-value'),
        $deliveryPriceContainer = $('.js-delivery-price');

    function updateCartInfo(data)
    {
        $totalPriceContainer.text(data.priceWithDiscount);
        $totalPriceContainer2.text(data.priceWithDiscount);
        $cartItemsCountContainer.text(data.cartCount);
        $priceWithoutDiscountContainer.text(data.priceValue);
        var discountAmount = (data.cart.promotion_code ? data.cart.promotion_code.code_discount : 0);
        if (!discountAmount) {
            $discountContainer.closest('.total-order__item').hide();
        } else {
            $discountContainer.closest('.total-order__item').show();
        }
        $discountContainer.text(discountAmount);
        $deliveryPriceContainer.text(data.cart.delivery_amount);
        $("#delivery_amount").val(data.cart.delivery_amount);
    }

    function ajaxUpdateCart()
    {
        $.ajax({
            url: '/shop/cart',
            type: 'GET',
            dataType: 'JSON',
            success: function (answer) {
                updateCartInfo(answer.data);
            }
        });
    }

    $selectCount.change(function () {
        var count = $(this).val();
        $selectCount.attr('disabled', 'disabled');
        $.ajax({
            url: $(this).data('update-count-url'),
            type: 'POST',
            data: {
                count: count
            },
            success: function (answer) {
                $selectCount.removeAttr('disabled');
                if (answer.result == 'success') {
                    updateCartInfo(answer.data);
                } else {
                    alert('Ошибка обновления количества чехлов.');
                }
            },
            error: function (answer) {
                alert('Ошибка обновления количества чехлов.');
                $selectCount.removeAttr('disabled');
            }
        });
    });

    $removeCaseBtn.click(function (e) {
        var $this = $(this);
        $this.attr('disabled', 'disabled');
        $.ajax({
            url: $(this).data('remove-url'),
            type: 'GET',
            success: function (answer) {
                $this.removeAttr('disabled');
                if (answer.result == 'success') {
                    $this.closest('.js-case-container').fadeOut();
                    updateCartInfo(answer.data);
                } else {
                    alert('Ошибка удаления чехла.');
                }
            },
            error: function (answer) {
                alert('Ошибка удаления чехла.');
                $this.removeAttr('disabled');
            }
        });
        e.preventDefault();
        return false;
    });

    $removeProductBtn.click(function (e) {
        var $this = $(this);
        $this.attr('disabled', 'disabled');
        $.ajax({
            url: $(this).data('remove-url'),
            type: 'GET',
            success: function (answer) {
                $this.removeAttr('disabled');
                if (answer.result == 'success') {
                    $this.closest('.js-case-container').fadeOut();
                    updateCartInfo(answer.data);
                } else {
                    alert('Ошибка удаления продукта.');
                }
            },
            error: function (answer) {
                alert('Ошибка удаления продукта.');
                $this.removeAttr('disabled');
            }
        });
        e.preventDefault();
        return false;
    });

    $('.js-promo-btn').on('click', function(e) {
        var $this = $(this);
        $this.attr('disabled', 'disabled');

        $.ajax({
            url: '/cart/code/apply/' + encodeURIComponent($("#promo-input").val()),
            type: 'POST',
            success: function (answer) {
                $this.removeAttr('disabled');
                if (!answer.code) {
                    $this.closest('.js-promo').removeClass('is-input').addClass('is-error');
                } else {
                    $this.closest('.js-promo').removeClass('is-error').addClass('is-success').removeClass('is-input');
                    ajaxUpdateCart();
                }
            },
            error: function (answer) {
                $this.removeAttr('disabled');
                $this.closest('.js-promo').removeClass('is-input').addClass('is-error');
            }
        });
        e.preventDefault();
        return false;
    });

    $('.js-remove-promo').on('click', function(e) {
        var $this = $(this);
        $this.attr('disabled', 'disabled');

        $.ajax({
            url: '/cart/code/remove',
            type: 'POST',
            success: function (answer) {
                $this.removeAttr('disabled');
                $this.closest('.js-promo').removeClass('is-error').removeClass('is-success').addClass('is-input');
                ajaxUpdateCart();
            },
            error: function (answer) {
                $this.removeAttr('disabled');
                $this.closest('.js-promo').removeClass('is-error').removeClass('is-success').addClass('is-input');
                ajaxUpdateCart();
            }
        });
        e.preventDefault();
        return false;
    });

    var $citySelect = $('.js-city');

    $citySelect.select2({
        dropdownCssClass: 'drop-child',
        ajax: {
            url: "/cdek/cities",
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return {
                    mask: params.term + '%',
                    page: params.page
                };
            },
            processResults: function (data, params) {
                var d = [];
                for (var i = 0, len = data.data.length; i < len; ++i) {
                    d.push({
                        id: data.data[i].city_id,
                        text: data.data[i].city_name_full
                    });
                }

                params.page = params.page || 1;

                setTimeout(function() {
                    $('.select2-results__options').mCustomScrollbar({
                        axis: 'y',
                        scrollInertia: 400
                    });
                    $(document).scroll();
                }, 10);

                return {
                    results: d,
                    pagination: {
                        more: (params.page * 30) < data.total
                    }
                };
            },
            cache: true
        },
        escapeMarkup: function (markup) { return markup; }, // let our custom formatter work
        minimumInputLength: 3,
        language: "ru",
        placeholder: 'Выберите город'
    });

    $citySelect.on('select2:open', function (evt) {
        var _this = $(this);
        $('.drop-child').parent().addClass('drop-address').width($(evt.target).siblings('.select2').width());
        setTimeout(function() {
            $(document).scroll();
        }, 1);
    });

    $citySelect.on('select2:closing', function (evt) {
        $('.select2-results__options').mCustomScrollbar("destroy");
    });

    $(document).on('keydown', '.select2-search__field', function() {
        $('.select2-results__options').mCustomScrollbar("destroy");
    });

    $(document).on('keyup', '.select2-search__field', function() {
        $(document).scroll();
    });

    $('.js-country').select2({
        dropdownCssClass: 'drop-child',
        ajax: {
            url: "/countries",
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return {
                    mask: params.term + '%',
                    page: params.page
                };
            },
            processResults: function (data, params) {
                var d = [];
                for (var i = 0, len = data.data.length; i < len; ++i) {
                    d.push({
                        id: data.data[i].country_iso,
                        text: data.data[i].country_name_ru
                    });
                }

                params.page = params.page || 1;

                setTimeout(function() {
                    $('.select2-results__options').mCustomScrollbar({
                        axis: 'y',
                        scrollInertia: 400
                    });
                    $(document).scroll();
                }, 10);

                return {
                    results: d,
                    pagination: {
                        more: (params.page * 30) < data.total
                    }
                };
            },
            cache: true
        },
        escapeMarkup: function (markup) { return markup; },
        minimumInputLength: 3,
        language: "ru",
        placeholder: 'Выберите страну'
    });

    $('.js-country').on('select2:open', function (evt) {
        var _this = $(this);
        $('.drop-child').parent().addClass('drop-address').width($(evt.target).siblings('.select2').width());
        setTimeout(function() {
            $(document).scroll();
        }, 1);
    });

    $('.js-country').on('select2:closing', function (evt) {
        $('.select2-results__options').mCustomScrollbar("destroy");
    });

    $('.js-country').on('select2:select', function () {
        $('#country').val($(this).val());
    });

    $('.js-pickpoint-city').on('select2:select', function () {
        updateCdekPickpoint($(this).val(), function () {
            $('.js-delivery-next').show().trigger('click');
        });
    });

    function updateDeliveryPrice()
    {
        var deliveryName   = $('input[name=delivery_type]:checked').val();
        var deliveryAmount = (parseInt($('.js-delivery-price-' + deliveryName).data('price')) || 0);
        $.ajax({
            url: '/shop/cart/update_delivery_info',
            data: {
                delivery_name: deliveryName,
                delivery_amount: deliveryAmount
            },
            type: 'POST',
            success: function (answer) {
                updateCartInfo(answer.data);
            }
        });
    }

    $citySelect.on('select2:select', function () {
        $('#city').val($(this).val());
        $('#country').val('RU');
        updateCdekCost($(this).val(), updateDeliveryPrice);
    });

    function updatePaymentMethodList()
    {
        $('.js-payment-method').hide();
        var deliveryMethodName = $('input[name=delivery_type]:checked').val();
        if (deliveryMethodName) {
            var methods = window.deliveryPaymentsMethod[deliveryMethodName];
            if (methods) {
                for (var i = 0; i < methods.length; i++) {
                    $('.js-payment-method-' + methods[i]).show();
                }
            }
            var $currentMethod = $('input[name=payment_methods_id]:checked');
            if ($currentMethod.closest('.js-payment-method').is(':hidden')) {
                $currentMethod.prop('checked', false);
                $currentMethod.trigger('change');
            }
        }
    }

    $('input[name=delivery_type]').change(function () {
        updateDeliveryPrice();
        updatePaymentMethodList();
        return true;
    });

    if ($('input[name=delivery_type]:checked').length) {
        updatePaymentMethodList();
    }

    function updateCdekCost(city_id, successCallback)
    {
        $.ajax({
            type: "POST",
            url: "/cdek/cities/" + city_id + "/cost",
            dataType: 'text',
            success: function (data) {
                if (data != false) {
                    var result = JSON.parse(data);

                    if (!result[137]) {
                        $('.js-delivery-cost-137').text('');
                        $('.js-delivery-date-137').text('');
                    } else {
                        $('.js-delivery-cost-137').text(result[137].price).data('price', result[137].price);
                        $('.js-delivery-date-137').text(moment(result[137].deliveryDateMax).add(2, 'days').format('D-MM-YYYY'));
                    }
                    $('.js-delivery-cost-136').text(result[136].price).data('price', result[136].price);
                    $('.js-delivery-date-136').text(moment(result[136].deliveryDateMax).add(2, 'days').format('D-MM-YYYY'));

                    successCallback();
                } else {
                    console.log("AJAX request to SDEC API returned an empty array\n");
                }
            },
            fail: function (data) {
                console.log("AJAX request to SDEC API failed\n");
            }
        });
    }

    function updateCdekPickpoint(city_id, successCallback)
    {
        $.ajax({
            type: 'GET',
            url: '/cdek/cities/' + city_id + '/pvz',
            success: function (data) {
                var point, placemark, centerx, centery;
                centerx = centery = 0;

                map.geoObjects.removeAll();

                for (var i = 0; i < data.length; i++) {
                    point = data[i];
                    centerx += point.coordx;
                    centery += point.coordy;
                    placemark = new ymaps.Placemark([point.coordy, point.coordx], {}, {
                        balloonContentLayout: ymaps.templateLayoutFactory.createClass(
                            '<div class="map__balloon js-balloon">' +
                            '<div class="map__balloon-title js-balloon-address">' + point.address + '</div>' +
                            '<input type="hidden" class="pvz" value="' + point.code + '">' +
                            '<div class="map__balloon-text">Тел.: ' + point.phone + '<br>' + point.work_time + '</div>' +
                            '<button class="btn js-balloon-btn">Выбрать</button>' +
                            '</div>'),
                        balloonPanelMaxMapArea: 0
                    });
                    if ($(window).width() > 680) {
                        placemark.options._options.balloonPanelMaxMapArea = 0;
                    } else {
                        placemark.options._options.balloonPanelMaxMapArea = Infinity;
                    }

                    map.geoObjects.add(placemark);
                }

                if (data.length) {
                    map.setCenter([centery / data.length, centerx / data.length]);
                }

                successCallback();
            }
        });
    }

    function fixOrder() {
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
    }

    if ($(window).width() > 680) {
        fixOrder();
    }

    var timeout;
    $(window).resize(function () {
        clearTimeout(timeout);
        setTimeout(function () {
            $(window).off('scroll');
            if ($(window).width() > 680) {
                fixOrder();
            } else {
                $(window).off('scroll');
            }
        }, 100);
    });
});

function initYandex () {
    $('.js-suggest').on('input', function(e) {
        if ($(this).closest('.js-delivery-form').find('.mCustomScrollBox').length) {
            $(this).closest('.js-delivery-form').find('.ymaps-2-1-47-search__suggest').mCustomScrollbar('update');
        } else {
            $(this).closest('.js-delivery-form').find('.ymaps-2-1-47-search__suggest').mCustomScrollbar({
                axis: 'y',
                scrollInertia: 400
            });
        }
    });

    window.map = new ymaps.Map('map', {
        center: [55.650625, 37.62708],
        zoom: 10
    }, {
        searchControlProvider: 'yandex#search'
    });

    $(document).on('click', '.js-balloon-btn', function(e) {
        var address = $(this).closest('.js-balloon').find('.js-balloon-address').text();
        $('.js-point-address').text(address);
        $('#pickpoint_address').val(address);
        $('#pvz').val($(this).closest('.js-balloon').find('.pvz').val());
        $('body').removeClass('is-nav');
        $('.js-point').show();
        $('.js-delivery-next').hide();
        $(this).closest('.js-popup').removeClass('is-visible');

        var top = $('#pickpoint-container').offset().top;
        var body = $("html, body");
        // 50 прикидка на глаз
        body.stop().animate({scrollTop: top - 50}, '700', 'swing');
        e.preventDefault();
        return false;
    });
}