@extends('layout')

@section('style')

@endsection

@section('content')
    <header class="aui-bar aui-bar-nav">
        <a class="aui-pull-left aui-btn" onclick="history.back()">
            <span class="aui-iconfont aui-icon-left"></span>
        </a>
        <div class="aui-title">猜大小</div>

    </header>
    <section class="aui-content">
        <ul class="aui-list aui-form-list">
            <li class="aui-list-item">
                <div class="aui-list-item-inner">
                    <div class="aui-list-item-label">
                        庄池
                    </div>
                    <div class="aui-list-item-input">
                        <input type="number" id="cash" placeholder="请输入您希望使用的铜板" value="">
                    </div>
                </div>
            </li>
            <li class="aui-list-item">
                <div class="aui-list-item-inner">
                    <div class="aui-list-item-label">
                        赔率
                    </div>
                    <div class="aui-list-item-input">
                        <label><input class="aui-radio change-seed" type="radio" value="1.97" name="rate" checked> 1.97</label>
                        <label><input class="aui-radio change-seed" type="radio" value="2.97" name="rate"> 2.97</label>
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
                <div class="aui-list-item-inner">
                    <div class="aui-list-item-label">
                        限时
                    </div>
                    <div class="aui-list-item-input">
                        <select name="timer" class="timer" >
                            <option value="1">1小时</option>
                            <option value="2">2小时</option>
                            <option value="3">3小时</option>
                            <option value="4">4小时</option>
                        </select>
                    </div>
                </div>
            </li>
            <li class="aui-list-item">
                <div class="aui-list-item-inner aui-list-item-center aui-list-item-btn">
                    <div class="aui-btn aui-btn-block aui-btn-success aui-margin-r-5 btn-guess">提交</div>
                </div>
            </li>
        </ul>
    </section>

@endsection


@section('script')
<script>
$('.btn-guess').unbind('click' ).bind('click' , throttle( guess , 1500 )) ;
$(".change-seed").change( function(){
    var type = $("input[name='rate']:checked").val().trim();
    if( '2.97' == type ) {
        $('#mid-item').removeClass('aui-hide') ;
    } else {
        $('#mid-item').addClass('aui-hide');
        var seed = $("input[name='seed']:checked").val().trim();
        if( 'mid' == seed ) {
            $("input[value='max']").prop('checked' , true );
        }
    }
});
function guess() {
    var cash = $('#cash').val().trim();
    var type = $("input[name='rate']:checked").val().trim();
    var seed = $("input[name='seed']:checked").val().trim();
    var timer = $('.timer').val().trim();
    if( !cash ) {
        toast.fail({
            title : '请填写提供的奖励铜板' ,
            duration : 1500
        }) ;
        return false
    }
    if( cash < 1 ) {
        toast.fail({
            title : '铜板太少了吧' ,
            duration : 1500
        }) ;
        return false
    }
    if( !type ) {
        toast.fail({
            title : '请选择一个赔率' ,
            duration : 1500
        }) ;
        return false
    }
    if( !seed ) {
        toast.fail({
            title : '请选择要放的大小' ,
            duration : 1500
        }) ;
        return false
    }
    $.post('{{ route('wap.guess.create') }}' , {
        cash : cash ,
        type : type ,
        seed : seed ,
        last : timer ,
        '_token':"{{csrf_token()}}" ,
    } , function( data ){
        if( data.errcode === 0 ) {
            toast.success({
                title : data.msg ,
                duration : 1500
            }) ;
            setTimeout( function(){
                location.href= data.url
            } , 1500 )

        } else {
            toast.fail({
                title : data.msg  ,
                duration : 1500
            }) ;
            if( data.errcode === 3000 ) {
                setTimeout( function(){
                    location.href = "{{route('member.chargeform')}}" ;
                } , 1500 );
            }
        }
    })
}
</script>
@endsection