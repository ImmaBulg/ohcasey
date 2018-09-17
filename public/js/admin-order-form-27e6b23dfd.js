$(function () {
    function searchTemplate(before, after)
    {
        var founded = null;
        $.each(SMS_TEMPLATES, function (index, template) {
            if ((template.before_order_status_id == before && template.after_order_status_id == after) || template.before_order_status_id == after) {
                founded = template;
                return founded;
            }
        });

        return founded;
    }

    var SAVED_STATUS = {};
    $.each(ORDERS, function (index, order) {
        SAVED_STATUS[order.order_id] = order.order_status_id;
        SAVED_STATUS[order.order_id + '_prev'] = order.order_status_id;
    });

    function sendSms(order_id)
    {
        var template = searchTemplate(SAVED_STATUS[order_id + '_prev'], SAVED_STATUS[order_id]);
        if (template) {
            $.post("/admin/sms_templates/send/" + order_id, {
                before_order_status_id: template.before_order_status_id,
                after_order_status_id: template.after_order_status_id
            });
        }
    }

    $('.oh-order-status').on('change', function(e) {
        var id = $(this).data('order-id');
        var newStatus = $(this).find('option:selected').val();
        var currentStatus = SAVED_STATUS[id];
        $.post("/admin/orders/" + id, {status: newStatus});
        var template = searchTemplate(currentStatus, newStatus);

        if(newStatus == 3 || newStatus == 6){
            $(this).closest('td')
                .next().text('Оплачен');
        }
        SAVED_STATUS[id] = newStatus;
        SAVED_STATUS[id + '_prev'] = currentStatus;
        console.log(template);
        if (template) {
            sendSms(id);
        }


        if (newStatus == 5) {
            newStatus = 15;
            currentStatus = 5;
            $.post("/admin/orders/" + id, {status: newStatus});
            SAVED_STATUS[id] = newStatus;
            SAVED_STATUS[id + '_prev'] = currentStatus;
        } else
            $(this).confirmation({
                onConfirm: function () {
                    sendSms(id);
                },
                title: 'Отправить СМС?',
                btnOkLabel: 'Да',
                btnOkIcon: 'fa fa-thumbs-o-up',
                btnCancelLabel: 'Нет',
                btnCancelIcon: 'fa fa-thumbs-o-down'
            }).confirmation('show');
    });

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

    $('#select_offer_id').select2();

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
                $('#select_offer_id option').remove();
                $.each(answer.items, function (index, data) {
                    console.log($('#select_offer_id'));
                    $('#select_offer_id').append(
                        "<option value='" + data.id + "'>" + data.option_values + "</option>"
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

    $('.js-select-offer').select2().change(function() {
        var id = $(this).data('id');
        var order_id = $(this).data('order');
        var select_id = $(this).val();
        $.ajax({
            url: '/admin/orders/cartSetProductUpdate',
            type: 'POST',
            data: {
                type: 'offer',
                cartSetProductId: id,
                selectId: select_id,
                orderId: order_id,
            },
            dataType: 'JSON',
            success: function(answer) {
                console.log(answer);
            },
            error: function(answer) {
                console.log(answer);
            }
        });
    });

    $('.js-select-size').select2().change(function() {
        var id = $(this).data('id');
        var order_id = $(this).data('order');
        var select_id = $(this).val();
        $.ajax({
            url: '/admin/orders/cartSetProductUpdate',
            type: 'POST',
            data: {
                type: 'size',
                cartSetProductId: id,
                selectId: select_id,
                orderId: order_id,
            },
            dataType: 'JSON',
            success: function(answer) {
                console.log(answer);
            },
            error: function(answer) {
                console.log(answer);
            }
        });
    });

    $('.js-select-print').select2({
    }).change(function() {
        var id = $(this).data('id');
        var order_id = $(this).data('order');
        var select_id = $(this).val();

        $.ajax({
            url: '/admin/orders/cartSetProductUpdate',
            type: 'POST',
            data: {
                type: 'print',
                cartSetProductId: id,
                selectId: select_id,
                orderId: order_id,
            },
            dataType: 'JSON',
            success: function(answer) {
                console.log(answer);
            },
            error: function(answer) {
                console.log(answer);
            }
        })
    });

    $('#delivery-choose-date').datepicker({
        format: "yyyy-mm-dd",
        todayBtn: true,
        language: "ru",
        todayHighlight: true,
    }).change(function() {
        var date= $('#delivery-choose-date').val();
        var hour = $('.js-select-hour').val();
        var minute = $('.js-select-minute').val();
        var hour_to = $('.js-select-hour_to').val();
        var minute_to = $('.js-select-minute_to').val();

        $.ajax({
            url: '/admin/orders/ajax_update_deliverytime',
            type: 'POST',
            data: {
                cartId: Object.keys(CART)[0],
                time: date + ' ' + ((parseInt(hour) < 10) ? '0' + hour : hour) + ':' + ((parseInt(minute) < 10) ? '0' + minute : minute),
                time_to: ((parseInt(hour_to) < 10) ? '0' + hour_to : hour_to) + ':' + ((parseInt(minute_to) < 10) ? '0' + minute_to : minute_to),
            },
            dataType: 'JSON',
            success: function(answer) {
                console.log(answer);
            },
            error: function(answer) {
                console.log(answer);
            }
        })
    });

    $('.js-select-hour').select2({
        minimumResultsForSearch: -1
    }).change(function() {
        var date= $('#delivery-choose-date').val();
        var hour = $('.js-select-hour').val();
        var minute = $('.js-select-minute').val();
        var hour_to = $('.js-select-hour_to').val();
        var minute_to = $('.js-select-minute_to').val();

        $.ajax({
            url: '/admin/orders/ajax_update_deliverytime',
            type: 'POST',
            data: {
                cartId: Object.keys(CART)[0],
                time: date + ' ' + ((parseInt(hour) < 10) ? '0' + hour : hour) + ':' + ((parseInt(minute) < 10) ? '0' + minute : minute),
                time_to: ((parseInt(hour_to) < 10) ? '0' + hour_to : hour_to) + ':' + ((parseInt(minute_to) < 10) ? '0' + minute_to : minute_to),
            },
            dataType: 'JSON',
            success: function(answer) {
                console.log(answer);
            },
            error: function(answer) {
                console.log(answer);
            }
        })
    });

    $('.js-select-minute').select2({
        minimumResultsForSearch: -1
    }).change(function() {
        var date= $('#delivery-choose-date').val();
        var hour = $('.js-select-hour').val();
        var minute = $('.js-select-minute').val();
        var hour_to = $('.js-select-hour_to').val();
        var minute_to = $('.js-select-minute_to').val();

        $.ajax({
            url: '/admin/orders/ajax_update_deliverytime',
            type: 'POST',
            data: {
                cartId: Object.keys(CART)[0],
                time: date + ' ' + ((parseInt(hour) < 10) ? '0' + hour : hour) + ':' + ((parseInt(minute) < 10) ? '0' + minute : minute),
                time_to: ((parseInt(hour_to) < 10) ? '0' + hour_to : hour_to) + ':' + ((parseInt(minute_to) < 10) ? '0' + minute_to : minute_to),
            },
            dataType: 'JSON',
            success: function(answer) {
                console.log(answer);
            },
            error: function(answer) {
                console.log(answer);
            }
        })
    });

    $('.js-select-hour_to').select2({
        minimumResultsForSearch: -1
    }).change(function() {
        var date= $('#delivery-choose-date').val();
        var hour = $('.js-select-hour').val();
        var minute = $('.js-select-minute').val();
        var hour_to = $('.js-select-hour_to').val();
        var minute_to = $('.js-select-minute_to').val();

        $.ajax({
            url: '/admin/orders/ajax_update_deliverytime',
            type: 'POST',
            data: {
                cartId: Object.keys(CART)[0],
                time: date + ' ' + ((parseInt(hour) < 10) ? '0' + hour : hour) + ':' + ((parseInt(minute) < 10) ? '0' + minute : minute),
                time_to: ((parseInt(hour_to) < 10) ? '0' + hour_to : hour_to) + ':' + ((parseInt(minute_to) < 10) ? '0' + minute_to : minute_to),
            },
            dataType: 'JSON',
            success: function(answer) {
                console.log(answer);
            },
            error: function(answer) {
                console.log(answer);
            }
        })
    });

    $('.js-select-minute_to').select2({
        minimumResultsForSearch: -1
    }).change(function() {
        var date= $('#delivery-choose-date').val();
        var hour = $('.js-select-hour').val();
        var minute = $('.js-select-minute').val();
        var hour_to = $('.js-select-hour_to').val();
        var minute_to = $('.js-select-minute_to').val();

        $.ajax({
            url: '/admin/orders/ajax_update_deliverytime',
            type: 'POST',
            data: {
                cartId: Object.keys(CART)[0],
                time: date + ' ' + ((parseInt(hour) < 10) ? '0' + hour : hour) + ':' + ((parseInt(minute) < 10) ? '0' + minute : minute),
                time_to: ((parseInt(hour_to) < 10) ? '0' + hour_to : hour_to) + ':' + ((parseInt(minute_to) < 10) ? '0' + minute_to : minute_to),
            },
            dataType: 'JSON',
            success: function(answer) {
                console.log(answer);
            },
            error: function(answer) {
                console.log(answer);
            }
        })
    });

    $('.js-date-send-picker').datepicker({
        format: "yyyy-mm-dd",
        todayBtn: true,
        language: "ru",
        todayHighlight: true,
    }).change(function() {
        var date = $(this).val();
        var id = $(this).parent().parent().parent().data('itemid');
        var order_id = $(this).parent().parent().parent().data('orderid');
        var type = $(this).parent().parent().parent().data('type');

        if (type === 'case')
        {
            $.ajax({
                url: '/admin/orders/ajax_case_update_print_info',
                type: 'POST',
                data: {
                    variable: 'date-send',
                    cartSetCase: id,
                    date: date,
                    orderId: order_id,
                },
                dataType: 'JSON',
                success: function(answer) {
                    console.log(answer);
                },
                error: function(answer) {
                    console.log(answer);
                }
            });
        } else {
            $.ajax({
                url: '/admin/orders/ajax_update_print_info',
                type: 'POST',
                data: {
                    variable: 'date-send',
                    cartSetProductId: id,
                    date: date,
                    orderId: order_id,
                },
                dataType: 'JSON',
                success: function(answer) {
                    console.log(answer);
                },
                error: function(answer) {
                    console.log(answer);
                }
            });
        }


    });

    $('.js-supposed-date-picker').datepicker({
        format: "yyyy-mm-dd",
        todayBtn: true,
        language: "ru",
        todayHighlight: true,
    }).change(function() {
        var date = $(this).val();
        var id = $(this).parent().parent().parent().data('itemid');
        var order_id = $(this).parent().parent().parent().data('orderid');
        var type = $(this).parent().parent().parent().data('type');

        if (type === 'case') {
            $.ajax({
                url: '/admin/orders/ajax_case_update_print_info',
                type: 'POST',
                data: {
                    variable: 'supposed-date',
                    cartSetCase: id,
                    date: date,
                    orderId: order_id,
                },
                dataType: 'JSON',
                success: function(answer) {
                    console.log(answer);
                },
                error: function(answer) {
                    console.log(answer);
                }
            });
        }else {
            $.ajax({
                url: '/admin/orders/ajax_update_print_info',
                type: 'POST',
                data: {
                    variable: 'supposed-date',
                    cartSetProductId: id,
                    date: date,
                    orderId: order_id,
                },
                dataType: 'JSON',
                success: function(answer) {
                    console.log(answer);
                },
                error: function(answer) {
                    console.log(answer);
                }
            });
        }
    });

    $('.js-date-back-picker').datepicker({
        format: "yyyy-mm-dd",
        todayBtn: true,
        language: "ru",
        todayHighlight: true,
    }).change(function() {
        var date = $(this).val();
        var id = $(this).parent().parent().parent().data('itemid');
        var order_id = $(this).parent().parent().parent().data('orderid');
        var type = $(this).parent().parent().parent().data('type');

        if (type === 'case') {
            $.ajax({
                url: '/admin/orders/ajax_case_update_print_info',
                type: 'POST',
                data: {
                    variable: 'date-back',
                    cartSetCase: id,
                    date: date,
                    orderId: order_id,
                },
                dataType: 'JSON',
                success: function(answer) {
                    console.log(answer);
                },
                error: function(answer) {
                    console.log(answer);
                }
            });
        } else {
            $.ajax({
                url: '/admin/orders/ajax_update_print_info',
                type: 'POST',
                data: {
                    variable: 'date-back',
                    cartSetProductId: id,
                    date: date,
                    orderId: order_id,
                },
                dataType: 'JSON',
                success: function(answer) {
                    console.log(answer);
                },
                error: function(answer) {
                    console.log(answer);
                }
            });
        }
    });

    $('.js-print-status-select').select2().change(function () {
        var selectId = $(this).val();
        var id = $(this).parent().parent().parent().data('itemid');
        var order_id = $(this).parent().parent().parent().data('orderid');
        var type = $(this).parent().parent().parent().data('type');

        if (type === 'case') {
            $.ajax({
                url: '/admin/orders/ajax_case_update_print_info',
                type: 'POST',
                data: {
                    variable: 'print-status',
                    cartSetCase: id,
                    selectId: selectId,
                    orderId: order_id,
                },
                dataType: 'JSON',
                success: function(answer) {
                    console.log(answer);
                },
                error: function(answer) {
                    console.log(answer);
                }
            });
        } else {
            $.ajax({
                url: '/admin/orders/ajax_update_print_info',
                type: 'POST',
                data: {
                    variable: 'print-status',
                    cartSetProductId: id,
                    selectId: selectId,
                    orderId: order_id,
                },
                dataType: 'JSON',
                success: function(answer) {
                    console.log(answer);
                },
                error: function(answer) {
                    console.log(answer);
                }
            });
        }

    });

    $('.edit-cost').click(function() {
        $('#old-cost').text($(this).data('cost'));
        $('.item-id').text($(this).data('id'));
        $('.item-id').attr('hidden', true);
        $('.save-cost').data('type', $(this).data('type') ? $(this).data('type') : 'product');
        $('#myModal').modal('toggle');
    });

    $('.save-cost').click(function() {
        let id = $('.item-id').text();
        let cost = $('.new-cost').val();
        let old_cost = $('#old-cost').text();
        let type = $(this).data('type');

        $.ajax({
            url: '/admin/orders/ajax_edit_item_cost',
            type: 'POST',
            data: {
                id: id,
                cost: cost,
                old_cost: old_cost,
                type: type,
            },
            dataType: 'JSON',
            success: function(answer) {
                console.log(answer);
                location.reload();
            },
            error: function(answer) {
                console.log(answer);
            }
        })
    });

    $('#delivery_price').change(function() {
       let id = $(this).data('id');
       let old_cost = $(this).data('cost');
       let cost = $(this).val();

       $.ajax({
           url: '/admin/orders/ajax_edit_delivery_cost',
           type: 'POST',
           data: {
               id: id,
               old_cost: old_cost,
               cost: cost,
           },
           dataType: 'JSON',
           success: function (answer) {
               console.log(answer);
           },
           error: function (answer) {
               console.log(answer);
           }
       })
    });

    $('.edit-count').click(function() {
        let id = $(this).data('id');
        let old_count = $(this).data('count');
        let type = $(this).data('type');
        $('#old-count').text(old_count);
        $('.save-count').data('count', old_count);
        $('.save-count').data('id', id);
        $('.save-count').data('type', type ? type : 'product');
        $('#myModalCount').modal('toggle');
    });

    $('.save-count').click(function() {
       let id = $(this).data('id');
       let old_count = $(this).data('count');
       let count = $('.new-count').val();
       let type = $(this).data('type');

       $.ajax({
           url: '/admin/orders/ajax_edit_item_count',
           type: 'POST',
           data: {
               id: id,
               old_count: old_count,
               count: count,
               type: type,
           },
           dataType: 'JSON',
           success: function (answer) {
               console.log(answer);
               location.reload();
           },
           error: function (answer) {
               console.log(answer);
           }
       })
    });


});