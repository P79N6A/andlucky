@foreach( $list as $val )
    <li class="aui-list-item aui-list-item-middle" data-id="{{data_get( $val , 'id' )}}">
        <div class="aui-media-list-item-inner">
            <div class="aui-list-item-media wechat-avatar" style="width: 3rem;">
                <!--<div class="aui-badge">9</div>-->
                <img class="aui-img-round" src="{{ asset( data_get( $val , 'avatar' , 'images/logo.png') ) }}">
            </div>
            <div class="aui-list-item-inner">
                <div class="aui-list-item-text">
                    <div class="aui-list-item-title">{{ data_get( $val , 'nickname' , config('global.no_nickname')) }}</div>
                    <div class="aui-list-item-right">
                        @if( data_get( $val , 'online'))
                            <div class="aui-badge" style="top: 0rem;left: auto;right: .2rem;min-width: 2rem;background:#009635;">在线</div>
                        @else
                            <div class="aui-badge" style="top: 0rem;left: auto;right: .2rem;min-width: 2rem;background:#9e9e9e;">离线</div>
                        @endif
                    </div>

                </div>
                <div class="aui-list-item-text aui-font-size-12">
                    信用：
                    {{data_get( $val , 'credit')}}
                    &nbsp;,&nbsp;
                    {{data_get( $val , 'not_pay_big_small' ) }}/{{data_get( $val , 'lose_big_small' ) }}
                    &nbsp; , &nbsp;{{data_get( $val , 'not_pay_big_small_cash' ) }}/{{data_get( $val , 'lose_big_small_cash' ) }}
                </div>

                <div class="aui-list-item-text aui-font-size-12">
                    {{ data_get( $val , 'mobile' , '') }}
                </div>

            </div>
        </div>
    </li>
@endforeach