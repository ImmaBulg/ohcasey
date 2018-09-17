webpackJsonp([1],[
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
/* 2 */
/***/ (function(module, exports, __webpack_require__) {

var Component = __webpack_require__(1)(
  /* script */
  __webpack_require__(15),
  /* template */
  __webpack_require__(24),
  /* scopeId */
  null,
  /* cssModules */
  null
)
Component.options.__file = "C:\\OSPanel\\domains\\ohcasey.ru\\www\\resources\\assets\\js\\components\\Layout.vue"
if (Component.esModule && Object.keys(Component.esModule).some(function (key) {return key !== "default" && key !== "__esModule"})) {console.error("named exports are not supported in *.vue files.")}
if (Component.options.functional) {console.error("[vue-loader] Layout.vue: functional components are not supported with templates, they should use render functions.")}

/* hot reload */
if (false) {(function () {
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), false)
  if (!hotAPI.compatible) return
  module.hot.accept()
  if (!module.hot.data) {
    hotAPI.createRecord("data-v-0cf751aa", Component.options)
  } else {
    hotAPI.reload("data-v-0cf751aa", Component.options)
  }
})()}

module.exports = Component.exports


/***/ }),
/* 3 */
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
/* 4 */,
/* 5 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


Object.defineProperty(exports, "__esModule", {
    value: true
});

var _axios = __webpack_require__(6);

var _axios2 = _interopRequireDefault(_axios);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

exports.default = {
    loading: false,

    request: function request(settings) {
        return new Promise(function (resolve, reject) {
            _axios2.default[settings.method](settings.path, settings.method == 'get' ? { params: settings.params } : settings.params).then(function (response) {
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
/* 6 */,
/* 7 */
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

var listToStyles = __webpack_require__(28)

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

function createStyleElement () {
  var styleElement = document.createElement('style')
  styleElement.type = 'text/css'
  head.appendChild(styleElement)
  return styleElement
}

function addStyle (obj /* StyleObjectPart */) {
  var update, remove
  var styleElement = document.querySelector('style[data-vue-ssr-id~="' + obj.id + '"]')

  if (styleElement) {
    if (isProduction) {
      // has SSR styles and in production mode.
      // simply do nothing.
      return noop
    } else {
      // has SSR styles but in dev mode.
      // for some reason Chrome can't handle source map in server-rendered
      // style tags - source maps in <style> only works if the style tag is
      // created and inserted dynamically. So we remove the server rendered
      // styles and inject new ones.
      styleElement.parentNode.removeChild(styleElement)
    }
  }

  if (isOldIE) {
    // use singleton mode for IE9.
    var styleIndex = singletonCounter++
    styleElement = singletonElement || (singletonElement = createStyleElement())
    update = applyToSingletonTag.bind(null, styleElement, styleIndex, false)
    remove = applyToSingletonTag.bind(null, styleElement, styleIndex, true)
  } else {
    // use multi-style-tag mode in all other cases
    styleElement = createStyleElement()
    update = applyToTag.bind(null, styleElement)
    remove = function () {
      styleElement.parentNode.removeChild(styleElement)
    }
  }

  update(obj)

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
/* 8 */,
/* 9 */,
/* 10 */
/***/ (function(module, exports) {

/*
	MIT License http://www.opensource.org/licenses/mit-license.php
	Author Tobias Koppers @sokra
*/
var stylesInDom = {},
	memoize = function(fn) {
		var memo;
		return function () {
			if (typeof memo === "undefined") memo = fn.apply(this, arguments);
			return memo;
		};
	},
	isOldIE = memoize(function() {
		return /msie [6-9]\b/.test(self.navigator.userAgent.toLowerCase());
	}),
	getHeadElement = memoize(function () {
		return document.head || document.getElementsByTagName("head")[0];
	}),
	singletonElement = null,
	singletonCounter = 0,
	styleElementsInsertedAtTop = [];

module.exports = function(list, options) {
	if(typeof DEBUG !== "undefined" && DEBUG) {
		if(typeof document !== "object") throw new Error("The style-loader cannot be used in a non-browser environment");
	}

	options = options || {};
	// Force single-tag solution on IE6-9, which has a hard limit on the # of <style>
	// tags it will allow on a page
	if (typeof options.singleton === "undefined") options.singleton = isOldIE();

	// By default, add <style> tags to the bottom of <head>.
	if (typeof options.insertAt === "undefined") options.insertAt = "bottom";

	var styles = listToStyles(list);
	addStylesToDom(styles, options);

	return function update(newList) {
		var mayRemove = [];
		for(var i = 0; i < styles.length; i++) {
			var item = styles[i];
			var domStyle = stylesInDom[item.id];
			domStyle.refs--;
			mayRemove.push(domStyle);
		}
		if(newList) {
			var newStyles = listToStyles(newList);
			addStylesToDom(newStyles, options);
		}
		for(var i = 0; i < mayRemove.length; i++) {
			var domStyle = mayRemove[i];
			if(domStyle.refs === 0) {
				for(var j = 0; j < domStyle.parts.length; j++)
					domStyle.parts[j]();
				delete stylesInDom[domStyle.id];
			}
		}
	};
}

function addStylesToDom(styles, options) {
	for(var i = 0; i < styles.length; i++) {
		var item = styles[i];
		var domStyle = stylesInDom[item.id];
		if(domStyle) {
			domStyle.refs++;
			for(var j = 0; j < domStyle.parts.length; j++) {
				domStyle.parts[j](item.parts[j]);
			}
			for(; j < item.parts.length; j++) {
				domStyle.parts.push(addStyle(item.parts[j], options));
			}
		} else {
			var parts = [];
			for(var j = 0; j < item.parts.length; j++) {
				parts.push(addStyle(item.parts[j], options));
			}
			stylesInDom[item.id] = {id: item.id, refs: 1, parts: parts};
		}
	}
}

function listToStyles(list) {
	var styles = [];
	var newStyles = {};
	for(var i = 0; i < list.length; i++) {
		var item = list[i];
		var id = item[0];
		var css = item[1];
		var media = item[2];
		var sourceMap = item[3];
		var part = {css: css, media: media, sourceMap: sourceMap};
		if(!newStyles[id])
			styles.push(newStyles[id] = {id: id, parts: [part]});
		else
			newStyles[id].parts.push(part);
	}
	return styles;
}

function insertStyleElement(options, styleElement) {
	var head = getHeadElement();
	var lastStyleElementInsertedAtTop = styleElementsInsertedAtTop[styleElementsInsertedAtTop.length - 1];
	if (options.insertAt === "top") {
		if(!lastStyleElementInsertedAtTop) {
			head.insertBefore(styleElement, head.firstChild);
		} else if(lastStyleElementInsertedAtTop.nextSibling) {
			head.insertBefore(styleElement, lastStyleElementInsertedAtTop.nextSibling);
		} else {
			head.appendChild(styleElement);
		}
		styleElementsInsertedAtTop.push(styleElement);
	} else if (options.insertAt === "bottom") {
		head.appendChild(styleElement);
	} else {
		throw new Error("Invalid value for parameter 'insertAt'. Must be 'top' or 'bottom'.");
	}
}

function removeStyleElement(styleElement) {
	styleElement.parentNode.removeChild(styleElement);
	var idx = styleElementsInsertedAtTop.indexOf(styleElement);
	if(idx >= 0) {
		styleElementsInsertedAtTop.splice(idx, 1);
	}
}

function createStyleElement(options) {
	var styleElement = document.createElement("style");
	styleElement.type = "text/css";
	insertStyleElement(options, styleElement);
	return styleElement;
}

function createLinkElement(options) {
	var linkElement = document.createElement("link");
	linkElement.rel = "stylesheet";
	insertStyleElement(options, linkElement);
	return linkElement;
}

function addStyle(obj, options) {
	var styleElement, update, remove;

	if (options.singleton) {
		var styleIndex = singletonCounter++;
		styleElement = singletonElement || (singletonElement = createStyleElement(options));
		update = applyToSingletonTag.bind(null, styleElement, styleIndex, false);
		remove = applyToSingletonTag.bind(null, styleElement, styleIndex, true);
	} else if(obj.sourceMap &&
		typeof URL === "function" &&
		typeof URL.createObjectURL === "function" &&
		typeof URL.revokeObjectURL === "function" &&
		typeof Blob === "function" &&
		typeof btoa === "function") {
		styleElement = createLinkElement(options);
		update = updateLink.bind(null, styleElement);
		remove = function() {
			removeStyleElement(styleElement);
			if(styleElement.href)
				URL.revokeObjectURL(styleElement.href);
		};
	} else {
		styleElement = createStyleElement(options);
		update = applyToTag.bind(null, styleElement);
		remove = function() {
			removeStyleElement(styleElement);
		};
	}

	update(obj);

	return function updateStyle(newObj) {
		if(newObj) {
			if(newObj.css === obj.css && newObj.media === obj.media && newObj.sourceMap === obj.sourceMap)
				return;
			update(obj = newObj);
		} else {
			remove();
		}
	};
}

var replaceText = (function () {
	var textStore = [];

	return function (index, replacement) {
		textStore[index] = replacement;
		return textStore.filter(Boolean).join('\n');
	};
})();

function applyToSingletonTag(styleElement, index, remove, obj) {
	var css = remove ? "" : obj.css;

	if (styleElement.styleSheet) {
		styleElement.styleSheet.cssText = replaceText(index, css);
	} else {
		var cssNode = document.createTextNode(css);
		var childNodes = styleElement.childNodes;
		if (childNodes[index]) styleElement.removeChild(childNodes[index]);
		if (childNodes.length) {
			styleElement.insertBefore(cssNode, childNodes[index]);
		} else {
			styleElement.appendChild(cssNode);
		}
	}
}

function applyToTag(styleElement, obj) {
	var css = obj.css;
	var media = obj.media;

	if(media) {
		styleElement.setAttribute("media", media)
	}

	if(styleElement.styleSheet) {
		styleElement.styleSheet.cssText = css;
	} else {
		while(styleElement.firstChild) {
			styleElement.removeChild(styleElement.firstChild);
		}
		styleElement.appendChild(document.createTextNode(css));
	}
}

function updateLink(linkElement, obj) {
	var css = obj.css;
	var sourceMap = obj.sourceMap;

	if(sourceMap) {
		// http://stackoverflow.com/a/26603875
		css += "\n/*# sourceMappingURL=data:application/json;base64," + btoa(unescape(encodeURIComponent(JSON.stringify(sourceMap)))) + " */";
	}

	var blob = new Blob([css], { type: "text/css" });

	var oldSrc = linkElement.href;

	linkElement.href = URL.createObjectURL(blob);

	if(oldSrc)
		URL.revokeObjectURL(oldSrc);
}


/***/ }),
/* 11 */
/***/ (function(module, exports, __webpack_require__) {

var Component = __webpack_require__(1)(
  /* script */
  __webpack_require__(32),
  /* template */
  __webpack_require__(33),
  /* scopeId */
  null,
  /* cssModules */
  null
)
Component.options.__file = "C:\\OSPanel\\domains\\ohcasey.ru\\www\\resources\\assets\\js\\components\\paginator.vue"
if (Component.esModule && Object.keys(Component.esModule).some(function (key) {return key !== "default" && key !== "__esModule"})) {console.error("named exports are not supported in *.vue files.")}
if (Component.options.functional) {console.error("[vue-loader] paginator.vue: functional components are not supported with templates, they should use render functions.")}

/* hot reload */
if (false) {(function () {
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), false)
  if (!hotAPI.compatible) return
  module.hot.accept()
  if (!module.hot.data) {
    hotAPI.createRecord("data-v-e1321980", Component.options)
  } else {
    hotAPI.reload("data-v-e1321980", Component.options)
  }
})()}

module.exports = Component.exports


/***/ }),
/* 12 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


var _vuestore = __webpack_require__(5);

var _vuestore2 = _interopRequireDefault(_vuestore);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

var DataTableMixin = {
    data: function data() {
        return {
            vuestore: _vuestore2.default,
            page: {},
            rows: [],
            rowsRoute: '',
            deleteRoute: '',
            // offset: 4,
            search: '',
            active: null,
            activeOptions: [{ text: 'Все', value: null }, { text: 'Активные', value: 1 }, { text: 'Неактивные', value: 0 }],
            category: null,
            categoriesRoute: '',
            categoryOptions: [{ text: 'Все', value: null }],
            // map keys for select options value and text
            categoryValueKey: 'id',
            categoryTextKey: 'path',
            firstLoaded: true
        };
    },
    created: function created() {
        this.updateTable();
        // this.getCategories()
    },

    methods: {
        updateTable: function updateTable() {
            var _this = this;

            if (this.vuestore.loading && !this.firstLoaded) return;else this.vuestore.loading = true;
            this.firstLoaded = true;

            var settings = {
                method: 'get',
                path: this.rowsRoute,
                params: {
                    page: this.rows.current_page
                }
            };
            if (this.search) settings.params.search = this.search;
            if (this.active != null) settings.params.active = this.active;
            if (this.category != null) settings.params.category = this.category;
            this.vuestore.request(settings).then(function (response) {
                _this.rows = response.data.rows || response.data;
                _this.page = response.data.page;
                _this.vuestore.loading = false;
            }).catch(function (response) {
                console.error(response);
                _this.vuestore.loading = false;
            });
        },

        // changePage(page){
        //     this.rows.current_page = page
        //     this.updateTable()
        // },
        searchRows: function searchRows() {
            this.updateTable();
        },
        getCategories: function getCategories() {
            var _this2 = this;

            if (this.categoryOptions.length > 1) return;else this.vuestore.loading = true;
            var settings = {
                method: 'get',
                path: this.categoriesRoute
            };
            this.vuestore.request(settings).then(function (response) {
                // this.categoryOptions = this.categoryOptions.concat(response.data)
                response.data.forEach(function (item) {
                    _this2.categoryOptions.push({ value: item[_this2.categoryValueKey], text: item[_this2.categoryTextKey] });
                });
                _this2.vuestore.loading = false;
            }).catch(function (response) {
                console.error(response);
                _this2.vuestore.loading = false;
            });
        },
        filterCategory: function filterCategory() {
            this.updateTable();
        },
        destroy: function destroy(params) {
            var _this3 = this;

            var del = document.getElementById('delete-' + params.id);
            var choice = confirm(del.getAttribute('data-confirm'));
            if (!choice) return;
            if (this.vuestore.loading) return;else this.vuestore.loading = true;
            var row = del.closest('tr');
            row.classList.add('danger');
            var settings = {
                method: 'delete',
                path: lroutes.api.admin.ecommerce[params.key].destroy.replace(/{.*}/, params.id)
            };
            this.vuestore.request(settings).then(function (response) {
                _this3.vuestore.loading = false;
                row.classList.remove('danger');
                _this3.updateTable();
            }).catch(function (response) {
                console.error(response);
                _this3.vuestore.loading = false;
            });
        }
    }
};

module.exports = DataTableMixin;

/***/ }),
/* 13 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

var _axios = __webpack_require__(6);

var _axios2 = _interopRequireDefault(_axios);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

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
            var _this = this;

            return new Promise(function (resolve, reject) {
                _axios2.default[requestType](url, _this.data()).then(function (response) {
                    if (response.data) {
                        _this.onSuccess(response.data);
                    }

                    resolve(response);
                }).catch(function (error) {
                    if (error.response) {
                        _this.onFail(error.response.data);
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

module.exports = Form;

/***/ }),
/* 14 */
/***/ (function(module, exports, __webpack_require__) {

var Component = __webpack_require__(1)(
  /* script */
  __webpack_require__(17),
  /* template */
  __webpack_require__(25),
  /* scopeId */
  null,
  /* cssModules */
  null
)
Component.options.__file = "C:\\OSPanel\\domains\\ohcasey.ru\\www\\resources\\assets\\js\\components\\quill-editor.vue"
if (Component.esModule && Object.keys(Component.esModule).some(function (key) {return key !== "default" && key !== "__esModule"})) {console.error("named exports are not supported in *.vue files.")}
if (Component.options.functional) {console.error("[vue-loader] quill-editor.vue: functional components are not supported with templates, they should use render functions.")}

/* hot reload */
if (false) {(function () {
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), false)
  if (!hotAPI.compatible) return
  module.hot.accept()
  if (!module.hot.data) {
    hotAPI.createRecord("data-v-1a284cd6", Component.options)
  } else {
    hotAPI.reload("data-v-1a284cd6", Component.options)
  }
})()}

module.exports = Component.exports


/***/ }),
/* 15 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


Object.defineProperty(exports, "__esModule", {
    value: true
});

var _loader = __webpack_require__(23);

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
/* 16 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


Object.defineProperty(exports, "__esModule", {
    value: true
});

var _vuestore = __webpack_require__(5);

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
/* 17 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


Object.defineProperty(exports, "__esModule", {
    value: true
});

__webpack_require__(22);

__webpack_require__(21);

var _quill = __webpack_require__(29);

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
/* 18 */
/***/ (function(module, exports, __webpack_require__) {

exports = module.exports = __webpack_require__(3)();
// imports


// module
exports.push([module.i, "/*!\n * Quill Editor v1.2.6\n * https://quilljs.com/\n * Copyright (c) 2014, Jason Chen\n * Copyright (c) 2013, salesforce.com\n */\n.ql-container {\n  box-sizing: border-box;\n  font-family: Helvetica, Arial, sans-serif;\n  font-size: 13px;\n  height: 100%;\n  margin: 0px;\n  position: relative;\n}\n.ql-container.ql-disabled .ql-tooltip {\n  visibility: hidden;\n}\n.ql-container.ql-disabled .ql-editor ul[data-checked] > li::before {\n  pointer-events: none;\n}\n.ql-clipboard {\n  left: -100000px;\n  height: 1px;\n  overflow-y: hidden;\n  position: absolute;\n  top: 50%;\n}\n.ql-clipboard p {\n  margin: 0;\n  padding: 0;\n}\n.ql-editor {\n  box-sizing: border-box;\n  cursor: text;\n  line-height: 1.42;\n  height: 100%;\n  outline: none;\n  overflow-y: auto;\n  padding: 12px 15px;\n  tab-size: 4;\n  -moz-tab-size: 4;\n  text-align: left;\n  white-space: pre-wrap;\n  word-wrap: break-word;\n}\n.ql-editor p,\n.ql-editor ol,\n.ql-editor ul,\n.ql-editor pre,\n.ql-editor blockquote,\n.ql-editor h1,\n.ql-editor h2,\n.ql-editor h3,\n.ql-editor h4,\n.ql-editor h5,\n.ql-editor h6 {\n  margin: 0;\n  padding: 0;\n  counter-reset: list-1 list-2 list-3 list-4 list-5 list-6 list-7 list-8 list-9;\n}\n.ql-editor ol,\n.ql-editor ul {\n  padding-left: 1.5em;\n}\n.ql-editor ol > li,\n.ql-editor ul > li {\n  list-style-type: none;\n}\n.ql-editor ul > li::before {\n  content: '\\2022';\n}\n.ql-editor ul[data-checked=true],\n.ql-editor ul[data-checked=false] {\n  pointer-events: none;\n}\n.ql-editor ul[data-checked=true] > li *,\n.ql-editor ul[data-checked=false] > li * {\n  pointer-events: all;\n}\n.ql-editor ul[data-checked=true] > li::before,\n.ql-editor ul[data-checked=false] > li::before {\n  color: #777;\n  cursor: pointer;\n  pointer-events: all;\n}\n.ql-editor ul[data-checked=true] > li::before {\n  content: '\\2611';\n}\n.ql-editor ul[data-checked=false] > li::before {\n  content: '\\2610';\n}\n.ql-editor li::before {\n  display: inline-block;\n  white-space: nowrap;\n  width: 1.2em;\n  text-align: right;\n  margin-right: 0.3em;\n  margin-left: -1.5em;\n}\n.ql-editor li.ql-direction-rtl::before {\n  text-align: left;\n  margin-left: 0.3em;\n}\n.ql-editor ol li,\n.ql-editor ul li {\n  padding-left: 1.5em;\n}\n.ql-editor ol li {\n  counter-reset: list-1 list-2 list-3 list-4 list-5 list-6 list-7 list-8 list-9;\n  counter-increment: list-num;\n}\n.ql-editor ol li:before {\n  content: counter(list-num, decimal) '. ';\n}\n.ql-editor ol li.ql-indent-1 {\n  counter-increment: list-1;\n}\n.ql-editor ol li.ql-indent-1:before {\n  content: counter(list-1, lower-alpha) '. ';\n}\n.ql-editor ol li.ql-indent-1 {\n  counter-reset: list-2 list-3 list-4 list-5 list-6 list-7 list-8 list-9;\n}\n.ql-editor ol li.ql-indent-2 {\n  counter-increment: list-2;\n}\n.ql-editor ol li.ql-indent-2:before {\n  content: counter(list-2, lower-roman) '. ';\n}\n.ql-editor ol li.ql-indent-2 {\n  counter-reset: list-3 list-4 list-5 list-6 list-7 list-8 list-9;\n}\n.ql-editor ol li.ql-indent-3 {\n  counter-increment: list-3;\n}\n.ql-editor ol li.ql-indent-3:before {\n  content: counter(list-3, decimal) '. ';\n}\n.ql-editor ol li.ql-indent-3 {\n  counter-reset: list-4 list-5 list-6 list-7 list-8 list-9;\n}\n.ql-editor ol li.ql-indent-4 {\n  counter-increment: list-4;\n}\n.ql-editor ol li.ql-indent-4:before {\n  content: counter(list-4, lower-alpha) '. ';\n}\n.ql-editor ol li.ql-indent-4 {\n  counter-reset: list-5 list-6 list-7 list-8 list-9;\n}\n.ql-editor ol li.ql-indent-5 {\n  counter-increment: list-5;\n}\n.ql-editor ol li.ql-indent-5:before {\n  content: counter(list-5, lower-roman) '. ';\n}\n.ql-editor ol li.ql-indent-5 {\n  counter-reset: list-6 list-7 list-8 list-9;\n}\n.ql-editor ol li.ql-indent-6 {\n  counter-increment: list-6;\n}\n.ql-editor ol li.ql-indent-6:before {\n  content: counter(list-6, decimal) '. ';\n}\n.ql-editor ol li.ql-indent-6 {\n  counter-reset: list-7 list-8 list-9;\n}\n.ql-editor ol li.ql-indent-7 {\n  counter-increment: list-7;\n}\n.ql-editor ol li.ql-indent-7:before {\n  content: counter(list-7, lower-alpha) '. ';\n}\n.ql-editor ol li.ql-indent-7 {\n  counter-reset: list-8 list-9;\n}\n.ql-editor ol li.ql-indent-8 {\n  counter-increment: list-8;\n}\n.ql-editor ol li.ql-indent-8:before {\n  content: counter(list-8, lower-roman) '. ';\n}\n.ql-editor ol li.ql-indent-8 {\n  counter-reset: list-9;\n}\n.ql-editor ol li.ql-indent-9 {\n  counter-increment: list-9;\n}\n.ql-editor ol li.ql-indent-9:before {\n  content: counter(list-9, decimal) '. ';\n}\n.ql-editor .ql-indent-1:not(.ql-direction-rtl) {\n  padding-left: 3em;\n}\n.ql-editor li.ql-indent-1:not(.ql-direction-rtl) {\n  padding-left: 4.5em;\n}\n.ql-editor .ql-indent-1.ql-direction-rtl.ql-align-right {\n  padding-right: 3em;\n}\n.ql-editor li.ql-indent-1.ql-direction-rtl.ql-align-right {\n  padding-right: 4.5em;\n}\n.ql-editor .ql-indent-2:not(.ql-direction-rtl) {\n  padding-left: 6em;\n}\n.ql-editor li.ql-indent-2:not(.ql-direction-rtl) {\n  padding-left: 7.5em;\n}\n.ql-editor .ql-indent-2.ql-direction-rtl.ql-align-right {\n  padding-right: 6em;\n}\n.ql-editor li.ql-indent-2.ql-direction-rtl.ql-align-right {\n  padding-right: 7.5em;\n}\n.ql-editor .ql-indent-3:not(.ql-direction-rtl) {\n  padding-left: 9em;\n}\n.ql-editor li.ql-indent-3:not(.ql-direction-rtl) {\n  padding-left: 10.5em;\n}\n.ql-editor .ql-indent-3.ql-direction-rtl.ql-align-right {\n  padding-right: 9em;\n}\n.ql-editor li.ql-indent-3.ql-direction-rtl.ql-align-right {\n  padding-right: 10.5em;\n}\n.ql-editor .ql-indent-4:not(.ql-direction-rtl) {\n  padding-left: 12em;\n}\n.ql-editor li.ql-indent-4:not(.ql-direction-rtl) {\n  padding-left: 13.5em;\n}\n.ql-editor .ql-indent-4.ql-direction-rtl.ql-align-right {\n  padding-right: 12em;\n}\n.ql-editor li.ql-indent-4.ql-direction-rtl.ql-align-right {\n  padding-right: 13.5em;\n}\n.ql-editor .ql-indent-5:not(.ql-direction-rtl) {\n  padding-left: 15em;\n}\n.ql-editor li.ql-indent-5:not(.ql-direction-rtl) {\n  padding-left: 16.5em;\n}\n.ql-editor .ql-indent-5.ql-direction-rtl.ql-align-right {\n  padding-right: 15em;\n}\n.ql-editor li.ql-indent-5.ql-direction-rtl.ql-align-right {\n  padding-right: 16.5em;\n}\n.ql-editor .ql-indent-6:not(.ql-direction-rtl) {\n  padding-left: 18em;\n}\n.ql-editor li.ql-indent-6:not(.ql-direction-rtl) {\n  padding-left: 19.5em;\n}\n.ql-editor .ql-indent-6.ql-direction-rtl.ql-align-right {\n  padding-right: 18em;\n}\n.ql-editor li.ql-indent-6.ql-direction-rtl.ql-align-right {\n  padding-right: 19.5em;\n}\n.ql-editor .ql-indent-7:not(.ql-direction-rtl) {\n  padding-left: 21em;\n}\n.ql-editor li.ql-indent-7:not(.ql-direction-rtl) {\n  padding-left: 22.5em;\n}\n.ql-editor .ql-indent-7.ql-direction-rtl.ql-align-right {\n  padding-right: 21em;\n}\n.ql-editor li.ql-indent-7.ql-direction-rtl.ql-align-right {\n  padding-right: 22.5em;\n}\n.ql-editor .ql-indent-8:not(.ql-direction-rtl) {\n  padding-left: 24em;\n}\n.ql-editor li.ql-indent-8:not(.ql-direction-rtl) {\n  padding-left: 25.5em;\n}\n.ql-editor .ql-indent-8.ql-direction-rtl.ql-align-right {\n  padding-right: 24em;\n}\n.ql-editor li.ql-indent-8.ql-direction-rtl.ql-align-right {\n  padding-right: 25.5em;\n}\n.ql-editor .ql-indent-9:not(.ql-direction-rtl) {\n  padding-left: 27em;\n}\n.ql-editor li.ql-indent-9:not(.ql-direction-rtl) {\n  padding-left: 28.5em;\n}\n.ql-editor .ql-indent-9.ql-direction-rtl.ql-align-right {\n  padding-right: 27em;\n}\n.ql-editor li.ql-indent-9.ql-direction-rtl.ql-align-right {\n  padding-right: 28.5em;\n}\n.ql-editor .ql-video {\n  display: block;\n  max-width: 100%;\n}\n.ql-editor .ql-video.ql-align-center {\n  margin: 0 auto;\n}\n.ql-editor .ql-video.ql-align-right {\n  margin: 0 0 0 auto;\n}\n.ql-editor .ql-bg-black {\n  background-color: #000;\n}\n.ql-editor .ql-bg-red {\n  background-color: #e60000;\n}\n.ql-editor .ql-bg-orange {\n  background-color: #f90;\n}\n.ql-editor .ql-bg-yellow {\n  background-color: #ff0;\n}\n.ql-editor .ql-bg-green {\n  background-color: #008a00;\n}\n.ql-editor .ql-bg-blue {\n  background-color: #06c;\n}\n.ql-editor .ql-bg-purple {\n  background-color: #93f;\n}\n.ql-editor .ql-color-white {\n  color: #fff;\n}\n.ql-editor .ql-color-red {\n  color: #e60000;\n}\n.ql-editor .ql-color-orange {\n  color: #f90;\n}\n.ql-editor .ql-color-yellow {\n  color: #ff0;\n}\n.ql-editor .ql-color-green {\n  color: #008a00;\n}\n.ql-editor .ql-color-blue {\n  color: #06c;\n}\n.ql-editor .ql-color-purple {\n  color: #93f;\n}\n.ql-editor .ql-font-serif {\n  font-family: Georgia, Times New Roman, serif;\n}\n.ql-editor .ql-font-monospace {\n  font-family: Monaco, Courier New, monospace;\n}\n.ql-editor .ql-size-small {\n  font-size: 0.75em;\n}\n.ql-editor .ql-size-large {\n  font-size: 1.5em;\n}\n.ql-editor .ql-size-huge {\n  font-size: 2.5em;\n}\n.ql-editor .ql-direction-rtl {\n  direction: rtl;\n  text-align: inherit;\n}\n.ql-editor .ql-align-center {\n  text-align: center;\n}\n.ql-editor .ql-align-justify {\n  text-align: justify;\n}\n.ql-editor .ql-align-right {\n  text-align: right;\n}\n.ql-editor.ql-blank::before {\n  color: rgba(0,0,0,0.6);\n  content: attr(data-placeholder);\n  font-style: italic;\n  pointer-events: none;\n  position: absolute;\n}\n", ""]);

// exports


/***/ }),
/* 19 */
/***/ (function(module, exports, __webpack_require__) {

exports = module.exports = __webpack_require__(3)();
// imports


// module
exports.push([module.i, "/*!\n * Quill Editor v1.2.6\n * https://quilljs.com/\n * Copyright (c) 2014, Jason Chen\n * Copyright (c) 2013, salesforce.com\n */\n.ql-container {\n  box-sizing: border-box;\n  font-family: Helvetica, Arial, sans-serif;\n  font-size: 13px;\n  height: 100%;\n  margin: 0px;\n  position: relative;\n}\n.ql-container.ql-disabled .ql-tooltip {\n  visibility: hidden;\n}\n.ql-container.ql-disabled .ql-editor ul[data-checked] > li::before {\n  pointer-events: none;\n}\n.ql-clipboard {\n  left: -100000px;\n  height: 1px;\n  overflow-y: hidden;\n  position: absolute;\n  top: 50%;\n}\n.ql-clipboard p {\n  margin: 0;\n  padding: 0;\n}\n.ql-editor {\n  box-sizing: border-box;\n  cursor: text;\n  line-height: 1.42;\n  height: 100%;\n  outline: none;\n  overflow-y: auto;\n  padding: 12px 15px;\n  tab-size: 4;\n  -moz-tab-size: 4;\n  text-align: left;\n  white-space: pre-wrap;\n  word-wrap: break-word;\n}\n.ql-editor p,\n.ql-editor ol,\n.ql-editor ul,\n.ql-editor pre,\n.ql-editor blockquote,\n.ql-editor h1,\n.ql-editor h2,\n.ql-editor h3,\n.ql-editor h4,\n.ql-editor h5,\n.ql-editor h6 {\n  margin: 0;\n  padding: 0;\n  counter-reset: list-1 list-2 list-3 list-4 list-5 list-6 list-7 list-8 list-9;\n}\n.ql-editor ol,\n.ql-editor ul {\n  padding-left: 1.5em;\n}\n.ql-editor ol > li,\n.ql-editor ul > li {\n  list-style-type: none;\n}\n.ql-editor ul > li::before {\n  content: '\\2022';\n}\n.ql-editor ul[data-checked=true],\n.ql-editor ul[data-checked=false] {\n  pointer-events: none;\n}\n.ql-editor ul[data-checked=true] > li *,\n.ql-editor ul[data-checked=false] > li * {\n  pointer-events: all;\n}\n.ql-editor ul[data-checked=true] > li::before,\n.ql-editor ul[data-checked=false] > li::before {\n  color: #777;\n  cursor: pointer;\n  pointer-events: all;\n}\n.ql-editor ul[data-checked=true] > li::before {\n  content: '\\2611';\n}\n.ql-editor ul[data-checked=false] > li::before {\n  content: '\\2610';\n}\n.ql-editor li::before {\n  display: inline-block;\n  white-space: nowrap;\n  width: 1.2em;\n  text-align: right;\n  margin-right: 0.3em;\n  margin-left: -1.5em;\n}\n.ql-editor li.ql-direction-rtl::before {\n  text-align: left;\n  margin-left: 0.3em;\n}\n.ql-editor ol li,\n.ql-editor ul li {\n  padding-left: 1.5em;\n}\n.ql-editor ol li {\n  counter-reset: list-1 list-2 list-3 list-4 list-5 list-6 list-7 list-8 list-9;\n  counter-increment: list-num;\n}\n.ql-editor ol li:before {\n  content: counter(list-num, decimal) '. ';\n}\n.ql-editor ol li.ql-indent-1 {\n  counter-increment: list-1;\n}\n.ql-editor ol li.ql-indent-1:before {\n  content: counter(list-1, lower-alpha) '. ';\n}\n.ql-editor ol li.ql-indent-1 {\n  counter-reset: list-2 list-3 list-4 list-5 list-6 list-7 list-8 list-9;\n}\n.ql-editor ol li.ql-indent-2 {\n  counter-increment: list-2;\n}\n.ql-editor ol li.ql-indent-2:before {\n  content: counter(list-2, lower-roman) '. ';\n}\n.ql-editor ol li.ql-indent-2 {\n  counter-reset: list-3 list-4 list-5 list-6 list-7 list-8 list-9;\n}\n.ql-editor ol li.ql-indent-3 {\n  counter-increment: list-3;\n}\n.ql-editor ol li.ql-indent-3:before {\n  content: counter(list-3, decimal) '. ';\n}\n.ql-editor ol li.ql-indent-3 {\n  counter-reset: list-4 list-5 list-6 list-7 list-8 list-9;\n}\n.ql-editor ol li.ql-indent-4 {\n  counter-increment: list-4;\n}\n.ql-editor ol li.ql-indent-4:before {\n  content: counter(list-4, lower-alpha) '. ';\n}\n.ql-editor ol li.ql-indent-4 {\n  counter-reset: list-5 list-6 list-7 list-8 list-9;\n}\n.ql-editor ol li.ql-indent-5 {\n  counter-increment: list-5;\n}\n.ql-editor ol li.ql-indent-5:before {\n  content: counter(list-5, lower-roman) '. ';\n}\n.ql-editor ol li.ql-indent-5 {\n  counter-reset: list-6 list-7 list-8 list-9;\n}\n.ql-editor ol li.ql-indent-6 {\n  counter-increment: list-6;\n}\n.ql-editor ol li.ql-indent-6:before {\n  content: counter(list-6, decimal) '. ';\n}\n.ql-editor ol li.ql-indent-6 {\n  counter-reset: list-7 list-8 list-9;\n}\n.ql-editor ol li.ql-indent-7 {\n  counter-increment: list-7;\n}\n.ql-editor ol li.ql-indent-7:before {\n  content: counter(list-7, lower-alpha) '. ';\n}\n.ql-editor ol li.ql-indent-7 {\n  counter-reset: list-8 list-9;\n}\n.ql-editor ol li.ql-indent-8 {\n  counter-increment: list-8;\n}\n.ql-editor ol li.ql-indent-8:before {\n  content: counter(list-8, lower-roman) '. ';\n}\n.ql-editor ol li.ql-indent-8 {\n  counter-reset: list-9;\n}\n.ql-editor ol li.ql-indent-9 {\n  counter-increment: list-9;\n}\n.ql-editor ol li.ql-indent-9:before {\n  content: counter(list-9, decimal) '. ';\n}\n.ql-editor .ql-indent-1:not(.ql-direction-rtl) {\n  padding-left: 3em;\n}\n.ql-editor li.ql-indent-1:not(.ql-direction-rtl) {\n  padding-left: 4.5em;\n}\n.ql-editor .ql-indent-1.ql-direction-rtl.ql-align-right {\n  padding-right: 3em;\n}\n.ql-editor li.ql-indent-1.ql-direction-rtl.ql-align-right {\n  padding-right: 4.5em;\n}\n.ql-editor .ql-indent-2:not(.ql-direction-rtl) {\n  padding-left: 6em;\n}\n.ql-editor li.ql-indent-2:not(.ql-direction-rtl) {\n  padding-left: 7.5em;\n}\n.ql-editor .ql-indent-2.ql-direction-rtl.ql-align-right {\n  padding-right: 6em;\n}\n.ql-editor li.ql-indent-2.ql-direction-rtl.ql-align-right {\n  padding-right: 7.5em;\n}\n.ql-editor .ql-indent-3:not(.ql-direction-rtl) {\n  padding-left: 9em;\n}\n.ql-editor li.ql-indent-3:not(.ql-direction-rtl) {\n  padding-left: 10.5em;\n}\n.ql-editor .ql-indent-3.ql-direction-rtl.ql-align-right {\n  padding-right: 9em;\n}\n.ql-editor li.ql-indent-3.ql-direction-rtl.ql-align-right {\n  padding-right: 10.5em;\n}\n.ql-editor .ql-indent-4:not(.ql-direction-rtl) {\n  padding-left: 12em;\n}\n.ql-editor li.ql-indent-4:not(.ql-direction-rtl) {\n  padding-left: 13.5em;\n}\n.ql-editor .ql-indent-4.ql-direction-rtl.ql-align-right {\n  padding-right: 12em;\n}\n.ql-editor li.ql-indent-4.ql-direction-rtl.ql-align-right {\n  padding-right: 13.5em;\n}\n.ql-editor .ql-indent-5:not(.ql-direction-rtl) {\n  padding-left: 15em;\n}\n.ql-editor li.ql-indent-5:not(.ql-direction-rtl) {\n  padding-left: 16.5em;\n}\n.ql-editor .ql-indent-5.ql-direction-rtl.ql-align-right {\n  padding-right: 15em;\n}\n.ql-editor li.ql-indent-5.ql-direction-rtl.ql-align-right {\n  padding-right: 16.5em;\n}\n.ql-editor .ql-indent-6:not(.ql-direction-rtl) {\n  padding-left: 18em;\n}\n.ql-editor li.ql-indent-6:not(.ql-direction-rtl) {\n  padding-left: 19.5em;\n}\n.ql-editor .ql-indent-6.ql-direction-rtl.ql-align-right {\n  padding-right: 18em;\n}\n.ql-editor li.ql-indent-6.ql-direction-rtl.ql-align-right {\n  padding-right: 19.5em;\n}\n.ql-editor .ql-indent-7:not(.ql-direction-rtl) {\n  padding-left: 21em;\n}\n.ql-editor li.ql-indent-7:not(.ql-direction-rtl) {\n  padding-left: 22.5em;\n}\n.ql-editor .ql-indent-7.ql-direction-rtl.ql-align-right {\n  padding-right: 21em;\n}\n.ql-editor li.ql-indent-7.ql-direction-rtl.ql-align-right {\n  padding-right: 22.5em;\n}\n.ql-editor .ql-indent-8:not(.ql-direction-rtl) {\n  padding-left: 24em;\n}\n.ql-editor li.ql-indent-8:not(.ql-direction-rtl) {\n  padding-left: 25.5em;\n}\n.ql-editor .ql-indent-8.ql-direction-rtl.ql-align-right {\n  padding-right: 24em;\n}\n.ql-editor li.ql-indent-8.ql-direction-rtl.ql-align-right {\n  padding-right: 25.5em;\n}\n.ql-editor .ql-indent-9:not(.ql-direction-rtl) {\n  padding-left: 27em;\n}\n.ql-editor li.ql-indent-9:not(.ql-direction-rtl) {\n  padding-left: 28.5em;\n}\n.ql-editor .ql-indent-9.ql-direction-rtl.ql-align-right {\n  padding-right: 27em;\n}\n.ql-editor li.ql-indent-9.ql-direction-rtl.ql-align-right {\n  padding-right: 28.5em;\n}\n.ql-editor .ql-video {\n  display: block;\n  max-width: 100%;\n}\n.ql-editor .ql-video.ql-align-center {\n  margin: 0 auto;\n}\n.ql-editor .ql-video.ql-align-right {\n  margin: 0 0 0 auto;\n}\n.ql-editor .ql-bg-black {\n  background-color: #000;\n}\n.ql-editor .ql-bg-red {\n  background-color: #e60000;\n}\n.ql-editor .ql-bg-orange {\n  background-color: #f90;\n}\n.ql-editor .ql-bg-yellow {\n  background-color: #ff0;\n}\n.ql-editor .ql-bg-green {\n  background-color: #008a00;\n}\n.ql-editor .ql-bg-blue {\n  background-color: #06c;\n}\n.ql-editor .ql-bg-purple {\n  background-color: #93f;\n}\n.ql-editor .ql-color-white {\n  color: #fff;\n}\n.ql-editor .ql-color-red {\n  color: #e60000;\n}\n.ql-editor .ql-color-orange {\n  color: #f90;\n}\n.ql-editor .ql-color-yellow {\n  color: #ff0;\n}\n.ql-editor .ql-color-green {\n  color: #008a00;\n}\n.ql-editor .ql-color-blue {\n  color: #06c;\n}\n.ql-editor .ql-color-purple {\n  color: #93f;\n}\n.ql-editor .ql-font-serif {\n  font-family: Georgia, Times New Roman, serif;\n}\n.ql-editor .ql-font-monospace {\n  font-family: Monaco, Courier New, monospace;\n}\n.ql-editor .ql-size-small {\n  font-size: 0.75em;\n}\n.ql-editor .ql-size-large {\n  font-size: 1.5em;\n}\n.ql-editor .ql-size-huge {\n  font-size: 2.5em;\n}\n.ql-editor .ql-direction-rtl {\n  direction: rtl;\n  text-align: inherit;\n}\n.ql-editor .ql-align-center {\n  text-align: center;\n}\n.ql-editor .ql-align-justify {\n  text-align: justify;\n}\n.ql-editor .ql-align-right {\n  text-align: right;\n}\n.ql-editor.ql-blank::before {\n  color: rgba(0,0,0,0.6);\n  content: attr(data-placeholder);\n  font-style: italic;\n  pointer-events: none;\n  position: absolute;\n}\n.ql-snow.ql-toolbar:after,\n.ql-snow .ql-toolbar:after {\n  clear: both;\n  content: '';\n  display: table;\n}\n.ql-snow.ql-toolbar button,\n.ql-snow .ql-toolbar button {\n  background: none;\n  border: none;\n  cursor: pointer;\n  display: inline-block;\n  float: left;\n  height: 24px;\n  padding: 3px 5px;\n  width: 28px;\n}\n.ql-snow.ql-toolbar button svg,\n.ql-snow .ql-toolbar button svg {\n  float: left;\n  height: 100%;\n}\n.ql-snow.ql-toolbar button:active:hover,\n.ql-snow .ql-toolbar button:active:hover {\n  outline: none;\n}\n.ql-snow.ql-toolbar input.ql-image[type=file],\n.ql-snow .ql-toolbar input.ql-image[type=file] {\n  display: none;\n}\n.ql-snow.ql-toolbar button:hover,\n.ql-snow .ql-toolbar button:hover,\n.ql-snow.ql-toolbar button.ql-active,\n.ql-snow .ql-toolbar button.ql-active,\n.ql-snow.ql-toolbar .ql-picker-label:hover,\n.ql-snow .ql-toolbar .ql-picker-label:hover,\n.ql-snow.ql-toolbar .ql-picker-label.ql-active,\n.ql-snow .ql-toolbar .ql-picker-label.ql-active,\n.ql-snow.ql-toolbar .ql-picker-item:hover,\n.ql-snow .ql-toolbar .ql-picker-item:hover,\n.ql-snow.ql-toolbar .ql-picker-item.ql-selected,\n.ql-snow .ql-toolbar .ql-picker-item.ql-selected {\n  color: #06c;\n}\n.ql-snow.ql-toolbar button:hover .ql-fill,\n.ql-snow .ql-toolbar button:hover .ql-fill,\n.ql-snow.ql-toolbar button.ql-active .ql-fill,\n.ql-snow .ql-toolbar button.ql-active .ql-fill,\n.ql-snow.ql-toolbar .ql-picker-label:hover .ql-fill,\n.ql-snow .ql-toolbar .ql-picker-label:hover .ql-fill,\n.ql-snow.ql-toolbar .ql-picker-label.ql-active .ql-fill,\n.ql-snow .ql-toolbar .ql-picker-label.ql-active .ql-fill,\n.ql-snow.ql-toolbar .ql-picker-item:hover .ql-fill,\n.ql-snow .ql-toolbar .ql-picker-item:hover .ql-fill,\n.ql-snow.ql-toolbar .ql-picker-item.ql-selected .ql-fill,\n.ql-snow .ql-toolbar .ql-picker-item.ql-selected .ql-fill,\n.ql-snow.ql-toolbar button:hover .ql-stroke.ql-fill,\n.ql-snow .ql-toolbar button:hover .ql-stroke.ql-fill,\n.ql-snow.ql-toolbar button.ql-active .ql-stroke.ql-fill,\n.ql-snow .ql-toolbar button.ql-active .ql-stroke.ql-fill,\n.ql-snow.ql-toolbar .ql-picker-label:hover .ql-stroke.ql-fill,\n.ql-snow .ql-toolbar .ql-picker-label:hover .ql-stroke.ql-fill,\n.ql-snow.ql-toolbar .ql-picker-label.ql-active .ql-stroke.ql-fill,\n.ql-snow .ql-toolbar .ql-picker-label.ql-active .ql-stroke.ql-fill,\n.ql-snow.ql-toolbar .ql-picker-item:hover .ql-stroke.ql-fill,\n.ql-snow .ql-toolbar .ql-picker-item:hover .ql-stroke.ql-fill,\n.ql-snow.ql-toolbar .ql-picker-item.ql-selected .ql-stroke.ql-fill,\n.ql-snow .ql-toolbar .ql-picker-item.ql-selected .ql-stroke.ql-fill {\n  fill: #06c;\n}\n.ql-snow.ql-toolbar button:hover .ql-stroke,\n.ql-snow .ql-toolbar button:hover .ql-stroke,\n.ql-snow.ql-toolbar button.ql-active .ql-stroke,\n.ql-snow .ql-toolbar button.ql-active .ql-stroke,\n.ql-snow.ql-toolbar .ql-picker-label:hover .ql-stroke,\n.ql-snow .ql-toolbar .ql-picker-label:hover .ql-stroke,\n.ql-snow.ql-toolbar .ql-picker-label.ql-active .ql-stroke,\n.ql-snow .ql-toolbar .ql-picker-label.ql-active .ql-stroke,\n.ql-snow.ql-toolbar .ql-picker-item:hover .ql-stroke,\n.ql-snow .ql-toolbar .ql-picker-item:hover .ql-stroke,\n.ql-snow.ql-toolbar .ql-picker-item.ql-selected .ql-stroke,\n.ql-snow .ql-toolbar .ql-picker-item.ql-selected .ql-stroke,\n.ql-snow.ql-toolbar button:hover .ql-stroke-miter,\n.ql-snow .ql-toolbar button:hover .ql-stroke-miter,\n.ql-snow.ql-toolbar button.ql-active .ql-stroke-miter,\n.ql-snow .ql-toolbar button.ql-active .ql-stroke-miter,\n.ql-snow.ql-toolbar .ql-picker-label:hover .ql-stroke-miter,\n.ql-snow .ql-toolbar .ql-picker-label:hover .ql-stroke-miter,\n.ql-snow.ql-toolbar .ql-picker-label.ql-active .ql-stroke-miter,\n.ql-snow .ql-toolbar .ql-picker-label.ql-active .ql-stroke-miter,\n.ql-snow.ql-toolbar .ql-picker-item:hover .ql-stroke-miter,\n.ql-snow .ql-toolbar .ql-picker-item:hover .ql-stroke-miter,\n.ql-snow.ql-toolbar .ql-picker-item.ql-selected .ql-stroke-miter,\n.ql-snow .ql-toolbar .ql-picker-item.ql-selected .ql-stroke-miter {\n  stroke: #06c;\n}\n@media (pointer: coarse) {\n  .ql-snow.ql-toolbar button:hover:not(.ql-active),\n  .ql-snow .ql-toolbar button:hover:not(.ql-active) {\n    color: #444;\n  }\n  .ql-snow.ql-toolbar button:hover:not(.ql-active) .ql-fill,\n  .ql-snow .ql-toolbar button:hover:not(.ql-active) .ql-fill,\n  .ql-snow.ql-toolbar button:hover:not(.ql-active) .ql-stroke.ql-fill,\n  .ql-snow .ql-toolbar button:hover:not(.ql-active) .ql-stroke.ql-fill {\n    fill: #444;\n  }\n  .ql-snow.ql-toolbar button:hover:not(.ql-active) .ql-stroke,\n  .ql-snow .ql-toolbar button:hover:not(.ql-active) .ql-stroke,\n  .ql-snow.ql-toolbar button:hover:not(.ql-active) .ql-stroke-miter,\n  .ql-snow .ql-toolbar button:hover:not(.ql-active) .ql-stroke-miter {\n    stroke: #444;\n  }\n}\n.ql-snow {\n  box-sizing: border-box;\n}\n.ql-snow * {\n  box-sizing: border-box;\n}\n.ql-snow .ql-hidden {\n  display: none;\n}\n.ql-snow .ql-out-bottom,\n.ql-snow .ql-out-top {\n  visibility: hidden;\n}\n.ql-snow .ql-tooltip {\n  position: absolute;\n  transform: translateY(10px);\n}\n.ql-snow .ql-tooltip a {\n  cursor: pointer;\n  text-decoration: none;\n}\n.ql-snow .ql-tooltip.ql-flip {\n  transform: translateY(-10px);\n}\n.ql-snow .ql-formats {\n  display: inline-block;\n  vertical-align: middle;\n}\n.ql-snow .ql-formats:after {\n  clear: both;\n  content: '';\n  display: table;\n}\n.ql-snow .ql-stroke {\n  fill: none;\n  stroke: #444;\n  stroke-linecap: round;\n  stroke-linejoin: round;\n  stroke-width: 2;\n}\n.ql-snow .ql-stroke-miter {\n  fill: none;\n  stroke: #444;\n  stroke-miterlimit: 10;\n  stroke-width: 2;\n}\n.ql-snow .ql-fill,\n.ql-snow .ql-stroke.ql-fill {\n  fill: #444;\n}\n.ql-snow .ql-empty {\n  fill: none;\n}\n.ql-snow .ql-even {\n  fill-rule: evenodd;\n}\n.ql-snow .ql-thin,\n.ql-snow .ql-stroke.ql-thin {\n  stroke-width: 1;\n}\n.ql-snow .ql-transparent {\n  opacity: 0.4;\n}\n.ql-snow .ql-direction svg:last-child {\n  display: none;\n}\n.ql-snow .ql-direction.ql-active svg:last-child {\n  display: inline;\n}\n.ql-snow .ql-direction.ql-active svg:first-child {\n  display: none;\n}\n.ql-snow .ql-editor h1 {\n  font-size: 2em;\n}\n.ql-snow .ql-editor h2 {\n  font-size: 1.5em;\n}\n.ql-snow .ql-editor h3 {\n  font-size: 1.17em;\n}\n.ql-snow .ql-editor h4 {\n  font-size: 1em;\n}\n.ql-snow .ql-editor h5 {\n  font-size: 0.83em;\n}\n.ql-snow .ql-editor h6 {\n  font-size: 0.67em;\n}\n.ql-snow .ql-editor a {\n  text-decoration: underline;\n}\n.ql-snow .ql-editor blockquote {\n  border-left: 4px solid #ccc;\n  margin-bottom: 5px;\n  margin-top: 5px;\n  padding-left: 16px;\n}\n.ql-snow .ql-editor code,\n.ql-snow .ql-editor pre {\n  background-color: #f0f0f0;\n  border-radius: 3px;\n}\n.ql-snow .ql-editor pre {\n  white-space: pre-wrap;\n  margin-bottom: 5px;\n  margin-top: 5px;\n  padding: 5px 10px;\n}\n.ql-snow .ql-editor code {\n  font-size: 85%;\n  padding-bottom: 2px;\n  padding-top: 2px;\n}\n.ql-snow .ql-editor code:before,\n.ql-snow .ql-editor code:after {\n  content: \"\\A0\";\n  letter-spacing: -2px;\n}\n.ql-snow .ql-editor pre.ql-syntax {\n  background-color: #23241f;\n  color: #f8f8f2;\n  overflow: visible;\n}\n.ql-snow .ql-editor img {\n  max-width: 100%;\n}\n.ql-snow .ql-picker {\n  color: #444;\n  display: inline-block;\n  float: left;\n  font-size: 14px;\n  font-weight: 500;\n  height: 24px;\n  position: relative;\n  vertical-align: middle;\n}\n.ql-snow .ql-picker-label {\n  cursor: pointer;\n  display: inline-block;\n  height: 100%;\n  padding-left: 8px;\n  padding-right: 2px;\n  position: relative;\n  width: 100%;\n}\n.ql-snow .ql-picker-label::before {\n  display: inline-block;\n  line-height: 22px;\n}\n.ql-snow .ql-picker-options {\n  background-color: #fff;\n  display: none;\n  min-width: 100%;\n  padding: 4px 8px;\n  position: absolute;\n  white-space: nowrap;\n}\n.ql-snow .ql-picker-options .ql-picker-item {\n  cursor: pointer;\n  display: block;\n  padding-bottom: 5px;\n  padding-top: 5px;\n}\n.ql-snow .ql-picker.ql-expanded .ql-picker-label {\n  color: #ccc;\n  z-index: 2;\n}\n.ql-snow .ql-picker.ql-expanded .ql-picker-label .ql-fill {\n  fill: #ccc;\n}\n.ql-snow .ql-picker.ql-expanded .ql-picker-label .ql-stroke {\n  stroke: #ccc;\n}\n.ql-snow .ql-picker.ql-expanded .ql-picker-options {\n  display: block;\n  margin-top: -1px;\n  top: 100%;\n  z-index: 1;\n}\n.ql-snow .ql-color-picker,\n.ql-snow .ql-icon-picker {\n  width: 28px;\n}\n.ql-snow .ql-color-picker .ql-picker-label,\n.ql-snow .ql-icon-picker .ql-picker-label {\n  padding: 2px 4px;\n}\n.ql-snow .ql-color-picker .ql-picker-label svg,\n.ql-snow .ql-icon-picker .ql-picker-label svg {\n  right: 4px;\n}\n.ql-snow .ql-icon-picker .ql-picker-options {\n  padding: 4px 0px;\n}\n.ql-snow .ql-icon-picker .ql-picker-item {\n  height: 24px;\n  width: 24px;\n  padding: 2px 4px;\n}\n.ql-snow .ql-color-picker .ql-picker-options {\n  padding: 3px 5px;\n  width: 152px;\n}\n.ql-snow .ql-color-picker .ql-picker-item {\n  border: 1px solid transparent;\n  float: left;\n  height: 16px;\n  margin: 2px;\n  padding: 0px;\n  width: 16px;\n}\n.ql-snow .ql-picker:not(.ql-color-picker):not(.ql-icon-picker) svg {\n  position: absolute;\n  margin-top: -9px;\n  right: 0;\n  top: 50%;\n  width: 18px;\n}\n.ql-snow .ql-picker.ql-header .ql-picker-label[data-label]:not([data-label=''])::before,\n.ql-snow .ql-picker.ql-font .ql-picker-label[data-label]:not([data-label=''])::before,\n.ql-snow .ql-picker.ql-size .ql-picker-label[data-label]:not([data-label=''])::before,\n.ql-snow .ql-picker.ql-header .ql-picker-item[data-label]:not([data-label=''])::before,\n.ql-snow .ql-picker.ql-font .ql-picker-item[data-label]:not([data-label=''])::before,\n.ql-snow .ql-picker.ql-size .ql-picker-item[data-label]:not([data-label=''])::before {\n  content: attr(data-label);\n}\n.ql-snow .ql-picker.ql-header {\n  width: 98px;\n}\n.ql-snow .ql-picker.ql-header .ql-picker-label::before,\n.ql-snow .ql-picker.ql-header .ql-picker-item::before {\n  content: 'Normal';\n}\n.ql-snow .ql-picker.ql-header .ql-picker-label[data-value=\"1\"]::before,\n.ql-snow .ql-picker.ql-header .ql-picker-item[data-value=\"1\"]::before {\n  content: 'Heading 1';\n}\n.ql-snow .ql-picker.ql-header .ql-picker-label[data-value=\"2\"]::before,\n.ql-snow .ql-picker.ql-header .ql-picker-item[data-value=\"2\"]::before {\n  content: 'Heading 2';\n}\n.ql-snow .ql-picker.ql-header .ql-picker-label[data-value=\"3\"]::before,\n.ql-snow .ql-picker.ql-header .ql-picker-item[data-value=\"3\"]::before {\n  content: 'Heading 3';\n}\n.ql-snow .ql-picker.ql-header .ql-picker-label[data-value=\"4\"]::before,\n.ql-snow .ql-picker.ql-header .ql-picker-item[data-value=\"4\"]::before {\n  content: 'Heading 4';\n}\n.ql-snow .ql-picker.ql-header .ql-picker-label[data-value=\"5\"]::before,\n.ql-snow .ql-picker.ql-header .ql-picker-item[data-value=\"5\"]::before {\n  content: 'Heading 5';\n}\n.ql-snow .ql-picker.ql-header .ql-picker-label[data-value=\"6\"]::before,\n.ql-snow .ql-picker.ql-header .ql-picker-item[data-value=\"6\"]::before {\n  content: 'Heading 6';\n}\n.ql-snow .ql-picker.ql-header .ql-picker-item[data-value=\"1\"]::before {\n  font-size: 2em;\n}\n.ql-snow .ql-picker.ql-header .ql-picker-item[data-value=\"2\"]::before {\n  font-size: 1.5em;\n}\n.ql-snow .ql-picker.ql-header .ql-picker-item[data-value=\"3\"]::before {\n  font-size: 1.17em;\n}\n.ql-snow .ql-picker.ql-header .ql-picker-item[data-value=\"4\"]::before {\n  font-size: 1em;\n}\n.ql-snow .ql-picker.ql-header .ql-picker-item[data-value=\"5\"]::before {\n  font-size: 0.83em;\n}\n.ql-snow .ql-picker.ql-header .ql-picker-item[data-value=\"6\"]::before {\n  font-size: 0.67em;\n}\n.ql-snow .ql-picker.ql-font {\n  width: 108px;\n}\n.ql-snow .ql-picker.ql-font .ql-picker-label::before,\n.ql-snow .ql-picker.ql-font .ql-picker-item::before {\n  content: 'Sans Serif';\n}\n.ql-snow .ql-picker.ql-font .ql-picker-label[data-value=serif]::before,\n.ql-snow .ql-picker.ql-font .ql-picker-item[data-value=serif]::before {\n  content: 'Serif';\n}\n.ql-snow .ql-picker.ql-font .ql-picker-label[data-value=monospace]::before,\n.ql-snow .ql-picker.ql-font .ql-picker-item[data-value=monospace]::before {\n  content: 'Monospace';\n}\n.ql-snow .ql-picker.ql-font .ql-picker-item[data-value=serif]::before {\n  font-family: Georgia, Times New Roman, serif;\n}\n.ql-snow .ql-picker.ql-font .ql-picker-item[data-value=monospace]::before {\n  font-family: Monaco, Courier New, monospace;\n}\n.ql-snow .ql-picker.ql-size {\n  width: 98px;\n}\n.ql-snow .ql-picker.ql-size .ql-picker-label::before,\n.ql-snow .ql-picker.ql-size .ql-picker-item::before {\n  content: 'Normal';\n}\n.ql-snow .ql-picker.ql-size .ql-picker-label[data-value=small]::before,\n.ql-snow .ql-picker.ql-size .ql-picker-item[data-value=small]::before {\n  content: 'Small';\n}\n.ql-snow .ql-picker.ql-size .ql-picker-label[data-value=large]::before,\n.ql-snow .ql-picker.ql-size .ql-picker-item[data-value=large]::before {\n  content: 'Large';\n}\n.ql-snow .ql-picker.ql-size .ql-picker-label[data-value=huge]::before,\n.ql-snow .ql-picker.ql-size .ql-picker-item[data-value=huge]::before {\n  content: 'Huge';\n}\n.ql-snow .ql-picker.ql-size .ql-picker-item[data-value=small]::before {\n  font-size: 10px;\n}\n.ql-snow .ql-picker.ql-size .ql-picker-item[data-value=large]::before {\n  font-size: 18px;\n}\n.ql-snow .ql-picker.ql-size .ql-picker-item[data-value=huge]::before {\n  font-size: 32px;\n}\n.ql-snow .ql-color-picker.ql-background .ql-picker-item {\n  background-color: #fff;\n}\n.ql-snow .ql-color-picker.ql-color .ql-picker-item {\n  background-color: #000;\n}\n.ql-toolbar.ql-snow {\n  border: 1px solid #ccc;\n  box-sizing: border-box;\n  font-family: 'Helvetica Neue', 'Helvetica', 'Arial', sans-serif;\n  padding: 8px;\n}\n.ql-toolbar.ql-snow .ql-formats {\n  margin-right: 15px;\n}\n.ql-toolbar.ql-snow .ql-picker-label {\n  border: 1px solid transparent;\n}\n.ql-toolbar.ql-snow .ql-picker-options {\n  border: 1px solid transparent;\n  box-shadow: rgba(0,0,0,0.2) 0 2px 8px;\n}\n.ql-toolbar.ql-snow .ql-picker.ql-expanded .ql-picker-label {\n  border-color: #ccc;\n}\n.ql-toolbar.ql-snow .ql-picker.ql-expanded .ql-picker-options {\n  border-color: #ccc;\n}\n.ql-toolbar.ql-snow .ql-color-picker .ql-picker-item.ql-selected,\n.ql-toolbar.ql-snow .ql-color-picker .ql-picker-item:hover {\n  border-color: #000;\n}\n.ql-toolbar.ql-snow + .ql-container.ql-snow {\n  border-top: 0px;\n}\n.ql-snow .ql-tooltip {\n  background-color: #fff;\n  border: 1px solid #ccc;\n  box-shadow: 0px 0px 5px #ddd;\n  color: #444;\n  padding: 5px 12px;\n  white-space: nowrap;\n}\n.ql-snow .ql-tooltip::before {\n  content: \"Visit URL:\";\n  line-height: 26px;\n  margin-right: 8px;\n}\n.ql-snow .ql-tooltip input[type=text] {\n  display: none;\n  border: 1px solid #ccc;\n  font-size: 13px;\n  height: 26px;\n  margin: 0px;\n  padding: 3px 5px;\n  width: 170px;\n}\n.ql-snow .ql-tooltip a.ql-preview {\n  display: inline-block;\n  max-width: 200px;\n  overflow-x: hidden;\n  text-overflow: ellipsis;\n  vertical-align: top;\n}\n.ql-snow .ql-tooltip a.ql-action::after {\n  border-right: 1px solid #ccc;\n  content: 'Edit';\n  margin-left: 16px;\n  padding-right: 8px;\n}\n.ql-snow .ql-tooltip a.ql-remove::before {\n  content: 'Remove';\n  margin-left: 8px;\n}\n.ql-snow .ql-tooltip a {\n  line-height: 26px;\n}\n.ql-snow .ql-tooltip.ql-editing a.ql-preview,\n.ql-snow .ql-tooltip.ql-editing a.ql-remove {\n  display: none;\n}\n.ql-snow .ql-tooltip.ql-editing input[type=text] {\n  display: inline-block;\n}\n.ql-snow .ql-tooltip.ql-editing a.ql-action::after {\n  border-right: 0px;\n  content: 'Save';\n  padding-right: 0px;\n}\n.ql-snow .ql-tooltip[data-mode=link]::before {\n  content: \"Enter link:\";\n}\n.ql-snow .ql-tooltip[data-mode=formula]::before {\n  content: \"Enter formula:\";\n}\n.ql-snow .ql-tooltip[data-mode=video]::before {\n  content: \"Enter video:\";\n}\n.ql-snow a {\n  color: #06c;\n}\n.ql-container.ql-snow {\n  border: 1px solid #ccc;\n}\n", ""]);

// exports


/***/ }),
/* 20 */
/***/ (function(module, exports, __webpack_require__) {

exports = module.exports = __webpack_require__(3)();
// imports


// module
exports.push([module.i, "\n.load-bar {\n    position: fixed;\n    top:0;\n    left: 0;\n    width: 100%;\n    height: 5px;\n    background-color: #fdba2c;\n    z-index: 10000;\n}\n.bar {\n    content: \"\";\n    display: inline;\n    position: absolute;\n    width: 0;\n    height: 100%;\n    left: 50%;\n    text-align: center;\n}\n.bar:nth-child(1) {\n    background-color: #da4733;\n    animation: loading 3s linear infinite;\n}\n.bar:nth-child(2) {\n    background-color: #3b78e7;\n    animation: loading 3s linear 1s infinite;\n}\n.bar:nth-child(3) {\n    background-color: #fdba2c;\n    animation: loading 3s linear 2s infinite;\n}\n@keyframes loading {\nfrom {left: 50%; width: 0;z-index:100;\n}\n33.3333% {left: 0; width: 100%;z-index: 10;\n}\nto {left: 0; width: 100%;\n}\n}\n", ""]);

// exports


/***/ }),
/* 21 */
/***/ (function(module, exports, __webpack_require__) {

// style-loader: Adds some css to the DOM by adding a <style> tag

// load the styles
var content = __webpack_require__(18);
if(typeof content === 'string') content = [[module.i, content, '']];
// add the styles to the DOM
var update = __webpack_require__(10)(content, {});
if(content.locals) module.exports = content.locals;
// Hot Module Replacement
if(false) {
	// When the styles change, update the <style> tags
	if(!content.locals) {
		module.hot.accept("!!../../css-loader/index.js!./quill.core.css", function() {
			var newContent = require("!!../../css-loader/index.js!./quill.core.css");
			if(typeof newContent === 'string') newContent = [[module.id, newContent, '']];
			update(newContent);
		});
	}
	// When the module is disposed, remove the <style> tags
	module.hot.dispose(function() { update(); });
}

/***/ }),
/* 22 */
/***/ (function(module, exports, __webpack_require__) {

// style-loader: Adds some css to the DOM by adding a <style> tag

// load the styles
var content = __webpack_require__(19);
if(typeof content === 'string') content = [[module.i, content, '']];
// add the styles to the DOM
var update = __webpack_require__(10)(content, {});
if(content.locals) module.exports = content.locals;
// Hot Module Replacement
if(false) {
	// When the styles change, update the <style> tags
	if(!content.locals) {
		module.hot.accept("!!../../css-loader/index.js!./quill.snow.css", function() {
			var newContent = require("!!../../css-loader/index.js!./quill.snow.css");
			if(typeof newContent === 'string') newContent = [[module.id, newContent, '']];
			update(newContent);
		});
	}
	// When the module is disposed, remove the <style> tags
	module.hot.dispose(function() { update(); });
}

/***/ }),
/* 23 */
/***/ (function(module, exports, __webpack_require__) {


/* styles */
__webpack_require__(27)

var Component = __webpack_require__(1)(
  /* script */
  __webpack_require__(16),
  /* template */
  __webpack_require__(26),
  /* scopeId */
  null,
  /* cssModules */
  null
)
Component.options.__file = "C:\\OSPanel\\domains\\ohcasey.ru\\www\\resources\\assets\\js\\components\\loader.vue"
if (Component.esModule && Object.keys(Component.esModule).some(function (key) {return key !== "default" && key !== "__esModule"})) {console.error("named exports are not supported in *.vue files.")}
if (Component.options.functional) {console.error("[vue-loader] loader.vue: functional components are not supported with templates, they should use render functions.")}

/* hot reload */
if (false) {(function () {
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), false)
  if (!hotAPI.compatible) return
  module.hot.accept()
  if (!module.hot.data) {
    hotAPI.createRecord("data-v-fff3d918", Component.options)
  } else {
    hotAPI.reload("data-v-fff3d918", Component.options)
  }
})()}

module.exports = Component.exports


/***/ }),
/* 24 */
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
     require("vue-hot-reload-api").rerender("data-v-0cf751aa", module.exports)
  }
}

/***/ }),
/* 25 */
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
     require("vue-hot-reload-api").rerender("data-v-1a284cd6", module.exports)
  }
}

/***/ }),
/* 26 */
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
     require("vue-hot-reload-api").rerender("data-v-fff3d918", module.exports)
  }
}

/***/ }),
/* 27 */
/***/ (function(module, exports, __webpack_require__) {

// style-loader: Adds some css to the DOM by adding a <style> tag

// load the styles
var content = __webpack_require__(20);
if(typeof content === 'string') content = [[module.i, content, '']];
if(content.locals) module.exports = content.locals;
// add the styles to the DOM
var update = __webpack_require__(7)("7a78c2c2", content, false);
// Hot Module Replacement
if(false) {
 // When the styles change, update the <style> tags
 if(!content.locals) {
   module.hot.accept("!!../../../../node_modules/css-loader/index.js!../../../../node_modules/vue-loader/lib/style-rewriter.js?id=data-v-fff3d918!../../../../node_modules/vue-loader/lib/selector.js?type=styles&index=0!./loader.vue", function() {
     var newContent = require("!!../../../../node_modules/css-loader/index.js!../../../../node_modules/vue-loader/lib/style-rewriter.js?id=data-v-fff3d918!../../../../node_modules/vue-loader/lib/selector.js?type=styles&index=0!./loader.vue");
     if(typeof newContent === 'string') newContent = [[module.id, newContent, '']];
     update(newContent);
   });
 }
 // When the module is disposed, remove the <style> tags
 module.hot.dispose(function() { update(); });
}

/***/ }),
/* 28 */
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
/* 29 */,
/* 30 */,
/* 31 */,
/* 32 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


Object.defineProperty(exports, "__esModule", {
    value: true
});
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

exports.default = {
    props: ['rows'],
    data: function data() {
        return {
            offset: 4
        };
    },

    methods: {
        changePage: function changePage(page) {
            this.rows.current_page = page;
            this.$emit('update_table');
        }
    },
    computed: {
        pagesNumbers: function pagesNumbers() {
            if (!this.rows.to) {
                return [];
            }
            var from = this.rows.current_page - this.offset;
            if (from < 1) {
                from = 1;
            }
            var to = from + this.offset * 2;
            if (to >= this.rows.last_page) {
                to = this.rows.last_page;
            }
            var pagesArray = [];
            while (from <= to) {
                pagesArray.push(from);
                from++;
            }
            return pagesArray;
        }
    }
};

/***/ }),
/* 33 */
/***/ (function(module, exports, __webpack_require__) {

module.exports={render:function (){var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
  return (_vm.rows.last_page > 1) ? _c('ul', {
    staticClass: "pagination",
    staticStyle: {
      "margin": "2px 0",
      "float": "right"
    }
  }, [(_vm.rows.prev_page_url) ? _c('li', [_c('a', {
    on: {
      "click": function($event) {
        _vm.changePage(1)
      }
    }
  }, [_vm._v("«")])]) : _vm._e(), _vm._v(" "), (_vm.rows.prev_page_url) ? _c('li', [_c('a', {
    on: {
      "click": function($event) {
        _vm.changePage(_vm.rows.current_page - 1)
      }
    }
  }, [_vm._v("‹")])]) : _vm._e(), _vm._v(" "), _vm._l((_vm.pagesNumbers), function(page) {
    return _c('li', {
      staticClass: "paginate_button",
      class: [(page == _vm.rows.current_page) ? 'active' : '']
    }, [_c('a', {
      on: {
        "click": function($event) {
          _vm.changePage(page)
        }
      }
    }, [_vm._v(_vm._s(page))])])
  }), _vm._v(" "), (_vm.rows.next_page_url) ? _c('li', [_c('a', {
    on: {
      "click": function($event) {
        _vm.changePage(_vm.rows.current_page + 1)
      }
    }
  }, [_vm._v("›")])]) : _vm._e(), _vm._v(" "), (_vm.rows.next_page_url) ? _c('li', [_c('a', {
    on: {
      "click": function($event) {
        _vm.changePage(_vm.rows.last_page)
      }
    }
  }, [_vm._v("»")])]) : _vm._e()], 2) : _vm._e()
},staticRenderFns: []}
module.exports.render._withStripped = true
if (false) {
  module.hot.accept()
  if (module.hot.data) {
     require("vue-hot-reload-api").rerender("data-v-e1321980", module.exports)
  }
}

/***/ }),
/* 34 */
/***/ (function(module, exports) {

module.exports = function(module) {
	if(!module.webpackPolyfill) {
		module.deprecate = function() {};
		module.paths = [];
		// module.parent = undefined by default
		if(!module.children) module.children = [];
		Object.defineProperty(module, "loaded", {
			enumerable: true,
			get: function() {
				return module.l;
			}
		});
		Object.defineProperty(module, "id", {
			enumerable: true,
			get: function() {
				return module.i;
			}
		});
		module.webpackPolyfill = 1;
	}
	return module;
};


/***/ }),
/* 35 */,
/* 36 */,
/* 37 */,
/* 38 */,
/* 39 */,
/* 40 */,
/* 41 */,
/* 42 */,
/* 43 */,
/* 44 */,
/* 45 */,
/* 46 */,
/* 47 */,
/* 48 */,
/* 49 */,
/* 50 */,
/* 51 */,
/* 52 */,
/* 53 */,
/* 54 */,
/* 55 */,
/* 56 */,
/* 57 */,
/* 58 */,
/* 59 */,
/* 60 */,
/* 61 */,
/* 62 */,
/* 63 */,
/* 64 */,
/* 65 */,
/* 66 */,
/* 67 */,
/* 68 */,
/* 69 */,
/* 70 */,
/* 71 */,
/* 72 */,
/* 73 */,
/* 74 */,
/* 75 */,
/* 76 */,
/* 77 */,
/* 78 */,
/* 79 */,
/* 80 */,
/* 81 */,
/* 82 */,
/* 83 */,
/* 84 */,
/* 85 */,
/* 86 */,
/* 87 */,
/* 88 */,
/* 89 */,
/* 90 */,
/* 91 */,
/* 92 */,
/* 93 */,
/* 94 */,
/* 95 */,
/* 96 */,
/* 97 */,
/* 98 */,
/* 99 */,
/* 100 */,
/* 101 */,
/* 102 */,
/* 103 */,
/* 104 */,
/* 105 */,
/* 106 */,
/* 107 */,
/* 108 */,
/* 109 */,
/* 110 */,
/* 111 */,
/* 112 */,
/* 113 */,
/* 114 */,
/* 115 */,
/* 116 */,
/* 117 */,
/* 118 */,
/* 119 */,
/* 120 */,
/* 121 */,
/* 122 */,
/* 123 */,
/* 124 */,
/* 125 */,
/* 126 */,
/* 127 */,
/* 128 */,
/* 129 */,
/* 130 */,
/* 131 */,
/* 132 */,
/* 133 */,
/* 134 */,
/* 135 */,
/* 136 */,
/* 137 */,
/* 138 */,
/* 139 */,
/* 140 */,
/* 141 */,
/* 142 */,
/* 143 */,
/* 144 */,
/* 145 */,
/* 146 */,
/* 147 */,
/* 148 */,
/* 149 */,
/* 150 */,
/* 151 */,
/* 152 */,
/* 153 */,
/* 154 */,
/* 155 */,
/* 156 */,
/* 157 */
/***/ (function(module, exports, __webpack_require__) {


/* styles */
__webpack_require__(405)

var Component = __webpack_require__(1)(
  /* script */
  __webpack_require__(230),
  /* template */
  __webpack_require__(375),
  /* scopeId */
  null,
  /* cssModules */
  null
)
Component.options.__file = "C:\\OSPanel\\domains\\ohcasey.ru\\www\\resources\\assets\\js\\components\\Offer\\DataTable.vue"
if (Component.esModule && Object.keys(Component.esModule).some(function (key) {return key !== "default" && key !== "__esModule"})) {console.error("named exports are not supported in *.vue files.")}
if (Component.options.functional) {console.error("[vue-loader] DataTable.vue: functional components are not supported with templates, they should use render functions.")}

/* hot reload */
if (false) {(function () {
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), false)
  if (!hotAPI.compatible) return
  module.hot.accept()
  if (!module.hot.data) {
    hotAPI.createRecord("data-v-76745c00", Component.options)
  } else {
    hotAPI.reload("data-v-76745c00", Component.options)
  }
})()}

module.exports = Component.exports


/***/ }),
/* 158 */
/***/ (function(module, exports, __webpack_require__) {

var Component = __webpack_require__(1)(
  /* script */
  __webpack_require__(231),
  /* template */
  __webpack_require__(377),
  /* scopeId */
  null,
  /* cssModules */
  null
)
Component.options.__file = "C:\\OSPanel\\domains\\ohcasey.ru\\www\\resources\\assets\\js\\components\\Offer\\Form.vue"
if (Component.esModule && Object.keys(Component.esModule).some(function (key) {return key !== "default" && key !== "__esModule"})) {console.error("named exports are not supported in *.vue files.")}
if (Component.options.functional) {console.error("[vue-loader] Form.vue: functional components are not supported with templates, they should use render functions.")}

/* hot reload */
if (false) {(function () {
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), false)
  if (!hotAPI.compatible) return
  module.hot.accept()
  if (!module.hot.data) {
    hotAPI.createRecord("data-v-7c577c90", Component.options)
  } else {
    hotAPI.reload("data-v-7c577c90", Component.options)
  }
})()}

module.exports = Component.exports


/***/ }),
/* 159 */
/***/ (function(module, exports, __webpack_require__) {

var Component = __webpack_require__(1)(
  /* script */
  __webpack_require__(236),
  /* template */
  __webpack_require__(346),
  /* scopeId */
  null,
  /* cssModules */
  null
)
Component.options.__file = "C:\\OSPanel\\domains\\ohcasey.ru\\www\\resources\\assets\\js\\components\\OptionGroup\\Form.vue"
if (Component.esModule && Object.keys(Component.esModule).some(function (key) {return key !== "default" && key !== "__esModule"})) {console.error("named exports are not supported in *.vue files.")}
if (Component.options.functional) {console.error("[vue-loader] Form.vue: functional components are not supported with templates, they should use render functions.")}

/* hot reload */
if (false) {(function () {
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), false)
  if (!hotAPI.compatible) return
  module.hot.accept()
  if (!module.hot.data) {
    hotAPI.createRecord("data-v-1cc123ec", Component.options)
  } else {
    hotAPI.reload("data-v-1cc123ec", Component.options)
  }
})()}

module.exports = Component.exports


/***/ }),
/* 160 */
/***/ (function(module, exports, __webpack_require__) {

var Component = __webpack_require__(1)(
  /* script */
  __webpack_require__(241),
  /* template */
  __webpack_require__(378),
  /* scopeId */
  null,
  /* cssModules */
  null
)
Component.options.__file = "C:\\OSPanel\\domains\\ohcasey.ru\\www\\resources\\assets\\js\\components\\OptionValue\\Form.vue"
if (Component.esModule && Object.keys(Component.esModule).some(function (key) {return key !== "default" && key !== "__esModule"})) {console.error("named exports are not supported in *.vue files.")}
if (Component.options.functional) {console.error("[vue-loader] Form.vue: functional components are not supported with templates, they should use render functions.")}

/* hot reload */
if (false) {(function () {
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), false)
  if (!hotAPI.compatible) return
  module.hot.accept()
  if (!module.hot.data) {
    hotAPI.createRecord("data-v-7ea3f950", Component.options)
  } else {
    hotAPI.reload("data-v-7ea3f950", Component.options)
  }
})()}

module.exports = Component.exports


/***/ }),
/* 161 */
/***/ (function(module, exports, __webpack_require__) {

var Component = __webpack_require__(1)(
  /* script */
  __webpack_require__(246),
  /* template */
  __webpack_require__(361),
  /* scopeId */
  null,
  /* cssModules */
  null
)
Component.options.__file = "C:\\OSPanel\\domains\\ohcasey.ru\\www\\resources\\assets\\js\\components\\Option\\Form.vue"
if (Component.esModule && Object.keys(Component.esModule).some(function (key) {return key !== "default" && key !== "__esModule"})) {console.error("named exports are not supported in *.vue files.")}
if (Component.options.functional) {console.error("[vue-loader] Form.vue: functional components are not supported with templates, they should use render functions.")}

/* hot reload */
if (false) {(function () {
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), false)
  if (!hotAPI.compatible) return
  module.hot.accept()
  if (!module.hot.data) {
    hotAPI.createRecord("data-v-4ca9f90d", Component.options)
  } else {
    hotAPI.reload("data-v-4ca9f90d", Component.options)
  }
})()}

module.exports = Component.exports


/***/ }),
/* 162 */
/***/ (function(module, exports, __webpack_require__) {

var Component = __webpack_require__(1)(
  /* script */
  __webpack_require__(251),
  /* template */
  __webpack_require__(376),
  /* scopeId */
  null,
  /* cssModules */
  null
)
Component.options.__file = "C:\\OSPanel\\domains\\ohcasey.ru\\www\\resources\\assets\\js\\components\\Page\\Form.vue"
if (Component.esModule && Object.keys(Component.esModule).some(function (key) {return key !== "default" && key !== "__esModule"})) {console.error("named exports are not supported in *.vue files.")}
if (Component.options.functional) {console.error("[vue-loader] Form.vue: functional components are not supported with templates, they should use render functions.")}

/* hot reload */
if (false) {(function () {
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), false)
  if (!hotAPI.compatible) return
  module.hot.accept()
  if (!module.hot.data) {
    hotAPI.createRecord("data-v-78a23df3", Component.options)
  } else {
    hotAPI.reload("data-v-78a23df3", Component.options)
  }
})()}

module.exports = Component.exports


/***/ }),
/* 163 */
/***/ (function(module, exports, __webpack_require__) {


/* styles */
__webpack_require__(399)

var Component = __webpack_require__(1)(
  /* script */
  __webpack_require__(256),
  /* template */
  __webpack_require__(357),
  /* scopeId */
  null,
  /* cssModules */
  null
)
Component.options.__file = "C:\\OSPanel\\domains\\ohcasey.ru\\www\\resources\\assets\\js\\components\\Product\\Form.vue"
if (Component.esModule && Object.keys(Component.esModule).some(function (key) {return key !== "default" && key !== "__esModule"})) {console.error("named exports are not supported in *.vue files.")}
if (Component.options.functional) {console.error("[vue-loader] Form.vue: functional components are not supported with templates, they should use render functions.")}

/* hot reload */
if (false) {(function () {
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), false)
  if (!hotAPI.compatible) return
  module.hot.accept()
  if (!module.hot.data) {
    hotAPI.createRecord("data-v-37dc2376", Component.options)
  } else {
    hotAPI.reload("data-v-37dc2376", Component.options)
  }
})()}

module.exports = Component.exports


/***/ }),
/* 164 */
/***/ (function(module, exports, __webpack_require__) {

var Component = __webpack_require__(1)(
  /* script */
  __webpack_require__(261),
  /* template */
  __webpack_require__(350),
  /* scopeId */
  null,
  /* cssModules */
  null
)
Component.options.__file = "C:\\OSPanel\\domains\\ohcasey.ru\\www\\resources\\assets\\js\\components\\Setting\\Form.vue"
if (Component.esModule && Object.keys(Component.esModule).some(function (key) {return key !== "default" && key !== "__esModule"})) {console.error("named exports are not supported in *.vue files.")}
if (Component.options.functional) {console.error("[vue-loader] Form.vue: functional components are not supported with templates, they should use render functions.")}

/* hot reload */
if (false) {(function () {
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), false)
  if (!hotAPI.compatible) return
  module.hot.accept()
  if (!module.hot.data) {
    hotAPI.createRecord("data-v-2824abf8", Component.options)
  } else {
    hotAPI.reload("data-v-2824abf8", Component.options)
  }
})()}

module.exports = Component.exports


/***/ }),
/* 165 */
/***/ (function(module, exports, __webpack_require__) {

var Component = __webpack_require__(1)(
  /* script */
  __webpack_require__(266),
  /* template */
  __webpack_require__(333),
  /* scopeId */
  null,
  /* cssModules */
  null
)
Component.options.__file = "C:\\OSPanel\\domains\\ohcasey.ru\\www\\resources\\assets\\js\\components\\Tag\\Form.vue"
if (Component.esModule && Object.keys(Component.esModule).some(function (key) {return key !== "default" && key !== "__esModule"})) {console.error("named exports are not supported in *.vue files.")}
if (Component.options.functional) {console.error("[vue-loader] Form.vue: functional components are not supported with templates, they should use render functions.")}

/* hot reload */
if (false) {(function () {
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), false)
  if (!hotAPI.compatible) return
  module.hot.accept()
  if (!module.hot.data) {
    hotAPI.createRecord("data-v-01a7087a", Component.options)
  } else {
    hotAPI.reload("data-v-01a7087a", Component.options)
  }
})()}

module.exports = Component.exports


/***/ }),
/* 166 */,
/* 167 */,
/* 168 */,
/* 169 */,
/* 170 */,
/* 171 */,
/* 172 */,
/* 173 */
/***/ (function(module, exports, __webpack_require__) {

var Component = __webpack_require__(1)(
  /* script */
  __webpack_require__(232),
  /* template */
  __webpack_require__(394),
  /* scopeId */
  null,
  /* cssModules */
  null
)
Component.options.__file = "C:\\OSPanel\\domains\\ohcasey.ru\\www\\resources\\assets\\js\\components\\Offer\\Offer.vue"
if (Component.esModule && Object.keys(Component.esModule).some(function (key) {return key !== "default" && key !== "__esModule"})) {console.error("named exports are not supported in *.vue files.")}
if (Component.options.functional) {console.error("[vue-loader] Offer.vue: functional components are not supported with templates, they should use render functions.")}

/* hot reload */
if (false) {(function () {
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), false)
  if (!hotAPI.compatible) return
  module.hot.accept()
  if (!module.hot.data) {
    hotAPI.createRecord("data-v-f94573d0", Component.options)
  } else {
    hotAPI.reload("data-v-f94573d0", Component.options)
  }
})()}

module.exports = Component.exports


/***/ }),
/* 174 */
/***/ (function(module, exports, __webpack_require__) {

var Component = __webpack_require__(1)(
  /* script */
  __webpack_require__(233),
  /* template */
  __webpack_require__(355),
  /* scopeId */
  null,
  /* cssModules */
  null
)
Component.options.__file = "C:\\OSPanel\\domains\\ohcasey.ru\\www\\resources\\assets\\js\\components\\Offer\\OfferCreate.vue"
if (Component.esModule && Object.keys(Component.esModule).some(function (key) {return key !== "default" && key !== "__esModule"})) {console.error("named exports are not supported in *.vue files.")}
if (Component.options.functional) {console.error("[vue-loader] OfferCreate.vue: functional components are not supported with templates, they should use render functions.")}

/* hot reload */
if (false) {(function () {
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), false)
  if (!hotAPI.compatible) return
  module.hot.accept()
  if (!module.hot.data) {
    hotAPI.createRecord("data-v-33717598", Component.options)
  } else {
    hotAPI.reload("data-v-33717598", Component.options)
  }
})()}

module.exports = Component.exports


/***/ }),
/* 175 */
/***/ (function(module, exports, __webpack_require__) {

var Component = __webpack_require__(1)(
  /* script */
  __webpack_require__(234),
  /* template */
  __webpack_require__(344),
  /* scopeId */
  null,
  /* cssModules */
  null
)
Component.options.__file = "C:\\OSPanel\\domains\\ohcasey.ru\\www\\resources\\assets\\js\\components\\Offer\\OfferEdit.vue"
if (Component.esModule && Object.keys(Component.esModule).some(function (key) {return key !== "default" && key !== "__esModule"})) {console.error("named exports are not supported in *.vue files.")}
if (Component.options.functional) {console.error("[vue-loader] OfferEdit.vue: functional components are not supported with templates, they should use render functions.")}

/* hot reload */
if (false) {(function () {
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), false)
  if (!hotAPI.compatible) return
  module.hot.accept()
  if (!module.hot.data) {
    hotAPI.createRecord("data-v-1bd1797c", Component.options)
  } else {
    hotAPI.reload("data-v-1bd1797c", Component.options)
  }
})()}

module.exports = Component.exports


/***/ }),
/* 176 */
/***/ (function(module, exports, __webpack_require__) {

var Component = __webpack_require__(1)(
  /* script */
  __webpack_require__(237),
  /* template */
  __webpack_require__(345),
  /* scopeId */
  null,
  /* cssModules */
  null
)
Component.options.__file = "C:\\OSPanel\\domains\\ohcasey.ru\\www\\resources\\assets\\js\\components\\OptionGroup\\OptionGroup.vue"
if (Component.esModule && Object.keys(Component.esModule).some(function (key) {return key !== "default" && key !== "__esModule"})) {console.error("named exports are not supported in *.vue files.")}
if (Component.options.functional) {console.error("[vue-loader] OptionGroup.vue: functional components are not supported with templates, they should use render functions.")}

/* hot reload */
if (false) {(function () {
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), false)
  if (!hotAPI.compatible) return
  module.hot.accept()
  if (!module.hot.data) {
    hotAPI.createRecord("data-v-1be52798", Component.options)
  } else {
    hotAPI.reload("data-v-1be52798", Component.options)
  }
})()}

module.exports = Component.exports


/***/ }),
/* 177 */
/***/ (function(module, exports, __webpack_require__) {

var Component = __webpack_require__(1)(
  /* script */
  __webpack_require__(238),
  /* template */
  __webpack_require__(392),
  /* scopeId */
  null,
  /* cssModules */
  null
)
Component.options.__file = "C:\\OSPanel\\domains\\ohcasey.ru\\www\\resources\\assets\\js\\components\\OptionGroup\\OptionGroupCreate.vue"
if (Component.esModule && Object.keys(Component.esModule).some(function (key) {return key !== "default" && key !== "__esModule"})) {console.error("named exports are not supported in *.vue files.")}
if (Component.options.functional) {console.error("[vue-loader] OptionGroupCreate.vue: functional components are not supported with templates, they should use render functions.")}

/* hot reload */
if (false) {(function () {
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), false)
  if (!hotAPI.compatible) return
  module.hot.accept()
  if (!module.hot.data) {
    hotAPI.createRecord("data-v-f21d3360", Component.options)
  } else {
    hotAPI.reload("data-v-f21d3360", Component.options)
  }
})()}

module.exports = Component.exports


/***/ }),
/* 178 */
/***/ (function(module, exports, __webpack_require__) {

var Component = __webpack_require__(1)(
  /* script */
  __webpack_require__(239),
  /* template */
  __webpack_require__(382),
  /* scopeId */
  null,
  /* cssModules */
  null
)
Component.options.__file = "C:\\OSPanel\\domains\\ohcasey.ru\\www\\resources\\assets\\js\\components\\OptionGroup\\OptionGroupEdit.vue"
if (Component.esModule && Object.keys(Component.esModule).some(function (key) {return key !== "default" && key !== "__esModule"})) {console.error("named exports are not supported in *.vue files.")}
if (Component.options.functional) {console.error("[vue-loader] OptionGroupEdit.vue: functional components are not supported with templates, they should use render functions.")}

/* hot reload */
if (false) {(function () {
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), false)
  if (!hotAPI.compatible) return
  module.hot.accept()
  if (!module.hot.data) {
    hotAPI.createRecord("data-v-bc620944", Component.options)
  } else {
    hotAPI.reload("data-v-bc620944", Component.options)
  }
})()}

module.exports = Component.exports


/***/ }),
/* 179 */
/***/ (function(module, exports, __webpack_require__) {

var Component = __webpack_require__(1)(
  /* script */
  __webpack_require__(242),
  /* template */
  __webpack_require__(385),
  /* scopeId */
  null,
  /* cssModules */
  null
)
Component.options.__file = "C:\\OSPanel\\domains\\ohcasey.ru\\www\\resources\\assets\\js\\components\\OptionValue\\OptionValue.vue"
if (Component.esModule && Object.keys(Component.esModule).some(function (key) {return key !== "default" && key !== "__esModule"})) {console.error("named exports are not supported in *.vue files.")}
if (Component.options.functional) {console.error("[vue-loader] OptionValue.vue: functional components are not supported with templates, they should use render functions.")}

/* hot reload */
if (false) {(function () {
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), false)
  if (!hotAPI.compatible) return
  module.hot.accept()
  if (!module.hot.data) {
    hotAPI.createRecord("data-v-d08c95d0", Component.options)
  } else {
    hotAPI.reload("data-v-d08c95d0", Component.options)
  }
})()}

module.exports = Component.exports


/***/ }),
/* 180 */
/***/ (function(module, exports, __webpack_require__) {

var Component = __webpack_require__(1)(
  /* script */
  __webpack_require__(243),
  /* template */
  __webpack_require__(342),
  /* scopeId */
  null,
  /* cssModules */
  null
)
Component.options.__file = "C:\\OSPanel\\domains\\ohcasey.ru\\www\\resources\\assets\\js\\components\\OptionValue\\OptionValueCreate.vue"
if (Component.esModule && Object.keys(Component.esModule).some(function (key) {return key !== "default" && key !== "__esModule"})) {console.error("named exports are not supported in *.vue files.")}
if (Component.options.functional) {console.error("[vue-loader] OptionValueCreate.vue: functional components are not supported with templates, they should use render functions.")}

/* hot reload */
if (false) {(function () {
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), false)
  if (!hotAPI.compatible) return
  module.hot.accept()
  if (!module.hot.data) {
    hotAPI.createRecord("data-v-19547434", Component.options)
  } else {
    hotAPI.reload("data-v-19547434", Component.options)
  }
})()}

module.exports = Component.exports


/***/ }),
/* 181 */
/***/ (function(module, exports, __webpack_require__) {

var Component = __webpack_require__(1)(
  /* script */
  __webpack_require__(244),
  /* template */
  __webpack_require__(387),
  /* scopeId */
  null,
  /* cssModules */
  null
)
Component.options.__file = "C:\\OSPanel\\domains\\ohcasey.ru\\www\\resources\\assets\\js\\components\\OptionValue\\OptionValueEdit.vue"
if (Component.esModule && Object.keys(Component.esModule).some(function (key) {return key !== "default" && key !== "__esModule"})) {console.error("named exports are not supported in *.vue files.")}
if (Component.options.functional) {console.error("[vue-loader] OptionValueEdit.vue: functional components are not supported with templates, they should use render functions.")}

/* hot reload */
if (false) {(function () {
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), false)
  if (!hotAPI.compatible) return
  module.hot.accept()
  if (!module.hot.data) {
    hotAPI.createRecord("data-v-d6b79b7c", Component.options)
  } else {
    hotAPI.reload("data-v-d6b79b7c", Component.options)
  }
})()}

module.exports = Component.exports


/***/ }),
/* 182 */
/***/ (function(module, exports, __webpack_require__) {

var Component = __webpack_require__(1)(
  /* script */
  __webpack_require__(247),
  /* template */
  __webpack_require__(365),
  /* scopeId */
  null,
  /* cssModules */
  null
)
Component.options.__file = "C:\\OSPanel\\domains\\ohcasey.ru\\www\\resources\\assets\\js\\components\\Option\\Option.vue"
if (Component.esModule && Object.keys(Component.esModule).some(function (key) {return key !== "default" && key !== "__esModule"})) {console.error("named exports are not supported in *.vue files.")}
if (Component.options.functional) {console.error("[vue-loader] Option.vue: functional components are not supported with templates, they should use render functions.")}

/* hot reload */
if (false) {(function () {
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), false)
  if (!hotAPI.compatible) return
  module.hot.accept()
  if (!module.hot.data) {
    hotAPI.createRecord("data-v-5f6d0bfe", Component.options)
  } else {
    hotAPI.reload("data-v-5f6d0bfe", Component.options)
  }
})()}

module.exports = Component.exports


/***/ }),
/* 183 */
/***/ (function(module, exports, __webpack_require__) {

var Component = __webpack_require__(1)(
  /* script */
  __webpack_require__(248),
  /* template */
  __webpack_require__(347),
  /* scopeId */
  null,
  /* cssModules */
  null
)
Component.options.__file = "C:\\OSPanel\\domains\\ohcasey.ru\\www\\resources\\assets\\js\\components\\Option\\OptionCreate.vue"
if (Component.esModule && Object.keys(Component.esModule).some(function (key) {return key !== "default" && key !== "__esModule"})) {console.error("named exports are not supported in *.vue files.")}
if (Component.options.functional) {console.error("[vue-loader] OptionCreate.vue: functional components are not supported with templates, they should use render functions.")}

/* hot reload */
if (false) {(function () {
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), false)
  if (!hotAPI.compatible) return
  module.hot.accept()
  if (!module.hot.data) {
    hotAPI.createRecord("data-v-24eef2cc", Component.options)
  } else {
    hotAPI.reload("data-v-24eef2cc", Component.options)
  }
})()}

module.exports = Component.exports


/***/ }),
/* 184 */
/***/ (function(module, exports, __webpack_require__) {

var Component = __webpack_require__(1)(
  /* script */
  __webpack_require__(249),
  /* template */
  __webpack_require__(340),
  /* scopeId */
  null,
  /* cssModules */
  null
)
Component.options.__file = "C:\\OSPanel\\domains\\ohcasey.ru\\www\\resources\\assets\\js\\components\\Option\\OptionEdit.vue"
if (Component.esModule && Object.keys(Component.esModule).some(function (key) {return key !== "default" && key !== "__esModule"})) {console.error("named exports are not supported in *.vue files.")}
if (Component.options.functional) {console.error("[vue-loader] OptionEdit.vue: functional components are not supported with templates, they should use render functions.")}

/* hot reload */
if (false) {(function () {
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), false)
  if (!hotAPI.compatible) return
  module.hot.accept()
  if (!module.hot.data) {
    hotAPI.createRecord("data-v-1365a628", Component.options)
  } else {
    hotAPI.reload("data-v-1365a628", Component.options)
  }
})()}

module.exports = Component.exports


/***/ }),
/* 185 */
/***/ (function(module, exports, __webpack_require__) {

var Component = __webpack_require__(1)(
  /* script */
  __webpack_require__(252),
  /* template */
  __webpack_require__(388),
  /* scopeId */
  null,
  /* cssModules */
  null
)
Component.options.__file = "C:\\OSPanel\\domains\\ohcasey.ru\\www\\resources\\assets\\js\\components\\Page\\Page.vue"
if (Component.esModule && Object.keys(Component.esModule).some(function (key) {return key !== "default" && key !== "__esModule"})) {console.error("named exports are not supported in *.vue files.")}
if (Component.options.functional) {console.error("[vue-loader] Page.vue: functional components are not supported with templates, they should use render functions.")}

/* hot reload */
if (false) {(function () {
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), false)
  if (!hotAPI.compatible) return
  module.hot.accept()
  if (!module.hot.data) {
    hotAPI.createRecord("data-v-e0be9384", Component.options)
  } else {
    hotAPI.reload("data-v-e0be9384", Component.options)
  }
})()}

module.exports = Component.exports


/***/ }),
/* 186 */
/***/ (function(module, exports, __webpack_require__) {

var Component = __webpack_require__(1)(
  /* script */
  __webpack_require__(253),
  /* template */
  __webpack_require__(337),
  /* scopeId */
  null,
  /* cssModules */
  null
)
Component.options.__file = "C:\\OSPanel\\domains\\ohcasey.ru\\www\\resources\\assets\\js\\components\\Page\\PageCreate.vue"
if (Component.esModule && Object.keys(Component.esModule).some(function (key) {return key !== "default" && key !== "__esModule"})) {console.error("named exports are not supported in *.vue files.")}
if (Component.options.functional) {console.error("[vue-loader] PageCreate.vue: functional components are not supported with templates, they should use render functions.")}

/* hot reload */
if (false) {(function () {
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), false)
  if (!hotAPI.compatible) return
  module.hot.accept()
  if (!module.hot.data) {
    hotAPI.createRecord("data-v-086380da", Component.options)
  } else {
    hotAPI.reload("data-v-086380da", Component.options)
  }
})()}

module.exports = Component.exports


/***/ }),
/* 187 */
/***/ (function(module, exports, __webpack_require__) {

var Component = __webpack_require__(1)(
  /* script */
  __webpack_require__(254),
  /* template */
  __webpack_require__(362),
  /* scopeId */
  null,
  /* cssModules */
  null
)
Component.options.__file = "C:\\OSPanel\\domains\\ohcasey.ru\\www\\resources\\assets\\js\\components\\Page\\PageEdit.vue"
if (Component.esModule && Object.keys(Component.esModule).some(function (key) {return key !== "default" && key !== "__esModule"})) {console.error("named exports are not supported in *.vue files.")}
if (Component.options.functional) {console.error("[vue-loader] PageEdit.vue: functional components are not supported with templates, they should use render functions.")}

/* hot reload */
if (false) {(function () {
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), false)
  if (!hotAPI.compatible) return
  module.hot.accept()
  if (!module.hot.data) {
    hotAPI.createRecord("data-v-513a3068", Component.options)
  } else {
    hotAPI.reload("data-v-513a3068", Component.options)
  }
})()}

module.exports = Component.exports


/***/ }),
/* 188 */
/***/ (function(module, exports, __webpack_require__) {

var Component = __webpack_require__(1)(
  /* script */
  __webpack_require__(257),
  /* template */
  __webpack_require__(384),
  /* scopeId */
  null,
  /* cssModules */
  null
)
Component.options.__file = "C:\\OSPanel\\domains\\ohcasey.ru\\www\\resources\\assets\\js\\components\\Product\\Product.vue"
if (Component.esModule && Object.keys(Component.esModule).some(function (key) {return key !== "default" && key !== "__esModule"})) {console.error("named exports are not supported in *.vue files.")}
if (Component.options.functional) {console.error("[vue-loader] Product.vue: functional components are not supported with templates, they should use render functions.")}

/* hot reload */
if (false) {(function () {
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), false)
  if (!hotAPI.compatible) return
  module.hot.accept()
  if (!module.hot.data) {
    hotAPI.createRecord("data-v-d009ab04", Component.options)
  } else {
    hotAPI.reload("data-v-d009ab04", Component.options)
  }
})()}

module.exports = Component.exports


/***/ }),
/* 189 */
/***/ (function(module, exports, __webpack_require__) {

var Component = __webpack_require__(1)(
  /* script */
  __webpack_require__(258),
  /* template */
  __webpack_require__(372),
  /* scopeId */
  null,
  /* cssModules */
  null
)
Component.options.__file = "C:\\OSPanel\\domains\\ohcasey.ru\\www\\resources\\assets\\js\\components\\Product\\ProductCreate.vue"
if (Component.esModule && Object.keys(Component.esModule).some(function (key) {return key !== "default" && key !== "__esModule"})) {console.error("named exports are not supported in *.vue files.")}
if (Component.options.functional) {console.error("[vue-loader] ProductCreate.vue: functional components are not supported with templates, they should use render functions.")}

/* hot reload */
if (false) {(function () {
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), false)
  if (!hotAPI.compatible) return
  module.hot.accept()
  if (!module.hot.data) {
    hotAPI.createRecord("data-v-71a5c51a", Component.options)
  } else {
    hotAPI.reload("data-v-71a5c51a", Component.options)
  }
})()}

module.exports = Component.exports


/***/ }),
/* 190 */
/***/ (function(module, exports, __webpack_require__) {

var Component = __webpack_require__(1)(
  /* script */
  __webpack_require__(259),
  /* template */
  __webpack_require__(334),
  /* scopeId */
  null,
  /* cssModules */
  null
)
Component.options.__file = "C:\\OSPanel\\domains\\ohcasey.ru\\www\\resources\\assets\\js\\components\\Product\\ProductEdit.vue"
if (Component.esModule && Object.keys(Component.esModule).some(function (key) {return key !== "default" && key !== "__esModule"})) {console.error("named exports are not supported in *.vue files.")}
if (Component.options.functional) {console.error("[vue-loader] ProductEdit.vue: functional components are not supported with templates, they should use render functions.")}

/* hot reload */
if (false) {(function () {
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), false)
  if (!hotAPI.compatible) return
  module.hot.accept()
  if (!module.hot.data) {
    hotAPI.createRecord("data-v-02c084a8", Component.options)
  } else {
    hotAPI.reload("data-v-02c084a8", Component.options)
  }
})()}

module.exports = Component.exports


/***/ }),
/* 191 */
/***/ (function(module, exports, __webpack_require__) {

var Component = __webpack_require__(1)(
  /* script */
  __webpack_require__(262),
  /* template */
  __webpack_require__(368),
  /* scopeId */
  null,
  /* cssModules */
  null
)
Component.options.__file = "C:\\OSPanel\\domains\\ohcasey.ru\\www\\resources\\assets\\js\\components\\Setting\\Setting.vue"
if (Component.esModule && Object.keys(Component.esModule).some(function (key) {return key !== "default" && key !== "__esModule"})) {console.error("named exports are not supported in *.vue files.")}
if (Component.options.functional) {console.error("[vue-loader] Setting.vue: functional components are not supported with templates, they should use render functions.")}

/* hot reload */
if (false) {(function () {
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), false)
  if (!hotAPI.compatible) return
  module.hot.accept()
  if (!module.hot.data) {
    hotAPI.createRecord("data-v-65380b40", Component.options)
  } else {
    hotAPI.reload("data-v-65380b40", Component.options)
  }
})()}

module.exports = Component.exports


/***/ }),
/* 192 */
/***/ (function(module, exports, __webpack_require__) {

var Component = __webpack_require__(1)(
  /* script */
  __webpack_require__(263),
  /* template */
  __webpack_require__(349),
  /* scopeId */
  null,
  /* cssModules */
  null
)
Component.options.__file = "C:\\OSPanel\\domains\\ohcasey.ru\\www\\resources\\assets\\js\\components\\Setting\\SettingCreate.vue"
if (Component.esModule && Object.keys(Component.esModule).some(function (key) {return key !== "default" && key !== "__esModule"})) {console.error("named exports are not supported in *.vue files.")}
if (Component.options.functional) {console.error("[vue-loader] SettingCreate.vue: functional components are not supported with templates, they should use render functions.")}

/* hot reload */
if (false) {(function () {
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), false)
  if (!hotAPI.compatible) return
  module.hot.accept()
  if (!module.hot.data) {
    hotAPI.createRecord("data-v-2798e748", Component.options)
  } else {
    hotAPI.reload("data-v-2798e748", Component.options)
  }
})()}

module.exports = Component.exports


/***/ }),
/* 193 */
/***/ (function(module, exports, __webpack_require__) {

var Component = __webpack_require__(1)(
  /* script */
  __webpack_require__(264),
  /* template */
  __webpack_require__(353),
  /* scopeId */
  null,
  /* cssModules */
  null
)
Component.options.__file = "C:\\OSPanel\\domains\\ohcasey.ru\\www\\resources\\assets\\js\\components\\Setting\\SettingEdit.vue"
if (Component.esModule && Object.keys(Component.esModule).some(function (key) {return key !== "default" && key !== "__esModule"})) {console.error("named exports are not supported in *.vue files.")}
if (Component.options.functional) {console.error("[vue-loader] SettingEdit.vue: functional components are not supported with templates, they should use render functions.")}

/* hot reload */
if (false) {(function () {
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), false)
  if (!hotAPI.compatible) return
  module.hot.accept()
  if (!module.hot.data) {
    hotAPI.createRecord("data-v-313b346a", Component.options)
  } else {
    hotAPI.reload("data-v-313b346a", Component.options)
  }
})()}

module.exports = Component.exports


/***/ }),
/* 194 */
/***/ (function(module, exports, __webpack_require__) {

var Component = __webpack_require__(1)(
  /* script */
  __webpack_require__(267),
  /* template */
  __webpack_require__(391),
  /* scopeId */
  null,
  /* cssModules */
  null
)
Component.options.__file = "C:\\OSPanel\\domains\\ohcasey.ru\\www\\resources\\assets\\js\\components\\Tag\\Tag.vue"
if (Component.esModule && Object.keys(Component.esModule).some(function (key) {return key !== "default" && key !== "__esModule"})) {console.error("named exports are not supported in *.vue files.")}
if (Component.options.functional) {console.error("[vue-loader] Tag.vue: functional components are not supported with templates, they should use render functions.")}

/* hot reload */
if (false) {(function () {
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), false)
  if (!hotAPI.compatible) return
  module.hot.accept()
  if (!module.hot.data) {
    hotAPI.createRecord("data-v-ec0c2358", Component.options)
  } else {
    hotAPI.reload("data-v-ec0c2358", Component.options)
  }
})()}

module.exports = Component.exports


/***/ }),
/* 195 */
/***/ (function(module, exports, __webpack_require__) {

var Component = __webpack_require__(1)(
  /* script */
  __webpack_require__(268),
  /* template */
  __webpack_require__(335),
  /* scopeId */
  null,
  /* cssModules */
  null
)
Component.options.__file = "C:\\OSPanel\\domains\\ohcasey.ru\\www\\resources\\assets\\js\\components\\Tag\\TagCreate.vue"
if (Component.esModule && Object.keys(Component.esModule).some(function (key) {return key !== "default" && key !== "__esModule"})) {console.error("named exports are not supported in *.vue files.")}
if (Component.options.functional) {console.error("[vue-loader] TagCreate.vue: functional components are not supported with templates, they should use render functions.")}

/* hot reload */
if (false) {(function () {
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), false)
  if (!hotAPI.compatible) return
  module.hot.accept()
  if (!module.hot.data) {
    hotAPI.createRecord("data-v-04bbd070", Component.options)
  } else {
    hotAPI.reload("data-v-04bbd070", Component.options)
  }
})()}

module.exports = Component.exports


/***/ }),
/* 196 */
/***/ (function(module, exports, __webpack_require__) {

var Component = __webpack_require__(1)(
  /* script */
  __webpack_require__(269),
  /* template */
  __webpack_require__(390),
  /* scopeId */
  null,
  /* cssModules */
  null
)
Component.options.__file = "C:\\OSPanel\\domains\\ohcasey.ru\\www\\resources\\assets\\js\\components\\Tag\\TagEdit.vue"
if (Component.esModule && Object.keys(Component.esModule).some(function (key) {return key !== "default" && key !== "__esModule"})) {console.error("named exports are not supported in *.vue files.")}
if (Component.options.functional) {console.error("[vue-loader] TagEdit.vue: functional components are not supported with templates, they should use render functions.")}

/* hot reload */
if (false) {(function () {
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), false)
  if (!hotAPI.compatible) return
  module.hot.accept()
  if (!module.hot.data) {
    hotAPI.createRecord("data-v-e5252504", Component.options)
  } else {
    hotAPI.reload("data-v-e5252504", Component.options)
  }
})()}

module.exports = Component.exports


/***/ }),
/* 197 */,
/* 198 */,
/* 199 */,
/* 200 */,
/* 201 */,
/* 202 */,
/* 203 */,
/* 204 */,
/* 205 */,
/* 206 */,
/* 207 */,
/* 208 */,
/* 209 */,
/* 210 */,
/* 211 */,
/* 212 */,
/* 213 */,
/* 214 */,
/* 215 */,
/* 216 */,
/* 217 */,
/* 218 */,
/* 219 */,
/* 220 */,
/* 221 */,
/* 222 */,
/* 223 */,
/* 224 */,
/* 225 */,
/* 226 */,
/* 227 */,
/* 228 */,
/* 229 */,
/* 230 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


Object.defineProperty(exports, "__esModule", {
    value: true
});

var _vue = __webpack_require__(4);

var _vue2 = _interopRequireDefault(_vue);

var _DataTableMixin = __webpack_require__(12);

var _DataTableMixin2 = _interopRequireDefault(_DataTableMixin);

var _paginator = __webpack_require__(11);

var _paginator2 = _interopRequireDefault(_paginator);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

exports.default = {
    components: {
        paginator: _paginator2.default
    },
    // props: ['option_group_id'],
    data: function data() {
        var sourceRoute = void 0,
            sourceFields = void 0;
        if (this.$route.name == 'product.edit') {
            sourceRoute = lroutes.api.admin.ecommerce.option_group.product_offers.replace(/{.*}/, this.$route.params.id);
            sourceFields = [{ key: 'quantity', name: 'Кол-во', type: 'string' }, { key: 'option_values', name: 'Значения опций', type: 'array' }, { key: 'active', name: 'Активно', type: 'boolean' }];
        } else {
            sourceRoute = lroutes.api.admin.ecommerce.option_group.show.replace(/{.*}/, this.$route.params.id);
            sourceFields = [{ key: 'product_id', name: 'Товар', type: 'string' }, { key: 'quantity', name: 'Кол-во', type: 'string' }, { key: 'weight', name: 'Вес', type: 'string' }, { key: 'option_values', name: 'Значения опций', type: 'array' }, { key: 'active', name: 'Активно', type: 'boolean' }];
        }
        return {
            fields: sourceFields,
            rowsRoute: sourceRoute,
            productForm: this.$parent.$parent.$refs.pForm.form
        };
    },

    mixins: [_DataTableMixin2.default],
    methods: {
        changeActive: function changeActive(row) {
            var _this = this;

            if (this.vuestore.loading) return;
            this.vuestore.loading = true;
            var settings = {
                method: 'post',
                path: lroutes.api.admin.ecommerce.option_group_value.update.replace(/{.*}/, row.id),
                params: {
                    active: !!!row.active
                }
            };
            this.vuestore.request(settings).then(function (response) {
                row.active = !row.active;
                _this.vuestore.loading = false;
            }).catch(function (response) {
                console.error(response);
                _this.vuestore.loading = false;
            });
        },
        generate: function generate() {
            var _this2 = this;

            if (this.vuestore.loading) return;
            this.vuestore.loading = true;
            var settings = {
                method: 'post',
                path: lroutes.api.admin.ecommerce.product.generate.replace(/{.*}/, this.$route.params.id)
            };
            this.vuestore.request(settings).then(function (response) {
                var newOffers = response.data.counts.offers_affter - response.data.counts.offers_before;
                var msg = newOffers > 0 ? "Добавлено " + newOffers + " новых ТП на основе значений опций товара." : "Новые ТП не найдены.";
                window.alert(msg);
                _this2.vuestore.loading = false;
            }).catch(function (response) {
                console.error(response);
                _this2.vuestore.loading = false;
            });
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

/***/ }),
/* 231 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


Object.defineProperty(exports, "__esModule", {
    value: true
});

var _vue = __webpack_require__(4);

var _vue2 = _interopRequireDefault(_vue);

var _vuestore = __webpack_require__(5);

var _vuestore2 = _interopRequireDefault(_vuestore);

var _form = __webpack_require__(13);

var _form2 = _interopRequireDefault(_form);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

exports.default = {
    data: function data() {
        return {
            lroutes: lroutes,
            vuestore: _vuestore2.default,
            data: [], //recived additinol data from server
            form: new _form2.default({
                product_id: '',
                quantity: '',
                weight: '',
                options: '',
                active: true
            }),
            selectsData: {}
        };
    },
    created: function created() {
        var _this = this;

        if (this.$route.params.id) {
            var path = lroutes.api.admin.ecommerce.offer.edit.replace(/{.*}/, this.$route.params.id);
        } else {
            if (this.$route.query && this.$route.query.product_id) {
                this.form.product_id = this.$route.query.product_id;
                var path = lroutes.api.admin.ecommerce.offer.create + '?product_id=' + this.form.product_id;
            } else return;
        }
        this.vuestore.loading = true;
        this.form.get(path).then(function (response) {
            if (response.data) {
                _this.data = response.data.data;
                if (_this.data.offer_values) {
                    for (var v in _this.data.offer_values) {
                        _vue2.default.set(_this.selectsData, _this.data.offer_values[v].option_id, _this.data.offer_values[v].id);
                    }
                } else {
                    for (var _v in Object.keys(_this.data.values)) {
                        _vue2.default.set(_this.selectsData, Object.keys(_this.data.values)[_v], '');
                    }
                }
            }
            _this.vuestore.loading = false;
        }).catch(function (response) {
            console.error(response);
            _this.vuestore.loading = false;
        });
    },

    methods: {
        onSubmit: function onSubmit() {
            var _this2 = this;

            if (this.vuestore.loading) return;else this.vuestore.loading = true;
            var opt = [];
            for (var s in this.selectsData) {
                if (this.selectsData[s]) opt.push(this.selectsData[s]);
            }
            this.form.options = opt;

            if (this.$route.params.id) {
                var path = lroutes.api.admin.ecommerce.offer.update.replace(/{.*}/, this.$route.params.id);
                this.form.put(path).then(function (response) {
                    _this2.vuestore.loading = false;
                    _this2.$root.$router.push({ name: 'product.edit', params: { id: _this2.form.product_id } });
                }).catch(function (response) {
                    console.error(response);
                    _this2.vuestore.loading = false;
                });
            } else {
                var _path = lroutes.api.admin.ecommerce.offer.store;
                this.form.post(_path).then(function (response) {
                    _this2.vuestore.loading = false;
                    _this2.$root.$router.push({ name: 'product.edit', params: { id: _this2.form.product_id } });
                }).catch(function (response) {
                    console.error(response);
                    _this2.vuestore.loading = false;
                });
            }
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

/***/ }),
/* 232 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


Object.defineProperty(exports, "__esModule", {
    value: true
});

var _Layout = __webpack_require__(2);

var _Layout2 = _interopRequireDefault(_Layout);

var _DataTable = __webpack_require__(157);

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
/* 233 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


Object.defineProperty(exports, "__esModule", {
    value: true
});

var _Layout = __webpack_require__(2);

var _Layout2 = _interopRequireDefault(_Layout);

var _Form = __webpack_require__(158);

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
        OfferForm: _Form2.default
    }
};

/***/ }),
/* 234 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


Object.defineProperty(exports, "__esModule", {
    value: true
});

var _Layout = __webpack_require__(2);

var _Layout2 = _interopRequireDefault(_Layout);

var _Form = __webpack_require__(158);

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
        OfferForm: _Form2.default
    }
};

/***/ }),
/* 235 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


Object.defineProperty(exports, "__esModule", {
    value: true
});

var _vue = __webpack_require__(4);

var _vue2 = _interopRequireDefault(_vue);

var _DataTableMixin = __webpack_require__(12);

var _DataTableMixin2 = _interopRequireDefault(_DataTableMixin);

var _paginator = __webpack_require__(11);

var _paginator2 = _interopRequireDefault(_paginator);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

exports.default = {
    components: {
        paginator: _paginator2.default
    },
    data: function data() {
        return {
            fields: [{ key: 'name', name: 'Название', type: 'string' }],
            rowsRoute: lroutes.api.admin.ecommerce.option_group.index
        };
    },

    mixins: [_DataTableMixin2.default]
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

/***/ }),
/* 236 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


Object.defineProperty(exports, "__esModule", {
    value: true
});

var _vuestore = __webpack_require__(5);

var _vuestore2 = _interopRequireDefault(_vuestore);

var _form = __webpack_require__(13);

var _form2 = _interopRequireDefault(_form);

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

exports.default = {
    data: function data() {
        return {
            lroutes: lroutes,
            vuestore: _vuestore2.default,
            data: [], //recived additinol data from server
            form: new _form2.default({
                name: '',
                delivery_info: '',
                options: []
            }),
            selected_option: []
        };
    },
    created: function created() {
        var _this = this;

        if (this.$route.params.id) {
            var path = lroutes.api.admin.ecommerce.option_group.edit.replace(/{.*}/, this.$route.params.id);
        } else {
            path = lroutes.api.admin.ecommerce.option_group.create;
        }
        this.vuestore.loading = true;
        this.form.get(path).then(function (response) {
            if (response.data) {
                _this.data = response.data.data;
                if (_this.form.options) {
                    _this.selected_option = _this.form.options;
                }
            }
            _this.vuestore.loading = false;
        }).catch(function (response) {
            console.error(response);
            _this.vuestore.loading = false;
        });
    },

    methods: {
        onSubmit: function onSubmit() {
            var _this2 = this;

            if (this.vuestore.loading) return;else this.vuestore.loading = true;
            if (this.$route.params.id) {
                var path = lroutes.api.admin.ecommerce.option_group.update.replace(/{.*}/, this.$route.params.id);
                this.form.put(path).then(function (response) {
                    // console.log(response)
                    _this2.vuestore.loading = false;
                    _this2.$root.$router.push({ name: 'option_group.index' });
                }).catch(function (response) {
                    console.error(response);
                    _this2.vuestore.loading = false;
                });
            } else {
                var _path = lroutes.api.admin.ecommerce.option_group.store;
                this.form.post(_path).then(function (response) {
                    // console.log(response)
                    _this2.vuestore.loading = false;
                    _this2.$root.$router.push({ name: 'option_group.index' });
                }).catch(function (response) {
                    console.error(response);
                    _this2.vuestore.loading = false;
                });
            }
        }
    },
    watch: {
        selected_option: function selected_option() {
            this.form.options = this.selected_option;
        }
    }
};

/***/ }),
/* 237 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


Object.defineProperty(exports, "__esModule", {
    value: true
});

var _Layout = __webpack_require__(2);

var _Layout2 = _interopRequireDefault(_Layout);

var _DataTable = __webpack_require__(323);

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
/* 238 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


Object.defineProperty(exports, "__esModule", {
    value: true
});

var _Layout = __webpack_require__(2);

var _Layout2 = _interopRequireDefault(_Layout);

var _Form = __webpack_require__(159);

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
        OptionGroupForm: _Form2.default
    }
};

/***/ }),
/* 239 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


Object.defineProperty(exports, "__esModule", {
    value: true
});

var _Layout = __webpack_require__(2);

var _Layout2 = _interopRequireDefault(_Layout);

var _Form = __webpack_require__(159);

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
        OptionGroupForm: _Form2.default
    }
};

/***/ }),
/* 240 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


Object.defineProperty(exports, "__esModule", {
    value: true
});

var _vue = __webpack_require__(4);

var _vue2 = _interopRequireDefault(_vue);

var _DataTableMixin = __webpack_require__(12);

var _DataTableMixin2 = _interopRequireDefault(_DataTableMixin);

var _paginator = __webpack_require__(11);

var _paginator2 = _interopRequireDefault(_paginator);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

exports.default = {
    components: {
        paginator: _paginator2.default
    },
    data: function data() {
        return {
            fields: [{ key: 'value', name: 'Значение', type: 'string' }, { key: 'title', name: 'Название', type: 'string' }, { key: 'order', name: 'Порядок', type: 'string' }],
            rowsRoute: lroutes.api.admin.ecommerce.option_value.index,
            categoriesRoute: lroutes.api.admin.ecommerce.option.list,
            // map keys for select options value and text
            categoryTextKey: 'name'
        };
    },

    mixins: [_DataTableMixin2.default],
    created: function created() {
        this.getCategories();
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

/***/ }),
/* 241 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


Object.defineProperty(exports, "__esModule", {
    value: true
});

var _vuestore = __webpack_require__(5);

var _vuestore2 = _interopRequireDefault(_vuestore);

var _form = __webpack_require__(13);

var _form2 = _interopRequireDefault(_form);

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

exports.default = {
    data: function data() {
        return {
            lroutes: lroutes,
            vuestore: _vuestore2.default,
            data: [], //recived additinol data from server
            selected_option: null,
            form: new _form2.default({
                option_id: '',
                value: '',
                title: '',
                description: '',
                image: '',
                order: ''
            })
        };
    },
    created: function created() {
        var _this = this;

        if (this.$route.params.id) {
            var path = lroutes.api.admin.ecommerce.option_value.edit.replace(/{.*}/, this.$route.params.id);
        } else {
            path = lroutes.api.admin.ecommerce.option_value.create;
        }
        this.vuestore.loading = true;
        this.form.get(path).then(function (response) {
            if (response.data) {
                _this.data = response.data.data;
            }
            if (_this.form.option_id) {
                _this.selected_option = _this.form.option_id;
            }
            _this.vuestore.loading = false;
        }).catch(function (response) {
            console.error(response);
            _this.vuestore.loading = false;
        });
    },

    methods: {
        onSubmit: function onSubmit() {
            var _this2 = this;

            if (this.vuestore.loading) return;else this.vuestore.loading = true;
            if (this.$route.params.id) {
                var path = lroutes.api.admin.ecommerce.option_value.update.replace(/{.*}/, this.$route.params.id);
                this.form.put(path).then(function (response) {
                    // console.log(response)
                    _this2.vuestore.loading = false;
                    _this2.$root.$router.push({ name: 'option_value.index' });
                }).catch(function (response) {
                    console.error(response);
                    _this2.vuestore.loading = false;
                });
            } else {
                var _path = lroutes.api.admin.ecommerce.option_value.store;
                this.form.post(_path).then(function (response) {
                    // console.log(response)
                    _this2.vuestore.loading = false;
                    _this2.$root.$router.push({ name: 'option_value.index' });
                }).catch(function (response) {
                    console.error(response);
                    _this2.vuestore.loading = false;
                });
            }
        }
    },
    watch: {
        selected_option: function selected_option() {
            this.form.option_id = this.selected_option;
        }
    }
};

/***/ }),
/* 242 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


Object.defineProperty(exports, "__esModule", {
    value: true
});

var _Layout = __webpack_require__(2);

var _Layout2 = _interopRequireDefault(_Layout);

var _DataTable = __webpack_require__(324);

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
/* 243 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


Object.defineProperty(exports, "__esModule", {
    value: true
});

var _Layout = __webpack_require__(2);

var _Layout2 = _interopRequireDefault(_Layout);

var _Form = __webpack_require__(160);

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
        OptionvalueForm: _Form2.default
    }
};

/***/ }),
/* 244 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


Object.defineProperty(exports, "__esModule", {
    value: true
});

var _Layout = __webpack_require__(2);

var _Layout2 = _interopRequireDefault(_Layout);

var _Form = __webpack_require__(160);

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
        OptionvalueForm: _Form2.default
    }
};

/***/ }),
/* 245 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


Object.defineProperty(exports, "__esModule", {
    value: true
});

var _vue = __webpack_require__(4);

var _vue2 = _interopRequireDefault(_vue);

var _DataTableMixin = __webpack_require__(12);

var _DataTableMixin2 = _interopRequireDefault(_DataTableMixin);

var _paginator = __webpack_require__(11);

var _paginator2 = _interopRequireDefault(_paginator);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

exports.default = {
    components: {
        paginator: _paginator2.default
    },
    data: function data() {
        return {
            fields: [{ key: 'key', name: 'Ключ', type: 'string' }, { key: 'name', name: 'Название', type: 'string' }, { key: 'order', name: 'Порядок', type: 'string' }],
            rowsRoute: lroutes.api.admin.ecommerce.option.index
        };
    },

    mixins: [_DataTableMixin2.default]
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

/***/ }),
/* 246 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


Object.defineProperty(exports, "__esModule", {
    value: true
});

var _vuestore = __webpack_require__(5);

var _vuestore2 = _interopRequireDefault(_vuestore);

var _form = __webpack_require__(13);

var _form2 = _interopRequireDefault(_form);

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

exports.default = {
    data: function data() {
        return {
            lroutes: lroutes,
            vuestore: _vuestore2.default,
            data: [], //recived additinol data from server
            form: new _form2.default({
                name: '',
                key: '',
                order: ''
            })
        };
    },
    created: function created() {
        var _this = this;

        if (this.$route.params.id) {
            var path = lroutes.api.admin.ecommerce.option.edit.replace(/{.*}/, this.$route.params.id);
            this.vuestore.loading = true;
            this.form.get(path).then(function (response) {
                if (response.data) {
                    _this.data = response.data.data;
                }
                _this.vuestore.loading = false;
            }).catch(function (response) {
                console.error(response);
                _this.vuestore.loading = false;
            });
        }
    },

    methods: {
        onSubmit: function onSubmit() {
            var _this2 = this;

            if (this.vuestore.loading) return;else this.vuestore.loading = true;
            if (this.$route.params.id) {
                var path = lroutes.api.admin.ecommerce.option.update.replace(/{.*}/, this.$route.params.id);
                this.form.put(path).then(function (response) {
                    // console.log(response)
                    _this2.vuestore.loading = false;
                    _this2.$root.$router.push({ name: 'option.index' });
                }).catch(function (response) {
                    console.error(response);
                    _this2.vuestore.loading = false;
                });
            } else {
                var _path = lroutes.api.admin.ecommerce.option.store;
                this.form.post(_path).then(function (response) {
                    // console.log(response)
                    _this2.vuestore.loading = false;
                    _this2.$root.$router.push({ name: 'option.index' });
                }).catch(function (response) {
                    console.error(response);
                    _this2.vuestore.loading = false;
                });
            }
        }
    }
};

/***/ }),
/* 247 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


Object.defineProperty(exports, "__esModule", {
    value: true
});

var _Layout = __webpack_require__(2);

var _Layout2 = _interopRequireDefault(_Layout);

var _DataTable = __webpack_require__(325);

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
/* 248 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


Object.defineProperty(exports, "__esModule", {
    value: true
});

var _Layout = __webpack_require__(2);

var _Layout2 = _interopRequireDefault(_Layout);

var _Form = __webpack_require__(161);

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
        OptionForm: _Form2.default
    }
};

/***/ }),
/* 249 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


Object.defineProperty(exports, "__esModule", {
    value: true
});

var _Layout = __webpack_require__(2);

var _Layout2 = _interopRequireDefault(_Layout);

var _Form = __webpack_require__(161);

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
        OptionForm: _Form2.default
    }
};

/***/ }),
/* 250 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


Object.defineProperty(exports, "__esModule", {
    value: true
});

var _vue = __webpack_require__(4);

var _vue2 = _interopRequireDefault(_vue);

var _DataTableMixin = __webpack_require__(12);

var _DataTableMixin2 = _interopRequireDefault(_DataTableMixin);

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

exports.default = {
    data: function data() {
        return {
            fields: [{ key: 'title', name: 'Заголовок', type: 'string' }, { key: 'slug', name: 'URL', type: 'string' }],
            rowsRoute: lroutes.api.admin.ecommerce.page.index
        };
    },

    mixins: [_DataTableMixin2.default]
};

/***/ }),
/* 251 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


Object.defineProperty(exports, "__esModule", {
    value: true
});

var _vuestore = __webpack_require__(5);

var _vuestore2 = _interopRequireDefault(_vuestore);

var _form = __webpack_require__(13);

var _form2 = _interopRequireDefault(_form);

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

exports.default = {
    mounted: function mounted() {
        var self = this;
        var contents = self.form.content;
        CKEDITOR.replace('ckeditor', {
            filebrowserBrowseUrl: "../../../../../js/kcfinder/browse.php?opener=ckeditor&type=files",
            filebrowserImageBrowseUrl: "../../../../../js/kcfinder/browse.php?opener=ckeditor&type=images",
            filebrowserFlashBrowseUrl: "../../../../../js/kcfinder/browse.php?opener=ckeditor&type=flash",
            filebrowserUploadUrl: "../../../../../js/kcfinder/upload.php?opener=ckeditor&type=files",
            filebrowserImageUploadUrl: "../../../../../js/kcfinder/upload.php?opener=ckeditor&type=images",
            filebrowserFlashUploadUrl: "../../../../../js/kcfinder/upload.php?opener=ckeditor&type=flash"
        });
    },
    updated: function updated() {
        if (CKEDITOR.instances.ckeditor.getData() == "") {
            var self = this;
            var contents = self.form.content;
            CKEDITOR.instances.ckeditor.setData(contents);
            CKEDITOR.replace('ckeditor', {
                filebrowserBrowseUrl: "../../../../../js/kcfinder/browse.php?opener=ckeditor&type=files",
                filebrowserImageBrowseUrl: "../../../../../js/kcfinder/browse.php?opener=ckeditor&type=images",
                filebrowserFlashBrowseUrl: "../../../../../js/kcfinder/browse.php?opener=ckeditor&type=flash",
                filebrowserUploadUrl: "../../../../../js/kcfinder/upload.php?opener=ckeditor&type=files",
                filebrowserImageUploadUrl: "../../../../../js/kcfinder/upload.php?opener=ckeditor&type=images",
                filebrowserFlashUploadUrl: "../../../../../js/kcfinder/upload.php?opener=ckeditor&type=flash"
            }).setData(contents);
        }
    },
    data: function data() {
        return {
            lroutes: lroutes,
            vuestore: _vuestore2.default,
            data: [], //recived additinol data from server
            form: new _form2.default({
                title: '',
                slug: '',
                h1: '',
                keywords: '',
                description: '',
                content: ''
            })
        };
    },
    created: function created() {
        var _this = this;

        if (this.$route.params.id) {
            var path = lroutes.api.admin.ecommerce.page.edit.replace(/{.*}/, this.$route.params.id);
            this.vuestore.loading = true;
            this.form.get(path).then(function (response) {
                if (response.data) {
                    _this.data = response.data.data;
                }
                _this.vuestore.loading = false;
            }).catch(function (response) {
                console.error(response);
                _this.vuestore.loading = false;
            });
        }
    },

    methods: {
        onSubmit: function onSubmit() {
            var _this2 = this;

            if (this.vuestore.loading) return;else this.vuestore.loading = true;
            this.form.content = CKEDITOR.instances.ckeditor.getData();
            if (this.$route.params.id) {
                var path = lroutes.api.admin.ecommerce.page.update.replace(/{.*}/, this.$route.params.id);
                this.form.put(path).then(function (response) {
                    // console.log(response)
                    _this2.vuestore.loading = false;
                    _this2.$root.$router.push({ name: 'page.index' });
                }).catch(function (response) {
                    console.error(response);
                    _this2.vuestore.loading = false;
                });
            } else {
                var _path = lroutes.api.admin.ecommerce.page.store;
                this.form.post(_path).then(function (response) {
                    // console.log(response)
                    _this2.vuestore.loading = false;
                    _this2.$root.$router.push({ name: 'page.index' });
                }).catch(function (response) {
                    console.error(response);
                    _this2.vuestore.loading = false;
                });
            }
        }
    }
};

/***/ }),
/* 252 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


Object.defineProperty(exports, "__esModule", {
    value: true
});

var _Layout = __webpack_require__(2);

var _Layout2 = _interopRequireDefault(_Layout);

var _DataTable = __webpack_require__(326);

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
/* 253 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


Object.defineProperty(exports, "__esModule", {
    value: true
});

var _Layout = __webpack_require__(2);

var _Layout2 = _interopRequireDefault(_Layout);

var _Form = __webpack_require__(162);

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
        PageForm: _Form2.default
    }
};

/***/ }),
/* 254 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


Object.defineProperty(exports, "__esModule", {
    value: true
});

var _Layout = __webpack_require__(2);

var _Layout2 = _interopRequireDefault(_Layout);

var _Form = __webpack_require__(162);

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
        PageForm: _Form2.default
    }
};

/***/ }),
/* 255 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


Object.defineProperty(exports, "__esModule", {
    value: true
});

var _vue = __webpack_require__(4);

var _vue2 = _interopRequireDefault(_vue);

var _DataTableMixin = __webpack_require__(12);

var _DataTableMixin2 = _interopRequireDefault(_DataTableMixin);

var _paginator = __webpack_require__(11);

var _paginator2 = _interopRequireDefault(_paginator);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

exports.default = {
    components: {
        paginator: _paginator2.default
    },
    data: function data() {
        return {
            fields: [{ key: 'code', name: 'Артикул', type: 'string' }, { key: 'name', name: 'Название', type: 'string' }, { key: 'price_string', name: 'Цена', type: 'string' }, { key: 'categories', name: 'Категории', type: 'array' }, { key: 'active', name: 'Активен', type: 'boolean' }, { key: 'order', name: 'Порядок', type: 'string' }],
            rowsRoute: lroutes.api.admin.ecommerce.product.index,
            categoriesRoute: lroutes.api.admin.ecommerce.category.list
        };
    },

    mixins: [_DataTableMixin2.default],
    created: function created() {
        this.getCategories();
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

/***/ }),
/* 256 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


Object.defineProperty(exports, "__esModule", {
    value: true
});

var _axios = __webpack_require__(6);

var _axios2 = _interopRequireDefault(_axios);

var _vuestore = __webpack_require__(5);

var _vuestore2 = _interopRequireDefault(_vuestore);

var _quillEditor = __webpack_require__(14);

var _quillEditor2 = _interopRequireDefault(_quillEditor);

var _dropzone = __webpack_require__(312);

var _dropzone2 = _interopRequireDefault(_dropzone);

var _form = __webpack_require__(13);

var _form2 = _interopRequireDefault(_form);

__webpack_require__(313);

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

exports.default = {
    props: ['is_create'],
    data: function data() {
        return {
            lroutes: lroutes,
            vuestore: _vuestore2.default,
            selected: [],
            selected_background: null,
            selected_option_group_id: null,
            option_groups: [],
            default_selected: [],
            selected_related_product: null,
            selected_tag: null,
            photos: [],
            data: {}, //recived additinol data from server
            form: new _form2.default({
                name: '',
                background_id: '',
                code: '',
                title: '',
                keywords: '',
                h1: '',
                description: '',
                price: '',
                discount: '',
                order: '',
                active: false,
                hit: false,
                sale: false,
                bestseller: false,
                maket_photo: true,
                categories: [],
                related: [],
                tags: [],
                option_group_id: null,
                option_group: {
                    name: ''
                },
                htmlDescription: ''
            })
        };
    },

    components: {
        quillEditor: _quillEditor2.default
    },
    created: function created() {
        var _this = this;

        var path;
        if (this.$route.params.id) {
            path = lroutes.api.admin.ecommerce.product.edit.replace(/{.*}/, this.$route.params.id);
        } else {
            path = lroutes.api.admin.ecommerce.product.create;
        }
        this.vuestore.loading = true;
        this.form.get(path).then(function (response) {
            if (response.data) {
                _this.data = response.data.data;
                if (_this.form.categories && _this.form.categories.length) {
                    _this.form.categories.forEach(function (c) {
                        _this.selected.push(c.id);
                    });
                    _this.default_selected = _this.selected;
                }
                if (_this.form.background_id) {
                    _this.selected_background = _this.form.background_id;
                }
            }
            _this.vuestore.loading = false;
            _this.getPhotos(_this.data.id);
        }).catch(function (response) {
            console.error(response);
            _this.vuestore.loading = false;
        });
    },
    mounted: function mounted() {
        var _this2 = this;

        _dropzone2.default.autoDiscover = false;
        var dzsettings = {};
        dzsettings.url = lroutes.api.admin.ecommerce.photo.store;
        dzsettings.paramName = 'photo';
        dzsettings.maxFilesize = 10;
        dzsettings.maxFiles = 10;
        // dzsettings.addRemoveLinks = true
        this.dz = new _dropzone2.default("#dz", dzsettings);

        this.dz.on('sending', function (file, response, formData) {
            if (_this2.$route.params.id) {
                formData.append('id', _this2.$route.params.id);
            }
        });
        this.dz.on('success', function (file, response) {
            _this2.getPhotos(_this2.data.id);
            _this2.dz.removeAllFiles();
        });
        var $tagsSelect = $('.js-data-tags-ajax').select2({
            placeholder: "Выберите тег",
            minimumInputLength: 1,
            ajax: {
                url: "/api/admin/ecommerce/tag",
                dataType: 'json',
                cache: true,
                delay: 250,
                data: function data(params) {
                    return { search: params.term };
                },
                processResults: function processResults(data, params) {
                    return { results: data.data };
                }
            },
            escapeMarkup: function escapeMarkup(markup) {
                return markup;
            },
            templateResult: function templateResult(product) {
                return '<div>' + product.name + '</div>';
            },
            templateSelection: function templateSelection(product) {
                return product.name;
            }
        });

        $tagsSelect.on("select2:select", function (e) {
            _this2.selected_tag = e.params.data;
        });

        var $relatedSelect = $(".js-data-example-ajax").select2({
            placeholder: "Выберите сопутствующий товар",
            minimumInputLength: 1,
            ajax: {
                url: "/api/admin/ecommerce/product",
                dataType: 'json',
                cache: true,
                delay: 250,
                data: function data(params) {
                    return { search: params.term };
                },
                processResults: function processResults(data, params) {
                    return { results: data.data };
                }
            },
            escapeMarkup: function escapeMarkup(markup) {
                return markup;
            },
            templateResult: function templateResult(product) {
                return '<div>' + product.name + '</div>';
            },
            templateSelection: function templateSelection(product) {
                return product.name;
            }
        });

        $relatedSelect.on("select2:select", function (e) {
            _this2.selected_related_product = e.params.data;
        });
    },

    methods: {
        onSubmit: function onSubmit() {
            var _this3 = this;

            if (this.vuestore.loading) return;else this.vuestore.loading = true;

            if (this.form.related) {
                this.form.related = this.form.related.map(function (item) {
                    return item.id;
                });
            }

            if (this.form.tags) {
                this.form.tags = this.form.tags.map(function (item) {
                    return item.id;
                });
            }

            if (this.$route.params.id) {
                var path = lroutes.api.admin.ecommerce.product.update.replace(/{.*}/, this.$route.params.id);
                this.form.put(path).then(function (response) {
                    _this3.vuestore.loading = false;
                    _this3.$root.$router.push({ name: 'product.index' });
                }).catch(function (response) {
                    console.error(response);
                    _this3.vuestore.loading = false;
                });
            } else {
                var _path = lroutes.api.admin.ecommerce.product.store;
                this.form.post(_path).then(function (response) {
                    _this3.vuestore.loading = false;
                    _this3.$root.$router.push({ name: 'product.index' });
                }).catch(function (response) {
                    console.error(response);
                    _this3.vuestore.loading = false;
                });
            }
        },
        resetCategories: function resetCategories() {
            this.selected = this.default_selected;
        },
        getPhotos: function getPhotos(id) {
            var _this4 = this;

            var settings = {};
            settings.path = lroutes.api.admin.ecommerce.photo.index;
            settings.method = 'get';
            settings.params = { id: id };
            this.vuestore.loading = true;
            this.vuestore.request(settings).then(function (response) {
                if (response.data.length) {
                    _this4.photos = response.data;
                    // server return first photo as default
                    _this4.setDefault(0);
                }
                _this4.vuestore.loading = false;
            }).catch(function (response) {
                return console.error(response);
            });
        },
        removeRelated: function removeRelated(item) {
            var removeIndex = this.form.related.map(function (item) {
                return item.id;
            }).indexOf(item);
            this.form.related.splice(removeIndex, 1);
        },
        addRelated: function addRelated() {
            if (!this.form.related) this.form.related = [];
            this.form.related.push(this.selected_related_product);
        },
        removeTag: function removeTag(item) {
            var removeIndex = this.form.tags.map(function (item) {
                return item.id;
            }).indexOf(item);
            this.form.tags.splice(removeIndex, 1);
        },
        addTag: function addTag() {
            if (!this.form.tags) this.form.tags = [];
            this.form.tags.push(this.selected_tag);
        },
        removePhoto: function removePhoto(photo) {
            var _this5 = this;

            if (this.vuestore.loading) return;
            var settings = {};
            settings.path = lroutes.api.admin.ecommerce.photo.destroy.replace(/{.*}/, photo.id);
            settings.method = 'delete';
            this.vuestore.loading = true;
            this.vuestore.request(settings).then(function (response) {
                _this5.photos = _this5.photos.filter(function (item) {
                    return item.id != photo.id;
                });
                _this5.vuestore.loading = false;
                if (photo.default) _this5.getPhotos(_this5.data.id);
            }).catch(function (response) {
                console.error(response);
                _this5.vuestore.loading = false;
            });
        },
        clickDefault: function clickDefault(id, index) {
            var _this6 = this;

            if (this.vuestore.loading) return;
            this.setDefault(index);
            var settings = {};
            settings.path = lroutes.api.admin.ecommerce.photo.update.replace(/{.*}/, id);
            settings.method = 'put';
            this.vuestore.loading = true;
            this.vuestore.request(settings).then(function (response) {
                _this6.vuestore.loading = false;
            }).catch(function (response) {
                console.error(response);
                _this6.vuestore.loading = false;
            });
        },
        setDefault: function setDefault(index) {
            var _this7 = this;

            this.photos.forEach(function (item, i) {
                _this7.$set(item, 'default', i == index);
            });
        }
    },
    watch: {
        selected: function selected() {
            this.form.categories = this.selected;
        },
        selected_background: function selected_background() {
            this.form.background_id = this.selected_background;
        },
        selected_option_group_id: function selected_option_group_id() {
            this.form.option_group_id = this.selected_option_group_id;
        }
    }
};

/***/ }),
/* 257 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


Object.defineProperty(exports, "__esModule", {
    value: true
});

var _Layout = __webpack_require__(2);

var _Layout2 = _interopRequireDefault(_Layout);

var _DataTable = __webpack_require__(327);

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
/* 258 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


Object.defineProperty(exports, "__esModule", {
    value: true
});

var _Layout = __webpack_require__(2);

var _Layout2 = _interopRequireDefault(_Layout);

var _Form = __webpack_require__(163);

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
/* 259 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


Object.defineProperty(exports, "__esModule", {
    value: true
});

var _Layout = __webpack_require__(2);

var _Layout2 = _interopRequireDefault(_Layout);

var _Form = __webpack_require__(163);

var _Form2 = _interopRequireDefault(_Form);

var _DataTable = __webpack_require__(157);

var _DataTable2 = _interopRequireDefault(_DataTable);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

exports.default = {
    components: {
        Layout: _Layout2.default,
        ProductForm: _Form2.default,
        Offers: _DataTable2.default
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

/***/ }),
/* 260 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


Object.defineProperty(exports, "__esModule", {
    value: true
});

var _vue = __webpack_require__(4);

var _vue2 = _interopRequireDefault(_vue);

var _DataTableMixin = __webpack_require__(12);

var _DataTableMixin2 = _interopRequireDefault(_DataTableMixin);

var _paginator = __webpack_require__(11);

var _paginator2 = _interopRequireDefault(_paginator);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

exports.default = {
    components: {
        paginator: _paginator2.default
    },
    data: function data() {
        return {
            fields: [{ key: 'key', name: 'Ключ', type: 'string' }, { key: 'title', name: 'Название', type: 'string' }, { key: 'value', name: 'Значение', type: 'string' }, { key: 'type', name: 'Тип', type: 'string' }, { key: 'group', name: 'Группировка', type: 'string' }],
            rowsRoute: lroutes.api.admin.ecommerce.setting.index
        };
    },

    mixins: [_DataTableMixin2.default]
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

/***/ }),
/* 261 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


Object.defineProperty(exports, "__esModule", {
    value: true
});

var _vuestore = __webpack_require__(5);

var _vuestore2 = _interopRequireDefault(_vuestore);

var _form = __webpack_require__(13);

var _form2 = _interopRequireDefault(_form);

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

exports.default = {
    data: function data() {
        return {
            lroutes: lroutes,
            vuestore: _vuestore2.default,
            data: [], //recived additinol data from server
            form: new _form2.default({
                title: '',
                value: '',
                key: '',
                type: '',
                group: ''
            })
        };
    },
    created: function created() {
        var _this = this;

        if (this.$route.params.id) {
            var path = lroutes.api.admin.ecommerce.setting.edit.replace(/{.*}/, this.$route.params.id);
            this.vuestore.loading = true;
            this.form.get(path).then(function (response) {
                if (response.data) {
                    _this.data = response.data.data;
                }
                _this.vuestore.loading = false;
            }).catch(function (response) {
                console.error(response);
                _this.vuestore.loading = false;
            });
        }
    },

    methods: {
        onSubmit: function onSubmit() {
            var _this2 = this;

            if (this.vuestore.loading) return;else this.vuestore.loading = true;
            if (this.$route.params.id) {
                var path = lroutes.api.admin.ecommerce.setting.update.replace(/{.*}/, this.$route.params.id);
                this.form.put(path).then(function (response) {
                    // console.log(response)
                    _this2.vuestore.loading = false;
                    _this2.$root.$router.push({ name: 'setting.index' });
                }).catch(function (response) {
                    console.error(response);
                    _this2.vuestore.loading = false;
                });
            } else {
                var _path = lroutes.api.admin.ecommerce.setting.store;
                this.form.post(_path).then(function (response) {
                    // console.log(response)
                    _this2.vuestore.loading = false;
                    _this2.$root.$router.push({ name: 'setting.index' });
                }).catch(function (response) {
                    console.error(response);
                    _this2.vuestore.loading = false;
                });
            }
        }
    }
};

/***/ }),
/* 262 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


Object.defineProperty(exports, "__esModule", {
    value: true
});

var _Layout = __webpack_require__(2);

var _Layout2 = _interopRequireDefault(_Layout);

var _DataTable = __webpack_require__(328);

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
/* 263 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


Object.defineProperty(exports, "__esModule", {
    value: true
});

var _Layout = __webpack_require__(2);

var _Layout2 = _interopRequireDefault(_Layout);

var _Form = __webpack_require__(164);

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
        SettingForm: _Form2.default
    }
};

/***/ }),
/* 264 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


Object.defineProperty(exports, "__esModule", {
    value: true
});

var _Layout = __webpack_require__(2);

var _Layout2 = _interopRequireDefault(_Layout);

var _Form = __webpack_require__(164);

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
        SettingForm: _Form2.default
    }
};

/***/ }),
/* 265 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


Object.defineProperty(exports, "__esModule", {
    value: true
});

var _vue = __webpack_require__(4);

var _vue2 = _interopRequireDefault(_vue);

var _DataTableMixin = __webpack_require__(12);

var _DataTableMixin2 = _interopRequireDefault(_DataTableMixin);

var _paginator = __webpack_require__(11);

var _paginator2 = _interopRequireDefault(_paginator);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

exports.default = {
    components: {
        paginator: _paginator2.default
    },
    data: function data() {
        return {
            fields: [{ key: 'name', name: 'Название', type: 'string' }, { key: 'slug', name: 'Slug', type: 'string' }, { key: 'title', name: 'Title', type: 'string' }, { key: 'h1', name: 'H1', type: 'string' }, { key: 'keywords', name: 'Keywords', type: 'string' }, { key: 'description', name: 'Description', type: 'string' }, { key: 'order', name: 'Порядок', type: 'string' }],
            rowsRoute: lroutes.api.admin.ecommerce.tag.index
        };
    },

    mixins: [_DataTableMixin2.default]
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

/***/ }),
/* 266 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


Object.defineProperty(exports, "__esModule", {
    value: true
});

var _vuestore = __webpack_require__(5);

var _vuestore2 = _interopRequireDefault(_vuestore);

var _form = __webpack_require__(13);

var _form2 = _interopRequireDefault(_form);

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

exports.default = {
    data: function data() {
        return {
            lroutes: lroutes,
            vuestore: _vuestore2.default,
            data: [], //recived additinol data from server
            form: new _form2.default({
                name: '',
                slug: '',
                title: '',
                h1: '',
                keywords: '',
                description: '',
                order: ''
            })
        };
    },
    created: function created() {
        var _this = this;

        if (this.$route.params.id) {
            var path = lroutes.api.admin.ecommerce.tag.edit.replace(/{.*}/, this.$route.params.id);
            this.vuestore.loading = true;
            this.form.get(path).then(function (response) {
                if (response.data) {
                    _this.data = response.data.data;
                }
                _this.vuestore.loading = false;
            }).catch(function (response) {
                console.error(response);
                _this.vuestore.loading = false;
            });
        }
    },

    methods: {
        onSubmit: function onSubmit() {
            var _this2 = this;

            if (this.vuestore.loading) return;else this.vuestore.loading = true;
            if (this.$route.params.id) {
                var path = lroutes.api.admin.ecommerce.tag.update.replace(/{.*}/, this.$route.params.id);
                this.form.put(path).then(function (response) {
                    // console.log(response)
                    _this2.vuestore.loading = false;
                    _this2.$root.$router.push({ name: 'tag.index' });
                }).catch(function (response) {
                    console.error(response);
                    _this2.vuestore.loading = false;
                });
            } else {
                var _path = lroutes.api.admin.ecommerce.tag.store;
                this.form.post(_path).then(function (response) {
                    // console.log(response)
                    _this2.vuestore.loading = false;
                    _this2.$root.$router.push({ name: 'tag.index' });
                }).catch(function (response) {
                    console.error(response);
                    _this2.vuestore.loading = false;
                });
            }
        }
    }
};

/***/ }),
/* 267 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


Object.defineProperty(exports, "__esModule", {
    value: true
});

var _Layout = __webpack_require__(2);

var _Layout2 = _interopRequireDefault(_Layout);

var _DataTable = __webpack_require__(329);

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
/* 268 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


Object.defineProperty(exports, "__esModule", {
    value: true
});

var _Layout = __webpack_require__(2);

var _Layout2 = _interopRequireDefault(_Layout);

var _Form = __webpack_require__(165);

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
        TagForm: _Form2.default
    }
};

/***/ }),
/* 269 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


Object.defineProperty(exports, "__esModule", {
    value: true
});

var _Layout = __webpack_require__(2);

var _Layout2 = _interopRequireDefault(_Layout);

var _Form = __webpack_require__(165);

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
        TagForm: _Form2.default
    }
};

/***/ }),
/* 270 */,
/* 271 */,
/* 272 */,
/* 273 */,
/* 274 */,
/* 275 */,
/* 276 */,
/* 277 */,
/* 278 */,
/* 279 */,
/* 280 */,
/* 281 */,
/* 282 */,
/* 283 */,
/* 284 */,
/* 285 */,
/* 286 */,
/* 287 */,
/* 288 */,
/* 289 */,
/* 290 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


Object.defineProperty(exports, "__esModule", {
    value: true
});

var _vue = __webpack_require__(4);

var _vue2 = _interopRequireDefault(_vue);

var _vueRouter = __webpack_require__(9);

var _vueRouter2 = _interopRequireDefault(_vueRouter);

var _Product = __webpack_require__(188);

var _Product2 = _interopRequireDefault(_Product);

var _ProductCreate = __webpack_require__(189);

var _ProductCreate2 = _interopRequireDefault(_ProductCreate);

var _ProductEdit = __webpack_require__(190);

var _ProductEdit2 = _interopRequireDefault(_ProductEdit);

var _Option = __webpack_require__(182);

var _Option2 = _interopRequireDefault(_Option);

var _OptionCreate = __webpack_require__(183);

var _OptionCreate2 = _interopRequireDefault(_OptionCreate);

var _OptionEdit = __webpack_require__(184);

var _OptionEdit2 = _interopRequireDefault(_OptionEdit);

var _Setting = __webpack_require__(191);

var _Setting2 = _interopRequireDefault(_Setting);

var _SettingCreate = __webpack_require__(192);

var _SettingCreate2 = _interopRequireDefault(_SettingCreate);

var _SettingEdit = __webpack_require__(193);

var _SettingEdit2 = _interopRequireDefault(_SettingEdit);

var _Tag = __webpack_require__(194);

var _Tag2 = _interopRequireDefault(_Tag);

var _TagCreate = __webpack_require__(195);

var _TagCreate2 = _interopRequireDefault(_TagCreate);

var _TagEdit = __webpack_require__(196);

var _TagEdit2 = _interopRequireDefault(_TagEdit);

var _OptionValue = __webpack_require__(179);

var _OptionValue2 = _interopRequireDefault(_OptionValue);

var _OptionValueCreate = __webpack_require__(180);

var _OptionValueCreate2 = _interopRequireDefault(_OptionValueCreate);

var _OptionValueEdit = __webpack_require__(181);

var _OptionValueEdit2 = _interopRequireDefault(_OptionValueEdit);

var _OptionGroup = __webpack_require__(176);

var _OptionGroup2 = _interopRequireDefault(_OptionGroup);

var _OptionGroupCreate = __webpack_require__(177);

var _OptionGroupCreate2 = _interopRequireDefault(_OptionGroupCreate);

var _OptionGroupEdit = __webpack_require__(178);

var _OptionGroupEdit2 = _interopRequireDefault(_OptionGroupEdit);

var _Offer = __webpack_require__(173);

var _Offer2 = _interopRequireDefault(_Offer);

var _OfferCreate = __webpack_require__(174);

var _OfferCreate2 = _interopRequireDefault(_OfferCreate);

var _OfferEdit = __webpack_require__(175);

var _OfferEdit2 = _interopRequireDefault(_OfferEdit);

var _Page = __webpack_require__(185);

var _Page2 = _interopRequireDefault(_Page);

var _PageCreate = __webpack_require__(186);

var _PageCreate2 = _interopRequireDefault(_PageCreate);

var _PageEdit = __webpack_require__(187);

var _PageEdit2 = _interopRequireDefault(_PageEdit);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

// import OptionGroupShow from './components/OptionGroup/OptionGroupShow.vue'

var routes = [{ path: lroutes.admin.ecommerce.product.index, name: 'product.index', component: _Product2.default }, { path: lroutes.admin.ecommerce.product.create, name: 'product.create', component: _ProductCreate2.default }, { path: lroutes.admin.ecommerce.product.edit.replace(/{.*}/, ':id'), name: 'product.edit', component: _ProductEdit2.default }, { path: lroutes.admin.ecommerce.option.index, name: 'option.index', component: _Option2.default }, { path: lroutes.admin.ecommerce.option.create, name: 'option.create', component: _OptionCreate2.default }, { path: lroutes.admin.ecommerce.option.edit.replace(/{.*}/, ':id'), name: 'option.edit', component: _OptionEdit2.default }, { path: lroutes.admin.ecommerce.setting.index, name: 'setting.index', component: _Setting2.default }, { path: lroutes.admin.ecommerce.setting.create, name: 'setting.create', component: _SettingCreate2.default }, { path: lroutes.admin.ecommerce.setting.edit.replace(/{.*}/, ':id'), name: 'setting.edit', component: _SettingEdit2.default }, { path: lroutes.admin.ecommerce.tag.index, name: 'tag.index', component: _Tag2.default }, { path: lroutes.admin.ecommerce.tag.create, name: 'tag.create', component: _TagCreate2.default }, { path: lroutes.admin.ecommerce.tag.edit.replace(/{.*}/, ':id'), name: 'tag.edit', component: _TagEdit2.default }, { path: lroutes.admin.ecommerce.option_value.index, name: 'option_value.index', component: _OptionValue2.default }, { path: lroutes.admin.ecommerce.option_value.create, name: 'option_value.create', component: _OptionValueCreate2.default }, { path: lroutes.admin.ecommerce.option_value.edit.replace(/{.*}/, ':id'), name: 'option_value.edit', component: _OptionValueEdit2.default }, { path: lroutes.admin.ecommerce.option_group.index, name: 'option_group.index', component: _OptionGroup2.default }, { path: lroutes.admin.ecommerce.option_group.create, name: 'option_group.create', component: _OptionGroupCreate2.default }, { path: lroutes.admin.ecommerce.option_group.show.replace(/{.*}/, ':id'), name: 'option_group.show', component: _Offer2.default }, { path: lroutes.admin.ecommerce.option_group.edit.replace(/{.*}/, ':id'), name: 'option_group.edit', component: _OptionGroupEdit2.default }, { path: lroutes.admin.ecommerce.offer.create, name: 'offer.create', component: _OfferCreate2.default }, { path: lroutes.admin.ecommerce.offer.edit.replace(/{.*}/, ':id'), name: 'offer.edit', component: _OfferEdit2.default }, { path: lroutes.admin.ecommerce.page.index, name: 'page.index', component: _Page2.default }, { path: lroutes.admin.ecommerce.page.create, name: 'page.create', component: _PageCreate2.default }, { path: lroutes.admin.ecommerce.page.edit.replace(/{.*}/, ':id'), name: 'page.edit', component: _PageEdit2.default }];

_vue2.default.use(_vueRouter2.default);

var router = new _vueRouter2.default({
    mode: 'history',
    routes: routes
});

var app = new _vue2.default({
    router: router
}).$mount('#app');

exports.default = app;

/***/ }),
/* 291 */,
/* 292 */,
/* 293 */,
/* 294 */,
/* 295 */
/***/ (function(module, exports, __webpack_require__) {

exports = module.exports = __webpack_require__(3)();
// imports


// module
exports.push([module.i, "/*\n * The MIT License\n * Copyright (c) 2012 Matias Meno <m@tias.me>\n */\n@-webkit-keyframes passing-through {\n  0% {\n    opacity: 0;\n    -webkit-transform: translateY(40px);\n    -moz-transform: translateY(40px);\n    -ms-transform: translateY(40px);\n    -o-transform: translateY(40px);\n    transform: translateY(40px); }\n  30%, 70% {\n    opacity: 1;\n    -webkit-transform: translateY(0px);\n    -moz-transform: translateY(0px);\n    -ms-transform: translateY(0px);\n    -o-transform: translateY(0px);\n    transform: translateY(0px); }\n  100% {\n    opacity: 0;\n    -webkit-transform: translateY(-40px);\n    -moz-transform: translateY(-40px);\n    -ms-transform: translateY(-40px);\n    -o-transform: translateY(-40px);\n    transform: translateY(-40px); } }\n@-moz-keyframes passing-through {\n  0% {\n    opacity: 0;\n    -webkit-transform: translateY(40px);\n    -moz-transform: translateY(40px);\n    -ms-transform: translateY(40px);\n    -o-transform: translateY(40px);\n    transform: translateY(40px); }\n  30%, 70% {\n    opacity: 1;\n    -webkit-transform: translateY(0px);\n    -moz-transform: translateY(0px);\n    -ms-transform: translateY(0px);\n    -o-transform: translateY(0px);\n    transform: translateY(0px); }\n  100% {\n    opacity: 0;\n    -webkit-transform: translateY(-40px);\n    -moz-transform: translateY(-40px);\n    -ms-transform: translateY(-40px);\n    -o-transform: translateY(-40px);\n    transform: translateY(-40px); } }\n@keyframes passing-through {\n  0% {\n    opacity: 0;\n    -webkit-transform: translateY(40px);\n    -moz-transform: translateY(40px);\n    -ms-transform: translateY(40px);\n    -o-transform: translateY(40px);\n    transform: translateY(40px); }\n  30%, 70% {\n    opacity: 1;\n    -webkit-transform: translateY(0px);\n    -moz-transform: translateY(0px);\n    -ms-transform: translateY(0px);\n    -o-transform: translateY(0px);\n    transform: translateY(0px); }\n  100% {\n    opacity: 0;\n    -webkit-transform: translateY(-40px);\n    -moz-transform: translateY(-40px);\n    -ms-transform: translateY(-40px);\n    -o-transform: translateY(-40px);\n    transform: translateY(-40px); } }\n@-webkit-keyframes slide-in {\n  0% {\n    opacity: 0;\n    -webkit-transform: translateY(40px);\n    -moz-transform: translateY(40px);\n    -ms-transform: translateY(40px);\n    -o-transform: translateY(40px);\n    transform: translateY(40px); }\n  30% {\n    opacity: 1;\n    -webkit-transform: translateY(0px);\n    -moz-transform: translateY(0px);\n    -ms-transform: translateY(0px);\n    -o-transform: translateY(0px);\n    transform: translateY(0px); } }\n@-moz-keyframes slide-in {\n  0% {\n    opacity: 0;\n    -webkit-transform: translateY(40px);\n    -moz-transform: translateY(40px);\n    -ms-transform: translateY(40px);\n    -o-transform: translateY(40px);\n    transform: translateY(40px); }\n  30% {\n    opacity: 1;\n    -webkit-transform: translateY(0px);\n    -moz-transform: translateY(0px);\n    -ms-transform: translateY(0px);\n    -o-transform: translateY(0px);\n    transform: translateY(0px); } }\n@keyframes slide-in {\n  0% {\n    opacity: 0;\n    -webkit-transform: translateY(40px);\n    -moz-transform: translateY(40px);\n    -ms-transform: translateY(40px);\n    -o-transform: translateY(40px);\n    transform: translateY(40px); }\n  30% {\n    opacity: 1;\n    -webkit-transform: translateY(0px);\n    -moz-transform: translateY(0px);\n    -ms-transform: translateY(0px);\n    -o-transform: translateY(0px);\n    transform: translateY(0px); } }\n@-webkit-keyframes pulse {\n  0% {\n    -webkit-transform: scale(1);\n    -moz-transform: scale(1);\n    -ms-transform: scale(1);\n    -o-transform: scale(1);\n    transform: scale(1); }\n  10% {\n    -webkit-transform: scale(1.1);\n    -moz-transform: scale(1.1);\n    -ms-transform: scale(1.1);\n    -o-transform: scale(1.1);\n    transform: scale(1.1); }\n  20% {\n    -webkit-transform: scale(1);\n    -moz-transform: scale(1);\n    -ms-transform: scale(1);\n    -o-transform: scale(1);\n    transform: scale(1); } }\n@-moz-keyframes pulse {\n  0% {\n    -webkit-transform: scale(1);\n    -moz-transform: scale(1);\n    -ms-transform: scale(1);\n    -o-transform: scale(1);\n    transform: scale(1); }\n  10% {\n    -webkit-transform: scale(1.1);\n    -moz-transform: scale(1.1);\n    -ms-transform: scale(1.1);\n    -o-transform: scale(1.1);\n    transform: scale(1.1); }\n  20% {\n    -webkit-transform: scale(1);\n    -moz-transform: scale(1);\n    -ms-transform: scale(1);\n    -o-transform: scale(1);\n    transform: scale(1); } }\n@keyframes pulse {\n  0% {\n    -webkit-transform: scale(1);\n    -moz-transform: scale(1);\n    -ms-transform: scale(1);\n    -o-transform: scale(1);\n    transform: scale(1); }\n  10% {\n    -webkit-transform: scale(1.1);\n    -moz-transform: scale(1.1);\n    -ms-transform: scale(1.1);\n    -o-transform: scale(1.1);\n    transform: scale(1.1); }\n  20% {\n    -webkit-transform: scale(1);\n    -moz-transform: scale(1);\n    -ms-transform: scale(1);\n    -o-transform: scale(1);\n    transform: scale(1); } }\n.dropzone, .dropzone * {\n  box-sizing: border-box; }\n\n.dropzone {\n  min-height: 150px;\n  border: 2px solid rgba(0, 0, 0, 0.3);\n  background: white;\n  padding: 20px 20px; }\n  .dropzone.dz-clickable {\n    cursor: pointer; }\n    .dropzone.dz-clickable * {\n      cursor: default; }\n    .dropzone.dz-clickable .dz-message, .dropzone.dz-clickable .dz-message * {\n      cursor: pointer; }\n  .dropzone.dz-started .dz-message {\n    display: none; }\n  .dropzone.dz-drag-hover {\n    border-style: solid; }\n    .dropzone.dz-drag-hover .dz-message {\n      opacity: 0.5; }\n  .dropzone .dz-message {\n    text-align: center;\n    margin: 2em 0; }\n  .dropzone .dz-preview {\n    position: relative;\n    display: inline-block;\n    vertical-align: top;\n    margin: 16px;\n    min-height: 100px; }\n    .dropzone .dz-preview:hover {\n      z-index: 1000; }\n      .dropzone .dz-preview:hover .dz-details {\n        opacity: 1; }\n    .dropzone .dz-preview.dz-file-preview .dz-image {\n      border-radius: 20px;\n      background: #999;\n      background: linear-gradient(to bottom, #eee, #ddd); }\n    .dropzone .dz-preview.dz-file-preview .dz-details {\n      opacity: 1; }\n    .dropzone .dz-preview.dz-image-preview {\n      background: white; }\n      .dropzone .dz-preview.dz-image-preview .dz-details {\n        -webkit-transition: opacity 0.2s linear;\n        -moz-transition: opacity 0.2s linear;\n        -ms-transition: opacity 0.2s linear;\n        -o-transition: opacity 0.2s linear;\n        transition: opacity 0.2s linear; }\n    .dropzone .dz-preview .dz-remove {\n      font-size: 14px;\n      text-align: center;\n      display: block;\n      cursor: pointer;\n      border: none; }\n      .dropzone .dz-preview .dz-remove:hover {\n        text-decoration: underline; }\n    .dropzone .dz-preview:hover .dz-details {\n      opacity: 1; }\n    .dropzone .dz-preview .dz-details {\n      z-index: 20;\n      position: absolute;\n      top: 0;\n      left: 0;\n      opacity: 0;\n      font-size: 13px;\n      min-width: 100%;\n      max-width: 100%;\n      padding: 2em 1em;\n      text-align: center;\n      color: rgba(0, 0, 0, 0.9);\n      line-height: 150%; }\n      .dropzone .dz-preview .dz-details .dz-size {\n        margin-bottom: 1em;\n        font-size: 16px; }\n      .dropzone .dz-preview .dz-details .dz-filename {\n        white-space: nowrap; }\n        .dropzone .dz-preview .dz-details .dz-filename:hover span {\n          border: 1px solid rgba(200, 200, 200, 0.8);\n          background-color: rgba(255, 255, 255, 0.8); }\n        .dropzone .dz-preview .dz-details .dz-filename:not(:hover) {\n          overflow: hidden;\n          text-overflow: ellipsis; }\n          .dropzone .dz-preview .dz-details .dz-filename:not(:hover) span {\n            border: 1px solid transparent; }\n      .dropzone .dz-preview .dz-details .dz-filename span, .dropzone .dz-preview .dz-details .dz-size span {\n        background-color: rgba(255, 255, 255, 0.4);\n        padding: 0 0.4em;\n        border-radius: 3px; }\n    .dropzone .dz-preview:hover .dz-image img {\n      -webkit-transform: scale(1.05, 1.05);\n      -moz-transform: scale(1.05, 1.05);\n      -ms-transform: scale(1.05, 1.05);\n      -o-transform: scale(1.05, 1.05);\n      transform: scale(1.05, 1.05);\n      -webkit-filter: blur(8px);\n      filter: blur(8px); }\n    .dropzone .dz-preview .dz-image {\n      border-radius: 20px;\n      overflow: hidden;\n      width: 120px;\n      height: 120px;\n      position: relative;\n      display: block;\n      z-index: 10; }\n      .dropzone .dz-preview .dz-image img {\n        display: block; }\n    .dropzone .dz-preview.dz-success .dz-success-mark {\n      -webkit-animation: passing-through 3s cubic-bezier(0.77, 0, 0.175, 1);\n      -moz-animation: passing-through 3s cubic-bezier(0.77, 0, 0.175, 1);\n      -ms-animation: passing-through 3s cubic-bezier(0.77, 0, 0.175, 1);\n      -o-animation: passing-through 3s cubic-bezier(0.77, 0, 0.175, 1);\n      animation: passing-through 3s cubic-bezier(0.77, 0, 0.175, 1); }\n    .dropzone .dz-preview.dz-error .dz-error-mark {\n      opacity: 1;\n      -webkit-animation: slide-in 3s cubic-bezier(0.77, 0, 0.175, 1);\n      -moz-animation: slide-in 3s cubic-bezier(0.77, 0, 0.175, 1);\n      -ms-animation: slide-in 3s cubic-bezier(0.77, 0, 0.175, 1);\n      -o-animation: slide-in 3s cubic-bezier(0.77, 0, 0.175, 1);\n      animation: slide-in 3s cubic-bezier(0.77, 0, 0.175, 1); }\n    .dropzone .dz-preview .dz-success-mark, .dropzone .dz-preview .dz-error-mark {\n      pointer-events: none;\n      opacity: 0;\n      z-index: 500;\n      position: absolute;\n      display: block;\n      top: 50%;\n      left: 50%;\n      margin-left: -27px;\n      margin-top: -27px; }\n      .dropzone .dz-preview .dz-success-mark svg, .dropzone .dz-preview .dz-error-mark svg {\n        display: block;\n        width: 54px;\n        height: 54px; }\n    .dropzone .dz-preview.dz-processing .dz-progress {\n      opacity: 1;\n      -webkit-transition: all 0.2s linear;\n      -moz-transition: all 0.2s linear;\n      -ms-transition: all 0.2s linear;\n      -o-transition: all 0.2s linear;\n      transition: all 0.2s linear; }\n    .dropzone .dz-preview.dz-complete .dz-progress {\n      opacity: 0;\n      -webkit-transition: opacity 0.4s ease-in;\n      -moz-transition: opacity 0.4s ease-in;\n      -ms-transition: opacity 0.4s ease-in;\n      -o-transition: opacity 0.4s ease-in;\n      transition: opacity 0.4s ease-in; }\n    .dropzone .dz-preview:not(.dz-processing) .dz-progress {\n      -webkit-animation: pulse 6s ease infinite;\n      -moz-animation: pulse 6s ease infinite;\n      -ms-animation: pulse 6s ease infinite;\n      -o-animation: pulse 6s ease infinite;\n      animation: pulse 6s ease infinite; }\n    .dropzone .dz-preview .dz-progress {\n      opacity: 1;\n      z-index: 1000;\n      pointer-events: none;\n      position: absolute;\n      height: 16px;\n      left: 50%;\n      top: 50%;\n      margin-top: -8px;\n      width: 80px;\n      margin-left: -40px;\n      background: rgba(255, 255, 255, 0.9);\n      -webkit-transform: scale(1);\n      border-radius: 8px;\n      overflow: hidden; }\n      .dropzone .dz-preview .dz-progress .dz-upload {\n        background: #333;\n        background: linear-gradient(to bottom, #666, #444);\n        position: absolute;\n        top: 0;\n        left: 0;\n        bottom: 0;\n        width: 0;\n        -webkit-transition: width 300ms ease-in-out;\n        -moz-transition: width 300ms ease-in-out;\n        -ms-transition: width 300ms ease-in-out;\n        -o-transition: width 300ms ease-in-out;\n        transition: width 300ms ease-in-out; }\n    .dropzone .dz-preview.dz-error .dz-error-message {\n      display: block; }\n    .dropzone .dz-preview.dz-error:hover .dz-error-message {\n      opacity: 1;\n      pointer-events: auto; }\n    .dropzone .dz-preview .dz-error-message {\n      pointer-events: none;\n      z-index: 1000;\n      position: absolute;\n      display: block;\n      display: none;\n      opacity: 0;\n      -webkit-transition: opacity 0.3s ease;\n      -moz-transition: opacity 0.3s ease;\n      -ms-transition: opacity 0.3s ease;\n      -o-transition: opacity 0.3s ease;\n      transition: opacity 0.3s ease;\n      border-radius: 8px;\n      font-size: 13px;\n      top: 130px;\n      left: -10px;\n      width: 140px;\n      background: #be2626;\n      background: linear-gradient(to bottom, #be2626, #a92222);\n      padding: 0.5em 1.2em;\n      color: white; }\n      .dropzone .dz-preview .dz-error-message:after {\n        content: '';\n        position: absolute;\n        top: -6px;\n        left: 64px;\n        width: 0;\n        height: 0;\n        border-left: 6px solid transparent;\n        border-right: 6px solid transparent;\n        border-bottom: 6px solid #be2626; }\n", ""]);

// exports


/***/ }),
/* 296 */,
/* 297 */,
/* 298 */
/***/ (function(module, exports, __webpack_require__) {

exports = module.exports = __webpack_require__(3)();
// imports


// module
exports.push([module.i, "\n.category.label{\n    margin-right:5px;\n}\n", ""]);

// exports


/***/ }),
/* 299 */,
/* 300 */,
/* 301 */
/***/ (function(module, exports, __webpack_require__) {

exports = module.exports = __webpack_require__(3)();
// imports


// module
exports.push([module.i, "\n.clear {\n    clear: both;\n}\n.photo-block {\n    float: left;\n    margin: 15px;\n}\n.photo {\n    width: 150px;\n    height: 150px;\n    overflow: hidden;\n    border-radius: 5px;\n}\n.photo img {\n    width: 150px;\n    height: auto;\n}\n.photo.default {\n    border: 3px solid green;\n}\n", ""]);

// exports


/***/ }),
/* 302 */
/***/ (function(module, exports, __webpack_require__) {

exports = module.exports = __webpack_require__(3)();
// imports


// module
exports.push([module.i, "\n.category.label{\n    margin-right:5px;\n}\n", ""]);

// exports


/***/ }),
/* 303 */
/***/ (function(module, exports, __webpack_require__) {

exports = module.exports = __webpack_require__(3)();
// imports


// module
exports.push([module.i, "\n.category.label{\n    margin-right:5px;\n}\n", ""]);

// exports


/***/ }),
/* 304 */,
/* 305 */
/***/ (function(module, exports, __webpack_require__) {

exports = module.exports = __webpack_require__(3)();
// imports


// module
exports.push([module.i, "\n.category.label{\n    margin-right:5px;\n}\n", ""]);

// exports


/***/ }),
/* 306 */
/***/ (function(module, exports, __webpack_require__) {

exports = module.exports = __webpack_require__(3)();
// imports


// module
exports.push([module.i, "\n.category.label{\n    margin-right:5px;\n}\n", ""]);

// exports


/***/ }),
/* 307 */
/***/ (function(module, exports, __webpack_require__) {

exports = module.exports = __webpack_require__(3)();
// imports


// module
exports.push([module.i, "\n.category.label{\n    margin-right:5px;\n}\n", ""]);

// exports


/***/ }),
/* 308 */
/***/ (function(module, exports, __webpack_require__) {

exports = module.exports = __webpack_require__(3)();
// imports


// module
exports.push([module.i, "\n.category.label{\n    margin-right:5px;\n}\n", ""]);

// exports


/***/ }),
/* 309 */
/***/ (function(module, exports, __webpack_require__) {

exports = module.exports = __webpack_require__(3)();
// imports


// module
exports.push([module.i, "\n.category.label{\n    margin-right:5px;\n}\n", ""]);

// exports


/***/ }),
/* 310 */,
/* 311 */,
/* 312 */
/***/ (function(module, exports, __webpack_require__) {

/* WEBPACK VAR INJECTION */(function(module) {
/*
 *
 * More info at [www.dropzonejs.com](http://www.dropzonejs.com)
 *
 * Copyright (c) 2012, Matias Meno
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 *
 */

(function() {
  var Dropzone, Emitter, camelize, contentLoaded, detectVerticalSquash, drawImageIOSFix, noop, without,
    __slice = [].slice,
    __hasProp = {}.hasOwnProperty,
    __extends = function(child, parent) { for (var key in parent) { if (__hasProp.call(parent, key)) child[key] = parent[key]; } function ctor() { this.constructor = child; } ctor.prototype = parent.prototype; child.prototype = new ctor(); child.__super__ = parent.prototype; return child; };

  noop = function() {};

  Emitter = (function() {
    function Emitter() {}

    Emitter.prototype.addEventListener = Emitter.prototype.on;

    Emitter.prototype.on = function(event, fn) {
      this._callbacks = this._callbacks || {};
      if (!this._callbacks[event]) {
        this._callbacks[event] = [];
      }
      this._callbacks[event].push(fn);
      return this;
    };

    Emitter.prototype.emit = function() {
      var args, callback, callbacks, event, _i, _len;
      event = arguments[0], args = 2 <= arguments.length ? __slice.call(arguments, 1) : [];
      this._callbacks = this._callbacks || {};
      callbacks = this._callbacks[event];
      if (callbacks) {
        for (_i = 0, _len = callbacks.length; _i < _len; _i++) {
          callback = callbacks[_i];
          callback.apply(this, args);
        }
      }
      return this;
    };

    Emitter.prototype.removeListener = Emitter.prototype.off;

    Emitter.prototype.removeAllListeners = Emitter.prototype.off;

    Emitter.prototype.removeEventListener = Emitter.prototype.off;

    Emitter.prototype.off = function(event, fn) {
      var callback, callbacks, i, _i, _len;
      if (!this._callbacks || arguments.length === 0) {
        this._callbacks = {};
        return this;
      }
      callbacks = this._callbacks[event];
      if (!callbacks) {
        return this;
      }
      if (arguments.length === 1) {
        delete this._callbacks[event];
        return this;
      }
      for (i = _i = 0, _len = callbacks.length; _i < _len; i = ++_i) {
        callback = callbacks[i];
        if (callback === fn) {
          callbacks.splice(i, 1);
          break;
        }
      }
      return this;
    };

    return Emitter;

  })();

  Dropzone = (function(_super) {
    var extend, resolveOption;

    __extends(Dropzone, _super);

    Dropzone.prototype.Emitter = Emitter;


    /*
    This is a list of all available events you can register on a dropzone object.
    
    You can register an event handler like this:
    
        dropzone.on("dragEnter", function() { });
     */

    Dropzone.prototype.events = ["drop", "dragstart", "dragend", "dragenter", "dragover", "dragleave", "addedfile", "addedfiles", "removedfile", "thumbnail", "error", "errormultiple", "processing", "processingmultiple", "uploadprogress", "totaluploadprogress", "sending", "sendingmultiple", "success", "successmultiple", "canceled", "canceledmultiple", "complete", "completemultiple", "reset", "maxfilesexceeded", "maxfilesreached", "queuecomplete"];

    Dropzone.prototype.defaultOptions = {
      url: null,
      method: "post",
      withCredentials: false,
      parallelUploads: 2,
      uploadMultiple: false,
      maxFilesize: 256,
      paramName: "file",
      createImageThumbnails: true,
      maxThumbnailFilesize: 10,
      thumbnailWidth: 120,
      thumbnailHeight: 120,
      filesizeBase: 1000,
      maxFiles: null,
      params: {},
      clickable: true,
      ignoreHiddenFiles: true,
      acceptedFiles: null,
      acceptedMimeTypes: null,
      autoProcessQueue: true,
      autoQueue: true,
      addRemoveLinks: false,
      previewsContainer: null,
      hiddenInputContainer: "body",
      capture: null,
      renameFilename: null,
      dictDefaultMessage: "Drop files here to upload",
      dictFallbackMessage: "Your browser does not support drag'n'drop file uploads.",
      dictFallbackText: "Please use the fallback form below to upload your files like in the olden days.",
      dictFileTooBig: "File is too big ({{filesize}}MiB). Max filesize: {{maxFilesize}}MiB.",
      dictInvalidFileType: "You can't upload files of this type.",
      dictResponseError: "Server responded with {{statusCode}} code.",
      dictCancelUpload: "Cancel upload",
      dictCancelUploadConfirmation: "Are you sure you want to cancel this upload?",
      dictRemoveFile: "Remove file",
      dictRemoveFileConfirmation: null,
      dictMaxFilesExceeded: "You can not upload any more files.",
      accept: function(file, done) {
        return done();
      },
      init: function() {
        return noop;
      },
      forceFallback: false,
      fallback: function() {
        var child, messageElement, span, _i, _len, _ref;
        this.element.className = "" + this.element.className + " dz-browser-not-supported";
        _ref = this.element.getElementsByTagName("div");
        for (_i = 0, _len = _ref.length; _i < _len; _i++) {
          child = _ref[_i];
          if (/(^| )dz-message($| )/.test(child.className)) {
            messageElement = child;
            child.className = "dz-message";
            continue;
          }
        }
        if (!messageElement) {
          messageElement = Dropzone.createElement("<div class=\"dz-message\"><span></span></div>");
          this.element.appendChild(messageElement);
        }
        span = messageElement.getElementsByTagName("span")[0];
        if (span) {
          if (span.textContent != null) {
            span.textContent = this.options.dictFallbackMessage;
          } else if (span.innerText != null) {
            span.innerText = this.options.dictFallbackMessage;
          }
        }
        return this.element.appendChild(this.getFallbackForm());
      },
      resize: function(file) {
        var info, srcRatio, trgRatio;
        info = {
          srcX: 0,
          srcY: 0,
          srcWidth: file.width,
          srcHeight: file.height
        };
        srcRatio = file.width / file.height;
        info.optWidth = this.options.thumbnailWidth;
        info.optHeight = this.options.thumbnailHeight;
        if ((info.optWidth == null) && (info.optHeight == null)) {
          info.optWidth = info.srcWidth;
          info.optHeight = info.srcHeight;
        } else if (info.optWidth == null) {
          info.optWidth = srcRatio * info.optHeight;
        } else if (info.optHeight == null) {
          info.optHeight = (1 / srcRatio) * info.optWidth;
        }
        trgRatio = info.optWidth / info.optHeight;
        if (file.height < info.optHeight || file.width < info.optWidth) {
          info.trgHeight = info.srcHeight;
          info.trgWidth = info.srcWidth;
        } else {
          if (srcRatio > trgRatio) {
            info.srcHeight = file.height;
            info.srcWidth = info.srcHeight * trgRatio;
          } else {
            info.srcWidth = file.width;
            info.srcHeight = info.srcWidth / trgRatio;
          }
        }
        info.srcX = (file.width - info.srcWidth) / 2;
        info.srcY = (file.height - info.srcHeight) / 2;
        return info;
      },

      /*
      Those functions register themselves to the events on init and handle all
      the user interface specific stuff. Overwriting them won't break the upload
      but can break the way it's displayed.
      You can overwrite them if you don't like the default behavior. If you just
      want to add an additional event handler, register it on the dropzone object
      and don't overwrite those options.
       */
      drop: function(e) {
        return this.element.classList.remove("dz-drag-hover");
      },
      dragstart: noop,
      dragend: function(e) {
        return this.element.classList.remove("dz-drag-hover");
      },
      dragenter: function(e) {
        return this.element.classList.add("dz-drag-hover");
      },
      dragover: function(e) {
        return this.element.classList.add("dz-drag-hover");
      },
      dragleave: function(e) {
        return this.element.classList.remove("dz-drag-hover");
      },
      paste: noop,
      reset: function() {
        return this.element.classList.remove("dz-started");
      },
      addedfile: function(file) {
        var node, removeFileEvent, removeLink, _i, _j, _k, _len, _len1, _len2, _ref, _ref1, _ref2, _results;
        if (this.element === this.previewsContainer) {
          this.element.classList.add("dz-started");
        }
        if (this.previewsContainer) {
          file.previewElement = Dropzone.createElement(this.options.previewTemplate.trim());
          file.previewTemplate = file.previewElement;
          this.previewsContainer.appendChild(file.previewElement);
          _ref = file.previewElement.querySelectorAll("[data-dz-name]");
          for (_i = 0, _len = _ref.length; _i < _len; _i++) {
            node = _ref[_i];
            node.textContent = this._renameFilename(file.name);
          }
          _ref1 = file.previewElement.querySelectorAll("[data-dz-size]");
          for (_j = 0, _len1 = _ref1.length; _j < _len1; _j++) {
            node = _ref1[_j];
            node.innerHTML = this.filesize(file.size);
          }
          if (this.options.addRemoveLinks) {
            file._removeLink = Dropzone.createElement("<a class=\"dz-remove\" href=\"javascript:undefined;\" data-dz-remove>" + this.options.dictRemoveFile + "</a>");
            file.previewElement.appendChild(file._removeLink);
          }
          removeFileEvent = (function(_this) {
            return function(e) {
              e.preventDefault();
              e.stopPropagation();
              if (file.status === Dropzone.UPLOADING) {
                return Dropzone.confirm(_this.options.dictCancelUploadConfirmation, function() {
                  return _this.removeFile(file);
                });
              } else {
                if (_this.options.dictRemoveFileConfirmation) {
                  return Dropzone.confirm(_this.options.dictRemoveFileConfirmation, function() {
                    return _this.removeFile(file);
                  });
                } else {
                  return _this.removeFile(file);
                }
              }
            };
          })(this);
          _ref2 = file.previewElement.querySelectorAll("[data-dz-remove]");
          _results = [];
          for (_k = 0, _len2 = _ref2.length; _k < _len2; _k++) {
            removeLink = _ref2[_k];
            _results.push(removeLink.addEventListener("click", removeFileEvent));
          }
          return _results;
        }
      },
      removedfile: function(file) {
        var _ref;
        if (file.previewElement) {
          if ((_ref = file.previewElement) != null) {
            _ref.parentNode.removeChild(file.previewElement);
          }
        }
        return this._updateMaxFilesReachedClass();
      },
      thumbnail: function(file, dataUrl) {
        var thumbnailElement, _i, _len, _ref;
        if (file.previewElement) {
          file.previewElement.classList.remove("dz-file-preview");
          _ref = file.previewElement.querySelectorAll("[data-dz-thumbnail]");
          for (_i = 0, _len = _ref.length; _i < _len; _i++) {
            thumbnailElement = _ref[_i];
            thumbnailElement.alt = file.name;
            thumbnailElement.src = dataUrl;
          }
          return setTimeout(((function(_this) {
            return function() {
              return file.previewElement.classList.add("dz-image-preview");
            };
          })(this)), 1);
        }
      },
      error: function(file, message) {
        var node, _i, _len, _ref, _results;
        if (file.previewElement) {
          file.previewElement.classList.add("dz-error");
          if (typeof message !== "String" && message.error) {
            message = message.error;
          }
          _ref = file.previewElement.querySelectorAll("[data-dz-errormessage]");
          _results = [];
          for (_i = 0, _len = _ref.length; _i < _len; _i++) {
            node = _ref[_i];
            _results.push(node.textContent = message);
          }
          return _results;
        }
      },
      errormultiple: noop,
      processing: function(file) {
        if (file.previewElement) {
          file.previewElement.classList.add("dz-processing");
          if (file._removeLink) {
            return file._removeLink.textContent = this.options.dictCancelUpload;
          }
        }
      },
      processingmultiple: noop,
      uploadprogress: function(file, progress, bytesSent) {
        var node, _i, _len, _ref, _results;
        if (file.previewElement) {
          _ref = file.previewElement.querySelectorAll("[data-dz-uploadprogress]");
          _results = [];
          for (_i = 0, _len = _ref.length; _i < _len; _i++) {
            node = _ref[_i];
            if (node.nodeName === 'PROGRESS') {
              _results.push(node.value = progress);
            } else {
              _results.push(node.style.width = "" + progress + "%");
            }
          }
          return _results;
        }
      },
      totaluploadprogress: noop,
      sending: noop,
      sendingmultiple: noop,
      success: function(file) {
        if (file.previewElement) {
          return file.previewElement.classList.add("dz-success");
        }
      },
      successmultiple: noop,
      canceled: function(file) {
        return this.emit("error", file, "Upload canceled.");
      },
      canceledmultiple: noop,
      complete: function(file) {
        if (file._removeLink) {
          file._removeLink.textContent = this.options.dictRemoveFile;
        }
        if (file.previewElement) {
          return file.previewElement.classList.add("dz-complete");
        }
      },
      completemultiple: noop,
      maxfilesexceeded: noop,
      maxfilesreached: noop,
      queuecomplete: noop,
      addedfiles: noop,
      previewTemplate: "<div class=\"dz-preview dz-file-preview\">\n  <div class=\"dz-image\"><img data-dz-thumbnail /></div>\n  <div class=\"dz-details\">\n    <div class=\"dz-size\"><span data-dz-size></span></div>\n    <div class=\"dz-filename\"><span data-dz-name></span></div>\n  </div>\n  <div class=\"dz-progress\"><span class=\"dz-upload\" data-dz-uploadprogress></span></div>\n  <div class=\"dz-error-message\"><span data-dz-errormessage></span></div>\n  <div class=\"dz-success-mark\">\n    <svg width=\"54px\" height=\"54px\" viewBox=\"0 0 54 54\" version=\"1.1\" xmlns=\"http://www.w3.org/2000/svg\" xmlns:xlink=\"http://www.w3.org/1999/xlink\" xmlns:sketch=\"http://www.bohemiancoding.com/sketch/ns\">\n      <title>Check</title>\n      <defs></defs>\n      <g id=\"Page-1\" stroke=\"none\" stroke-width=\"1\" fill=\"none\" fill-rule=\"evenodd\" sketch:type=\"MSPage\">\n        <path d=\"M23.5,31.8431458 L17.5852419,25.9283877 C16.0248253,24.3679711 13.4910294,24.366835 11.9289322,25.9289322 C10.3700136,27.4878508 10.3665912,30.0234455 11.9283877,31.5852419 L20.4147581,40.0716123 C20.5133999,40.1702541 20.6159315,40.2626649 20.7218615,40.3488435 C22.2835669,41.8725651 24.794234,41.8626202 26.3461564,40.3106978 L43.3106978,23.3461564 C44.8771021,21.7797521 44.8758057,19.2483887 43.3137085,17.6862915 C41.7547899,16.1273729 39.2176035,16.1255422 37.6538436,17.6893022 L23.5,31.8431458 Z M27,53 C41.3594035,53 53,41.3594035 53,27 C53,12.6405965 41.3594035,1 27,1 C12.6405965,1 1,12.6405965 1,27 C1,41.3594035 12.6405965,53 27,53 Z\" id=\"Oval-2\" stroke-opacity=\"0.198794158\" stroke=\"#747474\" fill-opacity=\"0.816519475\" fill=\"#FFFFFF\" sketch:type=\"MSShapeGroup\"></path>\n      </g>\n    </svg>\n  </div>\n  <div class=\"dz-error-mark\">\n    <svg width=\"54px\" height=\"54px\" viewBox=\"0 0 54 54\" version=\"1.1\" xmlns=\"http://www.w3.org/2000/svg\" xmlns:xlink=\"http://www.w3.org/1999/xlink\" xmlns:sketch=\"http://www.bohemiancoding.com/sketch/ns\">\n      <title>Error</title>\n      <defs></defs>\n      <g id=\"Page-1\" stroke=\"none\" stroke-width=\"1\" fill=\"none\" fill-rule=\"evenodd\" sketch:type=\"MSPage\">\n        <g id=\"Check-+-Oval-2\" sketch:type=\"MSLayerGroup\" stroke=\"#747474\" stroke-opacity=\"0.198794158\" fill=\"#FFFFFF\" fill-opacity=\"0.816519475\">\n          <path d=\"M32.6568542,29 L38.3106978,23.3461564 C39.8771021,21.7797521 39.8758057,19.2483887 38.3137085,17.6862915 C36.7547899,16.1273729 34.2176035,16.1255422 32.6538436,17.6893022 L27,23.3431458 L21.3461564,17.6893022 C19.7823965,16.1255422 17.2452101,16.1273729 15.6862915,17.6862915 C14.1241943,19.2483887 14.1228979,21.7797521 15.6893022,23.3461564 L21.3431458,29 L15.6893022,34.6538436 C14.1228979,36.2202479 14.1241943,38.7516113 15.6862915,40.3137085 C17.2452101,41.8726271 19.7823965,41.8744578 21.3461564,40.3106978 L27,34.6568542 L32.6538436,40.3106978 C34.2176035,41.8744578 36.7547899,41.8726271 38.3137085,40.3137085 C39.8758057,38.7516113 39.8771021,36.2202479 38.3106978,34.6538436 L32.6568542,29 Z M27,53 C41.3594035,53 53,41.3594035 53,27 C53,12.6405965 41.3594035,1 27,1 C12.6405965,1 1,12.6405965 1,27 C1,41.3594035 12.6405965,53 27,53 Z\" id=\"Oval-2\" sketch:type=\"MSShapeGroup\"></path>\n        </g>\n      </g>\n    </svg>\n  </div>\n</div>"
    };

    extend = function() {
      var key, object, objects, target, val, _i, _len;
      target = arguments[0], objects = 2 <= arguments.length ? __slice.call(arguments, 1) : [];
      for (_i = 0, _len = objects.length; _i < _len; _i++) {
        object = objects[_i];
        for (key in object) {
          val = object[key];
          target[key] = val;
        }
      }
      return target;
    };

    function Dropzone(element, options) {
      var elementOptions, fallback, _ref;
      this.element = element;
      this.version = Dropzone.version;
      this.defaultOptions.previewTemplate = this.defaultOptions.previewTemplate.replace(/\n*/g, "");
      this.clickableElements = [];
      this.listeners = [];
      this.files = [];
      if (typeof this.element === "string") {
        this.element = document.querySelector(this.element);
      }
      if (!(this.element && (this.element.nodeType != null))) {
        throw new Error("Invalid dropzone element.");
      }
      if (this.element.dropzone) {
        throw new Error("Dropzone already attached.");
      }
      Dropzone.instances.push(this);
      this.element.dropzone = this;
      elementOptions = (_ref = Dropzone.optionsForElement(this.element)) != null ? _ref : {};
      this.options = extend({}, this.defaultOptions, elementOptions, options != null ? options : {});
      if (this.options.forceFallback || !Dropzone.isBrowserSupported()) {
        return this.options.fallback.call(this);
      }
      if (this.options.url == null) {
        this.options.url = this.element.getAttribute("action");
      }
      if (!this.options.url) {
        throw new Error("No URL provided.");
      }
      if (this.options.acceptedFiles && this.options.acceptedMimeTypes) {
        throw new Error("You can't provide both 'acceptedFiles' and 'acceptedMimeTypes'. 'acceptedMimeTypes' is deprecated.");
      }
      if (this.options.acceptedMimeTypes) {
        this.options.acceptedFiles = this.options.acceptedMimeTypes;
        delete this.options.acceptedMimeTypes;
      }
      this.options.method = this.options.method.toUpperCase();
      if ((fallback = this.getExistingFallback()) && fallback.parentNode) {
        fallback.parentNode.removeChild(fallback);
      }
      if (this.options.previewsContainer !== false) {
        if (this.options.previewsContainer) {
          this.previewsContainer = Dropzone.getElement(this.options.previewsContainer, "previewsContainer");
        } else {
          this.previewsContainer = this.element;
        }
      }
      if (this.options.clickable) {
        if (this.options.clickable === true) {
          this.clickableElements = [this.element];
        } else {
          this.clickableElements = Dropzone.getElements(this.options.clickable, "clickable");
        }
      }
      this.init();
    }

    Dropzone.prototype.getAcceptedFiles = function() {
      var file, _i, _len, _ref, _results;
      _ref = this.files;
      _results = [];
      for (_i = 0, _len = _ref.length; _i < _len; _i++) {
        file = _ref[_i];
        if (file.accepted) {
          _results.push(file);
        }
      }
      return _results;
    };

    Dropzone.prototype.getRejectedFiles = function() {
      var file, _i, _len, _ref, _results;
      _ref = this.files;
      _results = [];
      for (_i = 0, _len = _ref.length; _i < _len; _i++) {
        file = _ref[_i];
        if (!file.accepted) {
          _results.push(file);
        }
      }
      return _results;
    };

    Dropzone.prototype.getFilesWithStatus = function(status) {
      var file, _i, _len, _ref, _results;
      _ref = this.files;
      _results = [];
      for (_i = 0, _len = _ref.length; _i < _len; _i++) {
        file = _ref[_i];
        if (file.status === status) {
          _results.push(file);
        }
      }
      return _results;
    };

    Dropzone.prototype.getQueuedFiles = function() {
      return this.getFilesWithStatus(Dropzone.QUEUED);
    };

    Dropzone.prototype.getUploadingFiles = function() {
      return this.getFilesWithStatus(Dropzone.UPLOADING);
    };

    Dropzone.prototype.getAddedFiles = function() {
      return this.getFilesWithStatus(Dropzone.ADDED);
    };

    Dropzone.prototype.getActiveFiles = function() {
      var file, _i, _len, _ref, _results;
      _ref = this.files;
      _results = [];
      for (_i = 0, _len = _ref.length; _i < _len; _i++) {
        file = _ref[_i];
        if (file.status === Dropzone.UPLOADING || file.status === Dropzone.QUEUED) {
          _results.push(file);
        }
      }
      return _results;
    };

    Dropzone.prototype.init = function() {
      var eventName, noPropagation, setupHiddenFileInput, _i, _len, _ref, _ref1;
      if (this.element.tagName === "form") {
        this.element.setAttribute("enctype", "multipart/form-data");
      }
      if (this.element.classList.contains("dropzone") && !this.element.querySelector(".dz-message")) {
        this.element.appendChild(Dropzone.createElement("<div class=\"dz-default dz-message\"><span>" + this.options.dictDefaultMessage + "</span></div>"));
      }
      if (this.clickableElements.length) {
        setupHiddenFileInput = (function(_this) {
          return function() {
            if (_this.hiddenFileInput) {
              _this.hiddenFileInput.parentNode.removeChild(_this.hiddenFileInput);
            }
            _this.hiddenFileInput = document.createElement("input");
            _this.hiddenFileInput.setAttribute("type", "file");
            if ((_this.options.maxFiles == null) || _this.options.maxFiles > 1) {
              _this.hiddenFileInput.setAttribute("multiple", "multiple");
            }
            _this.hiddenFileInput.className = "dz-hidden-input";
            if (_this.options.acceptedFiles != null) {
              _this.hiddenFileInput.setAttribute("accept", _this.options.acceptedFiles);
            }
            if (_this.options.capture != null) {
              _this.hiddenFileInput.setAttribute("capture", _this.options.capture);
            }
            _this.hiddenFileInput.style.visibility = "hidden";
            _this.hiddenFileInput.style.position = "absolute";
            _this.hiddenFileInput.style.top = "0";
            _this.hiddenFileInput.style.left = "0";
            _this.hiddenFileInput.style.height = "0";
            _this.hiddenFileInput.style.width = "0";
            document.querySelector(_this.options.hiddenInputContainer).appendChild(_this.hiddenFileInput);
            return _this.hiddenFileInput.addEventListener("change", function() {
              var file, files, _i, _len;
              files = _this.hiddenFileInput.files;
              if (files.length) {
                for (_i = 0, _len = files.length; _i < _len; _i++) {
                  file = files[_i];
                  _this.addFile(file);
                }
              }
              _this.emit("addedfiles", files);
              return setupHiddenFileInput();
            });
          };
        })(this);
        setupHiddenFileInput();
      }
      this.URL = (_ref = window.URL) != null ? _ref : window.webkitURL;
      _ref1 = this.events;
      for (_i = 0, _len = _ref1.length; _i < _len; _i++) {
        eventName = _ref1[_i];
        this.on(eventName, this.options[eventName]);
      }
      this.on("uploadprogress", (function(_this) {
        return function() {
          return _this.updateTotalUploadProgress();
        };
      })(this));
      this.on("removedfile", (function(_this) {
        return function() {
          return _this.updateTotalUploadProgress();
        };
      })(this));
      this.on("canceled", (function(_this) {
        return function(file) {
          return _this.emit("complete", file);
        };
      })(this));
      this.on("complete", (function(_this) {
        return function(file) {
          if (_this.getAddedFiles().length === 0 && _this.getUploadingFiles().length === 0 && _this.getQueuedFiles().length === 0) {
            return setTimeout((function() {
              return _this.emit("queuecomplete");
            }), 0);
          }
        };
      })(this));
      noPropagation = function(e) {
        e.stopPropagation();
        if (e.preventDefault) {
          return e.preventDefault();
        } else {
          return e.returnValue = false;
        }
      };
      this.listeners = [
        {
          element: this.element,
          events: {
            "dragstart": (function(_this) {
              return function(e) {
                return _this.emit("dragstart", e);
              };
            })(this),
            "dragenter": (function(_this) {
              return function(e) {
                noPropagation(e);
                return _this.emit("dragenter", e);
              };
            })(this),
            "dragover": (function(_this) {
              return function(e) {
                var efct;
                try {
                  efct = e.dataTransfer.effectAllowed;
                } catch (_error) {}
                e.dataTransfer.dropEffect = 'move' === efct || 'linkMove' === efct ? 'move' : 'copy';
                noPropagation(e);
                return _this.emit("dragover", e);
              };
            })(this),
            "dragleave": (function(_this) {
              return function(e) {
                return _this.emit("dragleave", e);
              };
            })(this),
            "drop": (function(_this) {
              return function(e) {
                noPropagation(e);
                return _this.drop(e);
              };
            })(this),
            "dragend": (function(_this) {
              return function(e) {
                return _this.emit("dragend", e);
              };
            })(this)
          }
        }
      ];
      this.clickableElements.forEach((function(_this) {
        return function(clickableElement) {
          return _this.listeners.push({
            element: clickableElement,
            events: {
              "click": function(evt) {
                if ((clickableElement !== _this.element) || (evt.target === _this.element || Dropzone.elementInside(evt.target, _this.element.querySelector(".dz-message")))) {
                  _this.hiddenFileInput.click();
                }
                return true;
              }
            }
          });
        };
      })(this));
      this.enable();
      return this.options.init.call(this);
    };

    Dropzone.prototype.destroy = function() {
      var _ref;
      this.disable();
      this.removeAllFiles(true);
      if ((_ref = this.hiddenFileInput) != null ? _ref.parentNode : void 0) {
        this.hiddenFileInput.parentNode.removeChild(this.hiddenFileInput);
        this.hiddenFileInput = null;
      }
      delete this.element.dropzone;
      return Dropzone.instances.splice(Dropzone.instances.indexOf(this), 1);
    };

    Dropzone.prototype.updateTotalUploadProgress = function() {
      var activeFiles, file, totalBytes, totalBytesSent, totalUploadProgress, _i, _len, _ref;
      totalBytesSent = 0;
      totalBytes = 0;
      activeFiles = this.getActiveFiles();
      if (activeFiles.length) {
        _ref = this.getActiveFiles();
        for (_i = 0, _len = _ref.length; _i < _len; _i++) {
          file = _ref[_i];
          totalBytesSent += file.upload.bytesSent;
          totalBytes += file.upload.total;
        }
        totalUploadProgress = 100 * totalBytesSent / totalBytes;
      } else {
        totalUploadProgress = 100;
      }
      return this.emit("totaluploadprogress", totalUploadProgress, totalBytes, totalBytesSent);
    };

    Dropzone.prototype._getParamName = function(n) {
      if (typeof this.options.paramName === "function") {
        return this.options.paramName(n);
      } else {
        return "" + this.options.paramName + (this.options.uploadMultiple ? "[" + n + "]" : "");
      }
    };

    Dropzone.prototype._renameFilename = function(name) {
      if (typeof this.options.renameFilename !== "function") {
        return name;
      }
      return this.options.renameFilename(name);
    };

    Dropzone.prototype.getFallbackForm = function() {
      var existingFallback, fields, fieldsString, form;
      if (existingFallback = this.getExistingFallback()) {
        return existingFallback;
      }
      fieldsString = "<div class=\"dz-fallback\">";
      if (this.options.dictFallbackText) {
        fieldsString += "<p>" + this.options.dictFallbackText + "</p>";
      }
      fieldsString += "<input type=\"file\" name=\"" + (this._getParamName(0)) + "\" " + (this.options.uploadMultiple ? 'multiple="multiple"' : void 0) + " /><input type=\"submit\" value=\"Upload!\"></div>";
      fields = Dropzone.createElement(fieldsString);
      if (this.element.tagName !== "FORM") {
        form = Dropzone.createElement("<form action=\"" + this.options.url + "\" enctype=\"multipart/form-data\" method=\"" + this.options.method + "\"></form>");
        form.appendChild(fields);
      } else {
        this.element.setAttribute("enctype", "multipart/form-data");
        this.element.setAttribute("method", this.options.method);
      }
      return form != null ? form : fields;
    };

    Dropzone.prototype.getExistingFallback = function() {
      var fallback, getFallback, tagName, _i, _len, _ref;
      getFallback = function(elements) {
        var el, _i, _len;
        for (_i = 0, _len = elements.length; _i < _len; _i++) {
          el = elements[_i];
          if (/(^| )fallback($| )/.test(el.className)) {
            return el;
          }
        }
      };
      _ref = ["div", "form"];
      for (_i = 0, _len = _ref.length; _i < _len; _i++) {
        tagName = _ref[_i];
        if (fallback = getFallback(this.element.getElementsByTagName(tagName))) {
          return fallback;
        }
      }
    };

    Dropzone.prototype.setupEventListeners = function() {
      var elementListeners, event, listener, _i, _len, _ref, _results;
      _ref = this.listeners;
      _results = [];
      for (_i = 0, _len = _ref.length; _i < _len; _i++) {
        elementListeners = _ref[_i];
        _results.push((function() {
          var _ref1, _results1;
          _ref1 = elementListeners.events;
          _results1 = [];
          for (event in _ref1) {
            listener = _ref1[event];
            _results1.push(elementListeners.element.addEventListener(event, listener, false));
          }
          return _results1;
        })());
      }
      return _results;
    };

    Dropzone.prototype.removeEventListeners = function() {
      var elementListeners, event, listener, _i, _len, _ref, _results;
      _ref = this.listeners;
      _results = [];
      for (_i = 0, _len = _ref.length; _i < _len; _i++) {
        elementListeners = _ref[_i];
        _results.push((function() {
          var _ref1, _results1;
          _ref1 = elementListeners.events;
          _results1 = [];
          for (event in _ref1) {
            listener = _ref1[event];
            _results1.push(elementListeners.element.removeEventListener(event, listener, false));
          }
          return _results1;
        })());
      }
      return _results;
    };

    Dropzone.prototype.disable = function() {
      var file, _i, _len, _ref, _results;
      this.clickableElements.forEach(function(element) {
        return element.classList.remove("dz-clickable");
      });
      this.removeEventListeners();
      _ref = this.files;
      _results = [];
      for (_i = 0, _len = _ref.length; _i < _len; _i++) {
        file = _ref[_i];
        _results.push(this.cancelUpload(file));
      }
      return _results;
    };

    Dropzone.prototype.enable = function() {
      this.clickableElements.forEach(function(element) {
        return element.classList.add("dz-clickable");
      });
      return this.setupEventListeners();
    };

    Dropzone.prototype.filesize = function(size) {
      var cutoff, i, selectedSize, selectedUnit, unit, units, _i, _len;
      selectedSize = 0;
      selectedUnit = "b";
      if (size > 0) {
        units = ['TB', 'GB', 'MB', 'KB', 'b'];
        for (i = _i = 0, _len = units.length; _i < _len; i = ++_i) {
          unit = units[i];
          cutoff = Math.pow(this.options.filesizeBase, 4 - i) / 10;
          if (size >= cutoff) {
            selectedSize = size / Math.pow(this.options.filesizeBase, 4 - i);
            selectedUnit = unit;
            break;
          }
        }
        selectedSize = Math.round(10 * selectedSize) / 10;
      }
      return "<strong>" + selectedSize + "</strong> " + selectedUnit;
    };

    Dropzone.prototype._updateMaxFilesReachedClass = function() {
      if ((this.options.maxFiles != null) && this.getAcceptedFiles().length >= this.options.maxFiles) {
        if (this.getAcceptedFiles().length === this.options.maxFiles) {
          this.emit('maxfilesreached', this.files);
        }
        return this.element.classList.add("dz-max-files-reached");
      } else {
        return this.element.classList.remove("dz-max-files-reached");
      }
    };

    Dropzone.prototype.drop = function(e) {
      var files, items;
      if (!e.dataTransfer) {
        return;
      }
      this.emit("drop", e);
      files = e.dataTransfer.files;
      this.emit("addedfiles", files);
      if (files.length) {
        items = e.dataTransfer.items;
        if (items && items.length && (items[0].webkitGetAsEntry != null)) {
          this._addFilesFromItems(items);
        } else {
          this.handleFiles(files);
        }
      }
    };

    Dropzone.prototype.paste = function(e) {
      var items, _ref;
      if ((e != null ? (_ref = e.clipboardData) != null ? _ref.items : void 0 : void 0) == null) {
        return;
      }
      this.emit("paste", e);
      items = e.clipboardData.items;
      if (items.length) {
        return this._addFilesFromItems(items);
      }
    };

    Dropzone.prototype.handleFiles = function(files) {
      var file, _i, _len, _results;
      _results = [];
      for (_i = 0, _len = files.length; _i < _len; _i++) {
        file = files[_i];
        _results.push(this.addFile(file));
      }
      return _results;
    };

    Dropzone.prototype._addFilesFromItems = function(items) {
      var entry, item, _i, _len, _results;
      _results = [];
      for (_i = 0, _len = items.length; _i < _len; _i++) {
        item = items[_i];
        if ((item.webkitGetAsEntry != null) && (entry = item.webkitGetAsEntry())) {
          if (entry.isFile) {
            _results.push(this.addFile(item.getAsFile()));
          } else if (entry.isDirectory) {
            _results.push(this._addFilesFromDirectory(entry, entry.name));
          } else {
            _results.push(void 0);
          }
        } else if (item.getAsFile != null) {
          if ((item.kind == null) || item.kind === "file") {
            _results.push(this.addFile(item.getAsFile()));
          } else {
            _results.push(void 0);
          }
        } else {
          _results.push(void 0);
        }
      }
      return _results;
    };

    Dropzone.prototype._addFilesFromDirectory = function(directory, path) {
      var dirReader, errorHandler, readEntries;
      dirReader = directory.createReader();
      errorHandler = function(error) {
        return typeof console !== "undefined" && console !== null ? typeof console.log === "function" ? console.log(error) : void 0 : void 0;
      };
      readEntries = (function(_this) {
        return function() {
          return dirReader.readEntries(function(entries) {
            var entry, _i, _len;
            if (entries.length > 0) {
              for (_i = 0, _len = entries.length; _i < _len; _i++) {
                entry = entries[_i];
                if (entry.isFile) {
                  entry.file(function(file) {
                    if (_this.options.ignoreHiddenFiles && file.name.substring(0, 1) === '.') {
                      return;
                    }
                    file.fullPath = "" + path + "/" + file.name;
                    return _this.addFile(file);
                  });
                } else if (entry.isDirectory) {
                  _this._addFilesFromDirectory(entry, "" + path + "/" + entry.name);
                }
              }
              readEntries();
            }
            return null;
          }, errorHandler);
        };
      })(this);
      return readEntries();
    };

    Dropzone.prototype.accept = function(file, done) {
      if (file.size > this.options.maxFilesize * 1024 * 1024) {
        return done(this.options.dictFileTooBig.replace("{{filesize}}", Math.round(file.size / 1024 / 10.24) / 100).replace("{{maxFilesize}}", this.options.maxFilesize));
      } else if (!Dropzone.isValidFile(file, this.options.acceptedFiles)) {
        return done(this.options.dictInvalidFileType);
      } else if ((this.options.maxFiles != null) && this.getAcceptedFiles().length >= this.options.maxFiles) {
        done(this.options.dictMaxFilesExceeded.replace("{{maxFiles}}", this.options.maxFiles));
        return this.emit("maxfilesexceeded", file);
      } else {
        return this.options.accept.call(this, file, done);
      }
    };

    Dropzone.prototype.addFile = function(file) {
      file.upload = {
        progress: 0,
        total: file.size,
        bytesSent: 0
      };
      this.files.push(file);
      file.status = Dropzone.ADDED;
      this.emit("addedfile", file);
      this._enqueueThumbnail(file);
      return this.accept(file, (function(_this) {
        return function(error) {
          if (error) {
            file.accepted = false;
            _this._errorProcessing([file], error);
          } else {
            file.accepted = true;
            if (_this.options.autoQueue) {
              _this.enqueueFile(file);
            }
          }
          return _this._updateMaxFilesReachedClass();
        };
      })(this));
    };

    Dropzone.prototype.enqueueFiles = function(files) {
      var file, _i, _len;
      for (_i = 0, _len = files.length; _i < _len; _i++) {
        file = files[_i];
        this.enqueueFile(file);
      }
      return null;
    };

    Dropzone.prototype.enqueueFile = function(file) {
      if (file.status === Dropzone.ADDED && file.accepted === true) {
        file.status = Dropzone.QUEUED;
        if (this.options.autoProcessQueue) {
          return setTimeout(((function(_this) {
            return function() {
              return _this.processQueue();
            };
          })(this)), 0);
        }
      } else {
        throw new Error("This file can't be queued because it has already been processed or was rejected.");
      }
    };

    Dropzone.prototype._thumbnailQueue = [];

    Dropzone.prototype._processingThumbnail = false;

    Dropzone.prototype._enqueueThumbnail = function(file) {
      if (this.options.createImageThumbnails && file.type.match(/image.*/) && file.size <= this.options.maxThumbnailFilesize * 1024 * 1024) {
        this._thumbnailQueue.push(file);
        return setTimeout(((function(_this) {
          return function() {
            return _this._processThumbnailQueue();
          };
        })(this)), 0);
      }
    };

    Dropzone.prototype._processThumbnailQueue = function() {
      if (this._processingThumbnail || this._thumbnailQueue.length === 0) {
        return;
      }
      this._processingThumbnail = true;
      return this.createThumbnail(this._thumbnailQueue.shift(), (function(_this) {
        return function() {
          _this._processingThumbnail = false;
          return _this._processThumbnailQueue();
        };
      })(this));
    };

    Dropzone.prototype.removeFile = function(file) {
      if (file.status === Dropzone.UPLOADING) {
        this.cancelUpload(file);
      }
      this.files = without(this.files, file);
      this.emit("removedfile", file);
      if (this.files.length === 0) {
        return this.emit("reset");
      }
    };

    Dropzone.prototype.removeAllFiles = function(cancelIfNecessary) {
      var file, _i, _len, _ref;
      if (cancelIfNecessary == null) {
        cancelIfNecessary = false;
      }
      _ref = this.files.slice();
      for (_i = 0, _len = _ref.length; _i < _len; _i++) {
        file = _ref[_i];
        if (file.status !== Dropzone.UPLOADING || cancelIfNecessary) {
          this.removeFile(file);
        }
      }
      return null;
    };

    Dropzone.prototype.createThumbnail = function(file, callback) {
      var fileReader;
      fileReader = new FileReader;
      fileReader.onload = (function(_this) {
        return function() {
          if (file.type === "image/svg+xml") {
            _this.emit("thumbnail", file, fileReader.result);
            if (callback != null) {
              callback();
            }
            return;
          }
          return _this.createThumbnailFromUrl(file, fileReader.result, callback);
        };
      })(this);
      return fileReader.readAsDataURL(file);
    };

    Dropzone.prototype.createThumbnailFromUrl = function(file, imageUrl, callback, crossOrigin) {
      var img;
      img = document.createElement("img");
      if (crossOrigin) {
        img.crossOrigin = crossOrigin;
      }
      img.onload = (function(_this) {
        return function() {
          var canvas, ctx, resizeInfo, thumbnail, _ref, _ref1, _ref2, _ref3;
          file.width = img.width;
          file.height = img.height;
          resizeInfo = _this.options.resize.call(_this, file);
          if (resizeInfo.trgWidth == null) {
            resizeInfo.trgWidth = resizeInfo.optWidth;
          }
          if (resizeInfo.trgHeight == null) {
            resizeInfo.trgHeight = resizeInfo.optHeight;
          }
          canvas = document.createElement("canvas");
          ctx = canvas.getContext("2d");
          canvas.width = resizeInfo.trgWidth;
          canvas.height = resizeInfo.trgHeight;
          drawImageIOSFix(ctx, img, (_ref = resizeInfo.srcX) != null ? _ref : 0, (_ref1 = resizeInfo.srcY) != null ? _ref1 : 0, resizeInfo.srcWidth, resizeInfo.srcHeight, (_ref2 = resizeInfo.trgX) != null ? _ref2 : 0, (_ref3 = resizeInfo.trgY) != null ? _ref3 : 0, resizeInfo.trgWidth, resizeInfo.trgHeight);
          thumbnail = canvas.toDataURL("image/png");
          _this.emit("thumbnail", file, thumbnail);
          if (callback != null) {
            return callback();
          }
        };
      })(this);
      if (callback != null) {
        img.onerror = callback;
      }
      return img.src = imageUrl;
    };

    Dropzone.prototype.processQueue = function() {
      var i, parallelUploads, processingLength, queuedFiles;
      parallelUploads = this.options.parallelUploads;
      processingLength = this.getUploadingFiles().length;
      i = processingLength;
      if (processingLength >= parallelUploads) {
        return;
      }
      queuedFiles = this.getQueuedFiles();
      if (!(queuedFiles.length > 0)) {
        return;
      }
      if (this.options.uploadMultiple) {
        return this.processFiles(queuedFiles.slice(0, parallelUploads - processingLength));
      } else {
        while (i < parallelUploads) {
          if (!queuedFiles.length) {
            return;
          }
          this.processFile(queuedFiles.shift());
          i++;
        }
      }
    };

    Dropzone.prototype.processFile = function(file) {
      return this.processFiles([file]);
    };

    Dropzone.prototype.processFiles = function(files) {
      var file, _i, _len;
      for (_i = 0, _len = files.length; _i < _len; _i++) {
        file = files[_i];
        file.processing = true;
        file.status = Dropzone.UPLOADING;
        this.emit("processing", file);
      }
      if (this.options.uploadMultiple) {
        this.emit("processingmultiple", files);
      }
      return this.uploadFiles(files);
    };

    Dropzone.prototype._getFilesWithXhr = function(xhr) {
      var file, files;
      return files = (function() {
        var _i, _len, _ref, _results;
        _ref = this.files;
        _results = [];
        for (_i = 0, _len = _ref.length; _i < _len; _i++) {
          file = _ref[_i];
          if (file.xhr === xhr) {
            _results.push(file);
          }
        }
        return _results;
      }).call(this);
    };

    Dropzone.prototype.cancelUpload = function(file) {
      var groupedFile, groupedFiles, _i, _j, _len, _len1, _ref;
      if (file.status === Dropzone.UPLOADING) {
        groupedFiles = this._getFilesWithXhr(file.xhr);
        for (_i = 0, _len = groupedFiles.length; _i < _len; _i++) {
          groupedFile = groupedFiles[_i];
          groupedFile.status = Dropzone.CANCELED;
        }
        file.xhr.abort();
        for (_j = 0, _len1 = groupedFiles.length; _j < _len1; _j++) {
          groupedFile = groupedFiles[_j];
          this.emit("canceled", groupedFile);
        }
        if (this.options.uploadMultiple) {
          this.emit("canceledmultiple", groupedFiles);
        }
      } else if ((_ref = file.status) === Dropzone.ADDED || _ref === Dropzone.QUEUED) {
        file.status = Dropzone.CANCELED;
        this.emit("canceled", file);
        if (this.options.uploadMultiple) {
          this.emit("canceledmultiple", [file]);
        }
      }
      if (this.options.autoProcessQueue) {
        return this.processQueue();
      }
    };

    resolveOption = function() {
      var args, option;
      option = arguments[0], args = 2 <= arguments.length ? __slice.call(arguments, 1) : [];
      if (typeof option === 'function') {
        return option.apply(this, args);
      }
      return option;
    };

    Dropzone.prototype.uploadFile = function(file) {
      return this.uploadFiles([file]);
    };

    Dropzone.prototype.uploadFiles = function(files) {
      var file, formData, handleError, headerName, headerValue, headers, i, input, inputName, inputType, key, method, option, progressObj, response, updateProgress, url, value, xhr, _i, _j, _k, _l, _len, _len1, _len2, _len3, _m, _ref, _ref1, _ref2, _ref3, _ref4, _ref5;
      xhr = new XMLHttpRequest();
      for (_i = 0, _len = files.length; _i < _len; _i++) {
        file = files[_i];
        file.xhr = xhr;
      }
      method = resolveOption(this.options.method, files);
      url = resolveOption(this.options.url, files);
      xhr.open(method, url, true);
      xhr.withCredentials = !!this.options.withCredentials;
      response = null;
      handleError = (function(_this) {
        return function() {
          var _j, _len1, _results;
          _results = [];
          for (_j = 0, _len1 = files.length; _j < _len1; _j++) {
            file = files[_j];
            _results.push(_this._errorProcessing(files, response || _this.options.dictResponseError.replace("{{statusCode}}", xhr.status), xhr));
          }
          return _results;
        };
      })(this);
      updateProgress = (function(_this) {
        return function(e) {
          var allFilesFinished, progress, _j, _k, _l, _len1, _len2, _len3, _results;
          if (e != null) {
            progress = 100 * e.loaded / e.total;
            for (_j = 0, _len1 = files.length; _j < _len1; _j++) {
              file = files[_j];
              file.upload = {
                progress: progress,
                total: e.total,
                bytesSent: e.loaded
              };
            }
          } else {
            allFilesFinished = true;
            progress = 100;
            for (_k = 0, _len2 = files.length; _k < _len2; _k++) {
              file = files[_k];
              if (!(file.upload.progress === 100 && file.upload.bytesSent === file.upload.total)) {
                allFilesFinished = false;
              }
              file.upload.progress = progress;
              file.upload.bytesSent = file.upload.total;
            }
            if (allFilesFinished) {
              return;
            }
          }
          _results = [];
          for (_l = 0, _len3 = files.length; _l < _len3; _l++) {
            file = files[_l];
            _results.push(_this.emit("uploadprogress", file, progress, file.upload.bytesSent));
          }
          return _results;
        };
      })(this);
      xhr.onload = (function(_this) {
        return function(e) {
          var _ref;
          if (files[0].status === Dropzone.CANCELED) {
            return;
          }
          if (xhr.readyState !== 4) {
            return;
          }
          response = xhr.responseText;
          if (xhr.getResponseHeader("content-type") && ~xhr.getResponseHeader("content-type").indexOf("application/json")) {
            try {
              response = JSON.parse(response);
            } catch (_error) {
              e = _error;
              response = "Invalid JSON response from server.";
            }
          }
          updateProgress();
          if (!((200 <= (_ref = xhr.status) && _ref < 300))) {
            return handleError();
          } else {
            return _this._finished(files, response, e);
          }
        };
      })(this);
      xhr.onerror = (function(_this) {
        return function() {
          if (files[0].status === Dropzone.CANCELED) {
            return;
          }
          return handleError();
        };
      })(this);
      progressObj = (_ref = xhr.upload) != null ? _ref : xhr;
      progressObj.onprogress = updateProgress;
      headers = {
        "Accept": "application/json",
        "Cache-Control": "no-cache",
        "X-Requested-With": "XMLHttpRequest"
      };
      if (this.options.headers) {
        extend(headers, this.options.headers);
      }
      for (headerName in headers) {
        headerValue = headers[headerName];
        if (headerValue) {
          xhr.setRequestHeader(headerName, headerValue);
        }
      }
      formData = new FormData();
      if (this.options.params) {
        _ref1 = this.options.params;
        for (key in _ref1) {
          value = _ref1[key];
          formData.append(key, value);
        }
      }
      for (_j = 0, _len1 = files.length; _j < _len1; _j++) {
        file = files[_j];
        this.emit("sending", file, xhr, formData);
      }
      if (this.options.uploadMultiple) {
        this.emit("sendingmultiple", files, xhr, formData);
      }
      if (this.element.tagName === "FORM") {
        _ref2 = this.element.querySelectorAll("input, textarea, select, button");
        for (_k = 0, _len2 = _ref2.length; _k < _len2; _k++) {
          input = _ref2[_k];
          inputName = input.getAttribute("name");
          inputType = input.getAttribute("type");
          if (input.tagName === "SELECT" && input.hasAttribute("multiple")) {
            _ref3 = input.options;
            for (_l = 0, _len3 = _ref3.length; _l < _len3; _l++) {
              option = _ref3[_l];
              if (option.selected) {
                formData.append(inputName, option.value);
              }
            }
          } else if (!inputType || ((_ref4 = inputType.toLowerCase()) !== "checkbox" && _ref4 !== "radio") || input.checked) {
            formData.append(inputName, input.value);
          }
        }
      }
      for (i = _m = 0, _ref5 = files.length - 1; 0 <= _ref5 ? _m <= _ref5 : _m >= _ref5; i = 0 <= _ref5 ? ++_m : --_m) {
        formData.append(this._getParamName(i), files[i], this._renameFilename(files[i].name));
      }
      return this.submitRequest(xhr, formData, files);
    };

    Dropzone.prototype.submitRequest = function(xhr, formData, files) {
      return xhr.send(formData);
    };

    Dropzone.prototype._finished = function(files, responseText, e) {
      var file, _i, _len;
      for (_i = 0, _len = files.length; _i < _len; _i++) {
        file = files[_i];
        file.status = Dropzone.SUCCESS;
        this.emit("success", file, responseText, e);
        this.emit("complete", file);
      }
      if (this.options.uploadMultiple) {
        this.emit("successmultiple", files, responseText, e);
        this.emit("completemultiple", files);
      }
      if (this.options.autoProcessQueue) {
        return this.processQueue();
      }
    };

    Dropzone.prototype._errorProcessing = function(files, message, xhr) {
      var file, _i, _len;
      for (_i = 0, _len = files.length; _i < _len; _i++) {
        file = files[_i];
        file.status = Dropzone.ERROR;
        this.emit("error", file, message, xhr);
        this.emit("complete", file);
      }
      if (this.options.uploadMultiple) {
        this.emit("errormultiple", files, message, xhr);
        this.emit("completemultiple", files);
      }
      if (this.options.autoProcessQueue) {
        return this.processQueue();
      }
    };

    return Dropzone;

  })(Emitter);

  Dropzone.version = "4.3.0";

  Dropzone.options = {};

  Dropzone.optionsForElement = function(element) {
    if (element.getAttribute("id")) {
      return Dropzone.options[camelize(element.getAttribute("id"))];
    } else {
      return void 0;
    }
  };

  Dropzone.instances = [];

  Dropzone.forElement = function(element) {
    if (typeof element === "string") {
      element = document.querySelector(element);
    }
    if ((element != null ? element.dropzone : void 0) == null) {
      throw new Error("No Dropzone found for given element. This is probably because you're trying to access it before Dropzone had the time to initialize. Use the `init` option to setup any additional observers on your Dropzone.");
    }
    return element.dropzone;
  };

  Dropzone.autoDiscover = true;

  Dropzone.discover = function() {
    var checkElements, dropzone, dropzones, _i, _len, _results;
    if (document.querySelectorAll) {
      dropzones = document.querySelectorAll(".dropzone");
    } else {
      dropzones = [];
      checkElements = function(elements) {
        var el, _i, _len, _results;
        _results = [];
        for (_i = 0, _len = elements.length; _i < _len; _i++) {
          el = elements[_i];
          if (/(^| )dropzone($| )/.test(el.className)) {
            _results.push(dropzones.push(el));
          } else {
            _results.push(void 0);
          }
        }
        return _results;
      };
      checkElements(document.getElementsByTagName("div"));
      checkElements(document.getElementsByTagName("form"));
    }
    _results = [];
    for (_i = 0, _len = dropzones.length; _i < _len; _i++) {
      dropzone = dropzones[_i];
      if (Dropzone.optionsForElement(dropzone) !== false) {
        _results.push(new Dropzone(dropzone));
      } else {
        _results.push(void 0);
      }
    }
    return _results;
  };

  Dropzone.blacklistedBrowsers = [/opera.*Macintosh.*version\/12/i];

  Dropzone.isBrowserSupported = function() {
    var capableBrowser, regex, _i, _len, _ref;
    capableBrowser = true;
    if (window.File && window.FileReader && window.FileList && window.Blob && window.FormData && document.querySelector) {
      if (!("classList" in document.createElement("a"))) {
        capableBrowser = false;
      } else {
        _ref = Dropzone.blacklistedBrowsers;
        for (_i = 0, _len = _ref.length; _i < _len; _i++) {
          regex = _ref[_i];
          if (regex.test(navigator.userAgent)) {
            capableBrowser = false;
            continue;
          }
        }
      }
    } else {
      capableBrowser = false;
    }
    return capableBrowser;
  };

  without = function(list, rejectedItem) {
    var item, _i, _len, _results;
    _results = [];
    for (_i = 0, _len = list.length; _i < _len; _i++) {
      item = list[_i];
      if (item !== rejectedItem) {
        _results.push(item);
      }
    }
    return _results;
  };

  camelize = function(str) {
    return str.replace(/[\-_](\w)/g, function(match) {
      return match.charAt(1).toUpperCase();
    });
  };

  Dropzone.createElement = function(string) {
    var div;
    div = document.createElement("div");
    div.innerHTML = string;
    return div.childNodes[0];
  };

  Dropzone.elementInside = function(element, container) {
    if (element === container) {
      return true;
    }
    while (element = element.parentNode) {
      if (element === container) {
        return true;
      }
    }
    return false;
  };

  Dropzone.getElement = function(el, name) {
    var element;
    if (typeof el === "string") {
      element = document.querySelector(el);
    } else if (el.nodeType != null) {
      element = el;
    }
    if (element == null) {
      throw new Error("Invalid `" + name + "` option provided. Please provide a CSS selector or a plain HTML element.");
    }
    return element;
  };

  Dropzone.getElements = function(els, name) {
    var e, el, elements, _i, _j, _len, _len1, _ref;
    if (els instanceof Array) {
      elements = [];
      try {
        for (_i = 0, _len = els.length; _i < _len; _i++) {
          el = els[_i];
          elements.push(this.getElement(el, name));
        }
      } catch (_error) {
        e = _error;
        elements = null;
      }
    } else if (typeof els === "string") {
      elements = [];
      _ref = document.querySelectorAll(els);
      for (_j = 0, _len1 = _ref.length; _j < _len1; _j++) {
        el = _ref[_j];
        elements.push(el);
      }
    } else if (els.nodeType != null) {
      elements = [els];
    }
    if (!((elements != null) && elements.length)) {
      throw new Error("Invalid `" + name + "` option provided. Please provide a CSS selector, a plain HTML element or a list of those.");
    }
    return elements;
  };

  Dropzone.confirm = function(question, accepted, rejected) {
    if (window.confirm(question)) {
      return accepted();
    } else if (rejected != null) {
      return rejected();
    }
  };

  Dropzone.isValidFile = function(file, acceptedFiles) {
    var baseMimeType, mimeType, validType, _i, _len;
    if (!acceptedFiles) {
      return true;
    }
    acceptedFiles = acceptedFiles.split(",");
    mimeType = file.type;
    baseMimeType = mimeType.replace(/\/.*$/, "");
    for (_i = 0, _len = acceptedFiles.length; _i < _len; _i++) {
      validType = acceptedFiles[_i];
      validType = validType.trim();
      if (validType.charAt(0) === ".") {
        if (file.name.toLowerCase().indexOf(validType.toLowerCase(), file.name.length - validType.length) !== -1) {
          return true;
        }
      } else if (/\/\*$/.test(validType)) {
        if (baseMimeType === validType.replace(/\/.*$/, "")) {
          return true;
        }
      } else {
        if (mimeType === validType) {
          return true;
        }
      }
    }
    return false;
  };

  if (typeof jQuery !== "undefined" && jQuery !== null) {
    jQuery.fn.dropzone = function(options) {
      return this.each(function() {
        return new Dropzone(this, options);
      });
    };
  }

  if (typeof module !== "undefined" && module !== null) {
    module.exports = Dropzone;
  } else {
    window.Dropzone = Dropzone;
  }

  Dropzone.ADDED = "added";

  Dropzone.QUEUED = "queued";

  Dropzone.ACCEPTED = Dropzone.QUEUED;

  Dropzone.UPLOADING = "uploading";

  Dropzone.PROCESSING = Dropzone.UPLOADING;

  Dropzone.CANCELED = "canceled";

  Dropzone.ERROR = "error";

  Dropzone.SUCCESS = "success";


  /*
  
  Bugfix for iOS 6 and 7
  Source: http://stackoverflow.com/questions/11929099/html5-canvas-drawimage-ratio-bug-ios
  based on the work of https://github.com/stomita/ios-imagefile-megapixel
   */

  detectVerticalSquash = function(img) {
    var alpha, canvas, ctx, data, ey, ih, iw, py, ratio, sy;
    iw = img.naturalWidth;
    ih = img.naturalHeight;
    canvas = document.createElement("canvas");
    canvas.width = 1;
    canvas.height = ih;
    ctx = canvas.getContext("2d");
    ctx.drawImage(img, 0, 0);
    data = ctx.getImageData(0, 0, 1, ih).data;
    sy = 0;
    ey = ih;
    py = ih;
    while (py > sy) {
      alpha = data[(py - 1) * 4 + 3];
      if (alpha === 0) {
        ey = py;
      } else {
        sy = py;
      }
      py = (ey + sy) >> 1;
    }
    ratio = py / ih;
    if (ratio === 0) {
      return 1;
    } else {
      return ratio;
    }
  };

  drawImageIOSFix = function(ctx, img, sx, sy, sw, sh, dx, dy, dw, dh) {
    var vertSquashRatio;
    vertSquashRatio = detectVerticalSquash(img);
    return ctx.drawImage(img, sx, sy, sw, sh, dx, dy, dw, dh / vertSquashRatio);
  };


  /*
   * contentloaded.js
   *
   * Author: Diego Perini (diego.perini at gmail.com)
   * Summary: cross-browser wrapper for DOMContentLoaded
   * Updated: 20101020
   * License: MIT
   * Version: 1.2
   *
   * URL:
   * http://javascript.nwbox.com/ContentLoaded/
   * http://javascript.nwbox.com/ContentLoaded/MIT-LICENSE
   */

  contentLoaded = function(win, fn) {
    var add, doc, done, init, poll, pre, rem, root, top;
    done = false;
    top = true;
    doc = win.document;
    root = doc.documentElement;
    add = (doc.addEventListener ? "addEventListener" : "attachEvent");
    rem = (doc.addEventListener ? "removeEventListener" : "detachEvent");
    pre = (doc.addEventListener ? "" : "on");
    init = function(e) {
      if (e.type === "readystatechange" && doc.readyState !== "complete") {
        return;
      }
      (e.type === "load" ? win : doc)[rem](pre + e.type, init, false);
      if (!done && (done = true)) {
        return fn.call(win, e.type || e);
      }
    };
    poll = function() {
      var e;
      try {
        root.doScroll("left");
      } catch (_error) {
        e = _error;
        setTimeout(poll, 50);
        return;
      }
      return init("poll");
    };
    if (doc.readyState !== "complete") {
      if (doc.createEventObject && root.doScroll) {
        try {
          top = !win.frameElement;
        } catch (_error) {}
        if (top) {
          poll();
        }
      }
      doc[add](pre + "DOMContentLoaded", init, false);
      doc[add](pre + "readystatechange", init, false);
      return win[add](pre + "load", init, false);
    }
  };

  Dropzone._autoDiscoverFunction = function() {
    if (Dropzone.autoDiscover) {
      return Dropzone.discover();
    }
  };

  contentLoaded(window, Dropzone._autoDiscoverFunction);

}).call(this);

/* WEBPACK VAR INJECTION */}.call(exports, __webpack_require__(34)(module)))

/***/ }),
/* 313 */
/***/ (function(module, exports, __webpack_require__) {

// style-loader: Adds some css to the DOM by adding a <style> tag

// load the styles
var content = __webpack_require__(295);
if(typeof content === 'string') content = [[module.i, content, '']];
// add the styles to the DOM
var update = __webpack_require__(10)(content, {});
if(content.locals) module.exports = content.locals;
// Hot Module Replacement
if(false) {
	// When the styles change, update the <style> tags
	if(!content.locals) {
		module.hot.accept("!!../../css-loader/index.js!./dropzone.css", function() {
			var newContent = require("!!../../css-loader/index.js!./dropzone.css");
			if(typeof newContent === 'string') newContent = [[module.id, newContent, '']];
			update(newContent);
		});
	}
	// When the module is disposed, remove the <style> tags
	module.hot.dispose(function() { update(); });
}

/***/ }),
/* 314 */,
/* 315 */,
/* 316 */,
/* 317 */,
/* 318 */,
/* 319 */,
/* 320 */,
/* 321 */,
/* 322 */,
/* 323 */
/***/ (function(module, exports, __webpack_require__) {


/* styles */
__webpack_require__(396)

var Component = __webpack_require__(1)(
  /* script */
  __webpack_require__(235),
  /* template */
  __webpack_require__(338),
  /* scopeId */
  null,
  /* cssModules */
  null
)
Component.options.__file = "C:\\OSPanel\\domains\\ohcasey.ru\\www\\resources\\assets\\js\\components\\OptionGroup\\DataTable.vue"
if (Component.esModule && Object.keys(Component.esModule).some(function (key) {return key !== "default" && key !== "__esModule"})) {console.error("named exports are not supported in *.vue files.")}
if (Component.options.functional) {console.error("[vue-loader] DataTable.vue: functional components are not supported with templates, they should use render functions.")}

/* hot reload */
if (false) {(function () {
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), false)
  if (!hotAPI.compatible) return
  module.hot.accept()
  if (!module.hot.data) {
    hotAPI.createRecord("data-v-0eb212ee", Component.options)
  } else {
    hotAPI.reload("data-v-0eb212ee", Component.options)
  }
})()}

module.exports = Component.exports


/***/ }),
/* 324 */
/***/ (function(module, exports, __webpack_require__) {


/* styles */
__webpack_require__(400)

var Component = __webpack_require__(1)(
  /* script */
  __webpack_require__(240),
  /* template */
  __webpack_require__(358),
  /* scopeId */
  null,
  /* cssModules */
  null
)
Component.options.__file = "C:\\OSPanel\\domains\\ohcasey.ru\\www\\resources\\assets\\js\\components\\OptionValue\\DataTable.vue"
if (Component.esModule && Object.keys(Component.esModule).some(function (key) {return key !== "default" && key !== "__esModule"})) {console.error("named exports are not supported in *.vue files.")}
if (Component.options.functional) {console.error("[vue-loader] DataTable.vue: functional components are not supported with templates, they should use render functions.")}

/* hot reload */
if (false) {(function () {
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), false)
  if (!hotAPI.compatible) return
  module.hot.accept()
  if (!module.hot.data) {
    hotAPI.createRecord("data-v-3f929e60", Component.options)
  } else {
    hotAPI.reload("data-v-3f929e60", Component.options)
  }
})()}

module.exports = Component.exports


/***/ }),
/* 325 */
/***/ (function(module, exports, __webpack_require__) {


/* styles */
__webpack_require__(401)

var Component = __webpack_require__(1)(
  /* script */
  __webpack_require__(245),
  /* template */
  __webpack_require__(360),
  /* scopeId */
  null,
  /* cssModules */
  null
)
Component.options.__file = "C:\\OSPanel\\domains\\ohcasey.ru\\www\\resources\\assets\\js\\components\\Option\\DataTable.vue"
if (Component.esModule && Object.keys(Component.esModule).some(function (key) {return key !== "default" && key !== "__esModule"})) {console.error("named exports are not supported in *.vue files.")}
if (Component.options.functional) {console.error("[vue-loader] DataTable.vue: functional components are not supported with templates, they should use render functions.")}

/* hot reload */
if (false) {(function () {
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), false)
  if (!hotAPI.compatible) return
  module.hot.accept()
  if (!module.hot.data) {
    hotAPI.createRecord("data-v-40a4f1cb", Component.options)
  } else {
    hotAPI.reload("data-v-40a4f1cb", Component.options)
  }
})()}

module.exports = Component.exports


/***/ }),
/* 326 */
/***/ (function(module, exports, __webpack_require__) {


/* styles */
__webpack_require__(403)

var Component = __webpack_require__(1)(
  /* script */
  __webpack_require__(250),
  /* template */
  __webpack_require__(366),
  /* scopeId */
  null,
  /* cssModules */
  null
)
Component.options.__file = "C:\\OSPanel\\domains\\ohcasey.ru\\www\\resources\\assets\\js\\components\\Page\\DataTable.vue"
if (Component.esModule && Object.keys(Component.esModule).some(function (key) {return key !== "default" && key !== "__esModule"})) {console.error("named exports are not supported in *.vue files.")}
if (Component.options.functional) {console.error("[vue-loader] DataTable.vue: functional components are not supported with templates, they should use render functions.")}

/* hot reload */
if (false) {(function () {
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), false)
  if (!hotAPI.compatible) return
  module.hot.accept()
  if (!module.hot.data) {
    hotAPI.createRecord("data-v-61b1cca5", Component.options)
  } else {
    hotAPI.reload("data-v-61b1cca5", Component.options)
  }
})()}

module.exports = Component.exports


/***/ }),
/* 327 */
/***/ (function(module, exports, __webpack_require__) {


/* styles */
__webpack_require__(407)

var Component = __webpack_require__(1)(
  /* script */
  __webpack_require__(255),
  /* template */
  __webpack_require__(381),
  /* scopeId */
  null,
  /* cssModules */
  null
)
Component.options.__file = "C:\\OSPanel\\domains\\ohcasey.ru\\www\\resources\\assets\\js\\components\\Product\\DataTable.vue"
if (Component.esModule && Object.keys(Component.esModule).some(function (key) {return key !== "default" && key !== "__esModule"})) {console.error("named exports are not supported in *.vue files.")}
if (Component.options.functional) {console.error("[vue-loader] DataTable.vue: functional components are not supported with templates, they should use render functions.")}

/* hot reload */
if (false) {(function () {
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), false)
  if (!hotAPI.compatible) return
  module.hot.accept()
  if (!module.hot.data) {
    hotAPI.createRecord("data-v-b70500da", Component.options)
  } else {
    hotAPI.reload("data-v-b70500da", Component.options)
  }
})()}

module.exports = Component.exports


/***/ }),
/* 328 */
/***/ (function(module, exports, __webpack_require__) {


/* styles */
__webpack_require__(406)

var Component = __webpack_require__(1)(
  /* script */
  __webpack_require__(260),
  /* template */
  __webpack_require__(379),
  /* scopeId */
  null,
  /* cssModules */
  null
)
Component.options.__file = "C:\\OSPanel\\domains\\ohcasey.ru\\www\\resources\\assets\\js\\components\\Setting\\DataTable.vue"
if (Component.esModule && Object.keys(Component.esModule).some(function (key) {return key !== "default" && key !== "__esModule"})) {console.error("named exports are not supported in *.vue files.")}
if (Component.options.functional) {console.error("[vue-loader] DataTable.vue: functional components are not supported with templates, they should use render functions.")}

/* hot reload */
if (false) {(function () {
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), false)
  if (!hotAPI.compatible) return
  module.hot.accept()
  if (!module.hot.data) {
    hotAPI.createRecord("data-v-a4a77998", Component.options)
  } else {
    hotAPI.reload("data-v-a4a77998", Component.options)
  }
})()}

module.exports = Component.exports


/***/ }),
/* 329 */
/***/ (function(module, exports, __webpack_require__) {


/* styles */
__webpack_require__(404)

var Component = __webpack_require__(1)(
  /* script */
  __webpack_require__(265),
  /* template */
  __webpack_require__(373),
  /* scopeId */
  null,
  /* cssModules */
  null
)
Component.options.__file = "C:\\OSPanel\\domains\\ohcasey.ru\\www\\resources\\assets\\js\\components\\Tag\\DataTable.vue"
if (Component.esModule && Object.keys(Component.esModule).some(function (key) {return key !== "default" && key !== "__esModule"})) {console.error("named exports are not supported in *.vue files.")}
if (Component.options.functional) {console.error("[vue-loader] DataTable.vue: functional components are not supported with templates, they should use render functions.")}

/* hot reload */
if (false) {(function () {
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), false)
  if (!hotAPI.compatible) return
  module.hot.accept()
  if (!module.hot.data) {
    hotAPI.createRecord("data-v-74797e7e", Component.options)
  } else {
    hotAPI.reload("data-v-74797e7e", Component.options)
  }
})()}

module.exports = Component.exports


/***/ }),
/* 330 */,
/* 331 */,
/* 332 */,
/* 333 */
/***/ (function(module, exports, __webpack_require__) {

module.exports={render:function (){var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
  return _c('div', {
    staticClass: "col-lg-7"
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
  }, [_c('label', [_vm._v("Название")]), _vm._v(" "), _c('input', {
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
      "value": (_vm.form.name)
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
    class: [_vm.form.errors.has('slug') ? 'has-error' : '']
  }, [_c('label', [_vm._v("Slug")]), _vm._v(" "), _c('input', {
    directives: [{
      name: "model",
      rawName: "v-model",
      value: (_vm.form.slug),
      expression: "form.slug"
    }],
    staticClass: "form-control",
    attrs: {
      "name": "slug",
      "type": "text"
    },
    domProps: {
      "value": (_vm.form.slug)
    },
    on: {
      "input": function($event) {
        if ($event.target.composing) { return; }
        _vm.form.slug = $event.target.value
      }
    }
  }), _vm._v(" "), (_vm.form.errors.has('slug')) ? _c('span', {
    staticClass: "help-block is-danger",
    domProps: {
      "textContent": _vm._s(_vm.form.errors.get('slug'))
    }
  }) : _vm._e()]), _vm._v(" "), _c('div', {
    staticClass: "form-group",
    class: [_vm.form.errors.has('title') ? 'has-error' : '']
  }, [_c('label', [_vm._v("Title")]), _vm._v(" "), _c('input', {
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
      "value": (_vm.form.title)
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
    class: [_vm.form.errors.has('h1') ? 'has-error' : '']
  }, [_c('label', [_vm._v("H1")]), _vm._v(" "), _c('input', {
    directives: [{
      name: "model",
      rawName: "v-model",
      value: (_vm.form.h1),
      expression: "form.h1"
    }],
    staticClass: "form-control",
    attrs: {
      "name": "h1",
      "type": "text"
    },
    domProps: {
      "value": (_vm.form.h1)
    },
    on: {
      "input": function($event) {
        if ($event.target.composing) { return; }
        _vm.form.h1 = $event.target.value
      }
    }
  }), _vm._v(" "), (_vm.form.errors.has('h1')) ? _c('span', {
    staticClass: "help-block is-danger",
    domProps: {
      "textContent": _vm._s(_vm.form.errors.get('h1'))
    }
  }) : _vm._e()]), _vm._v(" "), _c('div', {
    staticClass: "form-group",
    class: [_vm.form.errors.has('keywords') ? 'has-error' : '']
  }, [_c('label', [_vm._v("Keywords")]), _vm._v(" "), _c('input', {
    directives: [{
      name: "model",
      rawName: "v-model",
      value: (_vm.form.keywords),
      expression: "form.keywords"
    }],
    staticClass: "form-control",
    attrs: {
      "name": "keywords",
      "type": "text"
    },
    domProps: {
      "value": (_vm.form.keywords)
    },
    on: {
      "input": function($event) {
        if ($event.target.composing) { return; }
        _vm.form.keywords = $event.target.value
      }
    }
  }), _vm._v(" "), (_vm.form.errors.has('keywords')) ? _c('span', {
    staticClass: "help-block is-danger",
    domProps: {
      "textContent": _vm._s(_vm.form.errors.get('keywords'))
    }
  }) : _vm._e()]), _vm._v(" "), _c('div', {
    staticClass: "form-group",
    class: [_vm.form.errors.has('description') ? 'has-error' : '']
  }, [_c('label', [_vm._v("Description")]), _vm._v(" "), _c('input', {
    directives: [{
      name: "model",
      rawName: "v-model",
      value: (_vm.form.description),
      expression: "form.description"
    }],
    staticClass: "form-control",
    attrs: {
      "name": "description",
      "type": "text"
    },
    domProps: {
      "value": (_vm.form.description)
    },
    on: {
      "input": function($event) {
        if ($event.target.composing) { return; }
        _vm.form.description = $event.target.value
      }
    }
  }), _vm._v(" "), (_vm.form.errors.has('description')) ? _c('span', {
    staticClass: "help-block is-danger",
    domProps: {
      "textContent": _vm._s(_vm.form.errors.get('description'))
    }
  }) : _vm._e()]), _vm._v(" "), _c('div', {
    staticClass: "form-group",
    class: [_vm.form.errors.has('order') ? 'has-error' : '']
  }, [_c('label', [_vm._v("Порядок сортировки")]), _vm._v(" "), _c('input', {
    directives: [{
      name: "model",
      rawName: "v-model",
      value: (_vm.form.order),
      expression: "form.order"
    }],
    staticClass: "form-control",
    attrs: {
      "name": "order",
      "type": "text"
    },
    domProps: {
      "value": (_vm.form.order)
    },
    on: {
      "input": function($event) {
        if ($event.target.composing) { return; }
        _vm.form.order = $event.target.value
      }
    }
  }), _vm._v(" "), (_vm.form.errors.has('order')) ? _c('span', {
    staticClass: "help-block is-danger",
    domProps: {
      "textContent": _vm._s(_vm.form.errors.get('order'))
    }
  }) : _vm._e()]), _vm._v(" "), _c('div', {
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
        name: 'tag.index'
      }
    }
  }, [_vm._v("Отмена")])], 1)])])])])
},staticRenderFns: []}
module.exports.render._withStripped = true
if (false) {
  module.hot.accept()
  if (module.hot.data) {
     require("vue-hot-reload-api").rerender("data-v-01a7087a", module.exports)
  }
}

/***/ }),
/* 334 */
/***/ (function(module, exports, __webpack_require__) {

module.exports={render:function (){var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
  return _c('layout', [_c('h1', {
    slot: "title"
  }, [_vm._v("Редкатровать товар")]), _vm._v(" "), _c('template', {
    slot: "panel-title"
  }, [_vm._v("Редактирование товара")]), _vm._v(" "), _c('template', {
    slot: "body"
  }, [_c('div', {
    staticClass: "row"
  }, [_c('div', {
    staticClass: "col-md-6"
  }, [_c('product-form', {
    ref: "pForm",
    attrs: {
      "is_create": 0
    }
  })], 1), _vm._v(" "), _c('div', {
    staticClass: "col-md-6"
  }, [_c('offers')], 1)])])], 2)
},staticRenderFns: []}
module.exports.render._withStripped = true
if (false) {
  module.hot.accept()
  if (module.hot.data) {
     require("vue-hot-reload-api").rerender("data-v-02c084a8", module.exports)
  }
}

/***/ }),
/* 335 */
/***/ (function(module, exports, __webpack_require__) {

module.exports={render:function (){var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
  return _c('layout', [_c('h1', {
    slot: "title"
  }, [_vm._v("Новый тег")]), _vm._v(" "), _c('template', {
    slot: "panel-title"
  }, [_vm._v("Добавить тег")]), _vm._v(" "), _c('template', {
    slot: "body"
  }, [_c('tag-form')], 1)], 2)
},staticRenderFns: []}
module.exports.render._withStripped = true
if (false) {
  module.hot.accept()
  if (module.hot.data) {
     require("vue-hot-reload-api").rerender("data-v-04bbd070", module.exports)
  }
}

/***/ }),
/* 336 */,
/* 337 */
/***/ (function(module, exports, __webpack_require__) {

module.exports={render:function (){var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
  return _c('layout', [_c('h1', {
    slot: "title"
  }, [_vm._v("Новая страница")]), _vm._v(" "), _c('template', {
    slot: "panel-title"
  }, [_vm._v("Добавить страницу")]), _vm._v(" "), _c('template', {
    slot: "body"
  }, [_c('page-form')], 1)], 2)
},staticRenderFns: []}
module.exports.render._withStripped = true
if (false) {
  module.hot.accept()
  if (module.hot.data) {
     require("vue-hot-reload-api").rerender("data-v-086380da", module.exports)
  }
}

/***/ }),
/* 338 */
/***/ (function(module, exports, __webpack_require__) {

module.exports={render:function (){var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
  return _c('div', [_c('div', {
    staticClass: "form-group"
  }, [_c('router-link', {
    staticClass: "btn btn-success",
    attrs: {
      "to": {
        name: 'option_group.create'
      },
      "disbled": ""
    }
  }, [_vm._v("Добавть тип товара")])], 1), _vm._v(" "), _c('div', {
    staticClass: "table-responsive"
  }, [_c('table', {
    staticClass: "table table-striped table-bordered table-hover"
  }, [_c('thead', [_c('tr', [_c('th', [_vm._v("id")]), _vm._v(" "), _vm._l((_vm.fields), function(field) {
    return _c('th', [_vm._v(_vm._s(field.name))])
  }), _vm._v(" "), _vm._m(0), _vm._v(" "), _vm._m(1)], 2)]), _vm._v(" "), _c('tbody', _vm._l((_vm.rows.data), function(row) {
    return _c('tr', [_c('td', [_vm._v(_vm._s(row.id))]), _vm._v(" "), _vm._l((_vm.fields), function(field) {
      return _c('td', [_vm._v("\n                        " + _vm._s(row[field.key]) + "\n                    ")])
    }), _vm._v(" "), _c('td', [_c('router-link', {
      attrs: {
        "to": {
          name: 'option_group.show',
          params: {
            id: row.id
          }
        }
      }
    }, [_c('i', {
      staticClass: "fa fa-search",
      attrs: {
        "aria-hidden": "true"
      }
    })])], 1), _vm._v(" "), _c('td', [_c('router-link', {
      attrs: {
        "to": {
          name: 'option_group.edit',
          params: {
            id: row.id
          }
        }
      }
    }, [_c('i', {
      staticClass: "fa fa-pencil",
      attrs: {
        "aria-hidden": "true"
      }
    })])], 1)], 2)
  }))])]), _vm._v(" "), _c('div', {
    staticClass: "col-sm-6"
  }, [_vm._v("\n        Страница " + _vm._s(_vm.rows.current_page) + " из " + _vm._s(_vm.rows.last_page) + ". Запись c " + _vm._s(_vm.rows.from) + " по " + _vm._s(_vm.rows.to) + " из " + _vm._s(_vm.rows.total) + "\n    ")]), _vm._v(" "), _c('div', {
    staticClass: "col-sm-6"
  }, [_c('paginator', {
    attrs: {
      "rows": _vm.rows
    },
    on: {
      "update_table": _vm.updateTable
    }
  })], 1)])
},staticRenderFns: [function (){var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
  return _c('th', [_c('i', {
    staticClass: "fa fa-search",
    attrs: {
      "aria-hidden": "true"
    }
  })])
},function (){var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
  return _c('th', [_c('i', {
    staticClass: "fa fa-pencil",
    attrs: {
      "aria-hidden": "true"
    }
  })])
}]}
module.exports.render._withStripped = true
if (false) {
  module.hot.accept()
  if (module.hot.data) {
     require("vue-hot-reload-api").rerender("data-v-0eb212ee", module.exports)
  }
}

/***/ }),
/* 339 */,
/* 340 */
/***/ (function(module, exports, __webpack_require__) {

module.exports={render:function (){var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
  return _c('layout', [_c('h1', {
    slot: "title"
  }, [_vm._v("Редкатровать опцию")]), _vm._v(" "), _c('template', {
    slot: "panel-title"
  }, [_vm._v("Редактирование опцию")]), _vm._v(" "), _c('template', {
    slot: "body"
  }, [_c('option-form')], 1)], 2)
},staticRenderFns: []}
module.exports.render._withStripped = true
if (false) {
  module.hot.accept()
  if (module.hot.data) {
     require("vue-hot-reload-api").rerender("data-v-1365a628", module.exports)
  }
}

/***/ }),
/* 341 */,
/* 342 */
/***/ (function(module, exports, __webpack_require__) {

module.exports={render:function (){var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
  return _c('layout', [_c('h1', {
    slot: "title"
  }, [_vm._v("Новое значение опции")]), _vm._v(" "), _c('template', {
    slot: "panel-title"
  }, [_vm._v("Добавить значение опиции")]), _vm._v(" "), _c('template', {
    slot: "body"
  }, [_c('optionvalue-form')], 1)], 2)
},staticRenderFns: []}
module.exports.render._withStripped = true
if (false) {
  module.hot.accept()
  if (module.hot.data) {
     require("vue-hot-reload-api").rerender("data-v-19547434", module.exports)
  }
}

/***/ }),
/* 343 */,
/* 344 */
/***/ (function(module, exports, __webpack_require__) {

module.exports={render:function (){var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
  return _c('layout', [_c('h1', {
    slot: "title"
  }, [_vm._v("Редкатрование Торговое предложение")]), _vm._v(" "), _c('template', {
    slot: "panel-title"
  }, [_vm._v("Редкатрование Торговое предложение")]), _vm._v(" "), _c('template', {
    slot: "body"
  }, [_c('offer-form')], 1)], 2)
},staticRenderFns: []}
module.exports.render._withStripped = true
if (false) {
  module.hot.accept()
  if (module.hot.data) {
     require("vue-hot-reload-api").rerender("data-v-1bd1797c", module.exports)
  }
}

/***/ }),
/* 345 */
/***/ (function(module, exports, __webpack_require__) {

module.exports={render:function (){var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
  return _c('layout', [_c('h1', {
    slot: "title"
  }, [_vm._v("Типы товаров")]), _vm._v(" "), _c('template', {
    slot: "panel-title"
  }, [_vm._v("Типы товаров")]), _vm._v(" "), _c('template', {
    slot: "body"
  }, [_c('data-table')], 1)], 2)
},staticRenderFns: []}
module.exports.render._withStripped = true
if (false) {
  module.hot.accept()
  if (module.hot.data) {
     require("vue-hot-reload-api").rerender("data-v-1be52798", module.exports)
  }
}

/***/ }),
/* 346 */
/***/ (function(module, exports, __webpack_require__) {

module.exports={render:function (){var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
  return _c('div', {
    staticClass: "col-lg-7"
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
  }, [_c('label', [_vm._v("Название")]), _vm._v(" "), _c('input', {
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
      "value": (_vm.form.name)
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
    class: [_vm.form.errors.has('delivery_info') ? 'has-error' : '']
  }, [_c('label', [_vm._v("О доставке")]), _vm._v(" "), _c('textarea', {
    directives: [{
      name: "model",
      rawName: "v-model",
      value: (_vm.form.delivery_info),
      expression: "form.delivery_info"
    }],
    staticClass: "form-control",
    attrs: {
      "name": "delivery_info",
      "rows": "10"
    },
    domProps: {
      "value": (_vm.form.delivery_info)
    },
    on: {
      "input": function($event) {
        if ($event.target.composing) { return; }
        _vm.form.delivery_info = $event.target.value
      }
    }
  }), _vm._v(" "), (_vm.form.errors.has('delivery_info')) ? _c('span', {
    staticClass: "help-block is-danger",
    domProps: {
      "textContent": _vm._s(_vm.form.errors.get('delivery_info'))
    }
  }) : _vm._e()]), _vm._v(" "), _c('div', {
    staticClass: "form-group",
    class: [_vm.form.errors.has('options') ? 'has-error' : '']
  }, [_c('label', [_vm._v("Опции")]), _vm._v(" "), _c('select', {
    directives: [{
      name: "model",
      rawName: "v-model",
      value: (_vm.selected_option),
      expression: "selected_option"
    }],
    staticClass: "form-control",
    attrs: {
      "multiple": "",
      "size": "8"
    },
    on: {
      "change": function($event) {
        var $$selectedVal = Array.prototype.filter.call($event.target.options, function(o) {
          return o.selected
        }).map(function(o) {
          var val = "_value" in o ? o._value : o.value;
          return val
        });
        _vm.selected_option = $event.target.multiple ? $$selectedVal : $$selectedVal[0]
      }
    }
  }, _vm._l((this.data.options), function(option) {
    return _c('option', {
      domProps: {
        "value": option.id,
        "innerHTML": _vm._s(option.name)
      }
    })
  })), _vm._v(" "), (_vm.form.errors.has('options')) ? _c('span', {
    staticClass: "help-block is-danger",
    domProps: {
      "textContent": _vm._s(_vm.form.errors.get('options'))
    }
  }) : _vm._e()]), _vm._v(" "), _c('div', {
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
        name: 'option_value.index'
      }
    }
  }, [_vm._v("Отмена")])], 1)])])])])
},staticRenderFns: []}
module.exports.render._withStripped = true
if (false) {
  module.hot.accept()
  if (module.hot.data) {
     require("vue-hot-reload-api").rerender("data-v-1cc123ec", module.exports)
  }
}

/***/ }),
/* 347 */
/***/ (function(module, exports, __webpack_require__) {

module.exports={render:function (){var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
  return _c('layout', [_c('h1', {
    slot: "title"
  }, [_vm._v("Новая опция")]), _vm._v(" "), _c('template', {
    slot: "panel-title"
  }, [_vm._v("Добавить опицю")]), _vm._v(" "), _c('template', {
    slot: "body"
  }, [_c('option-form')], 1)], 2)
},staticRenderFns: []}
module.exports.render._withStripped = true
if (false) {
  module.hot.accept()
  if (module.hot.data) {
     require("vue-hot-reload-api").rerender("data-v-24eef2cc", module.exports)
  }
}

/***/ }),
/* 348 */,
/* 349 */
/***/ (function(module, exports, __webpack_require__) {

module.exports={render:function (){var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
  return _c('layout', [_c('h1', {
    slot: "title"
  }, [_vm._v("Новая настройка")]), _vm._v(" "), _c('template', {
    slot: "panel-title"
  }, [_vm._v("Добавить настройку")]), _vm._v(" "), _c('template', {
    slot: "body"
  }, [_c('setting-form')], 1)], 2)
},staticRenderFns: []}
module.exports.render._withStripped = true
if (false) {
  module.hot.accept()
  if (module.hot.data) {
     require("vue-hot-reload-api").rerender("data-v-2798e748", module.exports)
  }
}

/***/ }),
/* 350 */
/***/ (function(module, exports, __webpack_require__) {

module.exports={render:function (){var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
  return _c('div', {
    staticClass: "col-lg-7"
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
    class: [_vm.form.errors.has('title') ? 'has-error' : '']
  }, [_c('label', [_vm._v("Название")]), _vm._v(" "), _c('input', {
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
      "value": (_vm.form.title)
    },
    on: {
      "input": function($event) {
        if ($event.target.composing) { return; }
        _vm.form.title = $event.target.value
      }
    }
  }), _vm._v(" "), (_vm.form.errors.has('name')) ? _c('span', {
    staticClass: "help-block is-danger",
    domProps: {
      "textContent": _vm._s(_vm.form.errors.get('title'))
    }
  }) : _vm._e()]), _vm._v(" "), _c('div', {
    staticClass: "form-group",
    class: [_vm.form.errors.has('value') ? 'has-error' : '']
  }, [_c('label', [_vm._v("Значение")]), _vm._v(" "), _c('input', {
    directives: [{
      name: "model",
      rawName: "v-model",
      value: (_vm.form.value),
      expression: "form.value"
    }],
    staticClass: "form-control",
    attrs: {
      "name": "value",
      "type": "text"
    },
    domProps: {
      "value": (_vm.form.value)
    },
    on: {
      "input": function($event) {
        if ($event.target.composing) { return; }
        _vm.form.value = $event.target.value
      }
    }
  }), _vm._v(" "), (_vm.form.errors.has('value')) ? _c('span', {
    staticClass: "help-block is-danger",
    domProps: {
      "textContent": _vm._s(_vm.form.errors.get('value'))
    }
  }) : _vm._e()]), _vm._v(" "), _c('div', {
    staticClass: "form-group",
    class: [_vm.form.errors.has('key') ? 'has-error' : '']
  }, [_c('label', [_vm._v("Ключ")]), _vm._v(" "), _c('input', {
    directives: [{
      name: "model",
      rawName: "v-model",
      value: (_vm.form.key),
      expression: "form.key"
    }],
    staticClass: "form-control",
    attrs: {
      "name": "key",
      "type": "text"
    },
    domProps: {
      "value": (_vm.form.key)
    },
    on: {
      "input": function($event) {
        if ($event.target.composing) { return; }
        _vm.form.key = $event.target.value
      }
    }
  }), _vm._v(" "), (_vm.form.errors.has('key')) ? _c('span', {
    staticClass: "help-block is-danger",
    domProps: {
      "textContent": _vm._s(_vm.form.errors.get('key'))
    }
  }) : _vm._e()]), _vm._v(" "), _c('div', {
    staticClass: "form-group",
    class: [_vm.form.errors.has('type') ? 'has-error' : '']
  }, [_c('label', [_vm._v("Тип")]), _vm._v(" "), _c('input', {
    directives: [{
      name: "model",
      rawName: "v-model",
      value: (_vm.form.type),
      expression: "form.type"
    }],
    staticClass: "form-control",
    attrs: {
      "name": "type",
      "type": "text"
    },
    domProps: {
      "value": (_vm.form.type)
    },
    on: {
      "input": function($event) {
        if ($event.target.composing) { return; }
        _vm.form.type = $event.target.value
      }
    }
  }), _vm._v(" "), (_vm.form.errors.has('type')) ? _c('span', {
    staticClass: "help-block is-danger",
    domProps: {
      "textContent": _vm._s(_vm.form.errors.get('type'))
    }
  }) : _vm._e()]), _vm._v(" "), _c('div', {
    staticClass: "form-group",
    class: [_vm.form.errors.has('group') ? 'has-error' : '']
  }, [_c('label', [_vm._v("Группировка")]), _vm._v(" "), _c('input', {
    directives: [{
      name: "model",
      rawName: "v-model",
      value: (_vm.form.group),
      expression: "form.group"
    }],
    staticClass: "form-control",
    attrs: {
      "name": "group",
      "type": "text"
    },
    domProps: {
      "value": (_vm.form.group)
    },
    on: {
      "input": function($event) {
        if ($event.target.composing) { return; }
        _vm.form.group = $event.target.value
      }
    }
  }), _vm._v(" "), (_vm.form.errors.has('group')) ? _c('span', {
    staticClass: "help-block is-danger",
    domProps: {
      "textContent": _vm._s(_vm.form.errors.get('group'))
    }
  }) : _vm._e()]), _vm._v(" "), _c('div', {
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
        name: 'setting.index'
      }
    }
  }, [_vm._v("Отмена")])], 1)])])])])
},staticRenderFns: []}
module.exports.render._withStripped = true
if (false) {
  module.hot.accept()
  if (module.hot.data) {
     require("vue-hot-reload-api").rerender("data-v-2824abf8", module.exports)
  }
}

/***/ }),
/* 351 */,
/* 352 */,
/* 353 */
/***/ (function(module, exports, __webpack_require__) {

module.exports={render:function (){var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
  return _c('layout', [_c('h1', {
    slot: "title"
  }, [_vm._v("Редкатровать настройки")]), _vm._v(" "), _c('template', {
    slot: "panel-title"
  }, [_vm._v("Редактирование настройки")]), _vm._v(" "), _c('template', {
    slot: "body"
  }, [_c('setting-form')], 1)], 2)
},staticRenderFns: []}
module.exports.render._withStripped = true
if (false) {
  module.hot.accept()
  if (module.hot.data) {
     require("vue-hot-reload-api").rerender("data-v-313b346a", module.exports)
  }
}

/***/ }),
/* 354 */,
/* 355 */
/***/ (function(module, exports, __webpack_require__) {

module.exports={render:function (){var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
  return _c('layout', [_c('h1', {
    slot: "title"
  }, [_vm._v("Создание Торговое предложение")]), _vm._v(" "), _c('template', {
    slot: "panel-title"
  }, [_vm._v("Создание Торговое предложение")]), _vm._v(" "), _c('template', {
    slot: "body"
  }, [_c('offer-form')], 1)], 2)
},staticRenderFns: []}
module.exports.render._withStripped = true
if (false) {
  module.hot.accept()
  if (module.hot.data) {
     require("vue-hot-reload-api").rerender("data-v-33717598", module.exports)
  }
}

/***/ }),
/* 356 */,
/* 357 */
/***/ (function(module, exports, __webpack_require__) {

module.exports={render:function (){var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
  return _c('div', {
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
    class: [_vm.form.errors.has('option_group_id') ? 'has-error' : '']
  }, [_c('label', [_vm._v("Тип товара")]), _vm._v(" "), (!this.is_create) ? _c('p', [_c('span', {
    domProps: {
      "textContent": _vm._s(_vm.form.option_group.name)
    }
  })]) : _vm._e(), _vm._v(" "), (!this.is_create) ? _c('input', {
    directives: [{
      name: "model",
      rawName: "v-model",
      value: (_vm.selected_option_group_id),
      expression: "selected_option_group_id"
    }],
    attrs: {
      "type": "hidden"
    },
    domProps: {
      "value": (_vm.selected_option_group_id)
    },
    on: {
      "input": function($event) {
        if ($event.target.composing) { return; }
        _vm.selected_option_group_id = $event.target.value
      }
    }
  }) : _vm._e(), _vm._v(" "), _c('select', {
    directives: [{
      name: "show",
      rawName: "v-show",
      value: (_vm.is_create),
      expression: "is_create"
    }, {
      name: "model",
      rawName: "v-model",
      value: (_vm.selected_option_group_id),
      expression: "selected_option_group_id"
    }],
    staticClass: "form-control",
    on: {
      "change": function($event) {
        var $$selectedVal = Array.prototype.filter.call($event.target.options, function(o) {
          return o.selected
        }).map(function(o) {
          var val = "_value" in o ? o._value : o.value;
          return val
        });
        _vm.selected_option_group_id = $event.target.multiple ? $$selectedVal : $$selectedVal[0]
      }
    }
  }, _vm._l((this.data.option_groups), function(option) {
    return _c('option', {
      domProps: {
        "value": option.id,
        "innerHTML": _vm._s(option.name)
      }
    })
  })), _vm._v(" "), (_vm.form.errors.has('option_group_id')) ? _c('span', {
    staticClass: "help-block is-danger",
    domProps: {
      "textContent": _vm._s(_vm.form.errors.get('option_group_id'))
    }
  }) : _vm._e()]), _vm._v(" "), _c('div', {
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
      "value": (_vm.form.name)
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
    class: [_vm.form.errors.has('background_id') ? 'has-error' : '']
  }, [_c('label', [_vm._v("Картинка товара")]), _vm._v(" "), _c('select', {
    directives: [{
      name: "model",
      rawName: "v-model",
      value: (_vm.selected_background),
      expression: "selected_background"
    }],
    staticClass: "form-control",
    on: {
      "change": function($event) {
        var $$selectedVal = Array.prototype.filter.call($event.target.options, function(o) {
          return o.selected
        }).map(function(o) {
          var val = "_value" in o ? o._value : o.value;
          return val
        });
        _vm.selected_background = $event.target.multiple ? $$selectedVal : $$selectedVal[0]
      }
    }
  }, _vm._l((this.data.background_options), function(option) {
    return _c('option', {
      domProps: {
        "value": option.id,
        "innerHTML": _vm._s(option.name)
      }
    })
  })), _vm._v(" "), (_vm.form.errors.has('background_id')) ? _c('span', {
    staticClass: "help-block is-danger",
    domProps: {
      "textContent": _vm._s(_vm.form.errors.get('background_id'))
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
      "value": (_vm.form.code)
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
      "value": (_vm.form.title)
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
    class: [_vm.form.errors.has('keywords') ? 'has-error' : '']
  }, [_c('label', [_vm._v("Keywords")]), _vm._v(" "), _c('input', {
    directives: [{
      name: "model",
      rawName: "v-model",
      value: (_vm.form.keywords),
      expression: "form.keywords"
    }],
    staticClass: "form-control",
    attrs: {
      "name": "keywords",
      "type": "text"
    },
    domProps: {
      "value": (_vm.form.keywords)
    },
    on: {
      "input": function($event) {
        if ($event.target.composing) { return; }
        _vm.form.keywords = $event.target.value
      }
    }
  }), _vm._v(" "), (_vm.form.errors.has('keywords')) ? _c('span', {
    staticClass: "help-block is-danger",
    domProps: {
      "textContent": _vm._s(_vm.form.errors.get('keywords'))
    }
  }) : _vm._e()]), _vm._v(" "), _c('div', {
    staticClass: "form-group",
    class: [_vm.form.errors.has('h1') ? 'has-error' : '']
  }, [_c('label', [_vm._v("H1")]), _vm._v(" "), _c('input', {
    directives: [{
      name: "model",
      rawName: "v-model",
      value: (_vm.form.h1),
      expression: "form.h1"
    }],
    staticClass: "form-control",
    attrs: {
      "name": "h1",
      "type": "text"
    },
    domProps: {
      "value": (_vm.form.h1)
    },
    on: {
      "input": function($event) {
        if ($event.target.composing) { return; }
        _vm.form.h1 = $event.target.value
      }
    }
  }), _vm._v(" "), (_vm.form.errors.has('h1')) ? _c('span', {
    staticClass: "help-block is-danger",
    domProps: {
      "textContent": _vm._s(_vm.form.errors.get('h1'))
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
        var $$selectedVal = Array.prototype.filter.call($event.target.options, function(o) {
          return o.selected
        }).map(function(o) {
          var val = "_value" in o ? o._value : o.value;
          return val
        });
        _vm.selected = $event.target.multiple ? $$selectedVal : $$selectedVal[0]
      }
    }
  }, _vm._l((this.data.category_options), function(option) {
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
      "value": (_vm.form.description)
    },
    on: {
      "input": function($event) {
        if ($event.target.composing) { return; }
        _vm.form.description = $event.target.value
      }
    }
  }), _vm._v(" "), _c('quill-editor', {
    model: {
      value: (_vm.form.description),
      callback: function($$v) {
        _vm.form.description = $$v
      },
      expression: "form.description"
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
      "value": (_vm.form.price)
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
  }, [_c('label', [_vm._v("Старая цена")]), _vm._v(" "), _c('input', {
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
      "value": (_vm.form.discount)
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
    staticClass: "form-group",
    class: [_vm.form.errors.has('order') ? 'has-error' : '']
  }, [_c('label', [_vm._v("Порядок")]), _vm._v(" "), _c('input', {
    directives: [{
      name: "model",
      rawName: "v-model",
      value: (_vm.form.order),
      expression: "form.order"
    }],
    staticClass: "form-control",
    attrs: {
      "name": "order",
      "type": "text"
    },
    domProps: {
      "value": (_vm.form.order)
    },
    on: {
      "input": function($event) {
        if ($event.target.composing) { return; }
        _vm.form.order = $event.target.value
      }
    }
  }), _vm._v(" "), (_vm.form.errors.has('order')) ? _c('span', {
    staticClass: "help-block is-danger",
    domProps: {
      "textContent": _vm._s(_vm.form.errors.get('order'))
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
      "__c": function($event) {
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
  }), _vm._v(" Активен\n                ")])]), _vm._v(" "), _c('div', {
    staticClass: "checkbox"
  }, [_c('label', [_c('input', {
    directives: [{
      name: "model",
      rawName: "v-model",
      value: (_vm.form.bestseller),
      expression: "form.bestseller"
    }],
    attrs: {
      "type": "checkbox"
    },
    domProps: {
      "checked": Array.isArray(_vm.form.bestseller) ? _vm._i(_vm.form.bestseller, null) > -1 : (_vm.form.bestseller)
    },
    on: {
      "__c": function($event) {
        var $$a = _vm.form.bestseller,
          $$el = $event.target,
          $$c = $$el.checked ? (true) : (false);
        if (Array.isArray($$a)) {
          var $$v = null,
            $$i = _vm._i($$a, $$v);
          if ($$c) {
            $$i < 0 && (_vm.form.bestseller = $$a.concat($$v))
          } else {
            $$i > -1 && (_vm.form.bestseller = $$a.slice(0, $$i).concat($$a.slice($$i + 1)))
          }
        } else {
          _vm.form.bestseller = $$c
        }
      }
    }
  }), _vm._v(" Бестселлер\n                ")])]), _vm._v(" "), _c('div', {
    staticClass: "checkbox"
  }, [_c('label', [_c('input', {
    directives: [{
      name: "model",
      rawName: "v-model",
      value: (_vm.form.sale),
      expression: "form.sale"
    }],
    attrs: {
      "type": "checkbox"
    },
    domProps: {
      "checked": Array.isArray(_vm.form.sale) ? _vm._i(_vm.form.sale, null) > -1 : (_vm.form.sale)
    },
    on: {
      "__c": function($event) {
        var $$a = _vm.form.sale,
          $$el = $event.target,
          $$c = $$el.checked ? (true) : (false);
        if (Array.isArray($$a)) {
          var $$v = null,
            $$i = _vm._i($$a, $$v);
          if ($$c) {
            $$i < 0 && (_vm.form.sale = $$a.concat($$v))
          } else {
            $$i > -1 && (_vm.form.sale = $$a.slice(0, $$i).concat($$a.slice($$i + 1)))
          }
        } else {
          _vm.form.sale = $$c
        }
      }
    }
  }), _vm._v(" Акция\n                ")])]), _vm._v(" "), _c('div', {
    staticClass: "checkbox"
  }, [_c('label', [_c('input', {
    directives: [{
      name: "model",
      rawName: "v-model",
      value: (_vm.form.hit),
      expression: "form.hit"
    }],
    attrs: {
      "type": "checkbox"
    },
    domProps: {
      "checked": Array.isArray(_vm.form.hit) ? _vm._i(_vm.form.hit, null) > -1 : (_vm.form.hit)
    },
    on: {
      "__c": function($event) {
        var $$a = _vm.form.hit,
          $$el = $event.target,
          $$c = $$el.checked ? (true) : (false);
        if (Array.isArray($$a)) {
          var $$v = null,
            $$i = _vm._i($$a, $$v);
          if ($$c) {
            $$i < 0 && (_vm.form.hit = $$a.concat($$v))
          } else {
            $$i > -1 && (_vm.form.hit = $$a.slice(0, $$i).concat($$a.slice($$i + 1)))
          }
        } else {
          _vm.form.hit = $$c
        }
      }
    }
  }), _vm._v(" Хит\n                ")])]), _vm._v(" "), _c('div', {
    staticClass: "checkbox"
  }, [_c('label', [_c('input', {
    directives: [{
      name: "model",
      rawName: "v-model",
      value: (_vm.form.maket_photo),
      expression: "form.maket_photo"
    }],
    attrs: {
      "type": "checkbox"
    },
    domProps: {
      "checked": Array.isArray(_vm.form.maket_photo) ? _vm._i(_vm.form.maket_photo, null) > -1 : (_vm.form.maket_photo)
    },
    on: {
      "__c": function($event) {
        var $$a = _vm.form.maket_photo,
          $$el = $event.target,
          $$c = $$el.checked ? (true) : (false);
        if (Array.isArray($$a)) {
          var $$v = null,
            $$i = _vm._i($$a, $$v);
          if ($$c) {
            $$i < 0 && (_vm.form.maket_photo = $$a.concat($$v))
          } else {
            $$i > -1 && (_vm.form.maket_photo = $$a.slice(0, $$i).concat($$a.slice($$i + 1)))
          }
        } else {
          _vm.form.maket_photo = $$c
        }
      }
    }
  }), _vm._v(" Макет как основное фото\n                ")])]), _vm._v(" "), _c('div', {
    staticClass: "form-group"
  }, [(_vm.photos.length) ? _c('div', {
    staticClass: "photos"
  }, _vm._l((_vm.photos), function(photo, index) {
    return _c('div', {
      staticClass: "photo-block"
    }, [_c('div', {
      staticClass: "photo",
      class: photo.default ? 'default' : ''
    }, [_c('a', {
      on: {
        "click": function($event) {
          _vm.clickDefault(photo.id, index)
        }
      }
    }, [_c('img', {
      attrs: {
        "src": photo.url,
        "alt": "",
        "width": "150px"
      }
    })])]), _vm._v(" "), _c('a', {
      on: {
        "click": function($event) {
          _vm.removePhoto(photo)
        }
      }
    }, [_vm._v("Удалить")])])
  })) : _vm._e()]), _vm._v(" "), _vm._m(0), _vm._v(" "), _c('div', {
    staticClass: "form-group"
  }, [_c('label', [_vm._v("Теги")]), _vm._v(" "), _c('div', {
    staticClass: "well"
  }, [_c('table', {
    staticClass: "table"
  }, _vm._l((this.form.tags), function(item) {
    return _c('tr', [_c('td', [_vm._v(_vm._s(item.name))]), _vm._v(" "), _c('td', [_c('a', {
      on: {
        "click": function($event) {
          _vm.removeTag(item.id)
        }
      }
    }, [_vm._v("Удалить")])])])
  })), _vm._v(" "), _c('div', {
    staticClass: "form-inline"
  }, [_vm._m(1), _vm._v(" "), _c('div', {
    staticClass: "form-group"
  }, [_c('button', {
    staticClass: "btn btn-default btn-primary",
    attrs: {
      "type": "button"
    },
    on: {
      "click": function($event) {
        _vm.addTag()
      }
    }
  }, [_vm._v("Добавить")])])])])]), _vm._v(" "), _c('div', {
    staticClass: "form-group"
  }, [_c('label', [_vm._v("Сопутствующие товары")]), _vm._v(" "), _c('div', {
    staticClass: "well"
  }, [_c('table', {
    staticClass: "table"
  }, _vm._l((this.form.related), function(item) {
    return _c('tr', [_c('td', [_vm._v(_vm._s(item.name))]), _vm._v(" "), _c('td', [_c('a', {
      on: {
        "click": function($event) {
          _vm.removeRelated(item.id)
        }
      }
    }, [_vm._v("Удалить")])])])
  })), _vm._v(" "), _c('div', {
    staticClass: "form-inline"
  }, [_vm._m(2), _vm._v(" "), _c('div', {
    staticClass: "form-group"
  }, [_c('button', {
    staticClass: "btn btn-default btn-primary",
    attrs: {
      "type": "button"
    },
    on: {
      "click": function($event) {
        _vm.addRelated()
      }
    }
  }, [_vm._v("Добавить")])])])])]), _vm._v(" "), _c('div', {
    staticClass: "form-group"
  }, [_c('label', [_vm._v("Описание товара ")]), _vm._v(" "), _c('textarea', {
    directives: [{
      name: "model",
      rawName: "v-model",
      value: (_vm.form.htmlDescription),
      expression: "form.htmlDescription"
    }],
    staticClass: "form-control",
    staticStyle: {
      "resize": "vertical"
    },
    attrs: {
      "name": "bigText",
      "type": "text",
      "rows": "30"
    },
    domProps: {
      "value": (_vm.form.htmlDescription)
    },
    on: {
      "input": function($event) {
        if ($event.target.composing) { return; }
        _vm.form.htmlDescription = $event.target.value
      }
    }
  })]), _vm._v(" "), _c('div', {
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
  }, [_vm._v("Отмена")])], 1)])])])
},staticRenderFns: [function (){var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
  return _c('div', {
    staticClass: "form-group clear"
  }, [_c('div', {
    staticClass: "dropzone",
    attrs: {
      "id": "dz",
      "action": "/"
    }
  }, [_c('div', {
    staticClass: "fallback"
  }, [_c('input', {
    attrs: {
      "name": "file",
      "type": "file",
      "multiple": ""
    }
  })])])])
},function (){var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
  return _c('div', {
    staticClass: "form-group"
  }, [_c('select', {
    staticClass: "js-data-tags-ajax form-control"
  }, [_c('option', {
    attrs: {
      "value": ""
    }
  }, [_vm._v("Выберите тег")])])])
},function (){var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
  return _c('div', {
    staticClass: "form-group"
  }, [_c('select', {
    staticClass: "js-data-example-ajax form-control"
  }, [_c('option', {
    attrs: {
      "value": ""
    }
  }, [_vm._v("Выберите сопутствующий товар")])])])
}]}
module.exports.render._withStripped = true
if (false) {
  module.hot.accept()
  if (module.hot.data) {
     require("vue-hot-reload-api").rerender("data-v-37dc2376", module.exports)
  }
}

/***/ }),
/* 358 */
/***/ (function(module, exports, __webpack_require__) {

module.exports={render:function (){var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
  return _c('div', [_c('div', {
    staticClass: "form-group"
  }, [_c('router-link', {
    staticClass: "btn btn-success",
    attrs: {
      "to": {
        name: 'option_value.create'
      }
    }
  }, [_vm._v("Добавть значение")])], 1), _vm._v(" "), _c('div', {
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
      "value": (_vm.search)
    },
    on: {
      "keyup": function($event) {
        if (!('button' in $event) && _vm._k($event.keyCode, "enter", 13)) { return null; }
        _vm.searchRows()
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
        _vm.searchRows()
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
        var $$selectedVal = Array.prototype.filter.call($event.target.options, function(o) {
          return o.selected
        }).map(function(o) {
          var val = "_value" in o ? o._value : o.value;
          return val
        });
        _vm.category = $event.target.multiple ? $$selectedVal : $$selectedVal[0]
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
  }))])])]), _vm._v(" "), _c('div', {
    staticClass: "table-responsive"
  }, [_c('table', {
    staticClass: "table table-striped table-bordered table-hover"
  }, [_c('thead', [_c('tr', [_c('th', [_vm._v("id")]), _vm._v(" "), _vm._l((_vm.fields), function(field) {
    return _c('th', [_vm._v(_vm._s(field.name))])
  }), _vm._v(" "), _vm._m(0), _vm._v(" "), _vm._m(1)], 2)]), _vm._v(" "), _c('tbody', _vm._l((_vm.rows.data), function(row) {
    return _c('tr', [_c('td', [_vm._v(_vm._s(row.id))]), _vm._v(" "), _vm._l((_vm.fields), function(field) {
      return _c('td', [_vm._v("\n                        " + _vm._s(row[field.key]) + "\n                    ")])
    }), _vm._v(" "), _c('td', [_c('router-link', {
      attrs: {
        "to": {
          name: 'option_value.edit',
          params: {
            id: row.id
          }
        }
      }
    }, [_c('i', {
      staticClass: "fa fa-pencil",
      attrs: {
        "aria-hidden": "true"
      }
    })])], 1), _vm._v(" "), _c('td', [_c('a', {
      attrs: {
        "id": 'delete-' + row.id,
        "data-confirm": "Удалить опцию?"
      },
      on: {
        "click": function($event) {
          _vm.destroy(row.id)
        }
      }
    }, [_c('i', {
      staticClass: "fa fa-trash-o",
      attrs: {
        "aria-hidden": "true"
      }
    })])])], 2)
  }))])]), _vm._v(" "), _c('div', {
    staticClass: "col-sm-6"
  }, [_vm._v("\n        Страница " + _vm._s(_vm.rows.current_page) + " из " + _vm._s(_vm.rows.last_page) + ". Запись c " + _vm._s(_vm.rows.from) + " по " + _vm._s(_vm.rows.to) + " из " + _vm._s(_vm.rows.total) + "\n    ")]), _vm._v(" "), _c('div', {
    staticClass: "col-sm-6"
  }, [_c('paginator', {
    attrs: {
      "rows": _vm.rows
    },
    on: {
      "update_table": _vm.updateTable
    }
  })], 1)])
},staticRenderFns: [function (){var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
  return _c('th', [_c('i', {
    staticClass: "fa fa-pencil",
    attrs: {
      "aria-hidden": "true"
    }
  })])
},function (){var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
  return _c('th', [_c('i', {
    staticClass: "fa fa-trash-o",
    attrs: {
      "aria-hidden": "true"
    }
  })])
}]}
module.exports.render._withStripped = true
if (false) {
  module.hot.accept()
  if (module.hot.data) {
     require("vue-hot-reload-api").rerender("data-v-3f929e60", module.exports)
  }
}

/***/ }),
/* 359 */,
/* 360 */
/***/ (function(module, exports, __webpack_require__) {

module.exports={render:function (){var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
  return _c('div', [_c('div', {
    staticClass: "form-group"
  }, [_c('router-link', {
    staticClass: "btn btn-success",
    attrs: {
      "to": {
        name: 'option.create'
      }
    }
  }, [_vm._v("Добавть опцию")])], 1), _vm._v(" "), _c('div', {
    staticClass: "row"
  }, [_c('div', {
    staticClass: "col-sm-5 left"
  }, [_c('div', {
    staticClass: "form-group input-group"
  }, [_vm._t("searchfilter", [_c('input', {
    directives: [{
      name: "model",
      rawName: "v-model",
      value: (_vm.search),
      expression: "search"
    }],
    staticClass: "form-control",
    attrs: {
      "type": "text",
      "placeholder": ""
    },
    domProps: {
      "value": (_vm.search)
    },
    on: {
      "keyup": function($event) {
        if (!('button' in $event) && _vm._k($event.keyCode, "enter", 13)) { return null; }
        _vm.searchRows()
      },
      "input": function($event) {
        if ($event.target.composing) { return; }
        _vm.search = $event.target.value
      }
    }
  })], {
    search: _vm.search
  }), _vm._v(" "), _c('span', {
    staticClass: "input-group-btn"
  }, [_c('a', {
    staticClass: "btn btn-primary",
    on: {
      "click": function($event) {
        _vm.searchRows()
      }
    }
  }, [_c('i', {
    staticClass: "fa fa-search"
  })])])], 2)])]), _vm._v(" "), _c('div', {
    staticClass: "table-responsive"
  }, [_c('table', {
    staticClass: "table table-striped table-bordered table-hover"
  }, [_c('thead', [_c('tr', [_c('th', [_vm._v("id")]), _vm._v(" "), _vm._l((_vm.fields), function(field) {
    return _c('th', [_vm._v(_vm._s(field.name))])
  }), _vm._v(" "), _vm._m(0), _vm._v(" "), _vm._m(1)], 2)]), _vm._v(" "), _c('tbody', _vm._l((_vm.rows.data), function(row) {
    return _c('tr', [_c('td', [_vm._v(_vm._s(row.id))]), _vm._v(" "), _vm._l((_vm.fields), function(field) {
      return _c('td', [_vm._v("\n                        " + _vm._s(row[field.key]) + "\n                    ")])
    }), _vm._v(" "), _c('td', [_c('router-link', {
      attrs: {
        "to": {
          name: 'option.edit',
          params: {
            id: row.id
          }
        }
      }
    }, [_c('i', {
      staticClass: "fa fa-pencil",
      attrs: {
        "aria-hidden": "true"
      }
    })])], 1), _vm._v(" "), _c('td', [_c('a', {
      attrs: {
        "id": 'delete-' + row.id,
        "data-confirm": "Удалить опцию?"
      },
      on: {
        "click": function($event) {
          _vm.destroy({
            id: row.id,
            key: 'option'
          })
        }
      }
    }, [_c('i', {
      staticClass: "fa fa-trash-o",
      attrs: {
        "aria-hidden": "true"
      }
    })])])], 2)
  }))])]), _vm._v(" "), _c('div', {
    staticClass: "col-sm-6"
  }, [_vm._v("\n        Страница " + _vm._s(_vm.rows.current_page) + " из " + _vm._s(_vm.rows.last_page) + ". Запись c " + _vm._s(_vm.rows.from) + " по " + _vm._s(_vm.rows.to) + " из " + _vm._s(_vm.rows.total) + "\n    ")]), _vm._v(" "), _c('div', {
    staticClass: "col-sm-6"
  }, [_c('paginator', {
    attrs: {
      "rows": _vm.rows
    },
    on: {
      "update_table": _vm.updateTable
    }
  })], 1)])
},staticRenderFns: [function (){var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
  return _c('th', [_c('i', {
    staticClass: "fa fa-pencil",
    attrs: {
      "aria-hidden": "true"
    }
  })])
},function (){var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
  return _c('th', [_c('i', {
    staticClass: "fa fa-trash-o",
    attrs: {
      "aria-hidden": "true"
    }
  })])
}]}
module.exports.render._withStripped = true
if (false) {
  module.hot.accept()
  if (module.hot.data) {
     require("vue-hot-reload-api").rerender("data-v-40a4f1cb", module.exports)
  }
}

/***/ }),
/* 361 */
/***/ (function(module, exports, __webpack_require__) {

module.exports={render:function (){var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
  return _c('div', {
    staticClass: "col-lg-7"
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
    class: [_vm.form.errors.has('key') ? 'has-error' : '']
  }, [_c('label', [_vm._v("Ключ")]), _vm._v(" "), _c('input', {
    directives: [{
      name: "model",
      rawName: "v-model",
      value: (_vm.form.key),
      expression: "form.key"
    }],
    staticClass: "form-control",
    attrs: {
      "name": "key",
      "type": "text"
    },
    domProps: {
      "value": (_vm.form.key)
    },
    on: {
      "input": function($event) {
        if ($event.target.composing) { return; }
        _vm.form.key = $event.target.value
      }
    }
  }), _vm._v(" "), (_vm.form.errors.has('key')) ? _c('span', {
    staticClass: "help-block is-danger",
    domProps: {
      "textContent": _vm._s(_vm.form.errors.get('key'))
    }
  }) : _vm._e()]), _vm._v(" "), _c('div', {
    staticClass: "form-group",
    class: [_vm.form.errors.has('name') ? 'has-error' : '']
  }, [_c('label', [_vm._v("Название опции")]), _vm._v(" "), _c('input', {
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
      "value": (_vm.form.name)
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
    class: [_vm.form.errors.has('order') ? 'has-error' : '']
  }, [_c('label', [_vm._v("Порядок сортировки")]), _vm._v(" "), _c('input', {
    directives: [{
      name: "model",
      rawName: "v-model",
      value: (_vm.form.order),
      expression: "form.order"
    }],
    staticClass: "form-control",
    attrs: {
      "name": "order",
      "type": "text"
    },
    domProps: {
      "value": (_vm.form.order)
    },
    on: {
      "input": function($event) {
        if ($event.target.composing) { return; }
        _vm.form.order = $event.target.value
      }
    }
  }), _vm._v(" "), (_vm.form.errors.has('order')) ? _c('span', {
    staticClass: "help-block is-danger",
    domProps: {
      "textContent": _vm._s(_vm.form.errors.get('order'))
    }
  }) : _vm._e()]), _vm._v(" "), _c('div', {
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
        name: 'option.index'
      }
    }
  }, [_vm._v("Отмена")])], 1)])])])])
},staticRenderFns: []}
module.exports.render._withStripped = true
if (false) {
  module.hot.accept()
  if (module.hot.data) {
     require("vue-hot-reload-api").rerender("data-v-4ca9f90d", module.exports)
  }
}

/***/ }),
/* 362 */
/***/ (function(module, exports, __webpack_require__) {

module.exports={render:function (){var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
  return _c('layout', [_c('h1', {
    slot: "title"
  }, [_vm._v("Редкатровать страницу")]), _vm._v(" "), _c('template', {
    slot: "panel-title"
  }, [_vm._v("Редактирование страницу")]), _vm._v(" "), _c('template', {
    slot: "body"
  }, [_c('page-form')], 1)], 2)
},staticRenderFns: []}
module.exports.render._withStripped = true
if (false) {
  module.hot.accept()
  if (module.hot.data) {
     require("vue-hot-reload-api").rerender("data-v-513a3068", module.exports)
  }
}

/***/ }),
/* 363 */,
/* 364 */,
/* 365 */
/***/ (function(module, exports, __webpack_require__) {

module.exports={render:function (){var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
  return _c('layout', [_c('h1', {
    slot: "title"
  }, [_vm._v("Список опций")]), _vm._v(" "), _c('template', {
    slot: "panel-title"
  }, [_vm._v("Значения опций")]), _vm._v(" "), _c('template', {
    slot: "body"
  }, [_c('data-table')], 1)], 2)
},staticRenderFns: []}
module.exports.render._withStripped = true
if (false) {
  module.hot.accept()
  if (module.hot.data) {
     require("vue-hot-reload-api").rerender("data-v-5f6d0bfe", module.exports)
  }
}

/***/ }),
/* 366 */
/***/ (function(module, exports, __webpack_require__) {

module.exports={render:function (){var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
  return _c('div', [_c('div', {
    staticClass: "form-group"
  }, [_c('router-link', {
    staticClass: "btn btn-success",
    attrs: {
      "to": {
        name: 'page.create'
      }
    }
  }, [_vm._v("Добавть страницу")])], 1), _vm._v(" "), _c('div', {
    staticClass: "row"
  }, [_c('div', {
    staticClass: "col-sm-5 left"
  }, [_c('div', {
    staticClass: "form-group input-group"
  }, [_vm._t("searchfilter", [_c('input', {
    directives: [{
      name: "model",
      rawName: "v-model",
      value: (_vm.search),
      expression: "search"
    }],
    staticClass: "form-control",
    attrs: {
      "type": "text",
      "placeholder": ""
    },
    domProps: {
      "value": (_vm.search)
    },
    on: {
      "keyup": function($event) {
        if (!('button' in $event) && _vm._k($event.keyCode, "enter", 13)) { return null; }
        _vm.searchRows()
      },
      "input": function($event) {
        if ($event.target.composing) { return; }
        _vm.search = $event.target.value
      }
    }
  })], {
    search: _vm.search
  }), _vm._v(" "), _c('span', {
    staticClass: "input-group-btn"
  }, [_c('a', {
    staticClass: "btn btn-primary",
    on: {
      "click": function($event) {
        _vm.searchRows()
      }
    }
  }, [_c('i', {
    staticClass: "fa fa-search"
  })])])], 2)])]), _vm._v(" "), _c('div', {
    staticClass: "table-responsive"
  }, [_c('table', {
    staticClass: "table table-striped table-bordered table-hover"
  }, [_c('thead', [_c('tr', [_c('th', [_vm._v("id")]), _vm._v(" "), _vm._l((_vm.fields), function(field) {
    return _c('th', [_vm._v(_vm._s(field.name))])
  }), _vm._v(" "), _vm._m(0), _vm._v(" "), _vm._m(1)], 2)]), _vm._v(" "), _c('tbody', _vm._l((_vm.rows.data), function(row) {
    return _c('tr', [_c('td', [_vm._v(_vm._s(row.id))]), _vm._v(" "), _vm._l((_vm.fields), function(field) {
      return _c('td', [_vm._v("\n                        " + _vm._s(row[field.key]) + "\n                    ")])
    }), _vm._v(" "), _c('td', [_c('router-link', {
      attrs: {
        "to": {
          name: 'page.edit',
          params: {
            id: row.id
          }
        }
      }
    }, [_c('i', {
      staticClass: "fa fa-pencil",
      attrs: {
        "aria-hidden": "true"
      }
    })])], 1), _vm._v(" "), _c('td', [_c('a', {
      attrs: {
        "id": 'delete-' + row.id,
        "data-confirm": "Удалить страницу?"
      },
      on: {
        "click": function($event) {
          _vm.destroy({
            id: row.id,
            key: 'page'
          })
        }
      }
    }, [_c('i', {
      staticClass: "fa fa-trash-o",
      attrs: {
        "aria-hidden": "true"
      }
    })])])], 2)
  }))])]), _vm._v(" "), _c('div', {
    staticClass: "col-sm-6"
  }, [_vm._v("\n        Страница " + _vm._s(_vm.rows.current_page) + " из " + _vm._s(_vm.rows.last_page) + ". Запись c " + _vm._s(_vm.rows.from) + " по " + _vm._s(_vm.rows.to) + " из " + _vm._s(_vm.rows.total) + "\n    ")]), _vm._v(" "), _c('div', {
    staticClass: "col-sm-6"
  }, [(_vm.rows.last_page > 1) ? _c('ul', {
    staticClass: "pagination",
    staticStyle: {
      "margin": "2px 0",
      "float": "right"
    }
  }, [(_vm.rows.prev_page_url) ? _c('li', [_c('a', {
    on: {
      "click": function($event) {
        _vm.changePage(1)
      }
    }
  }, [_vm._v("«")])]) : _vm._e(), _vm._v(" "), (_vm.rows.prev_page_url) ? _c('li', [_c('a', {
    on: {
      "click": function($event) {
        _vm.changePage(_vm.rows.current_page - 1)
      }
    }
  }, [_vm._v("‹")])]) : _vm._e(), _vm._v(" "), _vm._l((_vm.pagesNumbers), function(page) {
    return _c('li', {
      staticClass: "paginate_button",
      class: [(page == _vm.rows.current_page) ? 'active' : '']
    }, [_c('a', {
      on: {
        "click": function($event) {
          _vm.changePage(page)
        }
      }
    }, [_vm._v(_vm._s(page))])])
  }), _vm._v(" "), (_vm.rows.next_page_url) ? _c('li', [_c('a', {
    on: {
      "click": function($event) {
        _vm.changePage(_vm.rows.current_page + 1)
      }
    }
  }, [_vm._v("›")])]) : _vm._e(), _vm._v(" "), (_vm.rows.next_page_url) ? _c('li', [_c('a', {
    on: {
      "click": function($event) {
        _vm.changePage(_vm.rows.last_page)
      }
    }
  }, [_vm._v("»")])]) : _vm._e()], 2) : _vm._e()])])
},staticRenderFns: [function (){var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
  return _c('th', [_c('i', {
    staticClass: "fa fa-pencil",
    attrs: {
      "aria-hidden": "true"
    }
  })])
},function (){var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
  return _c('th', [_c('i', {
    staticClass: "fa fa-trash-o",
    attrs: {
      "aria-hidden": "true"
    }
  })])
}]}
module.exports.render._withStripped = true
if (false) {
  module.hot.accept()
  if (module.hot.data) {
     require("vue-hot-reload-api").rerender("data-v-61b1cca5", module.exports)
  }
}

/***/ }),
/* 367 */,
/* 368 */
/***/ (function(module, exports, __webpack_require__) {

module.exports={render:function (){var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
  return _c('layout', [_c('h1', {
    slot: "title"
  }, [_vm._v("Список настроек")]), _vm._v(" "), _c('template', {
    slot: "panel-title"
  }, [_vm._v("Значения настроек")]), _vm._v(" "), _c('template', {
    slot: "body"
  }, [_c('data-table')], 1)], 2)
},staticRenderFns: []}
module.exports.render._withStripped = true
if (false) {
  module.hot.accept()
  if (module.hot.data) {
     require("vue-hot-reload-api").rerender("data-v-65380b40", module.exports)
  }
}

/***/ }),
/* 369 */,
/* 370 */,
/* 371 */,
/* 372 */
/***/ (function(module, exports, __webpack_require__) {

module.exports={render:function (){var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
  return _c('layout', [_c('h1', {
    slot: "title"
  }, [_vm._v("Новый товар")]), _vm._v(" "), _c('template', {
    slot: "panel-title"
  }, [_vm._v("Добавить товар")]), _vm._v(" "), _c('template', {
    slot: "body"
  }, [_c('product-form', {
    attrs: {
      "is_create": 1
    }
  })], 1)], 2)
},staticRenderFns: []}
module.exports.render._withStripped = true
if (false) {
  module.hot.accept()
  if (module.hot.data) {
     require("vue-hot-reload-api").rerender("data-v-71a5c51a", module.exports)
  }
}

/***/ }),
/* 373 */
/***/ (function(module, exports, __webpack_require__) {

module.exports={render:function (){var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
  return _c('div', [_c('div', {
    staticClass: "form-group"
  }, [_c('router-link', {
    staticClass: "btn btn-success",
    attrs: {
      "to": {
        name: 'tag.create'
      }
    }
  }, [_vm._v("Добавть тег")])], 1), _vm._v(" "), _c('div', {
    staticClass: "row"
  }, [_c('div', {
    staticClass: "col-sm-5 left"
  }, [_c('div', {
    staticClass: "form-group input-group"
  }, [_vm._t("searchfilter", [_c('input', {
    directives: [{
      name: "model",
      rawName: "v-model",
      value: (_vm.search),
      expression: "search"
    }],
    staticClass: "form-control",
    attrs: {
      "type": "text",
      "placeholder": ""
    },
    domProps: {
      "value": (_vm.search)
    },
    on: {
      "keyup": function($event) {
        if (!('button' in $event) && _vm._k($event.keyCode, "enter", 13)) { return null; }
        _vm.searchRows()
      },
      "input": function($event) {
        if ($event.target.composing) { return; }
        _vm.search = $event.target.value
      }
    }
  })], {
    search: _vm.search
  }), _vm._v(" "), _c('span', {
    staticClass: "input-group-btn"
  }, [_c('a', {
    staticClass: "btn btn-primary",
    on: {
      "click": function($event) {
        _vm.searchRows()
      }
    }
  }, [_c('i', {
    staticClass: "fa fa-search"
  })])])], 2)])]), _vm._v(" "), _c('div', {
    staticClass: "table-responsive"
  }, [_c('table', {
    staticClass: "table table-striped table-bordered table-hover"
  }, [_c('thead', [_c('tr', [_c('th', [_vm._v("id")]), _vm._v(" "), _vm._l((_vm.fields), function(field) {
    return _c('th', [_vm._v(_vm._s(field.name))])
  }), _vm._v(" "), _vm._m(0), _vm._v(" "), _vm._m(1)], 2)]), _vm._v(" "), _c('tbody', _vm._l((_vm.rows.data), function(row) {
    return _c('tr', [_c('td', [_vm._v(_vm._s(row.id))]), _vm._v(" "), _vm._l((_vm.fields), function(field) {
      return _c('td', [_vm._v("\n                        " + _vm._s(row[field.key]) + "\n                    ")])
    }), _vm._v(" "), _c('td', [_c('router-link', {
      attrs: {
        "to": {
          name: 'tag.edit',
          params: {
            id: row.id
          }
        }
      }
    }, [_c('i', {
      staticClass: "fa fa-pencil",
      attrs: {
        "aria-hidden": "true"
      }
    })])], 1), _vm._v(" "), _c('td', [_c('a', {
      attrs: {
        "id": 'delete-' + row.id,
        "data-confirm": "Удалить тег?"
      },
      on: {
        "click": function($event) {
          _vm.destroy({
            id: row.id,
            key: 'tag'
          })
        }
      }
    }, [_c('i', {
      staticClass: "fa fa-trash-o",
      attrs: {
        "aria-hidden": "true"
      }
    })])])], 2)
  }))])]), _vm._v(" "), _c('div', {
    staticClass: "col-sm-6"
  }, [_vm._v("\n        Страница " + _vm._s(_vm.rows.current_page) + " из " + _vm._s(_vm.rows.last_page) + ". Запись c " + _vm._s(_vm.rows.from) + " по " + _vm._s(_vm.rows.to) + " из " + _vm._s(_vm.rows.total) + "\n    ")]), _vm._v(" "), _c('div', {
    staticClass: "col-sm-6"
  }, [_c('paginator', {
    attrs: {
      "rows": _vm.rows
    },
    on: {
      "update_table": _vm.updateTable
    }
  })], 1)])
},staticRenderFns: [function (){var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
  return _c('th', [_c('i', {
    staticClass: "fa fa-pencil",
    attrs: {
      "aria-hidden": "true"
    }
  })])
},function (){var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
  return _c('th', [_c('i', {
    staticClass: "fa fa-trash-o",
    attrs: {
      "aria-hidden": "true"
    }
  })])
}]}
module.exports.render._withStripped = true
if (false) {
  module.hot.accept()
  if (module.hot.data) {
     require("vue-hot-reload-api").rerender("data-v-74797e7e", module.exports)
  }
}

/***/ }),
/* 374 */,
/* 375 */
/***/ (function(module, exports, __webpack_require__) {

module.exports={render:function (){var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
  return _c('div', [_c('div', {
    staticClass: "form-group"
  }, [(_vm.$route.name == 'product.edit') ? _c('router-link', {
    staticClass: "btn btn-success",
    attrs: {
      "to": {
        name: 'offer.create',
        query: {
          product_id: _vm.$route.params.id
        }
      }
    }
  }, [_vm._v("Добавть ТП к товару")]) : _vm._e(), _vm._v(" \n        "), (_vm.productForm.option_group_id == 1) ? _c('a', {
    staticClass: "btn btn-warning",
    class: _vm.vuestore.loading ? 'disabled' : '',
    attrs: {
      "title": "Существующие ТП остануться без изменений"
    },
    on: {
      "click": function($event) {
        _vm.generate()
      }
    }
  }, [_vm._v("Дополнить ТП на основе значений опций")]) : _vm._e()], 1), _vm._v(" "), _c('div', {
    staticClass: "table-responsive"
  }, [_c('table', {
    staticClass: "table table-striped table-bordered table-hover"
  }, [_c('thead', [_c('tr', [_c('th', [_vm._v("id")]), _vm._v(" "), _vm._l((_vm.fields), function(field) {
    return _c('th', [_vm._v(_vm._s(field.name))])
  }), _vm._v(" "), _vm._m(0)], 2)]), _vm._v(" "), _c('tbody', _vm._l((_vm.rows.data), function(row) {
    return _c('tr', [_c('td', [_vm._v(_vm._s(row.id))]), _vm._v(" "), _vm._l((_vm.fields), function(field) {
      return _c('td', [(field.type == 'string') ? [_vm._v(_vm._s(row[field.key]))] : _vm._e(), _vm._v(" "), (field.type == 'boolean') ? [_c('a', {
        on: {
          "click": function($event) {
            _vm.changeActive(row)
          }
        }
      }, [(row[field.key] == true) ? _c('i', {
        staticClass: "fa fa-toggle-on",
        attrs: {
          "aria-hidden": "true"
        }
      }) : _c('i', {
        staticClass: "fa fa-toggle-off",
        attrs: {
          "aria-hidden": "true"
        }
      })])] : _vm._e(), _vm._v(" "), _vm._l((row[field.key]), function(r) {
        return (field.type == 'array') ? _c('span', {
          staticClass: "category label label-success"
        }, [_vm._v("\n                            " + _vm._s(r.title) + "\n                        ")]) : _vm._e()
      })], 2)
    }), _vm._v(" "), _c('td', [_c('router-link', {
      attrs: {
        "to": {
          name: 'offer.edit',
          params: {
            id: row.id
          }
        }
      }
    }, [_c('i', {
      staticClass: "fa fa-pencil",
      attrs: {
        "aria-hidden": "true"
      }
    })])], 1)], 2)
  }))])]), _vm._v(" "), _c('div', {
    staticClass: "col-sm-6"
  }, [_vm._v("\n        Страница " + _vm._s(_vm.rows.current_page) + " из " + _vm._s(_vm.rows.last_page) + ". Запись c " + _vm._s(_vm.rows.from) + " по " + _vm._s(_vm.rows.to) + " из " + _vm._s(_vm.rows.total) + "\n    ")]), _vm._v(" "), _c('div', {
    staticClass: "col-sm-6"
  }, [_c('paginator', {
    attrs: {
      "rows": _vm.rows
    },
    on: {
      "update_table": _vm.updateTable
    }
  })], 1)])
},staticRenderFns: [function (){var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
  return _c('th', [_c('i', {
    staticClass: "fa fa-pencil",
    attrs: {
      "aria-hidden": "true"
    }
  })])
}]}
module.exports.render._withStripped = true
if (false) {
  module.hot.accept()
  if (module.hot.data) {
     require("vue-hot-reload-api").rerender("data-v-76745c00", module.exports)
  }
}

/***/ }),
/* 376 */
/***/ (function(module, exports, __webpack_require__) {

module.exports={render:function (){var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
  return _c('div', {
    staticClass: "col-lg-7"
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
    class: [_vm.form.errors.has('title') ? 'has-error' : '']
  }, [_c('label', [_vm._v("Заголовок")]), _vm._v(" "), _c('input', {
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
      "value": (_vm.form.title)
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
    class: [_vm.form.errors.has('slug') ? 'has-error' : '']
  }, [_c('label', [_vm._v("URL")]), _vm._v(" "), _c('input', {
    directives: [{
      name: "model",
      rawName: "v-model",
      value: (_vm.form.slug),
      expression: "form.slug"
    }],
    staticClass: "form-control",
    attrs: {
      "name": "slug",
      "type": "text"
    },
    domProps: {
      "value": (_vm.form.slug)
    },
    on: {
      "input": function($event) {
        if ($event.target.composing) { return; }
        _vm.form.slug = $event.target.value
      }
    }
  }), _vm._v(" "), (_vm.form.errors.has('slug')) ? _c('span', {
    staticClass: "help-block is-danger",
    domProps: {
      "textContent": _vm._s(_vm.form.errors.get('slug'))
    }
  }) : _vm._e()]), _vm._v(" "), _c('div', {
    staticClass: "form-group",
    class: [_vm.form.errors.has('h1') ? 'has-error' : '']
  }, [_c('label', [_vm._v("H1")]), _vm._v(" "), _c('input', {
    directives: [{
      name: "model",
      rawName: "v-model",
      value: (_vm.form.h1),
      expression: "form.h1"
    }],
    staticClass: "form-control",
    attrs: {
      "name": "h1",
      "type": "text"
    },
    domProps: {
      "value": (_vm.form.h1)
    },
    on: {
      "input": function($event) {
        if ($event.target.composing) { return; }
        _vm.form.h1 = $event.target.value
      }
    }
  }), _vm._v(" "), (_vm.form.errors.has('h1')) ? _c('span', {
    staticClass: "help-block is-danger",
    domProps: {
      "textContent": _vm._s(_vm.form.errors.get('h1'))
    }
  }) : _vm._e()]), _vm._v(" "), _c('div', {
    staticClass: "form-group",
    class: [_vm.form.errors.has('keywords') ? 'has-error' : '']
  }, [_c('label', [_vm._v("Keywords")]), _vm._v(" "), _c('input', {
    directives: [{
      name: "model",
      rawName: "v-model",
      value: (_vm.form.keywords),
      expression: "form.keywords"
    }],
    staticClass: "form-control",
    attrs: {
      "name": "keywords",
      "type": "text"
    },
    domProps: {
      "value": (_vm.form.keywords)
    },
    on: {
      "input": function($event) {
        if ($event.target.composing) { return; }
        _vm.form.keywords = $event.target.value
      }
    }
  }), _vm._v(" "), (_vm.form.errors.has('keywords')) ? _c('span', {
    staticClass: "help-block is-danger",
    domProps: {
      "textContent": _vm._s(_vm.form.errors.get('keywords'))
    }
  }) : _vm._e()]), _vm._v(" "), _c('div', {
    staticClass: "form-group",
    class: [_vm.form.errors.has('description') ? 'has-error' : '']
  }, [_c('label', [_vm._v("Description")]), _vm._v(" "), _c('input', {
    directives: [{
      name: "model",
      rawName: "v-model",
      value: (_vm.form.description),
      expression: "form.description"
    }],
    staticClass: "form-control",
    attrs: {
      "name": "description",
      "type": "text"
    },
    domProps: {
      "value": (_vm.form.description)
    },
    on: {
      "input": function($event) {
        if ($event.target.composing) { return; }
        _vm.form.description = $event.target.value
      }
    }
  }), _vm._v(" "), (_vm.form.errors.has('description')) ? _c('span', {
    staticClass: "help-block is-danger",
    domProps: {
      "textContent": _vm._s(_vm.form.errors.get('description'))
    }
  }) : _vm._e()]), _vm._v(" "), _c('div', {
    staticClass: "form-group",
    class: [_vm.form.errors.has('content') ? 'has-error' : '']
  }, [_c('label', [_vm._v("Содержание (включая html теги)")]), _vm._v(" "), _c('textarea', {
    directives: [{
      name: "model",
      rawName: "v-model",
      value: (_vm.form.content),
      expression: "form.content"
    }],
    staticClass: "form-control",
    attrs: {
      "id": "ckeditor",
      "name": "content",
      "rows": "20"
    },
    domProps: {
      "value": (_vm.form.content)
    },
    on: {
      "input": function($event) {
        if ($event.target.composing) { return; }
        _vm.form.content = $event.target.value
      }
    }
  }, [_vm._v(" sd")]), _vm._v(" "), (_vm.form.errors.has('content')) ? _c('span', {
    staticClass: "help-block is-danger",
    domProps: {
      "textContent": _vm._s(_vm.form.errors.get('content'))
    }
  }) : _vm._e()]), _vm._v(" "), _c('div', {
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
        name: 'page.index'
      }
    }
  }, [_vm._v("Отмена")])], 1)])])])])
},staticRenderFns: []}
module.exports.render._withStripped = true
if (false) {
  module.hot.accept()
  if (module.hot.data) {
     require("vue-hot-reload-api").rerender("data-v-78a23df3", module.exports)
  }
}

/***/ }),
/* 377 */
/***/ (function(module, exports, __webpack_require__) {

module.exports={render:function (){var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
  return _c('div', {
    staticClass: "col-lg-7"
  }, [_c('div', {
    staticClass: "panel panel-default"
  }, [_c('div', {
    staticClass: "panel-body"
  }, [(_vm.data.product) ? _c('h2', [_vm._v(_vm._s(_vm.data.product.title))]) : _vm._e(), _vm._v(" "), _c('form', {
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
    class: [_vm.form.errors.has('quantity') ? 'has-error' : '']
  }, [_c('label', [_vm._v("Кол-во")]), _vm._v(" "), _c('input', {
    directives: [{
      name: "model",
      rawName: "v-model",
      value: (_vm.form.quantity),
      expression: "form.quantity"
    }],
    staticClass: "form-control",
    attrs: {
      "name": "quantity",
      "type": "text"
    },
    domProps: {
      "value": (_vm.form.quantity)
    },
    on: {
      "input": function($event) {
        if ($event.target.composing) { return; }
        _vm.form.quantity = $event.target.value
      }
    }
  }), _vm._v(" "), (_vm.form.errors.has('quantity')) ? _c('span', {
    staticClass: "help-block is-danger",
    domProps: {
      "textContent": _vm._s(_vm.form.errors.get('quantity'))
    }
  }) : _vm._e()]), _vm._v(" "), _c('div', {
    staticClass: "form-group",
    class: [_vm.form.errors.has('weight') ? 'has-error' : '']
  }, [_c('label', [_vm._v("Вес")]), _vm._v(" "), _c('input', {
    directives: [{
      name: "model",
      rawName: "v-model",
      value: (_vm.form.weight),
      expression: "form.weight"
    }],
    staticClass: "form-control",
    attrs: {
      "name": "weight",
      "type": "text"
    },
    domProps: {
      "value": (_vm.form.weight)
    },
    on: {
      "input": function($event) {
        if ($event.target.composing) { return; }
        _vm.form.weight = $event.target.value
      }
    }
  }), _vm._v(" "), (_vm.form.errors.has('weight')) ? _c('span', {
    staticClass: "help-block is-danger",
    domProps: {
      "textContent": _vm._s(_vm.form.errors.get('weight'))
    }
  }) : _vm._e()]), _vm._v(" "), _c('div', {
    staticClass: "form-group",
    class: [_vm.form.errors.has('options') ? 'has-error' : '']
  }, [_c('label', [_vm._v("Значения опций")]), _vm._v(" "), _vm._l((_vm.data.values), function(v, k) {
    return _c('div', {
      staticClass: "form-group"
    }, [_c('select', {
      directives: [{
        name: "model",
        rawName: "v-model",
        value: (_vm.selectsData[k]),
        expression: "selectsData[k]"
      }],
      staticClass: "form-control",
      attrs: {
        "name": "",
        "id": 'select_for_option_id_' + k
      },
      on: {
        "change": function($event) {
          var $$selectedVal = Array.prototype.filter.call($event.target.options, function(o) {
            return o.selected
          }).map(function(o) {
            var val = "_value" in o ? o._value : o.value;
            return val
          });
          var $$exp = _vm.selectsData,
            $$idx = k;
          if (!Array.isArray($$exp)) {
            _vm.selectsData[k] = $event.target.multiple ? $$selectedVal : $$selectedVal[0]
          } else {
            $$exp.splice($$idx, 1, $event.target.multiple ? $$selectedVal : $$selectedVal[0])
          }
        }
      }
    }, _vm._l((_vm.data.values[k]), function(o) {
      return _c('option', {
        domProps: {
          "value": o.id
        }
      }, [_vm._v(_vm._s(o.title))])
    }))])
  })], 2), _vm._v(" "), _c('div', {
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
      "__c": function($event) {
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
  }, [_vm._v("Сохранить")]), _vm._v(" "), (this.form.product_id) ? _c('router-link', {
    staticClass: "btn btn-default",
    attrs: {
      "to": {
        name: 'product.edit',
        params: {
          id: this.form.product_id
        }
      }
    }
  }, [_vm._v("Отмена")]) : _vm._e()], 1)])])])])
},staticRenderFns: []}
module.exports.render._withStripped = true
if (false) {
  module.hot.accept()
  if (module.hot.data) {
     require("vue-hot-reload-api").rerender("data-v-7c577c90", module.exports)
  }
}

/***/ }),
/* 378 */
/***/ (function(module, exports, __webpack_require__) {

module.exports={render:function (){var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
  return _c('div', {
    staticClass: "col-lg-7"
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
    class: [_vm.form.errors.has('option_id') ? 'has-error' : '']
  }, [_c('label', [_vm._v("Опция")]), _vm._v(" "), _c('select', {
    directives: [{
      name: "model",
      rawName: "v-model",
      value: (_vm.selected_option),
      expression: "selected_option"
    }],
    staticClass: "form-control",
    on: {
      "change": function($event) {
        var $$selectedVal = Array.prototype.filter.call($event.target.options, function(o) {
          return o.selected
        }).map(function(o) {
          var val = "_value" in o ? o._value : o.value;
          return val
        });
        _vm.selected_option = $event.target.multiple ? $$selectedVal : $$selectedVal[0]
      }
    }
  }, _vm._l((this.data.options), function(option) {
    return _c('option', {
      domProps: {
        "value": option.id,
        "innerHTML": _vm._s(option.name)
      }
    })
  })), _vm._v(" "), (_vm.form.errors.has('option_id')) ? _c('span', {
    staticClass: "help-block is-danger",
    domProps: {
      "textContent": _vm._s(_vm.form.errors.get('option_id'))
    }
  }) : _vm._e()]), _vm._v(" "), _c('div', {
    staticClass: "form-group",
    class: [_vm.form.errors.has('value') ? 'has-error' : '']
  }, [_c('label', [_vm._v("Значение")]), _vm._v(" "), _c('input', {
    directives: [{
      name: "model",
      rawName: "v-model",
      value: (_vm.form.value),
      expression: "form.value"
    }],
    staticClass: "form-control",
    attrs: {
      "name": "value",
      "type": "text"
    },
    domProps: {
      "value": (_vm.form.value)
    },
    on: {
      "input": function($event) {
        if ($event.target.composing) { return; }
        _vm.form.value = $event.target.value
      }
    }
  }), _vm._v(" "), (_vm.form.errors.has('value')) ? _c('span', {
    staticClass: "help-block is-danger",
    domProps: {
      "textContent": _vm._s(_vm.form.errors.get('value'))
    }
  }) : _vm._e()]), _vm._v(" "), _c('div', {
    staticClass: "form-group",
    class: [_vm.form.errors.has('title') ? 'has-error' : '']
  }, [_c('label', [_vm._v("Название значения")]), _vm._v(" "), _c('input', {
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
      "value": (_vm.form.title)
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
    class: [_vm.form.errors.has('description') ? 'has-error' : '']
  }, [_c('label', [_vm._v("Описание значения")]), _vm._v(" "), _c('textarea', {
    directives: [{
      name: "model",
      rawName: "v-model",
      value: (_vm.form.description),
      expression: "form.description"
    }],
    staticClass: "form-control",
    attrs: {
      "name": "description"
    },
    domProps: {
      "value": (_vm.form.description)
    },
    on: {
      "input": function($event) {
        if ($event.target.composing) { return; }
        _vm.form.description = $event.target.value
      }
    }
  }), _vm._v(" "), (_vm.form.errors.has('description')) ? _c('span', {
    staticClass: "help-block is-danger",
    domProps: {
      "textContent": _vm._s(_vm.form.errors.get('description'))
    }
  }) : _vm._e()]), _vm._v(" "), _c('div', {
    staticClass: "form-group",
    class: [_vm.form.errors.has('image') ? 'has-error' : '']
  }, [_c('label', [_vm._v("Фото значения")]), _vm._v(" "), _c('input', {
    directives: [{
      name: "model",
      rawName: "v-model",
      value: (_vm.form.image),
      expression: "form.image"
    }],
    staticClass: "form-control",
    attrs: {
      "name": "image",
      "type": "text"
    },
    domProps: {
      "value": (_vm.form.image)
    },
    on: {
      "input": function($event) {
        if ($event.target.composing) { return; }
        _vm.form.image = $event.target.value
      }
    }
  }), _vm._v(" "), (_vm.form.errors.has('image')) ? _c('span', {
    staticClass: "help-block is-danger",
    domProps: {
      "textContent": _vm._s(_vm.form.errors.get('image'))
    }
  }) : _vm._e()]), _vm._v(" "), _c('div', {
    staticClass: "form-group",
    class: [_vm.form.errors.has('order') ? 'has-error' : '']
  }, [_c('label', [_vm._v("Порядок сортировки")]), _vm._v(" "), _c('input', {
    directives: [{
      name: "model",
      rawName: "v-model",
      value: (_vm.form.order),
      expression: "form.order"
    }],
    staticClass: "form-control",
    attrs: {
      "name": "order",
      "type": "text"
    },
    domProps: {
      "value": (_vm.form.order)
    },
    on: {
      "input": function($event) {
        if ($event.target.composing) { return; }
        _vm.form.order = $event.target.value
      }
    }
  }), _vm._v(" "), (_vm.form.errors.has('order')) ? _c('span', {
    staticClass: "help-block is-danger",
    domProps: {
      "textContent": _vm._s(_vm.form.errors.get('order'))
    }
  }) : _vm._e()]), _vm._v(" "), _c('div', {
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
        name: 'option_value.index'
      }
    }
  }, [_vm._v("Отмена")])], 1)])])])])
},staticRenderFns: []}
module.exports.render._withStripped = true
if (false) {
  module.hot.accept()
  if (module.hot.data) {
     require("vue-hot-reload-api").rerender("data-v-7ea3f950", module.exports)
  }
}

/***/ }),
/* 379 */
/***/ (function(module, exports, __webpack_require__) {

module.exports={render:function (){var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
  return _c('div', [_c('div', {
    staticClass: "form-group"
  }, [_c('router-link', {
    staticClass: "btn btn-success",
    attrs: {
      "to": {
        name: 'setting.create'
      }
    }
  }, [_vm._v("Добавть настройку")])], 1), _vm._v(" "), _c('div', {
    staticClass: "row"
  }, [_c('div', {
    staticClass: "col-sm-5 left"
  }, [_c('div', {
    staticClass: "form-group input-group"
  }, [_vm._t("searchfilter", [_c('input', {
    directives: [{
      name: "model",
      rawName: "v-model",
      value: (_vm.search),
      expression: "search"
    }],
    staticClass: "form-control",
    attrs: {
      "type": "text",
      "placeholder": ""
    },
    domProps: {
      "value": (_vm.search)
    },
    on: {
      "keyup": function($event) {
        if (!('button' in $event) && _vm._k($event.keyCode, "enter", 13)) { return null; }
        _vm.searchRows()
      },
      "input": function($event) {
        if ($event.target.composing) { return; }
        _vm.search = $event.target.value
      }
    }
  })], {
    search: _vm.search
  }), _vm._v(" "), _c('span', {
    staticClass: "input-group-btn"
  }, [_c('a', {
    staticClass: "btn btn-primary",
    on: {
      "click": function($event) {
        _vm.searchRows()
      }
    }
  }, [_c('i', {
    staticClass: "fa fa-search"
  })])])], 2)])]), _vm._v(" "), _c('div', {
    staticClass: "table-responsive"
  }, [_c('table', {
    staticClass: "table table-striped table-bordered table-hover"
  }, [_c('thead', [_c('tr', [_vm._l((_vm.fields), function(field) {
    return _c('th', [_vm._v(_vm._s(field.name))])
  }), _vm._v(" "), _vm._m(0), _vm._v(" "), _vm._m(1)], 2)]), _vm._v(" "), _c('tbody', _vm._l((_vm.rows.data), function(row) {
    return _c('tr', [_vm._l((_vm.fields), function(field) {
      return _c('td', [_vm._v("\n                        " + _vm._s(row[field.key]) + "\n                    ")])
    }), _vm._v(" "), _c('td', [_c('router-link', {
      attrs: {
        "to": {
          name: 'setting.edit',
          params: {
            id: row.id
          }
        }
      }
    }, [_c('i', {
      staticClass: "fa fa-pencil",
      attrs: {
        "aria-hidden": "true"
      }
    })])], 1), _vm._v(" "), _c('td', [_c('a', {
      attrs: {
        "id": 'delete-' + row.id,
        "data-confirm": "Удалить тег?"
      },
      on: {
        "click": function($event) {
          _vm.destroy({
            id: row.id,
            key: 'setting'
          })
        }
      }
    }, [_c('i', {
      staticClass: "fa fa-trash-o",
      attrs: {
        "aria-hidden": "true"
      }
    })])])], 2)
  }))])]), _vm._v(" "), _c('div', {
    staticClass: "col-sm-6"
  }, [_vm._v("\n        Страница " + _vm._s(_vm.rows.current_page) + " из " + _vm._s(_vm.rows.last_page) + ". Запись c " + _vm._s(_vm.rows.from) + " по " + _vm._s(_vm.rows.to) + " из " + _vm._s(_vm.rows.total) + "\n    ")]), _vm._v(" "), _c('div', {
    staticClass: "col-sm-6"
  }, [_c('paginator', {
    attrs: {
      "rows": _vm.rows
    },
    on: {
      "update_table": _vm.updateTable
    }
  })], 1)])
},staticRenderFns: [function (){var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
  return _c('th', [_c('i', {
    staticClass: "fa fa-pencil",
    attrs: {
      "aria-hidden": "true"
    }
  })])
},function (){var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
  return _c('th', [_c('i', {
    staticClass: "fa fa-trash-o",
    attrs: {
      "aria-hidden": "true"
    }
  })])
}]}
module.exports.render._withStripped = true
if (false) {
  module.hot.accept()
  if (module.hot.data) {
     require("vue-hot-reload-api").rerender("data-v-a4a77998", module.exports)
  }
}

/***/ }),
/* 380 */,
/* 381 */
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
      "value": (_vm.search)
    },
    on: {
      "keyup": function($event) {
        if (!('button' in $event) && _vm._k($event.keyCode, "enter", 13)) { return null; }
        _vm.searchRows()
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
        _vm.searchRows()
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
        var $$selectedVal = Array.prototype.filter.call($event.target.options, function(o) {
          return o.selected
        }).map(function(o) {
          var val = "_value" in o ? o._value : o.value;
          return val
        });
        _vm.category = $event.target.multiple ? $$selectedVal : $$selectedVal[0]
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
        var $$selectedVal = Array.prototype.filter.call($event.target.options, function(o) {
          return o.selected
        }).map(function(o) {
          var val = "_value" in o ? o._value : o.value;
          return val
        });
        _vm.active = $event.target.multiple ? $$selectedVal : $$selectedVal[0]
      }, function($event) {
        _vm.searchRows()
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
  }), _vm._v(" "), _vm._m(0), _vm._v(" "), _vm._m(1), _vm._v(" "), _vm._m(2)], 2)]), _vm._v(" "), _c('tbody', _vm._l((_vm.rows.data), function(row) {
    return _c('tr', [_c('td', [_vm._v(_vm._s(row.id))]), _vm._v(" "), _vm._l((_vm.fields), function(field) {
      return _c('td', [(field.type == 'string') ? [_vm._v(_vm._s(row[field.key]))] : _vm._e(), _vm._v(" "), _vm._l((row[field.key]), function(category) {
        return (field.type == 'array') ? [_c('span', {
          staticClass: "category label label-primary"
        }, [_vm._v("\n                                " + _vm._s(category.name) + "\n                            ")]), _vm._v(" \n                        ")] : _vm._e()
      }), _vm._v(" "), (field.type == 'boolean') ? [_vm._v(_vm._s(row[field.key] == true ? 'Да' : 'Нет'))] : _vm._e()], 2)
    }), _vm._v(" "), _c('td', [(row.photos.length) ? _c('i', {
      staticClass: "fa fa-picture-o",
      attrs: {
        "aria-hidden": "true"
      }
    }) : _vm._e()]), _vm._v(" "), _c('td', [_c('router-link', {
      attrs: {
        "to": {
          name: 'product.edit',
          params: {
            id: row.id
          }
        }
      }
    }, [_c('i', {
      staticClass: "fa fa-pencil",
      attrs: {
        "aria-hidden": "true"
      }
    })])], 1), _vm._v(" "), _c('td', [_c('a', {
      attrs: {
        "id": 'delete-' + row.id,
        "data-confirm": "Удалить товар?"
      },
      on: {
        "click": function($event) {
          _vm.destroy({
            id: row.id,
            key: 'product'
          })
        }
      }
    }, [_c('i', {
      staticClass: "fa fa-trash-o",
      attrs: {
        "aria-hidden": "true"
      }
    })])])], 2)
  }))])]), _vm._v(" "), _c('div', {
    staticClass: "col-sm-6"
  }, [_vm._v("\n        Страница " + _vm._s(_vm.rows.current_page) + " из " + _vm._s(_vm.rows.last_page) + ". Запись c " + _vm._s(_vm.rows.from) + " по " + _vm._s(_vm.rows.to) + " из " + _vm._s(_vm.rows.total) + "\n    ")]), _vm._v(" "), _c('div', {
    staticClass: "col-sm-6"
  }, [_c('paginator', {
    attrs: {
      "rows": _vm.rows
    },
    on: {
      "update_table": _vm.updateTable
    }
  })], 1)])
},staticRenderFns: [function (){var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
  return _c('th', [_c('i', {
    staticClass: "fa fa-picture-o",
    attrs: {
      "aria-hidden": "true"
    }
  })])
},function (){var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
  return _c('th', [_c('i', {
    staticClass: "fa fa-pencil",
    attrs: {
      "aria-hidden": "true"
    }
  })])
},function (){var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
  return _c('th', [_c('i', {
    staticClass: "fa fa-trash-o",
    attrs: {
      "aria-hidden": "true"
    }
  })])
}]}
module.exports.render._withStripped = true
if (false) {
  module.hot.accept()
  if (module.hot.data) {
     require("vue-hot-reload-api").rerender("data-v-b70500da", module.exports)
  }
}

/***/ }),
/* 382 */
/***/ (function(module, exports, __webpack_require__) {

module.exports={render:function (){var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
  return _c('layout', [_c('h1', {
    slot: "title"
  }, [_vm._v("Редактировать тип товара")]), _vm._v(" "), _c('template', {
    slot: "panel-title"
  }, [_vm._v("Редактировать тип тована")]), _vm._v(" "), _c('template', {
    slot: "body"
  }, [_c('option-group-form')], 1)], 2)
},staticRenderFns: []}
module.exports.render._withStripped = true
if (false) {
  module.hot.accept()
  if (module.hot.data) {
     require("vue-hot-reload-api").rerender("data-v-bc620944", module.exports)
  }
}

/***/ }),
/* 383 */,
/* 384 */
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
     require("vue-hot-reload-api").rerender("data-v-d009ab04", module.exports)
  }
}

/***/ }),
/* 385 */
/***/ (function(module, exports, __webpack_require__) {

module.exports={render:function (){var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
  return _c('layout', [_c('h1', {
    slot: "title"
  }, [_vm._v("Список значений опций")]), _vm._v(" "), _c('template', {
    slot: "panel-title"
  }, [_vm._v("Значения опций")]), _vm._v(" "), _c('template', {
    slot: "body"
  }, [_c('data-table')], 1)], 2)
},staticRenderFns: []}
module.exports.render._withStripped = true
if (false) {
  module.hot.accept()
  if (module.hot.data) {
     require("vue-hot-reload-api").rerender("data-v-d08c95d0", module.exports)
  }
}

/***/ }),
/* 386 */,
/* 387 */
/***/ (function(module, exports, __webpack_require__) {

module.exports={render:function (){var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
  return _c('layout', [_c('h1', {
    slot: "title"
  }, [_vm._v("Редкатровать опцию")]), _vm._v(" "), _c('template', {
    slot: "panel-title"
  }, [_vm._v("Редактирование опцию")]), _vm._v(" "), _c('template', {
    slot: "body"
  }, [_c('optionvalue-form')], 1)], 2)
},staticRenderFns: []}
module.exports.render._withStripped = true
if (false) {
  module.hot.accept()
  if (module.hot.data) {
     require("vue-hot-reload-api").rerender("data-v-d6b79b7c", module.exports)
  }
}

/***/ }),
/* 388 */
/***/ (function(module, exports, __webpack_require__) {

module.exports={render:function (){var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
  return _c('layout', [_c('h1', {
    slot: "title"
  }, [_vm._v("Список страниц")]), _vm._v(" "), _c('template', {
    slot: "panel-title"
  }, [_vm._v("Страницы")]), _vm._v(" "), _c('template', {
    slot: "body"
  }, [_c('data-table')], 1)], 2)
},staticRenderFns: []}
module.exports.render._withStripped = true
if (false) {
  module.hot.accept()
  if (module.hot.data) {
     require("vue-hot-reload-api").rerender("data-v-e0be9384", module.exports)
  }
}

/***/ }),
/* 389 */,
/* 390 */
/***/ (function(module, exports, __webpack_require__) {

module.exports={render:function (){var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
  return _c('layout', [_c('h1', {
    slot: "title"
  }, [_vm._v("Редкатровать тег")]), _vm._v(" "), _c('template', {
    slot: "panel-title"
  }, [_vm._v("Редактирование тега")]), _vm._v(" "), _c('template', {
    slot: "body"
  }, [_c('tag-form')], 1)], 2)
},staticRenderFns: []}
module.exports.render._withStripped = true
if (false) {
  module.hot.accept()
  if (module.hot.data) {
     require("vue-hot-reload-api").rerender("data-v-e5252504", module.exports)
  }
}

/***/ }),
/* 391 */
/***/ (function(module, exports, __webpack_require__) {

module.exports={render:function (){var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
  return _c('layout', [_c('h1', {
    slot: "title"
  }, [_vm._v("Список тегов")]), _vm._v(" "), _c('template', {
    slot: "panel-title"
  }, [_vm._v("Значения тегов")]), _vm._v(" "), _c('template', {
    slot: "body"
  }, [_c('data-table')], 1)], 2)
},staticRenderFns: []}
module.exports.render._withStripped = true
if (false) {
  module.hot.accept()
  if (module.hot.data) {
     require("vue-hot-reload-api").rerender("data-v-ec0c2358", module.exports)
  }
}

/***/ }),
/* 392 */
/***/ (function(module, exports, __webpack_require__) {

module.exports={render:function (){var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
  return _c('layout', [_c('h1', {
    slot: "title"
  }, [_vm._v("Новый тип товара")]), _vm._v(" "), _c('template', {
    slot: "panel-title"
  }, [_vm._v("Добавить тип тована")]), _vm._v(" "), _c('template', {
    slot: "body"
  }, [_c('option-group-form')], 1)], 2)
},staticRenderFns: []}
module.exports.render._withStripped = true
if (false) {
  module.hot.accept()
  if (module.hot.data) {
     require("vue-hot-reload-api").rerender("data-v-f21d3360", module.exports)
  }
}

/***/ }),
/* 393 */,
/* 394 */
/***/ (function(module, exports, __webpack_require__) {

module.exports={render:function (){var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
  return _c('layout', [_c('h1', {
    slot: "title"
  }, [_vm._v("Торговые предложения")]), _vm._v(" "), _c('template', {
    slot: "panel-title"
  }, [_vm._v("Торговые предложения")]), _vm._v(" "), _c('template', {
    slot: "body"
  }, [_c('data-table')], 1)], 2)
},staticRenderFns: []}
module.exports.render._withStripped = true
if (false) {
  module.hot.accept()
  if (module.hot.data) {
     require("vue-hot-reload-api").rerender("data-v-f94573d0", module.exports)
  }
}

/***/ }),
/* 395 */,
/* 396 */
/***/ (function(module, exports, __webpack_require__) {

// style-loader: Adds some css to the DOM by adding a <style> tag

// load the styles
var content = __webpack_require__(298);
if(typeof content === 'string') content = [[module.i, content, '']];
if(content.locals) module.exports = content.locals;
// add the styles to the DOM
var update = __webpack_require__(7)("8332ae50", content, false);
// Hot Module Replacement
if(false) {
 // When the styles change, update the <style> tags
 if(!content.locals) {
   module.hot.accept("!!../../../../../node_modules/css-loader/index.js!../../../../../node_modules/vue-loader/lib/style-rewriter.js?id=data-v-0eb212ee!../../../../../node_modules/vue-loader/lib/selector.js?type=styles&index=0!./DataTable.vue", function() {
     var newContent = require("!!../../../../../node_modules/css-loader/index.js!../../../../../node_modules/vue-loader/lib/style-rewriter.js?id=data-v-0eb212ee!../../../../../node_modules/vue-loader/lib/selector.js?type=styles&index=0!./DataTable.vue");
     if(typeof newContent === 'string') newContent = [[module.id, newContent, '']];
     update(newContent);
   });
 }
 // When the module is disposed, remove the <style> tags
 module.hot.dispose(function() { update(); });
}

/***/ }),
/* 397 */,
/* 398 */,
/* 399 */
/***/ (function(module, exports, __webpack_require__) {

// style-loader: Adds some css to the DOM by adding a <style> tag

// load the styles
var content = __webpack_require__(301);
if(typeof content === 'string') content = [[module.i, content, '']];
if(content.locals) module.exports = content.locals;
// add the styles to the DOM
var update = __webpack_require__(7)("063773d2", content, false);
// Hot Module Replacement
if(false) {
 // When the styles change, update the <style> tags
 if(!content.locals) {
   module.hot.accept("!!../../../../../node_modules/css-loader/index.js!../../../../../node_modules/vue-loader/lib/style-rewriter.js?id=data-v-37dc2376!../../../../../node_modules/vue-loader/lib/selector.js?type=styles&index=0!./Form.vue", function() {
     var newContent = require("!!../../../../../node_modules/css-loader/index.js!../../../../../node_modules/vue-loader/lib/style-rewriter.js?id=data-v-37dc2376!../../../../../node_modules/vue-loader/lib/selector.js?type=styles&index=0!./Form.vue");
     if(typeof newContent === 'string') newContent = [[module.id, newContent, '']];
     update(newContent);
   });
 }
 // When the module is disposed, remove the <style> tags
 module.hot.dispose(function() { update(); });
}

/***/ }),
/* 400 */
/***/ (function(module, exports, __webpack_require__) {

// style-loader: Adds some css to the DOM by adding a <style> tag

// load the styles
var content = __webpack_require__(302);
if(typeof content === 'string') content = [[module.i, content, '']];
if(content.locals) module.exports = content.locals;
// add the styles to the DOM
var update = __webpack_require__(7)("797d5bc4", content, false);
// Hot Module Replacement
if(false) {
 // When the styles change, update the <style> tags
 if(!content.locals) {
   module.hot.accept("!!../../../../../node_modules/css-loader/index.js!../../../../../node_modules/vue-loader/lib/style-rewriter.js?id=data-v-3f929e60!../../../../../node_modules/vue-loader/lib/selector.js?type=styles&index=0!./DataTable.vue", function() {
     var newContent = require("!!../../../../../node_modules/css-loader/index.js!../../../../../node_modules/vue-loader/lib/style-rewriter.js?id=data-v-3f929e60!../../../../../node_modules/vue-loader/lib/selector.js?type=styles&index=0!./DataTable.vue");
     if(typeof newContent === 'string') newContent = [[module.id, newContent, '']];
     update(newContent);
   });
 }
 // When the module is disposed, remove the <style> tags
 module.hot.dispose(function() { update(); });
}

/***/ }),
/* 401 */
/***/ (function(module, exports, __webpack_require__) {

// style-loader: Adds some css to the DOM by adding a <style> tag

// load the styles
var content = __webpack_require__(303);
if(typeof content === 'string') content = [[module.i, content, '']];
if(content.locals) module.exports = content.locals;
// add the styles to the DOM
var update = __webpack_require__(7)("433e412a", content, false);
// Hot Module Replacement
if(false) {
 // When the styles change, update the <style> tags
 if(!content.locals) {
   module.hot.accept("!!../../../../../node_modules/css-loader/index.js!../../../../../node_modules/vue-loader/lib/style-rewriter.js?id=data-v-40a4f1cb!../../../../../node_modules/vue-loader/lib/selector.js?type=styles&index=0!./DataTable.vue", function() {
     var newContent = require("!!../../../../../node_modules/css-loader/index.js!../../../../../node_modules/vue-loader/lib/style-rewriter.js?id=data-v-40a4f1cb!../../../../../node_modules/vue-loader/lib/selector.js?type=styles&index=0!./DataTable.vue");
     if(typeof newContent === 'string') newContent = [[module.id, newContent, '']];
     update(newContent);
   });
 }
 // When the module is disposed, remove the <style> tags
 module.hot.dispose(function() { update(); });
}

/***/ }),
/* 402 */,
/* 403 */
/***/ (function(module, exports, __webpack_require__) {

// style-loader: Adds some css to the DOM by adding a <style> tag

// load the styles
var content = __webpack_require__(305);
if(typeof content === 'string') content = [[module.i, content, '']];
if(content.locals) module.exports = content.locals;
// add the styles to the DOM
var update = __webpack_require__(7)("385c59a0", content, false);
// Hot Module Replacement
if(false) {
 // When the styles change, update the <style> tags
 if(!content.locals) {
   module.hot.accept("!!../../../../../node_modules/css-loader/index.js!../../../../../node_modules/vue-loader/lib/style-rewriter.js?id=data-v-61b1cca5!../../../../../node_modules/vue-loader/lib/selector.js?type=styles&index=0!./DataTable.vue", function() {
     var newContent = require("!!../../../../../node_modules/css-loader/index.js!../../../../../node_modules/vue-loader/lib/style-rewriter.js?id=data-v-61b1cca5!../../../../../node_modules/vue-loader/lib/selector.js?type=styles&index=0!./DataTable.vue");
     if(typeof newContent === 'string') newContent = [[module.id, newContent, '']];
     update(newContent);
   });
 }
 // When the module is disposed, remove the <style> tags
 module.hot.dispose(function() { update(); });
}

/***/ }),
/* 404 */
/***/ (function(module, exports, __webpack_require__) {

// style-loader: Adds some css to the DOM by adding a <style> tag

// load the styles
var content = __webpack_require__(306);
if(typeof content === 'string') content = [[module.i, content, '']];
if(content.locals) module.exports = content.locals;
// add the styles to the DOM
var update = __webpack_require__(7)("93a7d026", content, false);
// Hot Module Replacement
if(false) {
 // When the styles change, update the <style> tags
 if(!content.locals) {
   module.hot.accept("!!../../../../../node_modules/css-loader/index.js!../../../../../node_modules/vue-loader/lib/style-rewriter.js?id=data-v-74797e7e!../../../../../node_modules/vue-loader/lib/selector.js?type=styles&index=0!./DataTable.vue", function() {
     var newContent = require("!!../../../../../node_modules/css-loader/index.js!../../../../../node_modules/vue-loader/lib/style-rewriter.js?id=data-v-74797e7e!../../../../../node_modules/vue-loader/lib/selector.js?type=styles&index=0!./DataTable.vue");
     if(typeof newContent === 'string') newContent = [[module.id, newContent, '']];
     update(newContent);
   });
 }
 // When the module is disposed, remove the <style> tags
 module.hot.dispose(function() { update(); });
}

/***/ }),
/* 405 */
/***/ (function(module, exports, __webpack_require__) {

// style-loader: Adds some css to the DOM by adding a <style> tag

// load the styles
var content = __webpack_require__(307);
if(typeof content === 'string') content = [[module.i, content, '']];
if(content.locals) module.exports = content.locals;
// add the styles to the DOM
var update = __webpack_require__(7)("8e7e39c8", content, false);
// Hot Module Replacement
if(false) {
 // When the styles change, update the <style> tags
 if(!content.locals) {
   module.hot.accept("!!../../../../../node_modules/css-loader/index.js!../../../../../node_modules/vue-loader/lib/style-rewriter.js?id=data-v-76745c00!../../../../../node_modules/vue-loader/lib/selector.js?type=styles&index=0!./DataTable.vue", function() {
     var newContent = require("!!../../../../../node_modules/css-loader/index.js!../../../../../node_modules/vue-loader/lib/style-rewriter.js?id=data-v-76745c00!../../../../../node_modules/vue-loader/lib/selector.js?type=styles&index=0!./DataTable.vue");
     if(typeof newContent === 'string') newContent = [[module.id, newContent, '']];
     update(newContent);
   });
 }
 // When the module is disposed, remove the <style> tags
 module.hot.dispose(function() { update(); });
}

/***/ }),
/* 406 */
/***/ (function(module, exports, __webpack_require__) {

// style-loader: Adds some css to the DOM by adding a <style> tag

// load the styles
var content = __webpack_require__(308);
if(typeof content === 'string') content = [[module.i, content, '']];
if(content.locals) module.exports = content.locals;
// add the styles to the DOM
var update = __webpack_require__(7)("c0345408", content, false);
// Hot Module Replacement
if(false) {
 // When the styles change, update the <style> tags
 if(!content.locals) {
   module.hot.accept("!!../../../../../node_modules/css-loader/index.js!../../../../../node_modules/vue-loader/lib/style-rewriter.js?id=data-v-a4a77998!../../../../../node_modules/vue-loader/lib/selector.js?type=styles&index=0!./DataTable.vue", function() {
     var newContent = require("!!../../../../../node_modules/css-loader/index.js!../../../../../node_modules/vue-loader/lib/style-rewriter.js?id=data-v-a4a77998!../../../../../node_modules/vue-loader/lib/selector.js?type=styles&index=0!./DataTable.vue");
     if(typeof newContent === 'string') newContent = [[module.id, newContent, '']];
     update(newContent);
   });
 }
 // When the module is disposed, remove the <style> tags
 module.hot.dispose(function() { update(); });
}

/***/ }),
/* 407 */
/***/ (function(module, exports, __webpack_require__) {

// style-loader: Adds some css to the DOM by adding a <style> tag

// load the styles
var content = __webpack_require__(309);
if(typeof content === 'string') content = [[module.i, content, '']];
if(content.locals) module.exports = content.locals;
// add the styles to the DOM
var update = __webpack_require__(7)("54db06a9", content, false);
// Hot Module Replacement
if(false) {
 // When the styles change, update the <style> tags
 if(!content.locals) {
   module.hot.accept("!!../../../../../node_modules/css-loader/index.js!../../../../../node_modules/vue-loader/lib/style-rewriter.js?id=data-v-b70500da!../../../../../node_modules/vue-loader/lib/selector.js?type=styles&index=0!./DataTable.vue", function() {
     var newContent = require("!!../../../../../node_modules/css-loader/index.js!../../../../../node_modules/vue-loader/lib/style-rewriter.js?id=data-v-b70500da!../../../../../node_modules/vue-loader/lib/selector.js?type=styles&index=0!./DataTable.vue");
     if(typeof newContent === 'string') newContent = [[module.id, newContent, '']];
     update(newContent);
   });
 }
 // When the module is disposed, remove the <style> tags
 module.hot.dispose(function() { update(); });
}

/***/ })
],[290]);