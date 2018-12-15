<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\UserFocus;
use App\User ;

class FocusController extends Controller {
	
	
	public function create( Request $request ) {
		$userId = $request->input('user_id') ;
		$loginUser = auth()->guard('wap')->user();
		$user = User::findOrFail( $userId );
		$focus = UserFocus::firstOrCreate([
				'user_id' => $loginUser->id ,
				'focus_user_id' => $userId 
		] , [] );
		if( $focus ) {
			return response()->json([
					'errcode' => 0 ,
					'msg' => '关注成功'
			]);
		} 
		return response()->json([
				'errcode' => 10002 ,
				'msg' => '关注失败'
		]);
	}
	
	
	/**
	 * 取消关注
	 * @param Request $request
	 */
	public function destroy(  Request $request   ) {
		$id = $request->input('id');
		$userId = $request->input('user_id') ;
		if( !$id && !$userId ) {
			return response()->json([
					'errcode' => 10001 ,
					'msg' => '参数错误'
			]);
		}
		$loginUser = auth()->guard('wap')->user();
		if( $id ) {
			$focus = UserFocus::where('id' ,  $id )->where('user_id' , $loginUser->id )->first();
		} else {
			$focus = UserFocus::where('user_id' , $loginUser->id )->where('focus_user_id' , $userId )->first();
		}
		if( $focus->delete() ) {
			return response()->json([
					'errcode' => 0 ,
					'msg' => '取消关注成功'
			]);
		}
		return response()->json([
				'errcode' => 10002 ,
				'msg' => '取消关注失败'
		]);
	}
}