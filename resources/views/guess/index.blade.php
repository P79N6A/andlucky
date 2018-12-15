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
    

</style>
@endsection

@section('content')
    <header class="aui-bar aui-bar-nav">
        <div class="aui-tab">
            <div class="aui-tab-item aui-active">
                押大小
            </div>
            <a class="aui-tab-item " href="{{route('wap.game.index')}}">
                比大小
            </a>
            <a class="aui-tab-item" href="{{route('wap.guess.mine')}}">
                手气
            </a>

        </div>

    </header>
    <div class="aui-tab" style="justify-content: flex-end;">
        <a href="{{route('wap.guess.create')}}" class="aui-btn aui-pull-right" style="display: inline-block;margin: .25rem .5rem;background: none;color:#734d41;">点我开台</a>
    </div>
    <section class="aui-content" >
        <div class="guess-list" id="guess-content">
            @include('guess.indexitem')
        </div>
    </section>
    <div class="aui-popup aui-popup-bottom" id="guess-bottom" style="display: none;left:0rem;right:0rem;bottom:0rem;margin-left:0rem;">
        <div class="aui-popup-content" style="border-radius: 0;padding-top:.25rem;">
            <div class="aui-content-padded">
                <p id="rate-desc" class="aui-text-danger aui-font-size-18">

                </p>
            </div>
            <ul class="aui-list aui-form-list">
                <li class="aui-list-item">
                    <div class="aui-list-item-inner">
                        <div class="aui-list-item-label">
                            投入
                        </div>
                        <div class="aui-list-item-input" style="padding-right: 0rem;">
                            <input type="number" id="promise-cash" placeholder="">
                        </div>
                    </div>
                </li>
                <li class="aui-list-item">
                    <div class="aui-list-item-inner">
                        <div class="aui-list-item-label">
                            大小
                        </div>
                        <div class="aui-list-item-input">
                            <label><input class="aui-radio" type="radio" value="max" name="seed" checked="">大</label>
                            <label id="mid-item" class="aui-hide"><input class="aui-radio" type="radio" value="mid" name="seed" >中</label>
                            <label><input class="aui-radio" type="radio" value="min" name="seed">小</label>
                        </div>
                    </div>
                </li>
                <li class="aui-list-item">
                    <div class="aui-list-item-inner aui-list-item-center aui-list-item-btn">
                        <div class="aui-btn aui-btn-block aui-btn-sm aui-btn-success btn-battle">确认参与</div>
                    </div>
                </li>
            </ul>
        </div>
    </div>
    @include('foot')
@endsection


@section('script')
    <script src="{{asset('packages/aui/script/aui-scroll.js')}}"></script>
    @include('footscript')
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
        $.getJSON( '{{route('wap.guess.index')}}' , { page : currentPage } , function( data ){
            loading = false
            if( data.errcode === 0 ) {
                $('#guess-content').append( data.data )
            }
        }) ;
    });

    var login_user = {{ data_get( auth()->guard('wap')->user() , 'id' , 0 ) }} ;
    var id = 0 ;
    var rate = '' ;
    var maxCash = 0 ;
    $( document ).on('click' , '.btn-join' , function(){
        id = $(this).data('id');
        rate = $(this).data('rate');
        $('#rate-desc').html('当前赔率为' + rate );
        if( '1.97' == rate ) {
            $('#mid-rate').addClass('aui-hide');
        } else {
            $('#mid-rate').removeClass('aui-hide')
        }
        var cash = $(this).data('cash');
        var occupy_cash = $(this).data('occupy_cash') ;
        maxCash = Math.floor( cash / rate  - occupy_cash ) ;
        var tip = "您最多可以投入狗"+ maxCash +"粮"
        $('#promise-cash').attr('placeholder' , tip );
        if( !login_user ) {
            toast.fail({
                title : '您还没有登录，请选登录'
            });
            return false ;
        }
        pop_user = $(this).data('user_id');
        if( pop_user == login_user ) {
            toast.fail({
                title : '不能参加自己发起的活动'
            });
            return false ;
        }
        popup.show( document.getElementById("guess-bottom") )
    })
    $(document).on('click' , '.btn-battle' , function(){
        var cash = $('#promise-cash').val().trim();
        cash = parseInt( cash );
        cash = isNaN( cash ) ? 0 : cash ;
        var seed = $("input[name='seed']:checked").val().trim();
        if( cash <= 0 ) {
            toast.fail( {
                title : '请填写铜板' ,
                duration : 1500
            });
            return false ;
        }
        if( 1 > cash ) {
            toast.fail( {
                title : '铜板最小为1' ,
                duration : 1500
            });
            return false ;
        }
        if( maxCash < cash ) {
            toast.fail( {
                title : "铜板最大为" + maxCash  ,
                duration : 1500
            });
            return false ;
        }
        $.post('/guess/take/' + id , {
            'money' : cash ,
            'seed' : seed ,
            '_token' : '{{csrf_token()}}'
        } , function(  data ){

            if( data.errcode === 0 ) {
                //邀请成功 回到发送信息的页面
                toast.success({
                    title : data.msg ,
                    duration :1500
                }) ;
                setTimeout( function(){
                    location.href = data.url
                } , 1500 );
            } else{
                toast.fail({
                    title : data.msg ,
                    duration :1500
                }) ;
                if( data.errcode == 10004 ) {
                    setTimeout( function(){
                        location.href="{{route('member.chargeform')}}" ;
                    } , 2500 );
                }

            }

        });
        popup.hide();
    })
</script>
@endsection