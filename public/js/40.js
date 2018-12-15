webpackJsonp([40],{

/***/ 105:
/***/ (function(module, exports, __webpack_require__) {

var __vue_exports__, __vue_options__
var __vue_styles__ = {}

/* script */
__vue_exports__ = __webpack_require__(339)

/* template */
var __vue_template__ = __webpack_require__(425)
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
__vue_options__.__file = "/Users/wangfan/Work/webroot/adm/resources/assets/js/pages/notice/show.vue"
__vue_options__.render = __vue_template__.render
__vue_options__.staticRenderFns = __vue_template__.staticRenderFns

/* hot reload */
if (false) {(function () {
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), false)
  if (!hotAPI.compatible) return
  module.hot.accept()
  if (!module.hot.data) {
    hotAPI.createRecord("data-v-b99af7c0", __vue_options__)
  } else {
    hotAPI.reload("data-v-b99af7c0", __vue_options__)
  }
})()}
if (__vue_options__.functional) {console.error("[vue-loader] show.vue: functional components are not supported and should be defined in plain js files using render functions.")}

module.exports = __vue_exports__


/***/ }),

/***/ 339:
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



/* harmony default export */ __webpack_exports__["default"] = ({
	data: function data() {
		return {
			data: {}
		};
	},
	created: function created() {
		var that = this;
		__WEBPACK_IMPORTED_MODULE_0_axios___default.a.get('/notice/' + that.$route.params.id + '?_token=' + window._Token + '&api_token=' + window.apiToken).then(function (response) {
			//console.log( that );
			var data = response.data;
			if (data.errcode === 0) {
				that.data = data.data;
			}
		});
	}
});

/***/ }),

/***/ 425:
/***/ (function(module, exports, __webpack_require__) {

module.exports={render:function (){var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
  return _c('div', {
    staticClass: "page",
    attrs: {
      "id": "pageNoticeView"
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
  }, [_vm._v("公告")])]), _vm._v(" "), _c('div', {
    staticClass: "content"
  }, [_c('h4', {
    staticClass: "text-center"
  }, [_vm._v(_vm._s(_vm.data.title))]), _vm._v(" "), _c('div', {
    staticClass: "content-block font-size-14",
    staticStyle: {
      "margin-top": "0rem",
      "text-indent": "2em"
    },
    domProps: {
      "innerHTML": _vm._s(_vm.data.content)
    }
  })])])
},staticRenderFns: []}
if (false) {
  module.hot.accept()
  if (module.hot.data) {
     require("vue-hot-reload-api").rerender("data-v-b99af7c0", module.exports)
  }
}

/***/ })

});