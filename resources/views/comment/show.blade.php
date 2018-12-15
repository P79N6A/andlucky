@extends('layout')


@section('content')
    <header class="aui-bar aui-bar-nav" id="aui-header">
        <a class="aui-btn aui-pull-left" tapmode="" onclick="history.back()">
            <span class="aui-iconfont aui-icon-left"></span>
        </a>
        <div class="aui-title">评论</div>
    </header>
    <section class="aui-content" >
        <ul class="aui-list aui-media-list comments">
            <li class="aui-list-item" >
                <div class="aui-media-list-item-inner">
                    <div class="aui-list-item-media">
                        <img class="aui-img-round" src="{{asset( data_get( $comment->user , 'avatar' , 'images/logo.png'))}}">
                    </div>
                    <div class="aui-list-item-inner">
                        <div class="aui-list-item-text">
                            <div class="aui-list-item-title">{{ data_get( $comment->user , 'nickname' , config('global.nickname') ) }}</div>
                        </div>
                        <div class="aui-list-item-text">
                            {{ data_get( $comment , 'created_at' ) }}
                        </div>
                        <div class="aui-list-item-text aui-ellipsis-2">
                            {{ data_get( $comment , 'content' ) }}
                        </div>

                    </div>
                </div>
            </li>
        </ul>
    </section>

<section class="aui-content" id="microblog-comment" style="padding-bottom: 2.5rem;">
    <ul class="aui-list aui-media-list comments">

    </ul>
    <p class="no-data aui-hide">
        暂无评论，点击抢沙发
    </p>
</section>
<footer class="aui-bar aui-bar-tab" id="footer">
    <div class="aui-row-padded">
        <div class="aui-col-xs-9">
            <input type="text" id="comment-content" style="border-radius: 3px;border:solid #dfdfdf 1px;" />
        </div>
        <div class="aui-col-xs-3" style="line-height: 2.2rem;">
            <a class="aui-btn send-comment" >评论</a>
        </div>
    </div>
</footer>
@endsection


@section('script')
<script src="{{asset('packages/aui/script/aui-scroll.js')}}"></script>
<script>
    var currentPage = 1 ;
    var totalPage = 0 ;
    var loading = false ;

    //获取评论
    function loadComments(  ) {
        $.get("{{route('comments')}}" , { page : currentPage , 'id' : '{{$comment->id}}' , 'parent_id' : '{{$parent_id}}' , type :'comment'  } , function( data ){
            if( data.errcode === 0 ) {
                if( data.total === 0 ) {
                    $('.no-data').removeClass('aui-hide');
                }
                totalPage = data.totalPage ;
                page = data.currentPage + 1 ;
                $('#microblog-comment ul').append( data.html )
                bindEvent();

            }
        });
    }

    $('.send-comment').unbind('click').bind('click' , throttle( sendComment , 1500 ) )


    //发表评论
    function sendComment() {
        var comment = $('#comment-content').val().trim()
        if( !comment ) {
            return false ;
        }
        $.post('/comment/store' , {
            'id': {{$comment->id}} ,
            'type' : 'comment' ,
            'content' : comment ,
            '_token' :  '{{ csrf_token() }}'
        } , function( data ){
            toast.success({
                'title' : data.msg ,
                'duration':1500
            })
            if( data.errcode === 0 ) {
                $('#comment-content').val('') ;
                $('#comment-count').text( parseInt( $('#comment-count').text() )+ 1 );
                $('#microblog-comment ul').prepend( data.html );
                bindEvent();
                $('.no-data').addClass('aui-hide');
            }
        })
    }

    //删除评论
    function dropComment( that ) {
        console.log( that )
        $.post( $(that).data('href') , {
            '_method' : 'delete' ,
            '_token' :  '{{ csrf_token() }}'
        } , function( data ){
            toast.success({
                'title' : data.msg ,
                'duration':1500
            })
            $(that).parents('.comment-show').remove();
            $('#comment-count').text( parseInt( $('#comment-count').text() ) - 1 )
        })
    }

    //
    function bindEvent() {
        $('.comment-show').unbind('click').bind('click' , function(){
            location.href = $(this).data('href') ;
        }) ;
        $('.comment-drop').unbind('click').bind('click' , function( e ) {
            dropComment( this )
            e.stopPropagation();
            e.preventDefault();
            return false ;
        }) ;
    }

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
        setTimeout( function(){
            loading = false ;
        } , 3000 );
        loadComments( )
    });
    loadComments( )
</script>


@endsection