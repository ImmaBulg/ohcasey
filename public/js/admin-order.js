$(function() {
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
        console.log(template);
        if (template) {
            $.post("/admin/sms_templates/send/" + order_id, {
                before_order_status_id: template.before_order_status_id,
                after_order_status_id: template.after_order_status_id
            });
        }
    }

    function deleteOrder(row){
        var id= row.data('order-id');
        row.addClass('danger');
        $.post("/admin/orders/" + id + "/delete")
            .done(function(response){
                row.hide();
        });
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
        if (template) {
            sendSms(id);
        }



        if (newStatus == 5) {
            newStatus = 15;
            currentStatus = 5;
            $.post("/admin/orders/" + id, {status: newStatus}, function(data) {
                console.log(data);
            });
            SAVED_STATUS[id] = newStatus;
            SAVED_STATUS[id + '_prev'] = currentStatus;
        }
    });

    $('#report-range input').datepicker({
        format: "yyyy-mm-dd",
        todayBtn: true,
        language: "ru"
    });

    $('.btn-date').click(function () {
        $('#f_date_start').datepicker('update', $(this).data('from'));
        $('#f_date_end').datepicker('update', $(this).data('to'));
    });

    $('.btn-delete').click(function(){
        var row = $(this).closest('.order_row')
        $(this).confirmation({
            placement: 'left',
            onConfirm: function () {
                deleteOrder(row);
            },
            title: 'Удалить заказ?',
            btnOkLabel: 'Да',
            btnOkIcon: 'fa fa-thumbs-o-up',
            btnCancelLabel: 'Нет',
            btnCancelIcon: 'fa fa-thumbs-o-down'
        }).confirmation('show');

    });

});
