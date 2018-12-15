webpackJsonp([9],{

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

/***/ 258:
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



/* harmony default export */ __webpack_exports__["default"] = ({
	props: ['to'],
	data: function data() {
		return {
			desc: '',
			min: 0,
			max: 0,
			promise: '',
			onLine: true,
			showModal: 'none'
		};
	},
	created: function created() {
		var that = this;
		__WEBPACK_IMPORTED_MODULE_0_axios___default.a.get('/bigsmall/desc', {
			responseType: 'json'
		}).then(function (response) {
			console.log(that);
			that.min = response.data.min;
			that.max = response.data.max;
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
			if (that.min > that.promise) {
				that.$toast("狗粮最小为" + that.min, 1500, {
					'top': '50%'
				});
				return false;
			}
			if (that.max < that.promise) {
				that.$toast("狗粮最大为" + that.max, 1500, {
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
				'invite_user': that.to
			}, {
				responseType: 'json'
			}).then(function (ret) {
				console.log(ret);
				that.$toast(ret.data.msg, 5500);
				if (ret.data.errcode === 0) {
					//邀请成功 回到发送信息的页面
					that.hideModal();
				} else if (ret.data.errcode == 10006) {
					setTimeout(function () {
						that.$router.push({
							name: 'member-baseinfo'
						});
					}, 2500);
				}
			}, function (ret) {
				that.$router.push({
					name: 'login'
				});
				console.log(ret);
			});
		},
		hideModal: function hideModal() {
			this.showModal = 'none';
			this.$emit('closeModal');
		}
	},
	mounted: function mounted() {
		console.log(this.$route);
	},
	watch: {
		to: function to() {

			var that = this;
			if (that.to > 0) {
				that.showModal = 'block';
				__WEBPACK_IMPORTED_MODULE_0_axios___default.a.get('/im/isonline?user_id=' + that.to + '&api_token=' + window.apiToken, {
					responseType: 'json'
				}).then(function (response) {
					var data = response.data;
					if (data.errcode === 0) {
						if ('online' == response.data.online) {
							that.onLine = true;
						}
					}
				}).catch(function (ret) {
					that.$router.push({
						name: 'login'
					});
				});
			}
		}
	}
});

/***/ }),

/***/ 260:
/***/ (function(module, exports, __webpack_require__) {

exports = module.exports = __webpack_require__(11)(false);
// imports


// module
exports.push([module.i, "\n.bottom-fixed[data-v-32f4ec92] {\n\tposition: absolute;\n    right: 0;\n    left: 0;\n    z-index: 10;\n    height: 2.2rem;\n    padding-right: 0.5rem;\n    padding-left: 0.5rem;\n    -webkit-backface-visibility: hidden;\n    backface-visibility: hidden;\n    bottom: 0;\n    width: 100%;\n    padding: 0;\n    table-layout: fixed;\n    background: #fff;\n}\n.next-button[data-v-32f4ec92] {\n\tborder-radius: 1.25rem;\n\ttext-decoration: none;\n    text-align: center;\n    display: block;\n    border-radius: 0.25rem;\n    line-height: 2.25rem;\n    box-sizing: border-box;\n    -webkit-appearance: none;\n    -moz-appearance: none;\n    -ms-appearance: none;\n    appearance: none;\n    background: none;\n    padding: 0 0.5rem;\n    margin: 0;\n    height: 2.2rem;\n    white-space: nowrap;\n    position: relative;\n    text-overflow: ellipsis;\n    font-size: 0.8rem;\n    font-family: inherit;\n    cursor: pointer;\n    color: #fff;\n\tbackground: #734d41;\n\tborder: none;\n}\n.tip[data-v-32f4ec92] {\n\tbackground: #ffdeab;\n    margin: .5rem;\n    border-radius: .2rem;\n    padding: .2rem 1rem;\n    color: red;\n    font-size: .9rem;\n    text-align: center;\n    font-weight: 400;\n    line-height: 1.8rem;\n}\n.tip p[data-v-32f4ec92] {\n\tmargin:0;\n}\n", ""]);

// exports


/***/ }),

/***/ 262:
/***/ (function(module, exports, __webpack_require__) {

var __vue_exports__, __vue_options__
var __vue_styles__ = {}

/* styles */
__webpack_require__(266)

/* script */
__vue_exports__ = __webpack_require__(258)

/* template */
var __vue_template__ = __webpack_require__(264)
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
__vue_options__.__file = "/Users/wangfan/Work/webroot/adm/resources/assets/js/components/bigsmall.vue"
__vue_options__.render = __vue_template__.render
__vue_options__.staticRenderFns = __vue_template__.staticRenderFns
__vue_options__._scopeId = "data-v-32f4ec92"

/* hot reload */
if (false) {(function () {
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), false)
  if (!hotAPI.compatible) return
  module.hot.accept()
  if (!module.hot.data) {
    hotAPI.createRecord("data-v-32f4ec92", __vue_options__)
  } else {
    hotAPI.reload("data-v-32f4ec92", __vue_options__)
  }
})()}
if (__vue_options__.functional) {console.error("[vue-loader] bigsmall.vue: functional components are not supported and should be defined in plain js files using render functions.")}

module.exports = __vue_exports__


/***/ }),

/***/ 264:
/***/ (function(module, exports, __webpack_require__) {

module.exports={render:function (){var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
  return _c('div', {
    staticClass: "modal-overlay modal-overlay-visible",
    style: ({
      display: _vm.showModal
    }),
    on: {
      "click": _vm.hideModal
    }
  }, [_c('div', {
    staticClass: "modal modal-in",
    staticStyle: {
      "display": "block",
      "top": "5rem",
      "background": "#fff"
    },
    on: {
      "click": function($event) {
        $event.stopPropagation();
        if ($event.target !== $event.currentTarget) { return null; }
      }
    }
  }, [(!_vm.onLine) ? _c('div', {
    staticClass: "content-block tip"
  }, [_vm._m(0)]) : _vm._e(), _vm._v(" "), _c('div', {
    staticClass: "content-block list-block"
  }, [_c('ul', [_c('li', [_c('div', {
    staticClass: "item-content"
  }, [_c('div', {
    staticClass: "item-inner"
  }, [_c('div', {
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
    staticStyle: {
      "text-align": "center"
    },
    attrs: {
      "type": "text",
      "placeholder": '狗粮数需在' + _vm.min + '到' + _vm.max + '之间'
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
},staticRenderFns: [function (){var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
  return _c('p', [_vm._v("\n\t\t\t\t\t当前用户不在线"), _c('br'), _vm._v("请过5秒再刷新\n\t\t\t    ")])
}]}
if (false) {
  module.hot.accept()
  if (module.hot.data) {
     require("vue-hot-reload-api").rerender("data-v-32f4ec92", module.exports)
  }
}

/***/ }),

/***/ 266:
/***/ (function(module, exports, __webpack_require__) {

// style-loader: Adds some css to the DOM by adding a <style> tag

// load the styles
var content = __webpack_require__(260);
if(typeof content === 'string') content = [[module.i, content, '']];
// add the styles to the DOM
var update = __webpack_require__(246)(content, {});
if(content.locals) module.exports = content.locals;
// Hot Module Replacement
if(false) {
	// When the styles change, update the <style> tags
	if(!content.locals) {
		module.hot.accept("!!../../../../node_modules/css-loader/index.js!../../../../node_modules/vue-loader/lib/style-rewriter.js?id=data-v-32f4ec92&scoped=true!../../../../node_modules/vue-loader/lib/selector.js?type=styles&index=0!./bigsmall.vue", function() {
			var newContent = require("!!../../../../node_modules/css-loader/index.js!../../../../node_modules/vue-loader/lib/style-rewriter.js?id=data-v-32f4ec92&scoped=true!../../../../node_modules/vue-loader/lib/selector.js?type=styles&index=0!./bigsmall.vue");
			if(typeof newContent === 'string') newContent = [[module.id, newContent, '']];
			update(newContent);
		});
	}
	// When the module is disposed, remove the <style> tags
	module.hot.dispose(function() { update(); });
}

/***/ }),

/***/ 318:
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
Object.defineProperty(__webpack_exports__, "__esModule", { value: true });
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0_axios__ = __webpack_require__(60);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0_axios___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_0_axios__);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1_vue__ = __webpack_require__(29);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1_vue___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_1_vue__);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_2_vue_lazyload__ = __webpack_require__(382);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_2_vue_lazyload___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_2_vue_lazyload__);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_3_vue_chat_scroll__ = __webpack_require__(381);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_3_vue_chat_scroll___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_3_vue_chat_scroll__);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_4__components_bigsmall__ = __webpack_require__(262);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_4__components_bigsmall___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_4__components_bigsmall__);
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//






__WEBPACK_IMPORTED_MODULE_1_vue___default.a.use(__WEBPACK_IMPORTED_MODULE_3_vue_chat_scroll___default.a);
__WEBPACK_IMPORTED_MODULE_1_vue___default.a.use(__WEBPACK_IMPORTED_MODULE_2_vue_lazyload___default.a);
/* harmony default export */ __webpack_exports__["default"] = ({
	data: function data() {
		return {
			showOverlay: false,
			channelShow: false,
			panelShow: false,
			message: '',
			messages: [],
			toUser: {},
			toUserName: '',
			toUserId: 0,
			fromUser: {},
			queueMsg: {},
			to: 0,
			chatboxHeight: 0,
			timer: null
		};
	},
	created: function created() {
		this.fetchData();
	},
	watch: {
		'$route': function $route() {
			this.fetchData();
		}
	},
	mounted: function mounted() {
		var that = this;
		window.conn.listen({
			onTextMessage: function onTextMessage(message) {
				//收到文本消息
				console.log(message);
				console.log('txt');
				message.type = 'txt';
				tipAudio.play();
				console.log(message);
				if (message.to == imUser) {
					that.messages.push(message);
				}
				console.log(message);
			},
			onCmdMessage: function onCmdMessage(message) {
				console.log(message);
				console.log('cmd');
				tipAudio.play();
				if (message.ext.action == 'invite_bigsmall') {
					var msg = {
						'data': message.action,
						'type': 'invite_bigsmall',
						'to': message.to,
						'from': message.from,
						'id': message.ext.event_id,
						'msg_id': message.ext.event_id
					};
				} else {
					var msg = {
						'data': message.ext.worth,
						'type': 'game',
						'to': message.to,
						'from': message.from,
						'id': message.id,
						'msg_id': message.ext.event_id
					};
				}

				console.log(msg);
				if (msg.to = imUser) {
					that.messages.push(msg);
				}
				//that.messages.push( message );
			},
			//当消息发送到服务器
			onReceivedMessage: function onReceivedMessage(message) {},
			onPictureMessage: function onPictureMessage(message) {
				tipAudio.play();
				console.log(message);
				message.type = 'image';
				if (message.to == imUser) {
					that.messages.push(message);
				}
			}

		});
		setInterval(function () {
			if (that.$refs.chatcontent) {
				if (that.chatboxHeight != that.$refs.chatbox.clientHeight) {
					that.chatboxHeight = that.$refs.chatbox.clientHeight;
					that.$refs.chatcontent.scrollTop = that.chatboxHeight;
				}
			}
		}, 1000);
	},
	methods: {
		scrollKeyBoard: function scrollKeyBoard() {
			var that = this;
			console.log('scroll');
			clearInterval(that.timer);
			var index = 0;
			that.timer = setInterval(function () {
				if (index > 8 && that.$refs.chatcontent) {
					console.log(that.$refs.chatcontent);
					that.$refs.chatcontent.scrollTop = 1000000;
					that.$refs.chatbox.scrollTop = 1000000;
					document.body.scrollTop = 100000000;
					clearInterval(that.timer);
				} else {
					clearInterval(that.timer);
				}
				index++;
			}, 50);
		},
		fetchData: function fetchData() {
			var that = this;
			that.messages = [];
			that.toUserId = parseInt(that.$route.params.user_id);
			if (that.toUserId == 0) {
				//return 
			}
			__WEBPACK_IMPORTED_MODULE_0_axios___default.a.get('/im/userinfo/' + this.$route.params.user_id + '?_token=' + window._Token + '&api_token=' + window.apiToken, {
				responseType: 'json'
			}).then(function (res) {
				that.toUser = res.data.data;
				that.toUserName = that.toUser.nickname || '狗运';
			});

			__WEBPACK_IMPORTED_MODULE_0_axios___default.a.get('/member' + '?_token=' + window._Token + '&api_token=' + window.apiToken).then(function (response) {
				//console.log( that );
				var data = response.data;
				console.log(data);
				//that.user = data.data.user 
				that.fromUser = data.data.user;
			});

			__WEBPACK_IMPORTED_MODULE_0_axios___default.a.get('/im/history/' + this.$route.params.user_id + '?_token=' + window._Token + '&api_token=' + window.apiToken, {
				responseType: 'json'
			}).then(function (res) {
				if (res.data.data) {
					var list = res.data.data.data;
					for (var i = list.length - 1; i >= 0; i--) {
						that.messages.push({
							'data': list[i].body.data,
							'url': list[i].body.url,
							'type': list[i].type,
							'to': list[i].receiver ? list[i].receiver.name : '',
							'from': list[i].sender ? list[i].sender.name : '系统消息',
							'id': list[i].id,
							'msg_id': list[i].msg_id
						});
					}
				}
			});
		},
		getName: function getName(item) {
			console.log(item);
			console.log(this.toUser);
			return item.to == this.toUser.name ? this.toUser.nickname : this.fromUser.nickname;
		},
		avatar: function avatar(v) {
			return v ? v : '/images/logo.png';
		},
		chatlog: function chatlog(user_id) {},
		openPanel: function openPanel() {
			this.panelShow = !this.panelShow;
		},
		sendMsg: function sendMsg() {
			var that = this;
			if (!this.message) {
				return false;
			}
			var id = window.conn.getUniqueId(); // 生成本地消息id
			var msg = new WebIM.message('txt', id); // 创建文本消息
			msg.set({
				msg: that.message, // 消息内容
				to: that.toUser.name, // 接收消息对象（用户id）
				roomType: false,
				success: function success(id, serverMsgId) {
					var message = {
						'data': msg.value,
						'type': 'txt',
						'to': that.toUser.name,
						'from': window.imUser,
						'id': serverMsgId
					};
					that.messages.push(message);
					//同时保存到服务顺
					__WEBPACK_IMPORTED_MODULE_0_axios___default.a.post('/im/record', {
						'_token': window._Token,
						'api_token': window.apiToken,
						'data': message
					}, {
						responseType: 'json'
					}).then(function (res) {
						console.log(res);
					});
				},
				fail: function fail(e) {
					console.log("Send private text error");
				}
			});
			msg.body.chatType = 'singleChat';
			console.log(msg);
			window.conn.send(msg.body);
			this.message = '';
		},
		sendPrivateImg: function sendPrivateImg() {
			console.log(conn);
			var that = this;
			var id = conn.getUniqueId(); // 生成本地消息id
			var msg = new WebIM.message('img', id); // 创建图片消息
			var input = document.getElementById('image'); // 选择图片的input
			var file = WebIM.utils.getFileUrl(input); // 将图片转化为二进制文件
			that.panelShow = false;
			var allowType = {
				'jpg': true,
				'gif': true,
				'png': true,
				'bmp': true
			};
			if (file.filetype.toLowerCase() in allowType) {
				var option = {
					apiUrl: WebIM.config.apiURL,
					file: file,
					to: that.toUser.name, // 接收消息对象（用户id）
					roomType: false,
					chatType: 'singleChat',
					onFileUploadError: function onFileUploadError() {
						// 消息上传失败
						that.$toast('图片发送失败');
						document.getElementById('image').value = "";
					},
					onFileUploadComplete: function onFileUploadComplete(msg) {
						// 消息上传成功
						var res = msg.entities;
						for (var i in res) {
							var message = {
								'url': msg.uri + '/' + res[i].uuid,
								'type': 'image',
								'to': that.toUser.name,
								'from': window.imUser,
								'id': id
							};
							that.messages.push(message);

							__WEBPACK_IMPORTED_MODULE_0_axios___default.a.post('/im/record', {
								'_token': window._Token,
								'api_token': window.apiToken,
								'data': message
							}, {
								responseType: 'json'
							}).then(function (res) {
								console.log(res);
							}).catch(function (e) {

								console.log(e);
							});
						}
					},
					success: function success(msg) {
						// 消息发送成功
						console.log(msg);
					},
					flashUpload: WebIM.flashUpload
				};
				msg.set(option);
				window.conn.send(msg.body);
			}
		},
		bigOrSmall: function bigOrSmall() {
			var that = this;
			this.panelShow = !this.panelShow;
			that.to = this.$route.params.user_id;
			that.showBigSmall = 'block';
		},
		closeModal: function closeModal() {
			this.to = 0;
		},
		stolen: function stolen() {
			var that = this;
			this.panelShow = !this.panelShow;
			//发送偷钱的请求
			__WEBPACK_IMPORTED_MODULE_0_axios___default.a.put('/stolen/stolen/' + that.$route.params.user_id, {
				'_token': window._Token,
				'api_token': window.apiToken,
				'id': that.$route.params.user_id
			}, {
				responseType: 'json'
			}).then(function (ret) {
				console.log(ret);
				that.$toast(ret.data.msg);
				if (ret.data.errcode === 0) {
					//偷取成功  嘲讽一下
					console.log(ret.data);
					var id = window.conn.getUniqueId(); // 生成本地消息id
					var msg = new WebIM.message('txt', id); // 创建文本消息
					msg.set({
						msg: that.message, // 消息内容
						to: that.toUser.name, // 接收消息对象（用户id）
						roomType: false,
						success: function success(id, serverMsgId) {
							var message = {
								'data': "我偷取了你" + ret.data.data + "点狗粮，快来打我吖!!",
								'type': 'txt',
								'to': that.toUser.name,
								'from': window.imUser,
								'id': serverMsgId
							};
							that.messages.push(message);
							//同时保存到服务顺
							__WEBPACK_IMPORTED_MODULE_0_axios___default.a.post('/im/record', {
								'_token': window._Token,
								'api_token': window.apiToken,
								'data': message
							}, {
								responseType: 'json'
							}).then(function (res) {
								console.log(res);
							});
						},
						fail: function fail(e) {
							console.log("Send private text error");
						}
					});
					msg.body.chatType = 'singleChat';
					console.log(msg);
					window.conn.send(msg.body);
				}
			}, function (ret) {
				console.log(ret);
			});
		},
		agree: function agree(id) {
			var that = this;
			//发送偷钱的请求
			that.$confirm("您确定要加他为好友吗？").then(function () {
				__WEBPACK_IMPORTED_MODULE_0_axios___default.a.post('/im/accpet', {
					'_token': window._Token,
					'api_token': window.apiToken,
					'id': id
				}, {
					responseType: 'json'
				}).then(function (ret) {
					var data = ret.data;
					if (data.errcode === 0) {
						that.$router.push({
							name: 'im-chat',
							params: {
								user_id: data.user_id
							}
						});
					} else {
						that.$toast(data.msg);
					}
				});
			}).catch();
		},
		disagree: function disagree(id) {
			var that = this;
			//发送偷钱的请求
			__WEBPACK_IMPORTED_MODULE_0_axios___default.a.post('/im/disaccpet', {
				'_token': window._Token,
				'api_token': window.apiToken,
				'id': id
			}, {
				responseType: 'json'
			}).then(function (ret) {
				var data = ret.data;
				that.$toast(data.msg);
				if (data.errcode === 0) {
					for (var i in that.messages) {
						if (that.messages[i].msg_id == id) {
							that.messages.splice(i, 1);
							break;
						}
					}
					setTimeout(function () {
						that.$router.go(-1);
					}, 1500);
				}
			});
		}

	},
	components: {
		'bigsmall-modal': __WEBPACK_IMPORTED_MODULE_4__components_bigsmall___default.a
	}
});

/***/ }),

/***/ 365:
/***/ (function(module, exports, __webpack_require__) {

exports = module.exports = __webpack_require__(11)(false);
// imports


// module
exports.push([module.i, "\n.img-area[data-v-6127702f] {\n\twidth: 3rem;\n\theight:3rem;\n    overflow: hidden;\n    position: relative;\n    display:block;\n    margin-bottom: .2rem;\n}\n.img-input-file[data-v-6127702f] {\n\tposition: absolute;\n    left: 0px;\n    top: 0px;\n    z-index: 6;\n    opacity: 0;\n    height: 100%;\n    width: 100%;\n}\n.img-area-icon[data-v-6127702f] {\n\tposition: absolute;\n    width: 100%;\n    z-index: 2;\n}\n.pin-game[data-v-6127702f] {\n\tposition: absolute;\n\tleft :.5rem;\n\tbottom: 4rem;\n\ttext-align: center;\n    z-index: 999;\n}\n.card[data-v-6127702f] {\n\twidth: 10rem;\n}\n.card .card-header[data-v-6127702f], .card .card-footer[data-v-6127702f] {\n\tpadding: .2rem .7rem;\n\tmin-height: 1.5rem;\n}\n.card .card-header[data-v-6127702f] {\n\tfont-size: .65rem;\n\tfont-weight: bold;\n\tcolor:rgb(114, 76, 65);\n}\n.card .card-content-inner[data-v-6127702f] {\n\tcolor:#191919;\n\tpadding: .2rem .7rem;\n\tfont-size: .7rem;\n}\n.card .card-content-inner img[data-v-6127702f] {\n\tmargin: 0 auto;\n\twidth: 4rem;\n\tdisplay: block;\n\tfloat: none;\n}\n.card .card-footer[data-v-6127702f]:before {\n\tz-index:16;\n}\n", ""]);

// exports


/***/ }),

/***/ 381:
/***/ (function(module, exports, __webpack_require__) {

(function (global, factory) {
	 true ? module.exports = factory() :
	typeof define === 'function' && define.amd ? define(factory) :
	(global['vue-chat-scroll'] = factory());
}(this, (function () { 'use strict';

/**
 * @name VueJS vChatScroll (vue-chat-scroll)
 * @description Monitors an element and scrolls to the bottom if a new child is added
 * @author Theodore Messinezis <theo@theomessin.com>
 * @file v-chat-scroll  directive definition
 */

var scrollToBottom = function scrollToBottom(el) {
    el.scrollTop = el.scrollHeight;
};

var vChatScroll = {
    bind: function bind(el, binding) {
        var timeout = void 0;
        var scrolled = false;

        el.addEventListener('scroll', function (e) {
            if (timeout) window.clearTimeout(timeout);
            timeout = window.setTimeout(function () {
                scrolled = el.scrollTop + el.clientHeight + 1 < el.scrollHeight;
            }, 200);
        });

        new MutationObserver(function (e) {
            var config = binding.value || {};
            var pause = config.always === false && scrolled;
            if (pause || e[e.length - 1].addedNodes.length != 1) return;
            scrollToBottom(el);
        }).observe(el, { childList: true, subtree: true });
    },
    inserted: scrollToBottom
};

/**
 * @name VueJS vChatScroll (vue-chat-scroll)
 * @description Monitors an element and scrolls to the bottom if a new child is added
 * @author Theodore Messinezis <theo@theomessin.com>
 * @file vue-chat-scroll plugin definition
 */

var VueChatScroll = {
    install: function install(Vue, options) {
        Vue.directive('chat-scroll', vChatScroll);
    }
};

if (typeof window !== 'undefined' && window.Vue) {
    window.Vue.use(VueChatScroll);
}

return VueChatScroll;

})));


/***/ }),

/***/ 382:
/***/ (function(module, exports, __webpack_require__) {

/*!
 * Vue-Lazyload.js v1.1.4
 * (c) 2017 Awe <hilongjw@gmail.com>
 * Released under the MIT License.
 */
!function(e,t){ true?module.exports=t():"function"==typeof define&&define.amd?define(t):e.VueLazyload=t()}(this,function(){"use strict";function e(e,t){if(e.length){var n=e.indexOf(t);return n>-1?e.splice(n,1):void 0}}function t(e,t){if(!e||!t)return e||{};if(e instanceof Object)for(var n in t)e[n]=t[n];return e}function n(e,t){for(var n=!1,r=0,i=e.length;r<i;r++)if(t(e[r])){n=!0;break}return n}function r(e,t){if("IMG"===e.tagName&&e.getAttribute("data-srcset")){var n=e.getAttribute("data-srcset"),r=[],i=e.parentNode,o=i.offsetWidth*t,s=void 0,a=void 0,u=void 0;n=n.trim().split(","),n.map(function(e){e=e.trim(),s=e.lastIndexOf(" "),-1===s?(a=e,u=999998):(a=e.substr(0,s),u=parseInt(e.substr(s+1,e.length-s-2),10)),r.push([u,a])}),r.sort(function(e,t){if(e[0]<t[0])return-1;if(e[0]>t[0])return 1;if(e[0]===t[0]){if(-1!==t[1].indexOf(".webp",t[1].length-5))return 1;if(-1!==e[1].indexOf(".webp",e[1].length-5))return-1}return 0});for(var l="",d=void 0,c=r.length,h=0;h<c;h++)if(d=r[h],d[0]>=o){l=d[1];break}return l}}function i(e,t){for(var n=void 0,r=0,i=e.length;r<i;r++)if(t(e[r])){n=e[r];break}return n}function o(){if(!h)return!1;var e=!0,t=document;try{var n=t.createElement("object");n.type="image/webp",n.style.visibility="hidden",n.innerHTML="!",t.body.appendChild(n),e=!n.offsetWidth,t.body.removeChild(n)}catch(t){e=!1}return e}function s(e,t){var n=null,r=0;return function(){if(!n){var i=Date.now()-r,o=this,s=arguments,a=function(){r=Date.now(),n=!1,e.apply(o,s)};i>=t?a():n=setTimeout(a,t)}}}function a(e){return null!==e&&"object"===(void 0===e?"undefined":c(e))}function u(e){if(!(e instanceof Object))return[];if(Object.keys)return Object.keys(e);var t=[];for(var n in e)e.hasOwnProperty(n)&&t.push(n);return t}function l(e,t){if(!(e instanceof t))throw new TypeError("Cannot call a class as a function")}function d(e,t){if(!(e instanceof t))throw new TypeError("Cannot call a class as a function")}var c="function"==typeof Symbol&&"symbol"==typeof Symbol.iterator?function(e){return typeof e}:function(e){return e&&"function"==typeof Symbol&&e.constructor===Symbol&&e!==Symbol.prototype?"symbol":typeof e},h="undefined"!=typeof window,f=h&&"IntersectionObserver"in window,v={event:"event",observer:"observer"},p=function(){var e=arguments.length>0&&void 0!==arguments[0]?arguments[0]:1;return h&&window.devicePixelRatio||e},g=function(){if(h){var e=!1;try{var t=Object.defineProperty({},"passive",{get:function(){e=!0}});window.addEventListener("test",null,t)}catch(e){}return e}}(),y={on:function(e,t,n){var r=arguments.length>3&&void 0!==arguments[3]&&arguments[3];g?e.addEventListener(t,n,{capture:r,passive:!0}):e.addEventListener(t,n,r)},off:function(e,t,n){var r=arguments.length>3&&void 0!==arguments[3]&&arguments[3];e.removeEventListener(t,n,r)}},b=function(e,t,n){var r=new Image;r.src=e.src,r.onload=function(){t({naturalHeight:r.naturalHeight,naturalWidth:r.naturalWidth,src:r.src})},r.onerror=function(e){n(e)}},m=function(e,t){return"undefined"!=typeof getComputedStyle?getComputedStyle(e,null).getPropertyValue(t):e.style[t]},L=function(e){return m(e,"overflow")+m(e,"overflow-y")+m(e,"overflow-x")},w=function(e){if(h){if(!(e instanceof HTMLElement))return window;for(var t=e;t&&t!==document.body&&t!==document.documentElement&&t.parentNode;){if(/(scroll|auto)/.test(L(t)))return t;t=t.parentNode}return window}},_=function(){function e(e,t){for(var n=0;n<t.length;n++){var r=t[n];r.enumerable=r.enumerable||!1,r.configurable=!0,"value"in r&&(r.writable=!0),Object.defineProperty(e,r.key,r)}}return function(t,n,r){return n&&e(t.prototype,n),r&&e(t,r),t}}(),k={},E=function(){function e(t){var n=t.el,r=t.src,i=t.error,o=t.loading,s=t.bindType,a=t.$parent,u=t.options,d=t.elRenderer;l(this,e),this.el=n,this.src=r,this.error=i,this.loading=o,this.bindType=s,this.attempt=0,this.naturalHeight=0,this.naturalWidth=0,this.options=u,this.filter(),this.initState(),this.performanceData={init:Date.now(),loadStart:null,loadEnd:null},this.rect=n.getBoundingClientRect(),this.$parent=a,this.elRenderer=d,this.render("loading",!1)}return _(e,[{key:"initState",value:function(){this.state={error:!1,loaded:!1,rendered:!1}}},{key:"record",value:function(e){this.performanceData[e]=Date.now()}},{key:"update",value:function(e){var t=e.src,n=e.loading,r=e.error,i=this.src;this.src=t,this.loading=n,this.error=r,this.filter(),i!==this.src&&(this.attempt=0,this.initState())}},{key:"getRect",value:function(){this.rect=this.el.getBoundingClientRect()}},{key:"checkInView",value:function(){return this.getRect(),this.rect.top<window.innerHeight*this.options.preLoad&&this.rect.bottom>this.options.preLoadTop&&this.rect.left<window.innerWidth*this.options.preLoad&&this.rect.right>0}},{key:"filter",value:function(){var e=this;u(this.options.filter).map(function(t){e.options.filter[t](e,e.options)})}},{key:"renderLoading",value:function(e){var t=this;b({src:this.loading},function(n){t.render("loading",!1),e()},function(n){e(),t.options.silent||console.warn("VueLazyload log: load failed with loading image("+t.loading+")")})}},{key:"load",value:function(){var e=this;return this.attempt>this.options.attempt-1&&this.state.error?void(this.options.silent||console.log("VueLazyload log: "+this.src+" tried too more than "+this.options.attempt+" times")):this.state.loaded||k[this.src]?this.render("loaded",!0):void this.renderLoading(function(){e.attempt++,e.record("loadStart"),b({src:e.src},function(t){e.naturalHeight=t.naturalHeight,e.naturalWidth=t.naturalWidth,e.state.loaded=!0,e.state.error=!1,e.record("loadEnd"),e.render("loaded",!1),k[e.src]=1},function(t){e.state.error=!0,e.state.loaded=!1,e.render("error",!1)})})}},{key:"render",value:function(e,t){this.elRenderer(this,e,t)}},{key:"performance",value:function(){var e="loading",t=0;return this.state.loaded&&(e="loaded",t=(this.performanceData.loadEnd-this.performanceData.loadStart)/1e3),this.state.error&&(e="error"),{src:this.src,state:e,time:t}}},{key:"destroy",value:function(){this.el=null,this.src=null,this.error=null,this.loading=null,this.bindType=null,this.attempt=0}}]),e}(),T=function(){function e(e,t){for(var n=0;n<t.length;n++){var r=t[n];r.enumerable=r.enumerable||!1,r.configurable=!0,"value"in r&&(r.writable=!0),Object.defineProperty(e,r.key,r)}}return function(t,n,r){return n&&e(t.prototype,n),r&&e(t,r),t}}(),A="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7",$=["scroll","wheel","mousewheel","resize","animationend","transitionend","touchmove"],z={rootMargin:"0px",threshold:0},H=function(u){return function(){function l(e){var t=e.preLoad,n=e.error,r=e.throttleWait,i=e.preLoadTop,a=e.dispatchEvent,u=e.loading,c=e.attempt,h=e.silent,f=e.scale,g=e.listenEvents,y=(e.hasbind,e.filter),b=e.adapter,m=e.observer,L=e.observerOptions;d(this,l),this.version="1.1.4",this.mode=v.event,this.ListenerQueue=[],this.TargetIndex=0,this.TargetQueue=[],this.options={silent:h||!0,dispatchEvent:!!a,throttleWait:r||200,preLoad:t||1.3,preLoadTop:i||0,error:n||A,loading:u||A,attempt:c||3,scale:f||p(f),ListenEvents:g||$,hasbind:!1,supportWebp:o(),filter:y||{},adapter:b||{},observer:!!m,observerOptions:L||z},this._initEvent(),this.lazyLoadHandler=s(this._lazyLoadHandler.bind(this),this.options.throttleWait),this.setMode(this.options.observer?v.observer:v.event)}return T(l,[{key:"config",value:function(){var e=arguments.length>0&&void 0!==arguments[0]?arguments[0]:{};t(this.options,e)}},{key:"performance",value:function(){var e=[];return this.ListenerQueue.map(function(t){e.push(t.performance())}),e}},{key:"addLazyBox",value:function(e){this.ListenerQueue.push(e),h&&(this._addListenerTarget(window),this._observer&&this._observer.observe(e.el),e.$el&&e.$el.parentNode&&this._addListenerTarget(e.$el.parentNode))}},{key:"add",value:function(e,t,i){var o=this;if(n(this.ListenerQueue,function(t){return t.el===e}))return this.update(e,t),u.nextTick(this.lazyLoadHandler);var s=this._valueFormatter(t.value),a=s.src,l=s.loading,d=s.error;u.nextTick(function(){a=r(e,o.options.scale)||a,o._observer&&o._observer.observe(e);var n=Object.keys(t.modifiers)[0],s=void 0;n&&(s=i.context.$refs[n],s=s?s.$el||s:document.getElementById(n)),s||(s=w(e));var c=new E({bindType:t.arg,$parent:s,el:e,loading:l,error:d,src:a,elRenderer:o._elRenderer.bind(o),options:o.options});o.ListenerQueue.push(c),h&&(o._addListenerTarget(window),o._addListenerTarget(s)),o.lazyLoadHandler(),u.nextTick(function(){return o.lazyLoadHandler()})})}},{key:"update",value:function(e,t){var n=this,o=this._valueFormatter(t.value),s=o.src,a=o.loading,l=o.error;s=r(e,this.options.scale)||s;var d=i(this.ListenerQueue,function(t){return t.el===e});d&&d.update({src:s,loading:a,error:l}),this._observer&&this._observer.observe(e),this.lazyLoadHandler(),u.nextTick(function(){return n.lazyLoadHandler()})}},{key:"remove",value:function(t){if(t){this._observer&&this._observer.unobserve(t);var n=i(this.ListenerQueue,function(e){return e.el===t});n&&(this._removeListenerTarget(n.$parent),this._removeListenerTarget(window),e(this.ListenerQueue,n)&&n.destroy())}}},{key:"removeComponent",value:function(t){t&&(e(this.ListenerQueue,t),this._observer&&this._observer.unobserve(t.el),t.$parent&&t.$el.parentNode&&this._removeListenerTarget(t.$el.parentNode),this._removeListenerTarget(window))}},{key:"setMode",value:function(e){var t=this;f||e!==v.observer||(e=v.event),this.mode=e,e===v.event?(this._observer&&(this.ListenerQueue.forEach(function(e){t._observer.unobserve(e.el)}),this._observer=null),this.TargetQueue.forEach(function(e){t._initListen(e.el,!0)})):(this.TargetQueue.forEach(function(e){t._initListen(e.el,!1)}),this._initIntersectionObserver())}},{key:"_addListenerTarget",value:function(e){if(e){var t=i(this.TargetQueue,function(t){return t.el===e});return t?t.childrenCount++:(t={el:e,id:++this.TargetIndex,childrenCount:1,listened:!0},this.mode===v.event&&this._initListen(t.el,!0),this.TargetQueue.push(t)),this.TargetIndex}}},{key:"_removeListenerTarget",value:function(e){var t=this;this.TargetQueue.forEach(function(n,r){n.el===e&&(--n.childrenCount||(t._initListen(n.el,!1),t.TargetQueue.splice(r,1),n=null))})}},{key:"_initListen",value:function(e,t){var n=this;this.options.ListenEvents.forEach(function(r){return y[t?"on":"off"](e,r,n.lazyLoadHandler)})}},{key:"_initEvent",value:function(){var t=this;this.Event={listeners:{loading:[],loaded:[],error:[]}},this.$on=function(e,n){t.Event.listeners[e].push(n)},this.$once=function(e,n){function r(){i.$off(e,r),n.apply(i,arguments)}var i=t;t.$on(e,r)},this.$off=function(n,r){if(!r)return void(t.Event.listeners[n]=[]);e(t.Event.listeners[n],r)},this.$emit=function(e,n,r){t.Event.listeners[e].forEach(function(e){return e(n,r)})}}},{key:"_lazyLoadHandler",value:function(){var e=!1;this.ListenerQueue.forEach(function(t){t.state.loaded||(e=t.checkInView())&&t.load()})}},{key:"_initIntersectionObserver",value:function(){var e=this;f&&(this._observer=new IntersectionObserver(this._observerHandler.bind(this),this.options.observerOptions),this.ListenerQueue.length&&this.ListenerQueue.forEach(function(t){e._observer.observe(t.el)}))}},{key:"_observerHandler",value:function(e,t){var n=this;e.forEach(function(e){e.isIntersecting&&n.ListenerQueue.forEach(function(t){if(t.el===e.target){if(t.state.loaded)return n._observer.unobserve(t.el);t.load()}})})}},{key:"_elRenderer",value:function(e,t,n){if(e.el){var r=e.el,i=e.bindType,o=void 0;switch(t){case"loading":o=e.loading;break;case"error":o=e.error;break;default:o=e.src}if(i?r.style[i]="url("+o+")":r.getAttribute("src")!==o&&r.setAttribute("src",o),r.setAttribute("lazy",t),this.$emit(t,e,n),this.options.adapter[t]&&this.options.adapter[t](e,this.options),this.options.dispatchEvent){var s=new CustomEvent(t,{detail:e});r.dispatchEvent(s)}}}},{key:"_valueFormatter",value:function(e){var t=e,n=this.options.loading,r=this.options.error;return a(e)&&(e.src||this.options.silent||console.error("Vue Lazyload warning: miss src with "+e),t=e.src,n=e.loading||this.options.loading,r=e.error||this.options.error),{src:t,loading:n,error:r}}}]),l}()},O=function(e){return{props:{tag:{type:String,default:"div"}},render:function(e){return!1===this.show?e(this.tag):e(this.tag,null,this.$slots.default)},data:function(){return{el:null,state:{loaded:!1},rect:{},show:!1}},mounted:function(){this.el=this.$el,e.addLazyBox(this),e.lazyLoadHandler()},beforeDestroy:function(){e.removeComponent(this)},methods:{getRect:function(){this.rect=this.$el.getBoundingClientRect()},checkInView:function(){return this.getRect(),h&&this.rect.top<window.innerHeight*e.options.preLoad&&this.rect.bottom>0&&this.rect.left<window.innerWidth*e.options.preLoad&&this.rect.right>0},load:function(){this.show=!0,this.state.loaded=!0,this.$emit("show",this)}}}};return{install:function(e){var n=arguments.length>1&&void 0!==arguments[1]?arguments[1]:{},r=H(e),i=new r(n),o="2"===e.version.split(".")[0];e.prototype.$Lazyload=i,n.lazyComponent&&e.component("lazy-component",O(i)),o?e.directive("lazy",{bind:i.add.bind(i),update:i.update.bind(i),componentUpdated:i.lazyLoadHandler.bind(i),unbind:i.remove.bind(i)}):e.directive("lazy",{bind:i.lazyLoadHandler.bind(i),update:function(e,n){t(this.vm.$refs,this.vm.$els),i.add(this.el,{modifiers:this.modifiers||{},arg:this.arg,value:e,oldValue:n},{context:this.vm})},unbind:function(){i.remove(this.el)}})}}});


/***/ }),

/***/ 413:
/***/ (function(module, exports, __webpack_require__) {

module.exports={render:function (){var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
  return _c('div', {
    staticClass: "page"
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
  }), _vm._v("\n                返回\n            ")]), _vm._v(" "), _c('h1', {
    staticClass: "title"
  }, [_vm._v(_vm._s(_vm.toUserName))])]), _vm._v(" "), (_vm.toUserId) ? _c('nav', {
    staticClass: "bar bar-tab"
  }, [_c('div', {
    ref: "panel",
    staticClass: "feed-cont",
    class: [_vm.panelShow ? 'panel-show' : 'panel-hide']
  }, [_c('div', {
    staticClass: "flex"
  }, [_c('input', {
    directives: [{
      name: "model",
      rawName: "v-model",
      value: (_vm.message),
      expression: "message"
    }],
    staticClass: "feed-txt",
    attrs: {
      "autocomplete": "off",
      "type": "text",
      "value": "{message}"
    },
    domProps: {
      "value": (_vm.message)
    },
    on: {
      "keyup": function($event) {
        if (!('button' in $event) && $event.keyCode !== 13) { return null; }
        _vm.sendMsg($event)
      },
      "focus": _vm.scrollKeyBoard,
      "input": function($event) {
        if ($event.target.composing) { return; }
        _vm.message = $event.target.value
      }
    }
  }), _vm._v(" "), _c('div', {
    staticClass: "feed-btn",
    on: {
      "click": function($event) {
        _vm.sendMsg()
      }
    }
  }, [_vm._v("发送")]), _vm._v(" "), _c('div', {
    staticClass: "add-more",
    on: {
      "click": function($event) {
        _vm.openPanel()
      }
    }
  }, [_vm._v(" + ")])]), _vm._v(" "), _c('div', {
    staticClass: "more-menu flex"
  }, [_c('div', {
    staticClass: "more-item cursor",
    attrs: {
      "id": "chooseFileBtn"
    }
  }, [_c('span', {
    staticClass: "img-area"
  }, [_c('input', {
    staticClass: "img-input-file",
    attrs: {
      "type": "file",
      "id": "image"
    },
    on: {
      "change": _vm.sendPrivateImg
    }
  }), _vm._v(" "), _vm._m(0)]), _vm._v("\n\t\t\t\t\t\t图片\n\t\t\t\t\t")]), _vm._v(" "), _c('div', {
    staticClass: "more-item cursor",
    on: {
      "click": function($event) {
        _vm.bigOrSmall()
      }
    }
  }, [_vm._m(1), _vm._v("\n\t\t\t\t\t\t比大小\n\t\t\t\t\t")])]), _vm._v(" "), _vm._m(2)])]) : _vm._e(), _vm._v(" "), _c('div', {
    ref: "chatcontent",
    staticClass: "content"
  }, [_c('div', {
    ref: "chatbox",
    staticClass: "content-block chat-content",
    staticStyle: {
      "margin": "0rem",
      "padding": "0rem .25rem"
    }
  }, _vm._l((_vm.messages), function(item) {
    return _c('div', {
      staticClass: "item",
      class: {
        'item-me': _vm.toUserId > 0 && item.to != _vm.toUser.name, 'item-you': _vm.toUserId == 0 || item.to == _vm.toUser.name
      },
      attrs: {
        "data-id": item.id
      }
    }, [(_vm.toUserId > 0 && item.to == _vm.toUser.name) ? _c('img', {
      staticClass: "img j-img",
      attrs: {
        "src": _vm.avatar(_vm.fromUser ? _vm.fromUser.avatar : '')
      }
    }) : _c('img', {
      staticClass: "img j-img",
      attrs: {
        "src": _vm.avatar(_vm.toUser ? _vm.toUser.avatar : '')
      }
    }), _vm._v(" "), (item.type == 'txt') ? _c('div', {
      staticClass: "msg msg-text j-msg"
    }, [_c('div', {
      staticClass: "box"
    }, [_c('div', {
      staticClass: "cnt"
    }, [_c('div', {
      staticClass: "f-maxWid"
    }, [_vm._v(_vm._s(item.data))])])])]) : _vm._e(), _vm._v(" "), (item.type == 'addfriend') ? _c('div', {
      staticClass: "msg msg-text j-msg"
    }, [_c('div', {
      staticClass: "box"
    }, [_c('div', {
      staticClass: "cnt"
    }, [_c('div', {
      staticClass: "f-maxWid"
    }, [_vm._v(_vm._s(item.data))])]), _vm._v(" "), _c('div', {
      staticClass: "cnt"
    }, [_c('div', {
      staticClass: "f-maxWid",
      staticStyle: {
        "display": "flex",
        "font-size": ".75rem"
      }
    }, [_c('a', {
      staticClass: "button button-fill button-danger",
      on: {
        "click": function($event) {
          _vm.agree(item.msg_id)
        }
      }
    }, [_vm._v("同意")]), _vm._v("  \n\t\t\t\t\t\t\t\t\t"), _c('a', {
      staticClass: "button button-fill",
      on: {
        "click": function($event) {
          _vm.disagree(item.msg_id)
        }
      }
    }, [_vm._v("不同意")])])])])]) : _vm._e(), _vm._v(" "), (item.type == 'invite_bigsmall') ? _c('div', {
      staticClass: "msg msg-text j-msg"
    }, [(item.to != _vm.toUser.name) ? _c('div', {
      staticClass: "box"
    }, [_c('div', {
      staticClass: "card"
    }, [_c('div', {
      staticClass: "card-header"
    }, [_vm._v("应用消息：[比大小]")]), _vm._v(" "), _c('div', {
      staticClass: "card-content"
    }, [_c('div', {
      staticClass: "card-content-inner"
    }, [_vm._v("\n\t\t\t\t\t\t\t\t\t\t" + _vm._s(item.data) + "向您发起了比大小战斗\n\t\t\t\t\t\t\t\t\t")])]), _vm._v(" "), _c('div', {
      staticClass: "card-footer"
    }, [_c('router-link', {
      attrs: {
        "to": {
          name: 'bigsmall-detail',
          params: {
            id: item.msg_id
          }
        }
      }
    }, [_vm._v("查看详情")])], 1)])]) : _c('div', {
      staticClass: "box"
    }, [_c('div', {
      staticClass: "card"
    }, [_c('div', {
      staticClass: "card-header"
    }, [_vm._v("应用消息：[比大小]")]), _vm._v(" "), _c('div', {
      staticClass: "card-content"
    }, [_c('div', {
      staticClass: "card-content-inner"
    }, [_vm._v("\n\t\t\t\t\t\t\t\t\t\t您向" + _vm._s(_vm.getName(item)) + "发起了比大小战斗\n\t\t\t\t\t\t\t\t\t")])]), _vm._v(" "), _c('div', {
      staticClass: "card-footer"
    }, [_c('router-link', {
      attrs: {
        "to": {
          name: 'bigsmall-detail',
          params: {
            id: item.msg_id
          }
        }
      }
    }, [_vm._v("查看详情")])], 1)])])]) : _vm._e(), _vm._v(" "), (item.type == 'image') ? _c('div', {
      staticClass: "msg msg-text j-msg"
    }, [_c('div', {
      staticClass: "box"
    }, [_c('div', {
      staticClass: "cnt"
    }, [_c('a', {
      attrs: {
        "href": "javascript:void(0)"
      }
    }, [_c('img', {
      attrs: {
        "src": item.url
      }
    })])])])]) : _vm._e(), _vm._v(" "), _vm._m(3, true)])
  }))]), _vm._v(" "), _c('div', {
    staticClass: "modal-overlay",
    class: {
      'modal-overlay-visible': _vm.showOverlay
    }
  }), _vm._v(" "), _c('div', {
    staticClass: "actions-modal",
    class: {
      'modal-in': _vm.channelShow, 'modal-out': !_vm.channelShow,
    }
  }), _vm._v(" "), _c('bigsmall-modal', {
    attrs: {
      "to": _vm.to
    },
    on: {
      "closeModal": _vm.closeModal
    }
  })], 1)
},staticRenderFns: [function (){var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
  return _c('div', {
    staticClass: "item-ico img-area-icon"
  }, [_c('i', {
    staticClass: "iconfont icon-tupian"
  })])
},function (){var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
  return _c('div', {
    staticClass: "item-ico"
  }, [_c('i', {
    staticClass: "iconfont icon-tuxing"
  })])
},function (){var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
  return _c('div', {
    staticClass: "voice-layer"
  }, [_vm._v("\n\t\t\t\t\t按住说话\n\t\t\t\t\t"), _c('div', {
    staticClass: "v-close",
    attrs: {
      "onclick": "closeVoice()"
    }
  }, [_c('i', {
    staticClass: "icon iconfont icon-down-trangle"
  })]), _vm._v(" "), _c('div', {
    staticClass: "voice-ico cursor",
    attrs: {
      "id": "voiceBtn"
    }
  }, [_c('i', {
    staticClass: "icon iconfont icon-iconset0221"
  })])])
},function (){var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
  return _c('span', {
    staticClass: "readMsg"
  }, [_c('i'), _vm._v("已读")])
}]}
if (false) {
  module.hot.accept()
  if (module.hot.data) {
     require("vue-hot-reload-api").rerender("data-v-6127702f", module.exports)
  }
}

/***/ }),

/***/ 457:
/***/ (function(module, exports, __webpack_require__) {

// style-loader: Adds some css to the DOM by adding a <style> tag

// load the styles
var content = __webpack_require__(365);
if(typeof content === 'string') content = [[module.i, content, '']];
// add the styles to the DOM
var update = __webpack_require__(246)(content, {});
if(content.locals) module.exports = content.locals;
// Hot Module Replacement
if(false) {
	// When the styles change, update the <style> tags
	if(!content.locals) {
		module.hot.accept("!!../../../../../node_modules/css-loader/index.js!../../../../../node_modules/vue-loader/lib/style-rewriter.js?id=data-v-6127702f&scoped=true!../../../../../node_modules/vue-loader/lib/selector.js?type=styles&index=0!./chat.vue", function() {
			var newContent = require("!!../../../../../node_modules/css-loader/index.js!../../../../../node_modules/vue-loader/lib/style-rewriter.js?id=data-v-6127702f&scoped=true!../../../../../node_modules/vue-loader/lib/selector.js?type=styles&index=0!./chat.vue");
			if(typeof newContent === 'string') newContent = [[module.id, newContent, '']];
			update(newContent);
		});
	}
	// When the module is disposed, remove the <style> tags
	module.hot.dispose(function() { update(); });
}

/***/ }),

/***/ 84:
/***/ (function(module, exports, __webpack_require__) {

var __vue_exports__, __vue_options__
var __vue_styles__ = {}

/* styles */
__webpack_require__(457)

/* script */
__vue_exports__ = __webpack_require__(318)

/* template */
var __vue_template__ = __webpack_require__(413)
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
__vue_options__.__file = "/Users/wangfan/Work/webroot/adm/resources/assets/js/pages/im/chat.vue"
__vue_options__.render = __vue_template__.render
__vue_options__.staticRenderFns = __vue_template__.staticRenderFns
__vue_options__._scopeId = "data-v-6127702f"

/* hot reload */
if (false) {(function () {
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), false)
  if (!hotAPI.compatible) return
  module.hot.accept()
  if (!module.hot.data) {
    hotAPI.createRecord("data-v-6127702f", __vue_options__)
  } else {
    hotAPI.reload("data-v-6127702f", __vue_options__)
  }
})()}
if (__vue_options__.functional) {console.error("[vue-loader] chat.vue: functional components are not supported and should be defined in plain js files using render functions.")}

module.exports = __vue_exports__


/***/ })

});