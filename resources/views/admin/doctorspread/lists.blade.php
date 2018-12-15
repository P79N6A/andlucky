<style>
	.thead thead tr th {
		text-align: center;
		vertical-align: middle;
	}
	.thead tbody tr td {
		text-align: center;
		vertical-align: middle;
	}
</style>
<div class="box">
	<!-- /.box-header -->
	<div class="box-body table-responsive no-padding">
	
		<div class="col-sm-12 h4" style="text-align: center;">
		@if( request('month') ) 
			{{request('month')}}月，共推广{{$list->total()}}位医生
		@else
			共推广{{$list->total()}}位医生
		@endif
		</div>
		
		<table class="table table-bordered thead">
			<thead>
				<tr>
					<th>医生名称</th>
					<th>医院</th>
					<th>科室</th>
					<th>职称</th>
					<th>级别</th>
					<th>审核状态</th>
					<th>申请时间</th>
				</tr>

			</thead>
			<tbody>
			@if( $list )
				@foreach( $list->items() as $val )
				<tr>
					<td>{{ $val->name }}</td>
					<td>{{$val->unit_name}}</td>
					<td>{{$val->depart_name}}</td>
					<td>{{$val->professional}}</td>
					<td>{{$val->gradeInfo->name or ''}}</td>
					<td>{{data_get( config('global.doctor_auth_status') , $val->is_auth ) }}</td>
					<td>{{$val->created_at}}</td>
				</tr>
				@endforeach
			@endif	
			</tbody>
		</table>
	</div>
	<div class="box-footer clearfix">
		@if( $list )
			{{$list->render()}}
		@endif
	</div>
	<!-- /.box-body -->
</div>

