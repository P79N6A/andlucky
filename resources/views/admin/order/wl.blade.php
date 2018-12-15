<table class="table">
	<tbody>
		<tr>
			<th width="120">物流状态</th>
			<td>
				{{ data_get( config( 'global.wl_status' ) , data_get( data_get( $order->ship_detail , 'showapi_res_body' ) , 'status'  , '' )  , '' ) }}
			</td>
		</tr>
		<tr>
			<th width="120">承运来源</th>
			<td>
				{{ data_get( $order , 'ship_name' ) }}
			</td>
		</tr>
		<tr>
			<th width="120">运单编号</th>
			<td>
				{{ data_get( $order , 'post_script' ) }}
			</td>
		</tr>
		<tr>
			<th width="120">官方电话</th>
			<td>{{ data_get( data_get( $order->ship_detail , 'showapi_res_body' ) , 'tel' ) }}</td>
		</tr>
		
@foreach( data_get( data_get( $order->ship_detail , 'showapi_res_body' ) , 'data' , [] ) as $k => $val )
		<tr>
			<th width="120">
				{{ data_get( $val , 'time') }}
			</th>
			<td>
            	{{ data_get( $val , 'context') }}
			</td>
		</tr>
	@endforeach
		
	</tbody>
</table>
