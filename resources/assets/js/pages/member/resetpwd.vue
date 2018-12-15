<template>
<div class="page" id="pageModpwd">
    <header class="bar bar-nav">
        <a class="button button-link button-nav pull-left" @click="$router.go(-1)">
            <span class="icon icon-left"></span>
            返回
        </a>
        <h1 class="title">修改密码</h1>
    </header>
    <div class="content" >

        <div class="list-block" style="margin:.5rem 0rem;">
            <ul>
              <!-- Text inputs -->
                <li>
                    <div class="item-content">
                        <div class="item-inner">
                            <div class="item-title label">旧密码</div>
                            <div class="item-input">
                                <input type="password" v-model="old_pwd" placeholder="请输入密码">
                            </div>
                        </div>
                    </div>
                </li>

                <li>
                    <div class="item-content">
                        <div class="item-inner">
                            <div class="item-title label">密码</div>
                            <div class="item-input">
                                <input type="password" v-model="new_pwd" placeholder="请输入新密码">
                            </div>
                        </div>
                    </div>
                </li>

                <li>
                    <div class="item-content">
                        <div class="item-inner">
                            <div class="item-title label">确认密码</div>
                            <div class="item-input">
                                <input type="password" v-model="confirm_pwd" placeholder="请再次输入密码">
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
                    	<a class="button button-big button-fill button-success" @click="modpwd">修改密码</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
</template>

<script>
import Axios from 'axios'
export default {
	data:function(){
		return {
			old_pwd : '' ,
			new_pwd : '' ,
			confirm_pwd : '' ,
		}
	} ,
	methods :{
		modpwd : function(){
			let that = this
			if( !that.old_pwd ) {
				that.$toast('请输入旧密码');
				return false ;
			}
			if( !that.new_pwd ) {
				that.$toast('请填写密码');
				return false ;
			}
			if( !that.confirm_pwd ) {
				that.$toast('请再次输入密码');
				return false ;
			}
			if( that.confirm_pwd != that.new_pwd ) {
				that.$toast('两次输入密码不一致');
				return false ;
			}
			var post = {
				'oldpwd': that.old_pwd ,
				'password' : that.new_pwd ,
				'comfirmed' : that.confirm_pwd ,
				'_token': window._Token ,
				'api_token' : window.apiToken ,
			};
			Axios.post('/modpwd' , post ).then( function( ret ){
				let data = ret.data 
				that.$toast( data.msg )
				if( data.errcode === 0 ) {
					setTimeout( function(){
						that.$router.go(-1)
					} , 1500 );
				}
			})
		}
	}

}

</script>

<style>

</style>