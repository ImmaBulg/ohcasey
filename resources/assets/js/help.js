/**
 * Help
 */
var Help = {};

/**
 * Visible
 * @type {boolean}
 */
Help.visible = false;

/**
 * Elements
 * @type {Array}
 */
Help.elements = [];

/*
 * Constants
 */
Help.P_LEFT = 'left';
Help.P_RIGHT = 'right';
Help.P_TOP_RIGHT = 'top-right';
Help.P_BOTTOM_RIGHT = 'bottom-right';

/**
 * Show help
 * @param v
 * @param complete
 */
Help.show = function (v, complete) {
    if (v !== Help.visible) {
        Help.visible = v;
        if (v) {
            $('#help').removeClass('hidden out').addClass('in').on('click', function () {
                Help.show(false);
            });
            complete && complete();
        } else {
            $('#help').removeClass('in').addClass('out').off('click');
            setTimeout(function () {
                $('#help').addClass('hidden');
                complete && complete();
            }, 150);
        }
    }
    complete && complete();
};

/**
 * Add hightlight element
 * @param e
 * @param content
 * @param position
 */
Help.addElement = function (e, content, position) {
    Help.showElement(e, content, position);
    Help.elements.push({
        e: e,
        content: content,
        position: position
    });
};

/**
 * Show highlighter
 * @param e
 * @param content
 * @param position
 */
Help.showElement = function (e, content, position) {
    var offset = $(e).offset();
    var width = $(e).width();
    var height = $(e).height();

    // Append rect
    $('#help-rect').append(Help._makeSVG('rect', {
        x: offset.left,
        y: offset.top,
        width: width,
        height: height,
        fill: 'black',
        filter: 'url(#help-drop-shadow)'
    }));

    // Append text
    var wHeight = $(window).height();
    var wWidth = $(window).width();
    var indent = 30;
    var textStyle = [];
    switch (position) {
        case Help.P_LEFT:
            textStyle.push('top: ' + (offset.top) + 'px');
            textStyle.push('right: ' + (wWidth - offset.left + indent) + 'px');
            // content.addClass('text-right');
            // content.append($('<div class="m-t-15"><img src="img/help-arrow.png"></div>'));
            break;
        case Help.P_BOTTOM_RIGHT:
            textStyle.push('top: ' + (offset.top + height + indent) + 'px');
            textStyle.push('right: ' + (wWidth - offset.left - width + indent) + 'px');
            break;
        case Help.P_TOP_RIGHT:
            textStyle.push('bottom: ' + (wHeight - offset.top + indent) + 'px');
            textStyle.push('right: ' + (wWidth - offset.left - width + indent) + 'px');
            // content.append($('<img src="img/help-arrow.png" class="img-t-r m-l-15 pull-right">'));
            break;
    }

    var $text = $('<div class="help-text-item" style="' + textStyle.join('; ') + '">' + +'</div>');
    $text.append(content);
    $('#help-text').append($text);
};

/**
 * Clear containers
 * @private
 */
Help._clear = function () {
    $('#help-rect').empty();
    $('#help-text').empty();
};

/**
 * Make SVG element
 * @param tag
 * @param attrs
 * @returns {Element}
 * @private
 */
Help._makeSVG = function (tag, attrs) {
    var el = document.createElementNS('http://www.w3.org/2000/svg', tag);
    for (var k in attrs)
        el.setAttribute(k, attrs[k]);
    return el;
};

/**
 * Remove all elements
 */
Help.clearElements = function (complete) {
    Help.show(
        false,
        function() {
            Help.elements = [];
            Help._clear();
            complete && complete();
        }
    );
};

/**
 * Redraw element when resize
 */
$(window).on('resize', function () {
    if (Help.visible && Help.elements.length) {
        Help._clear();
        for (var i = 0, len = Help.elements.length; i < len; ++i) {
            Help.showElement(Help.elements[i].e, Help.elements[i].content, Help.elements[i].position);
        }
    }
});
