$(function() {
    // On change input
    $('.oh-img-list input').on('change', function () {
        checkAll();
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
            selected.append($('<input type="hidden" name="id[]" value="' + $(e).data('name') + '">'));
        });
    });

    // Update popup
    function showUpdate() {
        var selected = $(getSelected()[0]);
        var id = selected.data('name');
        var caption = selected.data('caption');

        $('#popup-update-form').find('.selected').empty().append($('<input type="hidden" name="id[]" value="' + selected.data('name') + '">'));

        $('#popup-update').find('input[name="font_caption"]').val(caption);
        $('#popup-update').modal('show');
    }
    $('#btn-edit').on('click', showUpdate);
    $(".oh-img").on('dblclick', showUpdate).popover();
});
