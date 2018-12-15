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
        <div class="aui-title">
            <img src="{{asset('images/logo.png')}}" style="width: 2.25rem;height:2.25rem;position: absolute;left:1.25rem;" />
            <span style="line-height:2.25rem;position: absolute;left:3.75rem;">
            连运
            </span>
        </div>
        <a class="aui-pull-right aui-btn" href="{{route('wap.notice.index')}}">
            <span class="aui-iconfont aui-icon-mail"></span>
        </a>
    </header>
    <div class="swiper-container" id="tab">
        <div class="swiper-wrapper" >
            @foreach( $cate as $val )
            <div class="aui-tab-item swiper-slide {{ $select == data_get( $val , 'id') ? 'aui-active' :'' }} " >
                <a href="{{route('microblog.index' , ['cate_id' => data_get( $val , 'id') ] ) }}">
                {{data_get( $val , 'title')}}
                </a>
            </div>
            @endforeach
            <div class="aui-tab-item swiper-slide">
                <a href="{{route('adv.index')}}">
                广告
                </a>
            </div>
        </div>
    </div>
    <section class="aui-content" id="microblog-content" style="padding-bottom: 2.5rem;">
        @include('home.index-item')
    </section>

    @include('foot')

@endsection


@section('script')
<script src="{{asset('packages/swiper/js/swiper.min.js')}}"></script>
<script src="{{asset('packages/aui/script/aui-scroll.js')}}"></script>
<script>
    //处理tab
    var mySwiper = new Swiper('.swiper-container',{
        slidesPerView: 5 ,
        spaceBetween: 5 ,
        //其他设置
    });
    mySwiper.slideTo( {{ $index }}) ;

    $(document).on('click' , '.aui-card-list' , function(){
        location.href = $(this).data('href')
    })


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
        $.getJSON( '{{route('microblog.index' , ['cate_id' => $select ])}}' , { page : currentPage } , function( data ){
            loading = false
            if( data.errcode === 0 ) {
                $('#microblog-content').append( data.data )
            }
        }) ;
    });
</script>
@include('footscript')
@endsection