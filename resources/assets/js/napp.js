window.WebIM = {}
window.Promise = require('promise');
window._Token = document.querySelector('meta[name="csrf-token"]').content 
window.apiToken = document.querySelector('meta[name="api-token"]').content 
window.tipAudio = document.getElementById("tipAudio")
window.imLogin = false
require('./audioPlugin')
import 'vue2-animate/dist/vue2-animate.min.css'
import Vue from 'vue'
import VueRouter from 'vue-router'
import promise from 'es6-promise'
promise.polyfill()
import Axios from "axios"


import {getCookie , formatDate , setCookie } from './utils'

import {Alert, Confirm, Prompt, Toast} from 'wc-messagebox'
import 'wc-messagebox/style.css'

import 'vue-smooth-picker/dist/css/style.css'
import SmoothPicker from 'vue-smooth-picker'
Vue.use( SmoothPicker )

import VCalendar from 'v-calendar'
import 'v-calendar/lib/v-calendar.min.css'
Vue.use(VCalendar)

var options = {}
Vue.use(Alert, options)
Vue.use(Confirm, options)
//Vue.use(Prompt, options)
Vue.use(Toast, 1500 , {
	'top' : '10%'
})

//Vue.use( Axios );
Vue.use( VueRouter )

import config from './im/WebIMConfig'
WebIM.config = config 
require( "easemob-websdk" )

//  page 
//import home from "./pages/index"
const home = resolve => {
    require.ensure(['./pages/index'], () => {
        resolve(require('./pages/index'))
    })
}

//import login from './pages/login'
const login = resolve => {
    require.ensure(['./pages/login'], () => {
        resolve(require('./pages/login'))
    })
}

//import register from './pages/register'
const register = resolve => {
    require.ensure(['./pages/register'], () => {
        resolve(require('./pages/register'))
    })
}

//import findpwd from './pages/findpwd'
const findpwd = resolve => {
    require.ensure(['./pages/findpwd'], () => {
        resolve(require('./pages/findpwd'))
    })
}

//import aboutus from './pages/aboutus'
const aboutus = resolve => {
    require.ensure(['./pages/aboutus'], () => {
        resolve(require('./pages/aboutus'))
    })
}

//import help from './pages/help'
const help = resolve => {
    require.ensure(['./pages/help'], () => {
        resolve(require('./pages/help'))
    })
}

//import feedback from './pages/feedback'
const feedback = resolve => {
    require.ensure(['./pages/feedback'], () => {
        resolve(require('./pages/feedback'))
    })
}

//import notices from './pages/notice/list'
const notices = resolve => {
    require.ensure(['./pages/notice/list'], () => {
        resolve(require('./pages/notice/list'))
    })
}


//import noticeshow from './pages/notice/show'
const noticeshow = resolve => {
    require.ensure(['./pages/notice/show'], () => {
        resolve(require('./pages/notice/show'))
    })
}

//import charge from './pages/charge'
const charge = resolve => {
    require.ensure(['./pages/charge'], () => {
        resolve(require('./pages/charge'))
    })
}

//广告
//import advs from "./pages/adv/adv"
const advs = resolve => {
    require.ensure(['./pages/adv/adv'], () => {
        resolve(require('./pages/adv/adv'))
    })
}

//import advshow from "./pages/adv/advshow"
const advshow = resolve => {
    require.ensure(['./pages/adv/advshow'], () => {
        resolve(require('./pages/adv/advshow'))
    })
}

//import advform from "./pages/adv/form"
const advform = resolve => {
    require.ensure(['./pages/adv/form'], () => {
        resolve(require('./pages/adv/form'))
    })
}

//说说
//import microblog from "./pages/blog/microblog"
const microblog = resolve => {
    require.ensure(['./pages/blog/microblog'], () => {
        resolve(require('./pages/blog/microblog'))
    })
}

//import microblogshow from "./pages/blog/microblogshow"
const microblogshow = resolve => {
    require.ensure(['./pages/blog/microblogshow'], () => {
        resolve(require('./pages/blog/microblogshow'))
    })
}

//import microblogform from "./pages/blog/microblogform.vue"
const microblogform = resolve => {
    require.ensure(['./pages/blog/microblogform'], () => {
        resolve(require('./pages/blog/microblogform'))
    })
}

//import microblogmine from "./pages/blog/mine.vue"
const microblogmine = resolve => {
    require.ensure(['./pages/blog/mine'], () => {
        resolve(require('./pages/blog/mine'))
    })
}

//import myspace from "./pages/blog/myspace"
const myspace = resolve => {
    require.ensure(['./pages/blog/myspace'], () => {
        resolve(require('./pages/blog/myspace'))
    })
}

//import space from "./pages/blog/space"
const space = resolve => {
    require.ensure(['./pages/blog/space'], () => {
        resolve(require('./pages/blog/space'))
    })
}

//import comment from "./pages/blog/comment"
const comment = resolve => {
    require.ensure(['./pages/blog/comment'], () => {
        resolve(require('./pages/blog/comment'))
    })
}

//发起邀请
//import inviteBigSmall from './pages/inviteBigSmall'
const inviteBigSmall = resolve => {
    require.ensure(['./pages/inviteBigSmall'], () => {
        resolve(require('./pages/inviteBigSmall'))
    })
}

//import inviteBigSmallDetail from './pages/inviteBigSmallDetail'
const inviteBigSmallDetail = resolve => {
    require.ensure(['./pages/inviteBigSmallDetail'], () => {
        resolve(require('./pages/inviteBigSmallDetail'))
    })
}

//import bigSmallHelp from './pages/bigSmallHelp'
const bigSmallHelp = resolve => {
    require.ensure(['./pages/bigSmallHelp'], () => {
        resolve(require('./pages/bigSmallHelp'))
    })
}

//好友
//import friends from './pages/im/friends'
const friends = resolve => {
    require.ensure(['./pages/im/friends'], () => {
        resolve(require('./pages/im/friends'))
    })
}

//import history from './pages/im/history'
const history = resolve => {
    require.ensure(['./pages/im/history'], () => {
        resolve(require('./pages/im/history'))
    })
}

//import thief from './pages/im/thief' 
const thief = resolve => {
    require.ensure(['./pages/im/thief'], () => {
        resolve(require('./pages/im/thief'))
    })
}

//import chat from './pages/im/chat' 
const chat = resolve => {
    require.ensure(['./pages/im/chat'], () => {
        resolve(require('./pages/im/chat'))
    })
}

//import searchFriend from './pages/im/searchFriend'
const searchFriend = resolve => {
    require.ensure(['./pages/im/searchFriend'], () => {
        resolve(require('./pages/im/searchFriend'))
    })
}

//我的会员中心
//import memberIndex from './pages/member/index'
const memberIndex = resolve => {
    require.ensure(['./pages/member/index'], () => {
        resolve(require('./pages/member/index'))
    })
}

//import avatar from './pages/member/avatar'
const avatar = resolve => {
    require.ensure(['./pages/member/avatar'], () => {
        resolve(require('./pages/member/avatar'))
    })
}

//import resetPassword from './pages/member/resetpwd'
const resetPassword = resolve => {
    require.ensure(['./pages/member/resetpwd'], () => {
        resolve(require('./pages/member/resetpwd'))
    })
}

//import invited from './pages/member/invited'
const invited = resolve => {
    require.ensure(['./pages/member/invited'], () => {
        resolve(require('./pages/member/invited'))
    })
}

//import messages from './pages/message/index'
const messages = resolve => {
    require.ensure(['./pages/message/index'], () => {
        resolve(require('./pages/message/index'))
    })
}

//import messageshow from './pages/message/show'
const messageshow = resolve => {
    require.ensure(['./pages/message/show'], () => {
        resolve(require('./pages/message/show'))
    })
}

//import memberBaseInfo from './pages/member/baseinfo'
const memberBaseInfo = resolve => {
    require.ensure(['./pages/member/baseinfo'], () => {
        resolve(require('./pages/member/baseinfo'))
    })
}

//import memberAdvs from './pages/adv/mine'
const memberAdvs = resolve => {
    require.ensure(['./pages/adv/mine'], () => {
        resolve(require('./pages/adv/mine'))
    })
}

//import memberBigSmall from './pages/member/bigsmall'
const memberBigSmall = resolve => {
    require.ensure(['./pages/member/bigsmall'], () => {
        resolve(require('./pages/member/bigsmall'))
    })
}

//import memberCashlog from './pages/member/cashlog'
const memberCashlog = resolve => {
    require.ensure(['./pages/member/cashlog'], () => {
        resolve(require('./pages/member/cashlog'))
    })
}

//import memberWithDraw from './pages/member/withdraw'
const memberWithDraw = resolve => {
    require.ensure(['./pages/member/withdraw'], () => {
        resolve(require('./pages/member/withdraw'))
    })
}

//import memberChargelog from './pages/member/chargelog'
const memberChargelog = resolve => {
    require.ensure(['./pages/member/chargelog'], () => {
        resolve(require('./pages/member/chargelog'))
    })
}

//押大小
//import guessForm from './pages/guess/form'
const guessForm = resolve => {
    require.ensure(['./pages/guess/form'], () => {
        resolve(require('./pages/guess/form'))
    })
}

//import guessShow from './pages/guess/show'
const guessShow = resolve => {
    require.ensure(['./pages/guess/show'], () => {
        resolve(require('./pages/guess/show'))
    })
}

//import guessMine from './pages/guess/mine'
const guessMine = resolve => {
    require.ensure(['./pages/guess/mine'], () => {
        resolve(require('./pages/guess/mine'))
    })
}

//import guessJoin from './pages/guess/joins'
const guessJoin = resolve => {
    require.ensure(['./pages/guess/joins'], () => {
        resolve(require('./pages/guess/joins'))
    })
}





// router 
const routes = [
	{ path: '/game', component: home , name : 'home' } ,
	{ path: '/login', component: login , name : 'login' } ,
	{ path: '/register', component: register , name : 'register' } ,
	{ path: '/findpwd', component: findpwd , name : 'findpwd' } ,
	{ path: '/aboutus', component: aboutus , name : 'aboutus' } ,
	{ path: '/help', component: help , name : 'help' } ,
	{ path: '/bigsmall/help', component: bigSmallHelp , name : 'bigsmall-help' } ,
	{ path: '/feedback', component: feedback , name : 'feedback' } ,
	{ path: '/notices', component: notices , name : 'notices' } ,
	{ path: '/notice/:id', component: noticeshow , name : 'notice-show' } ,
	{ path : '/charge' , component : charge , name:'charge' , meta:{ requireAuth : true } } ,


	{ path: '/adv', component: advs , name : 'adv' } ,
	{ path : '/adv/form' , component : advform , name:'adv-form' , meta:{ requireAuth : true } } ,
	{ path : '/adv/mine' , component : memberAdvs , name:'adv-mine' , meta:{ requireAuth : true } } ,
	{ path: '/adv/:id', component: advshow , name : 'advshow' } ,
	{ path: '/adv/:id/edit', component: advform , name : 'adv-edit' } ,
	

	{ path: '/', component: microblog , name : 'microblog' , meta:{ requireAuth : true , keepAlive: true } } ,
	{ path: '/microblogshow/:id', component: microblogshow , name : 'microblog-show' , meta:{ requireAuth : true  } } ,
	{ path: '/microblog/:id/edit', component: microblogform , name : 'microblog-edit' , meta:{ requireAuth : true } } ,
	{ path: '/microblog/form', component: microblogform , name : 'microblog-form' , meta:{ requireAuth : true } } ,
	{ path: '/microblog/mine', component: microblogmine , name : 'microblog-mine' , meta:{ requireAuth : true } } ,
	{ path: '/myspace', component: myspace , name : 'microblog-myspace' , meta:{ requireAuth : true } } ,
	{ path: '/space/:id', component: space , name : 'microblog-space' , meta:{ requireAuth : true } } ,

	{ path: '/comment/:id', component: comment , name : 'comment-show' , meta:{ requireAuth : true } } ,


	{ path : '/bigsmall/invite/:id' , component : inviteBigSmall , name:'bigsmall-invite' , meta:{ requireAuth : true } } ,
	{ path : '/bigsmall/detail/:id' , component : inviteBigSmallDetail , name:'bigsmall-detail' , meta:{ requireAuth : true } } ,
	

	{ path : '/im/friends' , component : friends , name:'im-friends' , meta:{ requireAuth : true } } ,
	{ path : '/im/history' , component : history , name:'im-history' , meta:{ requireAuth : true } } ,
	{ path : '/im/thief' , component : thief , name:'im-thief' , meta:{ requireAuth : true } } ,
	{ path : '/im/chat/:user_id' , component : chat , name:'im-chat' , meta:{ requireAuth : true } } ,
	{ path : '/im/searchfriend' , component : searchFriend , name:'chat-search-friend' , meta:{ requireAuth : true } } ,


	{ path : '/member' , component : memberIndex , name:'member' , meta:{ requireAuth : true } } ,
	{ path : '/member/avatar' , component : avatar , name:'avatar' , meta:{ requireAuth : true } } ,
	{ path : '/member/modpwd' , component : resetPassword , name:'member-modpwd' , meta:{ requireAuth : true } } ,
	{ path : '/member/invited' , component : invited , name:'member-invited' , meta:{ requireAuth : true } } ,
	{ path : '/member/messages' , component : messages , name:'messages' , meta:{ requireAuth : true } } ,
	{ path : '/member/message/:id' , component : messageshow , name:'message-show' , meta:{ requireAuth : true } } ,
	{ path : '/member/baseinfo' , component : memberBaseInfo , name:'member-baseinfo' , meta:{ requireAuth : true } } ,
	{ path : '/member/bigsmall' , component : memberBigSmall , name:'member-bigsmall' , meta:{ requireAuth : true } } ,
	{ path : '/member/cashlog' , component : memberCashlog , name:'member-cashlog' , meta:{ requireAuth : true } } ,
	{ path : '/member/chargelog' , component : memberChargelog , name:'member-chargelog' , meta:{ requireAuth : true } } ,
	{ path : '/member/withdraw' , component : memberWithDraw , name:'member-withdraw' , meta:{ requireAuth : true } } ,
	{ path : '/member/withdraw/apply' , component : memberWithDraw , name:'withdraw-apply' , meta:{ requireAuth : true } } ,


	{ path : '/guess/form' , component : guessForm , name:'guess-form' , meta:{ requireAuth : true } } ,
	{ path : '/guess/joins' , component : guessJoin , name:'guess-joins' , meta:{ requireAuth : true } } ,
	{ path : '/guess/show/:id' , component : guessShow , name:'guess-show' , meta:{ requireAuth : true } } ,
	{ path : '/guess/mine' , component : guessMine , name:'guess-mine' , meta:{ requireAuth : true } } ,




]


const router = new VueRouter({
	routes
});


router.beforeEach((to, from, next) => {
	window.to = to 
	window.from = from
	if( from.meta.keepAlive ) {
		let t = document.querySelector('.content').scrollTop
		localStorage.setItem('scroll' , t )
	}
	if(to.meta.requireAuth) {
		if( window.apiToken ) {
			next();
		} else {
			setCookie('_fromUrl' , to.fullPath , 1 )
			next({
				path: '/login'
			});
		}
	} else {
		if( window.apiToken && to.path == '/login' ) {
			next( {
				path: '/'
			} )
		} else {
			next();	
		}
	}
});

//import babel-polyfill from "babel-polyfill"

var FastClick = require("fastclick")
FastClick.attach(document.body)

window.conn = new WebIM.connection({
    isMultiLoginSessions: WebIM.config.isMultiLoginSessions,
    https: typeof WebIM.config.https === 'boolean' ? WebIM.config.https : location.protocol === 'https:',
    url: WebIM.config.xmppURL,
    heartBeatWait: WebIM.config.heartBeatWait,
    autoReconnectNumMax: WebIM.config.autoReconnectNumMax,
    autoReconnectInterval: WebIM.config.autoReconnectInterval,
    apiUrl: WebIM.config.apiURL,
    isAutoLogin: true
})

conn.onError = function( res ){

	console.log( res );
}

conn.onReceivedMessage = function( msg ){
	console.log( msg );
}

conn.onOffline = function(){
	alert('掉线下刷新一下')
}

conn.listen({
	onOpened:function( message ){
        console.log( message );
    } ,
    onTextMessage:function( message ) {
    	tipAudio.play()
    } ,
    onCmdMessage:function( message ){
		tipAudio.play()
	}
})

//如果有apiToken 则尝试登录
var times = 0 
var atemptLogin = setInterval( function(){
	if( times > 20 ) {
		alert("您的登录信息异常，请联系管理员")
		clearInterval( atemptLogin )
		times = 0 ;
		return ;
	}
	if( !window.imLogin) {
		if( window.apiToken ) {
			Axios.get('/userinfo?api_token=' + apiToken ).then( function( ret ){
				let data = ret.data
				if( data.errcode === 0 && data.user ) {
					var options = {
					    apiUrl: WebIM.config.apiURL,
					    user:   data.user.name ,
					    pwd: data.user.api_token ,
					    appKey: WebIM.config.appkey,
					    success: function (token) {
					        console.log( token );
					        var token = token.access_token;
					    	setCookie('webim_' + data.user.name , token, 1 );
					    	console.log('im login ok')
					    	window.imLogin = true 
					    	times = 0 ;
					    },
					    error: function( data ){
					    	console.log('err');
					    	console.log( data );
					    	times++ ;
					    }
					};
					conn.open(options);		
				}
			})
		}
	} else {
		clearInterval( atemptLogin )
	}
} , 15000 )
	

const app = new Vue({
	router
}).$mount('#app-3')


setInterval( function(){
	if( window.apiToken ) {
		Axios.get('/pong?api_token=' + window.apiToken )	
	}
} , 5000 );
