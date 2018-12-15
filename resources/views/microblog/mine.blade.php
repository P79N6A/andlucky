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
        <a class="aui-btn aui-pull-left" tapmode="" onclick="history.back()">
            <span class="aui-iconfont aui-icon-left"></span>
        </a>
        <div class="aui-title">我发布的资讯</div>
    </header>
    <section class="aui-content" id="microblog-content" style="padding-bottom: 2.5rem;">
        @include('microblog.mineitem')
    </section>

@endsection


@section('script')
<script src="{{asset('packages/aui/script/aui-scroll.js')}}"></script>
<script src="{{asset('packages/aui/script/aui-dialog.js')}}"></script>
<script>

    var currentPage = {{ $list->currentpage() }} ;
    var totalPage = {{ $list->lastpage() }} ;
    var loading = false ;
    var scroll = new auiScroll({
        listen:true, //是否监听滚动高度，开启后将实时返回滚动高度
        distance:200 //判断到达底部的距离，isToBottom为true
    },function(ret){
        if( loading ) {
            return false ;
        }
        loading = true
        if( currentPage >= totalPage ) {
            return false ;
        }
        currentPage++ ;
        setTimeout( function(){
            loading = false ;
        } , 3000 );
        $.getJSON( '{{route('wap.microblog.mine')}}' , { page : currentPage } , function( data ){
            loading = false
            if( data.errcode === 0 ) {
                $('#microblog-content').append( data.html )
            }
        }) ;
    });

    $(document).on('click' , '.btn-drop' , function(){
        var delUrl = $(this).data('href');
        var that = $(this);
        var dialog = new auiDialog({});
        dialog.alert({
            title:"删除提示",
            msg:'您确定要删除本条资讯吗？',
            buttons:['取消','确定']
        },function(ret){
            if(ret){
                if( ret.buttonIndex == 2 ) {
                    $.post( delUrl , {
                        '_token': "{{csrf_token()}}"
                    }, function (data) {
                        if (data.errcode === 0) {
                            //邀请成功 回到发送信息的页面
                            toast.success({
                                title: data.msg
                            });
                            $(that).parents('.aui-card-list').remove();
                        } else {
                            toast.fail({
                                title: data.msg
                            });
                        }

                    });
                }
            }
        })
    })

</script>
@endsection