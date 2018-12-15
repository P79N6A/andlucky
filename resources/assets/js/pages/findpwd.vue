<template>
<div class="page" id="pageFindpwd">
    <header class="bar bar-nav">
        <a class="button button-link button-nav pull-left" @click="$router.go(-1)">
            <span class="icon icon-left"></span>
            返回
        </a>
        <h1 class="title">找回密码</h1>
    </header>
    <div class="content" >

        <div class="list-block" style="margin:.5rem 0rem;">
            <ul>
              <!-- Text inputs -->
                <li>
                    <div class="item-content">
                        <div class="item-inner">
                            <div class="item-title label">手机号码</div>
                            <div class="item-input">
                                <input type="text" v-model="mobile" placeholder="请输入手机号码">
                            </div>
                        </div>
                    </div>
                </li>

                <li>
                    <div class="item-content">
                        <div class="item-inner">
                            <div class="item-title label">验证码</div>
                            <div class="item-input">
                                <input type="text" v-model="verify" placeholder="请输入难码">
                            </div>
                            <span style="width: 60%;" :class="{'color-gray': sending > 0 }" @click="sendVerify">{{btnVerify}}</span>
                        </div>
                    </div>
                </li>

                <li>
                    <div class="item-content">
                        <div class="item-inner">
                            <div class="item-title label">密码</div>
                            <div class="item-input">
                                <input type="password" v-model="password" placeholder="请输入密码">
                            </div>
                        </div>
                    </div>
                </li>

                <li>
                    <div class="item-content">
                        <div class="item-inner">
                            <div class="item-title label">确认密码</div>
                            <div class="item-input">
                                <input type="password" v-model="confirmPassword" placeholder="请再次输入密码">
                            </div>
                        </div>
                    </div>
                </li>

                <li>
                    
                </li>
            </ul>
            <div class="content-block">
                <div class="row">
                    <div class="col-100">
                    	<span  class="button button-big button-fill button-success" @click="findpwd">找回密码</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="content-block">
            <p><router-link :to="{name:'login'}" class="color-primary">返回登录</router-link></p>
        </div>
    </div>

</div>
</template>

<script>
import Axios from "axios"
import {getCookie , setCookie , delCookie , formatDate } from '../utils.js' ;
export default {
	data:function(){
		return {
			btnVerify : '获取验证码' ,
			sending : 0 ,
			mobile :'' ,
			password : '' ,
			verify : '' ,
			confirmPassword : ''
		}
	} ,
	methods :{
		sendVerify:function(){
			let that = this 
			if( that.sending > 0 ) {
				return false 
			}
			if( !that.mobile ) {
				that.$toast("请填写手机号码")
				return false 
			}
			
			
			var post = {
				'phone': that.mobile ,
				'_token': window._Token
			};
			that.sending = 1 
			Axios.post( '/sendfindpwdsms' , post ).then( function( ret ){
				let data = ret.data 
				if( data.errcode === 0 ) {
					that.sending = data.left ;
					that.btnVerify = that.sending + 's重新发送' 
					var timer = setInterval(function(){
						that.sending -- ;
						that.btnVerify = that.sending + 's重新发送' 
						if( that.sending == 0 ) {
							clearInterval( timer );
							that.btnVerify = '获取验证码' 
						}
					} , 1000);
				} else {
					that.$toast( data.msg );
				}
			});
		} ,
		findpwd:function(){
			let that = this 
			if( !that.mobile ) {
				that.$toast('请填写手机号码')
				return false 
			}
			if( !that.verify ) {
				that.$toast('请填写手验证码')
				return false 
			}
			if( !that.password ) {
				that.$toast('请填写密码')
				return false 
			}
			if( !that.confirmPassword ) {
				that.$toast('请再次输入密码')
				return false 
			}
			if( that.confirmPassword != that.password ) {
				that.$toast('两次输入密码不一致')
				return false 
			}
			
			var post = {
				'name': that.mobile ,
				'password' : that.password ,
				'comfirmed' : that.confirmPassword ,
				'verify' : that.verify ,
				'_token': window._Token
			};
			Axios.post( '/findpwd' , post ).then( function( ret ){
				let data = ret.data 
				if( data.errcode === 0 ) {
					if( data.errcode === 0 ) {
						that.$toast('修改密码成功');
						document.querySelector('meta[name="api-token"]').content = data.user.api_token
						window.apiToken = data.user.api_token
						window.imUser = data.user.name 
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
	                            let from = getCookie('_fromUrl')
								from = from ? from : '/'
								from = decodeURIComponent( from )
								delCookie( '_fromUrl' )
								that.$router.push({
									path : from
								})
	                        },
	                        error: function( data ){
	                            that.$toast('im login err');
	                            setTimeout( function(){
									let from = getCookie('_fromUrl')
									from = from ? from : '/'
									from = decodeURIComponent( from )
									delCookie( '_fromUrl' )
									that.$router.push({
										path : from
									})
								} , 1500 );
	                        }
	                    };
	                    conn.open( options )
	                    /**
						setTimeout( function(){
							that.$router.push({name:'home'})
						} , 1500 );
						**/
					}
				} else {
					that.$toast( data.msg );
				}
			});
		}
	}
}

</script>