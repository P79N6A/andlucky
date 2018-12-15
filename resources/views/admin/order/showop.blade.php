<style>
.select2-dropdown {
	z-index:20000000;
}

</style>
@if( $order->order_status == 0 )
<div class="btn-group">
    <button class="btn btn-warning btn-check" data-href="{{route('admin.order.checkok' , ['id' => $order->id ] )}}">审核</button>
</div>

<div class="btn-group">
    <a class="btn btn-warning btn-edit" href="{{route('admin.order.edit' , ['id' => $order->id ] )}}">编辑</a>
</div>


@endif



@if( $order->order_status == 1 )
  @if( auth()->guard('admin')->user()->inRoles(['finance5' , 'administrator']) )
  <div class="btn-group">
      <button class="btn btn-warning btn-offpay" data-href="{{route('admin.order.payoffline' , ['id' => $order->id ] )}}">线下支付</button>
  </div>
  @endif

  @if( auth()->guard('admin')->user()->inRoles(['servicer1' , 'servicer2' , 'servicer3' , 'administrator']) )
  <div class="btn-group">
      <button class="btn btn-warning btn-recivepay" data-href="{{route('admin.order.payrecive' , ['id' => $order->id ] )}}">货到付款</button>
  </div>
  @endif
@endif

@if( $order->order_status == 2 )
  @if( auth()->guard('admin')->user()->inRoles(['servicer1' , 'servicer2' , 'servicer3' , 'administrator']) )
  <div class="btn-group">
      <button class="btn btn-warning btn-send" data-href="{{route('admin.order.send' , ['id' => $order->id ] )}}">发货</button>
  </div>
  @endif
@endif


<div class="btn-group">
    <button class="btn btn-warning btn-mark" data-href="{{route('admin.order.mark' , ['id' => $order->id ] )}}">备注</button>
</div>

<textarea id="_send_tpl" style="display: none;">
<div class="row">
<form class="form-horizontal">
    <fieldset>
    <div class="form-group">
          <label class="control-label col-sm-3">快递公司</label>
          <div class="col-sm-9">
            <select class="form-control ship_name">
      			<option value="0">请选择快递公司 </option>
@foreach( config('expresslist.express') as $val )
      			<option value="{{$val['simpleName']}}"> {{$val['expName']}} </option>
@endforeach
			</select>
          </div>
        </div>
    <div class="form-group">
          <label class="control-label col-sm-3" for="input01">快递单号</label>
          <div class="col-sm-9">
            <input type="text" placeholder="请输入快递单号" class="form-control ship_no">
          </div>
        </div>
    </fieldset>
</form>
</div>
</textarea>