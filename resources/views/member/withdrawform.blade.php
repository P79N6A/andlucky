@extends('layout')

@section('style')

@endsection

@section('content')
    <header class="aui-bar aui-bar-nav">
        <a class="aui-pull-left aui-btn" onclick="history.back()">
            <span class="aui-iconfont aui-icon-left"></span>
        </a>
        <div class="aui-title">提现</div>

    </header>
    <section class="aui-content aui-margin-b-10">
        <ul class="aui-list aui-media-list">
            <li class="aui-list-header">
                提现说明
            </li>
            <li class="aui-list-item">
                <div class="aui-media-list-item-inner">
                    <div class="aui-list-item-inner">
                        <div class="aui-list-item-text aui-ellipsis-2">
                                铜板汇率为:1
                        </div>
                        <div class="aui-list-item-text aui-ellipsis-2">
                            您当前铜板为{{$user->cash_reward}}点，最大可提现部分为{{$max}}点
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
                        <input type="number" id="cash" placeholder="请输入提现铜板" style="text-align: center;">
                    </div>
                </div>
            </li>
            <li class="aui-list-item">
                <div class="aui-list-item-inner aui-list-item-center aui-list-item-btn">
                    <div class="aui-btn aui-btn-block aui-btn-sm aui-btn-danger btn-withdraw">提现</div>
                </div>
            </li>
        </ul>
    </section>




@endsection

@section('script')
<script>
    $('.btn-withdraw').unbind('click').bind('click' , throttle( charge , 1500 ) );

    function charge(){
        var cash = $('#cash').val().trim();
        cash = parseFloat( cash )
        cash = isNaN( cash ) ? 0 : cash
        cash = cash.toFixed( 2 )
        if( cash == 0 ) {
            return
        }

        if( cash > {{$max }}) {
            toast.fail({
                title:"您超过了最大提取额度" ,
                duration:1500
            });
            return false
        }
        $.post('{{route('member.withdraw')}}' , {
            '_token' : '{{csrf_token()}}' ,
            'cash' : cash
        } , function( data ) {
            if( data.errcode === 0 ) {
                toast.success({
                    title:"申请成功请等待审核" ,
                    duration:1500
                });
                setTimeout(function(){
                    history.back()
                } , 1500 )
            } else {
                //接口数据获取错误
                toast.fail({
                    title: data.msg ,
                    duration:1500
                });
            }

        });

    }
</script>
@endsection
