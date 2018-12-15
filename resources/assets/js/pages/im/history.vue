<template id="Index">
	<div class="page">
		<header class="bar bar-nav">
            <h1 class="title">最近联系</h1>
            <router-link :to="{name:'chat-search-friend'}" class="button button-link button-nav pull-right back" data-transition='slide-out'>
                <span class="icon icon-search"></span>
                加好友
            </router-link>
        </header>
        <menu-nav index="index"></menu-nav>
        <foot-bar select="chat"></foot-bar>
        <div class="content">
			<div class="content-block" style="margin:0rem;padding:0rem;">
				<router-link tag="div" :to="{ name: 'im-chat' , params:{ user_id : item.sender_id == 0 ? 0 : (user_id != item.receiver_id ? item.receiver_id : item.sender_id ) } }" class="card" v-for="item in friends" >
				    <div class="card-content">
				      <div class="list-block media-list">
				        <ul>
				          <li class="item-content">
				            <div class="item-media img-round">
				            	<img :src="getAvatar( item.sender && user_id != item.sender.id  ? item.sender.avatar : item.receiver.avatar )" width="44">
				            </div>
				            <div class="item-inner">
				              <div class="item-title-row">
				                <div class="item-title" style="width:100%">
				                	{{ item.sender_id == 0 ? '系统消息' : ( user_id != item.sender_id ? item.sender.nickname : item.receiver.nickname ) }}
				                	<span class="badge pull-right" v-if="item.isNew">{{item.isNew || 0}}</span>
				                </div>
				              </div>
				              <div class="item-subtitle" v-if="'txt' == item.type">{{item.body.data}}</div>
				              <div class="item-subtitle" v-if="'addfriend' == item.type">{{item.body.data}}</div>
				              <div class="item-subtitle" v-if="'invite_bigsmall' == item.type" v-html="makeGameTip( item )">
				          	  </div>
				              <div class="item-subtitle" v-if="'image' == item.type">[图片]</div>
				            </div>
				          </li>
				        </ul>
				      </div>
				    </div>
				</router-link>
				<infinite-loading @infinite="loadMore" ref="infiniteLoading">
					<span slot="no-more"></span>
					<span slot="no-result"></span>
				</infinite-loading> 
			</div>
		</div>

	</div>
	
</template>



<script>
import VueLoop from 'vue-loop'
import history from "../../components/history.vue" ;
import menu from '../../components/menuNav' ;
import footBar from '../../components/footNav'
import InfiniteLoading from 'vue-infinite-loading' ;
import Axios from 'axios' ;
export default {
	data :function(){
		return {
			page : 1 ,
			user_id:0,
			'friends' : [
			] 
		}
	} ,
	created:function(){
		console.log('load index');
	} ,
	mounted:function(){
		let that = this
		window.conn.listen({
			onTextMessage:function( message ){
				//收到文本消息
				console.log( message )
				console.log( 'txt' )
				message.type = 'txt' ;
				tipAudio.play()
				console.log( message )
				if( message.to == imUser ) {
					that.messages.push( message );	
				}
				for( var i in that.friends ) {
					if( that.friends[i].receiver.name == message.from || that.friends[i].sender.name == message.from ) {
						//如果 是相同的则给信息修改一下

					}
				}
				console.log( message );
			},
			onCmdMessage:function( message ){
				tipAudio.play()
				console.log('cmd')
				console.log( message );
				let friends = that.friends
				for( var i in friends ) {
					if( friends[i].receiver.name == message.from || friends[i].sender.name == message.from ) {
						//如果 是相同的则给信息修改一下
						if( message.ext.action == 'invite_bigsmall') {
							var msg = {
						    	'data' : message.action ,
						    	'type' : 'invite_bigsmall' ,
						    	'to' : message.to ,
						    	'from' : message.from ,
						    	'id' : message.ext.event_id ,
						    	'msg_id' : message.ext.event_id
						    } ;
						    friends[i].body.data = msg.data 
						    friends[i].type = msg.type 
						    friends[i].isNew++
						    that.friends = friends ;
						} else {
							var msg = {
						    	'data' : message.ext.worth ,
						    	'type' : 'game' ,
						    	'to' : message.to ,
						    	'from' : message.from ,
						    	'id' : message.id ,
						    	'msg_id' : message.ext.event_id
						    } ;	
						}


					}
				}
				console.log( that.friends )
			},
			//当消息发送到服务器
			onReceivedMessage:function( message ){
				
			} ,
			onPictureMessage : function( message ){
				console.log( message );
				tipAudio.play()
				message.type = 'image' ;
				if( message.to == imUser ) {
					that.messages.push( message );	
				}
			}

		});
	},
	methods:{
		loadMore : function( $state ){
			let that = this ;
			Axios.get('/im/recent' +
				'?_token=' + window._Token  + 
				'&api_token=' + window.apiToken + 
				'&page=' + that.page 
			, {
				responseType : 'json'
			}).then(function( ret ){
				$state.loaded();
				if( ret.data.errcode === 0 ) {
					//邀请成功 回到发送信息的页面
					console.log( ret.data );
					that.user_id = ret.data.user.id 
					let friends = [] 
					for( var i in ret.data.data.data ) {
						ret.data.data.data[i].isNew = 0 
						friends.push( ret.data.data.data[i])
					}
					that.friends = friends ;
					if( ret.data.data.last_page >= that.page ) {
						$state.complete();
					} else {
						that.page++ ;
					}
				}
				
			} , function( ret ){
				console.log( ret );
			});
		} ,
		getName:function( item ){
			return item.sender_id == 0 ? '系统消息' : ( this.user_id != item.sender_id ? item.sender.nickname : item.receiver.nickname )
			return item.alias_name ? item.alias_name : ( item.friend.nickname  || '狗运' ) ;
		} ,
		getAvatar:function( v ){
			if( !v ) {
				return '/images/logo.png' ;
			}
			return '/' + v ;
		} ,
		makeGameTip : function( item ) {
			let that = this
			var html = "";
			if( that.user_id == item.sender_id ) {
				//如果我是发起者
				html = "您向" + that.getName( item ) + "发起了比大小战斗，等待他接受战斗！";
			} else {
				//如果是接受者
				html = item.body.data + "向您发起了比大小战斗，是否接受战斗？";
			}
			return html  ;
		}
	},
	components: {
		'friend':history ,
		'menu-nav' : menu ,
		'foot-bar' : footBar ,
		InfiniteLoading
	}
}

</script>

<style scoped>
.badge {
	background: red;
	color:white;
}
</style>
