// Helper initialization
var helper = {

    // For casey
    casey: function() {
        "use strict";

        // On color click
        $('#constructor').find('.helper-bottom .item').on('click', function () {
            $('#constructor').find('.helper-bottom .item.active').removeClass('active');
            $(this).addClass('active');
            DEVICE.setValue({color: $(this).attr('data-color-id')});
        });

        // Click handler
        $('.control-panel-case .case').on('click', function () {
            $('.control-panel-case .case.active').removeClass('active');
            $(this).addClass('active');
            var casey = $(this).attr('data-case');
            DEVICE.setValue({casey: casey});
            if (window.APP_ENV == 'production') metrikaGoals.caseySelected(casey);
        });

        // Get current color
        var getCurrentColor = function(currentCase) {
            var currentColor = DEVICE.getValue().color;
            if (
                currentColor === undefined
                || currentColor === null
                || currentColor === ''
                || $('#constructor').find('.helper-bottom .item:visible[data-color-id=' + currentColor + ']').length == 0
            ) {
                var colors = $('.control-panel-case .case[data-case="' + currentCase + '"]:visible').attr('data-color').split(',');
                currentColor = colors[0] || null;
            }
            return currentColor;
        };

        // Toggle colors
        var toggleColors = function(colors) {
            $('#constructor').find('.helper-bottom .item').each(function(i, e) {
                if (colors.indexOf($(e).attr('data-color-id')) == -1) {
                    $(e).hide();
                } else {
                    $(e).show();
                }
            });
        };

        // Current case
        var currentCase = DEVICE.getValue().casey;
        if (!currentCase || $('.control-panel-case .case[data-case="' + currentCase + '"]').length == 0) {
            currentCase = $('.control-panel-case .case').first().attr('data-case');
        }
        var colors = $('.control-panel-case .case[data-case="' + currentCase + '"]').attr('data-color').split(',');
        toggleColors(colors);

        // Current color
        var currentColor = getCurrentColor(currentCase);

        // Set case and color
        $('.control-panel-case .case[data-case="' + currentCase + '"]').addClass('active');
        $('#price-value').html($('.control-panel-case .case[data-case="' + currentCase + '"]').data('cost'));
        $('#constructor').find('.helper-bottom .item[data-color-id=' + currentColor + ']').addClass('active');
        DEVICE.setValue({
            casey: currentCase,
            color: currentColor
        });

        // Change available colors
        $('.control-panel-case .case').on('click', function () {
            var casey = $(this).data('case');
            var dataColors = $(this).data('color') + ''; // cast to string
            var colors;
            if (dataColors.indexOf(',') != -1) {
                colors = dataColors.split(',');
            } else {
                colors = [dataColors];
            }
            var cost = $(this).data('cost');
            toggleColors(colors);

            var color = getCurrentColor(casey);
            $('#constructor').find('.helper-bottom .item.active').removeClass('active');
            $('#constructor').find('.helper-bottom .item[data-color-id=' + color + ']').addClass('active');
            $('#price-value').html(cost);

            DEVICE.setValue({
                casey: casey,
                color: color
            });

            // Go to background
            window.location.hash = '#bg';
        });
    },

    // Background
    bg: function() {
        "use strict";
        var loadBg = function(cat) {
            $('.control-panel-bg .list').load(BASEURL + '/cp/bg/' + encodeURIComponent(cat), function() {
                // Current device
                $('.control-panel-bg .bg[data-bg="' + DEVICE.getValue().bg + '"]').addClass('active');

                // Change bg
                $('.control-panel-bg .list .bg').on('click', function() {
                    $('#constructor .helper-right .right').show();
                    $('.control-panel-bg .bg.active').removeClass('active');
                    $(this).addClass('active');
                    var bg = $(this).attr('data-bg');
                    DEVICE.setValue({bg: bg, type: 'system'});
                    if (window.APP_ENV == 'production') metrikaGoals.backgroundDeviceSelected(bg);
                });
            });
        };

        $(function(){
            $('.control-panel-bg .category .r-button span').on('click', function() {
                loadBg($(this).parent().find('input').attr('value'));
            });
            $('.control-panel-bg .list').css('top', $('.control-panel-bg .category').height() + 5);
            loadBg($('.control-panel-bg .category input:checked').attr('value'));
        });

        // Right helper
        $('#constructor .helper-right .right').toggle(DEVICE.getValue().bg != null)
        $('#constructor .helper-right .right .icon').on('click', function() {
            DEVICE.setValue({bg: null, type: 'system'});
            $('#constructor .helper-right .right').hide();
            $('.control-panel-bg .bg.active').removeClass('active');
        });
        $('.control-panel-bg .bg').on('click', function(){
            $('#constructor .helper-right .right').show();
        });

        // Upload
        // var upload = new ss.SimpleUpload({
        //     button: '#upload',
        //     url: BASEURL + '/upload',
        //     name: 'file',
        //     responseType: 'json',
        //     onSubmit: function() {
        //         loading(true);
        //     },
        //     onComplete: function(filename, response, uploadBtn, fileSize) {
        //         loading(false);
        //         $('#constructor .helper-right .right').show();
        //         $('.control-panel-bg .bg.active').removeClass('active');
        //         DEVICE.setValue({bg: response.file, type: 'user'});
        //     },
        //     onError: function() {
        //         loading(false);
        //     }
        // });
    },

    // Device
    device: function() {
        "use strict";
        // Current device
        $('.control-panel-device .device[data-device="' + DEVICE.getValue().device + '"]').addClass('active');

        // Click handler
        $('.control-panel-device .device').on('click', function(){
            $('.control-panel-device .device.active').removeClass('active');
            $(this).addClass('active');
            var device = $(this).attr('data-device');
            var mask = $(this).attr('data-mask') || [];
            DEVICE.setValue({device: device, mask: mask, casey: null});
            if (window.APP_ENV == 'production') metrikaGoals.deviceSelected(device);
            $('#price-name').html($(this).data('name'));
            $('#price-value').html();

            // Got to casey
            window.location.hash = '#casey';
        });
    },

    // Font
    font: function() {
        // Add text
        var addText = function(font) {
            var text = new Text({name: font});
            addLayout(text, TEXT);
            text.focus();
            $('#constructor').find('.helper-right .font-text input').focus().select();
        };

        // Font button handler
        $('#font-add').on('click', function() {
            var font = $('.control-panel-font .font').first().attr('data-font');
            addText(font);
        });

        // Font list handler
        $('.control-panel-font .font').on('click', function(e) {
            var font = $(this).attr('data-font');
            if (Layout.focused && Layout.focused instanceof Text) {
                Layout.focused.setValue({name: font});
            } else {
                addText(font);
            }
            e.stopPropagation();
        });

        // Set focused
        var setFocused = function(v) {
            if (v) {
                // Font text
                $('#constructor').find('.helper-right .font-text').show(true);
                $('#constructor').find('.helper-right .font-text input')
                    .val(v.getValue().text)
                    .off().on('input', function() {
                        v.setValue({text: $(this).val()})
                    }).on('click', function(e) {
                        e.stopPropagation();
                    });

                // Font color
                $('#constructor').find('.helper-right .font-color .color').off().on('click', function(e) {
                        $('#constructor').find('.helper-right .font-color .color.active').removeClass('active');
                        $(this).addClass('active');
                        v.setValue({color: $(this).attr("data-color")});
                        e.stopPropagation();
                    });
                $('#constructor').find('.helper-right .font-color .color.active').removeClass('active');
                $('#constructor').find('.helper-right .font-color .color[data-color="' + v.getValue().color + '"]').addClass('active');
                $('#constructor').find('.helper-right .font-color').show(true);
            } else {
                $('#constructor').find('.helper-right .font-size').hide();
                $('#constructor').find('.helper-right .font-text').hide();
                $('#constructor').find('.helper-right .font-color').hide();
            }
        };

        // Set current focused
        setFocused(Layout.focused);

        // On focus element
        onFocusedElement = function(v) {
            if (v !== false) {
                if (v && v instanceof Text) {
                    setFocused(v);
                } else {
                    setFocused(null);
                }
            }
        }
    },

    // Smile
    smile: function() {
        "use strict";
        var loadSmiles = function(tag) {
            $('.control-panel-smile .list').load(BASEURL + '/cp/smile/' + tag, function() {
                $('.control-panel-smile .list .smile').on('click', function() {
                    var smile = new Smile({name: $(this).attr('data-smile')});
                    addLayout(smile, SMILE);
                });
            });
        };

        $(function(){
            $('.control-panel-smile .category .r-button span').on('click', function() {
                loadSmiles($(this).parent().find('input').attr('value'));
            });
            $('.control-panel-smile .list').css('top', $('.control-panel-smile .category').height() + 5);
            loadSmiles($('.control-panel-smile .category input:checked').attr('value'));
        });

        // Upload
        // var upload = new ss.SimpleUpload({
        //     button: '#upload',
        //     url: BASEURL + '/upload',
        //     name: 'file',
        //     responseType: 'json',
        //     onSubmit: function() {
        //         loading(true);
        //     },
        //     onComplete: function(filename, response, uploadBtn, fileSize) {
        //         loading(false);
        //         var smile = new Smile({name: response.file, type: 'user'});
        //         addLayout(smile, SMILE);
        //     },
        //     onError: function() {
        //         loading(false);
        //     }
        // });
    },

    // Common
    common: function() {
        // Scrollbar
        $('.ps').perfectScrollbar();

        // Hide helper
        $('.helper-icon[data-hide="true"] .icon').click(function() {
            $(this).parent().next().toggleClass('hidden');
        });

        // Clear
        $('.helper-clear').click(function() {
            while (TEXT.length)  TEXT[0].rm();
            while (SMILE.length) SMILE[0].rm();

            DEVICE.setValue({bg: null});
        });

        // Push to cart button handler
        $('#make-order').toggleClass('disabled', !ENV.DEVICE || !ENV.DEVICE.device || !ENV.DEVICE.casey);

        $('#make-order').off().on('click', function() {
            if (!$(this).hasClass('disabled')) {
                loading(true);
                $(this).addClass('disabled');
                if (isAdminEdit) {
                    $.post(
                        '/admin/orders/' + editOrder.order_id + '/cartSetCase/' + editCase.cart_set_id + '/store',
                        {current: getEnv()},
                        function () {
                            saveEnv(null);
                            window.location.href = '/admin/orders/' + editOrder.order_id + '/#cartSetCase';
                        }
                    )
                } else {
                    $.post(
                        BASEURL + '/cart/put/case',
                        {current: getEnv()},
                        function () {
                            // Clear current case
                            saveEnv(null);

                            // Go to cart
                            window.location.href = BASEURL + '/custom/cart';
                        }
                    )
                }
            }
        });
    }
};
