<template id="big_small_detail">
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
        <nav class="bar bar-tab" v-if="!is_inviter() && waiting()">
        	<span class="tab-item" @click="refuse()">
          		<span class="tab-label">不接受</span>
        	</span>
        	<span class="tab-item" @click="accept()">
          		<span class="tab-label">接受邀请</span>
        	</span>
        </nav>
        <nav class="bar bar-tab" v-if="is_inviter() && waiting()">
        	<span class="tab-item" @click="cancelInvite()">
          		<span class="tab-label">取消邀请</span>
        	</span>
        </nav>
        <nav class="bar bar-tab" v-if="hasresult() && win() && game.status == 1">
        	<span class="tab-item" @click="reback()">
          		<span class="tab-label">退还狗粮</span>
        	</span>
        </nav>
        <div class="content" >
	        <div class="row-result" v-if="hasresult()">
	        	<div class="col-left">
	        		<img :src="user_num_img()"  />
	        	</div>
	        	<div class="col-mid">
	        		<img src="/images/vs.png" />
	        	</div>
	        	<div class="col-right">
	        		<img :src="inviter_num_img()" />
	        	</div>

	        </div>
	        <div class="row-result" v-else>
	        	<div class="col-puzzle">
	        		<img src="/images/13.png" style="height:6rem;" />
	        	</div>
	        </div>

	        <div class="row">
	        	<div class="col-33">
	        		<div class="inviter">
	        			<img :src="getInviterAvatar()" class="avatar inviter-img">
	        			<a >{{getInviterName()}}</a>
	        			<span class="crown" v-if="inviterWin()"></span>
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
	        			<img :src="getInvitedAvatar()" class="avatar me-img">
	        			<a >{{getInvitedName()}}</a>
	        			<span class="crown" v-if="invitedWin()"></span>
	        		</div>
	        	</div>
	        </div>


	        <div class="content-block info" v-if="is_inviter()">
	        	<p v-if="hasresult() && win() && game.status == 1">
	        		恭喜您获得胜利！！！
	        		<br/>去和他聊天联系退还狗粮吧。
	        		<br/>对方电话是{{user.mobile}}
	        	</p>
	        	<p v-if="hasresult() && lose() && game.status == 1">
	        		很遗憾您输掉了战斗。<br/>
	        		对方支付宝:{{alipay}}
	        	</p>
	        	<p v-if="waiting()">
	        		等待对方接受战斗。
	        	</p>
	        	<p v-if="reject()">
	        		对方拒绝了战斗。
	        	</p>
	        	<p v-if="game.status == 2 && win()">
	        		您退还了狗粮!
	        	</p>
	        	<p v-if="game.status == 2 && lose()">
	        		对方退还了狗粮!
	        	</p>
	        	<p v-if="game.status == 4">
	        		您撤销了战斗
	        	</p>
	        	<p v-if="game.status == 6">
	        		系统撤消战斗!
	        	</p>
	        	<p v-if="game.status == 5 && win()">
	        		系统退对方狗粮!
	        	</p>
	        	<p v-if="game.status == 5 && lose()">
	        		系统退我狗粮!
	        	</p>
	        </div>
	        <div class="content-block info" v-else>
	        	<p v-if="hasresult() && win() && game.status == 1">
	        		恭喜您获得胜利！！！
	        		<br/>去和他聊天联系退还狗粮吧。
	        		<br/>对方电话是{{inviter.mobile}}
	        	</p>
	        	<p v-if="hasresult() && lose() && game.status == 1">
	        		很遗憾您输掉了战斗。<br/>
	        		对方支付宝:{{alipay}}
	        	</p>
	        	<p v-if="waiting()">
	        		准备好接受战斗了吗？
	        	</p>
	        	<p v-if="reject()">
	        		您拒绝了战斗。
	        	</p>
	        	<p v-if="game.status == 2 && win()">
	        		您退还了狗粮!
	        	</p>
	        	<p v-if="game.status == 2 && lose()">
	        		对方退还了狗粮!
	        	</p>
	        	<p v-if="game.status == 4">
	        		对方撤销战斗
	        	</p>
	        	<p v-if="game.status == 6">
	        		系统撤消战斗!
	        	</p>
	        	<p v-if="game.status == 5 && win()">
	        		系统退对方狗粮!
	        	</p>
	        	<p v-if="game.status == 5 && lose()">
	        		系统退我狗粮!
	        	</p>
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
			defeat:'' ,
			alipay:'',
			desc : '' ,
			min : 0 ,
			max : 0 ,
			promise: 0 ,
			s_ok : false ,
			game : {} ,
			user : {} ,
			inviter:{} ,
			invited:{} ,
		}
	} ,
	created : function(){
		var that = this ;
		console.log( that.route );
		Axios.get('/bigsmall/detail/' + that.$route.params.id  + 
			'?_token='+  window._Token + 
			'&api_token=' + window.apiToken
		).then(function(response) {
			console.log( that );
			that.promise = response.data.game.cash_deposit ;
			that.game = response.data.game ;
			that.user = response.data.user ;
			that.inviter = response.data.game.user ;
			that.invited = response.data.game.inviter ;
			if( that.game.user_num && that.game.inviter_num  ) {
				//如果不等于0 则说明这个页面已经发生了变化则去到详情页
				//that.$router.push({'name': 'bigsmalldetail' , params: that.$route.params });
				console.log('log');
				if( that.game.user_id == that.user.id ) {
					//发起者
					that.defeat = that.game.inviter.mobile 
					that.alipay = that.game.user.alipay_account
				} else {
					that.defeat = that.game.user.mobile 
					that.alipay = that.game.inviter.alipay_account
				}
			}
		});
	} ,
	methods : {
		back : function(){
			this.$router.back();
		} ,
		getInviterAvatar:function(){
			return this.inviter['avatar'] ? '/' + this.inviter['avatar'] : '/images/logo.png' ;
		},
		getInvitedAvatar:function( ){
			return this.invited['avatar'] ? '/' + this.invited['avatar'] : '/images/logo.png' ;
		} ,
		getInvitedName:function( ) {
			return this.invited.nickname ? this.invited.nickname : '狗运' ;
		},
		getInviterName:function(){
			return this.inviter.nickname ? this.inviter.nickname : '狗运' ;	
		},
		inviterWin:function(){
			return this.game.user_num && this.game.inviter_num && this.game.user_num > this.game.inviter_num ;
		},
		invitedWin:function(){
			return this.game.user_num && this.game.inviter_num && this.game.user_num < this.game.inviter_num ;
		},
		is_inviter:function(){
			return this.game.user_id == this.user.id
		},
		user_num_img :function(){
			return '/images/'+ this.game.user_num +'.png'
		},
		inviter_num_img :function(){
			return '/images/'+ this.game.inviter_num +'.png'
		},
		win:function(){
			if( this.game.user_id == this.user.id ) {
				//发起者
				this.defeat = this.game.inviter.mobile 
				this.alipay = this.game.user.alipay_account
				return this.game.user_num > this.game.inviter_num ;
			} else {
				this.defeat = this.game.user.mobile 
				this.alipay = this.game.inviter.alipay_account
				return this.game.user_num < this.game.inviter_num ;
			}
			
		},
		lose:function(){
			if( this.game.user_id == this.user.id ) {
				//发起者
				this.defeat = this.game.user.mobile 
				this.alipay = this.game.inviter.alipay_account
				return this.game.user_num < this.game.inviter_num ;
			} else {
				this.defeat = this.game.inviter.mobile 
				this.alipay = this.game.user.alipay_account
				return this.game.user_num > this.game.inviter_num ;
			}
		},
		waiting:function(){
			if( this.game.status == 0 ) {
				return true ;
			}
			return false ;
		} ,
		reject:function(){
			if( this.game.status == 3 ) {
				return true ;
			}
			return false ;
		} ,
		gameover:function(){
			if( this.game.status == 4 ) {
				return true ;
			}
			return false ;
		},
		hasresult:function(){
			console.log( this.game )
			return this.game.user_num && this.game.inviter_num  ;
		},
		cancelInvite:function(){
			let that = this
			Axios.put('/bigsmall/cancel/' + that.$route.params.id , {
				'_token' : window._Token ,
				'api_token' : window.apiToken ,
				'id' : that.$route.params.id ,
			} , {
				responseType : 'json'
			}).then(function( ret ){
				console.log( ret );
				let data = ret.data
				if( ret.data.errcode === 0 ) {
					//邀请成功 回到发送信息的页面
					that.game = data.game 
				}
				
			} , function( ret ){
				console.log( ret );
			})
		},
		accept : function(){
			let that = this
			Axios.put('/bigsmall/accept/' + that.$route.params.id , {
				'_token' : window._Token ,
				'api_token' : window.apiToken ,
				'id' : that.$route.params.id ,
			} , {
				responseType : 'json'
			}).then(function( ret ){
				console.log( ret );
				let data = ret.data
				if( ret.data.errcode === 0 ) {
					//邀请成功 回到发送信息的页面
					that.game = data.game 
					if( that.game.inviter_num > that.game.user_num ) {
						that.$toast("恭喜您获得胜利！！！")
					} else {
						that.$toast("很遗憾您输掉了战斗！！！")
					}
					/**
					setTimeout( function(){
						that.$router.go(-1);
					} , 1500 );
					**/
				}else if( ret.data.errcode == 10006 ) {
					that.$toast( data.msg )
					setTimeout( function(){
						that.$router.push({
							name : 'baseinfo'
						})
					} , 1500 );
				}
				
			} , function( ret ){
				console.log( ret );
			})
		} ,
		refuse:function(){
			let that = this
			Axios.put('/bigsmall/reject/' + that.$route.params.id , {
				'_token' : window._Token ,
				'api_token' : window.apiToken ,
				'id' : that.$route.params.id ,
			} , {
				responseType : 'json'
			}).then(function( ret ){
				console.log( ret );
				let data = ret.data
				that.$toast( data.msg )
				if( ret.data.errcode === 0 ) {
					//邀请成功 回到发送信息的页面
					that.game = data.game 
					/**
					setTimeout( function(){
						that.$router.go(-1);
					} , 1500 );
					**/
				}
				
			} , function( ret ){
				console.log( ret );
			})
		} ,
		reback:function(){
			let that = this
			Axios.put('/bigsmall/reback/' + that.$route.params.id , {
				'_token' : window._Token ,
				'api_token' : window.apiToken ,
				'id' : that.$route.params.id ,
			} , {
				responseType : 'json'
			}).then(function( ret ){
				console.log( ret );
				let data = ret.data
				that.$toast( data.msg )
				if( ret.data.errcode === 0 ) {
					//邀请成功 回到发送信息的页面
					that.game = data.game 
					/**
					setTimeout( function(){
						that.$router.go(-1);
					} , 1500 );
					**/
				}
				
			} , function( ret ){
				console.log( ret );
			})
		}
	} ,
	mounted : function(){
		console.log( this.$route )
	}
};

</script>

<style scoped>
.page {
	background: #fff;
}
.row-result {
	overflow: hidden;
	margin-bottom: 1.5rem;
	padding-top:2rem;
}
.col-left,.col-right {
	width: 45%;
	text-align: center;
	box-sizing: border-box;
  float: left;
}
.col-left {
	text-align: right;
	padding-right:1rem;
}
.col-right {
	text-align: left;
	padding-left:1rem;
}
.col-left img,.col-right img {
	width: 3.5rem;
	border-radius: .3rem;
}
.col-mid {
	width: 10%;
	box-sizing: border-box;
    float: left;
}
.col-mid img {
	width: 100%;
	margin-top:1.5rem;
}
.col-puzzle {
	width: 100%;
	text-align: center;
}

.inviter,.me {
	width:3rem;
	margin: 0 auto;
	text-align: center;
	position: relative;
	padding-top:.5rem;
}
.inviter .crown , .me .crown {
	width: 1.5rem;
    height: 1rem;
    top: .2rem;
    position: absolute;
    left: .75rem;
}
.to-pk {
    height: 1.3rem;
    line-height: 1rem;
    text-align: center;
    border-bottom: #724c41 solid 1px;
    color: #ffa500e0;
    font-size: 1rem;
    margin-top: 1.2rem;
    width: 70%;
    margin-left: auto;
    margin-right: auto;
}
.to-money {
    text-align: center;
    height: 1.2rem;
    line-height: 1.2rem;
    font-size: .95rem;
    color: red;
}
.avatar {
	width: 100%;
	border-radius: 50%;
	border:solid 1px #dfdfdf;
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
.info p {
	text-align: center ;
	font-size: .75rem;
	color:#41454a;
}
</style>
