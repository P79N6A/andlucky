@extends('layout')

@section('style')
<style>
    .aui-tab {
        background: none;
    }
    .aui-tab-item {
        color:#fff ;
    }
    .aui-tab-item.aui-active {
        color:#fff57e ;
        border-bottom: 2px solid #fff57e;
    }
    #tab.aui-tab {
        background: #ffffff;
    }
    #tab .aui-tab-item {
        color:#000 ;
    }
    #tab .aui-tab-item.aui-active {
        color:#734d41 ;
        border-bottom: 2px solid #734d41;
    }

</style>
@endsection

@section('content')
    <header class="aui-bar aui-bar-nav">
        <div class="aui-tab">
            <a class="aui-tab-item"  href="{{route('wap.guess.index')}}">
                押大小
            </a>
            <div class="aui-tab-item" href="{{route('wap.game.index')}}">
                比大小
            </div>
            <a class="aui-tab-item aui-active">
                手气
            </a>

        </div>

    </header>
    <div class="aui-tab" id="tab">
        <a class="aui-tab-item aui-active"  >
            押大小
        </a>
        <a class="aui-tab-item" href="{{route('wap.game.mine')}}">
            比大小
        </a>
    </div>
    <section class="aui-content" style="margin-bottom:3rem;" id="guess-content">
        @include('guess.mineitem')
    </section>
    @include('foot')
@endsection


@section('script')
    @include('footscript')
<script src="{{asset('packages/aui/script/aui-scroll.js')}}"></script>
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
        $.getJSON( '{{route('wap.game.mine')}}' , { page : currentPage } , function( data ){
            loading = false
            if( data.errcode === 0 ) {
                $('#guess-content').append( data.html )
                
            }
        }) ;
    });

    $(document).on('click' , '.aui-card-list' , function(){
        if( $(this).data('href') ) {
            location.href = $(this).data('href')
        }
    })
</script>
@endsection