@extends('layout')

@section('style')

@endsection

@section('content')
    <header class="aui-bar aui-bar-nav">
        <a class="aui-pull-left aui-btn" onclick="history.back()">
            <span class="aui-iconfont aui-icon-left"></span>
        </a>
        <div class="aui-title">登录</div>

    </header>
    <section class="aui-content aui-margin-b-5">
        <p class="text-center" style="background: #fff;margin:0rem;margin-top:.5rem;padding: .5rem;">
            <img src="/images/logo.png" style="width:4rem;margin:0rem auto;" />
        </p>
    </section>
    <div class="aui-content aui-margin-b-15">
        <ul class="aui-list aui-form-list">
            <li class="aui-list-item">
                <div class="aui-list-item-inner">
                    <div class="aui-list-item-label-icon">
                        <i class="aui-iconfont aui-icon-mobile"></i>
                    </div>
                    <div class="aui-list-item-input">
                        <input id="mobile" type="text" placeholder="请输入手机号码">
                    </div>
                </div>
            </li>
            <li class="aui-list-item">
                <div class="aui-list-item-inner">
                    <div class="aui-list-item-label-icon">
                        <i class="aui-iconfont aui-icon-lock"></i>
                    </div>
                    <div class="aui-list-item-input">
                        <input id="password" type="password" placeholder="请输入密码">
                    </div>
                </div>
            </li>
            <li class="aui-list-item">
                <div class="aui-list-item-inner aui-list-item-center aui-list-item-btn">
                    <div class="aui-btn aui-btn-block aui-btn-sm aui-btn-success btn-login">登录</div>
                </div>
            </li>
        </ul>
    </div>

    <div class="aui-content aui-margin-b-15 text-center">
        <p>还没注册？<a href="{{ route('register') }}" class="aui-text-danger">点击注册</a> , 忘记密码？
            <a href="{{route('findpwd')}}" class="aui-text-danger">点击找回</a></p>
    </div>
@endsection


@section('script')
<script type="text/javascript">
    $('.btn-login').unbind('click').bind('click' , throttle( login , 1500 )) ;
    function login() {
        var mobile = $('#mobile').val().trim();
        var password = $('#password').val().trim();
        if( !mobile ) {
            toast.fail({
                title:'请填写手机号码' ,
                duration:1500
            })
            return false ;
        }
        if( !password ) {
            toast.fail({
                title:'请填写密码' ,
                duration:1500
            })
            return false ;
        }

        $.post('{{route('login')}}', {
            name: mobile,
            password: password,
            _token: '{{csrf_token()}}',
        } , function ( data ) {
            if (data.errcode === 0) {
                toast.success({
                    title:data.msg ,
                    duration:1500
                })
                //回到前一页
                setTimeout( function(){
                    location.href = data.url
                } , 1500 );
            } else {
                toast.fail({
                    title:data.msg ,
                    duration:1500
                })
            }
        }) ;
    }
</script>
@endsection