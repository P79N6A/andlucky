webpackJsonp([43],{

/***/ 310:
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
			charge_desc: '',
			cash: 0
		};
	},
	created: function created() {
		var that = this;
		__WEBPACK_IMPORTED_MODULE_0_axios___default.a.get('/charge' + '?api_token=' + window.apiToken, {
			responseType: 'json'
		}).then(function (response) {
			console.log(response);
			var data = response.data;
			if (data.errcode === 0) {
				that.charge_desc = data.desc;
			} else {
				//接口数据获取错误
				that.$router.go(-1);
			}
		});
	},
	methods: {
		fixDecimal: function fixDecimal() {
			var that = this;
			var cash = parseFloat(that.cash);
			cash = isNaN(cash) ? 0 : cash;
			that.cash = cash.toFixed(2);
		},
		charge: function charge() {
			var that = this;
			var cash = parseFloat(that.cash);
			cash = isNaN(cash) ? 0 : cash;
			that.cash = cash.toFixed(2);
			if (that.cash == 0) {
				return;
			}
			__WEBPACK_IMPORTED_MODULE_0_axios___default.a.post('/charge', {
				'api_token': window.apiToken,
				'_token': window._Token,
				'cash': that.cash
			}, {
				responseType: 'json'
			}).then(function (response) {
				console.log(response);
				var data = response.data;
				if (data.errcode === 0) {
					location.href = data.url;
				} else {
					//接口数据获取错误
					that.$router.go(-1);
				}
			});
		}
	}
});

/***/ }),

/***/ 410:
/***/ (function(module, exports, __webpack_require__) {

module.exports={render:function (){var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
  return _c('div', {
    staticClass: "page",
    attrs: {
      "id": "pageCharge"
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
  }, [_vm._v("充值")])]), _vm._v(" "), _c('nav', {
    staticClass: "bar bar-tab"
  }, [_c('a', {
    staticClass: "tab-item cursor",
    on: {
      "click": _vm.charge
    }
  }, [_vm._v("\n            立即充值\n        ")])]), _vm._v(" "), _c('div', {
    staticClass: "content infinite-scroll infinite-scroll-bottom",
    attrs: {
      "data-distance": "50"
    }
  }, [_c('div', {
    staticClass: "content-block"
  }, [_c('h4', [_vm._v("充值说明")]), _vm._v(" "), _c('p', {
    domProps: {
      "innerHTML": _vm._s(_vm.charge_desc)
    }
  })]), _vm._v(" "), _c('div', {
    staticClass: "list-block",
    staticStyle: {
      "margin": ".5rem 0rem"
    }
  }, [_c('form', {
    attrs: {
      "action": "/charge",
      "method": "POST",
      "id": "charge-form"
    }
  }, [_c('ul', [_c('li', [_c('div', {
    staticClass: "item-content"
  }, [_c('div', {
    staticClass: "item-inner"
  }, [_c('div', {
    staticClass: "item-title label"
  }, [_vm._v("充值铜板")]), _vm._v(" "), _c('div', {
    staticClass: "item-input"
  }, [_c('input', {
    directives: [{
      name: "model",
      rawName: "v-model",
      value: (_vm.cash),
      expression: "cash"
    }],
    attrs: {
      "type": "text",
      "placeholder": "请输入您要充值的铜板"
    },
    domProps: {
      "value": (_vm.cash)
    },
    on: {
      "change": _vm.fixDecimal,
      "input": function($event) {
        if ($event.target.composing) { return; }
        _vm.cash = $event.target.value
      }
    }
  })])])])])])])])])])
},staticRenderFns: []}
if (false) {
  module.hot.accept()
  if (module.hot.data) {
     require("vue-hot-reload-api").rerender("data-v-5316c9d0", module.exports)
  }
}

/***/ }),

/***/ 76:
/***/ (function(module, exports, __webpack_require__) {

var __vue_exports__, __vue_options__
var __vue_styles__ = {}

/* script */
__vue_exports__ = __webpack_require__(310)

/* template */
var __vue_template__ = __webpack_require__(410)
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
__vue_options__.__file = "/Users/wangfan/Work/webroot/adm/resources/assets/js/pages/charge.vue"
__vue_options__.render = __vue_template__.render
__vue_options__.staticRenderFns = __vue_template__.staticRenderFns

/* hot reload */
if (false) {(function () {
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), false)
  if (!hotAPI.compatible) return
  module.hot.accept()
  if (!module.hot.data) {
    hotAPI.createRecord("data-v-5316c9d0", __vue_options__)
  } else {
    hotAPI.reload("data-v-5316c9d0", __vue_options__)
  }
})()}
if (__vue_options__.functional) {console.error("[vue-loader] charge.vue: functional components are not supported and should be defined in plain js files using render functions.")}

module.exports = __vue_exports__


/***/ })

});
