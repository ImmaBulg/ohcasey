webpackJsonp([4],{

/***/ 293:
/***/ (function(module, exports, __webpack_require__) {

"use strict";


var _typeof = typeof Symbol === "function" && typeof Symbol.iterator === "symbol" ? function (obj) { return typeof obj; } : function (obj) { return obj && typeof Symbol === "function" && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj; };

var _vue = __webpack_require__(4);

var _vue2 = _interopRequireDefault(_vue);

var _vueRouter = __webpack_require__(9);

var _vueRouter2 = _interopRequireDefault(_vueRouter);

var _axios = __webpack_require__(6);

var _axios2 = _interopRequireDefault(_axios);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

_vue2.default.use(_vueRouter2.default);

var routes = [{ path: '/', component: app }];

var router = new _vueRouter2.default({
    hashbang: false,
    mode: 'history',
    base: window.location.pathname,
    routes: routes
});

_vue2.default.component('modal', {
    template: '#modal-template',
    props: ['cart'],
    mounted: function mounted() {
        $('.js-scroll-popup').mCustomScrollbar({
            axis: 'y',
            scrollInertia: 400
        });
    }
});

var app = new _vue2.default({
    el: '#app',
    router: router,
    data: {
        select2: null,
        // data matrix related options from server
        raw: offers,
        // rebuilded options to display
        options: {},
        // exclude option from rebuild (always display any variants)
        exclude: 'device',
        // current selected option, others options will be rebuild
        selected: {},
        // offer on selected values
        offer: {},
        // product photos
        photos: { offer: null, product: [] },
        cart: [],
        cartCount: document.getElementById('cart__count'),
        // json data for old cart request
        json: null,
        // error - if old options values mapping false or if offer not found
        optionsError: false,
        sliderIsInit: false,
        // Modal window toggle flag
        showModal: false,
        headline: null,
        caseType: null
    },
    mounted: function mounted() {
        var _this = this;

        // select2
        this.select2init();
        // init options keys
        options.forEach(function (o) {
            return _vue2.default.set(_this.options, o.key, { name: o.name, values: [] });
        });
        let temp = [];
        console.log(this.options.device.values);
        // fill only exluded option values
        this.fillValues({ exclude: true });
        // set default selected only exluded value
        _vue2.default.set(this.selected, this.exclude, this.options[this.exclude].values[0].id);
        // fill product extra photos
        this.photos.product = product_photos;
        this.rebuildOptions();

        setTimeout(function () {
            if (_this.$route.query.device) {
                _this.options.device.values.forEach(function (item, index) {
                    if (item.value == _this.$route.query.device) {
                        _this.select2.val(parseInt(item.id)).trigger('change');
                    }
                });
            }

            if (_this.$route.query.color) {
                if (_this.options.color.values[_this.$route.query.color]) _this.selectOption('color', _this.options.color.values[_this.$route.query.color].id);
            }

            if (_this.$route.query.case) {
                _this.options.case.values.forEach(function (item, index) {
                    if (item.value == _this.$route.query.case) {
                        _this.selectOption('case', _this.options.case.values[index].id);
                    }
                });
            }
        }, 0);
    },
    updated: function updated() {
        if (!this.sliderIsInit) {
            this.sliderIsInit = true;
            this.sliderInit();
        } else {
            this.sliderReinit();
        }
    },

    methods: {
        setModelTitle: function setModelTitle(model) {
            return model + ' выберите вашу модель телефона в списке для подбора Вашего чехла';
        },
        setColorTitle: function setColorTitle(color) {
            return "Нажмите для выбора, если цвет вашего телефона " + color.toLowerCase();
        },
        setMaterialTitle: function setMaterialTitle(material) {
            return material + " выберите свой чехол в списке для подбора Вашего чехла";
        },
        resetOffer: function resetOffer() {
            this.offer = {};
            // this.photos.offer = null
            this.json = null;
            this.optionsError = false;
        },
        findCase: function findCase(value) {
            let i = undefined;
            this.options['case'].values.forEach(function (item, index) {
                if (item.value == value)
                    i = index;
            });
            return i;
        },
        defaultSelected2: function defaultSelected2() {
            /*for (var o in this.options) {
                _vue2.default.set(this.selected, o, this.options[o].values[0].id);
            }*/
        },
        defaultSelected: function defaultSelected() {
            /*for (var o in this.options) {
                // set default selected values as first array value
                if (!this.selected[o]) {
                    if (o == 'case') {
                        if (this.findCase(this.$route.query.case) != undefined)
                            _vue2.default.set(this.selected, o, this.options[o].values[this.findCase(this.$route.query.case)].id);
                        else
                            _vue2.default.set(this.selected, o, this.options[o].values[0].id);

                    }
                    else
                        _vue2.default.set(this.selected, o, this.options[o].values[0].id);
                }
            }*/
        },
        rebuildOptions: function rebuildOptions() {
            var _this2 = this;

            this.resetOffer();

            // get rows only with selected option, options in this rows must be filled
            var rows = this.raw.filter(function (row) {
                for (var id in row.option_values) {
                    if (_this2.selected[_this2.exclude] === row.option_values[id].id) {
                        return true;
                    }
                }
            });
            this.fillValues({rows: rows});
            this.defaultSelected();
            this.findOffer();
        },
        fillValues: function fillValues(s) {
            var _this3 = this;

            var settings = s || {};
            var rows = settings.rows || this.raw;
            var exclude = settings.exclude || false;
            // clear old options values except excluded
            for (var o in this.options) {
                if (o != this.exclude) {
                    this.options[o].values = [];
                }
            }

            var _loop = function _loop(r) {
                var _loop2 = function _loop2(v) {
                    var key = options.filter(function (o) {
                        return o.id == rows[r].option_values[v].option_id;
                    })[0].key;
                    var value = rows[r].option_values[v];
                    // check if this value already exists in options array
                    var exists = _this3.options[key].values.filter(function (v) {
                        return v.id === value.id;
                    });
                    // if not exists - add
                    if (exists.length == 0) {
                        if (exclude && key == _this3.exclude) _this3.options[key].values.push(value);
                        if (!exclude) _this3.options[key].values.push(value);
                    }
                };

                for (var v in rows[r].option_values) {
                    _loop2(v);
                }
            };

            for (var r in rows) {
                _loop(r);
            }
            // sort options by order
            for (var i in this.options) {
                var _s = this.sortByKey(this.options[i].values, 'order');
                this.options[i].values = _s;
            }
        },
        select2init: function select2init() {
            var _this4 = this;
            window.select2 = this.select2 = $('.js-select-item');
            if (this.select2.length) {
                this.select2.select2({
                    dropdownCssClass: 'drop-item',
                    minimumResultsForSearch: -1
                }).on('change', function () {
                    var value = parseInt(_this4.select2.val());
                    _this4.selectOption(_this4.exclude, value);
                    _this4.changeHeadline('device', value);
                    _this4.rebuildUrl(true);
                    _this4.defaultSelected2();
                });
            }
        },
        sliderInit: function sliderInit() {
            if ($('.js-slider-for').length) {
                $('.js-slider-for').slick({
                    slidesToShow: 1,
                    slidesToScroll: 1,
                    arrows: false,
                    fade: true,
                    asNavFor: '.js-slider-nav',
                    responsive: [{
                        breakpoint: 700,
                        settings: {
                            dots: true,
                            fade: false
                        }
                    }]
                });

                $('.js-slider-nav').slick({
                    slidesToShow: 4,
                    slidesToScroll: 1,
                    asNavFor: '.js-slider-for',
                    dots: false,
                    arrows: false,
                    infinite: true,
                    centerMode: false,
                    variableWidth: true,
                    focusOnSelect: true
                });
            }
        },
        sliderReinit: function sliderReinit() {
            $('.js-slider-for').slick('unslick');
            $('.js-slider-nav').slick('unslick');
            this.sliderInit();
        },
        changeHeadline: function changeHeadline(key, value) {
            var _this5 = this;

            this.headline = this.headline || {};

            this.options[key].values.forEach(function (v) {
                if (value === v.id) {
                    if (key === 'device') {
                        _this5.headline.device = v.title;
                    }
                    if (key === 'case') {
                        _this5.caseType = v.value;
                        _this5.headline.case = v.title;
                    }
                }
            });
            //_this5.rebuildUrl();
            //document.title = '\u0427\u0435\u0445\u043E\u043B \u0434\u043B\u044F ' + this.headline.device + ' \xAB' + window.product_name + '\xBB (\u043C\u0430\u0442\u0435\u0440\u0438\u0430\u043B ' + this.headline.case + ')  \u043A\u0443\u043F\u0438\u0442\u044C \u0432 \u0438\u043D\u0442\u0435\u0440\u043D\u0435\u0442-\u043C\u0430\u0433\u0430\u0437\u0438\u043D\u0435';
        },
        rebuildUrl: function rebuildUrl(device) {
            /*if (!device)
            {
                let device = this.selected.device;
                this.options.device.values.forEach(function(item) {
                    if (item.id == device) {
                        device = item.value;
                    }
                });
                let color = this.selected.color;
                this.options.color.values.forEach(function(item, index) {
                    if (item.id == color) {
                        color = index;
                    }
                });
                let cas = this.selected.case;
                this.options.case.values.forEach(function(item) {
                    if (item.id == cas)
                        cas = item.value;
                });
                let query = {
                    'sort' : '',
                    'device' : device,
                    'color' : color,
                    'case' : cas
                };
                this.$router.push({ path: '/', query: {
                        'sort' : '',
                        'device' : device,
                        'color' : color,
                        'case' : cas
                    }});
            }
            else {
                let device = this.selected.device;
                this.options.device.values.forEach(function(item) {
                    if (item.id == device) {
                        device = item.value;
                    }
                });
                let color = this.$route.query.color;
                let cas = this.$route.query.case;
                this.$router.push({ path: '/', query: {
                        'sort' : '',
                        'device' : device,
                        'color' : color,
                        'case' : cas
                    }});
            }*/

        },
        selectOption: function selectOption(key, value) {
            if (key == this.exclude) this.selected = {};
            _vue2.default.set(this.selected, key, value);
            if (key == this.exclude) {
                this.rebuildOptions();
                return;
            }

            if (key === 'case') this.changeHeadline('case', value);
            // find offer if all options selected

            if (Object.keys(this.selected).length == Object.keys(this.options).length) this.findOffer();
            this.rebuildUrl(false);
        },
        findOffer: function findOffer() {
            var _this6 = this;

            var offer = this.raw.filter(function (row) {
                var find = true;
                for (v in row.option_values) {
                    var _key = options.filter(function (o) {
                        return o.id == row.option_values[v].option_id;
                    })[0].key;
                    find = find && row.option_values[v].id == _this6.selected[_key];
                }
                return find;
            })[0];

            if (!offer) {
                this.resetOffer();
                this.optionsError = true;
                return;
            } else {
                this.offer = offer;
                this.optionsError = false;
            }


            var current_device_key = this.options.device.values.filter(function (device) {
                return device.id == _this6.selected['device'];
            })[0].value;

            // make json for old cart request
            var jsondata = {};

            var _loop3 = function _loop3() {
                var key = options[o].key;
                var value = _this6.options[key].values.filter(function (v) {
                    return v.id == _this6.selected[key];
                })[0].value;
                if (oldvalues[key]) {
                    var oldFindValue = null;
                    for (v in oldvalues[key]) {
                        if (key == 'color') {
                            if (oldvalues[key][current_device_key].length == 0) break;
                            color_index = oldvalues[key][current_device_key].indexOf(value);

                            if (color_index != -1) {
                                oldFindValue = color_index;
                                break;
                            }
                        } else {
                            if (oldvalues[key][v] == value) {
                                oldFindValue = oldvalues[key][v];
                                break;
                            }
                        }
                    }
                    if (oldFindValue != null) {
                        jsondata[key] = oldFindValue;
                    } else {
                        _this6.optionsError = true;
                        return {
                            v: false
                        };
                    }
                }
            };

            for (var o in options) {
                var v;
                var color_index;

                var _ret3 = _loop3();

                if ((typeof _ret3 === 'undefined' ? 'undefined' : _typeof(_ret3)) === "object") return _ret3.v;
            }
            this.json = {
                DEVICE: {
                    device: jsondata.device || '',
                    color: parseInt(jsondata.color) === jsondata.color ? jsondata.color : '',
                    casey: jsondata.case || '',
                    bg: bgName || '',
                    mask: []
                }

                // update cuurent offer photo
            };
            this.photos.offer = lroutes.api.product.image + '?bgName=' + bgName + '&deviceName=' + current_device_key + '&deviceColorIndex=' + jsondata.color + '&caseFileName=' + jsondata.case;
            $("#link-add-text").attr("href", "/s/hash?p=1" + '&bgName=' + bgName + '&deviceName=' + current_device_key + '&deviceColorIndex=' + jsondata.color + '&caseFileName=' + jsondata.case);
        },
        addToCart: function addToCart() {
            var _this7 = this;

            ga('send', 'event', 'Click', 'Add2basketCart');

            if (this.offer.id && this.json) {
                this.cart.push(this.offer);
                var current_count = parseInt(this.cartCount.innerHTML) || 0;
                this.cartCount.innerHTML = current_count + 1;
                _axios2.default.post('/cart/put/case/1', {
                    current: this.json,
                    offer_id: this.offer.id
                }).then(function (response) {
                    var requestConfig = {
                        headers: {
                            "Accept": "application/json",
                            "X-Requested-With": "XMLHttpRequest"
                        }
                    };
                    _this7.json = null;
                    _axios2.default.get('/cart', requestConfig).then(function (response) {
                        _this7.cart = [];

                        if (response.data.data.cartSetCases.length) {
                            _this7.cart = _this7.cart.concat(response.data.data.cartSetCases);
                        }
                        if (response.data.data.cartSetProducts.length) {
                            _this7.cart = _this7.cart.concat(response.data.data.cartSetProducts);
                        }

                        //_this7.toggleModal();
                        location.href = '/cart';
                    }).catch(function (response) {
                        console.error(error);
                        _this7.json = null;
                    });
                }).catch(function (error) {
                    console.error(error);
                    _this7.json = null;
                });
            }
        },
        toggleModal: function toggleModal() {
            this.showModal = true;
            $('body').addClass('is-nav');
            $('.js-scroll-popup').mCustomScrollbar({
                axis: 'y',
                scrollInertia: 400
            });
        },
        sortByKey: function sortByKey(array, key) {
            return array.sort(function (a, b) {
                var x = a[key];
                var y = b[key];
                return x < y ? -1 : x > y ? 1 : 0;
            })
        },
    },
    computed: {
        inCart: function inCart() {
            var _this8 = this;

            var productInCart = this.cart.filter(function (o) {
                return o.id == _this8.offer.id;
            })[0];
            return !!productInCart;
        },
        devicelist: function devicelist() {
            if (this.options && this.options.device && this.options.device.values) return this.options.device.values;else return [];
        },
        colorlist: function colorlist() {
            if (this.options && this.options.color && this.options.color.values) return this.options.color.values;else return [];
        },
        caselist: function caselist() {
            if (this.options && this.options.case && this.options.case.values) return this.options.case.values;else return [];
        }
    },
    watch: {
        'selected.device' (to, from) {
            console.log('from', from, 'to', to);
            if (from !== undefined)
            {
                let device = this.devicelist.filter(device => device.id === to);
                let cas = this.caselist.find(item => item.value == this.$route.query.case);
                console.log(cas);
                this.$route.query.device = device[0].value;
                this.$route.query.case = cas === undefined ? this.caselist[0].value : cas.value;
                this.$route.query.color = this.colorlist.length > this.$route.query.color ? this.$route.query.color : 0;
                let queryStr = '?sort=&device=' + device[0].value + '&case=' + this.$route.query.case + '&color=' + this.$route.query.color;
                let index = this.caselist.findIndex(item => item.value === this.$route.query.case);
                _vue2.default.set(this.selected, 'case', this.options['case'].values[index].id);
                _vue2.default.set(this.selected, 'color', this.options['color'].values[this.$route.query.color].id);
                window.history.pushState('', '', queryStr);
            } else {
                console.log('test');
                if (this.$route.query.device === 'iphone') {
                    let queryStr = '?sort=&device=iphonex&case=silicone&color=1';
                    this.$route.query.device = 'iphonex';
                    this.$route.query.case = 'silicone';
                    this.$route.query.color = 1;
                    window.history.pushState('', '', queryStr);
                } else if (this.$route.query.device ==='samsung') {
                    let queryStr = '?sort=&device=sgs7e&case=silicone&color=1';
                    this.$route.query.device = 'samsung';
                    this.$route.query.case = 'silicone';
                    this.$route.query.color = 1;
                    window.history.pushState('', '', queryStr);
                }
                else{
                    let queryStr = '?sort=&device=' + this.$route.query.device + '&case=' + this.$route.query.case + '&color=' + this.$route.query.color;
                    window.history.pushState('', '', queryStr);
                }
            }
        },
        'selected.case' (to, from) {
            console.log('from', from, 'to', to);
            let cas = this.caselist.find(item => item.id === to);
            cas = cas === undefined ? this.caselist[0] : cas;
            let queryStr = '?sort=&device=' + this.$route.query.device + '&case=' + cas.value + '&color=' + this.$route.query.color;
            this.$route.query.case = cas.value;
            window.history.pushState('', '', queryStr);
        },
        'selected.color' (to, from) {
            console.log(to);
            let color = this.colorlist.findIndex(item => item.id === to);
            color = color !== -1 ? color : 0;
            let queryStr = '?sort=&device=' + this.$route.query.device + '&case=' + this.$route.query.case + '&color=' + color;
            this.$route.query.color = color;
            window.history.pushState('', '', queryStr);
        }
    }
});

/***/ })

},[293]);

/* $("body").on("click", "#link-add-text", function(){
	console.log("send GA");
	ga('send', 'event', 'Click', 'add_custom');
	yaCounter32242774.reachGoal('add_custom');
}); */