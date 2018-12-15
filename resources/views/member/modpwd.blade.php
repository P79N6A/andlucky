@extends('layout')

@section('style')

@endsection

@section('content')
    <header class="aui-bar aui-bar-nav">
        <a class="aui-pull-left aui-btn" onclick="history.back()">
            <span class="aui-iconfont aui-icon-left"></span>
        </a>
        <div class="aui-title">修改密码</div>

    </header>
    <div class="aui-content aui-margin-b-15">
        <ul class="aui-list aui-form-list aui-margin-b-10">
            <li class="aui-list-item">
                <div class="aui-list-item-inner">
                    <div class="aui-list-item-label">
                        旧密码
                    </div>
                    <div class="aui-list-item-input">
                        <input id="old-pwd" type="password" placeholder="请输入旧密码">
                    </div>
                </div>
            </li>
            <li class="aui-list-item">
                <div class="aui-list-item-inner">
                    <div class="aui-list-item-label">
                        新密码
                    </div>
                    <div class="aui-list-item-input">
                        <input id="new-pwd" type="password" placeholder="请输入新密码">
                    </div>
                </div>
            </li>
        </ul>

    </div>
    <div class="aui-content-padded" >
        <div class="aui-btn aui-btn-danger aui-btn-block aui-btn-fill aui-btn-sm btn-modpwd">确认修改</div>
    </div>

@endsection


@section('script')
    <script>
        $('.btn-modpwd').unbind('click').bind('click' , throttle( modpwd , 1500 ) )
        function modpwd () {
            var oldPwd = $('#old-pwd').val().trim();
            var newPwd = $('#new-pwd').val().trim();
            if( !oldPwd ) {
                toast.fail({
                    title:'请填与旧密码' ,
                    duration:1500
                })
                return false ;
            }
            if( !newPwd ) {
                toast.fail({
                    title:'请填与新密码' ,
                    duration:1500
                })
                return false ;
            }
            if( oldPwd == newPwd ) {
                toast.fail({
                    title:'两次密码填写一致' ,
                    duration:1500
                })
                return false ;
            }
            var post = {
                'oldpwd': oldPwd ,
                'password' : newPwd ,
                '_token': '{{csrf_token()}}'
            };
            $.post('{{route('member.modpwd')}}' , post  , function( data ){
                if( data.errcode === 0 ) {
                    toast.success({
                        title:data.msg ,
                        duration:1500
                    })
                    setTimeout( function(){
                        history.back()
                    } , 1500 );
                } else {
                    toast.fail({
                        title:data.msg ,
                        duration:1500
                    })
                }
            })
        }
    </script>
@endsection