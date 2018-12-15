@extends('layout')

@section('style')
<style>
    .img-input-file {
        position: absolute;
        left: 0px;
        top: 0px;
        z-index: 6;
        opacity: 0;
        height: 100%;
        width: 100%;
    }
    .aui-chat .aui-chat-right .aui-chat-inner , .aui-chat .aui-chat-left .aui-chat-inner {
        width: 75%;
        max-width:75%;
    }
    .aui-chat .aui-chat-content {
        max-width: 100%;
    }
</style>
@endsection

@section('content')
    <header class="aui-bar aui-bar-nav">
        <a class="aui-pull-left aui-btn" onclick="history.back()">
            <span class="aui-iconfont aui-icon-left"></span>
        </a>
        <div class="aui-title">{{ data_get( $friend , 'nickname' , config('global.no_nickname')) }}</div>

    </header>
    <section class="aui-chat" id="chat-content" style="margin-bottom: 3rem;clear:both;display:none;">
        @verbatim
        <div
            v-for="item in messages"
            :data-id="item.id"
            class="aui-chat-item"
            :class="{'aui-chat-left' : toUserId > 0 && item.to != toUser.name , 'aui-chat-right': toUserId  == 0 || item.to == toUser.name }"
        >
            <div class="aui-chat-media">
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
            </div>
            <div class="aui-chat-inner">
                <div class="aui-chat-name" html="item.data" v-if="toUserId > 0 &&  item.to == toUser.name">{{ fromUser.nickname }}</div>
                <div class="aui-chat-name" html="item.data" v-else >{{ toUser.nickname }}</div>
                <div class="aui-chat-content">
                    <div class="aui-chat-arrow"></div>
                    <span v-if="item.type == 'txt'" v-html="item.data"></span>
                    <img v-if="item.type == 'image'" :src="item.url">
                    <div v-if="item.type =='invite_bigsmall' " style="width: 100%;display:block;">

                        <!-- 我收到的  -->
                        <div class="box" v-if="item.to != toUser.name">
                            <div class="aui-card-list">
                                <div class="aui-card-list-header">应用消息：[比大小]</div>
                                <div class="aui-card-list-content-padded">
                                    {{item.data}}向您发起了比大小战斗
                                </div>
                                <div class="aui-card-list-footer">
                                    <a :href="'/bigsmall/detail/' + item.msg_id">查看详情</a>
                                </div>
                            </div>
                        </div>
                        <div class="box" v-else>
                            <div class="aui-card-list">
                                <div class="aui-card-list-header">应用消息：[比大小]</div>
                                <div class="aui-card-list-content-padded">
                                    您向{{getName( item ) }}发起了比大小战斗
                                </div>
                                <div class="aui-card-list-footer">
                                    <a :href="'/bigsmall/detail/' + item.msg_id">查看详情</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endverbatim
        <div style="height:.1rem;clear:both;">&nbsp;</div>
    </section>

    <div class="aui-popup aui-popup-bottom" id="invite-bottom" style="display: none;left:0rem;right:0rem;bottom:0rem;margin-left:0rem;">
        <div class="aui-popup-content" style="border-radius: 0;padding-top:.25rem;min-height:20rem;">
            <ul class="aui-list aui-form-list">
                <li class="aui-list-item">
                    <div class="aui-list-item-inner">
                        <div class="aui-list-item-input" style="padding-right: 0rem;">
                            <input type="number" id="promise-cash" class="aui-text-center" placeholder="铜板需要在{{$min}}到{{$max}}之间">
                        </div>
                    </div>
                </li>
                <li class="aui-list-item">
                    <div class="aui-list-item-inner aui-list-item-center aui-list-item-btn">
                        <div class="aui-btn aui-btn-block aui-btn-sm aui-btn-success btn-battle">发起战斗</div>
                    </div>
                </li>
            </ul>
        </div>
    </div>

    <footer class="aui-bar aui-bar-tab" id="footer">
        <div class="aui-row-padded">
            <div class="aui-col-xs-8">
                <input type="text" id="msg-content" style="border-radius: 3px;border:solid #dfdfdf 1px;height:1.6rem;margin-top:.3rem;" />
            </div>
            <div class="aui-col-xs-4" style="line-height: 2.2rem;">
                <a class="aui-btn send-msg" >发送</a>
                <a class="aui-btn btn-pannel" >+</a>
            </div>
        </div>
        <div class="aui-grid" id="pannel" style="display: none;">
            <div class="aui-col-xs-4 cursor" id="chooseFileBtn">
						<span class="img-area">
							<input type="file" id="image-file" class="img-input-file" />
							<div class="item-ico img-area-icon" style="position: relative;z-index:3;">
								<i class="aui-iconfont aui-icon-image"></i>
							</div>
						</span>
                图片
            </div>
            <div class="aui-col-xs-4 cursor" id="btn-bigsmall">
                <div class="item-ico">
                    <i class="aui-iconfont aui-icon-star"></i>
                </div>
                比大小
            </div>
        </div>
    </footer>

@endsection


@section('script')
<script type="text/javascript" src="{{asset('vendor/im/websdk.config.js')}}"></script>
<script type="text/javascript" src="{{asset('vendor/im/strophe-1.2.8.js')}}"></script>
<script type='text/javascript' src="{{asset('vendor/im/websdk-1.4.13.js')}}"></script>
<script src="{{asset('packages/aui/script/aui-popup.js')}}"></script>
<script>
var _Token = "{{ csrf_token() }}";

var popup = new auiPopup();

var min = "{{$min}}" ;
var max = "{{$max}}" ;

var conn = new WebIM.connection({
    isMultiLoginSessions: WebIM.config.isMultiLoginSessions,
    https: typeof WebIM.config.https === 'boolean' ? WebIM.config.https : location.protocol === 'https:',
    url: WebIM.config.xmppURL,
    heartBeatWait: WebIM.config.heartBeatWait,
    autoReconnectNumMax: WebIM.config.autoReconnectNumMax,
    autoReconnectInterval: WebIM.config.autoReconnectInterval,
    apiUrl: WebIM.config.apiURL,
    isAutoLogin: true
})
var imUser = "{{data_get( $user , 'name' )}}" ;

var toUser = {!! json_encode( data_get( $friend , 'friend' ) ) !!} ;

var fromUser = {!! json_encode( $user ) !!} ;

var invite_user = toUser.id ;

var options = {
    apiUrl: WebIM.config.apiURL,
    user:   imUser ,
    pwd: "{{ data_get( $user  ,'api_token') }}" ,
    appKey: WebIM.config.appkey,
    success: function (token) {
        console.log( token );
        var token = token.access_token;
        setCookie('webim_' + data.user.name , token, 1 );
    },
    error : function( data ){
        console.log( data );
    }
};
conn.open(options);
</script>
<script src="https://cdn.jsdelivr.net/npm/vue"></script>
<script>
Zepto(function( $ ){

    $('#chat-content').show();
    var vm = new Vue({
        el: '#chat-content',
        data: {
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
        } ,
        mounted:function(){
            let that = this ;

            that.toUserId = "{{ data_get( $friend , 'friend_user_id') }}"
            if( that.toUserId == 0 ) {
                //return
            }

            that.toUser = toUser
            that.toUserName = that.toUser.nickname || '连运'

            that.fromUser = fromUser

            conn.listen({
                onTextMessage:function( message ){
                    //收到文本消息
                    console.log( message )
                    console.log( 'txt' )
                    message.type = 'txt' ;
                    console.log( message )
                    if( message.to == imUser ) {
                        that.messages.push( message );
                    }
                    console.log( message );
                    setTimeout( function(){
                        window.scrollTo( 0 , 999999)
                    } , 300 );
                },
                onCmdMessage:function( message ){
                    console.log( message );
                    console.log('cmd')
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
                    setTimeout( function(){
                        window.scrollTo( 0 , 999999)
                    } , 300 );
                    //that.messages.push( message );

                },
                //当消息发送到服务器
                onReceivedMessage:function( message ){
                    setTimeout( function(){
                        window.scrollTo( 0 , 999999)
                    } , 300 );
                } ,
                onPictureMessage : function( message ){
                    message.type = 'image' ;
                    if( message.to == imUser ) {
                        that.messages.push( message );
                        setTimeout( function(){
                            window.scrollTo( 0 , 999999)
                        } , 500 );
                    }
                }

            });
            this.fetchData();
            /**
            setInterval( function(){
                if( that.$refs.chatcontent ) {
                    if( that.chatboxHeight != that.$refs.chatbox.clientHeight ) {
                        that.chatboxHeight = that.$refs.chatbox.clientHeight ;
                        that.$refs.chatcontent.scrollTop = that.chatboxHeight ;

                    }
                }
            } , 1000 );
             **/
        },
        methods : {
            fetchData:function(){
                let that = this ;
                that.messages = []


                $.get('/im/history/' + that.toUser.id , function(  data ){
                    if( data.data ) {
                        let list = data.data.data ;
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
                        setTimeout( function(){
                            window.scrollTo( 0 , 999999)
                        } , 300 );
                    }
                });
            },
            getName:function( item ){
                console.log( item )
                console.log( this.toUser )
                return item.to == this.toUser.name ? this.toUser.nickname : this.fromUser.nickname ;
            } ,
            avatar :function( v ){
                return v ? '/' + v : '/images/logo.png'
            },
            chatlog : function( user_id ) {

            } ,
            openPanel : function( ) {
                this.panelShow = !this.panelShow ;
            } ,
            sendMsg : function( msgText ) {
                let that = this ;
                if( !msgText ) {

                    return false ;
                }
                var id = conn.getUniqueId();                 // 生成本地消息id
                var msg = new WebIM.message('txt', id);      // 创建文本消息
                msg.set({
                    msg:  msgText ,                  // 消息内容
                    to:  that.toUser.name ,                          // 接收消息对象（用户id）
                    roomType: false,
                    success: function (id, serverMsgId) {
                        console.log( id );
                        var message = {
                            'data' : msg.value ,
                            'type' : 'txt' ,
                            'to' : that.toUser.name ,
                            'from' : window.imUser ,
                            'id' : serverMsgId
                        } ;
                        that.messages.push( message );
                        //同时保存到服务顺
                        $.post('/im/record' , {
                            '_token' : _Token ,
                            'data' : message
                        }  , function( res ){
                            console.log( res );
                        });

                    },
                    error:function(){
                        console.log("Send private text error");
                    },
                    fail: function(e){
                        console.log("Send private text error");
                    }
                });
                msg.body.chatType = 'singleChat';
                console.log( msg )
                conn.send(msg.body);
                $('#msg-content').val('') ;
            } ,
            sendPrivateImg : function () {
                console.log( conn )
                let that = this ;
                var id = conn.getUniqueId();                   // 生成本地消息id
                var msg = new WebIM.message('img', id);        // 创建图片消息
                var input = document.getElementById('image-file');  // 选择图片的input
                var file = WebIM.utils.getFileUrl(input);      // 将图片转化为二进制文件
                $('#pannel').hide();
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
                        onFileUploadError: function ( err ) {      // 消息上传失败
                            console.log( err )
                            toast.fail({title:'图片发送失败'});
                            document.getElementById('image-file').value = ""
                        },
                        onFileUploadComplete: function ( msg  ) {   // 消息上传成功
                            var res = msg.entities ;
                            for( var i in res ) {
                                var message = {
                                    'url' :  msg.uri  + '/' + res[i].uuid ,
                                    'type' : 'image' ,
                                    'to' : that.toUser.name ,
                                    'from' : imUser ,
                                    'id' : id
                                } ;
                                that.messages.push( message );
                                $.post('/im/record' , {
                                    '_token' : _Token ,
                                    'data' : message
                                } , function( e ){
                                    console.log( e )
                                });
                            }
                            document.getElementById('image-file').value = ""
                        },
                        success: function ( msg ) {                // 消息发送成功
                            console.log( msg );
                            document.getElementById('image-file').value = ""
                        },
                        flashUpload: WebIM.flashUpload
                    };
                    msg.set(option);
                    conn.send(msg.body);
                }
            }

        }
    })


    $('.send-msg').bind('click' , throttle( sendMsg , 1500 ) ) ;
    $('#btn-bigsmall').bind('click' , throttle( bigOrSmall , 1500 ) ) ;
    $('#image-file').bind('change' , throttle( sendPrivateImg , 1500 ) ) ;

    function bigOrSmall() {
        popup.show( document.getElementById("invite-bottom") )
    }

    function sendPrivateImg() {
        vm.sendPrivateImg()
    }
    function sendMsg () {
        var msg = $('#msg-content').val().trim();
        if( !msg ) {
            return false ;
        }
        vm.sendMsg( msg )
    }

    $(document).on('click' , '.btn-battle' , function(){
        var cash = $('#promise-cash').val().trim();
        cash = parseInt( cash );
        cash = isNaN( cash ) ? 0 : cash ;
        if( cash <= 0 ) {
            toast.fail( {
                title : '请填写铜板' ,
                duration : 1500
            });
            return false ;
        }
        if( cash < min ) {
            toast.fail( {
                title : '单次邀请最少需要' + min + '铜板' ,
                duration : 1500
            });
            return false ;
        }
        if( cash > max ) {
            toast.fail( {
                title : '单次邀请最多只能' + max + '铜板' ,
                duration : 1500
            });
            return false ;
        }
        $.post('/bigsmall/invite' , {
            'promise' : cash ,
            '_token' : '{{csrf_token()}}' ,
            '_method' : 'put' ,
            'invite_user' : invite_user ,
        }  , function(  data  ){

            if( data.errcode === 0 ) {
                //战斗成功 回到发送信息的页面
                toast.success({
                    'title' : data.msg ,
                    duration : 1500
                }) ;
            } else {
                toast.fail( {
                    title : data.msg ,
                    duration : 1500
                })
            }

        });
        popup.hide();
        $('#pannel').hide();
    })

    $('.btn-pannel').click( function(){
        $('#pannel').toggle();
    }) ;
});
</script>


@endsection