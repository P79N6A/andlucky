<template>
<div class="page" id="pageLogin">
    <header class="bar bar-nav">
        <a class="button button-link button-nav pull-left" @click="$router.go(-1)">
            <span class="icon icon-left"></span>
            返回
        </a>
        <h1 class="title">用户登录</h1>
    </header>
    <div class="content" >
        <p class="text-center" style="background: #fff;margin:0rem;margin-top:.5rem;padding: .5rem;">
            <img src="/images/logo.png" style="width:4rem;" />
        </p>

        <div class="list-block" style="margin:0rem 0rem;">
            <ul>
              <!-- Text inputs -->
                <li>
                    <div class="item-content">
                        <div class="item-inner">
                            <div class="item-title label">手机号码</div>
                            <div class="item-input">
                                <input type="text" v-model="mobile" v-on:keyup.13="login" placeholder="请输入手机号码">
                            </div>
                        </div>
                    </div>
                </li>

                <li>
                    <div class="item-content">
                        <div class="item-inner">
                            <div class="item-title label">密码</div>
                            <div class="item-input">
                                <input type="password" v-model="password" v-on:keyup.13="login" placeholder="请输入密码">
                            </div>
                        </div>
                    </div>
                </li>

            </ul>
            <div class="content-block" style="margin:.5rem 0rem;">
                <div class="row">
                    <div class="col-100">
                    	<span class="button button-big button-fill button-success" @click="login" >登录</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="content-block">
            <p>还没注册？<router-link :to="{name:'register'}" class="color-danger">点击注册</router-link> , 忘记密码？
                <router-link :to="{name:'findpwd'}" class="color-danger">点击找回</router-link></p>
        </div>
    </div>

</div> 
</template>
<script>
require('es6-promise').polyfill();
import Axios from "axios"
import {getCookie , setCookie , delCookie , formatDate } from '../utils.js' ;
export default {
	data:function(){
		return {
			mobile : '' ,
			password : '' ,
		}
	},
	methods:{
		login:function( ){
			let that = this ;
			if( !that.mobile ) {
				that.$toast('请填写手机号码')
				return false ;
			}
			if( !that.password ) {
				that.$toast('请填写密码')
				return false ;
			}
        
            try {
    			Axios.post( '/login' , {
    				name : that.mobile ,
    				password : that.password ,
    				_token : window._Token ,
    			}).then(function( ret ){
    				let data = ret.data 
    				if( data.errcode === 0 ) {
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
                                console.log( that )
                                let from = getCookie('_fromUrl')
                                from = from ? from : '/'
                                from = decodeURIComponent( from )
                                delCookie( '_fromUrl' )
                                that.$router.push({
                                    path : from
                                })
                            },
                            error : function( data ){
                                //that.$toast('im login err');
                                console.log( data );
                                let from = getCookie('_fromUrl')
                                from = from ? from : '/'
                                from = decodeURIComponent( from )
                                delCookie( '_fromUrl' )
                                that.$router.push({
                                    path : from
                                })
                            }
                        };
                        conn.open(options);     
    				} else {
    					that.$toast( data.msg )
    				}
    			} , function( ret ){
                    //alert( ret )
                    that.$toast('登录异常请重试' + ret )

                    setTimeout( function(){
                        location.reload()
                    } , 1000 )
                }).catch(function( err ){
                    that.$toast('登录异常请重试')
                    setTimeout( function(){
                        location.reload()
                    } , 1000 )
                })
            } catch( e ) {
                  
            }
            
		}
	}
}

</script>

<style>
	
</style>