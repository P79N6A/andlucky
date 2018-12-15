@forelse( $list->items() as $val )
<div class="aui-card-list" data-href="{{route('wap.game.show' , ['id' => $val->id ])}}">
    <div class="aui-card-list-header">
        <div>
            <span class="aui-font-size-18">铜板 :</span> <span class="aui-text-warning">{{ $val->cash_deposit }}</span>
        </div>
        <div class="aui-pull-right">
            @if( $val->status == 0 )
                @if( $user->id == $val->user_id )
                <span class="aui-label haspay" >
                    等待对方应战
                </span>
                @else
                    <span class="aui-label haspay" >
                        等待应战
                    </span>
                @endif
            @endif
            @if( $val->status == 1 )
                    <span class="aui-label notpay" >
                    @if( $user->id == $val->user_id )
                        {{$val->user_num > $val->inviter_num ? '我方获胜' : '对手获胜'}}
                    @else
                        {{$val->user_num < $val->inviter_num ? '我方获胜' : '对手获胜'}}
                    @endif
                    </span>

            @endif

            @if( $val->status == 2 )
                <span class="aui-label haspay">
                    赢家退铜板
                </span>
            @endif

            @if( $val->status == 3 )
                <span class="aui-label haspay">
                    拒绝应战
                </span>
            @endif

            @if( $val->status == 4 )
                <span class="aui-label haspay">
                    用户撤销战斗
                </span>
            @endif

            @if( $val->status == 5 )
                <span class="aui-label haspay">
                    系统退铜板
                </span>
            @endif

            @if( $val->status == 6 )
                <span class="aui-label haspay">
                    系统撤销战斗
                </span>
            @endif

        </div>
    </div>
    <div class="aui-card-list-content-padded aui-border-b aui-border-t">
        <div class="aui-row aui-row-padded aui-text-center">
            <div class="aui-col-xs-4">
                <img class="avatar aui-img-round" src="{{asset( data_get( $val->user , 'avatar'  , 'images/logo.png') ) }}" />
                <div class="nickname">{{data_get( $val->user , 'nickname' , config('global.no_nickname' ) )}}</div>
                <div class="credit color-red">
                    {{ data_get( $val->user , 'credit') }}
                </div>
            </div>
            <div class="aui-col-xs-4">
                <div class="vs">VS</div>
                @if( in_array( $val->status , [1 , 2 , 5 ]))
                <div class="result">
                    {{$val->user_num}}&nbsp;:&nbsp;{{$val->inviter_num}}
                </div>
                @endif
            </div>
            <div class="aui-col-xs-4">
                <img class="avatar aui-img-round" src="{{asset( data_get( $val->inviter , 'avatar'  , 'images/logo.png') ) }}" />
                <div class="nickname">{{data_get( $val->inviter , 'nickname' , config('global.no_nickname' ) )}}</div>
                <div class="credit color-red">
                    {{ data_get( $val->inviter , 'credit') }}
                </div>
            </div>
        </div>
    </div>
    <div class="aui-card-list-footer">
        <div>{{ $val->created_at }}</div>
    </div>
</div>
@empty
<p class="no-data">
    暂无比大小信息
</p>
@endforelse