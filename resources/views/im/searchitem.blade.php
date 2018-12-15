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
                        <div class="aui-btn aui-btn-sm aui-btn-block aui-btn-danger btn-addfriend" data-id="{{ $val->id }}">加好友</div>
                    </div>
                </div>

                <div class="aui-list-item-text aui-font-size-12">
                    {{ data_get( $val , 'mobile' , '') }}
                </div>

            </div>
        </div>
    </li>
@endforeach