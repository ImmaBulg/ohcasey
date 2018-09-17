/**
 * Ajax setup
 */
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

/**
 * Env name
 */
var ENVNAME = 'ohcaseyconstr';
var LOCAL_ENV = {};

/**
 * Save env
 */
var saveEnv = function(env) {
    if (store.enabled) {
        store.set(ENVNAME, env);
    } else {
        LOCAL_ENV = env;
    }
    $.post(BASEURL + '/save', {current: env});
};

/**
 * Get env
 */
var getEnv = function() {
    if (store.enabled) {
        return store.get(ENVNAME);
    } else {
        return LOCAL_ENV;
    }
};

/**
 * Move cookie to store
 */
(function() {
    var env = Cookies.getJSON(ENVNAME);
    if (env) {
        saveEnv(env);
        Cookies.remove(ENVNAME);
    }
})();

/**
 * Constructor element
 */
var Element = function (attrs) {
    /**
     * Internal value object
     */
    this.value = {};
    $.extend(this.value, attrs || {});

    /**
     * Defaults
     * @type {{}}
     */
    var defaults = this.getDefaults();
    for (var i in defaults) {
        if (this.value[i] === undefined) {
            this.value[i] = defaults[i];
        }
    }

    /**
     * Base sizes
     */
    this.baseWidth = 247;
    this.baseHeight = 487;

    /**
     * Get value
     */
    this.getValue = function () {
        return this.value
    };

    /**
     * On change
     */
    this.onChange = [];

    /**
     * Update value
     */
    this.updateValue = function (attrs) {
        $.extend(this.value, attrs || {});
        this.update();
    };

    /**
     * Set value
     * @param attrs
     */
    this.setValue = function (attrs) {
        this.updateValue(attrs);
        $.each(this.onChange, function(i, v) {v()});
    };

    /**
     * Get content
     */
    this.getContent = function () {
    };

    /**
     * Update content
     */
    this.update = function () {
    };

    /**
     * Create objects
     */
    this.createObjects = function () {
    };

    /**
     * Render
     */
    this.render = function (target) {
        if (!this.canvas) this.createObjects();
        this.update();
        $(target).append(this.canvas);
    };

    /**
     * Unrender
     */
    this.unrender = function () {
        $(this.canvas).remove();
    };

    /**
     * Catch mouse
     */
    this._catchMouse = function (e, action, stop) {
        var X0 = e.pageX || e.originalEvent.touches[0].pageX;
        var Y0 = e.pageY || e.originalEvent.touches[0].pageY;

        // Action
        var _action = function (e) {
            action(
                e, X0, Y0,
                e.pageX || e.originalEvent.touches[0].pageX,
                e.pageY || e.originalEvent.touches[0].pageY
            );
            e.stopImmediatePropagation();
            e.stopPropagation();
            return false;
        };

        // Unbind
        var _unbind = function () {
            $(Element.base || document).off('touchmove mousemove', _action);
            $(Element.base || document).off('touchend touchcancel mouseup', _unbind);
            stop && stop();
        };

        // Events
        $(Element.base || document).on('touchmove mousemove', _action);
        $(Element.base || document).on('touchend touchcancel mouseup', _unbind);
    };
};
Element.base = null;
Element.prototype.getDefaults = function() {return {}};

/**
 * Device
 */
var Device = function (attrs) {
    Element.call(this, attrs);

    /**
     * Create objects
     */
    this.createObjects = function () {
        this.canvas = document.createElement('div');
        this.canvas.className = 'constructor';

        $(this.canvas).html(
            '<svg class="constructor-device" width="' + this.baseWidth + 'px" height="' + this.baseHeight + 'px" baseProfile="full" version="1.2">' +
            '<defs>' +
            '<mask id="constructor-mask" maskUnits="userSpaceOnUse" maskContentUnits="userSpaceOnUse" transform="scale(1)">' +
            '<image width="100%" height="100%" xlink:href="" />' +
            '</mask>' +
            '</defs>' +
            '<g class="constructor-content"></g>' +
            '<g class="constructor-masked" mask="url(#constructor-mask)"></g>' +
            '</svg>'
        );
        this.mask = $(this.canvas).find('defs image');
        this.body = $(this.canvas).find('g.constructor-content');
        this.masked = $(this.canvas).find('g.constructor-masked');
    };

    /**
     * Append masked
     */
    this.appendMasked = function(child) {

    };

    /**
     * Update content
     */
    this.update = function () {
        // Mask
        this.mask.attr(
            'xlink:href',
            URL_STORAGE +
            '/device/' +
            this.value.device +
            '/mask' +
            (this.value.mask && this.value.mask.indexOf(this.value.casey) !== -1 ? '_' + this.value.casey : '')
            + '.png'
        );

        // Device type
        var device = this.body.find('.constructor-device');
        var url = URL_STORAGE + '/device/' + this.value.device + '/device.png';
        if (device.length) {
            device.attr('href', url);
        } else {
            var image = document.createElementNS('http://www.w3.org/2000/svg', 'image');
            $(image).attr({
                'class': 'constructor-device',
                'width': '100%',
                'height': '100%'
            });
            image.setAttributeNS('http://www.w3.org/1999/xlink', 'href', url);
            this.body.append(image);
        }

        // Case
        var casey = this.body.find('.constructor-casey');
        var url = URL_STORAGE + '/device/' + this.value.device + '/color/' + this.value.casey + (this.value.color ? '_' + this.value.color : '') + '.png';
        if (this.value.casey) {
            if (casey.length) {
                casey.attr('href', url);
            } else {
                var image = document.createElementNS('http://www.w3.org/2000/svg', 'image');
                $(image).attr({
                    'class': 'constructor-casey',
                    'width': '100%',
                    'height': '100%'
                });
                image.setAttributeNS('http://www.w3.org/1999/xlink', 'href', url);
                this.body.append(image);
            }
        } else {
            casey.remove();
        }

        // Background
        var background = this.masked.find('.constructor-bg');
        var url = URL_STORAGE + (this.value.type == 'user' ? '/upload/' : '/sz/bg/500/') + this.value.bg;
        if (this.value.bg) {
            if (background.length) {
                background.attr('href', url);
            } else {
                var image = document.createElementNS('http://www.w3.org/2000/svg', 'image');
                $(image).attr({
                    'class': 'constructor-bg',
                    'width': '100%',
                    'height': '100%',
                    'mask': 'url(#constructor-mask)',
                    'zindex': 5
                });
                image.setAttributeNS('http://www.w3.org/1999/xlink', 'href', url);
                this.masked.append(image);
                this.sortMasked();
            }
        } else {
            background.remove();
        }
    };

    /**
     * Sort nodes in masked area
     */
    this.sortMasked = function() {
        "use strict";
        $(this.masked).children().sort(function(e1, e2) {
            return $(e1).attr('zindex') - $(e2).attr('zindex');
        }).appendTo(this.masked);
    }
};
Device.prototype.getDefaults = function() {
    var defaults = Element.prototype.getDefaults();
    // defaults.device = 'iphone7';
    defaults.device = 'iphonex';
    defaults.color = null;
    defaults.casey = null;
    defaults.bg = null;
    defaults.mask = [];
    return defaults;
};

/**
 * Layout class
 */
var Layout = function (attrs) {
    Element.call(this, attrs);

    // Options
    this.readOnly = false;
    this.canResize = true;
    this.canRotate = true;

    /**
     * Events
     * @type {*|Function}
     */
    this.onRemove = [];
    this.onFocus = [];
    this.onBlur = [];
    this.onLoad = [];

    /**
     * On resize
     */
    this.onResize = function(w0, h0) {};

    /**
     * Remove
     */
    this.rm = function() {
        $.each(this.onRemove, function (i, v) {
            v()
        });
    };

    /**
     * Focus element
     */
    this.focus = function() {
        "use strict";
        // this.value.zIndex = ++Layout.zIndex + 10;
        // this.target.setAttributeNS('http://www.w3.org/1999/xlink', 'zindex', this.value.zIndex.toString());
        Layout.focused && Layout.focused.blur();
        Layout.focused = this;
        $(this.canvas).addClass('active');
        $.each(this.onFocus, (function(i, v) {v(this)}).bind(this));
    };

    /**
     * Blur
     */
    this.blur = function() {
        $(this.canvas).removeClass('active');
        $.each(this.onBlur, (function (i, v) {v(this)}).bind(this));
        if (Layout.focused == this) {
            Layout.focused = null;
        }
    };

    /**
     * Create objects
     */
    this.createObjects = function () {
        // Main object
        this.canvas = document.createElement('div');
        this.canvas.className = 'constructor-layout';
        this.canvas.style.zIndex = this.value.zIndex;

        if (this.readOnly) {
            $(this.canvas).addClass('readonly');
        } else {
            this.canvas.tabIndex = -1;
            $(this.canvas).on('touchstart mousedown', (function (e) {
                this.focus();
            }).bind(this)).on('click', function(e) {
                return false;
            });

            // Move ctl
            this.move = document.createElement('div');
            this.move.className = 'icon-move';
            $([this.move, this.canvas]).on('touchstart mousedown', (function (event) {
                $(this.canvas).focus();
                $(this.move).addClass('active');

                var top0 = this.baseHeight * this.value.top;
                var left0 = this.baseWidth * this.value.left;
                this._catchMouse(
                    event,
                    (function (event, X0, Y0, X, Y) {
                        this.updateValue({
                            top: (top0 + Y - Y0) / this.baseHeight,
                            left: (left0 + X - X0) / this.baseWidth
                        });
                    }).bind(this),
                    (function () {
                        this.setValue();
                        $(this.move).removeClass('active');
                    }).bind(this)
                );
                return false;
            }).bind(this));
            this.canvas.appendChild(this.move);

            // Rotate ctl
            if (this.canRotate) {
                this.rotate = document.createElement('div');
                this.rotate.className = 'icon-rotate';
                $(this.rotate).on('touchstart mousedown', (function (event) {
                    $(this.rotate).addClass('active');

                    // Get center point
                    var canvas = $(this.canvas);
                    var offset = $(canvas).offset();
                    var position = $(canvas).position();
                    var height = $(canvas).height();
                    var width = $(canvas).width();
                    var centerLeft = (this.value.left * this.baseWidth) + width / 2;
                    var centerTop = (this.value.top * this.baseHeight) + height / 2;
                    var offsetLeft = offset.left - position.left;
                    var offsetTop = offset.top - position.top;

                    var pageX = event.pageX || event.originalEvent.touches[0].pageX;
                    var pageY = event.pageY || event.originalEvent.touches[0].pageY;

                    // Get start angle
                    var angleS = Math.atan2(centerTop - (pageY - offsetTop), (pageX - offsetLeft) - centerLeft) * 180 / Math.PI;
                    var angle0 = this.value.angle;

                    // Sin for resize
                    var R0 = Math.sqrt(Math.pow(centerTop - (pageY - offsetTop), 2) + Math.pow((pageX - offsetLeft) - centerLeft, 2));
                    var SIN = width / 2 / R0;

                    // Catch mouse
                    this._catchMouse(
                        event,
                        (function (event, X0, Y0, X, Y) {
                            // New value
                            var value = {};

                            // Rotate
                            var angle = Math.atan2(centerTop - (Y - offsetTop), (X - offsetLeft) - centerLeft) * 180 / Math.PI;
                            if (angle !== undefined) {
                                var da = angle - angleS;
                                value.angle = event.ctrlKey ? Math.round((angle0 + da) / 10) * 10 : angle0 + da;
                            }

                            // Update
                            this.updateValue(value);
                        }).bind(this),
                        (function () {
                            this.setValue();
                            $(this.rotate).removeClass('active');
                        }).bind(this)
                    );
                    return false;
                }).bind(this));
                this.canvas.appendChild(this.rotate);
            }

            // Resize ctl
            if (this.canResize) {
                this.resize = document.createElement('div');
                this.resize.className = 'icon-resize';
                $(this.resize).on('touchstart mousedown', (function (event) {
                    $(this.resize).addClass('active');

                    // Get center point
                    var canvas = $(this.canvas);
                    var offset = $(canvas).offset();
                    var position = $(canvas).position();
                    var height = $(canvas).height();
                    var width = $(canvas).width();
                    var offsetLeft = offset.left - position.left;
                    var offsetTop = offset.top - position.top;

                    // Catch mouse
                    this._catchMouse(
                        event,
                        (function (event, X0, Y0, X, Y) {
                            // New value
                            var value = {};

                            // Size
                            var W = width + X - X0;
                            W = event.ctrlKey ? Math.round(W / 10) * 10 : W;
                            var H = W / width * height;
                            value.width = W / this.baseWidth;
                            value.height = H / this.baseHeight;

                            // Update
                            this.updateValue(value);
                        }).bind(this),
                        (function () {
                            this.onResize(width, height);
                            this.setValue();
                            $(this.resize).removeClass('active');
                        }).bind(this)
                    );
                    return false;
                }).bind(this));
                this.canvas.appendChild(this.resize);
            }

            // Remove ctl
            this.remove = document.createElement('div');
            this.remove.className = 'icon-cross';
            $(this.remove).on('touchstart mousedown', (function (event) {
                this.rm();
                return false;
            }).bind(this));
            this.canvas.appendChild(this.remove);
        }

        // Content
        this.content = this.getContent();
        this.content.className = 'content';
        this.canvas.appendChild(this.content);

        // Target
        this.target = this.getTarget();

    };

    /**
     * Get content
     */
    this.getContent = function () {
    };

    /**
     * Get target
     */
    this.getTarget = function () {
    };

    /**
     * Unrender
     */
    this.unrender = function () {
        $(this.canvas).remove();
        $(this.target).remove();
    };

    /**
     * Get target
     */
    this.renderTarget = function (target) {
        if (!this.target) this.createObjects();
        this.updateTarget();
        $(target).append(this.target);
    };

    /**
     * Update target
     */
    this.updateTarget = function () {
        // Position
        $(this.target).attr({
            'x': (this.value.left * this.baseWidth) + 'px',
            'y': (this.value.top * this.baseHeight) + 'px',
            'width': Math.ceil(this.value.width * this.baseWidth) + 'px',
            'height': Math.ceil(this.value.height * this.baseHeight) + 'px'
        });

        // Rotation
        if (this.canRotate) {
            $(this.target).attr({
                'transform': 'rotate(' + (-this.value.angle) + ' ' + (this.value.left * this.baseWidth + this.value.width * this.baseWidth / 2) + ' ' + (this.value.top * this.baseHeight + this.value.height * this.baseHeight / 2) + ')'
            });
        }
    };

    /**
     * Update content
     */
    this.updateContent = function () {
        this.applyMove();
        this.applyRotate();
        this.applyResize();
    };

    /**
     * Apply move
     */
    this.applyMove = function() {
        $(this.canvas).css({
            'left': this.value.left * this.baseWidth + 'px',
            'top': this.value.top * this.baseHeight + 'px'
        });
    };

    /**
     * Apply rotation
     */
    this.applyRotate = function() {
        if (this.canRotate) {
            $(this.canvas).css({
                'transform': 'rotate(' + (-this.value.angle) + 'deg)',
                '-o-transform': 'rotate(' + (-this.value.angle) + 'deg)',
                '-moz-transform': 'rotate(' + (-this.value.angle) + 'deg)',
                '-webkit-transform': 'rotate(' + (-this.value.angle) + 'deg)'
            });
        }
    };

    /**
     * Apply resize
     */
    this.applyResize = function() {
        if (this.canResize && this.value.height) {
            this.content.height = this.value.height * this.baseHeight;
        }
    };

    /**
     * Update
     */
    this.update = function() {
        this.updateContent();
        this.updateTarget();
    }
};
Layout.zIndex = 0;
Layout.focused = null;
Layout.prototype = new Element();
Layout.prototype.getDefaults = function() {
    var defaults = Element.prototype.getDefaults();
    defaults.top = 0.1;
    defaults.left = 0.1;
    defaults.angle = 0;
    defaults.width = 0.3;
    defaults.height = 0.3;
    defaults.zIndex = ++Layout.zIndex + 10;
    return defaults;
};

/**
 * Smile
 */
var LayoutImage = function (attrs) {
    Layout.call(this, attrs);

    // Options
    this.fixedSize = false;

    /**
     * Get image URL
     */
    this.getImageUrl = function(){};

    /**
     * Create objects
     */
    this.createObjects = function() {
        var result = LayoutImage.prototype.createObjects.call(this);
        if (!this.readOnly) {
            this.loading = document.createElement('div');
            this.loading.className = 'loader';
            this.canvas.appendChild(this.loading);
        }
        return result;
    };

    /**
     * Get content
     */
    this.getContent = function () {
        var content = null;
        if (this.readOnly) {
            content = document.createElement('div');
        } else {
            content = document.createElement('img');
            content.src = this.getImageUrl();
            $(content).on('load', (function () {
                this.value.height = $(content).height() / this.baseHeight;
                this.value.width = $(content).width() / this.baseWidth;
                this.updateContent();
                this.updateTarget();
                if (this.loading) {
                    this.canvas.removeChild(this.loading);
                    this.loading = null;
                }
                $(this.target).removeClass('hidden');
                $.each(this.onLoad, function (i, v) {
                    v()
                });
            }).bind(this));
        }
        return content;
    };

    this.updateContent = function () {
        LayoutImage.prototype.updateContent.call(this);

        // Update content src
        if (!this.readOnly) {
            var src = this.getImageUrl();
            if (src != this.content.src) {
                // if (!this.value.height) {
                //     this.content.height = '';
                // }
                this.content.src = this.getImageUrl();
            }
        }
    };

    this.updateTarget = function () {
        LayoutImage.prototype.updateTarget.call(this);

        // Update target src
        var src = this.getImageUrl();
        if (src != this.target.getAttributeNS('http://www.w3.org/1999/xlink', 'href')) {
            this.target.setAttributeNS('http://www.w3.org/1999/xlink', 'href', this.getImageUrl());
        }
    };

    /**
     * Get target
     */
    this.getTarget = function () {
        var image = document.createElementNS('http://www.w3.org/2000/svg', 'image');
        $(image).attr({'class': 'constructor-layout-image' + (this.readOnly ? '' : ' hidden')});
        image.setAttributeNS('http://www.w3.org/1999/xlink', 'href', this.getImageUrl());
        image.setAttributeNS('http://www.w3.org/1999/xlink', 'zindex', this.value.zIndex.toString());
        return image;
    }
};
LayoutImage.prototype = new Layout();

/**
 * Smile
 */
var Smile = function(attrs) {
    LayoutImage.call(this, attrs);

    /**
     * Get URL for smile
     */
    this.getImageUrl = function() {
        return URL_STORAGE + (this.value.type == 'user' ? '/upload/' : '/smile/') + this.value.name;
    }
};
Smile.prototype = new LayoutImage();
Smile.prototype.getDefaults = function() {
    var defaults = LayoutImage.prototype.getDefaults();
    defaults.zIndex = 10000 + defaults.zIndex;
    return defaults;
};

/**
 * Text
 * @param attrs
 * @constructor
 */
var Text = function(attrs) {
    LayoutImage.call(this, attrs);

    /**
     * On resize
     */
    this.onResize = function(w0, h0) {
        var size = this.value.size * this.value.height / (h0 / this.baseHeight);
        this.value.size = size;
    };

    /**
     * Get URL for smile
     */
    this.getImageUrl = function() {
        return URL_FONT + '?' + $.param({
            font: this.value.name,
            color: this.value.color,
            text: this.value.text,
            size: this.value.size * this.baseHeight
        });
    }
};
Text.prototype = new LayoutImage();
Text.prototype.getDefaults = function() {
    var defaults = Layout.prototype.getDefaults();
    defaults.color = '#000000';
    defaults.text = 'Your text';
    defaults.size = 0.1;
    defaults.height = null;
    defaults.width = null;
    defaults.zIndex = 20000 + defaults.zIndex;
    return defaults;
};


/**
 * Initializations
 */
$(function() {
    $('.ps').perfectScrollbar();
});

/**
 * Scroll to
 * @param target
 * @param options
 * @param callback
 * @returns {*}
 */
$.fn.scrollTo = function( target, options, callback ){
    if(typeof options == 'function' && arguments.length == 2){ callback = options; options = target; }
    var settings = $.extend({
        scrollTarget  : target,
        offsetTop     : 50,
        duration      : 500,
        easing        : 'swing'
    }, options);
    return this.each(function(){
        var scrollPane = $(this);
        var scrollTarget = (typeof settings.scrollTarget == "number") ? settings.scrollTarget : $(settings.scrollTarget);
        var scrollY = (typeof scrollTarget == "number") ? scrollTarget : scrollTarget.offset().top + scrollPane.scrollTop() - parseInt(settings.offsetTop);
        scrollPane.animate({scrollTop : scrollY }, parseInt(settings.duration), settings.easing, function(){
            if (typeof callback == 'function') { callback.call(this); }
        });
    });
};

/**
 * Pluralize
 * @param number
 * @param one
 * @param two
 * @param five
 * @returns {*}
 */
function pluralize(number, one, two, five) {
    number = Math.abs(number);
    number %= 100;
    if (number >= 5 && number <= 20) {
        return five;
    }
    number %= 10;
    if (number == 1) {
        return one;
    }
    if (number >= 2 && number <= 4) {
        return two;
    }
    return five;
}

/**
 * Loading
 * @param v
 */
function loading(v) {
    $('#loading').toggleClass('hidden', !v);
}
