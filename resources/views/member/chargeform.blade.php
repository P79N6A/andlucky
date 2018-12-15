@extends('layout')

@section('style')

@endsection

@section('content')
    <header class="aui-bar aui-bar-nav">
        <a class="aui-pull-left aui-btn" onclick="history.back()">
            <span class="aui-iconfont aui-icon-left"></span>
        </a>
        <div class="aui-title">充值</div>

    </header>
    <section class="aui-content aui-margin-b-10">
        <ul class="aui-list aui-media-list">
            <li class="aui-list-header">
                充值规则
            </li>
            <li class="aui-list-item">
                <div class="aui-media-list-item-inner">
                    <div class="aui-list-item-inner">
                        <div class="aui-list-item-text aui-ellipsis-2">
                            1元人民币可充1个铜板。
                        </div>
                    </div>
                </div>
            </li>
        </ul>
    </section>

    <section class="aui-content aui-margin-b-10">
        <ul class="aui-list aui-form-list">
            <li class="aui-list-item">
                <div class="aui-list-item-inner">
                    <div class="aui-list-item-input">
                        <input type="number" id="cash" placeholder="请输入充值铜板" style="text-align: center;">
                    </div>
                </div>
            </li>
            <li class="aui-list-item">
                <div class="aui-list-item-inner aui-list-item-center aui-list-item-btn">
                    <div class="aui-btn aui-btn-block aui-btn-sm aui-btn-danger btn-charge">充值</div>
                </div>
            </li>
        </ul>
    </section>




@endsection

@section('script')
<script>
    $('.btn-charge').unbind('click').bind('click' , throttle( charge , 1500 ) );

    function charge(){
        console.log( 'cccc')
        var cash = $('#cash').val().trim();
        cash = parseFloat( cash )
        cash = isNaN( cash ) ? 0 : cash
        cash = cash.toFixed( 2 )
        if( cash == 0 ) {
            return
        }
        $.post('{{route('member.chargeform')}}' , {
            '_token' : '{{csrf_token()}}' ,
            'cash' : cash
        } , function( data ) {
            if( data.errcode === 0 ) {
                location.href = data.url
            } else {
                //接口数据获取错误
                history.back()
            }

        });
    }
</script>
@endsection
