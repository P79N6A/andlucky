@extends('layout')

@section('style')

@endsection

@section('content')
    <header class="aui-bar aui-bar-nav">
        <a class="aui-pull-left aui-btn" onclick="history.back()">
            <span class="aui-iconfont aui-icon-left"></span>
        </a>
        <div class="aui-title">注册</div>

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
                    <div class="aui-list-item-label">
                        手机号码
                    </div>
                    <div class="aui-list-item-input">
                        <input id="mobile" type="text" placeholder="请输入手机号码">
                    </div>
                </div>
            </li>
            <li class="aui-list-item">
                <div class="aui-list-item-inner">
                    <div class="aui-list-item-label">
                        昵称
                    </div>
                    <div class="aui-list-item-input">
                        <input id="nickname" type="text" placeholder="请输入昵称">
                    </div>
                </div>
            </li>
            <li class="aui-list-item">
                <div class="aui-list-item-inner">
                    <div class="aui-list-item-label">
                        验证码
                    </div>
                    <div class="aui-list-item-input">
                        <input id="verify" type="text" placeholder="请输入验证码">
                    </div>
                        <a class="aui-btn btn-sendverify" style="position: absolute;right:1rem;">获取验证码</a>
                </div>
            </li>
            <li class="aui-list-item">
                <div class="aui-list-item-inner">
                    <div class="aui-list-item-label">
                        密码
                    </div>
                    <div class="aui-list-item-input">
                        <input id="password" type="text" placeholder="请输入密码">
                    </div>
                </div>
            </li>
            <li class="aui-list-item">
                <div class="aui-list-item-inner">
                    <div class="aui-list-item-label">
                        确认密码
                    </div>
                    <div class="aui-list-item-input">
                        <input id="confirm_password" type="text" placeholder="请再次输入密码">
                    </div>
                </div>
            </li>
            <li class="aui-list-item">
                <div class="aui-list-item-inner">
                    <div class="aui-list-item-label">
                        推荐码
                    </div>
                    <div class="aui-list-item-input">
                        <input id="invited_code" type="text" placeholder="选填">
                    </div>
                </div>
            </li>
            <li class="aui-list-item">
                <div class="aui-list-item-inner">
                    <label>
                        <input class="aui-checkbox" type="checkbox" name="radio" id="agree-info">
                        <a class="btn-agreeinfo">《注册协议》</a>
                    </label>
                </div>
            </li>
            <li class="aui-list-item">
                <div class="aui-list-item-inner aui-list-item-center aui-list-item-btn">
                    <div class="aui-btn aui-btn-block aui-btn-sm aui-btn-success btn-register">注册</div>
                </div>
            </li>
        </ul>
    </div>

    <div class="aui-content aui-margin-b-15 text-center">
        <p>已有号码？<a href="{{ route('login') }}" class="aui-text-danger">点击登录</a> , 忘记密码？
            <a href="{{route('findpwd')}}" class="aui-text-danger">点击找回</a></p>
    </div>

    <div class="aui-popup aui-popup-bottom" id="agreeinfo-bottom" style="display: none;left:0rem;right:0rem;bottom:0rem;margin-left:0rem;height: 100%;overflow-y: scroll;">
        <div class="aui-popup-content" style="border-radius: 0;padding:.0rem;overflow-y: scroll;">
            <header class="aui-bar aui-bar-nav">
                <a class="aui-pull-left aui-btn btn-close">
                    <span class="aui-iconfont aui-icon-close"></span>
                </a>
                <div class="aui-title">注册协议</div>

            </header>
            <div class="aui-content-padded">
            @include('agreeinfo')
            </div>
        </div>
    </div>
@endsection


@section('script')
<script src="{{asset('packages/aui/script/aui-popup.js')}}"></script>
<script>
    $('.btn-register').unbind('click').bind('click' , throttle( register , 1500 )) ;
    $('.btn-sendverify').unbind('click').bind('click' , throttle( sendVerify , 1500 )) ;

    var popup = new auiPopup();
    $('.btn-agreeinfo').click( function(){
        popup.show( document.getElementById('agreeinfo-bottom')) ;
    } );

    $('.btn-close').click( function(){
        popup.hide()
    })
    var sending = 0 ;
    function sendVerify(){
        if( sending > 0 ) {
            return false
        }
        var mobile = $('#mobile').val().trim()
        if( !mobile ) {
            toast.fail({
                'title' : '请填写手机号码'
            });
            return false
        }


        var post = {
            'phone': mobile ,
            '_token': "{{csrf_token()}}"
        };
        sending = 1
        $.post( '/sendmobilesms' , post  , function( data ){
            if( data.errcode === 0 ) {
                sending = data.left ;
                var btnVerify = sending + 's重新发送' ;
                $('.btn-sendverify').html( btnVerify );
                var timer = setInterval(function(){
                    sending -- ;
                    var btnVerify = sending + 's重新发送' ;
                    if( sending == 0 ) {
                        clearInterval( timer );
                        var btnVerify = '获取验证码'
                    }
                    $('.btn-sendverify').html( btnVerify );
                } , 1000);
            } else {
                toast.fail({
                    title : data.msg
                })
            }
        });
    }

    function register() {
        var mobile = $('#mobile').val().trim();
        var nickname = $('#nickname').val().trim();
        var verify = $('#verify').val().trim();
        var password = $('#password').val().trim();
        var confirm_password = $('#confirm_password').val().trim();
        var invited_code = $('#invited_code').val().trim();
        var agree = $('#agree-info').prop('checked');
        if( !mobile ) {
            toast.fail({
                title : '请填写手机号码'
            })
            return false
        }
        if( !nickname ) {
            toast.fail({
                title : '请填写昵称方便与他人交流'
            })
            return false
        }
        if( !verify ) {
            toast.fail({
                title : '请填写手机验证码'
            })
            return false
        }
        if( !password ) {
            toast.fail({
                title : '请填写密码'
            })
            return false
        }
        if( !confirm_password ) {
            toast.fail({
                title : '请再次输入密码'
            })
            return false
        }
        if( confirm_password != password ) {
            toast.fail({
                title : '两次输入密码不一致'
            })
            return false
        }
        if( !agree ) {
            toast.fail({
                title : '请先同意注册协议'
            })
            return false
        }
        var post = {
            name: mobile ,
            nickname : nickname ,
            password : password ,
            comfirmed : confirm_password ,
            verify : verify ,
            invite_by : invited_code ,
            _token: "{{csrf_token()}}"
        };
        $.post( '/register' , post  , function( data ){
            if( data.errcode === 0 ) {
                if( data.errcode === 0 ) {
                    toast.success({
                        title : '注册成功'
                    });

                     setTimeout( function(){
							location.href = data.url
                     } , 1500 );
                }
            } else {
                toast.fail({
                    title : data.msg
                });
            }
        });
    }
</script>
@endsection