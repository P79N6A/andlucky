@foreach( $list->items() as $val )
    <div class="aui-card-list">

        <div class="aui-card-list-header aui-card-list-user aui-text-warning">
            {{ data_get( $val , 'title' ) }}
        </div>
        <div class="aui-card-list-content-padded">
            {!! nl2br( data_get( $val , 'content' ) ) !!}
        </div>
        <div class="aui-card-list-footer aui-border-t">
            <div><i class="aui-iconfont aui-icon-date"></i> {{$val->created_at}}</div>
        </div>
    </div>
@endforeach