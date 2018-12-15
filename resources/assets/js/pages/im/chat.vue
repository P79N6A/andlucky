<template id="Chat">
	<div class="page">
		<header class="bar bar-nav">
            
            <a class="button button-link button-nav pull-left" @click="$router.go(-1)" >
                <span class="icon icon-left"></span>
                返回
            </a>
            <h1 class="title">{{toUserName}}</h1>
        </header>
        <nav class="bar bar-tab" v-if="toUserId">
			<div class="feed-cont" v-bind:class="[ panelShow ? 'panel-show' : 'panel-hide' ]" ref="panel">
				<div class="flex">
					<input class="feed-txt" v-model="message" v-on:keyup.13="sendMsg" v-on:focus="scrollKeyBoard" autocomplete="off" type="text" value="{message}">
					<div class="feed-btn" v-on:click="sendMsg()">发送</div>
					<div class="add-more" v-on:click="openPanel()"> + </div>

				</div>
				<div class="more-menu flex">
					<div class="more-item cursor" id="chooseFileBtn">
						<span class="img-area">
							<input type="file" id="image" @change="sendPrivateImg" class="img-input-file" />
							<div class="item-ico img-area-icon">
								<i class="iconfont icon-tupian"></i>
							</div>
						</span>
						图片
					</div>
					<!--
					<div class="more-item cursor">
						<div class="item-ico">
							<i class="iconfont icon-Voice"></i>
						</div>
						语音输入
					</div>
					-->
					<div class="more-item cursor" @click="bigOrSmall()">
						<div class="item-ico">
							<i class="iconfont icon-tuxing"></i>
						</div>
						比大小
					</div>
					<!--
					<div class="more-item cursor" v-on:click="stolen()">
						<div class="item-ico">
							<i class="iconfont icon-toubaoxiang"></i>
						</div>
						摸一把
					</div>
					-->
				</div>
				<div class="voice-layer">
					按住说话
					<div class="v-close" onclick="closeVoice()"><i class="icon iconfont icon-down-trangle"></i></div>
					<div class="voice-ico cursor" id="voiceBtn">
						<i class="icon iconfont icon-iconset0221"></i>
					</div>
				</div>
			</div>

        </nav>
        <!--
        <span class="pin-game" @click="bigOrSmall">
        	<div class="item-ico">
				<i class="iconfont icon-tuxing"></i>
			</div>
			比大小
        </span>
    	-->
        <div class="content" ref="chatcontent">
			<div class="content-block chat-content" ref="chatbox" style="margin:0rem;padding:0rem .25rem;">
				<!--
					<p class="u-msgTime">- - - - -&nbsp;16:31&nbsp;- -- - -</p>
				-->
				<div
					v-for="item in messages"
					:data-id="item.id" 
					class="item" 
					:class="{'item-me' : toUserId > 0 && item.to != toUser.name , 'item-you': toUserId  == 0 || item.to == toUser.name }"

				>
					

					<img 
						class="img j-img" 
						v-if="toUserId > 0 &&  item.to == toUser.name"
						:src="avatar( fromUser ? fromUser.avatar : '' )" 
					/>
					<img 
						class="img j-img" 
						v-else
						:src="avatar( toUser ? toUser.avatar :'' )" 
					/>

					<div v-if="item.type =='txt' " class="msg msg-text j-msg">
						<div class="box">
							<div class="cnt">
								<div class="f-maxWid">{{item.data}}</div>
							</div>
						</div>
					</div>
					<div v-if="item.type =='addfriend' " class="msg msg-text j-msg">
						<div class="box">
							<div class="cnt">
								<div class="f-maxWid">{{item.data}}</div>
							</div>
							<div class="cnt">
								<div class="f-maxWid" style="display:flex; font-size:.75rem;">
									<a class="button button-fill button-danger" @click="agree( item.msg_id )">同意</a>&nbsp;&nbsp;
									<a class="button button-fill" @click="disagree( item.msg_id )">不同意</a>

								</div>
							</div>
						</div>
					</div>
					<div v-if="item.type =='invite_bigsmall' " class="msg msg-text j-msg">
						
						<!-- 我收到的  -->
						<div class="box" v-if="item.to != toUser.name">
							<div class="card">
								<div class="card-header">应用消息：[比大小]</div>
								<div class="card-content">
									<div class="card-content-inner">
										{{item.data}}向您发起了比大小战斗
									</div>
								</div>
								<div class="card-footer">
									<router-link :to="{name:'bigsmall-detail' , params:{ id : item.msg_id }}">查看详情</router-link>
								</div>
							</div>
						</div>
						<div class="box" v-else>
							<div class="card">
								<div class="card-header">应用消息：[比大小]</div>
								<div class="card-content">
									<div class="card-content-inner">
										您向{{getName( item ) }}发起了比大小战斗
									</div>
								</div>
								<div class="card-footer">
									<router-link :to="{name:'bigsmall-detail' , params:{ id : item.msg_id }}">查看详情</router-link>
								</div>
							</div>
						</div>
					</div>
					<div v-if="item.type == 'image'" class="msg msg-text j-msg">
						<div class="box">
							<div class="cnt">
									<a href="javascript:void(0)">
										<img :src="item.url" >
									</a>
							</div>
						</div>
					</div>

					<span class="readMsg"><i></i>已读</span>
				</div>
			</div>
		</div>
		<div class="modal-overlay" :class="{'modal-overlay-visible': showOverlay }"></div>
	    <div class="actions-modal" :class="{'modal-in':channelShow , 'modal-out': !channelShow , }">
	    </div>
	    <bigsmall-modal v-on:closeModal="closeModal" :to="to"></bigsmall-modal>
	</div>

</template>



<script>
import Axios from 'axios' ;
import Vue from "vue" ;
import VueLazyload from 'vue-lazyload' ;
import VueChatScroll from 'vue-chat-scroll' ;
import bigSmallModal from '../../components/bigsmall'
Vue.use(VueChatScroll) ;
Vue.use( VueLazyload );
export default {
	data :function(){
		return {
			showOverlay : false ,
			channelShow : false ,
			panelShow : false ,
			message : '' ,
			messages:[] ,
			toUser : {} ,
			toUserName : '' ,
			toUserId : 0 ,
			fromUser:{} ,
			queueMsg:{},
			to:0 ,
			chatboxHeight : 0 ,
			timer:null
		}
	} ,
	created : function(){
		this.fetchData()
		
	} ,
	watch:{
		'$route' : function(){
			this.fetchData()
		}
	} ,
	mounted:function(){
		let that = this ;
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
				console.log( message );
			},
			onCmdMessage:function( message ){
				console.log( message );
				console.log('cmd')
				tipAudio.play()
				if( message.ext.action == 'invite_bigsmall') {
					var msg = {
				    	'data' : message.action ,
				    	'type' : 'invite_bigsmall' ,
				    	'to' : message.to ,
				    	'from' : message.from ,
				    	'id' : message.ext.event_id ,
				    	'msg_id' : message.ext.event_id
				    } ;
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
				
			    console.log( msg );
			    if( msg.to = imUser ) {
			    	that.messages.push( msg );	
			    }
				//that.messages.push( message );
				
			},
			//当消息发送到服务器
			onReceivedMessage:function( message ){
				
			} ,
			onPictureMessage : function( message ){
				tipAudio.play()
				console.log( message );
				message.type = 'image' ;
				if( message.to == imUser ) {
					that.messages.push( message );	
				}
			}

		});
		setInterval( function(){
			if( that.$refs.chatcontent ) {
				if( that.chatboxHeight != that.$refs.chatbox.clientHeight ) {
					that.chatboxHeight = that.$refs.chatbox.clientHeight ;
					that.$refs.chatcontent.scrollTop = that.chatboxHeight ;

				}
			}
		} , 1000 );
	},
	methods : {
		scrollKeyBoard:function(){
			let that = this
			console.log('scroll')
			clearInterval( that.timer );
			var index = 0 ;
			that.timer = setInterval( function(){
				if( index > 8 && that.$refs.chatcontent ) {
					console.log( that.$refs.chatcontent )
					that.$refs.chatcontent.scrollTop = 1000000 ;
					that.$refs.chatbox.scrollTop =  1000000 ;
					document.body.scrollTop = 100000000 ;
					clearInterval( that.timer );
				} else {
					clearInterval( that.timer );
				}
				index++ 
			} , 50 );

		},
		fetchData:function(){
			let that = this ;
			that.messages = []
			that.toUserId = parseInt( that.$route.params.user_id )
			if( that.toUserId == 0 ) {
				//return 
			}
			Axios.get('/im/userinfo/' + this.$route.params.user_id  + 
				'?_token=' + window._Token + 
				'&api_token=' + window.apiToken
			, {
				responseType : 'json'
			} ).then(function( res ){
				that.toUser = res.data.data ;
				that.toUserName = that.toUser.nickname || '狗运'
			});

			Axios.get('/member' + 
				'?_token='+  window._Token + 
				'&api_token=' + window.apiToken 
			).then(function(response) {
				//console.log( that );
				let data = response.data 
				console.log( data )
				//that.user = data.data.user 
				that.fromUser = data.data.user 
			});

			Axios.get('/im/history/' + this.$route.params.user_id  + 
				'?_token=' + window._Token + 
				'&api_token=' + window.apiToken
			, {
				responseType : 'json'
			} ).then(function( res ){
				if( res.data.data ) {
					let list = res.data.data.data ;
					for( var i = list.length - 1 ; i >= 0 ; i-- ) {
						that.messages.push({
					    	'data' : list[i].body.data ,
					    	'url' : list[i].body.url ,
					    	'type' : list[i].type ,
					    	'to' : list[i].receiver ? list[i].receiver.name : '' ,
					    	'from' : list[i].sender ? list[i].sender.name : '系统消息' ,
					    	'id' : list[i].id ,
					    	'msg_id' : list[i].msg_id
					    });
					}
				}
			});
		},
		getName:function( item ){
			console.log( item )
			console.log( this.toUser )
			return item.to == this.toUser.name ? this.toUser.nickname : this.fromUser.nickname ;
		} ,
		avatar :function( v ){
			return v ? v : '/images/logo.png' 
		},
		chatlog : function( user_id ) {

		} ,
		openPanel : function( ) {
			this.panelShow = !this.panelShow ;
		} ,
		sendMsg : function() {
			let that = this ;
			if( !this.message ) {
				return false ;
			}
			var id = window.conn.getUniqueId();                 // 生成本地消息id
		    var msg = new WebIM.message('txt', id);      // 创建文本消息
		    msg.set({
		        msg:  that.message ,                  // 消息内容
		        to:  that.toUser.name ,                          // 接收消息对象（用户id）
		        roomType: false,
		        success: function (id, serverMsgId) {
		            var message = {
				    	'data' : msg.value ,
				    	'type' : 'txt' ,
				    	'to' : that.toUser.name ,
				    	'from' : window.imUser ,
				    	'id' : serverMsgId
				    } ;
		            that.messages.push( message );
				  	//同时保存到服务顺
					Axios.post('/im/record' , {
						'_token' : window._Token ,
						'api_token' : window.apiToken ,
						'data' : message 
					} , {
						responseType : 'json'
					} ).then(function( res ){
						console.log( res );
					});

		        },
		        fail: function(e){
		            console.log("Send private text error");
		        }
		    });
		    msg.body.chatType = 'singleChat';
		    console.log( msg );
		    window.conn.send(msg.body);
		    this.message = '';
		} ,
		sendPrivateImg : function () {
				console.log( conn )
				let that = this ;
		    var id = conn.getUniqueId();                   // 生成本地消息id
		    var msg = new WebIM.message('img', id);        // 创建图片消息
		    var input = document.getElementById('image');  // 选择图片的input
		    var file = WebIM.utils.getFileUrl(input);      // 将图片转化为二进制文件
		    that.panelShow = false ;
		    var allowType = {
		        'jpg': true,
		        'gif': true,
		        'png': true,
		        'bmp': true
		    };
		    if (file.filetype.toLowerCase() in allowType) {
		        var option = {
		            apiUrl: WebIM.config.apiURL,
		            file: file,
		            to:  that.toUser.name ,                          // 接收消息对象（用户id）
		            roomType: false,
		            chatType: 'singleChat',
		            onFileUploadError: function () {      // 消息上传失败
		                that.$toast('图片发送失败');
		                document.getElementById('image').value = ""
		            },
		            onFileUploadComplete: function ( msg  ) {   // 消息上传成功
		                var res = msg.entities ;
		                for( var i in res ) {
		                	var message = {
									    	'url' :  msg.uri  + '/' + res[i].uuid ,
									    	'type' : 'image' ,
									    	'to' : that.toUser.name ,
									    	'from' : window.imUser ,
									    	'id' : id
									    } ;
		                	that.messages.push( message );

									    Axios.post('/im/record' , {
												'_token' : window._Token ,
												'api_token' : window.apiToken ,
												'data' : message 
											} , {
												responseType : 'json'
											}).then(function( res ){
												console.log( res );
											}).catch( function( e ){
											
												console.log( e )
											
											});
		                }
		            },
		            success: function ( msg ) {                // 消息发送成功
		                console.log( msg );
		            },
		            flashUpload: WebIM.flashUpload
		        };
		        msg.set(option);
		        window.conn.send(msg.body);
		    }
		} ,
		bigOrSmall : function(){
			let that = this 
			this.panelShow = !this.panelShow 
			that.to = this.$route.params.user_id 
			that.showBigSmall = 'block' 

		} ,
		closeModal:function(){
			this.to = 0 
		},
		stolen:function(){
			let that = this ;
			this.panelShow = !this.panelShow ;
			//发送偷钱的请求
			Axios.put('/stolen/stolen/' + that.$route.params.user_id , {
				'_token' : window._Token ,
				'api_token' : window.apiToken ,
				'id' : that.$route.params.user_id ,
			} , {
				responseType : 'json'
			}).then(function( ret ){
				console.log( ret );
				that.$toast( ret.data.msg );
				if( ret.data.errcode === 0 ) {
					//偷取成功  嘲讽一下
					console.log( ret.data );
					var id = window.conn.getUniqueId();                 // 生成本地消息id
				    var msg = new WebIM.message('txt', id);      // 创建文本消息
				    msg.set({
				        msg:  that.message ,                  // 消息内容
				        to:  that.toUser.name ,                          // 接收消息对象（用户id）
				        roomType: false,
				        success: function (id, serverMsgId) {
				            var message = {
						    	'data' : "我偷取了你"+ ret.data.data +"点狗粮，快来打我吖!!" ,
						    	'type' : 'txt' ,
						    	'to' : that.toUser.name ,
						    	'from' : window.imUser ,
						    	'id' : serverMsgId
						    } ;
				            that.messages.push( message );
						  	//同时保存到服务顺
							Axios.post('/im/record' , {
								'_token' : window._Token ,
								'api_token' : window.apiToken ,
								'data' : message 
							} , {
								responseType : 'json'
							} ).then(function( res ){
								console.log( res );
							});
				        },
				        fail: function(e){
				            console.log("Send private text error");
				        }
				    });
				    msg.body.chatType = 'singleChat';
				    console.log( msg );
				    window.conn.send(msg.body);
				}
				
			} , function( ret ){
				console.log( ret );
			});
		} ,
		agree:function( id ) {
			let that = this ;
			//发送偷钱的请求
			that.$confirm("您确定要加他为好友吗？").then( function(){
				Axios.post('/im/accpet' , {
					'_token' : window._Token ,
					'api_token' : window.apiToken ,
					'id' : id
				} , {
					responseType : 'json'
				}).then(function( ret ){
					let data = ret.data
					if( data.errcode === 0 ) {
						that.$router.push({
							name : 'im-chat' ,
							params : {
								user_id : data.user_id 
							}
						})
					} else {
						that.$toast( data.msg )
					}
				});
			}).catch()
			
		} ,
		disagree:function( id ) {
			let that = this ;
			//发送偷钱的请求
			Axios.post('/im/disaccpet' , {
				'_token' : window._Token ,
				'api_token' : window.apiToken ,
				'id' : id
			} , {
				responseType : 'json'
			}).then(function( ret ){
				let data = ret.data
				that.$toast( data.msg )
				if( data.errcode === 0 ) {
					for( var i in that.messages ) {
						if( that.messages[i].msg_id == id ) {
							that.messages.splice( i , 1 );
							break ;
						}
					}
					setTimeout(function(){
						that.$router.go(-1)
					} , 1500 )	
				}
			});
		}

	} ,
	components:{
		'bigsmall-modal' : bigSmallModal ,
	}
}

</script>


<style scoped>
.img-area {
	width: 3rem;
	height:3rem;
    overflow: hidden;
    position: relative;
    display:block;
    margin-bottom: .2rem;
}
.img-input-file {
	position: absolute;
    left: 0px;
    top: 0px;
    z-index: 6;
    opacity: 0;
    height: 100%;
    width: 100%;
}

.img-area-icon {
	position: absolute;
    width: 100%;
    z-index: 2;
}
.pin-game {
	position: absolute;
	left :.5rem;
	bottom: 4rem;
	text-align: center;
    z-index: 999;
}
.card {
	width: 10rem;
}
.card .card-header, .card .card-footer {
	padding: .2rem .7rem;
	min-height: 1.5rem;


}
.card .card-header {
	font-size: .65rem;
	font-weight: bold;
	color:rgb(114, 76, 65);
}
.card .card-content-inner {
	color:#191919;
	padding: .2rem .7rem;
	font-size: .7rem;
}
.card .card-content-inner img {
	margin: 0 auto;
	width: 4rem;
	display: block;
	float: none;
}
.card .card-footer:before {
	z-index:16;
}
</style>
