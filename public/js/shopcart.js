var check_point_map;
$(function() {
    $('.js-delivery').on('change', function(e) {
        $('.js-delivery-hidden').slideUp(200);
        if ($(this).prop('checked')) {
            $(this).closest('.js-delivery-item').find('.js-delivery-hidden').slideDown(200);
        }

        $('.js-delivery-date-hidden').slideUp(200);
        if ($(this).prop('checked')) {
            $(this).closest('.js-delivery-item').find('.js-delivery-date-hidden').slideDown(200);
        }

    });

    $('.js-promo-link').on('click', function(e) {
        $(this).closest('.js-promo').addClass('is-input');
        e.preventDefault();
    });

    if ($('#map').length) {
        ymaps.ready(initYandex);
    }

    $('#phone').focus(function() {
        $('#phone').mask("+0(000)000-00-00");
       let val = $('#phone').val();
       if (val[0] !== '+' && val[1] !== 7)
           $('#phone').val('+7' + val);
    });

    $('.js-order-btn').on('click', function(e) {
        var paymentMethodSelected = ($('.js-payment-input:checked').length > 0),
            validate = true, error_validate = '';
			
		var phone = $('#phone').val(),
			name = '',
			email = '',
			city = '';
			
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
        var phone_valide = true;
        var reg = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
        let e_mail_valid = reg.test(String($('#email').val()).toLowerCase());

        if ($('#phone').val().length < 16 ) {
            console.log($('#phone').val().length);
            $('#input-phone').addClass('failure-validate');
            validate = false;
            phone_valide = false;
            error_validate = 'Необходимо корректно заполнить поле "Телефон"';
        }


        if($('#phone').val().length > 0 && $('#email').val().length > 0 ){
            if (!phone_valide || !e_mail_valid) {
               validate = false;
                //error_validate = 'Необходимо корректно заполнить поле "E-mail" или "Телефон"';
               $('#input-email').addClass('failure-validate');
            }
        }

        if($('#email').val() && !e_mail_valid)
            error_validate = 'Необходимо корректно заполнить поле "E-mail"';

        if(!phone_valide && !e_mail_valid)
                error_validate = 'Необходимо корректно заполнить поле "Телефон" или "E-mail"';

        if (!window.isShortShopCart) {
			name = $('#name').val();
			email = $('#email').val();
			
            if (!$('#name').val()) {
                $('#input-name').addClass('failure-validate');
                validate = false;
            }
            if ((!$('#email').val() ||
                !reg.test(String($('#email').val()).toLowerCase()))
                && $('#phone').val().length < 16 && !validate ) {
                $('#input-email').addClass('failure-validate');
                validate = false;
                error_validate = 'Необходимо корректно заполнить поле "E-mail" или "Телефон"';
            }

            if($('#email').val() && reg.test(String($('#email').val()).toLowerCase()) && $('#phone').val().length < 16){
            	error_validate = 'Необходимо корректно заполнить поле "Телефон"';
            	$('#input-email').removeClass('failure-validate');
            }

            if(!$('#name').val()){
                error_validate += ', "Ваше Имя"';
                if(error_validate == ', "Ваше Имя"')
                    error_validate = 'Необходимо корректно заполнить поле "Ваше Имя"';
            }


            if (!$('input[name=delivery_type]:checked').length) {
                $('#input-deliveries').addClass('failure-validate');
                validate = false;

                if(!error_validate)
                    error_validate = 'Необходимо корректно заполнить поле "Доставка"';
                else
                    error_validate += ', "Доставка"';

            } else {
                switch ($('input[name=delivery_type]:checked').val()) {
                    case 'courier_moscow':
                        if (!$('input[name=courier_moscow_address]').val()) {
                            $('#input_courier_moscow_address').addClass('failure-validate');
                            validate = false;

                            if(!error_validate)
                                error_validate = 'Необходимо корректно заполнить поле "Укажите адрес доставки"';
                            else
                                error_validate += ', "Укажите адрес доставки"';
                        }
						city = 'Москва';
                        break;
                    case 'pickpoint':
                        if (!$('#pickpoint_address').val()) {
                            $('#input_pickpoint_address').addClass('failure-validate');
                            validate = false;

                            if(!error_validate)
                                error_validate = 'Необходимо корректно заполнить поле "Укажите город"';
                            else
                                error_validate += ', "Укажите город"';
                        }
						city = $('[name="delivery_type"]:checked').closest('.radio__item').find('.select2-selection__rendered').text();
                        break;
                    case 'courier':
                        if (!$('input[name=courier_address]').val()) {
                            $('#input_courier_address').addClass('failure-validate');
                            validate = false;

                            if(!error_validate)
                                error_validate = 'Необходимо корректно заполнить поле "Укажите адрес доставки"';
                            else
                                error_validate += ', "Укажите город"';
                        }
						city = $('[name="delivery_type"]:checked').closest('.radio__item').find('.select2-selection__rendered').text() || 'Москва';
                        break;
                    case 'post':
                        if (!$('input[name=country]').val() || !$('input[name=post_code]').val() || !$('input[name=post_address]').val()) {
                            $('#input_post_address').addClass('failure-validate');
                            validate = false;

                            if(!$('input[name=post_code]').val()){
                                if(!error_validate)
                                    error_validate = 'Необходимо корректно заполнить поле "Укажите индекс"';
                                else
                                    error_validate += ', "Укажите индекс"';
                            }

                            if(!$('input[name=post_address]').val()){
                                if(!error_validate)
                                    error_validate = 'Необходимо корректно заполнить поле "Укажите адрес"';
                                else
                                    error_validate += ', "Укажите адрес"';
                            }
                        }
                        break;
                }
            }

            if (!paymentMethodSelected) {
                $('.js-payment-title').addClass('failure-validate').text('Выберите способ оплаты');
                validate = false;

                if(!error_validate)
                    error_validate = 'Необходимо корректно заполнить поле "Оплата"';
                else
                    error_validate += ', "Оплата"';
            }
        }

        if (!validate) {
            var top = $('.failure-validate:first').offset().top;
            var body = $("html, body");
            body.stop().animate({scrollTop: top}, '500', 'swing');
            e.preventDefault();
            if (error_validate != '') {
                $('.body_popup__text').text(error_validate);
                $('.popup-error-validate').css('display', 'block');
                setTimeout(function() {
                    if ($('.popup-error-validate').css('display') === 'block') {
                        $('.popup-error-validate .btn').trigger('click');
                    }
                }, 3000);
            } else {
                //$('.body_popup__text').text('Проверьте правильность введенных данных');
                $('.body_popup__text').text('Необходимо корректно заполнить поле "E-mail"');
                $('.popup-error-validate').css('display', 'block');
                setTimeout(function() {
                    if ($('.popup-error-validate').css('display') === 'block') {
                        $('.popup-error-validate .btn').trigger('click');
                    }
                }, 3000);
            }
        } else {
            e.preventDefault();
            let text = 'Пожалуйста проверьте правильность информации<br>Телефон: ' + $('#phone').val() + '<br>Почта: ' + $('#email').val();
            $('.popup-check .body_popup__text').html(text);
            $('.popup-check-validate').css('display', 'block');
        }
    });

    $('.popup-error-validate .btn').click(function() {
        $('.popup-error-validate').css('display', 'none');
    });

    $('.popup-check-validate .btn_ok').click(function() {
        $('.popup-check-validate').css('display', 'none');
        /** yandex && facebook reach goals**/
        // fbq('track', 'SiteOrder');
        // window.metrikaGoals.shopCartSubmitted(function () {
        //     $('#shop-form').submit();
        // });
        if(window.orderProducts){
            dataLayer.push({
                "ecommerce": {
                    "purchase": {
                        "actionField": {
                            "id" : window.cart_id ? window.cart_id : "TRX987"
                        },
                        "products": window.orderProducts
                    }
                }
            });
        }



        $('#shop-form').submit();

        return false;
    });

    $('.popup-check-validate .btn_edit').click(function() {
        $('.popup-check-validate').css('display', 'none');

        var scrollTop = $('.tabs__btns').offset().top;
    	$(document).scrollTop(scrollTop);
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
	
	// временная акция для нескольких типов доставки
	/*function updateCartInfo(data)
    {
        $totalPriceContainer.text(data.priceWithDiscount);
        $totalPriceContainer2.text(data.priceWithDiscount);
        $cartItemsCountContainer.text(data.cartCount);
        $priceWithoutDiscountContainer.text(data.priceValue);
        var discountAmount = (data.cart.promotion_code ? data.cart.promotion_code.code_discount : 0);
		// Пункты выдачи
		// Почта России 
		// Курьер по Москве в пределах МКАД
		var delivery_type = data.cart.delivery_name;
		var sum_cart = parseInt($totalPriceContainer2.html());
		console.log(sum_cart);
		if(sum_cart >= 1700 && (delivery_type == "pickpoint" ||  delivery_type == "post" ||  delivery_type == "courier_moscow")){ 
			discountAmount = data.cart.delivery_amount;
			$discountContainer.text("Бесплатная доставка");
			$discountContainer.parent().show();
		}else{
			discountAmount = 0;
			$discountContainer.parent().hide();
		}

        if (data.cart.promotion_code_id == 23)
            $totalPriceContainer2.html(parseInt(data.priceWithDiscount));
        else
            $totalPriceContainer2.html(parseInt($priceWithoutDiscountContainer.html()) - discountAmount );

        $deliveryPriceContainer.text(data.cart.delivery_amount);
        $("#delivery_amount").val(data.cart.delivery_amount);
    }*/

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
                console.log(answer);
                $this.removeAttr('disabled');
                if (answer.result == 'success') {
                    $this.closest('.js-case-container').fadeOut();
                    updateCartInfo(answer.data);
                    console.log(answer.data);
                    console.log(answer);
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
					placemark.options.set('preset', 'islands#blueIcon');					
					placemark.events.add('click', function (e) {
						check_point_map = e.get('target');
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
		// смена цвета точки на карте
		map.geoObjects.each(function (geoObject) {
			geoObject.options.set('preset', 'islands#blueIcon');
		});
		check_point_map.options.set('preset', 'islands#redIcon');
						
						
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

    $.fn.datepicker.dates['ru'] = {
        days: ["Воскресенье", "Понедельник", "Вторник", "Среда", "Четверг", "Пятница", "Суббота"],
        daysShort: ["Вск", "Пнд", "Втр", "Срд", "Чтв", "Птн", "Суб"],
        daysMin: ["Вс", "Пн", "Вт", "Ср", "Чт", "Пт", "Сб"],
        months: ["Январь", "Февраль", "Март", "Апрель", "Май", "Июнь", "Июль", "Август", "Сентябрь", "Октябрь", "Ноябрь", "Декабрь"],
        monthsShort: ["Янв", "Фев", "Мар", "Апр", "Май", "Июн", "Июл", "Авг", "Сен", "Окт", "Ноя", "Дек"],
        today: "Сегодня",
        clear: "Очистить",
        weekStart: 1,
        monthsTitle: 'Месяцы'
    };

    let date = new Date();

    $('.jd-delivery-date-select').datepicker({
        startDate: date,
        endDate: new Date(date.getFullYear(), date.getMonth(), date.getDate() + 14),
        enableOnReadonly: false,
        language: 'ru',
        disableTouchKeyboard: true,
        showOnFocus: true,
    });

}