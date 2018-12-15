webpackJsonp([41],{

/***/ 312:
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
Object.defineProperty(__webpack_exports__, "__esModule", { value: true });
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0_axios__ = __webpack_require__(60);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0_axios___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_0_axios__);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1__utils_js__ = __webpack_require__(61);
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
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
			btnVerify: '获取验证码',
			sending: 0,
			mobile: '',
			password: '',
			verify: '',
			confirmPassword: ''
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
			__WEBPACK_IMPORTED_MODULE_0_axios___default.a.post('/sendfindpwdsms', post).then(function (ret) {
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
		findpwd: function findpwd() {
			var that = this;
			if (!that.mobile) {
				that.$toast('请填写手机号码');
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

			var post = {
				'name': that.mobile,
				'password': that.password,
				'comfirmed': that.confirmPassword,
				'verify': that.verify,
				'_token': window._Token
			};
			__WEBPACK_IMPORTED_MODULE_0_axios___default.a.post('/findpwd', post).then(function (ret) {
				var data = ret.data;
				if (data.errcode === 0) {
					if (data.errcode === 0) {
						that.$toast('修改密码成功');
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
								setTimeout(function () {
									var from = __webpack_require__.i(__WEBPACK_IMPORTED_MODULE_1__utils_js__["c" /* getCookie */])('_fromUrl');
									from = from ? from : '/';
									from = decodeURIComponent(from);
									__webpack_require__.i(__WEBPACK_IMPORTED_MODULE_1__utils_js__["d" /* delCookie */])('_fromUrl');
									that.$router.push({
										path: from
									});
								}, 1500);
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
	}
});

/***/ }),

/***/ 434:
/***/ (function(module, exports, __webpack_require__) {

module.exports={render:function (){var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
  return _c('div', {
    staticClass: "page",
    attrs: {
      "id": "pageFindpwd"
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
  }, [_vm._v("找回密码")])]), _vm._v(" "), _c('div', {
    staticClass: "content"
  }, [_c('div', {
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
      "placeholder": "请输入难码"
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
  })])])])]), _vm._v(" "), _c('li')]), _vm._v(" "), _c('div', {
    staticClass: "content-block"
  }, [_c('div', {
    staticClass: "row"
  }, [_c('div', {
    staticClass: "col-100"
  }, [_c('span', {
    staticClass: "button button-big button-fill button-success",
    on: {
      "click": _vm.findpwd
    }
  }, [_vm._v("找回密码")])])])])]), _vm._v(" "), _c('div', {
    staticClass: "content-block"
  }, [_c('p', [_c('router-link', {
    staticClass: "color-primary",
    attrs: {
      "to": {
        name: 'login'
      }
    }
  }, [_vm._v("返回登录")])], 1)])])])
},staticRenderFns: []}
if (false) {
  module.hot.accept()
  if (module.hot.data) {
     require("vue-hot-reload-api").rerender("data-v-fc31dda0", module.exports)
  }
}

/***/ }),

/***/ 78:
/***/ (function(module, exports, __webpack_require__) {

var __vue_exports__, __vue_options__
var __vue_styles__ = {}

/* script */
__vue_exports__ = __webpack_require__(312)

/* template */
var __vue_template__ = __webpack_require__(434)
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
__vue_options__.__file = "/Users/wangfan/Work/webroot/adm/resources/assets/js/pages/findpwd.vue"
__vue_options__.render = __vue_template__.render
__vue_options__.staticRenderFns = __vue_template__.staticRenderFns

/* hot reload */
if (false) {(function () {
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), false)
  if (!hotAPI.compatible) return
  module.hot.accept()
  if (!module.hot.data) {
    hotAPI.createRecord("data-v-fc31dda0", __vue_options__)
  } else {
    hotAPI.reload("data-v-fc31dda0", __vue_options__)
  }
})()}
if (__vue_options__.functional) {console.error("[vue-loader] findpwd.vue: functional components are not supported and should be defined in plain js files using render functions.")}

module.exports = __vue_exports__


/***/ })

});