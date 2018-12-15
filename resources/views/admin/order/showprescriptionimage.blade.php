@if( $order->prescription_image && !empty( $order->prescription_image ))
<table class="table">
	<tbody>
		<tr>
			<td colspan="2" style="text-align: center;">
				<img src="{{ asset( current( $order->prescription_image ) ) }}" style="width:100%" class="prescriptionImage" />
			</td>
		</tr>
		<tr>
			<td><a class="btn btn-primary routateLeft ">顺时针<a/></td>
			<td><a class="btn btn-primary routateRight ">逆时针<a/></td>
		</tr>
	</tbody>
</table>
@endif