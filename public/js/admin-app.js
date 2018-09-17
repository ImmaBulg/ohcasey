webpackJsonp([0],[
/* 0 */,
/* 1 */
/***/ (function(module, exports) {

module.exports = function normalizeComponent (
  rawScriptExports,
  compiledTemplate,
  scopeId,
  cssModules
) {
  var esModule
  var scriptExports = rawScriptExports = rawScriptExports || {}

  // ES6 modules interop
  var type = typeof rawScriptExports.default
  if (type === 'object' || type === 'function') {
    esModule = rawScriptExports
    scriptExports = rawScriptExports.default
  }

  // Vue.extend constructor export interop
  var options = typeof scriptExports === 'function'
    ? scriptExports.options
    : scriptExports

  // render functions
  if (compiledTemplate) {
    options.render = compiledTemplate.render
    options.staticRenderFns = compiledTemplate.staticRenderFns
  }

  // scopedId
  if (scopeId) {
    options._scopeId = scopeId
  }

  // inject cssModules
  if (cssModules) {
    var computed = options.computed || (options.computed = {})
    Object.keys(cssModules).forEach(function (key) {
      var module = cssModules[key]
      computed[key] = function () { return module }
    })
  }

  return {
    esModule: esModule,
    exports: scriptExports,
    options: options
  }
}


/***/ }),
/* 2 */,
/* 3 */,
/* 4 */,
/* 5 */,
/* 6 */,
/* 7 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


Object.defineProperty(exports, "__esModule", {
    value: true
});

var _axios = __webpack_require__(2);

var _axios2 = _interopRequireDefault(_axios);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

exports.default = {
    loading: false,

    request: function request(settings) {
        return new Promise(function (resolve, reject) {
            _axios2.default[settings.method](settings.path, { params: settings.params }).then(function (response) {
                //do somthing before resolve for any request
                resolve(response);
            }).catch(function (error) {
                //do somthing before reject for any request
                reject(error);
            });
        });
    }
};

/***/ }),
/* 8 */
/***/ (function(module, exports, __webpack_require__) {

var Component = __webpack_require__(1)(
  /* script */
  __webpack_require__(41),
  /* template */
  __webpack_require__(64),
  /* scopeId */
  null,
  /* cssModules */
  null
)
Component.options.__file = "/var/www/ohcasey.ru/www/resources/assets/js/components/Layout.vue"
if (Component.esModule && Object.keys(Component.esModule).some(function (key) {return key !== "default" && key !== "__esModule"})) {console.error("named exports are not supported in *.vue files.")}
if (Component.options.functional) {console.error("[vue-loader] Layout.vue: functional components are not supported with templates, they should use render functions.")}

/* hot reload */
if (false) {(function () {
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), false)
  if (!hotAPI.compatible) return
  module.hot.accept()
  if (!module.hot.data) {
    hotAPI.createRecord("data-v-dc71aeec", Component.options)
  } else {
    hotAPI.reload("data-v-dc71aeec", Component.options)
  }
})()}

module.exports = Component.exports


/***/ }),
/* 9 */,
/* 10 */,
/* 11 */,
/* 12 */,
/* 13 */,
/* 14 */,
/* 15 */
/***/ (function(module, exports) {

/*
	MIT License http://www.opensource.org/licenses/mit-license.php
	Author Tobias Koppers @sokra
*/
// css base code, injected by the css-loader
module.exports = function() {
	var list = [];

	// return the list of modules as css string
	list.toString = function toString() {
		var result = [];
		for(var i = 0; i < this.length; i++) {
			var item = this[i];
			if(item[2]) {
				result.push("@media " + item[2] + "{" + item[1] + "}");
			} else {
				result.push(item[1]);
			}
		}
		return result.join("");
	};

	// import a list of modules into the list
	list.i = function(modules, mediaQuery) {
		if(typeof modules === "string")
			modules = [[null, modules, ""]];
		var alreadyImportedModules = {};
		for(var i = 0; i < this.length; i++) {
			var id = this[i][0];
			if(typeof id === "number")
				alreadyImportedModules[id] = true;
		}
		for(i = 0; i < modules.length; i++) {
			var item = modules[i];
			// skip already imported module
			// this implementation is not 100% perfect for weird media query combinations
			//  when a module is imported multiple times with different media queries.
			//  I hope this will never occur (Hey this way we have smaller bundles)
			if(typeof item[0] !== "number" || !alreadyImportedModules[item[0]]) {
				if(mediaQuery && !item[2]) {
					item[2] = mediaQuery;
				} else if(mediaQuery) {
					item[2] = "(" + item[2] + ") and (" + mediaQuery + ")";
				}
				list.push(item);
			}
		}
	};
	return list;
};


/***/ }),
/* 16 */
/***/ (function(module, exports, __webpack_require__) {

var Component = __webpack_require__(1)(
  /* script */
  __webpack_require__(40),
  /* template */
  __webpack_require__(60),
  /* scopeId */
  null,
  /* cssModules */
  null
)
Component.options.__file = "/var/www/ohcasey.ru/www/resources/assets/js/components/Form.vue"
if (Component.esModule && Object.keys(Component.esModule).some(function (key) {return key !== "default" && key !== "__esModule"})) {console.error("named exports are not supported in *.vue files.")}
if (Component.options.functional) {console.error("[vue-loader] Form.vue: functional components are not supported with templates, they should use render functions.")}

/* hot reload */
if (false) {(function () {
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), false)
  if (!hotAPI.compatible) return
  module.hot.accept()
  if (!module.hot.data) {
    hotAPI.createRecord("data-v-4bfb2f24", Component.options)
  } else {
    hotAPI.reload("data-v-4bfb2f24", Component.options)
  }
})()}

module.exports = Component.exports


/***/ }),
/* 17 */
/***/ (function(module, exports, __webpack_require__) {

/*
  MIT License http://www.opensource.org/licenses/mit-license.php
  Author Tobias Koppers @sokra
  Modified by Evan You @yyx990803
*/

var hasDocument = typeof document !== 'undefined'

if (typeof DEBUG !== 'undefined' && DEBUG) {
  if (!hasDocument) {
    throw new Error(
    'vue-style-loader cannot be used in a non-browser environment. ' +
    "Use { target: 'node' } in your Webpack config to indicate a server-rendering environment."
  ) }
}

var listToStyles = __webpack_require__(68)

/*
type StyleObject = {
  id: number;
  parts: Array<StyleObjectPart>
}

type StyleObjectPart = {
  css: string;
  media: string;
  sourceMap: ?string
}
*/

var stylesInDom = {/*
  [id: number]: {
    id: number,
    refs: number,
    parts: Array<(obj?: StyleObjectPart) => void>
  }
*/}

var head = hasDocument && (document.head || document.getElementsByTagName('head')[0])
var singletonElement = null
var singletonCounter = 0
var isProduction = false
var noop = function () {}

// Force single-tag solution on IE6-9, which has a hard limit on the # of <style>
// tags it will allow on a page
var isOldIE = typeof navigator !== 'undefined' && /msie [6-9]\b/.test(navigator.userAgent.toLowerCase())

module.exports = function (parentId, list, _isProduction) {
  isProduction = _isProduction

  var styles = listToStyles(parentId, list)
  addStylesToDom(styles)

  return function update (newList) {
    var mayRemove = []
    for (var i = 0; i < styles.length; i++) {
      var item = styles[i]
      var domStyle = stylesInDom[item.id]
      domStyle.refs--
      mayRemove.push(domStyle)
    }
    if (newList) {
      styles = listToStyles(parentId, newList)
      addStylesToDom(styles)
    } else {
      styles = []
    }
    for (var i = 0; i < mayRemove.length; i++) {
      var domStyle = mayRemove[i]
      if (domStyle.refs === 0) {
        for (var j = 0; j < domStyle.parts.length; j++) {
          domStyle.parts[j]()
        }
        delete stylesInDom[domStyle.id]
      }
    }
  }
}

function addStylesToDom (styles /* Array<StyleObject> */) {
  for (var i = 0; i < styles.length; i++) {
    var item = styles[i]
    var domStyle = stylesInDom[item.id]
    if (domStyle) {
      domStyle.refs++
      for (var j = 0; j < domStyle.parts.length; j++) {
        domStyle.parts[j](item.parts[j])
      }
      for (; j < item.parts.length; j++) {
        domStyle.parts.push(addStyle(item.parts[j]))
      }
      if (domStyle.parts.length > item.parts.length) {
        domStyle.parts.length = item.parts.length
      }
    } else {
      var parts = []
      for (var j = 0; j < item.parts.length; j++) {
        parts.push(addStyle(item.parts[j]))
      }
      stylesInDom[item.id] = { id: item.id, refs: 1, parts: parts }
    }
  }
}

function listToStyles (parentId, list) {
  var styles = []
  var newStyles = {}
  for (var i = 0; i < list.length; i++) {
    var item = list[i]
    var id = item[0]
    var css = item[1]
    var media = item[2]
    var sourceMap = item[3]
    var part = { css: css, media: media, sourceMap: sourceMap }
    if (!newStyles[id]) {
      part.id = parentId + ':0'
      styles.push(newStyles[id] = { id: id, parts: [part] })
    } else {
      part.id = parentId + ':' + newStyles[id].parts.length
      newStyles[id].parts.push(part)
    }
  }
  return styles
}

function createStyleElement () {
  var styleElement = document.createElement('style')
  styleElement.type = 'text/css'
  head.appendChild(styleElement)
  return styleElement
}

function addStyle (obj /* StyleObjectPart */) {
  var update, remove
  var styleElement = document.querySelector('style[data-vue-ssr-id~="' + obj.id + '"]')
  var hasSSR = styleElement != null

  // if in production mode and style is already provided by SSR,
  // simply do nothing.
  if (hasSSR && isProduction) {
    return noop
  }

  if (isOldIE) {
    // use singleton mode for IE9.
    var styleIndex = singletonCounter++
    styleElement = singletonElement || (singletonElement = createStyleElement())
    update = applyToSingletonTag.bind(null, styleElement, styleIndex, false)
    remove = applyToSingletonTag.bind(null, styleElement, styleIndex, true)
  } else {
    // use multi-style-tag mode in all other cases
    styleElement = styleElement || createStyleElement()
    update = applyToTag.bind(null, styleElement)
    remove = function () {
      styleElement.parentNode.removeChild(styleElement)
    }
  }

  if (!hasSSR) {
    update(obj)
  }

  return function updateStyle (newObj /* StyleObjectPart */) {
    if (newObj) {
      if (newObj.css === obj.css &&
          newObj.media === obj.media &&
          newObj.sourceMap === obj.sourceMap) {
        return
      }
      update(obj = newObj)
    } else {
      remove()
    }
  }
}

var replaceText = (function () {
  var textStore = []

  return function (index, replacement) {
    textStore[index] = replacement
    return textStore.filter(Boolean).join('\n')
  }
})()

function applyToSingletonTag (styleElement, index, remove, obj) {
  var css = remove ? '' : obj.css

  if (styleElement.styleSheet) {
    styleElement.styleSheet.cssText = replaceText(index, css)
  } else {
    var cssNode = document.createTextNode(css)
    var childNodes = styleElement.childNodes
    if (childNodes[index]) styleElement.removeChild(childNodes[index])
    if (childNodes.length) {
      styleElement.insertBefore(cssNode, childNodes[index])
    } else {
      styleElement.appendChild(cssNode)
    }
  }
}

function applyToTag (styleElement, obj) {
  var css = obj.css
  var media = obj.media
  var sourceMap = obj.sourceMap

  if (media) {
    styleElement.setAttribute('media', media)
  }

  if (sourceMap) {
    // https://developer.chrome.com/devtools/docs/javascript-debugging
    // this makes source maps inside style tags work properly in Chrome
    css += '\n/*# sourceURL=' + sourceMap.sources[0] + ' */'
    // http://stackoverflow.com/a/26603875
    css += '\n/*# sourceMappingURL=data:application/json;base64,' + btoa(unescape(encodeURIComponent(JSON.stringify(sourceMap)))) + ' */'
  }

  if (styleElement.styleSheet) {
    styleElement.styleSheet.cssText = css
  } else {
    while (styleElement.firstChild) {
      styleElement.removeChild(styleElement.firstChild)
    }
    styleElement.appendChild(document.createTextNode(css))
  }
}


/***/ }),
/* 18 */,
/* 19 */
/***/ (function(module, exports, __webpack_require__) {

var Component = __webpack_require__(1)(
  /* script */
  __webpack_require__(42),
  /* template */
  __webpack_require__(61),
  /* scopeId */
  null,
  /* cssModules */
  null
)
Component.options.__file = "/var/www/ohcasey.ru/www/resources/assets/js/components/Product.vue"
if (Component.esModule && Object.keys(Component.esModule).some(function (key) {return key !== "default" && key !== "__esModule"})) {console.error("named exports are not supported in *.vue files.")}
if (Component.options.functional) {console.error("[vue-loader] Product.vue: functional components are not supported with templates, they should use render functions.")}

/* hot reload */
if (false) {(function () {
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), false)
  if (!hotAPI.compatible) return
  module.hot.accept()
  if (!module.hot.data) {
    hotAPI.createRecord("data-v-4e216d82", Component.options)
  } else {
    hotAPI.reload("data-v-4e216d82", Component.options)
  }
})()}

module.exports = Component.exports


/***/ }),
/* 20 */
/***/ (function(module, exports, __webpack_require__) {

var Component = __webpack_require__(1)(
  /* script */
  __webpack_require__(43),
  /* template */
  __webpack_require__(58),
  /* scopeId */
  null,
  /* cssModules */
  null
)
Component.options.__file = "/var/www/ohcasey.ru/www/resources/assets/js/components/ProductCreate.vue"
if (Component.esModule && Object.keys(Component.esModule).some(function (key) {return key !== "default" && key !== "__esModule"})) {console.error("named exports are not supported in *.vue files.")}
if (Component.options.functional) {console.error("[vue-loader] ProductCreate.vue: functional components are not supported with templates, they should use render functions.")}

/* hot reload */
if (false) {(function () {
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), false)
  if (!hotAPI.compatible) return
  module.hot.accept()
  if (!module.hot.data) {
    hotAPI.createRecord("data-v-081e0f1b", Component.options)
  } else {
    hotAPI.reload("data-v-081e0f1b", Component.options)
  }
})()}

module.exports = Component.exports


/***/ }),
/* 21 */
/***/ (function(module, exports, __webpack_require__) {

var Component = __webpack_require__(1)(
  /* script */
  __webpack_require__(44),
  /* template */
  __webpack_require__(62),
  /* scopeId */
  null,
  /* cssModules */
  null
)
Component.options.__file = "/var/www/ohcasey.ru/www/resources/assets/js/components/ProductEdit.vue"
if (Component.esModule && Object.keys(Component.esModule).some(function (key) {return key !== "default" && key !== "__esModule"})) {console.error("named exports are not supported in *.vue files.")}
if (Component.options.functional) {console.error("[vue-loader] ProductEdit.vue: functional components are not supported with templates, they should use render functions.")}

/* hot reload */
if (false) {(function () {
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), false)
  if (!hotAPI.compatible) return
  module.hot.accept()
  if (!module.hot.data) {
    hotAPI.createRecord("data-v-5b155ae9", Component.options)
  } else {
    hotAPI.reload("data-v-5b155ae9", Component.options)
  }
})()}

module.exports = Component.exports


/***/ }),
/* 22 */,
/* 23 */,
/* 24 */,
/* 25 */,
/* 26 */,
/* 27 */,
/* 28 */,
/* 29 */,
/* 30 */,
/* 31 */,
/* 32 */,
/* 33 */,
/* 34 */,
/* 35 */,
/* 36 */,
/* 37 */,
/* 38 */,
/* 39 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


Object.defineProperty(exports, "__esModule", {
    value: true
});

var _vue = __webpack_require__(3);

var _vue2 = _interopRequireDefault(_vue);

var _axios = __webpack_require__(2);

var _axios2 = _interopRequireDefault(_axios);

var _vuestore = __webpack_require__(7);

var _vuestore2 = _interopRequireDefault(_vuestore);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

exports.default = {
    data: function data() {
        return {
            vuestore: _vuestore2.default,
            lroutes: lroutes,
            fields: [{ key: 'code', name: 'Артикул', type: 'string' }, { key: 'name', name: 'Название', type: 'string' }, { key: 'price_string', name: 'Цена', type: 'string' }, { key: 'categories', name: 'Категории', type: 'array' }, { key: 'active', name: 'Активен', type: 'boolean' }],
            products: [],
            offset: 4,
            search: '',
            active: null,
            activeOptions: [{ text: 'Все', value: null }, { text: 'Активные', value: 1 }, { text: 'Неактивные', value: 0 }],
            category: null,
            categoryOptions: [{ text: 'Все', value: null }]
        };
    },
    created: function created() {
        this.updateTable();
        this.getCategories();
    },

    methods: {
        updateTable: function updateTable() {
            var _this = this;

            if (this.vuestore.loading) return;else this.vuestore.loading = true;
            var settings = {
                method: 'get',
                path: lroutes.api.admin.ecommerce.product.index,
                params: {
                    page: this.products.current_page
                }
            };
            if (this.search) settings.params.search = this.search;
            if (this.active != null) settings.params.active = this.active;
            if (this.category != null) settings.params.category = this.category;
            this.vuestore.request(settings).then(function (response) {
                _this.products = response.data;
                _this.vuestore.loading = false;
            }).catch(function (response) {
                console.error(response);
                _this.vuestore.loading = false;
            });
        },
        changePage: function changePage(page) {
            this.products.current_page = page;
            this.updateTable();
        },
        searchProducts: function searchProducts() {
            this.updateTable();
        },
        getCategories: function getCategories() {
            var _this2 = this;

            if (this.categoryOptions.length > 1) return;else this.vuestore.loading = true;
            var settings = {
                method: 'get',
                path: lroutes.api.admin.ecommerce.category.index
            };
            this.vuestore.request(settings).then(function (response) {
                // this.categoryOptions = this.categoryOptions.concat(response.data)
                response.data.forEach(function (item) {
                    _this2.categoryOptions.push({ value: item.id, text: item.path });
                });
                _this2.vuestore.loading = false;
            }).catch(function (response) {
                console.error(response);
                _this2.vuestore.loading = false;
            });
        },
        filterCategory: function filterCategory() {
            this.updateTable();
        }
    },
    computed: {
        pagesNumbers: function pagesNumbers() {
            if (!this.products.to) {
                return [];
            }
            var from = this.products.current_page - this.offset;
            if (from < 1) {
                from = 1;
            }
            var to = from + this.offset * 2;
            if (to >= this.products.last_page) {
                to = this.products.last_page;
            }
            var pagesArray = [];
            while (from <= to) {
                pagesArray.push(from);
                from++;
            }
            return pagesArray;
        }
    }
}; //
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//

/***/ }),
/* 40 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


Object.defineProperty(exports, "__esModule", {
    value: true
});

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }(); //
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//

var _axios = __webpack_require__(2);

var _axios2 = _interopRequireDefault(_axios);

var _vuestore = __webpack_require__(7);

var _vuestore2 = _interopRequireDefault(_vuestore);

var _quillEditor = __webpack_require__(57);

var _quillEditor2 = _interopRequireDefault(_quillEditor);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

exports.default = {
    data: function data() {
        return {
            lroutes: lroutes,
            vuestore: _vuestore2.default,
            selected: [],
            default_selected: [],
            category_options: [],
            form: new Form({
                name: '',
                code: '',
                title: '',
                description: '',
                price: '',
                discount: '',
                active: false,
                categories: this.selected
            })
        };
    },

    components: {
        quillEditor: _quillEditor2.default
    },
    created: function created() {
        var _this = this;

        if (this.$route.params.id) {
            var path = lroutes.api.admin.ecommerce.product.edit.replace(/{.*}/, this.$route.params.id);
        } else {
            var path = lroutes.api.admin.ecommerce.product.create;
        }
        this.vuestore.loading = true;
        this.form.get(path).then(function (response) {
            if (response.data) {
                _this.category_options = response.data.data.category_options;
                _this.form.categories.forEach(function (c) {
                    return _this.selected.push(c.id);
                });
                _this.default_selected = _this.selected;
            }
            _this.vuestore.loading = false;
        }).catch(function (response) {
            // console.error(response)
            _this.vuestore.loading = false;
        });
    },

    methods: {
        onSubmit: function onSubmit() {
            var _this2 = this;

            if (this.vuestore.loading) return;else this.vuestore.loading = true;
            if (this.$route.params.id) {
                var path = lroutes.api.admin.ecommerce.product.update.replace(/{.*}/, this.$route.params.id);
                this.form.put(path).then(function (response) {
                    // console.log(response)
                    _this2.vuestore.loading = false;
                    _this2.$root.$router.push({ name: 'product.index' });
                }).catch(function (response) {
                    console.error(response);
                    _this2.vuestore.loading = false;
                });
            } else {
                var _path = lroutes.api.admin.ecommerce.product.store;
                this.form.post(_path).then(function (response) {
                    // console.log(response)
                    _this2.vuestore.loading = false;
                    _this2.$root.$router.push({ name: 'product.index' });
                }).catch(function (response) {
                    console.error(response);
                    _this2.vuestore.loading = false;
                });
            }
        },
        resetCategories: function resetCategories() {
            this.selected = this.default_selected;
        }
    },
    watch: {
        selected: function selected() {
            this.form.categories = this.selected;
        }
    }
};

var Errors = function () {
    /**
     * Create a new Errors instance.
     */
    function Errors() {
        _classCallCheck(this, Errors);

        this.errors = {};
    }

    /**
     * Determine if an errors exists for the given field.
     *
     * @param {string} field
     */


    _createClass(Errors, [{
        key: 'has',
        value: function has(field) {
            return this.errors.hasOwnProperty(field);
        }

        /**
         * Determine if we have any errors.
         */

    }, {
        key: 'any',
        value: function any() {
            return Object.keys(this.errors).length > 0;
        }

        /**
         * Retrieve the error message for a field.
         *
         * @param {string} field
         */

    }, {
        key: 'get',
        value: function get(field) {
            if (this.errors[field]) {
                return this.errors[field][0];
            }
        }

        /**
         * Record the new errors.
         *
         * @param {object} errors
         */

    }, {
        key: 'record',
        value: function record(errors) {
            this.errors = errors;
        }

        /**
         * Clear one or all error fields.
         *
         * @param {string|null} field
         */

    }, {
        key: 'clear',
        value: function clear(field) {
            if (field) {
                delete this.errors[field];

                return;
            }

            this.errors = {};
        }
    }]);

    return Errors;
}();

var Form = function () {
    /**
     * Create a new Form instance.
     *
     * @param {object} data
     */
    function Form(data) {
        _classCallCheck(this, Form);

        this.originalData = data;

        for (var field in data) {
            this[field] = data[field];
        }

        this.errors = new Errors();
    }

    /**
     * Fetch all relevant data for the form.
     */


    _createClass(Form, [{
        key: 'data',
        value: function data() {
            var data = {};

            for (var property in this.originalData) {
                data[property] = this[property];
            }

            return data;
        }

        /**
         * Reset the form fields.
         */

    }, {
        key: 'reset',
        value: function reset() {
            for (var field in this.originalData) {
                this[field] = '';
            }

            this.errors.clear();
        }

        /**
         * Send a GET request to the given URL.
         * .
         * @param {string} url
         */

    }, {
        key: 'get',
        value: function get(url) {
            return this.submit('get', url);
        }

        /**
         * Send a POST request to the given URL.
         * .
         * @param {string} url
         */

    }, {
        key: 'post',
        value: function post(url) {
            return this.submit('post', url);
        }

        /**
         * Send a PUT request to the given URL.
         * .
         * @param {string} url
         */

    }, {
        key: 'put',
        value: function put(url) {
            return this.submit('put', url);
        }

        /**
         * Send a PATCH request to the given URL.
         * .
         * @param {string} url
         */

    }, {
        key: 'patch',
        value: function patch(url) {
            return this.submit('patch', url);
        }

        /**
         * Send a DELETE request to the given URL.
         * .
         * @param {string} url
         */

    }, {
        key: 'delete',
        value: function _delete(url) {
            return this.submit('delete', url);
        }

        /**
         * Submit the form.
         *
         * @param {string} requestType
         * @param {string} url
         */

    }, {
        key: 'submit',
        value: function submit(requestType, url) {
            var _this3 = this;

            return new Promise(function (resolve, reject) {
                _axios2.default[requestType](url, _this3.data()).then(function (response) {
                    if (response.data) {
                        _this3.onSuccess(response.data);
                    }

                    resolve(response);
                }).catch(function (error) {
                    if (error.response) {
                        _this3.onFail(error.response.data);
                    }

                    reject(error);
                });
            });
        }

        /**
         * Handle a successful form submission.
         *
         * @param {object} data
         */

    }, {
        key: 'onSuccess',
        value: function onSuccess(data) {
            if (data.form) {
                for (var field in this.originalData) {
                    this[field] = data.form[field];
                }
            }
        }

        /**
         * Handle a failed form submission.
         *
         * @param {object} errors
         */

    }, {
        key: 'onFail',
        value: function onFail(errors) {
            this.errors.record(errors);
        }
    }]);

    return Form;
}();

/***/ }),
/* 41 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


Object.defineProperty(exports, "__esModule", {
    value: true
});

var _loader = __webpack_require__(56);

var _loader2 = _interopRequireDefault(_loader);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

exports.default = {
    components: {
        loader: _loader2.default
    }
}; //
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//

/***/ }),
/* 42 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


Object.defineProperty(exports, "__esModule", {
    value: true
});

var _Layout = __webpack_require__(8);

var _Layout2 = _interopRequireDefault(_Layout);

var _DataTable = __webpack_require__(55);

var _DataTable2 = _interopRequireDefault(_DataTable);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

//
//
//
//
//
//
//
//
//
//

exports.default = {
    components: {
        Layout: _Layout2.default,
        DataTable: _DataTable2.default
    }
};

/***/ }),
/* 43 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


Object.defineProperty(exports, "__esModule", {
    value: true
});

var _Layout = __webpack_require__(8);

var _Layout2 = _interopRequireDefault(_Layout);

var _Form = __webpack_require__(16);

var _Form2 = _interopRequireDefault(_Form);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

//
//
//
//
//
//
//
//
//
//

exports.default = {
    components: {
        Layout: _Layout2.default,
        ProductForm: _Form2.default
    }
};

/***/ }),
/* 44 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


Object.defineProperty(exports, "__esModule", {
    value: true
});

var _Layout = __webpack_require__(8);

var _Layout2 = _interopRequireDefault(_Layout);

var _Form = __webpack_require__(16);

var _Form2 = _interopRequireDefault(_Form);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

//
//
//
//
//
//
//
//
//
//

exports.default = {
    components: {
        Layout: _Layout2.default,
        ProductForm: _Form2.default
    }
};

/***/ }),
/* 45 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


Object.defineProperty(exports, "__esModule", {
    value: true
});

var _vuestore = __webpack_require__(7);

var _vuestore2 = _interopRequireDefault(_vuestore);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

exports.default = {
    data: function data() {
        return {
            vuestore: _vuestore2.default
        };
    }
}; //
//
//
//
//
//
//
//

/***/ }),
/* 46 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


Object.defineProperty(exports, "__esModule", {
    value: true
});

__webpack_require__(50);

__webpack_require__(49);

var _quill = __webpack_require__(9);

var _quill2 = _interopRequireDefault(_quill);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

exports.default = {
    props: {
        value: String,
        config: {
            default: function _default() {
                return {
                    placeholder: 'Описание товара',
                    modules: {
                        toolbar: [[{ header: [2, 3, false] }], ['bold', 'italic', 'underline'], [{ 'list': 'ordered' }, { 'list': 'bullet' }], ['clean']]
                    },
                    theme: 'snow'
                };
            }
        }
    },
    data: function data() {
        return {
            quill: {},
            content: ''
        };
    },
    mounted: function mounted() {

        this.quill = new _quill2.default(this.$refs.quill, this.config);
        // this.quill.clipboard.dangerouslyPasteHTML(0, this.value)
        // this.quill.pasteHTML(this.value)
        var self = this;
        this.quill.on('text-change', function () {
            if (self.quill.getLength() <= 1) self.content = '';else self.content = self.quill.container.firstChild.innerHTML;
            self.$emit('input', self.content);
        });
    },

    watch: {
        value: function value(newVal, oldVal) {
            if (this.quill) {
                if (newVal !== this.content) {
                    this.quill.pasteHTML(newVal);
                }
            }
        }
    }

}; //
//
//
//

/***/ }),
/* 47 */
/***/ (function(module, exports, __webpack_require__) {

exports = module.exports = __webpack_require__(15)();
// imports


// module
exports.push([module.i, "\n.load-bar {\n    position: fixed;\n    top:0;\n    left: 0;\n    width: 100%;\n    height: 5px;\n    background-color: #fdba2c;\n    z-index: 10000;\n}\n.bar {\n    content: \"\";\n    display: inline;\n    position: absolute;\n    width: 0;\n    height: 100%;\n    left: 50%;\n    text-align: center;\n}\n.bar:nth-child(1) {\n    background-color: #da4733;\n    animation: loading 3s linear infinite;\n}\n.bar:nth-child(2) {\n    background-color: #3b78e7;\n    animation: loading 3s linear 1s infinite;\n}\n.bar:nth-child(3) {\n    background-color: #fdba2c;\n    animation: loading 3s linear 2s infinite;\n}\n@keyframes loading {\nfrom {left: 50%; width: 0;z-index:100;\n}\n33.3333% {left: 0; width: 100%;z-index: 10;\n}\nto {left: 0; width: 100%;\n}\n}\n", ""]);

// exports


/***/ }),
/* 48 */
/***/ (function(module, exports, __webpack_require__) {

exports = module.exports = __webpack_require__(15)();
// imports


// module
exports.push([module.i, "\n.category.label{\n    margin-right:5px;\n}\n", ""]);

// exports


/***/ }),
/* 49 */
/***/ (function(module, exports) {

// removed by extract-text-webpack-plugin

/***/ }),
/* 50 */
/***/ (function(module, exports) {

// removed by extract-text-webpack-plugin

/***/ }),
/* 51 */,
/* 52 */,
/* 53 */,
/* 54 */,
/* 55 */
/***/ (function(module, exports, __webpack_require__) {


/* styles */
__webpack_require__(67)

var Component = __webpack_require__(1)(
  /* script */
  __webpack_require__(39),
  /* template */
  __webpack_require__(63),
  /* scopeId */
  null,
  /* cssModules */
  null
)
Component.options.__file = "/var/www/ohcasey.ru/www/resources/assets/js/components/DataTable.vue"
if (Component.esModule && Object.keys(Component.esModule).some(function (key) {return key !== "default" && key !== "__esModule"})) {console.error("named exports are not supported in *.vue files.")}
if (Component.options.functional) {console.error("[vue-loader] DataTable.vue: functional components are not supported with templates, they should use render functions.")}

/* hot reload */
if (false) {(function () {
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), false)
  if (!hotAPI.compatible) return
  module.hot.accept()
  if (!module.hot.data) {
    hotAPI.createRecord("data-v-78e4f214", Component.options)
  } else {
    hotAPI.reload("data-v-78e4f214", Component.options)
  }
})()}

module.exports = Component.exports


/***/ }),
/* 56 */
/***/ (function(module, exports, __webpack_require__) {


/* styles */
__webpack_require__(66)

var Component = __webpack_require__(1)(
  /* script */
  __webpack_require__(45),
  /* template */
  __webpack_require__(59),
  /* scopeId */
  null,
  /* cssModules */
  null
)
Component.options.__file = "/var/www/ohcasey.ru/www/resources/assets/js/components/loader.vue"
if (Component.esModule && Object.keys(Component.esModule).some(function (key) {return key !== "default" && key !== "__esModule"})) {console.error("named exports are not supported in *.vue files.")}
if (Component.options.functional) {console.error("[vue-loader] loader.vue: functional components are not supported with templates, they should use render functions.")}

/* hot reload */
if (false) {(function () {
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), false)
  if (!hotAPI.compatible) return
  module.hot.accept()
  if (!module.hot.data) {
    hotAPI.createRecord("data-v-1848e4d3", Component.options)
  } else {
    hotAPI.reload("data-v-1848e4d3", Component.options)
  }
})()}

module.exports = Component.exports


/***/ }),
/* 57 */
/***/ (function(module, exports, __webpack_require__) {

var Component = __webpack_require__(1)(
  /* script */
  __webpack_require__(46),
  /* template */
  __webpack_require__(65),
  /* scopeId */
  null,
  /* cssModules */
  null
)
Component.options.__file = "/var/www/ohcasey.ru/www/resources/assets/js/components/quill-editor.vue"
if (Component.esModule && Object.keys(Component.esModule).some(function (key) {return key !== "default" && key !== "__esModule"})) {console.error("named exports are not supported in *.vue files.")}
if (Component.options.functional) {console.error("[vue-loader] quill-editor.vue: functional components are not supported with templates, they should use render functions.")}

/* hot reload */
if (false) {(function () {
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), false)
  if (!hotAPI.compatible) return
  module.hot.accept()
  if (!module.hot.data) {
    hotAPI.createRecord("data-v-de8b4a16", Component.options)
  } else {
    hotAPI.reload("data-v-de8b4a16", Component.options)
  }
})()}

module.exports = Component.exports


/***/ }),
/* 58 */
/***/ (function(module, exports, __webpack_require__) {

module.exports={render:function (){var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
  return _c('layout', [_c('h1', {
    slot: "title"
  }, [_vm._v("Новый товар")]), _vm._v(" "), _c('template', {
    slot: "panel-title"
  }, [_vm._v("Добавить товар")]), _vm._v(" "), _c('template', {
    slot: "body"
  }, [_c('product-form')], 1)], 2)
},staticRenderFns: []}
module.exports.render._withStripped = true
if (false) {
  module.hot.accept()
  if (module.hot.data) {
     require("vue-hot-reload-api").rerender("data-v-081e0f1b", module.exports)
  }
}

/***/ }),
/* 59 */
/***/ (function(module, exports, __webpack_require__) {

module.exports={render:function (){var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
  return (_vm.vuestore.loading) ? _c('div', {
    staticClass: "load-bar"
  }, [_c('div', {
    staticClass: "bar"
  }), _vm._v(" "), _c('div', {
    staticClass: "bar"
  }), _vm._v(" "), _c('div', {
    staticClass: "bar"
  })]) : _vm._e()
},staticRenderFns: []}
module.exports.render._withStripped = true
if (false) {
  module.hot.accept()
  if (module.hot.data) {
     require("vue-hot-reload-api").rerender("data-v-1848e4d3", module.exports)
  }
}

/***/ }),
/* 60 */
/***/ (function(module, exports, __webpack_require__) {

module.exports={render:function (){var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
  return _c('div', {
    staticClass: "col-lg-6"
  }, [_c('div', {
    staticClass: "panel panel-default"
  }, [_c('div', {
    staticClass: "panel-body"
  }, [_c('form', {
    attrs: {
      "action": ""
    },
    on: {
      "submit": function($event) {
        $event.preventDefault();
        _vm.onSubmit()
      },
      "keydown": function($event) {
        _vm.form.errors.clear($event.target.name)
      }
    }
  }, [_c('div', {
    staticClass: "form-group",
    class: [_vm.form.errors.has('name') ? 'has-error' : '']
  }, [_c('label', [_vm._v("Название товара")]), _vm._v(" "), _c('input', {
    directives: [{
      name: "model",
      rawName: "v-model",
      value: (_vm.form.name),
      expression: "form.name"
    }],
    staticClass: "form-control",
    attrs: {
      "name": "name",
      "type": "text"
    },
    domProps: {
      "value": _vm._s(_vm.form.name)
    },
    on: {
      "input": function($event) {
        if ($event.target.composing) { return; }
        _vm.form.name = $event.target.value
      }
    }
  }), _vm._v(" "), (_vm.form.errors.has('name')) ? _c('span', {
    staticClass: "help-block is-danger",
    domProps: {
      "textContent": _vm._s(_vm.form.errors.get('name'))
    }
  }) : _vm._e()]), _vm._v(" "), _c('div', {
    staticClass: "form-group",
    class: [_vm.form.errors.has('code') ? 'has-error' : '']
  }, [_c('label', [_vm._v("Артикул")]), _vm._v(" "), _c('input', {
    directives: [{
      name: "model",
      rawName: "v-model",
      value: (_vm.form.code),
      expression: "form.code"
    }],
    staticClass: "form-control",
    attrs: {
      "name": "code",
      "type": "text"
    },
    domProps: {
      "value": _vm._s(_vm.form.code)
    },
    on: {
      "input": function($event) {
        if ($event.target.composing) { return; }
        _vm.form.code = $event.target.value
      }
    }
  }), _vm._v(" "), (_vm.form.errors.has('code')) ? _c('span', {
    staticClass: "help-block is-danger",
    domProps: {
      "textContent": _vm._s(_vm.form.errors.get('code'))
    }
  }) : _vm._e()]), _vm._v(" "), _c('div', {
    staticClass: "form-group",
    class: [_vm.form.errors.has('title') ? 'has-error' : '']
  }, [_c('label', [_vm._v("Title meta")]), _vm._v(" "), _c('input', {
    directives: [{
      name: "model",
      rawName: "v-model",
      value: (_vm.form.title),
      expression: "form.title"
    }],
    staticClass: "form-control",
    attrs: {
      "name": "title",
      "type": "text"
    },
    domProps: {
      "value": _vm._s(_vm.form.title)
    },
    on: {
      "input": function($event) {
        if ($event.target.composing) { return; }
        _vm.form.title = $event.target.value
      }
    }
  }), _vm._v(" "), (_vm.form.errors.has('title')) ? _c('span', {
    staticClass: "help-block is-danger",
    domProps: {
      "textContent": _vm._s(_vm.form.errors.get('title'))
    }
  }) : _vm._e()]), _vm._v(" "), _c('div', {
    staticClass: "form-group",
    class: [_vm.form.errors.has('cat') ? 'has-error' : '']
  }, [_c('label', [_vm._v("Категории")]), _vm._v(" "), _c('select', {
    directives: [{
      name: "model",
      rawName: "v-model",
      value: (_vm.selected),
      expression: "selected"
    }],
    staticClass: "form-control",
    attrs: {
      "multiple": "",
      "size": "8",
      "data-default": "default_selected"
    },
    on: {
      "change": function($event) {
        _vm.selected = Array.prototype.filter.call($event.target.options, function(o) {
          return o.selected
        }).map(function(o) {
          var val = "_value" in o ? o._value : o.value;
          return val
        })
      }
    }
  }, _vm._l((this.category_options), function(option) {
    return _c('option', {
      domProps: {
        "value": option.id,
        "innerHTML": _vm._s(option.path)
      }
    })
  })), _vm._v(" "), (_vm.form.errors.has('cat')) ? _c('span', {
    staticClass: "help-block is-danger",
    domProps: {
      "textContent": _vm._s(_vm.form.errors.get('cat'))
    }
  }) : _vm._e(), _vm._v(" "), _c('a', {
    on: {
      "click": function($event) {
        _vm.resetCategories()
      }
    }
  }, [_vm._v("Восстановить")])]), _vm._v(" "), _c('div', {
    staticClass: "form-group",
    class: [_vm.form.errors.has('description') ? 'has-error' : '']
  }, [_c('label', [_vm._v("Описание")]), _vm._v(" "), _c('textarea', {
    directives: [{
      name: "model",
      rawName: "v-model",
      value: (_vm.form.description),
      expression: "form.description"
    }],
    staticClass: "form-control",
    staticStyle: {
      "display": "none"
    },
    attrs: {
      "name": "description",
      "type": "text"
    },
    domProps: {
      "value": _vm._s(_vm.form.description)
    },
    on: {
      "input": function($event) {
        if ($event.target.composing) { return; }
        _vm.form.description = $event.target.value
      }
    }
  }), _vm._v(" "), _c('quill-editor', {
    directives: [{
      name: "model",
      rawName: "v-model",
      value: (_vm.form.description),
      expression: "form.description"
    }],
    domProps: {
      "value": (_vm.form.description)
    },
    on: {
      "input": function($event) {
        _vm.form.description = $event
      }
    }
  }), _vm._v(" "), (_vm.form.errors.has('description')) ? _c('span', {
    staticClass: "help-block is-danger",
    domProps: {
      "textContent": _vm._s(_vm.form.errors.get('description'))
    }
  }) : _vm._e()], 1), _vm._v(" "), _c('div', {
    staticClass: "form-group",
    class: [_vm.form.errors.has('price') ? 'has-error' : '']
  }, [_c('label', [_vm._v("Цена (₽)")]), _vm._v(" "), _c('input', {
    directives: [{
      name: "model",
      rawName: "v-model",
      value: (_vm.form.price),
      expression: "form.price"
    }],
    staticClass: "form-control",
    attrs: {
      "name": "price",
      "type": "text"
    },
    domProps: {
      "value": _vm._s(_vm.form.price)
    },
    on: {
      "input": function($event) {
        if ($event.target.composing) { return; }
        _vm.form.price = $event.target.value
      }
    }
  }), _vm._v(" "), (_vm.form.errors.has('price')) ? _c('span', {
    staticClass: "help-block is-danger",
    domProps: {
      "textContent": _vm._s(_vm.form.errors.get('price'))
    }
  }) : _vm._e()]), _vm._v(" "), _c('div', {
    staticClass: "form-group",
    class: [_vm.form.errors.has('discount') ? 'has-error' : '']
  }, [_c('label', [_vm._v("Размер скидки")]), _vm._v(" "), _c('input', {
    directives: [{
      name: "model",
      rawName: "v-model",
      value: (_vm.form.discount),
      expression: "form.discount"
    }],
    staticClass: "form-control",
    attrs: {
      "name": "discount",
      "type": "text"
    },
    domProps: {
      "value": _vm._s(_vm.form.discount)
    },
    on: {
      "input": function($event) {
        if ($event.target.composing) { return; }
        _vm.form.discount = $event.target.value
      }
    }
  }), _vm._v(" "), (_vm.form.errors.has('discount')) ? _c('span', {
    staticClass: "help-block is-danger",
    domProps: {
      "textContent": _vm._s(_vm.form.errors.get('discount'))
    }
  }) : _vm._e()]), _vm._v(" "), _c('div', {
    staticClass: "checkbox"
  }, [_c('label', [_c('input', {
    directives: [{
      name: "model",
      rawName: "v-model",
      value: (_vm.form.active),
      expression: "form.active"
    }],
    attrs: {
      "type": "checkbox"
    },
    domProps: {
      "checked": Array.isArray(_vm.form.active) ? _vm._i(_vm.form.active, null) > -1 : (_vm.form.active)
    },
    on: {
      "click": function($event) {
        var $$a = _vm.form.active,
          $$el = $event.target,
          $$c = $$el.checked ? (true) : (false);
        if (Array.isArray($$a)) {
          var $$v = null,
            $$i = _vm._i($$a, $$v);
          if ($$c) {
            $$i < 0 && (_vm.form.active = $$a.concat($$v))
          } else {
            $$i > -1 && (_vm.form.active = $$a.slice(0, $$i).concat($$a.slice($$i + 1)))
          }
        } else {
          _vm.form.active = $$c
        }
      }
    }
  }), _vm._v(" Активен\n                    ")])]), _vm._v(" "), _c('div', {
    staticClass: "form-group"
  }, [_c('button', {
    staticClass: "btn btn-primary",
    attrs: {
      "type": "submit"
    }
  }, [_vm._v("Сохранить")]), _vm._v(" "), _c('router-link', {
    staticClass: "btn btn-default",
    attrs: {
      "to": {
        name: 'product.index'
      }
    }
  }, [_vm._v("Отмена")])], 1)])])])])
},staticRenderFns: []}
module.exports.render._withStripped = true
if (false) {
  module.hot.accept()
  if (module.hot.data) {
     require("vue-hot-reload-api").rerender("data-v-4bfb2f24", module.exports)
  }
}

/***/ }),
/* 61 */
/***/ (function(module, exports, __webpack_require__) {

module.exports={render:function (){var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
  return _c('layout', [_c('h1', {
    slot: "title"
  }, [_vm._v("Список товаров")]), _vm._v(" "), _c('template', {
    slot: "panel-title"
  }, [_vm._v("Товары")]), _vm._v(" "), _c('template', {
    slot: "body"
  }, [_c('data-table')], 1)], 2)
},staticRenderFns: []}
module.exports.render._withStripped = true
if (false) {
  module.hot.accept()
  if (module.hot.data) {
     require("vue-hot-reload-api").rerender("data-v-4e216d82", module.exports)
  }
}

/***/ }),
/* 62 */
/***/ (function(module, exports, __webpack_require__) {

module.exports={render:function (){var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
  return _c('layout', [_c('h1', {
    slot: "title"
  }, [_vm._v("Редкатровать товар")]), _vm._v(" "), _c('template', {
    slot: "panel-title"
  }, [_vm._v("Редактирование товара")]), _vm._v(" "), _c('template', {
    slot: "body"
  }, [_c('product-form')], 1)], 2)
},staticRenderFns: []}
module.exports.render._withStripped = true
if (false) {
  module.hot.accept()
  if (module.hot.data) {
     require("vue-hot-reload-api").rerender("data-v-5b155ae9", module.exports)
  }
}

/***/ }),
/* 63 */
/***/ (function(module, exports, __webpack_require__) {

module.exports={render:function (){var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
  return _c('div', [_c('div', {
    staticClass: "form-group"
  }, [_c('router-link', {
    staticClass: "btn btn-success",
    attrs: {
      "to": {
        name: 'product.create'
      }
    }
  }, [_vm._v("Добавть товар")])], 1), _vm._v(" "), _c('div', {
    staticClass: "row"
  }, [_c('div', {
    staticClass: "col-sm-5 left"
  }, [_c('div', {
    staticClass: "form-group input-group"
  }, [_c('input', {
    directives: [{
      name: "model",
      rawName: "v-model",
      value: (_vm.search),
      expression: "search"
    }],
    staticClass: "form-control",
    attrs: {
      "type": "text",
      "placeholder": "Поиск по артикулу или названию"
    },
    domProps: {
      "value": _vm._s(_vm.search)
    },
    on: {
      "keyup": function($event) {
        if (_vm._k($event.keyCode, "enter", 13)) { return; }
        _vm.searchProducts()
      },
      "input": function($event) {
        if ($event.target.composing) { return; }
        _vm.search = $event.target.value
      }
    }
  }), _vm._v(" "), _c('span', {
    staticClass: "input-group-btn"
  }, [_c('a', {
    staticClass: "btn btn-primary",
    on: {
      "click": function($event) {
        _vm.searchProducts()
      }
    }
  }, [_c('i', {
    staticClass: "fa fa-search"
  })])])])]), _vm._v(" "), _c('div', {
    staticClass: "col-sm-5"
  }, [_c('div', {
    staticClass: "form-group"
  }, [_c('select', {
    directives: [{
      name: "model",
      rawName: "v-model",
      value: (_vm.category),
      expression: "category"
    }],
    staticClass: "form-control",
    attrs: {
      "id": "category-select",
      "name": "category"
    },
    on: {
      "change": [function($event) {
        _vm.category = Array.prototype.filter.call($event.target.options, function(o) {
          return o.selected
        }).map(function(o) {
          var val = "_value" in o ? o._value : o.value;
          return val
        })[0]
      }, function($event) {
        _vm.filterCategory()
      }]
    }
  }, _vm._l((_vm.categoryOptions), function(option) {
    return _c('option', {
      domProps: {
        "value": option.value,
        "selected": option.value == null,
        "innerHTML": _vm._s(option.text)
      }
    })
  }))])]), _vm._v(" "), _c('div', {
    staticClass: "col-sm-2"
  }, [_c('div', {
    staticClass: "form-group"
  }, [_c('select', {
    directives: [{
      name: "model",
      rawName: "v-model",
      value: (_vm.active),
      expression: "active"
    }],
    staticClass: "form-control",
    attrs: {
      "name": "active"
    },
    on: {
      "change": [function($event) {
        _vm.active = Array.prototype.filter.call($event.target.options, function(o) {
          return o.selected
        }).map(function(o) {
          var val = "_value" in o ? o._value : o.value;
          return val
        })[0]
      }, function($event) {
        _vm.searchProducts()
      }]
    }
  }, _vm._l((_vm.activeOptions), function(option) {
    return _c('option', {
      domProps: {
        "value": option.value,
        "selected": option.value == null
      }
    }, [_vm._v("\n                        " + _vm._s(option.text) + "\n                    ")])
  }))])])]), _vm._v(" "), _c('div', {
    staticClass: "table-responsive"
  }, [_c('table', {
    staticClass: "table table-striped table-bordered table-hover"
  }, [_c('thead', [_c('tr', [_c('th', [_vm._v("id")]), _vm._v(" "), _vm._l((_vm.fields), function(field) {
    return _c('th', [_vm._v(_vm._s(field.name))])
  }), _vm._v(" "), _c('th', [_vm._v("edit")])], 2)]), _vm._v(" "), _c('tbody', _vm._l((_vm.products.data), function(row) {
    return _c('tr', [_c('td', [_vm._v(_vm._s(row.id))]), _vm._v(" "), _vm._l((_vm.fields), function(field) {
      return _c('td', [(field.type == 'string') ? [_vm._v(_vm._s(row[field.key]))] : _vm._e(), _vm._v(" "), _vm._l((row[field.key]), function(category) {
        return (field.type == 'array') ? _c('span', {
          staticClass: "category label label-primary"
        }, [_vm._v("\n                            " + _vm._s(category.name) + "\n                        ")]) : _vm._e()
      }), _vm._v(" "), (field.type == 'boolean') ? [_vm._v(_vm._s(row[field.key] == true ? 'Да' : 'Нет'))] : _vm._e()], 2)
    }), _vm._v(" "), _c('td', [_c('router-link', {
      attrs: {
        "to": {
          name: 'product.edit',
          params: {
            id: row.id
          }
        }
      }
    }, [_vm._v("edit")])], 1)], 2)
  }))])]), _vm._v(" "), _c('div', {
    staticClass: "col-sm-6"
  }, [_vm._v("\n        Страница " + _vm._s(_vm.products.current_page) + " из " + _vm._s(_vm.products.last_page) + ". Запись c " + _vm._s(_vm.products.from) + " по " + _vm._s(_vm.products.to) + " из " + _vm._s(_vm.products.total) + "\n    ")]), _vm._v(" "), _c('div', {
    staticClass: "col-sm-6"
  }, [(_vm.products.last_page > 1) ? _c('ul', {
    staticClass: "pagination",
    staticStyle: {
      "margin": "2px 0",
      "float": "right"
    }
  }, [(_vm.products.prev_page_url) ? _c('li', [_c('a', {
    on: {
      "click": function($event) {
        _vm.changePage(1)
      }
    }
  }, [_vm._v("«")])]) : _vm._e(), _vm._v(" "), (_vm.products.prev_page_url) ? _c('li', [_c('a', {
    on: {
      "click": function($event) {
        _vm.changePage(_vm.products.current_page - 1)
      }
    }
  }, [_vm._v("‹")])]) : _vm._e(), _vm._v(" "), _vm._l((_vm.pagesNumbers), function(page) {
    return _c('li', {
      staticClass: "paginate_button",
      class: [(page == _vm.products.current_page) ? 'active' : '']
    }, [_c('a', {
      on: {
        "click": function($event) {
          _vm.changePage(page)
        }
      }
    }, [_vm._v(_vm._s(page))])])
  }), _vm._v(" "), (_vm.products.next_page_url) ? _c('li', [_c('a', {
    on: {
      "click": function($event) {
        _vm.changePage(_vm.products.current_page + 1)
      }
    }
  }, [_vm._v("›")])]) : _vm._e(), _vm._v(" "), (_vm.products.next_page_url) ? _c('li', [_c('a', {
    on: {
      "click": function($event) {
        _vm.changePage(_vm.products.last_page)
      }
    }
  }, [_vm._v("»")])]) : _vm._e()], 2) : _vm._e()])])
},staticRenderFns: []}
module.exports.render._withStripped = true
if (false) {
  module.hot.accept()
  if (module.hot.data) {
     require("vue-hot-reload-api").rerender("data-v-78e4f214", module.exports)
  }
}

/***/ }),
/* 64 */
/***/ (function(module, exports, __webpack_require__) {

module.exports={render:function (){var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
  return _c('div', [_c('loader'), _vm._v(" "), _c('div', {
    staticClass: "row"
  }, [_c('div', {
    staticClass: "col-lg-12"
  }, [_vm._t("title")], 2)]), _vm._v(" "), _c('div', {
    staticClass: "row"
  }, [_c('div', {
    staticClass: "col-lg-12"
  }, [_c('div', {
    staticClass: "panel panel-default"
  }, [_c('div', {
    staticClass: "panel-heading"
  }, [_vm._t("panel-title")], 2), _vm._v(" "), _c('div', {
    staticClass: "panel-body"
  }, [_vm._t("body")], 2)])])])], 1)
},staticRenderFns: []}
module.exports.render._withStripped = true
if (false) {
  module.hot.accept()
  if (module.hot.data) {
     require("vue-hot-reload-api").rerender("data-v-dc71aeec", module.exports)
  }
}

/***/ }),
/* 65 */
/***/ (function(module, exports, __webpack_require__) {

module.exports={render:function (){var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
  return _c('div', {
    ref: "quill",
    attrs: {
      "id": "editor"
    }
  })
},staticRenderFns: []}
module.exports.render._withStripped = true
if (false) {
  module.hot.accept()
  if (module.hot.data) {
     require("vue-hot-reload-api").rerender("data-v-de8b4a16", module.exports)
  }
}

/***/ }),
/* 66 */
/***/ (function(module, exports, __webpack_require__) {

// style-loader: Adds some css to the DOM by adding a <style> tag

// load the styles
var content = __webpack_require__(47);
if(typeof content === 'string') content = [[module.i, content, '']];
if(content.locals) module.exports = content.locals;
// add the styles to the DOM
var update = __webpack_require__(17)("7bb3cc3e", content, false);
// Hot Module Replacement
if(false) {
 // When the styles change, update the <style> tags
 if(!content.locals) {
   module.hot.accept("!!./../../../../node_modules/css-loader/index.js!./../../../../node_modules/vue-loader/lib/style-rewriter.js?id=data-v-1848e4d3!./../../../../node_modules/vue-loader/lib/selector.js?type=styles&index=0!./loader.vue", function() {
     var newContent = require("!!./../../../../node_modules/css-loader/index.js!./../../../../node_modules/vue-loader/lib/style-rewriter.js?id=data-v-1848e4d3!./../../../../node_modules/vue-loader/lib/selector.js?type=styles&index=0!./loader.vue");
     if(typeof newContent === 'string') newContent = [[module.id, newContent, '']];
     update(newContent);
   });
 }
 // When the module is disposed, remove the <style> tags
 module.hot.dispose(function() { update(); });
}

/***/ }),
/* 67 */
/***/ (function(module, exports, __webpack_require__) {

// style-loader: Adds some css to the DOM by adding a <style> tag

// load the styles
var content = __webpack_require__(48);
if(typeof content === 'string') content = [[module.i, content, '']];
if(content.locals) module.exports = content.locals;
// add the styles to the DOM
var update = __webpack_require__(17)("536accee", content, false);
// Hot Module Replacement
if(false) {
 // When the styles change, update the <style> tags
 if(!content.locals) {
   module.hot.accept("!!./../../../../node_modules/css-loader/index.js!./../../../../node_modules/vue-loader/lib/style-rewriter.js?id=data-v-78e4f214!./../../../../node_modules/vue-loader/lib/selector.js?type=styles&index=0!./DataTable.vue", function() {
     var newContent = require("!!./../../../../node_modules/css-loader/index.js!./../../../../node_modules/vue-loader/lib/style-rewriter.js?id=data-v-78e4f214!./../../../../node_modules/vue-loader/lib/selector.js?type=styles&index=0!./DataTable.vue");
     if(typeof newContent === 'string') newContent = [[module.id, newContent, '']];
     update(newContent);
   });
 }
 // When the module is disposed, remove the <style> tags
 module.hot.dispose(function() { update(); });
}

/***/ }),
/* 68 */
/***/ (function(module, exports) {

/**
 * Translates the list format produced by css-loader into something
 * easier to manipulate.
 */
module.exports = function listToStyles (parentId, list) {
  var styles = []
  var newStyles = {}
  for (var i = 0; i < list.length; i++) {
    var item = list[i]
    var id = item[0]
    var css = item[1]
    var media = item[2]
    var sourceMap = item[3]
    var part = {
      id: parentId + ':' + i,
      css: css,
      media: media,
      sourceMap: sourceMap
    }
    if (!newStyles[id]) {
      styles.push(newStyles[id] = { id: id, parts: [part] })
    } else {
      newStyles[id].parts.push(part)
    }
  }
  return styles
}


/***/ }),
/* 69 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


Object.defineProperty(exports, "__esModule", {
    value: true
});

var _vue = __webpack_require__(3);

var _vue2 = _interopRequireDefault(_vue);

var _vueRouter = __webpack_require__(5);

var _vueRouter2 = _interopRequireDefault(_vueRouter);

var _Product = __webpack_require__(19);

var _Product2 = _interopRequireDefault(_Product);

var _ProductCreate = __webpack_require__(20);

var _ProductCreate2 = _interopRequireDefault(_ProductCreate);

var _ProductEdit = __webpack_require__(21);

var _ProductEdit2 = _interopRequireDefault(_ProductEdit);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

// const lroutes = JSON.parse('{!!$routes!!}')
window.lroutes = JSON.parse(document.getElementById('app').dataset.routes);

var routes = [{ path: lroutes.admin.ecommerce.product.index, name: 'product.index', component: _Product2.default }, { path: lroutes.admin.ecommerce.product.create, name: 'product.create', component: _ProductCreate2.default }, { path: lroutes.admin.ecommerce.product.edit.replace(/{.*}/, ':id'), name: 'product.edit', component: _ProductEdit2.default }];

_vue2.default.use(_vueRouter2.default);

var router = new _vueRouter2.default({
    mode: 'history',
    routes: routes
});
// window.router = router

var app = new _vue2.default({
    router: router
}).$mount('#app');

exports.default = app;

/***/ })
],[69]);