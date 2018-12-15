@extends('layout')

@section('style')
<style>
    .row-result {
        overflow: hidden;
        margin-bottom: 1.5rem;
        padding-top:2rem;
    }
    .row-result img {
        margin:0 auto;
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
</style>
@endsection

@section('content')
    <header class="aui-bar aui-bar-nav">
        <a class="aui-pull-left aui-btn" onclick="history.back()">
            <span class="aui-iconfont aui-icon-left"></span>
        </a>
        <div class="aui-title">比大小</div>
        <a class="aui-pull-right aui-btn" href="{{route('wap.game.help')}}" >
            帮助
        </a>

    </header>
    <section class="aui-content-padded">
        @if( $game->user_num && $game->inviter_num )
        <div class="row-result">
            <div class="col-left">
                <img src="{{ asset('images/' . $game->user_num .'.png') }}"  />
            </div>
            <div class="col-mid">
                <img src="{{asset('/images/vs.png')}}" />
            </div>
            <div class="col-right">
                <img src="{{ asset('images/' . $game->inviter_num .'.png') }}"  />
            </div>

        </div>
        @else
            <div class="row-result">
                <div class="col-puzzle">
                    <img src="{{asset('/images/13.png')}}" style="height:6rem;" />
                </div>
            </div>
        @endif


            <div class="aui-row">
                <div class="aui-col-xs-4">
                    <div class="inviter">
                        <img src="{{ asset( data_get( $game->user , 'avatar' , 'images/logo.png')) }}" class="avatar inviter-img aui-img-round">
                        <a >{{ data_get( $game->user , 'nickname' , config('global.no_nickname') ) }}</a>
                        @if( $game->user_num && $game->inviter_num && $game->inviter_num < $game->user_num )
                            <span class="crown" ></span>
                        @endif
                    </div>
                </div>
                <div class="aui-col-xs-4">
                    <div class="to-pk">
                        VS
                    </div>
                    <div class="to-money">
                        {{$game->cash_deposit}}
                    </div>
                </div>
                <div class="aui-col-xs-4">
                    <div class="me">
                        <img src="{{ asset( data_get( $game->inviter , 'avatar' , 'images/logo.png')) }}" class="avatar me-img aui-img-round">
                        <a >{{ data_get( $game->inviter , 'nickname' , config('global.no_nickname') ) }}</a>
                        @if( $game->user_num && $game->inviter_num && $game->inviter_num > $game->user_num )
                            <span class="crown" ></span>
                        @endif
                    </div>
                </div>
            </div>

            <div class="aui-content aui-margin-t-15 aui-font-size-18 aui-text-center">
        @if( $user->id == $game->user_id )
            <p>
            @switch( $game->status )
                @case( 0 )
                        等待对方接受战斗。
                    @break
                @case ( 1 )
                        @if( $game->user_num && $game->inviter_num && $win)
                                恭喜您获得胜利！！！
                                <br/>去和他聊天联系退还铜板吧。
                                <br/>对方电话是{{ data_get( $game->inviter , 'mobile') }}
                        @else
                            很遗憾您输掉了战斗。<br/>
                            对方支付宝:{{data_get( $game->inviter , 'alipay_account') }}
                        @endif
                    @break
                @case( 2 )
                        @if( $win )
                            您退还了铜板!
                        @else
                        对方退还了铜板!
                        @endif
                    @break
                @case( 3 )
                        对方拒绝了战斗。
                    @break
                @case( 4 )
                    您撤销了战斗
                    @break ;
                @case( 5 )
                        @if( $win )
                            系统退对方铜板!
                        @else
                            系统退我铜板!
                        @endif
                    @break
                @case( 6 )
                        系统撤消战斗!
                    @break

            @endswitch
            </p>
        @else
            <p>
                @switch( $game->status )
                    @case( 0 )
                        准备好接受战斗了吗？
                        @break
                    @case ( 1 )
                            @if( $game->user_num && $game->inviter_num && $win)
                                恭喜您获得胜利！！！
                                <br/>去和他聊天联系退还铜板吧。
                                <br/>对方电话是{{ data_get( $game->user , 'mobile') }}
                            @else
                                很遗憾您输掉了战斗。<br/>
                                对方支付宝:{{data_get( $game->user , 'alipay_account') }}
                            @endif
                        @break
                    @case( 2 )
                            @if( $win )
                                您退还了铜板!
                            @else
                                对方退还了铜板!
                            @endif
                        @break
                    @case( 3 )
                            您拒绝了战斗。
                        @break
                    @case( 4 )
                            对方撤销战斗
                        @break ;
                    @case( 5 )
                            @if( $win )
                                系统退对方铜板!
                            @else
                                系统退我铜板!
                            @endif
                        @break
                    @case( 6 )
                            系统撤消战斗!
                        @break

                @endswitch
            </p>
        @endif
        </div>
    </section>


    @if( $user->id != $game->user_id && $game->status == 0)
    <footer class="aui-bar aui-bar-tab">
        <div class="aui-bar-tab-item aui-bg-warning aui-text-white btn-reject" tapmode="" style="width: 8rem;">
          		<span class="tab-label">不接受</span>
        </div>
        <div class="aui-bar-tab-item aui-bg-danger aui-text-white btn-accpet" tapmode="" style="width: auto;">
          		<span class="tab-label">接受邀请</span>
        </div>
    </footer>
    @endif
    @if( $user->id == $game->user_id && $game->status == 0 )
    <footer class="aui-bar aui-bar-tab">
            <div class="aui-bar-tab-item aui-bg-warning aui-text-white btn-cancel" tapmode="" style="width: auto;">取消邀请</div>
    </footer>
    @endif
    @if( $game->user_num && $game->inviter_num && $game->status == 1 && $win )
    <footer class="aui-bar aui-bar-tab">
        <div class="aui-bar-tab-item aui-bg-warning aui-text-white btn-reback" tapmode="" style="width: auto;">退还铜板</div>
    </footer>
    @endif

@endsection


@section('script')
<script>
    $('.btn-reject').unbind('click' ).bind('click' , throttle( reject , 1500 )) ;
    $('.btn-accpet').unbind('click' ).bind('click' , throttle( accept , 1500 )) ;
    $('.btn-cancel').unbind('click' ).bind('click' , throttle( cancel , 1500 )) ;
    $('.btn-reback').unbind('click' ).bind('click' , throttle( reback , 1500 )) ;


    function cancel(){
        let that = this
        $.post('{{route('wap.game.cancel' , ['id' => $game->id ])}}' , {
            '_method' : "PUT" ,
            '_token' : '{{ csrf_token() }}'
        }  , function( data ){
            if( data.errcode === 0 ) {
                toast.success({
                    title : data.msg
                });
                //邀请成功 回到发送信息的页面
                setTimeout( function(){
                    location.reload()
                } , 1500 );
            } else {
                toast.fail({
                    title : data.msg
                });
            }

        })
    }

    function accept(){
        $.post("{{route('wap.game.accept' , ['id' => $game->id ])}}" , {
            '_method' : "PUT" ,
            '_token' : '{{ csrf_token() }}'
        } , function( data ){
            if( data.errcode === 0 ) {
                //邀请成功 回到发送信息的页面
                toast.success({
                    title : data.msg
                })

                setTimeout( function(){
                    location.reload()
                } , 1500 );

            }else if( ret.data.errcode == 10006 ) {
                toast.fail({
                    title : data.msg
                })
                setTimeout( function(){
                    location.href = '{{route('member.chargeform')}}'
                } , 1500 );
            }

        })
    }

    function reject(){
        $.post("{{route('wap.game.reject' , ['id' => $game->id ])}}" , {
            '_method' : "PUT" ,
            '_token' : '{{ csrf_token() }}'
        } , function( data ){
            if( data.errcode === 0 ) {
                toast.success({
                    title : data.msg
                });
                setTimeout( function(){
                    location.reload()
                } , 1500 );

            } else {
                toast.fail({
                    title : data.msg
                })
            }

        });
    }

    function reback() {
        let that = this
        $.post("{{route('wap.game.reback' , ['id' => $game->id ])}}" , {
            '_method' : "PUT" ,
            '_token' : '{{ csrf_token() }}'
        }  , function( data ){
            if( data.errcode === 0 ) {
                toast.success({
                    title : data.msg
                });
                setTimeout( function(){
                    location.reload()
                } , 1500 );

            } else {
                toast.fail({
                    title : data.msg
                })
            }

        }) ;
    }
</script>
@endsection