<table class="table">
	<tbody>
		<tr>
			<th width="100">制法总剂数</th>
			<td>
				{{$dosage or 0 }}
			</td>
		</tr>
		<tr>
			<th width="100">药物总种类</th>
			<td>
				{{$kind or 0 }}
			</td>
		</tr>
		<tr>
			<th width="100">药物总重</th>
			<td>
				{{$weight or 0 }}
			</td>
		</tr>
		<tr>
			<th width="100">不可计重药物</th>
			<td>
				@if( is_array( $extra ) ) 
					@foreach( $extra as $val )
					{{$val->goods_sn}}&nbsp;---&nbsp;{{$val->goods_name}}&nbsp;---&nbsp;{{$val->num}}&nbsp;---&nbsp;{{$val->goods_unit}}<br/>
					@endforeach
				@endif
			</td>
		</tr>
	</tbody>
</table>
