webpackJsonp([36],{

/***/ 246:
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
		return /msie [6-9]\b/.test(window.navigator.userAgent.toLowerCase());
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

function addStyle(obj, options) {
	var styleElement, update, remove;

	if (options.singleton) {
		var styleIndex = singletonCounter++;
		styleElement = singletonElement || (singletonElement = createStyleElement(options));
		update = applyToSingletonTag.bind(null, styleElement, styleIndex, false);
		remove = applyToSingletonTag.bind(null, styleElement, styleIndex, true);
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
	var sourceMap = obj.sourceMap;

	if (media) {
		styleElement.setAttribute("media", media);
	}

	if (sourceMap) {
		// https://developer.chrome.com/devtools/docs/javascript-debugging
		// this makes source maps inside style tags work properly in Chrome
		css += '\n/*# sourceURL=' + sourceMap.sources[0] + ' */';
		// http://stackoverflow.com/a/26603875
		css += "\n/*# sourceMappingURL=data:application/json;base64," + btoa(unescape(encodeURIComponent(JSON.stringify(sourceMap)))) + " */";
	}

	if (styleElement.styleSheet) {
		styleElement.styleSheet.cssText = css;
	} else {
		while(styleElement.firstChild) {
			styleElement.removeChild(styleElement.firstChild);
		}
		styleElement.appendChild(document.createTextNode(css));
	}
}


/***/ }),

/***/ 299:
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
Object.defineProperty(__webpack_exports__, "__esModule", { value: true });
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0_axios__ = __webpack_require__(60);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0_axios___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_0_axios__);
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//


/* harmony default export */ __webpack_exports__["default"] = ({
	data: function data() {
		return {
			adv: {},
			hasLogin: false,
			countdown: 3,
			hasPraise: true,
			timer: null
		};
	},
	created: function created() {
		var that = this;
		that.hasLogin = !!window.apiToken;
		__WEBPACK_IMPORTED_MODULE_0_axios___default.a.get('/adv/' + that.$route.params.id + '?api_token=' + window.apiToken).then(function (ret) {
			console.log(ret);
			var data = ret.data;
			that.adv = data.data.adv;
			if (that.adv.tags) {
				that.adv.tags = that.adv.tags.split(',');
			} else {
				that.adv.tags = [];
			}
			if (that.hasLogin && data.data.has_click == 0) {
				that.timer = setInterval(function () {
					that.countdown--;
					if (that.countdown === 0) {
						clearInterval(that.timer);
						that.gain();
					}
				}, 1000);
			}
		});
	},
	beforeDestroy: function beforeDestroy() {
		if (this.timer) {
			clearInterval(this.timer);
		}
	},
	methods: {
		back: function back() {
			if (this.$route.query.from == 'form') {
				this.$router.push({
					'name': 'adv'
				});
			} else {
				this.$router.go(-1);
			}
		},
		cover: function cover(v) {
			return v ? v : '/images/logo.png';
		},
		up: function up() {
			var that = this;
			if (!that.hasLogin) {
				that.$toast('请先登录');
				return false;
			}
			__WEBPACK_IMPORTED_MODULE_0_axios___default.a.post('/adv/up', {
				'id': that.$route.params.id,
				'_token': window._Token,
				'api_token': window.apiToken
			}).then(function (ret) {
				console.log(ret);
				var data = ret.data;
				if (data.errcode === 0) {
					that.adv.up_times = data.num;
				} else {
					that.$toast(data.msg);
				}
			});
		},
		down: function down() {
			var that = this;
			if (!that.hasLogin) {
				that.$toast('请先登录');
				return false;
			}
			__WEBPACK_IMPORTED_MODULE_0_axios___default.a.post('/adv/down', {
				'id': that.$route.params.id,
				'_token': window._Token,
				'api_token': window.apiToken
			}).then(function (ret) {
				console.log(ret);
				var data = ret.data;
				if (data.errcode === 0) {
					that.adv.down_times = data.num;
				} else {
					that.$toast(data.msg);
				}
			});
		},
		gain: function gain() {
			var that = this;
			__WEBPACK_IMPORTED_MODULE_0_axios___default.a.post('/adv/gain/' + that.$route.params.id, {
				'id': that.$route.params.id,
				'_token': window._Token,
				'api_token': window.apiToken
			}).then(function (ret) {
				console.log(ret);
				var data = ret.data;
				that.$toast(data.msg);
				if (data.errcode === 0) {
					that.has_click = true;
				}
			});
		}
	}
});

/***/ }),

/***/ 370:
/***/ (function(module, exports, __webpack_require__) {

exports = module.exports = __webpack_require__(11)(false);
// imports


// module
exports.push([module.i, "\n.tag span[data-v-7e34fd20] {\n  padding:.2rem .2rem;\n  background: #dfdfdf;\n  margin:.2rem .2rem;\n  display: inline-block;\n}\n.tag span[data-v-7e34fd20]:first-child {\n  margin-left: 0;\n}\n", ""]);

// exports


/***/ }),

/***/ 420:
/***/ (function(module, exports, __webpack_require__) {

module.exports={render:function (){var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
  return _c('div', {
    staticClass: "page",
    attrs: {
      "id": "pageAdvShow"
    }
  }, [_c('header', {
    staticClass: "bar bar-nav"
  }, [_c('a', {
    staticClass: "button button-link button-nav pull-left",
    on: {
      "click": _vm.back
    }
  }, [_c('span', {
    staticClass: "icon icon-left"
  }), _vm._v("\n            返回\n        ")]), _vm._v(" "), _c('h1', {
    staticClass: "title"
  }, [_vm._v("广告详情")])]), _vm._v(" "), (_vm.adv.url) ? _c('nav', {
    staticClass: "bar bar-tab"
  }, [_c('a', {
    staticClass: "tab-item",
    attrs: {
      "href": _vm.adv.url
    }
  }, [_vm._v("\n            查看活动\n        ")])]) : _vm._e(), _vm._v(" "), _c('div', {
    staticClass: "content",
    staticStyle: {
      "background": "#fff"
    }
  }, [_c('div', {
    staticClass: "content-block-title font-size-20",
    staticStyle: {
      "margin": ".5rem",
      "font-size": "1rem"
    }
  }, [_vm._v(_vm._s(_vm.adv.title))]), _vm._v(" "), _c('div', {
    staticClass: "content-block-title font-size-14",
    staticStyle: {
      "margin": ".5rem"
    }
  }, [_c('div', {
    staticClass: "row"
  }, [_c('div', {
    staticClass: "col-100"
  }, [_vm._v("日期:" + _vm._s(_vm.adv.created_at))])]), _vm._v(" "), _c('div', {
    staticClass: "row",
    staticStyle: {
      "margin-top": ".5rem"
    }
  }, [_c('div', {
    staticClass: "col-100"
  }, [_vm._v("点击次数(" + _vm._s(_vm.adv.has_click_times) + "/" + _vm._s(_vm.adv.click_times) + ")")])]), _vm._v(" "), _c('div', {
    staticClass: "row",
    staticStyle: {
      "margin-top": ".5rem"
    }
  }, [_c('div', {
    staticClass: "col-100"
  }, [_vm._v("标签\n                  "), _vm._l((_vm.adv.tags), function(it, idx) {
    return _c('span', [_vm._v(_vm._s(it))])
  })], 2)])]), _vm._v(" "), _c('div', {
    staticClass: "content-block font-size-14",
    staticStyle: {
      "margin-top": "0rem",
      "text-indent": "2em",
      "line-height": "1.25rem"
    },
    domProps: {
      "innerHTML": _vm._s(_vm.adv.posts_content)
    }
  }), _vm._v(" "), (_vm.adv.cover) ? _c('div', {
    staticClass: "content-block font-size-14",
    staticStyle: {
      "margin-top": "0rem"
    }
  }, [_c('img', {
    attrs: {
      "src": _vm.cover(_vm.adv.cover),
      "width": "100%"
    }
  })]) : _vm._e(), _vm._v(" "), _c('div', {
    staticClass: "content-block"
  }, [_c('div', {
    staticClass: "row"
  }, [_c('div', {
    staticClass: "col-50"
  }, [_c('a', {
    staticClass: "button button-fill button-success",
    on: {
      "click": _vm.up
    }
  }, [_vm._v("赞(" + _vm._s(_vm.adv.up_times) + ")")])]), _vm._v(" "), _c('div', {
    staticClass: "col-50"
  }, [_c('a', {
    staticClass: "button button-fill button-warning",
    on: {
      "click": _vm.down
    }
  }, [_vm._v("踩(" + _vm._s(_vm.adv.down_times) + ")")])])])])])])
},staticRenderFns: []}
if (false) {
  module.hot.accept()
  if (module.hot.data) {
     require("vue-hot-reload-api").rerender("data-v-7e34fd20", module.exports)
  }
}

/***/ }),

/***/ 462:
/***/ (function(module, exports, __webpack_require__) {

// style-loader: Adds some css to the DOM by adding a <style> tag

// load the styles
var content = __webpack_require__(370);
if(typeof content === 'string') content = [[module.i, content, '']];
// add the styles to the DOM
var update = __webpack_require__(246)(content, {});
if(content.locals) module.exports = content.locals;
// Hot Module Replacement
if(false) {
	// When the styles change, update the <style> tags
	if(!content.locals) {
		module.hot.accept("!!../../../../../node_modules/css-loader/index.js!../../../../../node_modules/vue-loader/lib/style-rewriter.js?id=data-v-7e34fd20&scoped=true!../../../../../node_modules/vue-loader/lib/selector.js?type=styles&index=0!./advshow.vue", function() {
			var newContent = require("!!../../../../../node_modules/css-loader/index.js!../../../../../node_modules/vue-loader/lib/style-rewriter.js?id=data-v-7e34fd20&scoped=true!../../../../../node_modules/vue-loader/lib/selector.js?type=styles&index=0!./advshow.vue");
			if(typeof newContent === 'string') newContent = [[module.id, newContent, '']];
			update(newContent);
		});
	}
	// When the module is disposed, remove the <style> tags
	module.hot.dispose(function() { update(); });
}

/***/ }),

/***/ 65:
/***/ (function(module, exports, __webpack_require__) {

var __vue_exports__, __vue_options__
var __vue_styles__ = {}

/* styles */
__webpack_require__(462)

/* script */
__vue_exports__ = __webpack_require__(299)

/* template */
var __vue_template__ = __webpack_require__(420)
__vue_options__ = __vue_exports__ = __vue_exports__ || {}
if (
  typeof __vue_exports__.default === "object" ||
  typeof __vue_exports__.default === "function"
) {
if (Object.keys(__vue_exports__).some(function (key) { return key !== "default" && key !== "__esModule" })) {console.error("named exports are not supported in *.vue files.")}
__vue_options__ = __vue_exports__ = __vue_exports__.default
}
if (typeof __vue_options__ === "function") {
  __vue_options__ = __vue_options__.options
}
__vue_options__.__file = "/Users/wangfan/Work/webroot/adm/resources/assets/js/pages/adv/advshow.vue"
__vue_options__.render = __vue_template__.render
__vue_options__.staticRenderFns = __vue_template__.staticRenderFns
__vue_options__._scopeId = "data-v-7e34fd20"

/* hot reload */
if (false) {(function () {
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), false)
  if (!hotAPI.compatible) return
  module.hot.accept()
  if (!module.hot.data) {
    hotAPI.createRecord("data-v-7e34fd20", __vue_options__)
  } else {
    hotAPI.reload("data-v-7e34fd20", __vue_options__)
  }
})()}
if (__vue_options__.functional) {console.error("[vue-loader] advshow.vue: functional components are not supported and should be defined in plain js files using render functions.")}

module.exports = __vue_exports__


/***/ })

});