@forelse( $list->items() as $val )
    <div class="aui-card-list" data-href="{{route('wap.adv.show' , ['id' => $val->id ])}}">
        <div class="aui-card-list-header aui-card-list-user aui-border-b">
            <div class="aui-card-list-user-avatar">
                <img src="{{asset( data_get( $val->user , 'avatar' , 'images/logo.png' )) }}" class="aui-img-round" />
            </div>
            <div class="aui-card-list-user-name">
                <div>{{$val->title}}</div>
                <small class="aui-text-danger aui-text-bold">{{ data_get( $val , 'click_price') }}</small>
            </div>
            <div class="aui-card-list-user-name">
                <div>{{data_get( $val->user , 'nickname' , '连运' )}}</div>
                <small>{{$val->created_at }}</small>
            </div>
            @if( isset( $val->tags ) && is_array( $val->tags ) )
            <div class="aui-card-list-user-info">
                @foreach( $val->tags as $v)
                <span >{{$v}}</span>
                @endforeach
            </div>
            @endif
        </div>
        <div class="aui-card-list-footer aui-border-t">
            <div><i class="aui-iconfont aui-icon-laud" ></i> {{$val->up_times}}</div>
            <div><i class="aui-iconfont aui-icon-laud trans-180" STYLE="display:inline-block;transform: rotate(180deg) ;" ></i> {{$val->down_times }}</div>
            <div><i class="aui-iconfont aui-icon-display"></i> {{$val->has_click_times }}/{{ $val->click_times  }}</div>
        </div>
    </div>
@empty
    <p class="no-data">
        没有更多数据
    </p>
@endforelse