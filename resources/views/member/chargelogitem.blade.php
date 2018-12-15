@forelse( $list->items() as $val )
    <li class="aui-list-item aui-list-item-middle">
        <div class="aui-media-list-item-inner">
            <div class="aui-list-item-inner aui-list-item-arrow">
                <div class="aui-list-item-text">
                    <div class="aui-list-item-title aui-font-size-14">单号:{{ data_get( $val , 'trade_no') }}</div>
                    <div class="aui-list-item-right">{{ data_get( $val , 'charge') }}</div>
                </div>
                <div class="aui-list-item-text">
                    {{ data_get( $val , 'created_at' ) }}
                </div>
            </div>
        </div>
    </li>
@empty
    <p class="no-data">
        没有更多数据
    </p>
@endforelse