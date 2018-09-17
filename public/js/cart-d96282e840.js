/*
 * DEFINES
 */
var DELIVERY_SHOWROOM        = 'showroom';
var DELIVERY_COURIER         = 'courier';
var DELIVERY_COURIER_MOSCOW  = 'courier_moscow';
var DELIVERY_PICKPOINT       = 'pickpoint';
var DELIVERY_POST            = 'post';

var DELIVERY                 = [];

var COUNTRY_RU               = 'RU';

var CDEK_COURIER             = 137;
var CDEK_PICKPOINT           = 136;
var CDEK_MOSCOW              = 44;

var CONSTRUCTOR_HEIGHT       = 243;
var CONSTRUCTOR_WIDTH        = 124;

var FREE_CUP_DISCOUNT        = 0;

/*
 * SELECT PICKPOINT
 */
var selectPickPoint = function(s) {
    $('#pvz').val($(s).attr('data-id'));
    $('#pvz-select .pvz').text(decodeURIComponent($(s).attr('data-name')));
    $('#map-modal').addClass('hidden');
    $('#delivery-' + DELIVERY_PICKPOINT + ' .date').datepicker('show');
    delivery_PicPoint = true;
};

function showPaymentMethods(delivery){
    $('.payment_method').addClass('hidden');
    var currentDelivery = DELIVERY.filter(function(d){
        return d.delivery_name === delivery;
    })[0];
    currentDelivery.payment_methods.forEach(function(p){
        $('.payment_method.'+p.name).removeClass('hidden');
    });
}

/*
 * INTI CART
 */
$(function(){
    /*
     * RENDER CART ITEMS
     */

    for (var id in CARTCASE) {
        // ID
        var item = CARTCASE[id];
        var source = item.item_source;
        // Device container
        var device = $(
            '<div class="cart-device clearfix">' +
                '<div class="cart-device-remove pull-right"><span class="icon-cross pointer"></span></div>' +
                '<div class="cart-device-image pull-left"></div>' +
                '<div class="cart-device-note">' +
            '</div>');

        // Picture
        var picture = device.find('.cart-device-image').on('click', (function(source) {
            saveEnv(source);
            window.location.href = '/#bg';
        }).bind(null, source));

        // Case name, description and cost
        var caseDescription = '';
        if (source.DEVICE.casey == 'silicone') {
            caseDescription = '<div class="case-description">Полностью прозрачный</div><div class="case-name">Силикон</div>';
        } else if (source.DEVICE.casey == 'plastic') {
            caseDescription = '<div class="case-description">Матовый полупрозрачный</div><div class="case-name">Пластик</div>';
		} else if (source.DEVICE.casey == 'glitter') {
            caseDescription = '<div class="case-description">Жидкий глиттер</div><div class="case-name">Глиттер</div>';
		} else if (source.DEVICE.casey == 'glitter_1') {
            caseDescription = '<div class="case-description">Жидкий глиттер</div><div class="case-name">Глиттер</div>';
		} else if (source.DEVICE.casey == 'glitter_2') {
            caseDescription = '<div class="case-description">Жидкий глиттер</div><div class="case-name">Глиттер</div>';
		} else if (source.DEVICE.casey == 'glitter_3') {
            caseDescription = '<div class="case-description">Жидкий глиттер</div><div class="case-name">Глиттер</div>';
		} else if (source.DEVICE.casey == 'glitter_4') {
            caseDescription = '<div class="case-description">Жидкий глиттер</div><div class="case-name">Глиттер</div>';
        } else {
            caseDescription = '<div class="case-description">Матовый чёрный</div><div class="case-name">Soft Touch</div>';
        }
        caseDescription += '<div class="case-description">Модель телефона</div><div class="case-name">' + item.device.device_caption + '</div>';
        caseDescription += '<div class="case-cost">Цена</div><div class="case-cost-value">' + item.item_cost + '<span class="icon-rouble"></span></div>';
        device.find('.cart-device-note').html(caseDescription)

        // Remove button
        var remove = device.find('.cart-device-remove').addClass('link text-center').confirmation({
            title: 'Вы уверены?',
            btnOkClass: 'btn-danger',
            btnOkIcon: '',
            btnOkLabel: 'Удалить',
            btnCancelClass: 'btn-default',
            btnCancelIcon: '',
            btnCancelLabel: 'Оставить',
            container: 'body',
            singleton: true,
            popout: true,
            onConfirm: (function(id, device) {
                setTimeout(function() {
                    device.remove();
                    delete CART[id];
                    delete CARTCASE[id];
                    updateTotalCost();
                    $.post(
                        '/cart/' + id + '/delete',
                        function () {
                            if ($('#devices').find('.cart-device').length == 0) {
                                window.location.reload();
                            }
                        }
                    );
                }, 100);
            }).bind(null, id, device)
        });

        // Render current layout
        $('#devices').append(device);

        // Device
        var DEVICE = new Device(source.DEVICE);
        DEVICE.baseHeight = CONSTRUCTOR_HEIGHT;
        DEVICE.baseWidth = CONSTRUCTOR_WIDTH;
        DEVICE.render(picture);

        // Smiles
        var smiles = source.SMILE || [];
        for (var i = 0, len = smiles.length; i < len; ++i) {
            var smile = new Smile(smiles[i]);
            smile.baseHeight = CONSTRUCTOR_HEIGHT;
            smile.baseWidth = CONSTRUCTOR_WIDTH;
            smile.readOnly = true;
            smile.render(DEVICE.canvas);
            smile.renderTarget(DEVICE.masked);
        }

        // Text
        var texts = source.TEXT || [];
        for (var i = 0, len = texts.length; i < len; ++i) {
            var text = new Text(texts[i]);
            text.baseHeight = CONSTRUCTOR_HEIGHT;
            text.baseWidth = CONSTRUCTOR_WIDTH;
            text.readOnly = true;
            text.render(DEVICE.canvas);
            text.renderTarget(DEVICE.masked);
        }
    }

    /*
     * LOAD COUNTRIES
     */
    $('#country').select2({
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

    /*
     * LOAD CITIES
     */
    $('#city').select2({
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

    /*
     * YANDEX MAP WITH CDEK PVZ
     */
    var showPvzSelect = function() {
        var city = $('#city').find('option:selected').val();
        if (city) {
            $('#map').empty();
            $('#map-modal').removeClass('hidden');

            var map = new ymaps.Map("map", {
                center: [55.76, 37.64],
                zoom: 10
            });

            $.get(
                '/cdek/cities/' + city + '/pvz',
                function (data) {
                    var geoObjects = [];
                    for (var i = 0; i < data.length; i++) {
                        // Baloon content
                        var baloon = '<table><tbody>';
                        baloon += '<tr><td><strong>Адрес:</strong></td><td>' + data[i].address + '</td><tr>';
                        if (data[i].work_time) baloon += '<tr><td><strong>Рабочее время:</strong></td><td>' + data[i].work_time + '</td><tr>';
                        if (data[i].phone)     baloon += '<tr><td><strong>Телефон:</strong></td><td>' + data[i].phone + '</td><tr>';
                        if (data[i].note)      baloon += '<tr><td><strong>Примечание:</strong></td><td>' + data[i].note + '</td><tr>';
                        baloon += '</tbody></table>';

                        // Button
                        baloon += "<button type='button' class='btn btn-default btn-block center-block' data-id='" + data[i].code + "' data-name='" + encodeURIComponent(data[i].name + ', ' + data[i].address) + "' onclick='selectPickPoint(this)'>";
                        baloon += "Выбрать";
                        baloon += "</button>";

                        // Placemark
                        var object = new ymaps.Placemark(
                            [data[i].coordy, data[i].coordx], {
                                balloonContentHeader: data[i].name,
                                balloonContent: baloon
                            }
                        );
                        geoObjects.push(object);
                    }

                    var clusterer = new ymaps.Clusterer();
                    clusterer.add(geoObjects);
                    map.geoObjects.add(clusterer);
                    map.setBounds(clusterer.getBounds());
                }
            );
        }
    };

    /*
     * INIT YANDEX MAP
     */
    ymaps.ready(function() {
        $('#pvz-select').on('click', function () {
            showPvzSelect();
        });
        $('#map-modal').find('.close').on('click', function () {
            $('#map-modal').addClass('hidden');
        })
    });

    /*
     * INIT BOOTSTRAP POPOVERS
     */
    $('[data-toggle="popover"]').popover();

    /*
     * HIDE ALL DELIVERY
     */
    var deliveryHideAll = function() {
        $('.delivery').addClass('hidden');
        $('.delivery input[name="delivery_type"]').prop('checked', false);
    };

    /*
     * SHOW DELIVERY
     */
    var deliveryShow = function(v, value) {
        var show = value || value === undefined;
        var d = $('#delivery-' + v)
        d.toggleClass('hidden', !show);
        var inp = d.find('input[name="delivery_type"]');
        if(inp.attr('checked')) {
            inp.prop('checked', true);
            showDeliverySub(d);
        }
    };

    var showDeliverySub = function(delivery){
        // Show delivery sub
        $('.delivery .delivery-sub').addClass('hidden');
        delivery.find('.delivery-sub').removeClass('hidden');
    }

    /*
     * COUNTRY CHANGE HANDLER
     */
    $('#country').on('change', function() {
        if ($(this).val() == COUNTRY_RU) {
            // Show city and trigger change
            $('#row-city').removeClass('hidden').find('select').change();
            $('#delivery-' + DELIVERY_POST).find('.time').html('7-9<span class="hidden-xs"> дней</span><span class="visible-xs-inline"> д.</span>');

        } else {
            // Set only russian post
            $('#row-city').addClass('hidden');
            deliveryHideAll();
            deliveryShow(DELIVERY_POST);
            $('#delivery-' + DELIVERY_POST).find('.time').html('7-15<span class="hidden-xs"> дней</span><span class="visible-xs-inline"> д.</span>');
        }
    });

    if ($('#country').val()) {
        $('#country').trigger('change');
    }
    /*
     * INTIAL COUNTRY BY GEO IP
     */
    if (GEO && GEO.country_iso && GEO.country_name) {
        var option = $('<option selected>' + GEO.country_name + '</option>').val(GEO.country_iso);
        $('#country').empty().append(option).trigger('change');
    }

    // City selection
    var costs = {};
    $('#city').on('change', function() {
        // Non empty
        if (!$(this).val()) {
            return;
        }

        delivery_city = true;

        // Reset PVZ
        $('#pvz-select .text').text('Выберите пункт выдачи');
        $('#pvz').val('');


        // Get costs
        $.get(
            '/cdek/cities/' + $(this).val() + '/cost',
            function (result) {
                // Costs
                costs = result;

                // Hide all delivery
                deliveryHideAll();

                // If Moscow
                if ($('#city').val() == CDEK_MOSCOW) {
                    deliveryShow(DELIVERY_SHOWROOM);
                    deliveryShow(DELIVERY_COURIER_MOSCOW);
                    deliveryShow(DELIVERY_PICKPOINT);
                } else {
                    deliveryShow(DELIVERY_PICKPOINT);
                    deliveryShow(DELIVERY_COURIER);
                    deliveryShow(DELIVERY_POST);
                }

                // Set pickpoint
                if (costs[CDEK_PICKPOINT]) {
                    $('#delivery-' + DELIVERY_PICKPOINT + ' .cost').text(costs[CDEK_PICKPOINT].price || '?');
                } else {
                    deliveryShow(DELIVERY_PICKPOINT, false);
                }

                // Set courier cost
                if (costs[CDEK_COURIER]) {
                    $('#delivery-' + DELIVERY_COURIER + ' .cost').text(costs[CDEK_COURIER].price || '?');
                } else {
                    deliveryShow(DELIVERY_PICKPOINT, false);
                }
            }
        );
    });

    if ($('#city').val()) {
        $('#city').trigger('change');
    }

    /*
     * INIT CITY BY GEO IP
     */
    if (GEO && GEO.country_iso == COUNTRY_RU && GEO.city_name && GEO.cdek_city) {
        var option = $('<option selected>' + GEO.city_name + '</option>').val(GEO.cdek_city);
        $('#city').empty().append(option).trigger('change');
    }

    /*
     * GET CART ITEMS COST
     */
    var getBaseCost = function() {
        var cost = 0;
        for (var id in CART) {
            if (CART[id].item_sku == "product")
                FREE_CUP_DISCOUNT = CART[id].item_cost;

            cost += (parseInt(CART[id].item_cost, 10) * CART[id].item_count);
        }
        return cost;
    };

    /*
     * GET CART COUNT
     */
    var getBaseCount = function() {
        var count = 0;
        for (var id in CART) {
            count += CART[id].item_count;
        }
        return count;
    };

    /*
     * DISCOUNT
     */
    var setDiscount = function(count, amount, delivery) {
        // Hide all
        console.log(PROMOTION_CODE);
        if (PROMOTION_CODE) {
            // Show info
            $('#promocode_ctl').addClass('hidden');
            $('#promocode_info').removeClass('hidden');
            $('#promocode_name').text(PROMOTION_CODE.code_name);
			
			// цвет текста акции
			$(".cart_color_action").css("color", "#e85854"); 
			

            // Hide errors
            $('#promocode_cond_count').addClass('hidden');
            $('#promocode_cond_amount').addClass('hidden');

            // Check count
            if (
                PROMOTION_CODE.code_cond_cart_count
                && PROMOTION_CODE.code_cond_cart_count > count
            ) {
                $('#promocode_cond_count').removeClass('hidden').find('strong').text(PROMOTION_CODE.code_cond_cart_count);
                return 0;
            }

            // Check amount
            if (
                PROMOTION_CODE.code_cond_cart_amount
                && PROMOTION_CODE.code_cond_cart_amount > amount
            ) {
                $('#promocode_cond_amount').removeClass('hidden').find('strong').text(PROMOTION_CODE.code_cond_cart_amount);
                return 0;
            }

            var discount = PROMOTION_CODE.code_discount;
            var m = []

            if (discount == 'Кружка бесплатно')
            {
                console.log(FREE_CUP_DISCOUNT);
                return FREE_CUP_DISCOUNT;
            }

            // Percentage
            var rgx = /^(\d+)%$/ig;
            if (rgx.test(discount)) {
                rgx.lastIndex = 0;
                m = rgx.exec(discount);
                return Math.round((amount + delivery) * m[1] / 100);
            }

            // Value
            if (/^(\d+)$/.test(discount)) {
                return parseInt(discount, 10);
            }

            /* // Delivery
            if (discount == 'D') {
                return delivery;
            } */
			// временная промоакция для некоторых типов доставки
			// Delivery
            if (discount == 'D') {
				var delivery_type = $('input[name="delivery_type"]:checked').val();
				console.log(delivery_type);
				if(delivery_type == "pickpoint" ||  delivery_type == "post" ||  delivery_type == "courier_moscow"){
					// Пункты выдачи
					// Почта России 
					// Курьер по Москве в пределах МКАД
					return delivery;
				}else{
					return 0;
				} 
            }

        } else {
            $('#promocode_ctl').removeClass('hidden');
            $('#promocode_info').addClass('hidden');
        }

        // By default discount is zero
        return 0;
    };

    /*
     * PROMOCODE APPLY
     */
    $('#promocode_form').on('submit', function() {
        $('#promocode_error').empty().addClass('hidden');
        $(this).attr('disabled', true);
        $.post(
            BASEURL + '/cart/code/apply/' + encodeURIComponent($('#promocode_value').val()),
            function(result) {
                $(this).attr('disabled', false);
                PROMOTION_CODE = result.code;
                if (!result.code) {
                    $('#promocode_error').text('Промокод не действует').removeClass('hidden');
                }
                updateTotalCost();
            }
        ).fail(function() {
            $('#promocode_error').text('Ошибка при получении промокода').removeClass('hidden');
        }).always(function() {
            $(this).attr('disabled', false);
        });
        return false;
    });

    /*
     * REMOVE PROMO
     */
    $('#promocode_remove').click(function() {
        PROMOTION_CODE = null;
        updateTotalCost();
        $.post(BASEURL + '/cart/code/remove');
    });

    /*
     * UPDATE TOTAL COST
     */
    var COST_DELIVERY = 0;
    var updateTotalCost = function(delivery) {
        // Delivery
        if (delivery === undefined) {
            delivery = COST_DELIVERY;
        }
        var count = getBaseCount();
        var cost = getBaseCost();
        var discount = setDiscount(count, cost, delivery);

        $('.cart-count').text(count + ' ' + pluralize(count, 'ТОВАР', 'ТОВАРА', 'ТОВАРОВ'));
        $('.amount-cost').text(cost);
        $('.amount-delivery').text(delivery);
        $('.amount-discount').text(-discount);
        $('#amount-discount').toggleClass('hidden', !discount);
        $('.amount-total').text(cost + delivery - discount);

        // Update variables
        COST_DELIVERY = delivery;
    };

    /*
     * DELIVERY CHANGE
     */
    $('.delivery input[name="delivery_type"]').change(function() {
        // Non empty
        if (!$(this).prop('checked')) {
            return false;
        }

        // Delivery value
        var delivery = $(this).val();
        var $delivery = $('#delivery-' + delivery);

        if($('.payment_method').length) { //Cart2 проверка если корзина новая, запрос за методами оплаты
            if(!DELIVERY.length){
                $.get('/cart2/delivery')
                    .done(function(response){
                        DELIVERY = response;
                        showPaymentMethods(delivery);
                    });
            } else showPaymentMethods(delivery);
        }

        showDeliverySub($delivery);

        // Delivery
        switch (delivery) {

            case DELIVERY_PICKPOINT:
                // Date
                $delivery.find('.date').datepicker('destroy').datepicker({
                    format: 'yyyy-mm-dd', language: 'ru', weekStart: 1,
                    startDate: costs[CDEK_PICKPOINT] ? costs[CDEK_PICKPOINT].dateFrom : null,
                    endDate: costs[CDEK_PICKPOINT] ? costs[CDEK_PICKPOINT].dateTo : null,
                    autoclose: true
                });

                // Show PVZ selecting
                showPvzSelect();

                // Prices
                updateTotalCost(costs[CDEK_PICKPOINT] ? costs[CDEK_PICKPOINT].price : 0);
                break;

            case DELIVERY_COURIER:
                $delivery.find('.date').datepicker('destroy').datepicker({
                    format: 'yyyy-mm-dd', language: 'ru', weekStart: 1,
                    startDate: costs[CDEK_COURIER] ? costs[CDEK_COURIER].dateFrom : null,
                    endDate: costs[CDEK_COURIER] ? costs[CDEK_COURIER].dateTo : null,
                    autoclose: true
                }).datepicker('show');

                // Prices
                updateTotalCost(costs[CDEK_COURIER] ? costs[CDEK_COURIER].price : 0);
                break;

            case DELIVERY_COURIER_MOSCOW:
                $delivery.find('.date').datepicker('destroy').datepicker({
                    format: 'yyyy-mm-dd', language: 'ru', weekStart: 1,
                    startDate: (new Date()).getHours() > 18 ? "+3d" : "+2d",
                    endDate: "+15d",
                    autoclose: true
                }).datepicker('show');

                // Prices
                updateTotalCost(parseInt($delivery.find('.cost').text(), 10));
                break;

            case DELIVERY_SHOWROOM:
                $delivery.find('.date').datepicker('destroy').datepicker({
                    format: 'yyyy-mm-dd', language: 'ru', weekStart: 1,
                    startDate: (new Date()).getHours() > 18 ? "+3d" : "+2d",
                    endDate: "+14d",
                    autoclose: true
                }).datepicker('show');

                // Prices
                updateTotalCost(0);
                break;

            case DELIVERY_POST:
                // Prices
                updateTotalCost(parseInt($delivery.find('.cost').text(), 10));
                break;
        }
    });

    /*
     * CHECK FORM ON SUBMIT
     */
     $('#phone').focus(function() {
        $('#phone').mask("+0(000)000-00-00");
       var val = $('#phone').val();
       if (val[0] !== '+' && val[1] !== 7)
           $('#phone').val('+7' + val);
    });

    $('#cart-submit').on('click', function() {
        $('#form').submit()
    });
    $('.popup-error-validate .btn').click(function() {
            $('.popup-error-validate').css('display', 'none');
        });
    var phone_valide = true;
    var checked = false;
    $('#form').on('submit', function(e) {
        // Get result row
        var row = {};
        var values = $(this).serializeArray();
         var reg = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
            let e_mail_valid = reg.test(String($('#email').val()).toLowerCase());



        for (var i = 0, len = values.length; i < len; ++i) {
            row[values[i].name] = values[i].value;
        }
        row.delivery_type = $(this).find('input[name="delivery_type"]:checked').val();
        // Check errors
        var valid = true;
            validate = true,
            error_validate = '';
        for (var name in row) {
            // Get control
            var ctl = $(this).find('*[name=' + name + ']');
            ctl.removeClass('error');

            // Check mandatory
            if (ctl.first().data('mandatory') && !row[name]) {
                ctl.addClass('error');
                valid = false;
            }

            // Check mandatory function
            if (ctl.first().data('mandatory-function')) {
                var f = new Function('row', 'return ' + ctl.first().data('mandatory-function'));
                if (f(row) && !row[name]) {
                    ctl.addClass('error');
                    valid = false;
                }
            }
        }


         if ($('#phone').val().length < 16 ) {
            $('#phone').addClass('error');
            validate = false;
            phone_valide = false;
            //error_validate = 'Необходимо корректно заполнить поле "Телефон"';
        }
        if($('#phone').val().length > 0 && $('#email').val().length > 0 ){
            if (!phone_valide || !e_mail_valid) {
               validate = false;
               // error_validate = 'Необходимо корректно заполнить поле "E-mail" или "Телефон"';
               $('#email').addClass('error');
            }
        } else {
                $('#email').removeClass('error');
                if ($('#phone').val().length == 0 && e_mail_valid) {
                    $('#phone').removeClass('error');
                     error_validate = '';
                }
            }
        if ((!$('#email').val() ||
                !reg.test(String($('#email').val()).toLowerCase()))
                && $('#phone').val().length < 16 ) {
                $('#email').addClass('error');
                validate = false;
                //error_validate = 'Необходимо корректно заполнить поле "E-mail" или "Телефон"';
            }

        validate = true;
        if (!$("input[name='name']").val()) {
        	error_validate = 'Необходимо корректно заполнить поле "Фамилия, Имя"';
        	validate = false;
     	}

     	if (!$('#email').val() || !reg.test(String($('#email').val()).toLowerCase())) {
        	if (validate) {
        		error_validate = 'Необходимо корректно заполнить поле E-mail';
        		validate = false;
        		$('#email').addClass('error');
        	} else {
        		error_validate += ', "E-mail"';
        	}
        }

        if (!$('#email').val() || !reg.test(String($('#email').val()).toLowerCase())) {
        	$('#email').addClass('error');
        	valid = false;
        } else {
        	$('#email').removeClass('error');
        }


     	if ($('#phone').val().length < 16 ) {
     		if (validate) {
     			error_validate = 'Необходимо корректно заполнить поле "Телефон"';
     			validate = false;
     		} else {
     			error_validate += ', "Телефон"';
     		}
     	}

     	if ($('#phone').val().length < 16) {
        		$('#phone').addClass('error');
        }

		// доставка
		$(".delivery").find(".point").removeClass("error");
		if($('input[name="delivery_type"]:checked').length == 0){
			$(".delivery").find(".point").addClass("error");
			valid = false;
		}


		if (typeof delivery_city == 'undefined') {
			if (validate) {
				error_validate = 'Необходимо корректно заполнить поле "Выберите город"';
			} else {
				error_validate += ', "Выберите город"';
				validate = false;
			}
		}


		if (!$('input[name="delivery_type"]:checked').length && typeof delivery_city !== 'undefined') {
			if (validate) {
				error_validate = 'Необходимо сделать выбор типа доставки';
			} else {
				error_validate += ', "Тип доставки"';
				validate = false;
			}
		}

		delivery_type_error = $('input[name="delivery_type"]:checked').val();

		if (typeof delivery_PicPoint == 'undefined' &&  delivery_type_error == 'pickpoint') {
			if (validate) {
				error_validate = 'Необходимо выбрать пункт выдачи';
			} else {
				error_validate += ', "Пункт выдачи"';
				validate = false;
			}
		}

		if (!$('input[name="delivery_date_courier"]').val() && delivery_type_error == 'courier') {
			if (validate) {
				error_validate = 'Необходимо корректно заполнить поле "Дата визита"';
			} else {
				error_validate += ', "Дата визита"';
				validate = false;
			}
		}

		if (!$('input[name="courier_address"]').val() && delivery_type_error == 'courier') {
			if (!validate){
				error_validate += ', "Адрес"';
			} else {
				error_validate = 'Необходимо заполнить поле "Адрес"';
				validate = false;
			}
		}



		if (!$('input[name="post_code"]').val() && delivery_type_error == 'post') {
			if (!validate) {
				error_validate += ', "Индекс"';
			} else {
				error_validate = 'Необходимо заполнить поле "Индекс"';
				validate = false;
			}
		}

		if (!$('input[name="post_address"]').val() && delivery_type_error == 'post') {
			if (!validate) {
				error_validate += ', "Адрес"';
			} else {
				error_validate = 'Необходимо заполнить поле "Адрес"';
				validate = false;
			}
		}


        // If not valid
        if (!valid) {
			$("body").scrollTop( $(".error").offset().top );
            var body = $("html, body");
            body.stop().animate({scrollTop: top}, '500', 'swing');
            e.preventDefault();
            if (!validate) {
                console.log(error_validate);
                if (error_validate != '') {
                    $('.body_popup__text').text(error_validate);
                    $('.popup-error-validate').css('display', 'block');
                    setTimeout(function() {
                        if ($('.popup-error-validate').css('display') === 'block') {
                            $('.popup-error-validate .btn').trigger('click');
                        }
                    }, 3000);
                } else {
                    $('.body_popup__text').text(error_validate);
                    $('.popup-error-validate').css('display', 'block');
                    setTimeout(function() {
                        if ($('.popup-error-validate').css('display') === 'block') {
                            $('.popup-error-validate .btn').trigger('click');
                        }
                    }, 3000);
                }
            } else if(!valid) {
                $('.body_popup__text').text(error_validate);
                $('.popup-error-validate').css('display', 'block');
                setTimeout(function() {
                    if ($('.popup-error-validate').css('display') === 'block') {
                        $('.popup-error-validate .btn').trigger('click');
                    }
                }, 3000);
            }

            return false;
        } else {
            if (checked) {
                var products = [];
                /*$("#devices .cart-device").each(function(){
                    var block_device = $(this).closest(".cart-device");
                    var type_casey = block_device.find(".case-description").html();
                    var device = block_device.find(".case-name").eq(1).html();
                    var price = block_device.find(".case-cost-value").text();

                    var type_bg = "bg no found";
                    if(block_device.find("image.constructor-bg").length > 0){
                        var href_bg = $("image.constructor-bg").attr("href");
                        type_bg = href_bg.split('/')[href_bg.split('/').length-1];
                        type_bg = type_bg.replace(".png", "");
                    }
                    var one_product = {
                        "name": type_bg,
                        "price": price,
                        "category": type_casey,
                        "variant": device,
                        "quantity": 1,
                    };

                    products.push(one_product);
                });	*/
                for(cartSetCase in window.CARTCASE){
                    products.push({
                        name: window.CARTCASE[cartSetCase].item_source.DEVICE.bg,
                        price: window.CARTCASE[cartSetCase].item_cost,
                        category: window.CARTCASE[cartSetCase].device_name + ' ' + window.CARTCASE[cartSetCase].device.device_colors[window.CARTCASE[cartSetCase].item_source.DEVICE.color],
                        variant: window.CARTCASE[cartSetCase].item_source.DEVICE.casey,
                        quantity: window.CARTCASE[cartSetCase].item_count
                    });
                }

                var first_product;
                for(i in CART){
                    if(!first_product){
                        first_product = CART[i];
                    }
                }
                var id_cart = first_product.cart_id;
                dataLayer.push({
                    "ecommerce": {
                        "purchase": {
                            "actionField": {
                                "id" : id_cart
                            },
                            "products": products
                        }
                    }
                });
                console.log(id_cart, products);

                // Loading
                loading(true);
            } else {
                e.preventDefault();
                let text = 'Пожалуйста проверьте правильность информации<br>Телефон: ' + $('#phone').val() + '<br>Почта: ' + $('#email').val();
                $('.popup-check .body_popup__text').html(text);
                $('.popup-check-validate').css('display', 'block');
            }
        }
		// metrika
    });

    $('.popup-check-validate .btn_ok').click(function() {
        $('.popup-check-validate').css('display', 'none');
        checked = true;
        $('#form').submit();
    });

    $('.popup-check-validate .btn_edit').click(function() {
        $('.popup-check-validate').css('display', 'none');

        var scrollTop = $('#form').offset().top;
        $(document).scrollTop(scrollTop);
    });

    $('.payment_method input').on('click', function(){
        var btntext = "Оформить";
        if($(this).attr('class') == 'online'){
            btntext = 'Оплатить';
        }
        $('#cart-submit').html(btntext);
    });

    /*
     * INIT CART TOTALS
     */
    var initDeliveryAmount = $('#delivery-info').data('amount-delivery');
    updateTotalCost(initDeliveryAmount);
});


$(document).ready(function(){
	$(".type_payment_h5").hide();
	$('body').on('change', '.cart-cb input[type="radio"]', function(){ 
		val = $(this).val();
		$(".type_payment_h5").show();	
		
		if(val == "1"){ 
			change_name_btn_pay("online");
		}else{
			change_name_btn_pay("offline");
		}		
		
		// пункт выдачи
		if(val == "pickpoint"){ 
			$(".gen-offline-pay").hide();
			$(".gen-online-pay").show();
			$(".gen-online-pay").find('input[name="payment_methods_id"]').prop("checked", true);	
			change_name_btn_pay("online");
		}
		// Курьер по Москве в пределах МКАД
		if(val == "courier_moscow"){
			$(".gen-online-pay").hide();	
			$(".gen-offline-pay").show();
			$(".gen-offline-pay").find('input[name="payment_methods_id"]').prop("checked", true);	
			change_name_btn_pay("offline");
		}
		// Почта России
		if(val == "post"){
			$(".gen-offline-pay").hide();
			$(".gen-online-pay").show();
			$(".gen-online-pay").find('input[name="payment_methods_id"]').prop("checked", true);	
			change_name_btn_pay("online");
		}
		// Курьером по России
		if(val == "courier"){
			$(".gen-offline-pay").hide();
			$(".gen-online-pay").show();
			$(".gen-online-pay").find('input[name="payment_methods_id"]').prop("checked", true);
			change_name_btn_pay("online");
		}
		// pickpoint
	});
	
});

function change_name_btn_pay(type){
	if(type == "online"){
		$("#cart-submit").html("Оплатить");
	}else{
		$("#cart-submit").html("Оформить");
	}
}
