$(function(){
    $('#report-range input').datepicker({
        format: "yyyy-mm-dd",
        todayBtn: true,
        language: "ru"
    });

    $('.btn-date').click(function () {
        $('#f_date_start').datepicker('update', $(this).data('from'));
        $('#f_date_end').datepicker('update', $(this).data('to'));
    })
});
