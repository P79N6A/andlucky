@extends('layout')

@section('style')

@endsection

@section('content')
    <header class="aui-bar aui-bar-nav">
        <a class="aui-pull-left aui-btn" onclick="history.back()">
            <span class="aui-iconfont aui-icon-left"></span>
        </a>
        <div class="aui-title">好友申请</div>

    </header>
    <section class="aui-content">
        <ul class="aui-list aui-media-list">
            <li class="aui-list-item">
                <div class="aui-media-list-item-inner">
                    <div class="aui-list-item-media">
                        <img src="{{ asset( data_get( $data->user , 'avatar' , 'images/logo.png' ) ) }}" class="aui-img-round">
                    </div>
                    <div class="aui-list-item-inner">
                        <div class="aui-list-item-text">
                            <div class="aui-list-item-title">{{ data_get( $data->user ,'nickname' , config('global.no_nickname')) }}</div>
                        </div>
                        <div class="aui-list-item-text">
                            信用:{{ data_get( $data->user , 'credit') }}&nbsp;,&nbsp;
                            {{ data_get( $data->user , 'not_pay_big_small')}}/{{ data_get( $data->user , 'lose_big_small')}}&nbsp;,&nbsp;
                            {{ data_get( $data->user , 'not_pay_big_small_cash')}}/{{ data_get( $data->user , 'lose_big_small_cash') }}
                        </div>
                    </div>
                </div>
            </li>
        </ul>
        <p class="aui-text-center" style="padding:2rem 0rem;">
            {{ data_get( $data->user ,'nickname' , config('global.no_nickname')) }}向您发起了好友申请，是否同意添加他为好友?
        </p>
    </section>
    <footer class="aui-bar aui-bar-tab">
        <div class="aui-bar-tab-item aui-bg-warning aui-text-white btn-reject" tapmode="" style="width: 8rem;">
            <span class="tab-label">拒绝添加</span>
        </div>
        <div class="aui-bar-tab-item aui-bg-danger aui-text-white btn-accpet" tapmode="" style="width: auto;">
            <span class="tab-label">确认添加</span>
        </div>

    </footer>
@endsection


@section('script')
<script src="{{asset('packages/aui/script/aui-dialog.js')}}"></script>
<script>
    var id = "{{$data->id}}" ;
    $('.btn-accpet').bind('click' , throttle( agree , 1500 ) ) ;

    $('.btn-reject').bind('click' , throttle( disagree , 1500 ) ) ;
    function agree( ) {
        //发送偷钱的请求
        var dialog = new auiDialog({});
        dialog.alert({
            title:"提示",
            msg:'您确定要添加他为好友吗？',
            buttons:['取消','确定']
        },function(ret){
            if(ret) {
                if (ret.buttonIndex == 2) {
                    $.post('/im/accpet', {
                        '_token': "{{csrf_token()}}",
                        'id': id
                    }, function ( data ) {
                        if (data.errcode === 0) {
                            location.href = data.url
                        } else {
                            toast.fail({
                                title:data.msg
                            });
                        }
                    });
                }
            }
        });

    }
    function disagree( ) {
        $.post('/im/disaccpet' , {
            '_token' :"{{csrf_token()}}" ,
            'id' : id
        } , function( data ){
            if( data.errcode === 0 ) {
                return back();
            } else {
                toast.fail({
                    title:data.msg
                });
            }
        });
    }
</script>
@endsection