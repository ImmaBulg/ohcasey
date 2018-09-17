webpackJsonp([5],{

/***/ 287:
/***/ (function(module, exports, __webpack_require__) {

"use strict";


var _data;

var _vue = __webpack_require__(4);

var _vue2 = _interopRequireDefault(_vue);

var _vueRouter = __webpack_require__(9);

var _vueRouter2 = _interopRequireDefault(_vueRouter);

var _axios = __webpack_require__(6);

var _axios2 = _interopRequireDefault(_axios);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

function _defineProperty(obj, key, value) { if (key in obj) { Object.defineProperty(obj, key, { value: value, enumerable: true, configurable: true, writable: true }); } else { obj[key] = value; } return obj; }

_vue2.default.use(_vueRouter2.default);

var routes = [{ path: '/', component: app }];

var router = new _vueRouter2.default({
  hashbang: false,
  mode: 'history',
  base: window.location.pathname,
  routes: routes
});

var app = new _vue2.default({
  router: router,
  data: (_data = {
    isLoaded: false,
    isCollection: undefined,
    isLoading: false,
    queryString: {},
    select2case: null,
    select2device: null,
    select2deviceModal: null,
    select2caseModal: null,
    data: [],
    selected: {},
    selectedCase: null,
    selectedDevice: null,
    selectedColor: null,
    selectedColorIndex: null,
    selectedPalette: [],
    devices: window.devices,
    devices_samsung: window.devices_samsung,
    cases: null,
    colors: window.colors,
    category: window.category,
    iphone_devices: window.devices,
    paging: {
      total: null,
      from: null,
      to: null,
      pages: null
    },
    options: window.options,
    routeString: '',
  }, _defineProperty(_data, 'iphone_devices', window.devices), _defineProperty(_data, 'device_type', 'samsung'), _data),

  methods: {
    setImageAlt: function setImageAlt(product, addString) {
      if (addString != "")
        return addString.charAt(0).toUpperCase() + addString.substr(1) + ' ' + product.name.toLowerCase();
      else
        return product.name;
    },
    setFullUrl: function setFullUrl(product) {
      console.log(window.location.pathname.split('/'));
      if (window.location.pathname.split('/')[1] === 'collections') {
          if (this.select2device.children("option").filter(":selected").text() === 'iPhone') {
              return '/product/' + product.id + '?sort=&device=iphonex&case=silicone&color=1';
          }
          else if (this.select2device.children("option").filter(":selected").text() === 'Samsung') {
              return '/product/' + product.id + '?sort=&device=sgs7e&case=silicone&color=1';
          }
      } else if(window.location.pathname.split('/')[1] === 'catalog') {
          let model = window.location.pathname.split('/')[2];
          switch (model) {
              case 'galaxy_s6':
                  model = 'sgs6';
                break;
              case 'galaxy_s6_edge':
                  model = 'sgs6e';
                break;
              case 'galaxy_s7':
                  model = 'sgs7';
                break;
              case 'galaxy_s7_edge':
                  model = 'sgs7e';
                break;
              default:
                model = model.replace(/_/g, '');
                break;
          } 
          return '/product/' + product.id + '?sort=&device=' + model + '&case=silicone&color=0';
      } else if (window.location.pathname.split('/')[1] === 'glitter') {
        return '/product/' + product.id + '?sort=&device=iphone7';
      }
      return '/product/' + product.id + this.$route.fullPath;
    },
      getFullPath: function() {
          return '?sort=' + this.$route.query.sort + '&device=' + this.$route.query.device + (this.$route.query.case ? '&case=' + this.$route.query.case :  '&case=silicone') +  (this.$route.query.color && this.$route.query.color != 0  ? '&color=' + this.$route.query.color :  '&color=0');
      },
    setImageTitle: function setImageTitle(product, breadcrumbs, parent) {
      var rand_words = [['выбрать', 'заказать', 'посмотреть', 'купить', 'подобрать'], 
      ['оригинальный', 'уникальный', 'бесподобный', 'стильный', 'красивый', 'неповторимый'],
      ['чехол', 'дизайн', 'цвет и оттенок']];

      switch (parent) {
        case '36':
          return breadcrumbs.substr(0,1).toUpperCase() + breadcrumbs.substr(1) + ' ' + product.name.toLowerCase() + ' ' +  rand_words[0][Math.floor((Math.random()*rand_words[0].length))] + ' свой ' + rand_words[1][Math.floor((Math.random()*rand_words[1].length))] + ' ' + rand_words[2][Math.floor((Math.random()*rand_words[2].length))];
          break;
        case '2': 
          return breadcrumbs.substr(0,1).toUpperCase() + breadcrumbs.substr(1) + ' ' + product.name.toLowerCase() + ' выбрать из коллекции и отредактировать свой неповторимый чехол.';
          break;
        default:
          return breadcrumbs.substr(0,1).toUpperCase() + breadcrumbs.substr(1) + ' ' + product.name.toLowerCase();
          break;
      }
    },
    setColorTitle: function setColorTitle(color) {
      return "Нажмите для выбора, если цвет вашего телефона " + options[color];
    },
    setCaseTitle: function setCaseTitle(caption) {
      return caption + " выберите свой чехол в списке для подбора Вашего чехла";
    },
    changeFilterParams: function changeFilterParams(p, v) {
      var data = Object.assign({}, this.$route.query);
      data[p] = v;
        this.$route.query[p] = v;
        this.queryString = data;

        this.routeString = '/collections/' + this.category.slug + this.getFullPath();
    },
    getMeta: function (model) {
      if (window.location.pathname.split('/')[1] === 'collections') {
          let phone = this.select2device.children("option").filter(":selected").text();
          let cas = this.select2case.children("option").filter(":selected").text();
          if (window.tags[model]) {
              document.title = window.tags[model].title;
              document.description = window.tags[model].desc;
              document.querySelector('meta[name="keywords"').setAttribute("content", window.tags[model].keywords);
              document.querySelector('div[id="text_up"]').innerHTML = window.tags[model].text_up;
              document.querySelector('div[id="text_down"]').innerHTML = window.tags[model].text_down;
          } else {
              let keywords = window.category.keywords.split(',');
              let h1 = window.category.h1;
              let title = window.category.title;
              let color = this.options[this.colors[model][this.queryString.color]];
              keywords = keywords.join(' ' + phone.toLowerCase() + ' ' + color.toLowerCase() + ' ' + cas.toLowerCase() + ',');
              h1 = phone.toUpperCase() + ' - ' + h1 + ' - ' + color.toLowerCase() + ' телефон ' + cas.toLowerCase();
              title = phone.toUpperCase() + ' ' + color.toLowerCase() + ' - ' + title + ' ' + phone.toUpperCase() + ' ' + cas.toLowerCase();
              document.title = title;
              document.querySelector('meta[name="keywords"]').setAttribute("content", keywords);
              if (document.getElementsByClassName('h1')[0])
                  document.getElementsByClassName('h1')[0].innerHTML = h1;
          }
      }
    },
    getFilteredData: function getFilteredData() {
      var _this = this;

      this.isLoading = true;

      _axios2.default.get('/api/product?category_id=' + window.category.id, {
        params: this.queryString
      }).then(function (response) {
        _this.isLoading = false;

        _this.paging.pages = response.data.last_page;
        _this.data = response.data.data;
        _this.getMeta(_this.queryString.device);

      }).catch(function (error) {
        _this.isLoading = false;

        console.log(error);
      });
    },
    setColorPalette: function setColorPalette() {
      this.selectedPalette = this.colors[this.selectedDevice];
    },
    selectColor: function selectColor(v, index) {
      console.log(v, index);
      this.selectedColor = v;
      this.changeFilterParams('color', index);
    },
    changePage: function changePage(number) {
      this.changeFilterParams('page', number);
      window.scrollTo(0, 0);
    },
    initCaseSelect: function initCaseSelect() {
      var _this2 = this;

      if ($('.js-select-item-case').length) {

        if (this.select2case) {
          this.select2case.select2('destroy');
          this.select2case = null;
        }

        this.select2case = $('.js-select-item-case');

        this.select2case.select2({
          dropdownCssClass: 'drop-item',
          minimumResultsForSearch: -1
        }).on('change', function () {
          setTimeout(function () {
            _this2.changeFilterParams('case', _this2.select2case.val());
          }, 1);
        }).on('select2:closing', function (evt) {
          $('.select2-results__options').mCustomScrollbar("destroy");
        }).on('select2:open', function (evt) {
          setTimeout(function () {
            $('.select2-results__options').mCustomScrollbar({
              axis: 'y',
              scrollInertia: 400
            });

            $(document).scroll();
          }, 1);
        });
      }
    },
    initCaseModalSelect: function initCaseModalSelect() {
      var _this3 = this;

      if ($('.js-select-item-case-modal').length) {

        if (this.select2caseModal) {
          this.select2caseModal.select2('destroy');
          this.select2caseModal = null;
        }

        this.select2caseModal = $('.js-select-item-case-modal');

        this.select2caseModal.select2({
          dropdownCssClass: 'drop-item',
          minimumResultsForSearch: -1
        }).on('change', function () {
          _this3.selectedCase = _this3.select2caseModal.val();
        }).on('select2:closing', function (evt) {
          $('.select2-results__options').mCustomScrollbar("destroy");
        }).on('select2:open', function (evt) {
          setTimeout(function () {
            $('.select2-results__options').mCustomScrollbar({
              axis: 'y',
              scrollInertia: 400
            });

            $(document).scroll();
          }, 1);
        });
      }
    },
    selectMobile: function selectMobile() {
      if (this.device_type == 'samsung') {
        this.select2device.val(null);
        this.select2device.select2("destroy");
        this.devices = this.devices_samsung;
        this.selectedDevice = this.devices_samsung[0].value;
        this.select2init();
        this.changeFilterParams('device', this.selectedDevice);
        this.device_type = 'iphone';
        $('.js-select-item-change').select2({
          placeholder: 'Чехлы для iPhone'
        });
      } else {
        this.select2device.val(null);
        this.select2device.select2("destroy");
        this.devices = this.iphone_devices;
        this.selectedDevice = this.devices[0].value;
        this.select2init();
        this.changeFilterParams('device', this.selectedDevice);
        this.device_type = 'samsung';
        $('.js-select-item-change').select2({
          placeholder: 'Чехлы для Samsung'
        });
      }
    },
    select2init: function select2init() {
      var _this4 = this;

      if ($('.js-select-item-device').length) {
        $('.js-select-item-change').select2({
          dropdownCssClass: 'drop-item',
          minimumResultsForSearch: -1,
          width: 'resolve',
          placeholder: 'Чехлы для Samsung'
        });
        this.select2device = $('.js-select-item-device');
        this.select2device.select2({
          dropdownCssClass: 'drop-item',
          minimumResultsForSearch: -1
        }).on('change', function () {
          var queryParams = _this4.$route.query;
          console.log(window.location);
          _this4.selectedDevice = _this4.select2device.val();
          _this4.changeFilterParams('device', _this4.selectedDevice);
          console.log( _this4.selectedDevice);
          if (_this4.selectedDevice != 'iphone' && _this4.selectedDevice != 'samsung')
          {
              _this4.setColorPalette();
              _this4.selectedColor = _this4.colors[_this4.selectedDevice][0];
              _this4.changeFilterParams('color', _this4.selectedColorIndex ? _this4.selectedColorIndex : 0);
              _this4.cases = window.cases[_this4.selectedDevice];

              setTimeout(function () {
                  _this4.initCaseSelect();
                  if (_this4.selectedDevice == 'iphone4s' || _this4.selectedDevice == 'iphone4')
                    _this4.select2case.val(queryParams.case ? queryParams.case : 'softtouch').trigger('change');
                  else
                      _this4.select2case.val(queryParams.case ? queryParams.case : 'silicone').trigger('change');

              }, 0);
          }
        }).on('select2:closing', function (evt) {
          $('.select2-results__options').mCustomScrollbar("destroy");
        }).on('select2:open', function (evt) {
          setTimeout(function () {
            $('.select2-results__options').mCustomScrollbar({
              axis: 'y',
              scrollInertia: 400
            });

            $(document).scroll();
          }, 1);
        });
      }

      if ($('.js-select-item-device-modal').length) {
        this.select2deviceModal = $('.js-select-item-device-modal');
        this.select2deviceModal.select2({
          dropdownCssClass: 'drop-item',
          minimumResultsForSearch: -1
        }).on('change', function () {
          var queryParams = _this4.$route.query;

          _this4.selectedDevice = _this4.select2deviceModal.val();
          if (_this4.selectedDevice != 'iphone' && _this4.selectedDevice != 'samsung') {
              _this4.setColorPalette();
              _this4.selectedColor = _this4.colors[_this4.selectedDevice][0];
              _this4.changeFilterParams('color', _this4.selectedColorIndex ? _this4.selectedColorIndex : 0);
              _this4.cases = window.cases[_this4.selectedDevice];

              setTimeout(function () {
                  _this4.initCaseModalSelect();
                  _this4.select2caseModal.val(queryParams.case ? queryParams.case : 'silicone').trigger('change');
              }, 0);
          }
        }).on('select2:closing', function (evt) {
          $('.select2-results__options').mCustomScrollbar("destroy");
        }).on('select2:open', function (evt) {
          setTimeout(function () {
            $('.select2-results__options').mCustomScrollbar({
              axis: 'y',
              scrollInertia: 400
            });

            $(document).scroll();
          }, 1);
        });
      }
    },
    applyFilters: function applyFilters() {
      var queryParams = this.$route.query;

      if (this.selectedCase !== queryParams.case) {
        this.changeFilterParams('case', this.selectedCase);
      }

      // this.changeFilterParams('color', this.selectedColorIndex);
      this.selectColor(this.selectedPalette[this.selectedColorIndex], this.selectedColorIndex);

      if (this.selectedDevice !== queryParams.device) {
        this.select2device.val(this.selectedDevice).trigger('change');
      }

      $('.js-popup-close').click();
    }
  },

  mounted: function mounted() {
    var queryParams = this.$route.query;
    var color = this.$route.query.color;
    console.log(queryParams);
    this.isCollection = window.location.pathname.split('/')[1] == 'collections';
    console.log(queryParams);
    console.log(window.location);
    if (this.isCollection) {
      this.cases = window.cases[queryParams.device ? queryParams.device : 'iphone'];

      this.select2init();
      this.changeFilterParams('sort', queryParams.sort ? queryParams.sort : '');

      this.select2device.val(queryParams.device ? queryParams.device : 'iphone').trigger('change');
      this.select2deviceModal.val(queryParams.device ? queryParams.device : 'iphone').trigger('change');

      if (this.select2device.val() != 'iphone' && this.select2device.val() != 'samsung')
      {
          this.setColorPalette();

          if (color) {
              this.selectColor(this.selectedPalette[color], color);
          } else {
              this.selectColor(this.selectedPalette[0], 0);
          }
      }

      this.getFilteredData();
      this.isLoaded = true;
    } else {
      this.getFilteredData();
      this.isLoaded = true;
    }
  }
}).$mount('#app');

/***/ })

},[287]);