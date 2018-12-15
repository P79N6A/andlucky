<template id="big_small">
	<div class="page">
		<header class="bar bar-nav">
            <a v-on:click="back()" class="button button-link button-nav pull-left back" data-transition='slide-out'>
                    <span class="icon icon-left"></span>
                    返回
            </a>
            <h1 class="title">比较大小邀请</h1>
        </header>
        <div class="content infinite-scroll infinite-scroll-bottom" data-distance="50" >
	        <div class="content-block text-center" >
	        	<img src="/images/poke.png" />
	        </div>
	        <div class="row">
	        	<div class="col-33">
	        		<div class="inviter">
	        			<img src="http://wx.qlogo.cn/mmopen/Q3auHgzwzM7hq8e7Q0FLXO7ms14YDKFfrZ9Pd5bP7GUSqxxtZsQF27MxsHRIHLvIDnTAONnoVic3lGGBTdia14vA/0" data-account="ly_00000001" class="avatar inviter-img">
	        			<a >无悔</a>
	        		</div>
	        	</div>
	        	<div class="col-33">
	        		<div class="to-pk">
	        			VS
	        		</div>
	        		<div class="to-money">
	        			{{promise}}
	        		</div>
	        	</div>
	        	<div class="col-33">
	        		<div class="me">
	        			<img src="http://wx.qlogo.cn/mmopen/Q3auHgzwzM7hq8e7Q0FLXO7ms14YDKFfrZ9Pd5bP7GUSqxxtZsQF27MxsHRIHLvIDnTAONnoVic3lGGBTdia14vA/0" data-account="ly_00000001" class="avatar me-img">
	        			<a >机器人</a>
	        		</div>
	        	</div>

	        </div>
			<div class="content-block">
				<div class="row">
					<div class="col-50">
						<div class="button button-danger button-fill button-big" v-on:click="reject()">不接受</div>
					</div>
					<div class="col-50">
						<div class="button button-success button-fill button-big" v-on:click="accpet()">接受邀请</div>
					</div>
				</div>
			</div>
			<div class="content-block" >
				<p v-html="desc"></p>
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
			promise: 0
		}
	} ,
	created : function(){
		var that = this ;
		console.log( that.route );
		Axios.get('/bigsmall/invited/' + that.$route.params.id  + 
			'?_token='+  window._Token + 
			'&api_token=' + window.apiToken
		).then(function(response) {
			console.log( that );
			that.desc = response.data.desc.replace(/\r\n/ , '<br/>') ;
			that.min = response.data.min ;
			that.max = response.data.max ;
			that.promise = response.data.game.cash_deposit ;
			console.log( response );
			if( response.data.game.status != 0 ) {
				//如果不等于0 则说明这个页面已经发生了变化则去到详情页
				that.$router.push({'name': 'bigsmalldetail' , params: that.$route.params });
			}
		});
	} ,
	methods : {
		back : function(){
			this.$router.back();
		} ,
		reject:function(){
			var that = this ;
			Axios.put('/bigsmall/reject/' + that.$route.params.id , {
				'_token' : window._Token ,
				'api_token' : window.apiToken ,
				'id' : that.$route.params.id ,
			} , {
				responseType : 'json'
			}).then(function( ret ){
				console.log( ret );
				that.$toast( ret.data.msg );
				if( ret.data.errcode === 0 ) {
					//邀请成功 回到发送信息的页面
					setTimeout( function(){
						that.$router.go(-1);
					} , 1500 );
				}
				
			} , function( ret ){
				console.log( ret );
			});
		} ,
		accept : function(){
			Axios.put('/bigsmall/accpet/' + that.$route.params.id , {
				'_token' : window._Token ,
				'api_token' : window.apiToken ,
				'id' : that.$route.params.id ,
			} , {
				responseType : 'json'
			}).then(function( ret ){
				console.log( ret );
				that.$toast( ret.data.msg );
				if( ret.data.errcode === 0 ) {
					//邀请成功 回到发送信息的页面
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

<style>
.page {
	background: #fff;
}
.inviter , .me {
	width:4rem;
	margin: 0 auto;
	text-align: center;
}
.to-pk {
	height: 2.6rem;
	line-height: 2rem;
	text-align: center;
	border-bottom: #724c41 solid 3px;
    color: #ffa500e0;
    font-size: 1.5rem;
}
.to-money {
	text-align: center;
	height: 2rem;
    line-height: 2rem;
    font-size: 1.25rem;
    color: red;
}
.avatar {
	width: 100%;
	border-radius: 50%;
}
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
</style>