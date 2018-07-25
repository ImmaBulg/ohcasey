$(function () {
    var $hiddenDeliveryChooser = $('#change_delivery'),
        $deliveryInfo = $('.js-delivery-info'),
        $deliveryForm = $('.js-delivery-form');
    function toggleDeliveryForm() {
        $deliveryInfo.hide();
        $deliveryForm.hide();
        if ($hiddenDeliveryChooser.is(':checked')) {
            $deliveryForm.show();
        } else {
            $deliveryInfo.show();
        }
    }
    $hiddenDeliveryChooser.change(toggleDeliveryForm);
    toggleDeliveryForm();

    $('.js-log-line').hide();
    $('.js-log-line-0').show();

    $('.js-log-expander').click(function () {
        if ($(this).text() == 'Развернуть') {
            $(this).text('Свернуть');
            $('.js-log-line').show();
        } else {
            $(this).text('Развернуть');
            $('.js-log-line').hide();
            $('.js-log-line-0').show();
        }
    });

    var wasChanged = false;
    var changedElements = [];

    $('.js-was-changed, .js-was-changed > input, .js-was-changed > textarea, .js-was-changed > select').change(function () {
        wasChanged = true;
        changedElements.push($(this));
    });

    $("#order-form").submit(function () {
        wasChanged = false;
    });

    window.onbeforeunload = function (evt) {
        if (wasChanged) {
            var message = "Есть несохраннёные элементы, вы уверены, что хотите поикинуть страницу?";
            if (typeof evt == 'undefined') {
                evt = window.event;
            }
            if (evt) {
                evt.returnValue = message;
            }
            $.each(changedElements, function (i, element) {
                element.addClass('notSaved');
            });
            return message;
        }
    }

    $('.js-ajax-load-product').select2({
        ajax: {
            url: '/admin/orders/ajax_products_list',
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return {
                    q: params.term,
                    page: params.page
                };
            },
            processResults: function (data) {
                return {
                    results: $.map(data.items, function (item) {
                        return {
                            text: item.name,
                            id: item.id
                        }
                    })
                };
            }
        }
    }).change(function () {
        var product_id = $(this).val();
        var $offerContainer = $('.js-offer-container');
        if (product_id) {
            $offerContainer.show();
        } else {
            $offerContainer.hide();
        }
        $('.js-offer-container').css({opacity:.4});
        $.ajax({
            url: '/admin/orders/ajax_offers_list',
            data: {
                product_id: product_id
            },
            dataType: 'JSON',
            success: function (answer) {
                $('#offer_id option').remove();
                $.each(answer.items, function (index, data) {
                    $('#offer_id').append(
                        '<option value="' + data.id + '">' + data.option_values + '</option>'
                    );
                });
                $('.js-offer-container').css({opacity:1});
            },
            error: function (answer) {
                alert('Ошибка загрузки списка предложений');
                $('.js-offer-container').css({opacity:1});
            }
        });
    });
});