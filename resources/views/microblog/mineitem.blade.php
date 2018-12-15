@foreach( $list->items() as $val )
    <div class="aui-card-list" data-href="{{route('microblog.show' , ['id' => $val->id ])}}">
        <a href="{{route('microblog.show' , ['id' => $val->id ])}}">
        <div class="aui-card-list-header aui-card-list-user">
            {{$val->title}}
        </div>
        <div class="aui-card-list-header aui-card-list-user aui-border-b">
            <div class="aui-card-list-user-avatar">
                <img src="{{asset( data_get( $val->user , 'avatar' , 'images/logo.png' )) }}" class="aui-img-round" />
            </div>
            <div class="aui-card-list-user-name">
                <div>{{data_get( $val->user , 'nickname' , '连运' )}}</div>
                <!--<small>1天前</small>-->
            </div>
            <div class="aui-card-list-user-info">{{$val->created_at }}</div>
        </div>
        <div class="aui-card-list-content-padded">
            {!! str_limit( data_get( $val , 'content' ) , 200 ) !!}
        </div>
        @if( data_get( $val->extra , 'type') == 'image' )
            <div class="aui-row aui-row-padded">
                @if( is_array( data_get( $val->extra , 'data' , [] ) ) )

                @foreach( data_get( $val->extra , 'data' , [] ) as $img )
                    <div class="aui-col-xs-4">
                        <img src="{{asset( $img ) }}" />
                    </div>
                @endforeach

                @endif
            </div>
        @endif
        </a>
        <div class="aui-card-list-footer aui-border-t">
            <div><i class="aui-iconfont aui-icon-note"></i> {{$val->comment_count}}</div>
            <div><i class="aui-iconfont aui-icon-laud" style="display:inline-block;transform: rotate(180deg) ;"></i> {{$val->prase }}</div>
            <div><i class="aui-iconfont aui-icon-display"></i> {{$val->views }}</div>
            <a href="{{route('wap.microblog.edit' , ['id' => $val->id ])}}"><i class="aui-iconfont aui-icon-pencil"></i>编辑</a>
            <div class="btn-drop" data-href="{{route('wap.microblog.drop' , ['id' => $val->id ])}}"><i class="aui-iconfont aui-icon-trash"></i> 删除</div>
        </div>
    </div>
@endforeach