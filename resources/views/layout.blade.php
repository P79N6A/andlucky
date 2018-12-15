<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="maximum-scale=1.0,minimum-scale=1.0,user-scalable=0,width=device-width,initial-scale=1.0"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>连运网|www.andlucky.com</title>
    <meta content="虚拟商品交易，卖家信用评估，准确卖家信用，私人交易，无淘宝店铺交易" name="Keywords">
    <meta content="QQ群买卖信用保障，微信群交易信用保障" name="description">
    <meta name="format-detection" content="telephone=no,email=no,date=no,address=no">
    <link rel="stylesheet" type="text/css" href="{{asset('packages/aui/css/aui.css')}}" />
    <link rel="stylesheet" type="text/css" href="{{asset('css/luckydog.css')}}" />

</head>
@section('style')

@show
<body>
@yield('content')
</body>
<script type="text/javascript" src="{{asset('js/zepto.min.js')}}"></script>
<script type="text/javascript" src="{{asset('packages/aui/script/aui-toast.js')}}"></script>
<script>
var toast = new auiToast()
function throttle(fn, gapTime) {
    if (gapTime == null || gapTime == "undefined") {
        gapTime = 1500
    }

    var _lastTime = null

    // 返回新的函数
    return function () {
        var _nowTime = + new Date()
        if (_nowTime - _lastTime > gapTime || !_lastTime) {
            fn.apply(this, arguments)   //将this和参数传给原函数
            _lastTime = _nowTime
        }
    }
}

//获取cookie、
function getCookie(name) {
    var arr, reg = new RegExp("(^| )" + name + "=([^;]*)(;|$)");
    if (arr = document.cookie.match(reg))
        return (arr[2]);
    else
        return null;
}

//设置cookie,增加到vue实例方便全局调用
function setCookie (c_name, value, expiredays) {
    var exdate = new Date();
    exdate.setDate(exdate.getDate() + expiredays);
    document.cookie = c_name + "=" + escape(value) + ((expiredays == null) ? "" : ";expires=" + exdate.toGMTString());
};

//删除cookie
function delCookie (name) {
    var exp = new Date();
    exp.setTime(exp.getTime() - 1);
    var cval = getCookie(name);
    if (cval != null)
        document.cookie = name + "=" + cval + ";expires=" + exp.toGMTString();
};


@if( session()->has('error') )
    toast.fail({
        title : '{{session()->get('error')}}'
    }) ;
@endif
function loadErrorImg() {
    $('img').unbind('error').bind('error' ,  function(){
        $(this).attr('src' , '/images/logo.png').unbind('error');
    })
}
loadErrorImg();
$(function(){
    loadErrorImg();
})
</script>
@section('script')

@show
</html>