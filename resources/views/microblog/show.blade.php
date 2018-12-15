@extends('layout')

@section('style')
    <style>
        .aui-icon-laud:before {
            transform:rotate(180deg);
        }
        p {
            text-indent: 2em;
            margin-bottom: .75rem;
        }
        .blog-adv img {
            max-width: 100%;
        }
    </style>
@endsection

@section('content')
<header class="aui-bar aui-bar-nav" id="aui-header">
    <a class="aui-btn aui-pull-left" tapmode="" onclick="history.back()">
        <span class="aui-iconfont aui-icon-left"></span>
    </a>
    <div class="aui-title">{{$blog->title}}</div>
</header>
<section class="aui-content" id="microblog-content">
    @if( data_get( $blog , 'adv_pos' ) == 'top' )
    <div class="blog-adv">
        @if( data_get( $blog , 'adv_link' ) )
        <a href="{{data_get( $blog , 'adv_link' )}}" >
            <img src="{{ asset( data_get( $blog , 'adv_cover' )) }}"  />
        </a>
        @else
            @if( data_get( $blog , 'adv_cover' ) )
        <img src="{{ asset( data_get( $blog , 'adv_cover' )) }}" />
            @endif
        @endif
    </div>
    @endif
    <div class="aui-card-list">
        <div class="aui-card-list-header aui-card-list-user">
            {{$blog->title}}
        </div>
        <div class="aui-card-list-header aui-card-list-user aui-border-b">
            <div class="aui-card-list-user-avatar">
                <img src="{{asset( data_get( $blog->user , 'avatar' , 'images/logo.png' )) }}" class="aui-img-round" />
            </div>
            <div class="aui-card-list-user-name">
                <div>{{data_get( $blog->user , 'nickname' , '连运' )}}</div>
                <!--<small>1天前</small>-->
            </div>
            <div class="aui-card-list-user-info">{{$blog->created_at }}</div>
        </div>
        <div class="aui-card-list-content-padded">
            <p>
            {!! str_replace( PHP_EOL , '</p><p>' , (  data_get( $blog , 'content' ) ) ) !!}
            </p>
        </div>
        @if( data_get( $blog->extra , 'type') == 'image' )
            <div class="aui-row aui-row-padded">
                @foreach( data_get( $blog->extra , 'data' , [] ) as $img )
                        <img src="{{asset( $img ) }}" />
                @endforeach
            </div>
        @endif
        <div class="aui-card-list-footer aui-border-t">
            <div><i class="aui-iconfont aui-icon-note"></i> <i id="comment-count">{{$blog->comment_count}}</i></div>
            <div class="microblog-praise">
                <span style="transform: rotate(180deg);display:inline-block;">
                <i class="aui-iconfont aui-icon-laud {{ $has_praise ? 'active' : '' }}"></i>
                </span>
                <i id="praise-count">{{$blog->prase }}</i></div>
            <div><i class="aui-iconfont aui-icon-display"></i> {{$blog->views }}</div>
        </div>
    </div>
        @if( data_get( $blog , 'adv_pos' ) == 'bottom' )
            <div class="blog-adv">
                @if( data_get( $blog , 'adv_link' ) )
                    <a href="{{data_get( $blog , 'adv_link' )}}" >
                        <img src="{{ asset( data_get( $blog , 'adv_cover' )) }}"  />
                    </a>
                @else
                    @if( data_get( $blog , 'adv_cover' ) )
                        <img src="{{ asset( data_get( $blog , 'adv_cover' )) }}" />
                    @endif
                @endif
            </div>
        @endif
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
@if( auth()->guard('wap')->check() )
//获取铜板
setTimeout( function(){
    $.post( "{{route('microblog.gain' , ['id' => $blog->id ])}}" , { _token : '{{ csrf_token() }}'} , function( data ){
        if( data.errcode === 0 ) {
            toast.success({
                title: data.msg ,
                duration:1500
            });
        }
    }) ;
} , {{$countdown}} * 1000 );
@endif
var currentPage = 1 ;
var totalPage = 0 ;
var loading = false ;

//获取评论
function loadComments(  ) {
    $.get("{{route('comments')}}" , { page : currentPage , 'id' : '{{$blog->id}}' , 'parent_id' : '{{$blog->id}}' , type :'blog'  } , function( data ){
        if( data.errcode === 0 ) {
            if( data.total === 0 ) {
                $('.no-data').removeClass('aui-hide');
            }
            totalPage = data.totalPage ;
            page = data.currentPage + 1 ;
            $('#microblog-comment ul').append( data.html )
            bindEvent();
            $('.no-data').addClass('aui-hide');
        }
    });
}

$('.send-comment').unbind('click').bind('click' , throttle( sendComment , 1500 ) )


$('.microblog-praise').unbind('click').bind('click' , throttle( miroblogPraise , 1500 ) )
//发表评论
function sendComment() {
    var comment = $('#comment-content').val().trim()
    if( !comment ) {
        return false ;
    }
    $.post('/comment/store' , {
        'id': {{$blog->id}} ,
        'type' : 'blog' ,
        'content' : comment ,
        '_token' :  '{{ csrf_token() }}'
    } , function( data ){
        if( data.errcode === 0 ) {
            toast.success({
                'title' : data.msg ,
                'duration':1500
            })
            $('#comment-content').val('') ;
            $('#comment-count').text( parseInt( $('#comment-count').text() )+ 1 );
            $('#microblog-comment ul').prepend( data.html );
            bindEvent();
        } else {
            toast.fail({
                'title' : data.msg ,
                'duration':1500
            })
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

//点赞
function miroblogPraise( e ) {
    console.log( e )
    $.post( "{{ route('microblog.praise') }}" , {
        'id': {{$blog->id}} ,
        'type' : 'microblog' ,
        '_token' : '{{ csrf_token() }}'
    } ,  function( data ){
        if( data.errcode === 0 ) {
            toast.success({
                title:data.msg ,
                duration:1500
            })
            if( data.act == 'dec' ) {
                $('#praise-count').text( parseInt( $('#praise-count').text() ) - 1 )
                $( e ).find('i.aui-iconfont').removeClass('active');
            } else {
                $('#praise-count').text( parseInt( $('#praise-count').text() ) + 1 )
                $( e ).find('i.aui-iconfont').addClass('active');
            }
        } else {
            toast.fail({
                'title' : data.msg ,
                'duration':1500
            })
        }
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

