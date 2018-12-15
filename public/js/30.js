webpackJsonp([30],{

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

/***/ 325:
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
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
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
			defeat: '',
			alipay: '',
			desc: '',
			min: 0,
			max: 0,
			promise: 0,
			s_ok: false,
			game: {},
			user: {},
			inviter: {},
			invited: {}
		};
	},
	created: function created() {
		var that = this;
		console.log(that.route);
		__WEBPACK_IMPORTED_MODULE_0_axios___default.a.get('/bigsmall/detail/' + that.$route.params.id + '?_token=' + window._Token + '&api_token=' + window.apiToken).then(function (response) {
			console.log(that);
			that.promise = response.data.game.cash_deposit;
			that.game = response.data.game;
			that.user = response.data.user;
			that.inviter = response.data.game.user;
			that.invited = response.data.game.inviter;
			if (that.game.user_num && that.game.inviter_num) {
				//如果不等于0 则说明这个页面已经发生了变化则去到详情页
				//that.$router.push({'name': 'bigsmalldetail' , params: that.$route.params });
				console.log('log');
				if (that.game.user_id == that.user.id) {
					//发起者
					that.defeat = that.game.inviter.mobile;
					that.alipay = that.game.user.alipay_account;
				} else {
					that.defeat = that.game.user.mobile;
					that.alipay = that.game.inviter.alipay_account;
				}
			}
		});
	},
	methods: {
		back: function back() {
			this.$router.back();
		},
		getInviterAvatar: function getInviterAvatar() {
			return this.inviter['avatar'] ? '/' + this.inviter['avatar'] : '/images/logo.png';
		},
		getInvitedAvatar: function getInvitedAvatar() {
			return this.invited['avatar'] ? '/' + this.invited['avatar'] : '/images/logo.png';
		},
		getInvitedName: function getInvitedName() {
			return this.invited.nickname ? this.invited.nickname : '狗运';
		},
		getInviterName: function getInviterName() {
			return this.inviter.nickname ? this.inviter.nickname : '狗运';
		},
		inviterWin: function inviterWin() {
			return this.game.user_num && this.game.inviter_num && this.game.user_num > this.game.inviter_num;
		},
		invitedWin: function invitedWin() {
			return this.game.user_num && this.game.inviter_num && this.game.user_num < this.game.inviter_num;
		},
		is_inviter: function is_inviter() {
			return this.game.user_id == this.user.id;
		},
		user_num_img: function user_num_img() {
			return '/images/' + this.game.user_num + '.png';
		},
		inviter_num_img: function inviter_num_img() {
			return '/images/' + this.game.inviter_num + '.png';
		},
		win: function win() {
			if (this.game.user_id == this.user.id) {
				//发起者
				this.defeat = this.game.inviter.mobile;
				this.alipay = this.game.user.alipay_account;
				return this.game.user_num > this.game.inviter_num;
			} else {
				this.defeat = this.game.user.mobile;
				this.alipay = this.game.inviter.alipay_account;
				return this.game.user_num < this.game.inviter_num;
			}
		},
		lose: function lose() {
			if (this.game.user_id == this.user.id) {
				//发起者
				this.defeat = this.game.user.mobile;
				this.alipay = this.game.inviter.alipay_account;
				return this.game.user_num < this.game.inviter_num;
			} else {
				this.defeat = this.game.inviter.mobile;
				this.alipay = this.game.user.alipay_account;
				return this.game.user_num > this.game.inviter_num;
			}
		},
		waiting: function waiting() {
			if (this.game.status == 0) {
				return true;
			}
			return false;
		},
		reject: function reject() {
			if (this.game.status == 3) {
				return true;
			}
			return false;
		},
		gameover: function gameover() {
			if (this.game.status == 4) {
				return true;
			}
			return false;
		},
		hasresult: function hasresult() {
			console.log(this.game);
			return this.game.user_num && this.game.inviter_num;
		},
		cancelInvite: function cancelInvite() {
			var that = this;
			__WEBPACK_IMPORTED_MODULE_0_axios___default.a.put('/bigsmall/cancel/' + that.$route.params.id, {
				'_token': window._Token,
				'api_token': window.apiToken,
				'id': that.$route.params.id
			}, {
				responseType: 'json'
			}).then(function (ret) {
				console.log(ret);
				var data = ret.data;
				if (ret.data.errcode === 0) {
					//邀请成功 回到发送信息的页面
					that.game = data.game;
				}
			}, function (ret) {
				console.log(ret);
			});
		},
		accept: function accept() {
			var that = this;
			__WEBPACK_IMPORTED_MODULE_0_axios___default.a.put('/bigsmall/accept/' + that.$route.params.id, {
				'_token': window._Token,
				'api_token': window.apiToken,
				'id': that.$route.params.id
			}, {
				responseType: 'json'
			}).then(function (ret) {
				console.log(ret);
				var data = ret.data;
				if (ret.data.errcode === 0) {
					//邀请成功 回到发送信息的页面
					that.game = data.game;
					if (that.game.inviter_num > that.game.user_num) {
						that.$toast("恭喜您获得胜利！！！");
					} else {
						that.$toast("很遗憾您输掉了战斗！！！");
					}
					/**
     setTimeout( function(){
     	that.$router.go(-1);
     } , 1500 );
     **/
				} else if (ret.data.errcode == 10006) {
					that.$toast(data.msg);
					setTimeout(function () {
						that.$router.push({
							name: 'baseinfo'
						});
					}, 1500);
				}
			}, function (ret) {
				console.log(ret);
			});
		},
		refuse: function refuse() {
			var that = this;
			__WEBPACK_IMPORTED_MODULE_0_axios___default.a.put('/bigsmall/reject/' + that.$route.params.id, {
				'_token': window._Token,
				'api_token': window.apiToken,
				'id': that.$route.params.id
			}, {
				responseType: 'json'
			}).then(function (ret) {
				console.log(ret);
				var data = ret.data;
				that.$toast(data.msg);
				if (ret.data.errcode === 0) {
					//邀请成功 回到发送信息的页面
					that.game = data.game;
					/**
     setTimeout( function(){
     	that.$router.go(-1);
     } , 1500 );
     **/
				}
			}, function (ret) {
				console.log(ret);
			});
		},
		reback: function reback() {
			var that = this;
			__WEBPACK_IMPORTED_MODULE_0_axios___default.a.put('/bigsmall/reback/' + that.$route.params.id, {
				'_token': window._Token,
				'api_token': window.apiToken,
				'id': that.$route.params.id
			}, {
				responseType: 'json'
			}).then(function (ret) {
				console.log(ret);
				var data = ret.data;
				that.$toast(data.msg);
				if (ret.data.errcode === 0) {
					//邀请成功 回到发送信息的页面
					that.game = data.game;
					/**
     setTimeout( function(){
     	that.$router.go(-1);
     } , 1500 );
     **/
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

/***/ 355:
/***/ (function(module, exports, __webpack_require__) {

exports = module.exports = __webpack_require__(11)(false);
// imports


// module
exports.push([module.i, "\n.page[data-v-30392f65] {\n\tbackground: #fff;\n}\n.row-result[data-v-30392f65] {\n\toverflow: hidden;\n\tmargin-bottom: 1.5rem;\n\tpadding-top:2rem;\n}\n.col-left[data-v-30392f65],.col-right[data-v-30392f65] {\n\twidth: 45%;\n\ttext-align: center;\n\tbox-sizing: border-box;\n  float: left;\n}\n.col-left[data-v-30392f65] {\n\ttext-align: right;\n\tpadding-right:1rem;\n}\n.col-right[data-v-30392f65] {\n\ttext-align: left;\n\tpadding-left:1rem;\n}\n.col-left img[data-v-30392f65],.col-right img[data-v-30392f65] {\n\twidth: 3.5rem;\n\tborder-radius: .3rem;\n}\n.col-mid[data-v-30392f65] {\n\twidth: 10%;\n\tbox-sizing: border-box;\n    float: left;\n}\n.col-mid img[data-v-30392f65] {\n\twidth: 100%;\n\tmargin-top:1.5rem;\n}\n.col-puzzle[data-v-30392f65] {\n\twidth: 100%;\n\ttext-align: center;\n}\n.inviter[data-v-30392f65],.me[data-v-30392f65] {\n\twidth:3rem;\n\tmargin: 0 auto;\n\ttext-align: center;\n\tposition: relative;\n\tpadding-top:.5rem;\n}\n.inviter .crown [data-v-30392f65], .me .crown[data-v-30392f65] {\n\twidth: 1.5rem;\n    height: 1rem;\n    top: .2rem;\n    position: absolute;\n    left: .75rem;\n}\n.to-pk[data-v-30392f65] {\n    height: 1.3rem;\n    line-height: 1rem;\n    text-align: center;\n    border-bottom: #724c41 solid 1px;\n    color: #ffa500e0;\n    font-size: 1rem;\n    margin-top: 1.2rem;\n    width: 70%;\n    margin-left: auto;\n    margin-right: auto;\n}\n.to-money[data-v-30392f65] {\n    text-align: center;\n    height: 1.2rem;\n    line-height: 1.2rem;\n    font-size: .95rem;\n    color: red;\n}\n.avatar[data-v-30392f65] {\n\twidth: 100%;\n\tborder-radius: 50%;\n\tborder:solid 1px #dfdfdf;\n}\n.bottom-fixed[data-v-30392f65] {\n\tposition: absolute;\n    right: 0;\n    left: 0;\n    z-index: 10;\n    height: 2.2rem;\n    padding-right: 0.5rem;\n    padding-left: 0.5rem;\n    -webkit-backface-visibility: hidden;\n    backface-visibility: hidden;\n    bottom: 0;\n    width: 100%;\n    padding: 0;\n    table-layout: fixed;\n    background: #fff;\n}\n.next-button[data-v-30392f65] {\n\tborder-radius: 1.25rem;\n\ttext-decoration: none;\n    text-align: center;\n    display: block;\n    border-radius: 0.25rem;\n    line-height: 2.25rem;\n    box-sizing: border-box;\n    -webkit-appearance: none;\n    -moz-appearance: none;\n    -ms-appearance: none;\n    appearance: none;\n    background: none;\n    padding: 0 0.5rem;\n    margin: 0;\n    height: 2.2rem;\n    white-space: nowrap;\n    position: relative;\n    text-overflow: ellipsis;\n    font-size: 0.8rem;\n    font-family: inherit;\n    cursor: pointer;\n    color: #fff;\n\tbackground: #734d41;\n\tborder: none;\n}\n.info p[data-v-30392f65] {\n\ttext-align: center ;\n\tfont-size: .75rem;\n\tcolor:#41454a;\n}\n", ""]);

// exports


/***/ }),

/***/ 400:
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
  }), _vm._v("\n\t            帮助\n\t        ")])], 1), _vm._v(" "), (!_vm.is_inviter() && _vm.waiting()) ? _c('nav', {
    staticClass: "bar bar-tab"
  }, [_c('span', {
    staticClass: "tab-item",
    on: {
      "click": function($event) {
        _vm.refuse()
      }
    }
  }, [_c('span', {
    staticClass: "tab-label"
  }, [_vm._v("不接受")])]), _vm._v(" "), _c('span', {
    staticClass: "tab-item",
    on: {
      "click": function($event) {
        _vm.accept()
      }
    }
  }, [_c('span', {
    staticClass: "tab-label"
  }, [_vm._v("接受邀请")])])]) : _vm._e(), _vm._v(" "), (_vm.is_inviter() && _vm.waiting()) ? _c('nav', {
    staticClass: "bar bar-tab"
  }, [_c('span', {
    staticClass: "tab-item",
    on: {
      "click": function($event) {
        _vm.cancelInvite()
      }
    }
  }, [_c('span', {
    staticClass: "tab-label"
  }, [_vm._v("取消邀请")])])]) : _vm._e(), _vm._v(" "), (_vm.hasresult() && _vm.win() && _vm.game.status == 1) ? _c('nav', {
    staticClass: "bar bar-tab"
  }, [_c('span', {
    staticClass: "tab-item",
    on: {
      "click": function($event) {
        _vm.reback()
      }
    }
  }, [_c('span', {
    staticClass: "tab-label"
  }, [_vm._v("退还狗粮")])])]) : _vm._e(), _vm._v(" "), _c('div', {
    staticClass: "content"
  }, [(_vm.hasresult()) ? _c('div', {
    staticClass: "row-result"
  }, [_c('div', {
    staticClass: "col-left"
  }, [_c('img', {
    attrs: {
      "src": _vm.user_num_img()
    }
  })]), _vm._v(" "), _vm._m(0), _vm._v(" "), _c('div', {
    staticClass: "col-right"
  }, [_c('img', {
    attrs: {
      "src": _vm.inviter_num_img()
    }
  })])]) : _c('div', {
    staticClass: "row-result"
  }, [_vm._m(1)]), _vm._v(" "), _c('div', {
    staticClass: "row"
  }, [_c('div', {
    staticClass: "col-33"
  }, [_c('div', {
    staticClass: "inviter"
  }, [_c('img', {
    staticClass: "avatar inviter-img",
    attrs: {
      "src": _vm.getInviterAvatar()
    }
  }), _vm._v(" "), _c('a', [_vm._v(_vm._s(_vm.getInviterName()))]), _vm._v(" "), (_vm.inviterWin()) ? _c('span', {
    staticClass: "crown"
  }) : _vm._e()])]), _vm._v(" "), _c('div', {
    staticClass: "col-33"
  }, [_c('div', {
    staticClass: "to-pk"
  }, [_vm._v("\n\t        \t\t\tVS\n\t        \t\t")]), _vm._v(" "), _c('div', {
    staticClass: "to-money"
  }, [_vm._v("\n\t        \t\t\t" + _vm._s(_vm.promise) + "\n\t        \t\t")])]), _vm._v(" "), _c('div', {
    staticClass: "col-33"
  }, [_c('div', {
    staticClass: "me"
  }, [_c('img', {
    staticClass: "avatar me-img",
    attrs: {
      "src": _vm.getInvitedAvatar()
    }
  }), _vm._v(" "), _c('a', [_vm._v(_vm._s(_vm.getInvitedName()))]), _vm._v(" "), (_vm.invitedWin()) ? _c('span', {
    staticClass: "crown"
  }) : _vm._e()])])]), _vm._v(" "), (_vm.is_inviter()) ? _c('div', {
    staticClass: "content-block info"
  }, [(_vm.hasresult() && _vm.win() && _vm.game.status == 1) ? _c('p', [_vm._v("\n\t        \t\t恭喜您获得胜利！！！\n\t        \t\t"), _c('br'), _vm._v("去和他聊天联系退还狗粮吧。\n\t        \t\t"), _c('br'), _vm._v("对方电话是" + _vm._s(_vm.user.mobile) + "\n\t        \t")]) : _vm._e(), _vm._v(" "), (_vm.hasresult() && _vm.lose() && _vm.game.status == 1) ? _c('p', [_vm._v("\n\t        \t\t很遗憾您输掉了战斗。"), _c('br'), _vm._v("\n\t        \t\t对方支付宝:" + _vm._s(_vm.alipay) + "\n\t        \t")]) : _vm._e(), _vm._v(" "), (_vm.waiting()) ? _c('p', [_vm._v("\n\t        \t\t等待对方接受战斗。\n\t        \t")]) : _vm._e(), _vm._v(" "), (_vm.reject()) ? _c('p', [_vm._v("\n\t        \t\t对方拒绝了战斗。\n\t        \t")]) : _vm._e(), _vm._v(" "), (_vm.game.status == 2 && _vm.win()) ? _c('p', [_vm._v("\n\t        \t\t您退还了狗粮!\n\t        \t")]) : _vm._e(), _vm._v(" "), (_vm.game.status == 2 && _vm.lose()) ? _c('p', [_vm._v("\n\t        \t\t对方退还了狗粮!\n\t        \t")]) : _vm._e(), _vm._v(" "), (_vm.game.status == 4) ? _c('p', [_vm._v("\n\t        \t\t您撤销了战斗\n\t        \t")]) : _vm._e(), _vm._v(" "), (_vm.game.status == 6) ? _c('p', [_vm._v("\n\t        \t\t系统撤消战斗!\n\t        \t")]) : _vm._e(), _vm._v(" "), (_vm.game.status == 5 && _vm.win()) ? _c('p', [_vm._v("\n\t        \t\t系统退对方狗粮!\n\t        \t")]) : _vm._e(), _vm._v(" "), (_vm.game.status == 5 && _vm.lose()) ? _c('p', [_vm._v("\n\t        \t\t系统退我狗粮!\n\t        \t")]) : _vm._e()]) : _c('div', {
    staticClass: "content-block info"
  }, [(_vm.hasresult() && _vm.win() && _vm.game.status == 1) ? _c('p', [_vm._v("\n\t        \t\t恭喜您获得胜利！！！\n\t        \t\t"), _c('br'), _vm._v("去和他聊天联系退还狗粮吧。\n\t        \t\t"), _c('br'), _vm._v("对方电话是" + _vm._s(_vm.inviter.mobile) + "\n\t        \t")]) : _vm._e(), _vm._v(" "), (_vm.hasresult() && _vm.lose() && _vm.game.status == 1) ? _c('p', [_vm._v("\n\t        \t\t很遗憾您输掉了战斗。"), _c('br'), _vm._v("\n\t        \t\t对方支付宝:" + _vm._s(_vm.alipay) + "\n\t        \t")]) : _vm._e(), _vm._v(" "), (_vm.waiting()) ? _c('p', [_vm._v("\n\t        \t\t准备好接受战斗了吗？\n\t        \t")]) : _vm._e(), _vm._v(" "), (_vm.reject()) ? _c('p', [_vm._v("\n\t        \t\t您拒绝了战斗。\n\t        \t")]) : _vm._e(), _vm._v(" "), (_vm.game.status == 2 && _vm.win()) ? _c('p', [_vm._v("\n\t        \t\t您退还了狗粮!\n\t        \t")]) : _vm._e(), _vm._v(" "), (_vm.game.status == 2 && _vm.lose()) ? _c('p', [_vm._v("\n\t        \t\t对方退还了狗粮!\n\t        \t")]) : _vm._e(), _vm._v(" "), (_vm.game.status == 4) ? _c('p', [_vm._v("\n\t        \t\t对方撤销战斗\n\t        \t")]) : _vm._e(), _vm._v(" "), (_vm.game.status == 6) ? _c('p', [_vm._v("\n\t        \t\t系统撤消战斗!\n\t        \t")]) : _vm._e(), _vm._v(" "), (_vm.game.status == 5 && _vm.win()) ? _c('p', [_vm._v("\n\t        \t\t系统退对方狗粮!\n\t        \t")]) : _vm._e(), _vm._v(" "), (_vm.game.status == 5 && _vm.lose()) ? _c('p', [_vm._v("\n\t        \t\t系统退我狗粮!\n\t        \t")]) : _vm._e()]), _vm._v(" "), _c('div', {
    staticClass: "content-block"
  }, [_c('p', {
    domProps: {
      "innerHTML": _vm._s(_vm.desc)
    }
  })])])])
},staticRenderFns: [function (){var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
  return _c('div', {
    staticClass: "col-mid"
  }, [_c('img', {
    attrs: {
      "src": "/images/vs.png"
    }
  })])
},function (){var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
  return _c('div', {
    staticClass: "col-puzzle"
  }, [_c('img', {
    staticStyle: {
      "height": "6rem"
    },
    attrs: {
      "src": "/images/13.png"
    }
  })])
}]}
if (false) {
  module.hot.accept()
  if (module.hot.data) {
     require("vue-hot-reload-api").rerender("data-v-30392f65", module.exports)
  }
}

/***/ }),

/***/ 447:
/***/ (function(module, exports, __webpack_require__) {

// style-loader: Adds some css to the DOM by adding a <style> tag

// load the styles
var content = __webpack_require__(355);
if(typeof content === 'string') content = [[module.i, content, '']];
// add the styles to the DOM
var update = __webpack_require__(246)(content, {});
if(content.locals) module.exports = content.locals;
// Hot Module Replacement
if(false) {
	// When the styles change, update the <style> tags
	if(!content.locals) {
		module.hot.accept("!!../../../../node_modules/css-loader/index.js!../../../../node_modules/vue-loader/lib/style-rewriter.js?id=data-v-30392f65&scoped=true!../../../../node_modules/vue-loader/lib/selector.js?type=styles&index=0!./inviteBigSmallDetail.vue", function() {
			var newContent = require("!!../../../../node_modules/css-loader/index.js!../../../../node_modules/vue-loader/lib/style-rewriter.js?id=data-v-30392f65&scoped=true!../../../../node_modules/vue-loader/lib/selector.js?type=styles&index=0!./inviteBigSmallDetail.vue");
			if(typeof newContent === 'string') newContent = [[module.id, newContent, '']];
			update(newContent);
		});
	}
	// When the module is disposed, remove the <style> tags
	module.hot.dispose(function() { update(); });
}

/***/ }),

/***/ 91:
/***/ (function(module, exports, __webpack_require__) {

var __vue_exports__, __vue_options__
var __vue_styles__ = {}

/* styles */
__webpack_require__(447)

/* script */
__vue_exports__ = __webpack_require__(325)

/* template */
var __vue_template__ = __webpack_require__(400)
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
__vue_options__.__file = "/Users/wangfan/Work/webroot/adm/resources/assets/js/pages/inviteBigSmallDetail.vue"
__vue_options__.render = __vue_template__.render
__vue_options__.staticRenderFns = __vue_template__.staticRenderFns
__vue_options__._scopeId = "data-v-30392f65"

/* hot reload */
if (false) {(function () {
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), false)
  if (!hotAPI.compatible) return
  module.hot.accept()
  if (!module.hot.data) {
    hotAPI.createRecord("data-v-30392f65", __vue_options__)
  } else {
    hotAPI.reload("data-v-30392f65", __vue_options__)
  }
})()}
if (__vue_options__.functional) {console.error("[vue-loader] inviteBigSmallDetail.vue: functional components are not supported and should be defined in plain js files using render functions.")}

module.exports = __vue_exports__


/***/ })

});