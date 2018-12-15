@forelse( $list->items() as $val )
    <li class="aui-list-item aui-list-item-middle">
        <div class="aui-media-list-item-inner">
            <div class="aui-list-item-media
            {{ $val->status == 0 ? 'aui-text-danger' : '' }}
            {{ $val->status == 1 ? 'aui-text-success' : '' }}
            {{ $val->status == 2 ? 'aui-text-info' : '' }}" style="width: 4rem;">
                {{ data_get( config('global.withdraw_status') , data_get( $val , 'status' )) }}
            </div>
            <div class="aui-list-item-inner aui-list-item-arrow">
                <div class="aui-list-item-text">
                    <div class="aui-list-item-title aui-font-size-14">{{ data_get( $val , 'cash' ) }}</div>
                    <div class="aui-list-item-right">{{ data_get( $val , 'created_at' ) }}</div>
                </div>
                <div class="aui-list-item-text">
                    {{ data_get( $val , 'target_desc' ) }}
                </div>
            </div>
        </div>
    </li>
@empty
    <p class="no-data">
        没有更多数据
    </p>
@endforelse