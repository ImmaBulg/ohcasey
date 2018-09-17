$(document).ready(function() {
    $('#report-range input').datepicker({
        format: "yyyy-mm-dd",
        todayBtn: true,
        language: "ru"
    });

    $('#f_date').datepicker({
        format: "yyyy-mm-dd",
        todayBtn: true,
        language: "ru"
    });

    $('#f_print_date').datepicker({
        format: "yyyy-mm-dd",
        todayBtn: true,
        language: "ru"
    });

    $('#f_delivery_date').datepicker({
        format: "yyyy-mm-dd",
        todayBtn: true,
        language: "ru"
    });


    $('.btn-date').click(function () {
        $('#f_date_start').datepicker('update', $(this).data('from'));
        $('#f_date_end').datepicker('update', $(this).data('to'));
    });

    $('.print_status_select').select2();

    $('.print_status_select').change(function() {
        let id = $(this).data('id');
        let status_id = $(this).val();

        $.ajax({
            url: '/admin/updatePrintStatus',
            type: 'POST',
            data: {
                id: id,
                print_status: status_id,
            },
            success: function(answer) {
                console.log(answer);
            },
            error: function(answer) {
                alert('Error');
            }
        })
    });
});