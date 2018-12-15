@extends('layout')

@section('style')

@endsection

@section('content')
    <header class="aui-bar aui-bar-nav">
        <a class="aui-pull-left aui-btn" onclick="history.back()">
            <span class="aui-iconfont aui-icon-left"></span>
        </a>
        <div class="aui-title">找回密码</div>

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
                <div class="aui-list-item-inner aui-list-item-center aui-list-item-btn">
                    <div class="aui-btn aui-btn-block aui-btn-sm aui-btn-success btn-register">找回密码</div>
                </div>
            </li>
        </ul>
    </div>

    <div class="aui-content aui-margin-b-15 text-center">
        <p><a href="{{ route('login') }}" class="aui-text-danger">返回登录</a></p>
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
        $.post( '/sendfindpwdsms' , post  , function( data ){
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
        var verify = $('#verify').val().trim();
        var password = $('#password').val().trim();
        var confirm_password = $('#confirm_password').val().trim();

        if( !mobile ) {
            toast.fail({
                title : '请填写手机号码'
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
        var post = {
            name: mobile ,
            password : password ,
            comfirmed : confirm_password ,
            verify : verify ,
            _token: "{{csrf_token()}}"
        };
        $.post( '/findpwd' , post  , function( data ){
            if( data.errcode === 0 ) {
                if( data.errcode === 0 ) {
                    toast.success({
                        title : '密码修改成功'
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