@extends('layout')

@section('style')

<style>
    .aui-title {
        font-size:0.7rem;
    }
    .aui-bar-nav .aui-pull-right {
        font-size:0.6rem;
    }
</style>

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
            <a class="aui-tab-item aui-active">我的好友</a>
            <a class="aui-tab-item" href="{{route('im.recent')}}">最近聊天</a>
        </div>

    <section class="aui-content aui-margin-b-15">
        <ul class="aui-list aui-media-list" id="friends-content">
            <!--
            <li class="aui-list-item aui-list-item-middle wechat-top">
                <div class="aui-media-list-item-inner">
                    <div class="aui-list-item-media wechat-avatar">
                        <div class="aui-badge">9</div>
                        <div class="aui-row-padded">
                            <div class="aui-col-xs-4">
                                <img src="../image/demo1.png">
                            </div>
                            <div class="aui-col-xs-4">
                                <img src="../image/demo2.png">
                            </div>
                            <div class="aui-col-xs-4">
                                <img src="../image/demo3.png">
                            </div>
                            <div class="aui-col-xs-4">
                                <img src="../image/demo4.png">
                            </div>
                            <div class="aui-col-xs-4">
                                <img src="../image/demo5.png">
                            </div>
                            <div class="aui-col-xs-4">
                                <img src="../image/demo6.png">
                            </div>
                            <div class="aui-col-xs-4">
                                <img src="../image/demo1.png">
                            </div>
                            <div class="aui-col-xs-4">
                                <img src="../image/demo3.png">
                            </div>
                            <div class="aui-col-xs-4">
                                <img src="../image/demo5.png">
                            </div>
                        </div>
                    </div>
                    <div class="aui-list-item-inner">
                        <div class="aui-list-item-text">
                            <div class="aui-list-item-title">AUI微信开发交流群</div>
                            <div class="aui-list-item-right">08:00</div>
                        </div>
                        <div class="aui-list-item-text aui-font-size-12">
                            流浪男:欢迎使用AUI 2.0
                        </div>
                    </div>
                </div>
            </li>
            <li class="aui-list-item aui-list-item-middle wechat-top">
                <div class="aui-media-list-item-inner">
                    <div class="aui-list-item-media wechat-avatar">
                        <div class="aui-row-padded">
                            <div class="aui-col-xs-6">
                                <img src="../image/demo2.png">
                            </div>
                            <div class="aui-col-xs-6">
                                <img src="../image/demo3.png">
                            </div>
                            <div class="aui-col-xs-6">
                                <img src="../image/demo1.png">
                            </div>
                            <div class="aui-col-xs-6">
                                <img src="../image/demo5.png">
                            </div>
                        </div>

                    </div>
                    <div class="aui-list-item-inner">
                        <div class="aui-list-item-text">
                            <div class="aui-list-item-title">AUI 2.0讨论组</div>
                            <div class="aui-list-item-right">08:00</div>
                        </div>
                        <div class="aui-list-item-text aui-font-size-12">
                            AUI:官方网站 www.auicss.com
                        </div>
                    </div>
                </div>
            </li>
            -->
            @foreach( $list->items() as $val )
            <li class="aui-list-item aui-list-item-middle" data-href="{{ route('wap.im.chat' , ['id' => $val->friend_user_id ]) }}">
                <div class="aui-media-list-item-inner">
                    <div class="aui-list-item-media wechat-avatar" style="width:3rem;">
                        <!--<div class="aui-badge">9</div>-->
                        <img class="aui-img-round" src="{{ asset( data_get( $val->friend , 'avatar' , 'images/logo.png') ) }}">
                    </div>
                    <div class="aui-list-item-inner">
                        <div class="aui-list-item-text">
                            <div class="aui-list-item-title">{{ data_get( $val->friend , 'nickname' , config('global.no_nickname')) }}</div>
                            <div class="aui-list-item-right">
                                @if( data_get( $val->friend , 'online'))
                                    <div class="aui-badge" style="top: 0rem;left: auto;right: .2rem;min-width: 2rem;background:#009635;">在线</div>
                                    @else
                                    <div class="aui-badge" style="top: 0rem;left: auto;right: .2rem;min-width: 2rem;background:#9e9e9e;">离线</div>
                                @endif
                            </div>
                        </div>

                        <div class="aui-list-item-text aui-font-size-12">
                            {{ data_get( $val->friend , 'mobile' , '') }}
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

    @include('footscript')
    <script>
        $('#friends-content .aui-list-item').click( function(){
            if( $(this).data('href') ) {
                location.href = $(this).data('href');
            }
        } ) ;
    </script>
@endsection