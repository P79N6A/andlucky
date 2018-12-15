@forelse( $list->items() as $val )
<div class="aui-card-list">
    <div class="aui-card-list-header">
        <div>编号:{{$val->id}}</div>
        <span class="aui-label">
            {{ data_get( config('global.guess_status') , $val->status ) }}
        </span>
    </div>
    <div class="aui-card-list-header aui-card-list-user aui-border-b">
        <div class="aui-card-list-user-avatar">
            <img src="{{ asset( data_get( $val->user , 'avatar' , 'images/logo.png' ) ) }}" class="aui-img-round">
        </div>
        <div class="aui-card-list-user-name">
            <div>{{ data_get( $val->user ,'nickname' , config('global.no_nickname')) }}</div>
            <small class="aui-label aui-label-danger" style="color:#fff;">
                赔率：{{ $val->rate }}
            </small>
        </div>
        <div class="aui-card-list-user-info">

            信用:{{ data_get( $val->user , 'credit') }}&nbsp;,&nbsp;
            {{ data_get( $val->user , 'not_pay_big_small')}}/{{ data_get( $val->user , 'lose_big_small')}}&nbsp;,&nbsp;
            {{ data_get( $val->user , 'not_pay_big_small_cash')}}/{{ data_get( $val->user , 'lose_big_small_cash') }}
        </div>
    </div>
    <div class="aui-card-list-content aui-padded-t-10 aui-padded-b-10 aui-border-b">
        <div class="aui-row">
            @foreach( $val->join as $v )
            <div class="aui-col-xs-4 join-block">
                <div class="join-user-avatar img-round join-grid">
                    <img class="aui-img-round" src="{{asset( data_get( $v->user , 'avatar' , 'images/logo.png' ))}}">
                </div>
                <div>{{ data_get( $v->user , 'credit') }}</div>
                <div>{{ data_get( $v->user , 'nickname' , config('global.no_nickname') ) }}</div>
            </div>
            @endforeach
            <?php for( $i = 0 ; $i < ( 3 - count( $val->join )) ; $i++ ) : ?>
            <div class="aui-col-xs-4 join-block btn-join"
                 data-id="{{ $val->id }}"
                 data-rate="{{ $val->rate }}"
                 data-user_id="{{$val->user_id}}"
                 data-cash="{{$val->cash}}"
                 data-occupy_cash="{{$val->occupy_cash}}"
            >
                <div class="join-user-avatar img-round join-grid">
                    <img class="aui-img-round" src="{{asset('images/jia.png')}}">
                </div>
                <div>0.0</div>
                <div>等待加入</div>

            </div>
            <?php endfor; ?>
        </div>
    </div>
</div>
    @empty
<p class="no-data">
    暂时没有押大小活动!
</p>
@endforelse