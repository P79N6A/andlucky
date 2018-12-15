@forelse( $list->items() as $val )
<div class="aui-card-list" data-href="{{route('wap.guess.show' , ['id' => $val->id ])}}">
    <div class="aui-card-list-header aui-font-size-14">
        <div>编号:{{$val->id}}</div>
        <span class="aui-pull-right">
            {{ $val->created_at }}
        </span>
    </div>
    <div class="aui-card-list-header aui-card-list-user">
        <div class="aui-card-list-user-avatar">
            <img src="{{ asset( data_get( $val->user , 'avatar' , 'images/logo.png' ) ) }}" class="aui-img-round">
        </div>
        <div class="aui-card-list-user-name">
            <div>{{ data_get( $val->user ,'nickname' , config('global.no_nickname')) }}</div>
            <small class="aui-label">
                {{ data_get( config('global.guess_status') , $val->status ) }}

            </small>
        </div>
        <div class="aui-card-list-user-info">
                奖池：{{$val->cash}}
                <div class="aui-pull-right">
                    赔率：{{ $val->rate }}
                </div>
        </div>
    </div>
</div>
    @empty
<p class="no-data">
    暂时没有押大小活动!
</p>
@endforelse