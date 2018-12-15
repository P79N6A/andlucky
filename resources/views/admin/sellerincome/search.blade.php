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
	<div class="box-header">

		<div class="form-inline pull-left">
	    <form action="{{route('admin.sellerspread.index')}}" method="get" pjax-container="">
		        <fieldset>
					<div class="input-group input-group-sm">
					    <span class="input-group-addon"><strong>业务员名称</strong></span>
					    <select class="form-control seller_id" name="seller_id">
					    	@foreach( $seller as $k => $val )
					    		<option value="{{$k}}" @if( request()->input('seller_id') == $k ) selected @endif >{{ $val }}</option>
					    	@endforeach
					    </select>
					</div>
					<div class="input-group input-group-sm">
					    <span class="input-group-addon"><strong>月份</strong></span>
					    <input type="text" class="form-control month" placeholder="请选择月份" name="month" value="{{request()->input('month')}}">
					</div>
					<div class="input-group input-group-sm">
						<span class="input-group-addon"><strong>只看业务员推广的</strong></span>
						<div class="checkbox form-control">
					    	<label>
					      		<input type="checkbox" name="only_my" value="1" {{request()->input('only_my') ? 'checked': '' }} />
					    	</label>
					  	</div>
				  	</div>
		            <div class="btn-group btn-group-sm">
		                <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i></button>
		                <a href="{{route('admin.sellerspread.index')}}" class="btn btn-warning"><i class="fa fa-undo"></i></a>
		            </div>

		        </fieldset>
		</form>
		</div>
	</div>
</div>