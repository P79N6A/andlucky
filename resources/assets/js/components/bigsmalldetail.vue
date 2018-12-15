<template id="big_small_detail">
	<div class="page">
		<header class="bar bar-nav">
            <a v-on:click="back()" class="button button-link button-nav pull-left back" data-transition='slide-out'>
                    <span class="icon icon-left"></span>
                    返回
            </a>
            <h1 class="title">比大小</h1>
        </header>
        <div class="content infinite-scroll infinite-scroll-bottom" data-distance="50" >
	        <div class="content-block text-center" >
	        	
	        </div>
	        <div class="row-result" v-if="hasresult()">
	        	<div class="col-left">
	        		<img src="/images/7.png" />
	        	</div>
	        	<div class="col-mid">
	        		<img src="/images/vs.png" />
	        	</div>
	        	<div class="col-right">
	        		<img src="/images/7.png" />
	        	</div>

	        </div>
	        <div class="row-result" v-else>
	        	<div class="col-puzzle">
	        		<img src="/images/puzzle.png" />
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
	        <div class="content-block info">
	        	<p v-if="win()">
	        		恭喜您获得胜利！！！
	        	</p>
	        	<p v-if="lose()">
	        		很遗憾您输掉了游戏。
	        	</p>
	        	<p v-if="waiting()">
	        		等待对方接受战斗。
	        	</p>
	        	<p v-if="reject()">
	        		对方拒绝了PK邀请,游戏结束。
	        	</p>
	        	<p v-if="gameover()">
	        		游戏结束!
	        	</p>
	        </div>
			<div class="content-block" v-if="s_ok">
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
			promise: 0 ,
			s_ok : false ,
			game : {} ,
			user : {} ,
			inviter:{} ,
			invited:{} 
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
			if( response.data.game.status == 1 && response.data.is_inviter ) {
				//如果不等于0 则说明这个页面已经发生了变化则去到详情页
				//that.$router.push({'name': 'bigsmalldetail' , params: that.$route.params });
				console.log('log');
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
		win:function(){
			if( this.game.status != 1 ) {
				return false ;
			}
			if( this.game.user_id == user.id ) {
				//发起者
				return this.game.user_num > this.game.inviter_num ;
			} else {
				return this.game.user_num < this.game.inviter_num ;
			}
			
		},
		lose:function(){
			if( this.game.status != 1 ) {
				return false ;
			}
			if( this.game.user_id == user.id ) {
				//发起者
				return this.game.user_num < this.game.inviter_num ;
			} else {
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
			var result = this.game.user_num && this.game.inviter_num  ;
		},
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
.row-result {
	overflow: hidden;
	margin-bottom: 1.5rem;
}
.col-left,.col-right {
	width: 45%;
	text-align: center;
	box-sizing: border-box;
    float: left;
}
.col-left img , .col-right img {
	width: 5rem;
}
.col-mid {
	width: 10%;
	box-sizing: border-box;
    float: left;
}
.col-mid img {
	width: 100%;
	margin-top:3.5rem;
}
.col-puzzle {
	width: 100%;
	text-align: center;
}

.inviter , .me {
	width:4rem;
	margin: 0 auto;
	text-align: center;
	position: relative;
	padding-top:1.5rem;
}
.inviter .crown , .me .crown {
	width:4rem;
	height: 2rem;
	top: 0rem;
	position: absolute;
}
.to-pk {
	height: 2.6rem;
	line-height: 2rem;
	text-align: center;
	border-bottom: #724c41 solid 3px;
    color: #ffa500e0;
    font-size: 1.5rem;
    margin-top:1.0rem;
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
.info p {
	text-align: center ;
	font-size: 1rem;
	color:#41454a;
}
</style>
