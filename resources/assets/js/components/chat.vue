<template id="Chat">
	<div class="page">
		<header class="bar bar-nav">
            <router-link to="">
                <a class="button button-link button-nav pull-left back" @click="$router.go(-1)" data-transition='slide-out'>
                    <span class="icon icon-left"></span>
                    返回
                </a>
            </router-link>
            <h1 class="title">聊天记录</h1>
        </header>
        <nav class="bar bar-tab" v-if="toUserId > 0">
			<div class="feed-cont" v-bind:class="[ panelShow ? 'panel-show' : 'panel-hide' ]" ref="panel">
				<div class="flex">
					<input class="feed-txt" v-model="message" type="text" value="{message}">
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
					<div class="more-item cursor" v-on:click="bigOrSmall()">
						<div class="item-ico">
							<i class="iconfont icon-tuxing"></i>
						</div>
						比大小
					</div>
					<div class="more-item cursor" v-on:click="stolen()">
						<div class="item-ico">
							<i class="iconfont icon-toubaoxiang"></i>
						</div>
						摸一把
					</div>
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
        <div class="content" ref="chatcontent">
			<div class="content-block chat-content" ref="chatbox" style="margin:0rem;padding:0rem .25rem;">
				<!--
					<p class="u-msgTime">- - - - -&nbsp;16:31&nbsp;- -- - -</p>
				-->
				<div
					v-for="item in messages"
					:data-id="item.id" 
					class="item" 
					:class="{'item-me' : item.to != toUser.name , 'item-you': item.to == toUser.name }"

				>
					

					<img 
						class="img j-img" 
						v-if="item.to == toUser.name"
						src="http://wx.qlogo.cn/mmopen/Q3auHgzwzM7hq8e7Q0FLXO7ms14YDKFfrZ9Pd5bP7GUSqxxtZsQF27MxsHRIHLvIDnTAONnoVic3lGGBTdia14vA/0" 
					/>
					<img 
						class="img j-img" 
						v-else
						src="http://wx.qlogo.cn/mmopen/Q3auHgzwzM7hq8e7Q0FLXO7ms14YDKFfrZ9Pd5bP7GUSqxxtZsQF27MxsHRIHLvIDnTAONnoVic3lGGBTdia14vA/0" 
					/>

					<div v-if="item.type =='txt' " class="msg msg-text j-msg">
						<div class="box">
							<div class="cnt">
								<div class="f-maxWid">{{item.data}}</div>
							</div>
						</div>
					</div>
					<div v-if="item.type == 'image'" class="msg msg-text j-msg">
						<div class="box">
							<div class="cnt">
								
										<img :src="item.url" >
								
							</div>
						</div>
					</div>

					<span class="readMsg"><i></i>已读</span>
				</div>
			</div>
		</div>

	</div>
	
</template>



<script>
import Axios from 'axios' ;
import Vue from "vue" ;
import VueLazyload from 'vue-lazyload' ;
import VueChatScroll from 'vue-chat-scroll' ;
Vue.use(VueChatScroll) ;
Vue.use( VueLazyload );
export default {
	data :function(){
		return {
			panelShow : false ,
			message : '' ,
			toUserId : 0 ,
			messages:[] ,
			toUser : {} ,
			fromUser:{} ,
			queueMsg:{},
			chatboxHeight : 0 
		}
	} ,
	created : function(){
		let that = this ;
		that.toUserId = parseInt( that.$route.params.user_id )
		console.log( that.toUserId )
		if( that.toUserId == 0 ) {
			return 
		}
		
		Axios.get('/im/userinfo/' + this.$route.params.user_id  + 
			'?_token=' + window._Token + 
			'&api_token=' + window.apiToken
		,
		{
			responseType : 'json'
		}
		).then(function( res ){
			that.toUser = res.data.data ;
		});
		Axios.get('/im/history/' + this.$route.params.user_id  + 
			'?_token=' + window._Token + 
			'&api_token=' + window.apiToken
		,
		{
			responseType : 'json'
		}
		).then(function( res ){
			console.log( res );
			if( res.data.data ) {
				let list = res.data.data.data ;
				for( var i = list.length - 1 ; i >= 0 ; i-- ) {
					that.messages.push({
				    	'data' : list[i].body.data ,
				    	'url' : list[i].body.url ,
				    	'type' : list[i].type ,
				    	'to' : list[i].receiver.name ,
				    	'from' : list[i].sender ? list[i].sender.name : '系统消息' ,
				    	'id' : list[i].id
				    });
				}
			}
		});
		
	} ,
	mounted:function(){
		let that = this ;
		window.conn.listen({
			onTextMessage:function( message ){
				//收到文本消息
				message.type = 'txt' ;
				
				if( message.to == imUser ) {
					that.messages.push( message );	
				}
				console.log( message );
			},
			onCmdMessage:function( message ){
				console.log( message );
				var msg = {
			    	'data' : message.ext.worth ,
			    	'type' : 'game' ,
			    	'to' : message.to ,
			    	'from' : message.from ,
			    	'id' : message.id
			    } ;
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
				console.log( message );
				message.type = 'image' ;
				if( message.to == imUser ) {
					that.messages.push( message );	
				}
			}

		});
		setInterval( function(){
			if( that.chatboxHeight != that.$refs.chatbox.clientHeight ) {
				that.chatboxHeight = that.$refs.chatbox.clientHeight ;
				that.$refs.chatcontent.scrollTop = that.chatboxHeight ;

			}
		} , 1000 );
	},
	methods : {

		chatlog : function( user_id ) {

		} ,
		openPanel : function( ) {
			this.panelShow = !this.panelShow ;
			/**
			$('.feed-cont').css({
				"max-height":"8rem"
			});
			$(".list-content").css('bottom' , '8rem');
			$('.list-content').scrollTop(99999);
			$('.feed-cover').show();
			**/
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
					console.log( message );
					Axios.post('/api/im/record' ,
					{
						'_token' : window._Token ,
						'api_token' : window.apiToken ,
						'data' : message 
					}
					,
					{
						responseType : 'json'
					}
					).then(function( res ){
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
			let that = this ;
		    var id = conn.getUniqueId();                   // 生成本地消息id
		    var msg = new WebIM.message('img', id);        // 创建图片消息
		    var input = document.getElementById('image');  // 选择图片的input
		    var file = WebIM.utils.getFileUrl(input);      // 将图片转化为二进制文件
		    that.panelShow = false ;
		    console.log( file )
		    var allowType = {
		        'jpg': true,
		        'gif': true,
		        'png': true,
		        'bmp': true
		    };
		    console.log( file )
		    if (file.filetype.toLowerCase() in allowType) {
		        var option = {
		            apiUrl: WebIM.config.apiURL,
		            file: file,
		            to:  that.toUser.name ,                          // 接收消息对象（用户id）
		            roomType: false,
		            chatType: 'singleChat',
		            onFileUploadError: function () {      // 消息上传失败
		                that.$toast('图片发送失败');
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

											}).catch( e ) {
												alert('上传失败')
											};
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
			this.panelShow = !this.panelShow ;
			this.$router.push({name:'bigsmall' , 'params' : {'user_id' : this.$route.params.user_id } }) ;
			console.log( this.$router );
		} ,
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
							Axios.post('/api/im/record' , {
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
		}

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
</style>