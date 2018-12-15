@foreach( $list->items() as $val )
    <div class="aui-card-list">
        <a href="{{route('wap.adv.show' , ['id' => $val->id ])}}">
        <div class="aui-card-list-header aui-card-list-user aui-border-b">
            <span class="aui-text-danger aui-text-bold aui-font-size-20">{{$val->click_price}}</span>
            <span class="aui-font-size-16">{{$val->title}}</span>
            <span class="aui-pull-right aui-font-size-16 aui-label {{ $val->pay_status  ? "aui-label-success" : "aui-label-danger" }}" style="margin-top:.3rem;">
                {{ $val->pay_status  ? "已支付" : "未支付" }}
            </span>
        </div>
        <div class="aui-card-list-content-padded">
                        价格:
                        <span class="color-red aui-font-size-12"> {{ $val->click_price}}&nbsp;</span>/<span class="color-red aui-font-size-12"> {{$val->total_price}}&nbsp;</span>)
                    <br/>
                        点击:
                        (<span class="color-red aui-font-size-12"> {{$val->has_click_times}}&nbsp;</span>/<span class="color-red aui-font-size-12"> {{$val->click_times}}&nbsp;</span>)
                    <br/>

                        赞/踩       (<span class="color-red aui-font-size-12"> {{$val->up_times}}&nbsp;</span>/<span class="color-red aui-font-size-12"> {{$val->down_times}}&nbsp;</span>)

                        (<span class="color-red aui-font-size-12"> {{$val->up_times}}&nbsp;</span>/<span class="color-red aui-font-size-12"> {{$val->down_times}}&nbsp;</span>)

        </div>
        </a>
        <div class="aui-card-list-footer aui-border-t">
            <div><i class="aui-iconfont aui-icon-date"></i> {{$val->created_at}}</div>
            @if( $val->pay_status == 0 )
                <a href="{{route('wap.adv.edit' , ['id' => $val->id ])}}"><i class="aui-iconfont aui-icon-pencil"></i>编辑</a>
                <a class="btn-pay" data-href="{{route('wap.adv.pay' , ['id' => $val->id ])}}"><i class="aui-iconfont aui-icon-cart"></i> 去支付</a>
            @endif
        </div>
    </div>
@endforeach