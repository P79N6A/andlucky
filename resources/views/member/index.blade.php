@extends('layout')

@section('style')
    <link rel="stylesheet" type="text/css" href="{{asset('packages/swiper/css/swiper.min.css')}}" />
    <style>
        #tab {
            background:#fff ;
        }
    </style>
@endsection

@section('content')
    <header class="aui-bar aui-bar-nav" id="aui-header">
        <div class="aui-title">会员中心</div>
        <a class="aui-pull-right aui-btn" href="{{route('wap.message.index')}}">
            <span class="aui-iconfont aui-icon-mail"></span>
        </a>
    </header>

    <section class="aui-content aui-border-b" id="user-info">
        <div class="aui-list aui-media-list aui-list-noborder">
            <div class="aui-list-item aui-list-item-middle">
                <a class="aui-media-list-item-inner " href="{{route('wap.member.avatar')}}">
                    <div class="aui-list-item-media" style="width:3rem;">
                        <img src="{{ asset( data_get( $user , 'avatar' , 'images/logo.png')) }}" class="aui-img-round">
                    </div>
                    <div class="aui-list-item-inner aui-list-item-arrow">
                        <div class="aui-list-item-text text-white aui-font-size-18">{{ data_get( $user , 'nickname' , config('global.nickname')) }}</div>
                        <div class="aui-list-item-text text-white">
                            <div><i class="aui-iconfont aui-icon-mobile aui-font-size-14"></i>{{ data_get( $user , 'mobile') }}</div>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </section>
    <section class="aui-content aui-grid aui-margin-b-15">
        <div class="aui-row">
            <div class="aui-col-xs-6 aui-border-r">
                <big class="aui-text-warning">{{data_get( $user , 'cash_reward')}}</big>
                <div class="aui-gird-lable aui-font-size-12">铜板</div>
            </div>
            <div class="aui-col-xs-6">
                <big class="aui-text-danger">{{ data_get( $user , 'credit' , 0.00 ) }}</big>
                <div class="aui-gird-lable aui-font-size-12">信用</div>
            </div>
        </div>
    </section>

    <section class="aui-content member-link" style="padding-bottom: 3rem;">
        <ul class="aui-list aui-list-in aui-margin-b-15">
            <li class="aui-list-item" data-href="{{route('wap.member.baseinfo')}}">
                <div class="aui-list-item-label-icon">
                    <i class="aui-iconfont aui-icon-cert aui-text-danger"></i>
                </div>
                <div class="aui-list-item-inner aui-list-item-arrow">
                    <div class="aui-list-item-title">基本信息</div>
                </div>
            </li>
            <li class="aui-list-item" data-href="{{route('member.modpwd')}}" >
                <div class="aui-list-item-label-icon">
                    <i class="aui-iconfont aui-icon-lock aui-text-danger"></i>
                </div>
                <div class="aui-list-item-inner aui-list-item-arrow">
                    <div class="aui-list-item-title">修改密码</div>
                </div>
            </li>
            <li class="aui-list-item" data-href="{{ route('wap.adv.mine') }}">
                <div class="aui-list-item-label-icon">
                    <i class="aui-iconfont aui-icon-flag aui-text-danger"></i>
                </div>
                <div class="aui-list-item-inner aui-list-item-arrow">
                    <div class="aui-list-item-title">我发布的广告</div>
                </div>
            </li>
            <li class="aui-list-item" data-href="{{ route('wap.microblog.mine') }}">
                <div class="aui-list-item-label-icon">
                    <i class="aui-iconfont aui-icon-paper aui-text-danger"></i>
                </div>
                <div class="aui-list-item-inner aui-list-item-arrow">
                    <div class="aui-list-item-title">我发布的资讯</div>
                    <div class="aui-list-item-right">
                        <div class="aui-label aui-label-success">
                        {{ $microblog_count }}
                        </div>
                    </div>
                </div>
            </li>
        </ul>
        <ul class="aui-list aui-list-in aui-margin-b-15">
            <li class="aui-list-item" data-href="{{route('member.charge')}}">
                <div class="aui-list-item-label-icon">
                    <i class="aui-iconfont aui-icon-forward aui-text-warning"></i>
                </div>
                <div class="aui-list-item-inner aui-list-item-arrow">
                    <div class="aui-list-item-title">充值记录</div>
                </div>
            </li>
            <li class="aui-list-item" data-href="{{route('member.income')}}">
                <div class="aui-list-item-label-icon">
                    <i class="aui-iconfont aui-icon-plus aui-text-warning"></i>
                </div>
                <div class="aui-list-item-inner aui-list-item-arrow">
                    <div class="aui-list-item-title">我的收入</div>
                </div>
            </li>
            <li class="aui-list-item" data-href="{{route('member.withdrawlog')}}">
                <div class="aui-list-item-label-icon">
                    <i class="aui-iconfont aui-icon-back aui-text-warning"></i>
                </div>
                <div class="aui-list-item-inner aui-list-item-arrow">
                    <div class="aui-list-item-title">我的提现</div>
                </div>
            </li>
        </ul>

        <ul class="aui-list aui-list-in aui-margin-b-15">
            <li class="aui-list-item" data-href="{{route('help')}}">
                <div class="aui-list-item-label-icon">
                    <i class="aui-iconfont aui-icon-question aui-text-info"></i>
                </div>
                <div class="aui-list-item-inner aui-list-item-arrow">
                    <div class="aui-list-item-title">帮助说明</div>
                </div>
            </li>
            <li class="aui-list-item" data-href="{{route('feedback')}}">
                <div class="aui-list-item-label-icon">
                    <i class="aui-iconfont aui-icon-paper aui-text-info"></i>
                </div>
                <div class="aui-list-item-inner aui-list-item-arrow">
                    <div class="aui-list-item-title">意见反馈</div>
                </div>
            </li>
            <li class="aui-list-item" data-href="{{route('aboutus')}}">
                <div class="aui-list-item-label-icon">
                    <i class="aui-iconfont aui-icon-info aui-text-info"></i>
                </div>
                <div class="aui-list-item-inner aui-list-item-arrow">
                    <div class="aui-list-item-title">关于我们</div>
                </div>
            </li>
        </ul>

        <div class="aui-content-padded">
            <a class="aui-btn aui-btn-block aui-btn-sm aui-btn-danger" href="{{route('logout')}}">退出登录</a>

        </div>
    </section>

    @include('foot')
@endsection


@section('script')
<script>
$(document).on('click' , '.member-link .aui-list-item' , function(){
    if( $(this).data('href') ) {
        location.href = $(this).data('href')
    }
});
</script>
    @include('footscript')
@endsection