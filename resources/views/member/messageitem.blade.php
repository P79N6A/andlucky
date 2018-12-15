@foreach( $list->items() as $val )
    <div class="aui-card-list">
        <a href="{{route('wap.message.show' , ['id' => $val->id ])}}">
            <div class="aui-card-list-header aui-card-list-user aui-text-warning">
                通知
            </div>
            <div class="aui-card-list-content-padded">
                {!! str_limit( data_get( $val , 'message' ) , 200 ) !!}
            </div>
        </a>
        <div class="aui-card-list-footer aui-border-t">
            <div><i class="aui-iconfont aui-icon-date"></i> {{$val->created_at}}</div>
            <div class="aui-label {{$val->read_at ? '' : 'aui-label-warning' }}">
                {{$val->read_at ? '已读' : '未读' }}
            </div>
        </div>
    </div>
@endforeach