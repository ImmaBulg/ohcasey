$(function() {
    // On change input
    $('.oh-img-list input').on('change', function () {
        checkAll();
    });

    // Select all
    $('#btn-select-all').on('click', function () {
        selectAll(true);
    });
    $('#btn-unselect-all').on('click', function () {
        selectAll(false);
    });

    // Open upload popup
    $('#btn-upload').on('click', function() {
        $('#popup-upload').modal('show');
    });

    // File upload
    $('#ctl-upload').on('change', function() {
        $('#ctl-upload-count').text('Количество: ' + this.files.length);
    });

    // Upload
    $('#popup-upload-form').on('submit', function() {
        var files = $('#ctl-upload')[0].files.length;
        if (files == 0) {
            $(this).find('.btn-file').addClass('btn-danger');
            return false;
        } else {
            $(this).find('.btn-file').removeClass('btn-danger');
        }
    });

    // Remove
    $('#btn-remove-form').on('submit', function() {
        var selected = $(this).find('.selected');
        selected.empty();
        getSelected().each(function(i, e) {
            selected.append($('<input type="hidden" name="id[]" value="' + $(e).data('id') + '">'));
        });
    });

    // Btn edit
    function showUpdate() {
        var categories = [];
        var selected = $('#popup-update-form').find('.selected');
        selected.empty();
        getSelected().each(function(i, e) {
            selected.append($('<input type="hidden" name="id[]" value="' + $(e).data('name') + '">'));
            categories = categories.concat($(e).data('group'));
        });
        categories = unique(categories);
        $('#popup-update').find('input[type="checkbox"]').each(function(i, e) {
            if ($.inArray($(e).val(), categories) >= 0) {
                $(e).prop('checked', true);
                $(e).parent().addClass('active');
            } else {
                $(e).prop('checked', false);
                $(e).parent().removeClass('active');
            }
        });
        $('#popup-update').modal('show');
    }
    $('#btn-edit').on('click', showUpdate);

    // Init popovers and edit
    $(".oh-img").on('dblclick', function() {
        selectAll(false);
        $(this).find('input').prop('checked', true);
        checkAll();
        showUpdate();
    }).popover();

});
