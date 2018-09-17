$(function() {
    // Add
    var add = function() {
        open(
            'Добавить промокод',
            {code_enabled: true}, {},
            BASE_URL + '/promotion'
        )
    };

    // Update
    var update = function(row) {
        open(
            'Редактировать промокод',
            row, {_method: 'PATCH'},
            BASE_URL + '/promotion/' + row.code_id
        )
    };

    // Open popup
    var open = function(caption, row, additional, action) {
        // Set form values
        $('#form')[0].reset();
        for (var name in row) {
            if (name == 'code_discount_unit') {
                $('#form').find('input[name="' + name + '"]').each(function() {
                    var active = $(this).val() == row[name];
                    $(this).attr('checked', active).parent().toggleClass('active', active);
                });
            } else if (name == 'code_enabled') {
                $('#' + name).attr('checked', !!row[name]).change();
            } else {
                $('#' + name).val(row[name]);
            }
        }
        $('#form').attr('action', action);

        // Additional
        $('#form_additional').empty();
        for (name in additional) {
            var input = $('<input type="hidden" name="' + name + '" />');
            input.val(additional[name]);
            $('#form_additional').append(input);
        }

        // Modal caption and show
        $('#modal').modal('show').find('.modal-title').text(caption);

        // Visibility control
        $('#form').find('input[name="code_discount_unit"]').change(function() {
            var checked = $('#form').find('input[name="code_discount_unit"]:checked').val();
            if (checked == '%' || checked === '') {
                $('#code_discount_value_group').removeClass('hidden');
            } else {
                $('#code_discount_value_group').addClass('hidden');
                $('#code_discount_value').val('');
            }
        });

        // On submit
        $('#modal_apply').click(function(){$('#form').submit()});
        $('#form').on('submit', function() {
            var values = $(this).serializeArray();

            // Get row
            var row = {};
            for (var i = 0, len = values.length; i < len; ++i) {
                row[values[i].name] = values[i].value;
            }

            // Validation
            var valid = true;
            for (i = 0, len = values.length; i < len; ++i) {
                var error = false;
                var $e = $('#' + values[i].name);

                // Condition
                var dep = $e.data('chck-dep');
                if (dep) {
                    var depf = new Function('r', 'return ' + dep);
                    if (!depf(row)) {
                        continue;
                    }
                }

                // Mandatory
                if ($e.hasClass('chk-mandatory') && values[i].value === '') {
                    valid = false;
                    error = true;
                }

                // Int
                if ($e.hasClass('chk-int') && (values[i].value !== '' && !/^\d+$/.test(values[i].value))) {
                    valid = false;
                    error = true;
                }

                // Set error
                $e.parents('.form-group').first().toggleClass('has-error', error);
            }

            if (!valid) {
                return false;
            } else {
                $('#modal_apply').attr('disabled', true);
            }
        });
    };

    // Add handler
    $('#add').click(add);

    // Date pickers
    $('#code_valid_from, #code_valid_till').datepicker({
        format: "yyyy-mm-dd",
        todayBtn: true,
        language: "ru"
    });

    // Edit
    $('.promotion-edit').click(function() {
        var row = $(this).data('row');
        var matches = row.code_discount.match(/^(\d+)(.*)$/i);
        row.code_discount_value = matches[1];
        row.code_discount_unit = matches[2];
        update(row);
    });

    // Remove
    $('.promotion-remove').click(function() {
        if (confirm('Вы действительно хотите удалить промокод?')) {
            $(this).next().submit();
        }
    });

    // Filter
    $('.form-filter').on('submit', function() {
        var form = $('<form method="get">');
        form.attr('action', BASE_URL + '/promotion');
        $('.form-filter input').each(function() {
            if ($(this).val()) {
                var $input = $('<input type="hidden">');
                $input.attr('name', $(this).attr('name'));
                $input.attr('value', $(this).val());
                form.append($input);
            }
        });
        form.appendTo($('body')).submit();
        return false;
    });
});
