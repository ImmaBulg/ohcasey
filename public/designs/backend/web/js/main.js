var BASE_URL = '/designs';
//var BASE_URL = '';
$(function () {
    $( ".sortable" ).sortable({
        connectWith:".connectedSortable",
        items: ".sortable-item",
    }).disableSelection(); // для отмены выделения текста на элементах


    $('.option-wrapper').on('click', '.delete-case-file', function(ev){
        ev.preventDefault();
        $(this).closest('li').remove();
    });

    $('.case-design-form').submit(function (e) {
        // e.preventDefault();
        var $this = $(this),
            selectedValues = [],
            $selectedItems = $this.find('.selected-list .selected-list__file-name');

        if(!$selectedItems.length){
            $('.modal-message').text('Не выбрано ни одного файла');
            $('#small-modal').modal('show');
            e.preventDefault();
            return false;
        }

        $selectedItems.each(function (indx) {
            var $item = $(this),
                value = {},
                link = $item.closest('li').find('input').val();

            if(indx === 0 && link.length === 0){
                $('.modal-message').text('Поле ссылка первого файла не может быть пустым');
                $('#small-modal').modal('show');
                e.preventDefault();
                return false;
            }

            value.file = $item.text();
            value.link = link;
            selectedValues.push(value);
        });

        $('input[name="selectedFiles"]').val(JSON.stringify(selectedValues));

        $('[name="fast-load-file"]').val('');

    });


    $('.add-image-popup').on('click', function (e) {
        e.preventDefault();
        $('.select-image-popup').stop(true).fadeIn(400);
    });

    $('.input-image-popup').on('change', function (e) {
        $('.selected-image-preview').attr('src', $(this).val());
        $(this).closest('.select-image').addClass('select-image__selected')
            .siblings('.select-image').removeClass('select-image__selected');
    });

    $('.close-select-image-popup').on('click', function (e) {
        e.preventDefault();
        $('.select-image-popup').stop(true).fadeOut(400);
    });

    /*Быстрая загрузка файла в форме дизайнов кейсов*/
    $('[name="fast-load-file"]').on('change', function (ev) {
        var $this = $(this);
        if(!this.files.length){
            $this
                .siblings('span').text('Загрузить файл')
                .closest('label').siblings('.fast-download-btn').addClass('disabled');
            return false;
        }

        $this.siblings('span').text(this.files[0].name.substring(0, 15))
            .closest('label').siblings('.fast-download-btn').removeClass('disabled');
    });

    $('.fast-download-btn').on('click', function (e) {
        e.preventDefault();
        var $this = $(this);
        if($this.hasClass('disabled')) return false;

        var formData = new FormData;
        formData.append('image', $('[name="fast-load-file"]').get(0).files[0]);

        //upload start

        $this.add($this.siblings('label')).addClass('disabled');

        $.ajax({
            url: BASE_URL + '/backend/web/case-design/upload',
            type: 'post',
            data: formData,
            contentType: false,
            processData: false,
            success: function(data){
                var res = JSON.parse(data);
                $this.siblings('label').removeClass('disabled')
                    .find('input').val('')
                    .siblings('span').text('Загрузить файл');
                $('.upload-succes-msg').stop(true).show().fadeOut(4000);
                var html = '';
                if(res.fileType === 'image'){
                    html = '<li class="sortable-item ui-sortable-handle sortable-item-flex">\
                        <div class="sortable-item__main">\
                            <img src="'+BASE_URL+res.filePath+'" alt=""> <span class="selected-list__file-name">'+res.fileName+'</span>\
                        </div>\
                        <div class="sortable-item__input">\
                            <label class="control-label">\
                                Ссылка\
                                <input type="text" value="" maxlength="255" class="form-control">\
                            </label>\
                        </div>\
                        <div>\
                            <a href="#" class="btn btn-danger delete-case-file">Удалить</a>\
                        </div>\
                    </li>';
                }else{
                    html = '<li class="sortable-item ui-sortable-handle sortable-item-flex">\
                        <div class="sortable-item__main">\
                            <img src="'+BASE_URL+'/img/camera.png" alt=""> <span class="selected-list__file-name">'+res.fileName+'</span>\
                        </div>\
                        <div class="sortable-item__input">\
                            <label class="control-label">\
                                Ссылка\
                                <input type="text" value="" maxlength="255" class="form-control">\
                            </label>\
                        </div>\
                        <div>\
                            <a href="#" class="btn btn-danger delete-case-file">Удалить</a>\
                        </div>\
                    </li>';
                }
                $('.selected-list').append(html);
            },
            error: function(error){
                console.error('---', error);
            },
        })

    });
    /*Быстрая загрузка конец*/

    /**
     * Фльтрация тэгов в формме
     */
    $('.hashtag-search').on('input', function (e) {
        var $this = $(this),
            val = $this.val(),
            $hashtags = $this.closest('.custom-filter-wrapper').siblings('.option-wrapper').find('.hashtag-text');

        $hashtags.each(function () {
            var $hashtag = $(this);
            $hashtag.html().indexOf(val) !== -1 ? $hashtag.closest('.form-group').show() : $hashtag.closest('.form-group').hide();
        });

        var $addButton = $this.closest('.form-group').siblings('.add-hashtag-btn');

        val.length < 2 ? $addButton.addClass('disabled') : $addButton.removeClass('disabled');

    });
    /**
     * Добавление тэгов из формы дизайнов
     */
    $('.add-hashtag-btn').on('click', function (e) {
        e.preventDefault();
        var $this = $(this);
        if($this.hasClass('disabled')) return false;

        var hashTag = $this.siblings('.form-group').find('input').val();


        $('.hashtag-description-popup').stop(true).fadeIn(400);

    });

    $('#add-hashtag-btn').on('click', function(e){
        e.preventDefault();
        var $this = $(this),
            $btn = $('.add-hashtag-btn');
        var hashTag = $btn.siblings('.form-group').find('input').val();
        var $descr = $('#add-hashtag-description');

        $this.addClass('disabled');

        $.ajax({
            url: BASE_URL + '/backend/web/hashtag/ajax-create',
            type: 'post',
            data: { hashTag: hashTag, description: $descr.val() },
            success: function (data) {
                var res = JSON.parse(data);
                if(!res.success){
                    $('.modal-message').text(res.error);
                }else{
                    $('.modal-message').text('Хэштег добавлен');
                    var newTagHtml = '<div class="form-group" style="">\
                        <label><input type="checkbox" name="hashtag[]" value="'+res.id+'"><span class="hashtag-text">'+res.hashTag+'</span></label>\
                    </div>';
                    $btn.closest('.custom-filter-wrapper')
                        .siblings('.option-wrapper').prepend(newTagHtml);
                    $('.hashtag-description-popup').stop(true).fadeOut(400);
                    $descr.val('');
                }
                $('#small-modal').modal('show');
                $this.removeClass('disabled');
                $('.hashtag-search').removeAttr('disabled');
            },
        });

    });

    $('#add-hashtag-cancel').on('click', function(e){
        e.preventDefault();
        $('.hashtag-description-popup').stop(true).fadeOut(400);
    });

    /**
     * Сортировка дизайнов перетаскиванием в GridView
     */
    $( "#case-grid-view tbody" ).sortable({
        connectWith:".connectedSortable",
        items: ".sortable-row",
        update: caseSortUpdateCallback,
    }).disableSelection(); // для отмены выделения текста на элементах


  /*  $(document).mouseup(function(e){
        var $autoSelect = $('.auto-select.is-focused');
        if(!$autoSelect.parent().has(e.target).length){
            $autoSelect.removeClass('is-focused');
        }
    });*/

    $('.auto-select')
        .on('focus', function(){
            var $this = $(this);
            $this.addClass('is-focused')
        })
        .on('blur', function(){
            var $this = $(this);
            setTimeout(function() {
                $this.removeClass('is-focused')
            }, 150);
        })
        .on('input', function(){
        var $this = $(this),
            val = $this.val().toLowerCase(),
            $existingValues = $this.siblings('.existing-fields').find('.existing-fields__value');
        $existingValues.each(function () {
            var $item = $(this);
            $item.text().toLowerCase().indexOf(val) < 0 ? $item.hide() : $item.show();
        });
    });

    $('.existing-fields__value').on('click', function (e) {
        e.preventDefault();
        var $this = $(this);
        $this.closest('.existing-fields').siblings('.auto-select').val($this.text()).trigger('input');
    });

});

function caseSortUpdateCallback(event, ui){
    var elementId = ui.item.attr("data-key"),
        $prevItem = ui.item.prev(),
        $nextItem = ui.item.next(),
        $body = $('body');

    var prevId = $prevItem.length ? $prevItem.attr("data-key") : null;
    var nextId = $nextItem.length ? $nextItem.attr("data-key") : null;

    $body.addClass('ajax-loading');

    $.ajax({
        url: BASE_URL + '/backend/web/case-design/sort-items',
        type: 'patch',
        data: {
            elementId: elementId,
            prevId: prevId,
            nextId: nextId
        },
        success: function(data){
            $body.removeClass('ajax-loading');
        },
        error: function (err) {
            $body.removeClass('ajax-loading');
        }
    })
}