@extends('layout')

@section('style')

@endsection

@section('content')
    <header class="aui-bar aui-bar-nav">
        <div class="aui-title">我的好友</div>
        <a class="aui-pull-right aui-btn" href="{{route('wap.im.searchfriend')}}">
            <span class="aui-iconfont aui-icon-search"></span>
            加好友
        </a>
    </header>

        <div class="aui-tab" id="tab">
            <a class="aui-tab-item " href="{{route('friends')}}">我的好友</a>
            <a class="aui-tab-item aui-active" >最近聊天</a>
        </div>

    <section class="aui-content aui-margin-b-15">
        <ul class="aui-list aui-media-list im-recent">
            @foreach( $list->items() as $val )
            <li class="aui-list-item aui-list-item-middle"
                data-type="{{ data_get( $val , 'type') }}"
                data-id="{{ data_get( $val , 'id' ) }}"

            >
                <div class="aui-media-list-item-inner">
                    @if( data_get( $val->sender , 'id' ) == $user->id )
                        {{-- 如果我是发送者 则显示接收者的头像 --}}
                        <div class="aui-list-item-media wechat-avatar" style="width:3rem;">
                            <!--<div class="aui-badge">9</div>-->
                            <img class="aui-img-round" src="{{ asset( data_get( $val->receiver , 'avatar' , 'images/logo.png') ) }}">
                        </div>
                    @elseif( data_get( $val->receiver , 'id' ) == $user->id )
                        <div class="aui-list-item-media wechat-avatar" style="width:3rem;">
                            <!--<div class="aui-badge">9</div>-->
                            <img class="aui-img-round" src="{{ asset( data_get( $val->sender , 'avatar' , 'images/logo.png') ) }}">
                        </div>
                    @else
                        <div class="aui-list-item-media wechat-avatar" style="width:3rem;">
                            <!--<div class="aui-badge">9</div>-->
                            <img class="aui-img-round" src="{{ asset( 'images/logo.png' ) }}">
                        </div>
                    @endif
                    <div class="aui-list-item-inner">
                        <div class="aui-list-item-text">
                            @if( data_get( $val->sender , 'id' ) == $user->id )
                                <div class="aui-list-item-title">{{ data_get( $val->receiver , 'nickname' , config('global.no_nickname')) }}</div>
                            @elseif( data_get( $val->receiver , 'id' ) == $user->id )
                                <div class="aui-list-item-title">{{ data_get( $val->receiver , 'sender' , config('global.no_nickname')) }}</div>
                            @else
                                <div class="aui-list-item-title">系统消息</div>
                            @endif
                            <div class="aui-list-item-right">
                                {{ $val->created_at }}
                            </div>
                        </div>

                        <div class="aui-list-item-text aui-font-size-12">
                            @if( $val->type == 'txt')
                                {{ data_get( $val->body , 'data' , '') }}
                            @endif

                            @if( $val->type == 'image')
                                [图片]
                            @endif

                            @if( $val->type == 'addfriend')
                                {{ data_get( $val->body , 'data' , '') }}
                            @endif

                            @if( $val->type == 'invite_bigsmall')
                                @if( $user->id == data_get( $val->sender , 'id') )
                                    {{"您向" . data_get( $val->sender , 'nickname') . "发起了比大小战斗，等待他接受战斗！"}}
                                @else
                                    {{ data_get( $val->body , 'data') . "向您发起了比大小战斗，是否接受战斗？"}}
                                @endif
                            @endif
                        </div>

                    </div>
                </div>
            </li>
            @endforeach
        </ul>
    </section>

    @include('foot')
@endsection


@section('script')
<script>
$(document).on('click' , '.im-recent li' , function(){
    var id = $(this).data('id');
    var type = $(this).data('type');
    $.get('/im/game/' + id , function( data ){
        location.href = data.url
    })
})

</script>
    @include('footscript')
@endsection