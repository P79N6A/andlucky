@extends('layout')

@section('style')
<style>
    .aui-list .aui-list-item {
        min-height: 1.7rem;
    }
    .aui-list .aui-list-item-inner {
        min-height:1.25rem;
        line-height: .75rem;
    }
</style>
@endsection

@section('content')
    <header class="aui-bar aui-bar-nav">
        @if( request('from') == 'form')
        <a class="aui-pull-left aui-btn" href="{{route('wap.guess.index')}}">
            <span class="aui-iconfont aui-icon-left"></span>
        </a>
            @else
        <a class="aui-pull-left aui-btn" onclick="history.back()">
            <span class="aui-iconfont aui-icon-left"></span>
        </a>
        @endif
        <div class="aui-title">猜大小</div>

    </header>
    <section class="aui-content aui-margin-b-10">
        <ul class="aui-list aui-media-list">
            <li class="aui-list-item aui-list-item-middle">
                <div class="aui-media-list-item-inner">
                    <div class="aui-list-item-media" style="width: 3rem;">
                        <img src="{{ asset( data_get( $guess->user , 'avatar' , 'images/logo.png')) }}" class="aui-img-round aui-list-img-sm">
                    </div>
                    <div class="aui-list-item-inner">
                        <div class="aui-list-item-text">
                            <div class="aui-list-item-title aui-font-size-14">{{ data_get( $guess->user , 'nickname' , config('global.no_nickname' )) }}</div>
                            <div class="aui-list-item-right">
                                <div class="aui-label">
                                {{ data_get( config('global.guess_status') , $guess->status ) }}
                                </div>
                            </div>
                        </div>
                        <div class="aui-list-item-text">
                            {{ data_get( $guess->user , 'mobile') }}
                        </div>
                        <div class="aui-list-item-text">
                            信用:{{ data_get( $guess->user , 'credit') }}&nbsp;,&nbsp;
                            {{ data_get( $guess->user , 'not_pay_big_small')}}/{{ data_get( $guess->user , 'lose_big_small')}}&nbsp;,&nbsp;
                            {{ data_get( $guess->user , 'not_pay_big_small_cash')}}/{{ data_get( $guess->user , 'lose_big_small_cash') }}
                        </div>
                    </div>
                </div>
            </li>
        </ul>
        <ul class="aui-list">
            @if( $guess->user_id != $user->id && !$win )
            <li class="aui-list-item">
                <div class="aui-list-item-inner">
                    <div class="aui-list-item-title">支付宝</div>
                    <div class="aui-list-item-right">
                        {{ data_get( $guess->user , 'alipay_account') }}
                    </div>
                </div>
            </li>
            @endif
            <li class="aui-list-item">
                <div class="aui-list-item-inner">
                    <div class="aui-list-item-title">奖池</div>
                    <div class="aui-list-item-right">
                        {{ data_get( $guess , 'cash') }}
                    </div>
                </div>
            </li>
            @if( $guess->user_id == $user->id )
            <li class="aui-list-item">
                <div class="aui-list-item-inner">
                    <div class="aui-list-item-title">赢得</div>
                    <div class="aui-list-item-right">
                        {{ data_get( $guess , 'win_cash') }}
                    </div>
                </div>
            </li>
            <li class="aui-list-item">
                <div class="aui-list-item-inner">
                    <div class="aui-list-item-title">输掉</div>
                    <div class="aui-list-item-right">
                        {{ data_get( $guess , 'lose_cash') }}
                    </div>
                </div>
            </li>
            @endif

            @if( $guess->user_id == $user->id || $guess->status == 1 )
            <li class="aui-list-item">
                <div class="aui-list-item-inner">
                    <div class="aui-list-item-title">赔率</div>
                    <div class="aui-list-item-right">
                        {{ data_get( $guess , 'rate') }}
                    </div>
                </div>
            </li>

            <li class="aui-list-item">
                <div class="aui-list-item-inner">
                    <div class="aui-list-item-title">种子</div>
                    <div class="aui-list-item-right">
                        {{ data_get( config('global.guess_seed' ) ,  $guess->seed ) }}
                    </div>
                </div>
            </li>
            @endif
        </ul>
    </section>
    @if( $guess->user_id == $user->id )
        @forelse( $guess->join as $val )
    <section class="aui-content aui-margin-b-10">
        <ul class="aui-list aui-media-list">
            <li class="aui-list-item aui-list-item-middle">
                <div class="aui-media-list-item-inner">
                    <div class="aui-list-item-media" style="width: 3rem;">
                        <img src="{{ asset( data_get( data_get( $val , 'user' ) , 'avatar' , 'images/logo.png')) }}" class="aui-img-round aui-list-img-sm">
                    </div>
                    <div class="aui-list-item-inner">
                        <div class="aui-list-item-text">
                            <div class="aui-list-item-title aui-font-size-14">{{ data_get( data_get( $val , 'user' ) , 'nickname' , config('global.no_nickname' )) }}</div>
                        </div>
                        <div class="aui-list-item-text">
                            信用:{{ data_get( data_get( $val , 'user' ) , 'credit') }}&nbsp;,&nbsp;
                            {{ data_get( data_get( $val , 'user' ) , 'not_pay_big_small')}}/{{ data_get( data_get( $val , 'user' ) , 'lose_big_small')}}&nbsp;,&nbsp;
                            {{ data_get( data_get( $val , 'user' ) , 'not_pay_big_small_cash')}}/{{ data_get( data_get( $val , 'user' ) , 'lose_big_small_cash') }}
                        </div>
                    </div>
                </div>
            </li>
        </ul>
        <ul class="aui-list">
            @if( $val->is_win ==1 )
                <li class="aui-list-item">
                    <div class="aui-list-item-inner">
                        <div class="aui-list-item-title">支付宝</div>
                        <div class="aui-list-item-right">
                            {{ data_get( $val->user , 'alipay_account') }}
                        </div>
                    </div>
                </li>
            @endif
            @if( $val->is_win == 2 )
                <li class="aui-list-item">
                    <div class="aui-list-item-inner">
                        <div class="aui-list-item-title">手机号码</div>
                        <div class="aui-list-item-right">
                            {{ data_get( $val->user , 'mobile') }}
                        </div>
                    </div>
                </li>
            @endif
            <li class="aui-list-item">
                <div class="aui-list-item-inner">
                    <div class="aui-list-item-title">投注</div>
                    <div class="aui-list-item-right">
                        {{ data_get( $val , 'cash') }}
                    </div>
                </div>
            </li>
            <li class="aui-list-item">
                <div class="aui-list-item-inner">
                    <div class="aui-list-item-title">押注</div>
                    <div class="aui-list-item-right">
                        {{ data_get( config('global.guess_seed' ) ,  $val->seed ) }}
                    </div>
                </div>
            </li>
            @if( $val->is_win > 0 )
            <li class="aui-list-item">
                <div class="aui-list-item-inner">
                    <div class="aui-list-item-title">结果</div>
                    <div class="aui-list-item-right">
                        {{ $val->is_win == 1 ? '玩家胜' : '庄家胜'}}
                    </div>
                </div>
            </li>
            @endif

            @if( $val->status > 0 )
            <li class="aui-list-item">
                <div class="aui-list-item-inner">
                    <div class="aui-list-item-title">状态</div>
                    <div class="aui-list-item-right">
                        @if( $val->status == 1)
                            <span class="aui-label aui-label-danger">未退款</span>
                        @endif
                        @if( $val->status == 2)
                            <span class="aui-label aui-label-success">赢家已退铜板</span>
                        @endif
                        @if( $val->status == 3)
                            <span class="aui-label aui-label-warning">系统已退铜板</span>
                        @endif
                    </div>
                </div>
            </li>
            @endif
        </ul>
        <ul class="aui-list">
            <li class="aui-list-item">
                <div class="aui-list-item-inner">
                    <div class="aui-list-item-title">{{$val->created_at}}</div>
                    <div class="aui-list-item-right">
                        @if( $val->is_win == 2 && $val->status == 1)
                            <span class="aui-btn btn-reback" data-id="{{$val->id}}">退款<em class="aui-text-warning">{{ $val->cash * $guess->rate }}</em></span>
                        @endif
                    </div>
                </div>
            </li>
        </ul>
    </section>
        @empty
            <p class="no-data">暂无玩家参与</p>
        @endforelse
    @else
        @foreach( $guess->join as $val )
            @if( $val->user_id == $user->id )
                <section class="aui-content aui-margin-b-10">
                    <ul class="aui-list aui-media-list">
                        <li class="aui-list-item aui-list-item-middle">
                            <div class="aui-media-list-item-inner">
                                <div class="aui-list-item-media" style="width: 3rem;">
                                    <img src="{{ asset( data_get( data_get( $val , 'user' ) , 'avatar' , 'images/logo.png')) }}" class="aui-img-round aui-list-img-sm">
                                </div>
                                <div class="aui-list-item-inner">
                                    <div class="aui-list-item-text">
                                        <div class="aui-list-item-title aui-font-size-14">{{ data_get( data_get( $val , 'user' ) , 'nickname' , config('global.no_nickname' )) }}</div>
                                    </div>
                                    <div class="aui-list-item-text">
                                        信用:{{ data_get( data_get( $val , 'user' ) , 'credit') }}&nbsp;,&nbsp;
                                        {{ data_get( data_get( $val , 'user' ) , 'not_pay_big_small')}}/{{ data_get( data_get( $val , 'user' ) , 'lose_big_small')}}&nbsp;,&nbsp;
                                        {{ data_get( data_get( $val , 'user' ) , 'not_pay_big_small_cash')}}/{{ data_get( data_get( $val , 'user' ) , 'lose_big_small_cash') }}
                                    </div>
                                </div>
                            </div>
                        </li>
                    </ul>
                    <ul class="aui-list">

                        <li class="aui-list-item">
                            <div class="aui-list-item-inner">
                                <div class="aui-list-item-title">投注</div>
                                <div class="aui-list-item-right">
                                    {{ data_get( $val , 'cash') }}
                                </div>
                            </div>
                        </li>
                        <li class="aui-list-item">
                            <div class="aui-list-item-inner">
                                <div class="aui-list-item-title">押注</div>
                                <div class="aui-list-item-right">
                                    {{ data_get( config('global.guess_seed' ) ,  $val->seed ) }}
                                </div>
                            </div>
                        </li>
                        @if( $val->is_win > 0 )
                            <li class="aui-list-item">
                                <div class="aui-list-item-inner">
                                    <div class="aui-list-item-title">结果</div>
                                    <div class="aui-list-item-right">
                                        {{ $val->is_win == 1 ? '玩家胜' : '庄家胜'}}
                                    </div>
                                </div>
                            </li>
                        @endif

                        @if( $val->status > 0 )
                            <li class="aui-list-item">
                                <div class="aui-list-item-inner">
                                    <div class="aui-list-item-title">状态</div>
                                    <div class="aui-list-item-right">
                                        @if( $val->status == 1)
                                            <span class="aui-label aui-label-danger">未退款</span>
                                        @endif
                                        @if( $val->status == 2)
                                            <span class="aui-label aui-label-success">赢家已退铜板</span>
                                        @endif
                                        @if( $val->status == 3)
                                            <span class="aui-label aui-label-warning">系统已退铜板</span>
                                        @endif
                                    </div>
                                </div>
                            </li>
                        @endif
                    </ul>
                    <ul class="aui-list">
                        <li class="aui-list-item">
                            <div class="aui-list-item-inner">
                                <div class="aui-list-item-title">{{$val->created_at}}</div>
                                <div class="aui-list-item-right">
                                    @if( $val->is_win == 1 && $val->status == 1)
                                        <span class="aui-btn btn-reback" data-id="{{$val->id}}">退款<em class="aui-text-warning">{{ $val->cash * $guess->rate }}</em></span>
                                    @endif
                                </div>
                            </div>
                        </li>
                    </ul>
                </section>
            @endif
        @endforeach
    @endif

@endsection


@section('script')
    <script>
        $('.btn-reback').unbind('click' ).bind('click' , throttle( reback , 1500 )) ;
        function reback() {
            let that = this
            $.post("{{route('wap.guess.reback' , ['id' => $guess->id ])}}" , {
                '_method' : "PUT" ,
                '_token' : '{{ csrf_token() }}'
            }  , function( data ){
                if( data.errcode === 0 ) {
                    toast.success({
                        title : data.msg
                    });
                    setTimeout( function(){
                        location.reload()
                    } , 1500 );

                } else {
                    toast.fail({
                        title : data.msg
                    })
                }

            }) ;
        }
    </script>

@endsection