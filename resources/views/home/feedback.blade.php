@extends('layout')

@section('style')

@endsection

@section('content')
    <header class="aui-bar aui-bar-nav">
        <a class="aui-pull-left aui-btn" onclick="history.back()">
            <span class="aui-iconfont aui-icon-left"></span>
        </a>
        <div class="aui-title">意见反馈</div>

    </header>
    <section class="aui-content">
        <ul class="aui-list aui-form-list">
            <li class="aui-list-item">
                <div class="aui-list-item-inner">
                    <div class="aui-list-item-label">
                        手机号码
                    </div>
                    <div class="aui-list-item-input">
                        <input id="mobile" type="text" placeholder="请输入联系方式" value="{{ data_get( $user , 'mobile') }}">
                    </div>
                </div>
            </li>

            <li class="aui-list-header">反馈内容</li>
            <li class="aui-list-item">
                <div class="aui-list-item-inner">
                    <div class="aui-list-item-input">
                        <textarea id="content" placeholder="请输入反馈内容" onkeyup="countCharacter()"></textarea>
                    </div>
                </div>
            </li>
            <li class="aui-list-item">
                <div class="aui-list-item-inner">
                    <div id="character-tip" class="item-title label color-gray">0/200</div>
                    <div id="error-tip" class="item-input text-right color-gray">
                            内容控制在200字左右
                    </div>
                </div>
            </li>
            <li class="aui-list-item">
                <div class="aui-list-item-inner aui-list-item-center aui-list-item-btn">
                    <div class="aui-btn aui-btn-info aui-margin-r-5 btn-feedback" >提交</div>
                    <div class="aui-btn aui-btn-danger aui-margin-l-5 btn-reset">取消</div>
                </div>
            </li>
        </ul>
    </section>

@endsection


@section('script')
    <script>
        $('.btn-feedback').unbind('click' ).bind('click' , throttle( feedback , 1500 )) ;
        $('.btn-reset').unbind('click' ).bind('click' , throttle( function(){ $('#content').val('') ;} , 1500 )) ;
        function countCharacter() {
            var content = $('#content').val().trim();
            i = content.length ;
            if ( i > 200 ) {
                content = content.substring( 0 , 200 )
                i = content.length
                console.log( i )
                $('#content').val( content );
            }
            $('#character-tip').text( i + '/200')
        }
        function feedback() {
            var mobile = $('#mobile').val().trim();
            var content = $('#content').val().trim();
            if( !mobile ) {
                toast.fail({
                    title:"请填写您的联系方式!" ,
                    duration:1500
                });
                return false ;
            }
            if( !content ) {
                toast.fail({
                    title:"请填写您的建议内容!" ,
                    duration:1500
                });
                return false ;
            }
            //检查用户铜板是不是满足发布的最小要求
            var post = {
                'mobile': mobile ,
                'content' : content ,
                '_token':'{{csrf_token()}}'
            };
            $.post('/feedback' , post  , function( data ){
                if( data.errcode === 0) {
                    toast.success({
                        title: data.msg ,
                        duration:1500
                    });
                    setTimeout( function(){
                        history.back()
                    } , 1500 )
                } else {
                    toast.fail({
                        title: data.msg ,
                        duration:1500
                    });
                }
            })
        }
    </script>

@endsection
