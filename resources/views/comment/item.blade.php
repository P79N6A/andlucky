@foreach( $list->items() as $val )
<li class="aui-list-item comment-show" data-href="{{route('comment.show' , ['id' => $val->id , 'parent_id' => $parent_id ])}}">
    <div class="aui-media-list-item-inner">
        <div class="aui-list-item-media">
            <img class="aui-img-round" src="{{asset( data_get( $val->user , 'avatar' , 'images/logo.png'))}}">
        </div>
        <div class="aui-list-item-inner">
            <div class="aui-list-item-text">
                <div class="aui-list-item-title">{{ data_get( $val->user , 'nickname' , config('global.nickname') ) }}</div>
                @if( data_get( auth()->guard('wap')->user() , 'id' ) == $val->user_id || $canDrop )
                <div class="aui-list-item-right">
                    <a class="pull-right comment-drop" data-href="{{route('comment.drop' , ['id' => $val->id , 'parent_id' => $parent_id ])}}" >
                        <i class="aui-iconfont aui-icon-trash" ></i>
                    </a>
                </div>
                @endif
            </div>
            <div class="aui-list-item-text aui-ellipsis-2">
                {{ data_get( $val , 'content' ) }}
            </div>
            <div class="aui-list-item-text">
                {{ data_get( $val , 'created_at' ) }}&nbsp;<span class="com-reply-nums">{{data_get( $val , 'comment_count') }}</span>
            </div>
        </div>
    </div>
</li>
@endforeach