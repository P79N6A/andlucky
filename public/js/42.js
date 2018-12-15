webpackJsonp([42],{

/***/ 311:
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


/* harmony default export */ __webpack_exports__["default"] = ({
	data: function data() {
		return {
			user: {},
			mobile: '',
			content: ''
		};
	},
	created: function created() {
		var that = this;
		if (window.apiToken) {
			__WEBPACK_IMPORTED_MODULE_0_axios___default.a.get('/member' + '?_token=' + window._Token + '&api_token=' + window.apiToken).then(function (response) {
				//console.log( that );
				var data = response.data;
				console.log(data);
				that.user = data.data.user;
				that.mobile = data.data.user.mobile;
			});
		}
	},
	methods: {
		feedback: function feedback() {
			var that = this;

			if (!that.mobile) {
				that.$toast('请填写您的联系方式!');
				return false;
			}
			if (!that.content) {
				that.$toast("请填写您的建议内容！");
				return false;
			}
			//检查用户铜板是不是满足发布的最小要求
			var post = {
				'mobile': that.mobile,
				'content': that.content,
				'_token': window._Token
			};
			__WEBPACK_IMPORTED_MODULE_0_axios___default.a.post('/feedback', post).then(function (ret) {
				var data = ret.data;
				that.$toast(data.msg);
				if (data.errcode === 0) {
					setTimeout(function () {
						that.$router.go(-1);
					}, 1500);
				}
			});
		}
	}
});

/***/ }),

/***/ 430:
/***/ (function(module, exports, __webpack_require__) {

module.exports={render:function (){var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
  return _c('div', {
    staticClass: "page",
    attrs: {
      "id": "pageFeedback"
    }
  }, [_c('header', {
    staticClass: "bar bar-nav"
  }, [_c('a', {
    staticClass: "button button-link button-nav pull-left back",
    on: {
      "click": function($event) {
        _vm.$router.go(-1)
      }
    }
  }, [_c('span', {
    staticClass: "icon icon-left"
  }), _vm._v("\n            返回\n        ")]), _vm._v(" "), _c('h1', {
    staticClass: "title"
  }, [_vm._v("意见反馈")])]), _vm._v(" "), _c('nav', {
    staticClass: "bar bar-tab"
  }, [_c('a', {
    staticClass: "tab-item cursor",
    on: {
      "click": _vm.feedback
    }
  }, [_vm._v("\n            提交\n        ")])]), _vm._v(" "), _c('div', {
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
      "placeholder": "您的联系方式"
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
  })])])])]), _vm._v(" "), _vm._m(0), _vm._v(" "), _c('li', [_c('div', {
    staticClass: "item-content"
  }, [_c('div', {
    staticClass: "item-inner"
  }, [_c('div', {
    staticClass: "item-input"
  }, [_c('textarea', {
    directives: [{
      name: "model",
      rawName: "v-model",
      value: (_vm.content),
      expression: "content"
    }],
    attrs: {
      "placeholder": "请输入反馈内容"
    },
    domProps: {
      "value": (_vm.content)
    },
    on: {
      "input": function($event) {
        if ($event.target.composing) { return; }
        _vm.content = $event.target.value
      }
    }
  })])])])]), _vm._v(" "), _c('li', [_c('div', {
    staticClass: "item-content"
  }, [_c('div', {
    staticClass: "item-inner"
  }, [_c('div', {
    staticClass: "item-title label color-gray",
    attrs: {
      "id": "character-tip"
    }
  }, [_vm._v(_vm._s(_vm.content.length) + "/200")]), _vm._v(" "), _c('div', {
    staticClass: "item-input text-right color-gray",
    attrs: {
      "id": "error-tip"
    }
  }, [_vm._v("\n                                内容控制在200字左右\n                            ")])])])])])])])])
},staticRenderFns: [function (){var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
  return _c('li', [_c('div', {
    staticClass: "item-content"
  }, [_c('div', {
    staticClass: "item-inner"
  }, [_c('div', {
    staticClass: "item-title label"
  }, [_vm._v("反馈内容")])])])])
}]}
if (false) {
  module.hot.accept()
  if (module.hot.data) {
     require("vue-hot-reload-api").rerender("data-v-cf805f2e", module.exports)
  }
}

/***/ }),

/***/ 77:
/***/ (function(module, exports, __webpack_require__) {

var __vue_exports__, __vue_options__
var __vue_styles__ = {}

/* script */
__vue_exports__ = __webpack_require__(311)

/* template */
var __vue_template__ = __webpack_require__(430)
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
__vue_options__.__file = "/Users/wangfan/Work/webroot/adm/resources/assets/js/pages/feedback.vue"
__vue_options__.render = __vue_template__.render
__vue_options__.staticRenderFns = __vue_template__.staticRenderFns

/* hot reload */
if (false) {(function () {
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), false)
  if (!hotAPI.compatible) return
  module.hot.accept()
  if (!module.hot.data) {
    hotAPI.createRecord("data-v-cf805f2e", __vue_options__)
  } else {
    hotAPI.reload("data-v-cf805f2e", __vue_options__)
  }
})()}
if (__vue_options__.functional) {console.error("[vue-loader] feedback.vue: functional components are not supported and should be defined in plain js files using render functions.")}

module.exports = __vue_exports__


/***/ })

});
