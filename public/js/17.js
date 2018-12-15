webpackJsonp([17],{

/***/ 106:
/***/ (function(module, exports, __webpack_require__) {

var __vue_exports__, __vue_options__
var __vue_styles__ = {}

/* styles */
__webpack_require__(470)

/* script */
__vue_exports__ = __webpack_require__(340)

/* template */
var __vue_template__ = __webpack_require__(429)
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
__vue_options__.__file = "/Users/wangfan/Work/webroot/adm/resources/assets/js/pages/register.vue"
__vue_options__.render = __vue_template__.render
__vue_options__.staticRenderFns = __vue_template__.staticRenderFns

/* hot reload */
if (false) {(function () {
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), false)
  if (!hotAPI.compatible) return
  module.hot.accept()
  if (!module.hot.data) {
    hotAPI.createRecord("data-v-cd5956f2", __vue_options__)
  } else {
    hotAPI.reload("data-v-cd5956f2", __vue_options__)
  }
})()}
if (__vue_options__.functional) {console.error("[vue-loader] register.vue: functional components are not supported and should be defined in plain js files using render functions.")}

module.exports = __vue_exports__


/***/ }),

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

/***/ 294:
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
Object.defineProperty(__webpack_exports__, "__esModule", { value: true });
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//

/* harmony default export */ __webpack_exports__["default"] = ({});

/***/ }),

/***/ 340:
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
Object.defineProperty(__webpack_exports__, "__esModule", { value: true });
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0_axios__ = __webpack_require__(60);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0_axios___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_0_axios__);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1__utils_js__ = __webpack_require__(61);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_2__components_agreement__ = __webpack_require__(384);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_2__components_agreement___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_2__components_agreement__);
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
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
			mobile: '',
			nickname: '',
			password: '',
			confirmPassword: '',
			verify: '',
			invite: '',
			sending: 0,
			btnVerify: '获取验证码',
			agree: false,
			showAgree: false
		};
	},
	methods: {
		sendVerify: function sendVerify() {
			var that = this;
			if (that.sending > 0) {
				return false;
			}
			if (!that.mobile) {
				that.$toast("请填写手机号码");
				return false;
			}

			var post = {
				'phone': that.mobile,
				'_token': window._Token
			};
			that.sending = 1;
			__WEBPACK_IMPORTED_MODULE_0_axios___default.a.post('/sendmobilesms', post).then(function (ret) {
				var data = ret.data;
				if (data.errcode === 0) {
					that.sending = data.left;
					that.btnVerify = that.sending + 's重新发送';
					var timer = setInterval(function () {
						that.sending--;
						that.btnVerify = that.sending + 's重新发送';
						if (that.sending == 0) {
							clearInterval(timer);
							that.btnVerify = '获取验证码';
						}
					}, 1000);
				} else {
					that.$toast(data.msg);
				}
			});
		},
		agreeInfo: function agreeInfo() {
			this.showAgree = !this.showAgree;
		},
		register: function register() {
			var that = this;

			if (!that.mobile) {
				that.$toast('请填写手机号码');
				return false;
			}
			if (!that.nickname) {
				that.$toast('请填写昵称方便与他人交流');
				return false;
			}
			if (!that.verify) {
				that.$toast('请填写手验证码');
				return false;
			}
			if (!that.password) {
				that.$toast('请填写密码');
				return false;
			}
			if (!that.confirmPassword) {
				that.$toast('请再次输入密码');
				return false;
			}
			if (that.confirmPassword != that.password) {
				that.$toast('两次输入密码不一致');
				return false;
			}
			if (!that.agree) {
				that.$toast('请先同意注册协议');
				return false;
			}
			var post = {
				name: that.mobile,
				nickname: that.nickname,
				password: that.password,
				comfirmed: that.confirmPassword,
				verify: that.verify,
				invite_by: that.invite,
				_token: window._Token
			};
			__WEBPACK_IMPORTED_MODULE_0_axios___default.a.post('/register', post).then(function (ret) {
				var data = ret.data;
				if (data.errcode === 0) {
					if (data.errcode === 0) {
						that.$toast('注册成功');
						document.querySelector('meta[name="api-token"]').content = data.user.api_token;
						window.apiToken = data.user.api_token;
						window.imUser = data.user.name;
						var options = {
							apiUrl: WebIM.config.apiURL,
							user: data.user.name,
							pwd: data.user.api_token,
							appKey: WebIM.config.appkey,
							success: function success(token) {
								console.log(token);
								var token = token.access_token;
								__webpack_require__.i(__WEBPACK_IMPORTED_MODULE_1__utils_js__["a" /* setCookie */])('webim_' + data.user.name, token, 1);
								console.log('im login ok');
								var from = __webpack_require__.i(__WEBPACK_IMPORTED_MODULE_1__utils_js__["c" /* getCookie */])('_fromUrl');
								from = from ? from : '/';
								from = decodeURIComponent(from);
								__webpack_require__.i(__WEBPACK_IMPORTED_MODULE_1__utils_js__["d" /* delCookie */])('_fromUrl');
								that.$router.push({
									path: from
								});
							},
							error: function error(data) {
								that.$toast('im login err');
								console.log(data);
							}
						};
						conn.open(options);
						/**
      setTimeout( function(){
      that.$router.push({name:'home'})
      } , 1500 );
      **/
					}
				} else {
					that.$toast(data.msg);
				}
			});
		}
	},
	components: {
		agreement: __WEBPACK_IMPORTED_MODULE_2__components_agreement___default.a
	}
});

/***/ }),

/***/ 378:
/***/ (function(module, exports, __webpack_require__) {

exports = module.exports = __webpack_require__(11)(false);
// imports


// module
exports.push([module.i, "\n.show {\n\tdisplay:block ;\n}\n", ""]);

// exports


/***/ }),

/***/ 384:
/***/ (function(module, exports, __webpack_require__) {

var __vue_exports__, __vue_options__
var __vue_styles__ = {}

/* script */
__vue_exports__ = __webpack_require__(294)

/* template */
var __vue_template__ = __webpack_require__(419)
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
__vue_options__.__file = "/Users/wangfan/Work/webroot/adm/resources/assets/js/components/agreement.vue"
__vue_options__.render = __vue_template__.render
__vue_options__.staticRenderFns = __vue_template__.staticRenderFns

/* hot reload */
if (false) {(function () {
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), false)
  if (!hotAPI.compatible) return
  module.hot.accept()
  if (!module.hot.data) {
    hotAPI.createRecord("data-v-71516fec", __vue_options__)
  } else {
    hotAPI.reload("data-v-71516fec", __vue_options__)
  }
})()}
if (__vue_options__.functional) {console.error("[vue-loader] agreement.vue: functional components are not supported and should be defined in plain js files using render functions.")}

module.exports = __vue_exports__


/***/ }),

/***/ 419:
/***/ (function(module, exports, __webpack_require__) {

module.exports={render:function (){var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
  return _vm._m(0)
},staticRenderFns: [function (){var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
  return _c('div', {
    staticClass: "WordSection1",
    staticStyle: {
      "layout-grid": "15.6pt"
    }
  }, [_c('p', {
    staticClass: "MsoNormal",
    staticStyle: {
      "mso-margin-top-alt": "auto",
      "text-align": "left",
      "mso-pagination": "widow-orphan",
      "mso-outline-level": "4"
    },
    attrs: {
      "align": "left"
    }
  }, [_c('b', [_c('span', {
    staticStyle: {
      "font-size": ".65rem",
      "font-family": "宋体",
      "mso-bidi-font-family": "宋体"
    }
  }, [_vm._v("一、总则"), _c('span', {
    attrs: {
      "lang": "EN-US"
    }
  })])])]), _vm._v(" "), _c('p', {
    staticClass: "MsoNormal",
    staticStyle: {
      "text-align": "left",
      "mso-pagination": "widow-orphan"
    },
    attrs: {
      "align": "left"
    }
  }, [_c('span', {
    staticStyle: {
      "font-size": ".65rem",
      "font-family": "宋体",
      "mso-bidi-font-family": "宋体",
      "mso-font-kerning": "0pt"
    },
    attrs: {
      "lang": "EN-US"
    }
  }, [_vm._v("1.1 ")]), _c('span', {
    staticStyle: {
      "font-size": ".65rem",
      "mso-bidi-font-family": "宋体",
      "mso-font-kerning": "0pt"
    }
  }, [_vm._v("狗运网的所有权和运营权归广州康园科技有限公司所有。"), _c('span', {
    attrs: {
      "lang": "EN-US"
    }
  })])]), _vm._v(" "), _c('p', {
    staticClass: "MsoNormal",
    staticStyle: {
      "text-align": "left",
      "mso-pagination": "widow-orphan"
    },
    attrs: {
      "align": "left"
    }
  }, [_c('span', {
    staticStyle: {
      "font-size": ".65rem",
      "font-family": "宋体",
      "mso-bidi-font-family": "宋体",
      "mso-font-kerning": "0pt"
    },
    attrs: {
      "lang": "EN-US"
    }
  }, [_vm._v("1.2 ")]), _c('span', {
    staticStyle: {
      "font-size": ".65rem",
      "mso-bidi-font-family": "宋体",
      "mso-font-kerning": "0pt"
    }
  }, [_vm._v("用户在注册之前，应当仔细阅读本协议，并同意遵守本协议后方可成为注册用户。一旦注册成功，则用户与狗运网之间自动形成协议关系，用户应当受本协议的约束。用户在使用特殊的服务或产品时，应当同意接受相关协议后方能使用。"), _c('span', {
    attrs: {
      "lang": "EN-US"
    }
  })])]), _vm._v(" "), _c('p', {
    staticClass: "MsoNormal",
    staticStyle: {
      "text-align": "left",
      "mso-pagination": "widow-orphan"
    },
    attrs: {
      "align": "left"
    }
  }, [_c('span', {
    staticStyle: {
      "font-size": ".65rem",
      "font-family": "宋体",
      "mso-bidi-font-family": "宋体",
      "mso-font-kerning": "0pt"
    },
    attrs: {
      "lang": "EN-US"
    }
  }, [_vm._v("1.3 ")]), _c('span', {
    staticStyle: {
      "font-size": ".65rem",
      "mso-bidi-font-family": "宋体",
      "mso-font-kerning": "0pt"
    }
  }, [_vm._v("本协议则可由狗运网随时更新，用户应当及时关注并同意本站不承担通知义务。本站的通知、公告、声明或其它类似内容是本协议的一部分。"), _c('span', {
    attrs: {
      "lang": "EN-US"
    }
  })])]), _vm._v(" "), _c('p', {
    staticClass: "MsoNormal",
    staticStyle: {
      "mso-margin-top-alt": "auto",
      "text-align": "left",
      "mso-pagination": "widow-orphan",
      "mso-outline-level": "4"
    },
    attrs: {
      "align": "left"
    }
  }, [_c('b', [_c('span', {
    staticStyle: {
      "font-size": ".65rem",
      "font-family": "宋体",
      "mso-bidi-font-family": "宋体"
    }
  }, [_vm._v("二、服务内容"), _c('span', {
    attrs: {
      "lang": "EN-US"
    }
  })])])]), _vm._v(" "), _c('p', {
    staticClass: "MsoNormal",
    staticStyle: {
      "text-align": "left",
      "mso-pagination": "widow-orphan"
    },
    attrs: {
      "align": "left"
    }
  }, [_c('span', {
    staticStyle: {
      "font-size": ".65rem",
      "font-family": "宋体",
      "mso-bidi-font-family": "宋体",
      "mso-font-kerning": "0pt"
    },
    attrs: {
      "lang": "EN-US"
    }
  }, [_vm._v("2.1 ")]), _c('span', {
    staticStyle: {
      "font-size": ".65rem",
      "mso-bidi-font-family": "宋体",
      "mso-font-kerning": "0pt"
    }
  }, [_vm._v("狗运网的具体内容由本站根据实际情况提供。"), _c('span', {
    attrs: {
      "lang": "EN-US"
    }
  })])]), _vm._v(" "), _c('p', {
    staticClass: "MsoNormal",
    staticStyle: {
      "text-align": "left",
      "mso-pagination": "widow-orphan"
    },
    attrs: {
      "align": "left"
    }
  }, [_c('span', {
    staticStyle: {
      "font-size": ".65rem",
      "font-family": "宋体",
      "mso-bidi-font-family": "宋体",
      "mso-font-kerning": "0pt"
    },
    attrs: {
      "lang": "EN-US"
    }
  }, [_vm._v("2.2 ")]), _c('span', {
    staticStyle: {
      "font-size": ".65rem",
      "mso-bidi-font-family": "宋体",
      "mso-font-kerning": "0pt"
    }
  }, [_vm._v("本站仅提供相关的网络服务，除此之外与相关网络服务有关的设备"), _c('span', {
    attrs: {
      "lang": "EN-US"
    }
  }, [_vm._v("(")]), _vm._v("如个人电脑、手机、及其他与接入互联网或移动网有关的装置"), _c('span', {
    attrs: {
      "lang": "EN-US"
    }
  }, [_vm._v(")")]), _vm._v("及所需的费用"), _c('span', {
    attrs: {
      "lang": "EN-US"
    }
  }, [_vm._v("(")]), _vm._v("如为接入互联网而支付的电话费及上网费、为使用移动网而支付的手机费"), _c('span', {
    attrs: {
      "lang": "EN-US"
    }
  }, [_vm._v(")")]), _vm._v("均应由用户自行负担。"), _c('span', {
    attrs: {
      "lang": "EN-US"
    }
  })])]), _vm._v(" "), _c('p', {
    staticClass: "MsoNormal",
    staticStyle: {
      "mso-margin-top-alt": "auto",
      "text-align": "left",
      "mso-pagination": "widow-orphan",
      "mso-outline-level": "4"
    },
    attrs: {
      "align": "left"
    }
  }, [_c('b', [_c('span', {
    staticStyle: {
      "font-size": ".65rem",
      "font-family": "宋体",
      "mso-bidi-font-family": "宋体"
    }
  }, [_vm._v("三、用户帐号"), _c('span', {
    attrs: {
      "lang": "EN-US"
    }
  })])])]), _vm._v(" "), _c('p', {
    staticClass: "MsoNormal",
    staticStyle: {
      "text-align": "left",
      "mso-pagination": "widow-orphan"
    },
    attrs: {
      "align": "left"
    }
  }, [_c('span', {
    staticStyle: {
      "font-size": ".65rem",
      "font-family": "宋体",
      "mso-bidi-font-family": "宋体",
      "mso-font-kerning": "0pt"
    },
    attrs: {
      "lang": "EN-US"
    }
  }, [_vm._v("3.1 ")]), _c('span', {
    staticStyle: {
      "font-size": ".65rem",
      "mso-bidi-font-family": "宋体",
      "mso-font-kerning": "0pt"
    }
  }, [_vm._v("经本站注册系统完成注册程序的用户即成为正式用户，可以获得本站规定用户所应享有的权限；狗运网有权对用户的权限设计进行变更。"), _c('span', {
    attrs: {
      "lang": "EN-US"
    }
  })])]), _vm._v(" "), _c('p', {
    staticClass: "MsoNormal",
    staticStyle: {
      "text-align": "left",
      "mso-pagination": "widow-orphan"
    },
    attrs: {
      "align": "left"
    }
  }, [_c('span', {
    staticStyle: {
      "font-size": ".65rem",
      "font-family": "宋体",
      "mso-bidi-font-family": "宋体",
      "mso-font-kerning": "0pt"
    },
    attrs: {
      "lang": "EN-US"
    }
  }, [_vm._v("3.2 ")]), _c('span', {
    staticStyle: {
      "font-size": ".65rem",
      "mso-bidi-font-family": "宋体",
      "mso-font-kerning": "0pt"
    }
  }, [_vm._v("用户只能按照注册要求使用真实电话号码和支付宝账号等信息注册。用户有义务保证密码和帐号的安全，用户利用该密码和帐号所进行的一切活动引起的任何损失或损害，由用户自行承担全部责任，本站不承担任何责任。如用户发现帐号遭到未授权的使用或发生其他任何安全问题，应立即修改帐号密码并妥善保管，如有必要，请通知本站。因黑客行为或用户的保管疏忽导致帐号非法使用，本站不承担任何责任。"), _c('span', {
    attrs: {
      "lang": "EN-US"
    }
  })])]), _vm._v(" "), _c('p', {
    staticClass: "MsoNormal",
    staticStyle: {
      "mso-margin-top-alt": "auto",
      "text-align": "left",
      "mso-pagination": "widow-orphan",
      "mso-outline-level": "4"
    },
    attrs: {
      "align": "left"
    }
  }, [_c('b', [_c('span', {
    staticStyle: {
      "font-size": ".65rem",
      "font-family": "宋体",
      "mso-bidi-font-family": "宋体"
    }
  }, [_vm._v("四、使用规则"), _c('span', {
    attrs: {
      "lang": "EN-US"
    }
  })])])]), _vm._v(" "), _c('p', {
    staticClass: "MsoNormal",
    staticStyle: {
      "text-align": "left",
      "mso-pagination": "widow-orphan"
    },
    attrs: {
      "align": "left"
    }
  }, [_c('span', {
    staticStyle: {
      "font-size": ".65rem",
      "font-family": "宋体",
      "mso-bidi-font-family": "宋体",
      "mso-font-kerning": "0pt"
    },
    attrs: {
      "lang": "EN-US"
    }
  }, [_vm._v("4.1 ")]), _c('span', {
    staticStyle: {
      "font-size": ".65rem",
      "mso-bidi-font-family": "宋体",
      "mso-font-kerning": "0pt"
    }
  }, [_vm._v("遵守中华人民共和国相关法律法规，包括但不限于《中华人民共和国计算机信息系统安全保护条例》、《计算机软件保护条例》、《最高人民法院关于审理涉及计算机网络著作权纠纷案件适用法律若干问题的解释"), _c('span', {
    attrs: {
      "lang": "EN-US"
    }
  }, [_vm._v("(")]), _vm._v("法释"), _c('span', {
    attrs: {
      "lang": "EN-US"
    }
  }, [_vm._v("[2004]1")]), _vm._v("号"), _c('span', {
    attrs: {
      "lang": "EN-US"
    }
  }, [_vm._v(")")]), _vm._v("》、《全国人大常委会关于维护互联网安全的决定》、《互联网电子公告服务管理规定》、《互联网新闻信息服务管理规定》、《互联网著作权行政保护办法》和《信息网络传播权保护条例》等有关计算机互联网规定和知识产权的法律和法规、实施办法。"), _c('span', {
    attrs: {
      "lang": "EN-US"
    }
  })])]), _vm._v(" "), _c('p', {
    staticClass: "MsoNormal",
    staticStyle: {
      "text-align": "left",
      "mso-pagination": "widow-orphan"
    },
    attrs: {
      "align": "left"
    }
  }, [_c('span', {
    staticStyle: {
      "font-size": ".65rem",
      "font-family": "宋体",
      "mso-bidi-font-family": "宋体",
      "mso-font-kerning": "0pt"
    },
    attrs: {
      "lang": "EN-US"
    }
  }, [_vm._v("4.2 ")]), _c('span', {
    staticStyle: {
      "font-size": ".65rem",
      "mso-bidi-font-family": "宋体",
      "mso-font-kerning": "0pt"
    }
  }, [_vm._v("用户对其自行发表、上传或传送的内容负全部责任，所有用户不得在本站任何页面发布、转载、传送含有下列内容之一的信息，否则本站有权自行处理并不通知用户："), _c('span', {
    attrs: {
      "lang": "EN-US"
    }
  })])]), _vm._v(" "), _c('p', {
    staticClass: "MsoNormal",
    staticStyle: {
      "text-align": "left",
      "mso-pagination": "widow-orphan"
    },
    attrs: {
      "align": "left"
    }
  }, [_c('span', {
    staticStyle: {
      "font-size": ".65rem",
      "font-family": "宋体",
      "mso-bidi-font-family": "宋体",
      "mso-font-kerning": "0pt"
    },
    attrs: {
      "lang": "EN-US"
    }
  }, [_vm._v("(1)")]), _c('span', {
    staticStyle: {
      "font-size": ".65rem",
      "font-family": "宋体",
      "mso-bidi-font-family": "宋体",
      "mso-font-kerning": "0pt"
    }
  }, [_vm._v("违反宪法确定的基本原则的；"), _c('span', {
    attrs: {
      "lang": "EN-US"
    }
  }, [_vm._v("\n(2)")]), _vm._v("危害国家安全，泄漏国家机密，颠覆国家政权，破坏国家统一的；"), _c('span', {
    attrs: {
      "lang": "EN-US"
    }
  }, [_vm._v(" (3)")]), _vm._v("损害国家荣誉和利益的；"), _c('span', {
    attrs: {
      "lang": "EN-US"
    }
  }, [_vm._v(" (4)")]), _vm._v("煽动民族仇恨、民族歧视，破坏民族团结的；"), _c('span', {
    attrs: {
      "lang": "EN-US"
    }
  }, [_vm._v(" (5)")]), _vm._v("破坏国家宗教政策，宣扬邪教和封建迷信的；"), _c('span', {
    attrs: {
      "lang": "EN-US"
    }
  }, [_vm._v(" (6)")]), _vm._v("散布谣言，扰乱社会秩序，破坏社会稳定的；"), _c('span', {
    attrs: {
      "lang": "EN-US"
    }
  }, [_vm._v("(7)")]), _vm._v("散布淫秽、色情、赌博、暴力、恐怖或者教唆犯罪的；"), _c('span', {
    attrs: {
      "lang": "EN-US"
    }
  }, [_vm._v(" (8)")]), _vm._v("侮辱或者诽谤他人，侵害他人合法权益的；"), _c('span', {
    attrs: {
      "lang": "EN-US"
    }
  }, [_vm._v(" (9)")]), _vm._v("煽动非法集会、结社、游行、示威、聚众扰乱社会秩序的；"), _c('span', {
    attrs: {
      "lang": "EN-US"
    }
  }, [_vm._v(" (10)")]), _vm._v("以非法民间组织名义活动的；"), _c('span', {
    attrs: {
      "lang": "EN-US"
    }
  }, [_vm._v("(11)")]), _vm._v("含有法律、行政法规禁止的其他内容的。\n"), _c('span', {
    attrs: {
      "lang": "EN-US"
    }
  })])]), _vm._v(" "), _c('p', {
    staticClass: "MsoNormal",
    staticStyle: {
      "text-align": "left",
      "mso-pagination": "widow-orphan"
    },
    attrs: {
      "align": "left"
    }
  }, [_c('span', {
    staticStyle: {
      "font-size": ".65rem",
      "font-family": "宋体",
      "mso-bidi-font-family": "宋体",
      "mso-font-kerning": "0pt"
    },
    attrs: {
      "lang": "EN-US"
    }
  }, [_vm._v("4.3 ")]), _c('span', {
    staticStyle: {
      "font-size": ".65rem",
      "mso-bidi-font-family": "宋体",
      "mso-font-kerning": "0pt"
    }
  }, [_vm._v("用户承诺对其发表或者上传于本站的所有信息"), _c('span', {
    attrs: {
      "lang": "EN-US"
    }
  }, [_vm._v("(")]), _vm._v("即属于《中华人民共和国著作权法》规定的作品，包括但不限于文字、图片、音乐、电影、表演和录音录像制品和电脑程序等"), _c('span', {
    attrs: {
      "lang": "EN-US"
    }
  }, [_vm._v(")")]), _vm._v("均享有完整的知识产权，或者已经得到相关权利人的合法授权；如用户违反本条规定造成本站被第三人索赔的，用户应全额补偿本站一切费用"), _c('span', {
    attrs: {
      "lang": "EN-US"
    }
  }, [_vm._v("(")]), _vm._v("包括但不限于各种赔偿费、诉讼代理费及为此支出的其它合理费用"), _c('span', {
    attrs: {
      "lang": "EN-US"
    }
  }, [_vm._v(")")]), _vm._v("； "), _c('span', {
    attrs: {
      "lang": "EN-US"
    }
  })])]), _vm._v(" "), _c('p', {
    staticClass: "MsoNormal",
    staticStyle: {
      "text-align": "left",
      "mso-pagination": "widow-orphan"
    },
    attrs: {
      "align": "left"
    }
  }, [_c('span', {
    staticStyle: {
      "font-size": ".65rem",
      "font-family": "宋体",
      "mso-bidi-font-family": "宋体",
      "mso-font-kerning": "0pt"
    },
    attrs: {
      "lang": "EN-US"
    }
  }, [_vm._v("4.4 ")]), _c('span', {
    staticStyle: {
      "font-size": ".65rem",
      "mso-bidi-font-family": "宋体",
      "mso-font-kerning": "0pt"
    }
  }, [_vm._v("当第三方认为用户发表或者上传于本站的信息侵犯其权利，并根据《信息网络传播权保护条例》或者相关法律规定向本站发送权利通知书时，用户同意本站可以自行判断决定删除涉嫌侵权信息，除非用户提交书面证据材料排除侵权的可能性，本站将不会自动恢复上述删除的信息；\n"), _c('span', {
    attrs: {
      "lang": "EN-US"
    }
  })])]), _vm._v(" "), _c('p', {
    staticClass: "MsoNormal",
    staticStyle: {
      "text-align": "left",
      "mso-pagination": "widow-orphan"
    },
    attrs: {
      "align": "left"
    }
  }, [_c('span', {
    staticStyle: {
      "font-size": ".65rem",
      "font-family": "宋体",
      "mso-bidi-font-family": "宋体",
      "mso-font-kerning": "0pt"
    },
    attrs: {
      "lang": "EN-US"
    }
  }, [_vm._v("(1)")]), _c('span', {
    staticStyle: {
      "font-size": ".65rem",
      "font-family": "宋体",
      "mso-bidi-font-family": "宋体",
      "mso-font-kerning": "0pt"
    }
  }, [_vm._v("不得为任何非法目的而使用网络服务系统； "), _c('span', {
    attrs: {
      "lang": "EN-US"
    }
  })])]), _vm._v(" "), _c('p', {
    staticClass: "MsoNormal",
    staticStyle: {
      "text-align": "left",
      "mso-pagination": "widow-orphan"
    },
    attrs: {
      "align": "left"
    }
  }, [_c('span', {
    staticStyle: {
      "font-size": ".65rem",
      "font-family": "宋体",
      "mso-bidi-font-family": "宋体",
      "mso-font-kerning": "0pt"
    },
    attrs: {
      "lang": "EN-US"
    }
  }, [_vm._v("(2)")]), _c('span', {
    staticStyle: {
      "font-size": ".65rem",
      "font-family": "宋体",
      "mso-bidi-font-family": "宋体",
      "mso-font-kerning": "0pt"
    }
  }, [_vm._v("遵守所有与网络服务有关的网络协议、规定和程序；"), _c('span', {
    attrs: {
      "lang": "EN-US"
    }
  })])]), _vm._v(" "), _c('p', {
    staticClass: "MsoNormal",
    staticStyle: {
      "text-align": "left",
      "mso-pagination": "widow-orphan"
    },
    attrs: {
      "align": "left"
    }
  }, [_c('span', {
    staticStyle: {
      "font-size": ".65rem",
      "font-family": "宋体",
      "mso-bidi-font-family": "宋体",
      "mso-font-kerning": "0pt"
    },
    attrs: {
      "lang": "EN-US"
    }
  }, [_vm._v("(3)")]), _c('span', {
    staticStyle: {
      "font-size": ".65rem",
      "font-family": "宋体",
      "mso-bidi-font-family": "宋体",
      "mso-font-kerning": "0pt"
    }
  }, [_vm._v("不得利用本站进行任何可能对互联网的正常运转造成不利影响的行为；"), _c('span', {
    attrs: {
      "lang": "EN-US"
    }
  })])]), _vm._v(" "), _c('p', {
    staticClass: "MsoNormal",
    staticStyle: {
      "text-align": "left",
      "mso-pagination": "widow-orphan"
    },
    attrs: {
      "align": "left"
    }
  }, [_c('span', {
    staticStyle: {
      "font-size": ".65rem",
      "font-family": "宋体",
      "mso-bidi-font-family": "宋体",
      "mso-font-kerning": "0pt"
    },
    attrs: {
      "lang": "EN-US"
    }
  }, [_vm._v("(4)")]), _c('span', {
    staticStyle: {
      "font-size": ".65rem",
      "font-family": "宋体",
      "mso-bidi-font-family": "宋体",
      "mso-font-kerning": "0pt"
    }
  }, [_vm._v("不得利用本站进行任何不利于本站的行为。 "), _c('span', {
    attrs: {
      "lang": "EN-US"
    }
  })])]), _vm._v(" "), _c('p', {
    staticClass: "MsoNormal",
    staticStyle: {
      "text-align": "left",
      "mso-pagination": "widow-orphan"
    },
    attrs: {
      "align": "left"
    }
  }, [_c('span', {
    staticStyle: {
      "font-size": ".65rem",
      "font-family": "宋体",
      "mso-bidi-font-family": "宋体",
      "mso-font-kerning": "0pt"
    },
    attrs: {
      "lang": "EN-US"
    }
  }, [_vm._v("4.5 ")]), _c('span', {
    staticStyle: {
      "font-size": ".65rem",
      "mso-bidi-font-family": "宋体",
      "mso-font-kerning": "0pt"
    }
  }, [_vm._v("如用户在使用网络服务时违反上述任何规定，本站有权要求用户改正或直接采取一切必要的措施"), _c('span', {
    attrs: {
      "lang": "EN-US"
    }
  }, [_vm._v("(")]), _vm._v("包括但不限于删除用户张贴的内容、暂停或终止用户使用网络服务的权利"), _c('span', {
    attrs: {
      "lang": "EN-US"
    }
  }, [_vm._v(")")]), _vm._v("以减轻用户不当行为而造成的影响。"), _c('span', {
    attrs: {
      "lang": "EN-US"
    }
  })])]), _vm._v(" "), _c('p', {
    staticClass: "MsoNormal",
    staticStyle: {
      "mso-margin-top-alt": "auto",
      "text-align": "left",
      "mso-pagination": "widow-orphan",
      "mso-outline-level": "4"
    },
    attrs: {
      "align": "left"
    }
  }, [_c('b', [_c('span', {
    staticStyle: {
      "font-size": ".65rem",
      "font-family": "宋体",
      "mso-bidi-font-family": "宋体"
    }
  }, [_vm._v("五、隐私保护"), _c('span', {
    attrs: {
      "lang": "EN-US"
    }
  })])])]), _vm._v(" "), _c('p', {
    staticClass: "MsoNormal",
    staticStyle: {
      "text-align": "left",
      "mso-pagination": "widow-orphan"
    },
    attrs: {
      "align": "left"
    }
  }, [_c('span', {
    staticStyle: {
      "font-size": ".65rem",
      "font-family": "宋体",
      "mso-bidi-font-family": "宋体",
      "mso-font-kerning": "0pt"
    },
    attrs: {
      "lang": "EN-US"
    }
  }, [_vm._v("5.1 ")]), _c('span', {
    staticStyle: {
      "font-size": ".65rem",
      "mso-bidi-font-family": "宋体",
      "mso-font-kerning": "0pt"
    }
  }, [_vm._v("本站不对外公开或向第三方提供单个用户的注册资料及用户在使用网络服务时存储在本站的非公开内容，但下列情况除外："), _c('span', {
    attrs: {
      "lang": "EN-US"
    }
  }, [_vm._v(" (1)")]), _vm._v("事先获得用户的明确授权；"), _c('span', {
    attrs: {
      "lang": "EN-US"
    }
  }, [_vm._v(" (2)")]), _vm._v("根据有关的法律法规要求；"), _c('span', {
    attrs: {
      "lang": "EN-US"
    }
  }, [_vm._v("(3)")]), _vm._v("按照相关政府主管部门的要求；"), _c('span', {
    attrs: {
      "lang": "EN-US"
    }
  }, [_vm._v("(4)")]), _vm._v("为维护社会公众的利益。 "), _c('span', {
    attrs: {
      "lang": "EN-US"
    }
  })])]), _vm._v(" "), _c('p', {
    staticClass: "MsoNormal",
    staticStyle: {
      "text-align": "left",
      "mso-pagination": "widow-orphan"
    },
    attrs: {
      "align": "left"
    }
  }, [_c('span', {
    staticStyle: {
      "font-size": ".65rem",
      "font-family": "宋体",
      "mso-bidi-font-family": "宋体",
      "mso-font-kerning": "0pt"
    },
    attrs: {
      "lang": "EN-US"
    }
  }, [_vm._v("5.2 ")]), _c('span', {
    staticStyle: {
      "font-size": ".65rem",
      "mso-bidi-font-family": "宋体",
      "mso-font-kerning": "0pt"
    }
  }, [_vm._v("本站可能会与第三方合作向用户提供相关的网络服务，在此情况下，如该第三方同意承担与本站同等的保护用户隐私的责任，则本站有权将用户的注册资料等提供给该第三方。"), _c('span', {
    attrs: {
      "lang": "EN-US"
    }
  })])]), _vm._v(" "), _c('p', {
    staticClass: "MsoNormal",
    staticStyle: {
      "text-align": "left",
      "mso-pagination": "widow-orphan"
    },
    attrs: {
      "align": "left"
    }
  }, [_c('span', {
    staticStyle: {
      "font-size": ".65rem",
      "font-family": "宋体",
      "mso-bidi-font-family": "宋体",
      "mso-font-kerning": "0pt"
    },
    attrs: {
      "lang": "EN-US"
    }
  }, [_vm._v("5.3 ")]), _c('span', {
    staticStyle: {
      "font-size": ".65rem",
      "mso-bidi-font-family": "宋体",
      "mso-font-kerning": "0pt"
    }
  }, [_vm._v("在不透露单个用户隐私资料的前提下，本站有权对整个用户数据库进行分析并对用户数据库进行商业上的利用。"), _c('span', {
    attrs: {
      "lang": "EN-US"
    }
  })])]), _vm._v(" "), _c('p', {
    staticClass: "MsoNormal",
    staticStyle: {
      "mso-margin-top-alt": "auto",
      "text-align": "left",
      "mso-pagination": "widow-orphan",
      "mso-outline-level": "4"
    },
    attrs: {
      "align": "left"
    }
  }, [_c('b', [_c('span', {
    staticStyle: {
      "font-size": ".65rem",
      "font-family": "宋体",
      "mso-bidi-font-family": "宋体"
    }
  }, [_vm._v("六、版权声明"), _c('span', {
    attrs: {
      "lang": "EN-US"
    }
  })])])]), _vm._v(" "), _c('p', {
    staticClass: "MsoNormal",
    staticStyle: {
      "text-align": "left",
      "mso-pagination": "widow-orphan"
    },
    attrs: {
      "align": "left"
    }
  }, [_c('span', {
    staticStyle: {
      "font-size": ".65rem",
      "font-family": "宋体",
      "mso-bidi-font-family": "宋体",
      "mso-font-kerning": "0pt"
    },
    attrs: {
      "lang": "EN-US"
    }
  }, [_vm._v("6.1 ")]), _c('span', {
    staticStyle: {
      "font-size": ".65rem",
      "mso-bidi-font-family": "宋体",
      "mso-font-kerning": "0pt"
    }
  }, [_vm._v("本站的文字、图片、音频、视频等版权均归广州康园科技有限公司享有或与作者共同享有，未经本站许可，不得任意转载。"), _c('span', {
    attrs: {
      "lang": "EN-US"
    }
  })])]), _vm._v(" "), _c('p', {
    staticClass: "MsoNormal",
    staticStyle: {
      "text-align": "left",
      "mso-pagination": "widow-orphan"
    },
    attrs: {
      "align": "left"
    }
  }, [_c('span', {
    staticStyle: {
      "font-size": ".65rem",
      "font-family": "宋体",
      "mso-bidi-font-family": "宋体",
      "mso-font-kerning": "0pt"
    },
    attrs: {
      "lang": "EN-US"
    }
  }, [_vm._v("6.2 ")]), _c('span', {
    staticStyle: {
      "font-size": ".65rem",
      "mso-bidi-font-family": "宋体",
      "mso-font-kerning": "0pt"
    }
  }, [_vm._v("本站特有的标识、版面设计、编排方式等版权均属广州康园科技有限公司享有，未经本站许可，不得任意复制或转载。"), _c('span', {
    attrs: {
      "lang": "EN-US"
    }
  })])]), _vm._v(" "), _c('p', {
    staticClass: "MsoNormal",
    staticStyle: {
      "text-align": "left",
      "mso-pagination": "widow-orphan"
    },
    attrs: {
      "align": "left"
    }
  }, [_c('span', {
    staticStyle: {
      "font-size": ".65rem",
      "font-family": "宋体",
      "mso-bidi-font-family": "宋体",
      "mso-font-kerning": "0pt"
    },
    attrs: {
      "lang": "EN-US"
    }
  }, [_vm._v("6.3 ")]), _c('span', {
    staticStyle: {
      "font-size": ".65rem",
      "mso-bidi-font-family": "宋体",
      "mso-font-kerning": "0pt"
    }
  }, [_vm._v("使用本站的任何内容均应注明"), _c('span', {
    attrs: {
      "lang": "EN-US"
    }
  }, [_vm._v("“")]), _vm._v("来源于狗运网"), _c('span', {
    attrs: {
      "lang": "EN-US"
    }
  }, [_vm._v("”")]), _vm._v("及署上作者姓名，按法律规定需要支付稿酬的，应当通知本站及作者及支付稿酬，并独立承担一切法律责任。"), _c('span', {
    attrs: {
      "lang": "EN-US"
    }
  })])]), _vm._v(" "), _c('p', {
    staticClass: "MsoNormal",
    staticStyle: {
      "text-align": "left",
      "mso-pagination": "widow-orphan"
    },
    attrs: {
      "align": "left"
    }
  }, [_c('span', {
    staticStyle: {
      "font-size": ".65rem",
      "font-family": "宋体",
      "mso-bidi-font-family": "宋体",
      "mso-font-kerning": "0pt"
    },
    attrs: {
      "lang": "EN-US"
    }
  }, [_vm._v("6.4 ")]), _c('span', {
    staticStyle: {
      "font-size": ".65rem",
      "mso-bidi-font-family": "宋体",
      "mso-font-kerning": "0pt"
    }
  }, [_vm._v("本站享有所有作品用于其它用途的优先权，包括但不限于网站、电子杂志、平面出版等，但在使用前会通知作者，并按同行业的标准支付稿酬。"), _c('span', {
    attrs: {
      "lang": "EN-US"
    }
  })])]), _vm._v(" "), _c('p', {
    staticClass: "MsoNormal",
    staticStyle: {
      "text-align": "left",
      "mso-pagination": "widow-orphan"
    },
    attrs: {
      "align": "left"
    }
  }, [_c('span', {
    staticStyle: {
      "font-size": ".65rem",
      "font-family": "宋体",
      "mso-bidi-font-family": "宋体",
      "mso-font-kerning": "0pt"
    },
    attrs: {
      "lang": "EN-US"
    }
  }, [_vm._v("6.5 ")]), _c('span', {
    staticStyle: {
      "font-size": ".65rem",
      "mso-bidi-font-family": "宋体",
      "mso-font-kerning": "0pt"
    }
  }, [_vm._v("本站所有内容仅代表作者自己的立场和观点，与本站无关，由作者本人承担一切法律责任。"), _c('span', {
    attrs: {
      "lang": "EN-US"
    }
  })])]), _vm._v(" "), _c('p', {
    staticClass: "MsoNormal",
    staticStyle: {
      "text-align": "left",
      "mso-pagination": "widow-orphan"
    },
    attrs: {
      "align": "left"
    }
  }, [_c('span', {
    staticStyle: {
      "font-size": ".65rem",
      "font-family": "宋体",
      "mso-bidi-font-family": "宋体",
      "mso-font-kerning": "0pt"
    },
    attrs: {
      "lang": "EN-US"
    }
  }, [_vm._v("6.6 ")]), _c('span', {
    staticStyle: {
      "font-size": ".65rem",
      "mso-bidi-font-family": "宋体",
      "mso-font-kerning": "0pt"
    }
  }, [_vm._v("恶意转载本站内容的，本站保留将其诉诸法律的权利。"), _c('span', {
    attrs: {
      "lang": "EN-US"
    }
  })])]), _vm._v(" "), _c('p', {
    staticClass: "MsoNormal",
    staticStyle: {
      "mso-margin-top-alt": "auto",
      "text-align": "left",
      "mso-pagination": "widow-orphan",
      "mso-outline-level": "4"
    },
    attrs: {
      "align": "left"
    }
  }, [_c('b', [_c('span', {
    staticStyle: {
      "font-size": ".65rem",
      "font-family": "宋体",
      "mso-bidi-font-family": "宋体"
    }
  }, [_vm._v("七、责任声明"), _c('span', {
    attrs: {
      "lang": "EN-US"
    }
  })])])]), _vm._v(" "), _c('p', {
    staticClass: "MsoNormal",
    staticStyle: {
      "text-align": "left",
      "mso-pagination": "widow-orphan"
    },
    attrs: {
      "align": "left"
    }
  }, [_c('span', {
    staticStyle: {
      "font-size": ".65rem",
      "font-family": "宋体",
      "mso-bidi-font-family": "宋体",
      "mso-font-kerning": "0pt"
    },
    attrs: {
      "lang": "EN-US"
    }
  }, [_vm._v("7.1 ")]), _c('span', {
    staticStyle: {
      "font-size": ".65rem",
      "mso-bidi-font-family": "宋体",
      "mso-font-kerning": "0pt"
    }
  }, [_vm._v("用户明确同意其使用本站网络服务所存在的风险及一切后果将完全由用户本人承担，狗运网对此不承担任何责任。\n"), _c('span', {
    attrs: {
      "lang": "EN-US"
    }
  })])]), _vm._v(" "), _c('p', {
    staticClass: "MsoNormal",
    staticStyle: {
      "text-align": "left",
      "mso-pagination": "widow-orphan"
    },
    attrs: {
      "align": "left"
    }
  }, [_c('span', {
    staticStyle: {
      "font-size": ".65rem",
      "font-family": "宋体",
      "mso-bidi-font-family": "宋体",
      "mso-font-kerning": "0pt"
    },
    attrs: {
      "lang": "EN-US"
    }
  }, [_vm._v("7.2 ")]), _c('span', {
    staticStyle: {
      "font-size": ".65rem",
      "mso-bidi-font-family": "宋体",
      "mso-font-kerning": "0pt"
    }
  }, [_vm._v("本站无法保证网络服务一定能满足用户的要求，也不保证网络服务的及时性、安全性、准确性。"), _c('span', {
    attrs: {
      "lang": "EN-US"
    }
  })])]), _vm._v(" "), _c('p', {
    staticClass: "MsoNormal",
    staticStyle: {
      "text-align": "left",
      "mso-pagination": "widow-orphan"
    },
    attrs: {
      "align": "left"
    }
  }, [_c('span', {
    staticStyle: {
      "font-size": ".65rem",
      "font-family": "宋体",
      "mso-bidi-font-family": "宋体",
      "mso-font-kerning": "0pt"
    },
    attrs: {
      "lang": "EN-US"
    }
  }, [_vm._v("7.3 ")]), _c('span', {
    staticStyle: {
      "font-size": ".65rem",
      "mso-bidi-font-family": "宋体",
      "mso-font-kerning": "0pt"
    }
  }, [_vm._v("本站不保证为方便用户而设置的外部链接的准确性和完整性，同时，对于该等外部链接指向的不由本站实际控制的任何网页上的内容，本站不承担任何责任。"), _c('span', {
    attrs: {
      "lang": "EN-US"
    }
  })])]), _vm._v(" "), _c('p', {
    staticClass: "MsoNormal",
    staticStyle: {
      "text-align": "left",
      "mso-pagination": "widow-orphan"
    },
    attrs: {
      "align": "left"
    }
  }, [_c('span', {
    staticStyle: {
      "font-size": ".65rem",
      "font-family": "宋体",
      "mso-bidi-font-family": "宋体",
      "mso-font-kerning": "0pt"
    },
    attrs: {
      "lang": "EN-US"
    }
  }, [_vm._v("7.4 ")]), _c('span', {
    staticStyle: {
      "font-size": ".65rem",
      "mso-bidi-font-family": "宋体",
      "mso-font-kerning": "0pt"
    }
  }, [_vm._v("对于因不可抗力或本站不能控制的原因造成的网络服务中断或其它缺陷，本站不承担任何责任，但将尽力减少因此而给用户造成的损失和影响。"), _c('span', {
    attrs: {
      "lang": "EN-US"
    }
  })])]), _vm._v(" "), _c('p', {
    staticClass: "MsoNormal",
    staticStyle: {
      "text-align": "left",
      "mso-pagination": "widow-orphan"
    },
    attrs: {
      "align": "left"
    }
  }, [_c('span', {
    staticStyle: {
      "font-size": ".65rem",
      "font-family": "宋体",
      "mso-bidi-font-family": "宋体",
      "mso-font-kerning": "0pt"
    },
    attrs: {
      "lang": "EN-US"
    }
  }, [_vm._v("7.5 ")]), _c('span', {
    staticStyle: {
      "font-size": ".65rem",
      "mso-bidi-font-family": "宋体",
      "mso-font-kerning": "0pt"
    }
  }, [_vm._v("对于站向用户提供的下列产品或者服务的质量缺陷本身及其引发的任何损失，本站无需承担任何责任：\n"), _c('span', {
    attrs: {
      "lang": "EN-US"
    }
  })])]), _vm._v(" "), _c('p', {
    staticClass: "MsoNormal",
    staticStyle: {
      "text-align": "left",
      "mso-pagination": "widow-orphan"
    },
    attrs: {
      "align": "left"
    }
  }, [_c('span', {
    staticStyle: {
      "font-size": ".65rem",
      "font-family": "宋体",
      "mso-bidi-font-family": "宋体",
      "mso-font-kerning": "0pt"
    },
    attrs: {
      "lang": "EN-US"
    }
  }, [_vm._v("(1)")]), _c('span', {
    staticStyle: {
      "font-size": ".65rem",
      "font-family": "宋体",
      "mso-bidi-font-family": "宋体",
      "mso-font-kerning": "0pt"
    }
  }, [_vm._v("本站向用户免费提供的各项网络服务；"), _c('span', {
    attrs: {
      "lang": "EN-US"
    }
  }, [_vm._v("(2)")]), _vm._v("本站向用户赠送的任何产品或者服务。"), _c('span', {
    attrs: {
      "lang": "EN-US"
    }
  }, [_vm._v(" 7.6 ")]), _vm._v("本站有权于任何时间暂时或永久修改或终止本服务"), _c('span', {
    attrs: {
      "lang": "EN-US"
    }
  }, [_vm._v("(")]), _vm._v("或其任何部分"), _c('span', {
    attrs: {
      "lang": "EN-US"
    }
  }, [_vm._v(")")]), _vm._v("，而无论其通知与否，本站对用户和任何第三人均无需承担任何责任。"), _c('span', {
    attrs: {
      "lang": "EN-US"
    }
  })])]), _vm._v(" "), _c('p', {
    staticClass: "MsoNormal",
    staticStyle: {
      "mso-margin-top-alt": "auto",
      "text-align": "left",
      "mso-pagination": "widow-orphan",
      "mso-outline-level": "4"
    },
    attrs: {
      "align": "left"
    }
  }, [_c('b', [_c('span', {
    staticStyle: {
      "font-size": ".65rem",
      "font-family": "宋体",
      "mso-bidi-font-family": "宋体"
    }
  }, [_vm._v("八、附则"), _c('span', {
    attrs: {
      "lang": "EN-US"
    }
  })])])]), _vm._v(" "), _c('p', {
    staticClass: "MsoNormal"
  }, [_c('span', {
    staticStyle: {
      "font-size": ".65rem",
      "font-family": "宋体",
      "mso-bidi-font-family": "宋体",
      "mso-font-kerning": "0pt"
    },
    attrs: {
      "lang": "EN-US"
    }
  }, [_vm._v("8.1 ")]), _c('span', {
    staticStyle: {
      "font-size": ".65rem",
      "font-family": "宋体",
      "mso-bidi-font-family": "宋体"
    }
  }, [_vm._v("本协议的订立、执行和解释及争议的解决均应适用中华人民共和国法律。 "), _c('span', {
    attrs: {
      "lang": "EN-US"
    }
  })])]), _vm._v(" "), _c('p', {
    staticClass: "MsoNormal"
  }, [_c('span', {
    staticStyle: {
      "font-size": ".65rem",
      "font-family": "宋体",
      "mso-bidi-font-family": "宋体",
      "mso-font-kerning": "0pt"
    },
    attrs: {
      "lang": "EN-US"
    }
  }, [_vm._v("8.2 ")]), _c('span', {
    staticStyle: {
      "font-size": ".65rem",
      "font-family": "宋体",
      "mso-bidi-font-family": "宋体"
    }
  }, [_vm._v("如本协议中的任何条款无论因何种原因完全或部分无效或不具有执行力，本协议的其余条款仍应有效并且有约束力。"), _c('span', {
    attrs: {
      "lang": "EN-US"
    }
  })])]), _vm._v(" "), _c('p', {
    staticClass: "MsoNormal"
  }, [_c('span', {
    staticStyle: {
      "font-size": ".65rem",
      "font-family": "宋体",
      "mso-bidi-font-family": "宋体",
      "mso-font-kerning": "0pt"
    },
    attrs: {
      "lang": "EN-US"
    }
  }, [_vm._v("8.3 ")]), _c('span', {
    staticStyle: {
      "font-size": ".65rem",
      "font-family": "宋体",
      "mso-bidi-font-family": "宋体"
    }
  }, [_vm._v("本协议解释权及修订权归广州康园科技有限公司所有。")])])])
}]}
if (false) {
  module.hot.accept()
  if (module.hot.data) {
     require("vue-hot-reload-api").rerender("data-v-71516fec", module.exports)
  }
}

/***/ }),

/***/ 429:
/***/ (function(module, exports, __webpack_require__) {

module.exports={render:function (){var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
  return _c('div', {
    staticClass: "page",
    attrs: {
      "id": "pageRegister"
    }
  }, [_c('header', {
    staticClass: "bar bar-nav"
  }, [_c('a', {
    staticClass: "button button-link button-nav pull-left",
    on: {
      "click": function($event) {
        _vm.$router.go(-1)
      }
    }
  }, [_c('span', {
    staticClass: "icon icon-left"
  }), _vm._v("\n            返回\n        ")]), _vm._v(" "), _c('h1', {
    staticClass: "title"
  }, [_vm._v("用户注册")])]), _vm._v(" "), _c('div', {
    staticClass: "content"
  }, [_vm._m(0), _vm._v(" "), _c('div', {
    staticClass: "list-block",
    staticStyle: {
      "margin": ".5rem 0rem"
    }
  }, [_c('ul', [_c('li', [_c('div', {
    staticClass: "item-content"
  }, [_c('div', {
    staticClass: "item-inner"
  }, [_c('div', {
    staticClass: "item-title label"
  }, [_vm._v("手机号码")]), _vm._v(" "), _c('div', {
    staticClass: "item-input"
  }, [_c('input', {
    directives: [{
      name: "model",
      rawName: "v-model",
      value: (_vm.mobile),
      expression: "mobile"
    }],
    attrs: {
      "type": "text",
      "placeholder": "请输入手机号码"
    },
    domProps: {
      "value": (_vm.mobile)
    },
    on: {
      "input": function($event) {
        if ($event.target.composing) { return; }
        _vm.mobile = $event.target.value
      }
    }
  })])])])]), _vm._v(" "), _c('li', [_c('div', {
    staticClass: "item-content"
  }, [_c('div', {
    staticClass: "item-inner"
  }, [_c('div', {
    staticClass: "item-title label"
  }, [_vm._v("昵称")]), _vm._v(" "), _c('div', {
    staticClass: "item-input"
  }, [_c('input', {
    directives: [{
      name: "model",
      rawName: "v-model",
      value: (_vm.nickname),
      expression: "nickname"
    }],
    attrs: {
      "type": "text",
      "placeholder": "请输入昵称"
    },
    domProps: {
      "value": (_vm.nickname)
    },
    on: {
      "input": function($event) {
        if ($event.target.composing) { return; }
        _vm.nickname = $event.target.value
      }
    }
  })])])])]), _vm._v(" "), _c('li', [_c('div', {
    staticClass: "item-content"
  }, [_c('div', {
    staticClass: "item-inner"
  }, [_c('div', {
    staticClass: "item-title label"
  }, [_vm._v("验证码")]), _vm._v(" "), _c('div', {
    staticClass: "item-input"
  }, [_c('input', {
    directives: [{
      name: "model",
      rawName: "v-model",
      value: (_vm.verify),
      expression: "verify"
    }],
    attrs: {
      "type": "text",
      "placeholder": "请输入验证码"
    },
    domProps: {
      "value": (_vm.verify)
    },
    on: {
      "input": function($event) {
        if ($event.target.composing) { return; }
        _vm.verify = $event.target.value
      }
    }
  })]), _vm._v(" "), _c('span', {
    class: {
      'color-gray': _vm.sending > 0
    },
    staticStyle: {
      "width": "60%"
    },
    on: {
      "click": _vm.sendVerify
    }
  }, [_vm._v(_vm._s(_vm.btnVerify))])])])]), _vm._v(" "), _c('li', [_c('div', {
    staticClass: "item-content"
  }, [_c('div', {
    staticClass: "item-inner"
  }, [_c('div', {
    staticClass: "item-title label"
  }, [_vm._v("密码")]), _vm._v(" "), _c('div', {
    staticClass: "item-input"
  }, [_c('input', {
    directives: [{
      name: "model",
      rawName: "v-model",
      value: (_vm.password),
      expression: "password"
    }],
    attrs: {
      "type": "password",
      "placeholder": "请输入密码"
    },
    domProps: {
      "value": (_vm.password)
    },
    on: {
      "input": function($event) {
        if ($event.target.composing) { return; }
        _vm.password = $event.target.value
      }
    }
  })])])])]), _vm._v(" "), _c('li', [_c('div', {
    staticClass: "item-content"
  }, [_c('div', {
    staticClass: "item-inner"
  }, [_c('div', {
    staticClass: "item-title label"
  }, [_vm._v("确认密码")]), _vm._v(" "), _c('div', {
    staticClass: "item-input"
  }, [_c('input', {
    directives: [{
      name: "model",
      rawName: "v-model",
      value: (_vm.confirmPassword),
      expression: "confirmPassword"
    }],
    attrs: {
      "type": "password",
      "placeholder": "请再次输入密码"
    },
    domProps: {
      "value": (_vm.confirmPassword)
    },
    on: {
      "input": function($event) {
        if ($event.target.composing) { return; }
        _vm.confirmPassword = $event.target.value
      }
    }
  })])])])]), _vm._v(" "), _c('li', [_c('div', {
    staticClass: "item-content"
  }, [_c('div', {
    staticClass: "item-inner"
  }, [_c('div', {
    staticClass: "item-title label"
  }, [_vm._v("推荐码")]), _vm._v(" "), _c('div', {
    staticClass: "item-input"
  }, [_c('input', {
    directives: [{
      name: "model",
      rawName: "v-model",
      value: (_vm.invite),
      expression: "invite"
    }],
    attrs: {
      "type": "text",
      "placeholder": "选填"
    },
    domProps: {
      "value": (_vm.invite)
    },
    on: {
      "input": function($event) {
        if ($event.target.composing) { return; }
        _vm.invite = $event.target.value
      }
    }
  })])])])]), _vm._v(" "), _c('li', [_c('div', {
    staticClass: "item-content"
  }, [_c('div', {
    staticClass: "item-inner"
  }, [_c('div', {
    staticClass: "item-input label"
  }, [_c('input', {
    directives: [{
      name: "model",
      rawName: "v-model",
      value: (_vm.agree),
      expression: "agree"
    }],
    attrs: {
      "type": "checkbox"
    },
    domProps: {
      "checked": Array.isArray(_vm.agree) ? _vm._i(_vm.agree, null) > -1 : (_vm.agree)
    },
    on: {
      "change": function($event) {
        var $$a = _vm.agree,
          $$el = $event.target,
          $$c = $$el.checked ? (true) : (false);
        if (Array.isArray($$a)) {
          var $$v = null,
            $$i = _vm._i($$a, $$v);
          if ($$el.checked) {
            $$i < 0 && (_vm.agree = $$a.concat([$$v]))
          } else {
            $$i > -1 && (_vm.agree = $$a.slice(0, $$i).concat($$a.slice($$i + 1)))
          }
        } else {
          _vm.agree = $$c
        }
      }
    }
  }), _c('a', {
    on: {
      "click": _vm.agreeInfo
    }
  }, [_vm._v("《注册协议》")])])])])])]), _vm._v(" "), _c('div', {
    staticClass: "content-block",
    staticStyle: {
      "margin": ".5rem 0rem"
    }
  }, [_c('div', {
    staticClass: "row"
  }, [_c('div', {
    staticClass: "col-100"
  }, [_c('span', {
    staticClass: "button button-big button-fill button-success",
    on: {
      "click": _vm.register
    }
  }, [_vm._v("注册")])])])])]), _vm._v(" "), _c('div', {
    staticClass: "content-block"
  }, [_c('p', [_vm._v("\n            \t已有号码？\n            \t"), _c('router-link', {
    staticClass: "color-danger",
    attrs: {
      "to": {
        name: 'login'
      }
    }
  }, [_vm._v("点击登录")]), _vm._v(" \n            \t, 忘记密码？\n            \t"), _c('router-link', {
    staticClass: "color-danger",
    attrs: {
      "to": {
        name: 'findpwd'
      }
    }
  }, [_vm._v("点击找回")])], 1)])]), _vm._v(" "), _c('div', {
    staticClass: "popup popup-about",
    class: {
      'modal-in': _vm.showAgree, 'show': _vm.showAgree
    }
  }, [_c('header', {
    staticClass: "bar bar-nav"
  }, [_c('a', {
    staticClass: "button button-link button-nav pull-left",
    on: {
      "click": _vm.agreeInfo
    }
  }, [_c('span', {
    staticClass: "icon icon-left"
  }), _vm._v("\n\t            关闭\n\t        ")]), _vm._v(" "), _c('h1', {
    staticClass: "title"
  }, [_vm._v("用户注册协议")])]), _vm._v(" "), _c('div', {
    staticClass: "content content-padded"
  }, [_c('agreement')], 1)])])
},staticRenderFns: [function (){var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
  return _c('p', {
    staticClass: "text-center",
    staticStyle: {
      "background": "#fff",
      "margin": "0rem",
      "padding": ".5rem"
    }
  }, [_c('img', {
    staticStyle: {
      "width": "4rem"
    },
    attrs: {
      "src": "/images/logo.png"
    }
  })])
}]}
if (false) {
  module.hot.accept()
  if (module.hot.data) {
     require("vue-hot-reload-api").rerender("data-v-cd5956f2", module.exports)
  }
}

/***/ }),

/***/ 470:
/***/ (function(module, exports, __webpack_require__) {

// style-loader: Adds some css to the DOM by adding a <style> tag

// load the styles
var content = __webpack_require__(378);
if(typeof content === 'string') content = [[module.i, content, '']];
// add the styles to the DOM
var update = __webpack_require__(246)(content, {});
if(content.locals) module.exports = content.locals;
// Hot Module Replacement
if(false) {
	// When the styles change, update the <style> tags
	if(!content.locals) {
		module.hot.accept("!!../../../../node_modules/css-loader/index.js!../../../../node_modules/vue-loader/lib/style-rewriter.js?id=data-v-cd5956f2!../../../../node_modules/vue-loader/lib/selector.js?type=styles&index=0!./register.vue", function() {
			var newContent = require("!!../../../../node_modules/css-loader/index.js!../../../../node_modules/vue-loader/lib/style-rewriter.js?id=data-v-cd5956f2!../../../../node_modules/vue-loader/lib/selector.js?type=styles&index=0!./register.vue");
			if(typeof newContent === 'string') newContent = [[module.id, newContent, '']];
			update(newContent);
		});
	}
	// When the module is disposed, remove the <style> tags
	module.hot.dispose(function() { update(); });
}

/***/ })

});