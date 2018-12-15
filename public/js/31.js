webpackJsonp([31],{

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

/***/ 324:
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



/* harmony default export */ __webpack_exports__["default"] = ({
	data: function data() {
		return {
			desc: '',
			min: 0,
			max: 0,
			promise: '',
			onLine: true
		};
	},
	created: function created() {
		var that = this;
		__WEBPACK_IMPORTED_MODULE_0_axios___default.a.get('/bigsmall/desc', {
			responseType: 'json'
		}).then(function (response) {
			console.log(that);
			that.desc = response.data.desc.replace(/\r\n/, '<br/>');
			that.min = response.data.min;
			that.max = response.data.max;
		});
		__WEBPACK_IMPORTED_MODULE_0_axios___default.a.get('/im/isonline?user_id=' + that.$route.params.id + '&api_token=' + window.apiToken, {
			responseType: 'json'
		}).then(function (response) {
			if ('online' == response.data.online) {
				that.onLine = true;
			} else {
				that.onLine = false;
			}
		});
	},
	methods: {
		back: function back() {
			this.$router.back();
		},
		next: function next() {
			var that = this;
			console.log(that);
			if (!that.promise) {
				that.$toast("请填写狗粮", 1500, {
					'top': '50%'
				});
				return false;
			}
			if (false === that.onLine) {
				that.$toast("当前用户不在线，请换个好友试试", 1500, {
					'top': '50%'
				});
				return false;
			}
			__WEBPACK_IMPORTED_MODULE_0_axios___default.a.put('/bigsmall/invite', {
				'promise': that.promise,
				'_token': window._Token,
				'api_token': window.apiToken,
				'invite_user': that.$route.params.id
			}, {
				responseType: 'json'
			}).then(function (ret) {
				console.log(ret);
				that.$toast(ret.data.msg);
				if (ret.data.errcode === 0) {
					//战斗成功 回到发送信息的页面
					setTimeout(function () {
						that.$router.go(-1);
					}, 1500);
				}
			}, function (ret) {
				console.log(ret);
			});
		}
	},
	mounted: function mounted() {
		console.log(this.$route);
	}
});

/***/ }),

/***/ 348:
/***/ (function(module, exports, __webpack_require__) {

exports = module.exports = __webpack_require__(11)(false);
// imports


// module
exports.push([module.i, "\n.bottom-fixed[data-v-0ddea074] {\n\tposition: absolute;\n    right: 0;\n    left: 0;\n    z-index: 10;\n    height: 2.2rem;\n    padding-right: 0.5rem;\n    padding-left: 0.5rem;\n    -webkit-backface-visibility: hidden;\n    backface-visibility: hidden;\n    bottom: 0;\n    width: 100%;\n    padding: 0;\n    table-layout: fixed;\n    background: #fff;\n}\n.next-button[data-v-0ddea074] {\n\tborder-radius: 1.25rem;\n\ttext-decoration: none;\n    text-align: center;\n    display: block;\n    border-radius: 0.25rem;\n    line-height: 2.25rem;\n    box-sizing: border-box;\n    -webkit-appearance: none;\n    -moz-appearance: none;\n    -ms-appearance: none;\n    appearance: none;\n    background: none;\n    padding: 0 0.5rem;\n    margin: 0;\n    height: 2.2rem;\n    white-space: nowrap;\n    position: relative;\n    text-overflow: ellipsis;\n    font-size: 0.8rem;\n    font-family: inherit;\n    cursor: pointer;\n    color: #fff;\n\tbackground: #734d41;\n\tborder: none;\n}\n.tip[data-v-0ddea074] {\n\tbackground: #ffdeab;\n    margin: .5rem;\n    border-radius: .2rem;\n    padding: .2rem 1rem;\n    color: red;\n    font-size: .9rem;\n    text-align: center;\n    font-weight: 400;\n    line-height: 1.8rem;\n}\n.tip p[data-v-0ddea074] {\n\tmargin:0;\n}\n", ""]);

// exports


/***/ }),

/***/ 392:
/***/ (function(module, exports, __webpack_require__) {

module.exports={render:function (){var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
  return _c('div', {
    staticClass: "page"
  }, [_c('header', {
    staticClass: "bar bar-nav"
  }, [_c('a', {
    staticClass: "button button-link button-nav pull-left back",
    attrs: {
      "data-transition": "slide-out"
    },
    on: {
      "click": function($event) {
        _vm.back()
      }
    }
  }, [_c('span', {
    staticClass: "icon icon-left"
  }), _vm._v("\n                    返回\n            ")]), _vm._v(" "), _c('h1', {
    staticClass: "title"
  }, [_vm._v("比大小")]), _vm._v(" "), _c('router-link', {
    staticClass: "button button-link button-nav pull-right",
    attrs: {
      "to": {
        name: 'bigsmall-help'
      }
    }
  }, [_c('span', {
    staticClass: "icon icon-help"
  }), _vm._v("\n\t            帮助\n\t        ")])], 1), _vm._v(" "), _c('div', {
    staticClass: "content infinite-scroll infinite-scroll-bottom",
    attrs: {
      "data-distance": "50"
    }
  }, [_c('div', {
    staticClass: "content-block tip"
  }, [(!_vm.onLine) ? _c('p', [_vm._v("\n\t\t\t\t\t当前用户不在线\n\t\t\t    ")]) : _vm._e(), _vm._v(" "), _c('p', [_vm._v("狗粮应该在" + _vm._s(_vm.min) + "到" + _vm._s(_vm.max) + "之间")])]), _vm._v(" "), _c('div', {
    staticClass: "content-block list-block"
  }, [_c('ul', [_c('li', [_c('div', {
    staticClass: "item-content"
  }, [_c('div', {
    staticClass: "item-inner"
  }, [_c('div', {
    staticClass: "item-title label"
  }, [_vm._v("狗粮")]), _vm._v(" "), _c('div', {
    staticClass: "item-input"
  }, [_c('input', {
    directives: [{
      name: "model",
      rawName: "v-model.number",
      value: (_vm.promise),
      expression: "promise",
      modifiers: {
        "number": true
      }
    }],
    attrs: {
      "type": "text",
      "placeholder": "请输入您想使用的狗粮"
    },
    domProps: {
      "value": (_vm.promise)
    },
    on: {
      "input": function($event) {
        if ($event.target.composing) { return; }
        _vm.promise = _vm._n($event.target.value)
      },
      "blur": function($event) {
        _vm.$forceUpdate()
      }
    }
  })])])])])])]), _vm._v(" "), _c('div', {
    staticClass: "content-block"
  }, [_c('div', {
    staticClass: "button button-danger button-fill button-big",
    on: {
      "click": function($event) {
        _vm.next()
      }
    }
  }, [_vm._v("发起战斗")])])])])
},staticRenderFns: []}
if (false) {
  module.hot.accept()
  if (module.hot.data) {
     require("vue-hot-reload-api").rerender("data-v-0ddea074", module.exports)
  }
}

/***/ }),

/***/ 440:
/***/ (function(module, exports, __webpack_require__) {

// style-loader: Adds some css to the DOM by adding a <style> tag

// load the styles
var content = __webpack_require__(348);
if(typeof content === 'string') content = [[module.i, content, '']];
// add the styles to the DOM
var update = __webpack_require__(246)(content, {});
if(content.locals) module.exports = content.locals;
// Hot Module Replacement
if(false) {
	// When the styles change, update the <style> tags
	if(!content.locals) {
		module.hot.accept("!!../../../../node_modules/css-loader/index.js!../../../../node_modules/vue-loader/lib/style-rewriter.js?id=data-v-0ddea074&scoped=true!../../../../node_modules/vue-loader/lib/selector.js?type=styles&index=0!./inviteBigSmall.vue", function() {
			var newContent = require("!!../../../../node_modules/css-loader/index.js!../../../../node_modules/vue-loader/lib/style-rewriter.js?id=data-v-0ddea074&scoped=true!../../../../node_modules/vue-loader/lib/selector.js?type=styles&index=0!./inviteBigSmall.vue");
			if(typeof newContent === 'string') newContent = [[module.id, newContent, '']];
			update(newContent);
		});
	}
	// When the module is disposed, remove the <style> tags
	module.hot.dispose(function() { update(); });
}

/***/ }),

/***/ 90:
/***/ (function(module, exports, __webpack_require__) {

var __vue_exports__, __vue_options__
var __vue_styles__ = {}

/* styles */
__webpack_require__(440)

/* script */
__vue_exports__ = __webpack_require__(324)

/* template */
var __vue_template__ = __webpack_require__(392)
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
__vue_options__.__file = "/Users/wangfan/Work/webroot/adm/resources/assets/js/pages/inviteBigSmall.vue"
__vue_options__.render = __vue_template__.render
__vue_options__.staticRenderFns = __vue_template__.staticRenderFns
__vue_options__._scopeId = "data-v-0ddea074"

/* hot reload */
if (false) {(function () {
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), false)
  if (!hotAPI.compatible) return
  module.hot.accept()
  if (!module.hot.data) {
    hotAPI.createRecord("data-v-0ddea074", __vue_options__)
  } else {
    hotAPI.reload("data-v-0ddea074", __vue_options__)
  }
})()}
if (__vue_options__.functional) {console.error("[vue-loader] inviteBigSmall.vue: functional components are not supported and should be defined in plain js files using render functions.")}

module.exports = __vue_exports__


/***/ })

});