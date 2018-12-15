<template id="big_small">
	<div class="page">
		<header class="bar bar-nav">
            <a v-on:click="back()" class="button button-link button-nav pull-left back" data-transition='slide-out'>
                    <span class="icon icon-left"></span>
                    返回
            </a>
            <h1 class="title">比大小</h1>
            <router-link class="button button-link button-nav pull-right" :to="{name:'bigsmall-help'}">
	            <span class="icon icon-help"></span>
	            帮助
	        </router-link>
        </header>
        <div class="content infinite-scroll infinite-scroll-bottom" data-distance="50" >
	        <div class="content-block tip" >
	            <p v-if="!onLine">
					当前用户不在线
			    </p>
	            <p>狗粮应该在{{min}}到{{max}}之间</p>
	        </div>
	        <div class="content-block list-block">
			    <ul>
					<!-- Text inputs -->
					<li>
			        	<div class="item-content">
			        		<div class="item-inner">
			        			<div class="item-title label">狗粮</div>
			            		<div class="item-input">
			            			<input type="text" v-model.number="promise" placeholder="请输入您想使用的狗粮">
			            		</div>
			          		</div>
			        	</div>
			    	</li>
			    </ul>
			</div>
			<div class="content-block">
				<div class="button button-danger button-fill button-big" v-on:click="next()">发起战斗</div>
			</div>
    	</div>
    </div>
</template>

<script>
import Axios from "axios" ;

export default {
	data : function(){
		return {
			desc : '' ,
			min : 0 ,
			max : 0 ,
			promise: '' ,
			onLine : true ,
		}
	} ,
	created : function(){
		var that = this ;
		Axios.get('/bigsmall/desc' , {
			responseType : 'json'
		}).then(function(response) {
				console.log( that );
				that.desc = response.data.desc.replace(/\r\n/ , '<br/>') ;
				that.min = response.data.min ;
				that.max = response.data.max ;
			});
		Axios.get('/im/isonline?user_id=' +that.$route.params.id + '&api_token=' + window.apiToken  , {
			responseType : 'json'
		}).then(function(response) {
				if( 'online' == response.data.online ) {
					that.onLine = true ;
				} else {
					that.onLine = false ;
				}
			});
	} ,
	methods : {
		back : function(){
			this.$router.back();
		} ,
		next : function(){
			var that = this ;
			console.log( that );
			if( !that.promise ) {
				that.$toast("请填写狗粮" , 1500 , {
					'top' : '50%'
				}) ;
				return false ;
			}
			if( false === that.onLine ) {
				that.$toast("当前用户不在线，请换个好友试试" , 1500 , {
					'top' : '50%'
				}) ;
				return false ;	
			}
			Axios.put('/bigsmall/invite' , {
				'promise' : that.promise ,
				'_token' : window._Token ,
				'api_token' : window.apiToken ,
				'invite_user' : that.$route.params.id ,
			} , {
				responseType : 'json'
			}).then(function( ret ){
				console.log( ret );
				that.$toast( ret.data.msg );
				if( ret.data.errcode === 0 ) {
					//战斗成功 回到发送信息的页面
					setTimeout( function(){
						that.$router.go(-1);
					} , 1500 );
				}
				
			} , function( ret ){
				console.log( ret );
			});


			
		}
	} ,
	mounted : function(){
		console.log( this.$route )
	}
}

</script>

<style scoped >
.bottom-fixed {
	position: absolute;
    right: 0;
    left: 0;
    z-index: 10;
    height: 2.2rem;
    padding-right: 0.5rem;
    padding-left: 0.5rem;
    -webkit-backface-visibility: hidden;
    backface-visibility: hidden;
    bottom: 0;
    width: 100%;
    padding: 0;
    table-layout: fixed;
    background: #fff;
}
.next-button {
	border-radius: 1.25rem;
	text-decoration: none;
    text-align: center;
    display: block;
    border-radius: 0.25rem;
    line-height: 2.25rem;
    box-sizing: border-box;
    -webkit-appearance: none;
    -moz-appearance: none;
    -ms-appearance: none;
    appearance: none;
    background: none;
    padding: 0 0.5rem;
    margin: 0;
    height: 2.2rem;
    white-space: nowrap;
    position: relative;
    text-overflow: ellipsis;
    font-size: 0.8rem;
    font-family: inherit;
    cursor: pointer;
    color: #fff;
	background: #734d41;
	border: none;
}
.tip {
	background: #ffdeab;
    margin: .5rem;
    border-radius: .2rem;
    padding: .2rem 1rem;
    color: red;
    font-size: .9rem;
    text-align: center;
    font-weight: 400;
    line-height: 1.8rem;
}
.tip p {
	margin:0;
}
</style>