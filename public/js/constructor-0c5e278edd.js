// Global objects
var DEVICE = null;
var SMILE = [];
var TEXT = [];

/**
 * On focused element
 */
var onFocusedElement = null;

/**
 * Render layout
 * @param layout
 */
var renderLayout = function(layout) {
    "use strict";
    layout.render(DEVICE.canvas);
    layout.renderTarget(DEVICE.masked);
    layout.onFocus.push(function(v) {
        // DEVICE.sortMasked();
        onFocusedElement && onFocusedElement(v)
    });
    layout.onBlur.push(function() {
        onFocusedElement && onFocusedElement(false);
    });
    layout.onRemove.push(function() {
        layout.blur();
        layout.unrender();
        onFocusedElement && onFocusedElement(null);
    });
};

/**
 * Add layout raw
 * @param layout
 * @param storage
 */
var addLayoutRaw = function(layout, storage) {
    renderLayout(layout);
    storage.push(layout);
    layout.onRemove.push(function() {
        storage.splice(storage.indexOf(layout), 1);
        updateEnv();
    });
    layout.onChange.push(function() {
        updateEnv();
    });
};

/**
 * Add layot
 * @param layout
 * @param storage
 */
var addLayout = function(layout, storage) {
    addLayoutRaw(layout, storage);
    updateEnv();
    DEVICE.sortMasked();
};

/**
 * Environment
 */
var ENV = {};

/**
 * Update env
 */
var updateEnv = function() {
    // Save device
    ENV.DEVICE = DEVICE.getValue();

    // Save text
    ENV.TEXT = [];
    for (var i = 0, len = TEXT.length; i < len; ++i) {
        ENV.TEXT.push(TEXT[i].getValue())
    }

    // Save smiles
    ENV.SMILE = [];
    for (i = 0, len = SMILE.length; i < len; ++i) {
        ENV.SMILE.push(SMILE[i].getValue())
    }

    // Save env
    saveEnv(ENV);
    updateMenu();
    updateOrderControl();
};

/**
 * Update menu
 */
var updateMenu = function() {
    var menu = $("#header-menu");
    menu.find(".item").removeClass('active');
    if (ENV.DEVICE) {
        ENV.DEVICE.device && menu.find(".item[data-menu='device']").addClass('active');
        ENV.DEVICE.casey && menu.find(".item[data-menu='casey']").addClass('active');
        ENV.DEVICE.bg && menu.find(".item[data-menu='bg']").addClass('active');
    }
    ENV.SMILE && ENV.SMILE.length && menu.find(".item[data-menu='smile']").addClass('active');
    ENV.TEXT && ENV.TEXT.length && menu.find(".item[data-menu='font']").addClass('active');
};

/**
 * Update order controls
 */
var updateOrderControl = function() {
    // Menu
    var menuIndex = -1;
    var menu = $('#header-menu').find('a');
    for (var i = 0, len = menu.length; i < len; ++i) {
        if (BASEURL + '/' + window.location.hash == $(menu[i]).attr('href')) {
            menuIndex = i;
            break;
        }
    }

    // Next button
    $('#control-next')
        .toggleClass('disabled', menuIndex == -1 || menuIndex >= menu.length - 1)
        .off()
        .on('click', function() {
            if (!$(this).hasClass('disabled')) {
                window.location.href = $(menu[menuIndex + 1]).attr('href');
            }
        });
};

/**
 * Async seq
 */
var seq = 0;

/**
 * Routing
 * @param name
 */
var route = function(name) {
    // Route to device if device is null
    if (name !== 'device' && (!ENV || !ENV.DEVICE || !ENV.DEVICE.device)) {
        window.location.hash = '#device';
        return;
    }

    // Route to case if case is null
    if (
        name !== 'device'
        && name !== 'casey'
        && (!ENV || !ENV.DEVICE || !ENV.DEVICE.casey || ENV.DEVICE.color === '')
    )
    {
        window.location.hash = '#casey';
        return;
    }

    // Check valid route
    if ($("#header-menu").find('a[data-name="' + name + '"]').length == 0) {
        window.location.href = '#' + $("#header-menu").find('a').first().data('name');
        return;
    }

    // Update order controls
    updateOrderControl();

    // Active menu
    updateMenu();
    $("#header-menu").find(".item.selected").removeClass('selected');
    $("#header-menu").find(".item[data-menu='" + name + "']").addClass('selected');

    // Load helper
    ++seq;
    onFocusedElement = null;
    $('#control-panel').empty();
    $('#constructor').find('.helper').empty();
    $('#help').empty();
    $.when(
        $.post( BASEURL + '/cp/' + name + '/helper', {'current': getEnv()}),
        $.post( BASEURL + '/cp/' + name , {'current': getEnv()})
    ).done((function( s, name, a1, a2 ) {
        if (s == seq) {
            // Draw helpers
            var helpers = $(a1[0]);
            helpers.filter('.left').appendTo($('#constructor .helper-left'));
            helpers.filter('.right').appendTo($('#constructor .helper-right'));
            helpers.filter('.bottom').appendTo($('#constructor .helper-bottom'));
            helpers.filter('.help').appendTo($('#help'));

            // Draw control panel
            $('#control-panel').html(a2[0]);

            // Common initializer
            helper.common();

            // Init helper
            helper[name]();

            // // Show help
            // Help.clearElements(function() {
            //     var cookie = 'oh-help-' + name;
            //     if (true || !Cookies.get(cookie)) {
            //         Cookies.set(cookie, '1', {expires: 720});
            //         switch (name) {
            //             case 'device':
            //                 Help.addElement('#control-panel', '<div style="margin-top: 70px" class="text-right"><div class="m-b-15"><img class="help-img-b-r" src="img/help-arrow.png"></div>Выбери свой телефон</div>', Help.P_LEFT);
            //                 Help.show(true);
            //                 break;
            //             case 'casey':
            //                 Help.addElement('#control-panel', '<div style="margin-top: 70px" class="text-right"><div class="m-b-15"><img class="help-img-b-r" src="img/help-arrow.png"></div>На каком чехле изготовить?</div>', Help.P_LEFT);
            //                 Help.addElement('.control-panel-case-color', '<div class="text-right">Выбери цвет телефона <div class="m-l-15 pull-right"><img class="help-img-l-b" src="img/help-arrow.png"></div></div>', Help.P_TOP_RIGHT);
            //                 Help.show(true);
            //                 break;
            //             case 'bg':
            //                 Help.addElement('#control-panel', '<div style="margin-top: 70px" class="text-right"><div class="m-b-15"><img class="help-img-b-r" src="img/help-arrow.png"></div>Жми на категории<br>выбери дизайн</div>', Help.P_LEFT);
            //                 Help.addElement('#header-menu div[data-menu="font"]', '<div class="m-t-15">Добавляй текст<div class="pull-right m-l-15"><img class="help-img-b-r" width="50" src="img/help-arrow.png"></div></div>', Help.P_LEFT);
            //                 Help.show(true);
            //                 break;
            //             case 'font':
            //                 Help.addElement('#control-panel', '<div style="margin-top: 70px" class="text-right"><div class="m-b-15"><img class="help-img-b-r" src="img/help-arrow.png"></div>Выбирай шрифт</div>', Help.P_LEFT);
            //                 Help.addElement('#header-menu div[data-menu="smile"]', '<div class="m-t-15">Добавляй смайлы<div class="pull-right m-l-15"><img class="help-img-b-r" width="50" src="img/help-arrow.png"></div></div>', Help.P_LEFT);
            //                 Help.show(true);
            //                 break;
            //             case 'smile':
            //                 Help.addElement('#control-panel', '<div style="margin-top: 70px" class="text-right"><div class="m-b-15"><img class="help-img-b-r" src="img/help-arrow.png"></div>Жми на категории<br>выбери смайлы</div>', Help.P_LEFT);
            //                 Help.show(true);
            //                 break;
            //         }
            //     }
            // });
        }
    }).bind(null, seq, name));
};

// Prepare constructor
$(function(){
    // Set env
    ENV = getEnv() || {};

    // Set base element for mouse events
    Element.base = $('.centerer');

    // Add routes
    $(window).on('hashchange', function() {
        route((window.location.hash || '').slice(1));
    });
    route((window.location.hash || '').slice(1));

    if (window.googleAbTest) {
        window.googleAbTest();
    }

    // Device
    DEVICE = new Device(ENV.DEVICE);
    DEVICE.render($('#constructor-place'));
    DEVICE.onChange.push(function() {
        updateEnv();
    });

    // Smiles
    var smiles = ENV.SMILE || [];
    for (var i = 0, len = smiles.length; i < len; ++i) {
        var smile = new Smile(smiles[i]);
        addLayoutRaw(smile, SMILE);
    }

    // Text
    var texts = ENV.TEXT || [];
    for (var i = 0, len = texts.length; i < len; ++i) {
        var text = new Text(texts[i]);
        addLayoutRaw(text, TEXT);
    }

    // Sort by zIndex
    DEVICE.sortMasked();

    // Update menu
    updateMenu();

    // Blur focused elements while click main
    $('#constructor').on('click', function() {
        Layout.focused && Layout.focused.blur();
    });

    $.post(BASEURL + '/info', {current: getEnv()}, function(response) {
        $('#price-name').html(response.priceName);
        $('#price-value').html(response.priceValue);
    });
});
