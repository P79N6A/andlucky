<?php 


namespace App\Admin\Controllers;

use App\Http\Controllers\Controller;
use App\User;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Grid;
use Encore\Admin\Grid\Displayers\Actions ;
use Encore\Admin\Form ;
use Encore\Admin\Layout\Content;
use App\Admin\Extensions\Tools\Trashed ;

use Encore\Admin\Controllers\ModelForm ;
use App\Models\DoctorGrade ;
use Illuminate\Http\Request;
use App\Models\Withdraw;
use App\Admin\Extensions\Tools\WithdrawTool;
use App\Models\ScoreLog;
use EasyWeChat ;

class WithdrawController extends Controller {
	
	use ModelForm ;

	public function index() {
		return Admin::content(function (Content $content) {
		
			$content->header( '提现管理' );
			$content->description( trans('admin::lang.list') );
		
			$content->body( $this->grid()->render() );
		
		});
	}
	
	protected function grid() {
		return Admin::grid( Withdraw::class , function( Grid $grid ){
			$grid->model()->orderBy('id' , 'desc' );
			if (request('status' , -1 ) >=  0 ) {
				$grid->model()->where('status' , request('status' , -1 ) );
			}
			$grid->id('ID')->sortable();
			$grid->column('user.nickname' , '用户昵称') ;
			$grid->column('user.mobile' , '电话') ;
            $grid->column('user.alipay_account' , '支付宝') ;
			$grid->used( '提现积分' );
			$grid->cash( '现金（转换）');
			$grid->rate('积分汇率');
			$grid->status('状态')->display( function( $v ){
				return data_get( config('global.withdraw_status' ) , $v );
			} );
			$grid->created_at( '创建时间');
			$grid->updated_at( '更新时间' );
			
			//$grid->disableExport();
			//$grid->exporter(new \App\Admin\Extensions\Export\ExcelExporter());
			
			$grid->disableRowSelector();
			$grid->disableCreation();
			$grid->tools ( function ($tools) {
				$tools->append ( new WithdrawTool() );
			} );
			$grid->actions ( function ( Actions $action) {
				$action->disableDelete();
				if( $action->row->status > 0 ) {
					$action->disableEdit ();
					$link = route('admin.withdraw.show' , ['id' => $action->row->id ] );
					$button = "<a href='{$link}' class='btn btn-xs btn-default'>查看</a>";
					$action->append( $button );
				}
			} );
			
			$grid->filter( function( $filter ){
				$filter->disableIdFilter() ;
				$filter->where(function( $query ){
					$input = $this->input;
					$query->whereIn('user_id' , function( $query ) use( $input ) {
						return $query->from('users')->where('mobile' , 'like' , "%{$input}%")->select('uid');
					});
				} , '手机号码');
				$filter->useModal();
				$filter->between('created_at' , '创建日期')->dateTime(['format' => 'YYYY-MM-DD']);
			
			});
			
		});
	}

	
	public function edit( $id ) {
		return Admin::content(function (Content $content) use( $id ) {
		
			$content->header( '提现详情' );
			$content->description( '编辑' );
		
			$content->body( $this->form()->edit( $id ) );
		
		});
	}
	public function show( $id ) {
		return Admin::content(function (Content $content) use( $id ) {
	
			$content->header( '提现详情' );
			$content->description( '编辑' );
	
			$content->body( $this->showform()->view( $id ) );
	
		});
	}
	/**
	 * 提现保存
	 * @param unknown $id
	 * @param Request $request
	 */
	public function update( $id , Request $request ) {
		$form = $this->form();
		$form->saving( function( Form $form ){
			$status = $form->input('status' );
			if( $form->model()->status == 0 ) {
				$user = User::findOrFail( $form->model()->user_id );
				if( $status == 1 ) {
					//由0值改为审核通过
					$row = User::where('id' , $user->id )
					->where('cash_reward' , $user->cash_reward )
					->update([
							'cash_reward' => $user->cash_reward + $form->model()->score
					]);
					if( !$row ) {
						admin_toastr('用户积分更改失败' , 'error' );
						return back()->withInput();
					}
					/**
					$scoreLog = ScoreLog::create([
							'uid' => $user->id ,
							'score' => - $form->model()->score ,
							'event' => 'withdraw_success' ,
							'obj_id' => $form->model()->id ,
					]);
					if( !$scoreLog ) {
						admin_toastr('用户积分记录写入' , 'error' );
						return back()->withInput();
					}
					//由微信企业付款 向当前用户支付铜板
                    **/
					//TODO 发送通知
				} elseif( $status == 2 ) {
					//审核不通过
					$row = User::where('id' , $user->id )
					->where('cash_reward' , $user->cash_reward )
					->update([
							'cash_reward' => $user->cash_reward + $form->model()->cash ,
					]);
					if( !$row ) {
						admin_toastr('用户积分更改失败' , 'error' );
						return back()->withInput();
					}
				}
				
			}
		});
		return $form->update( $id );
	}
	
	protected function form() {
		return Admin::form( Withdraw::class , function( Form $form){
			$form->display('user.nickname' , '昵称');
			$form->display('user.mobile' , '联系手机');
			$form->display('used' , '提现积分');
			$form->display('cash' , '提现铜板');
			$form->display('rate' , '提现汇率');
			//$form->display('bank_name' , '姓名');
			//$form->display('bank_name' , '账号类型');
			$form->display('bank_account' , '支付宝账号');
			$form->radio('status' , '处理状态' )->options( config('global.withdraw_status') );
			$form->textarea('remark' , '备注说明')->help("对于已经付款的，和审核不通过的可以备注理由和信息");
		});
	}
	
	protected function showform() {
		return Admin::form( Withdraw::class , function( Form $form){
			$form->display('user.nickname' , '昵称');
			$form->display('user.mobile' , '联系手机');
			$form->display('user.alipay_account' , '支付宝账号');
			$form->display('used' , '提现积分');
			$form->display('cash' , '提现铜板');
			$form->display('rate' , '提现汇率');
			$form->display('status' , '处理状态' )->with( function( $v ){
				return data_get( config('global.withdraw_status') , $v );
			});
			$form->display('remark' , '备注说明');
		});
	}
	
	public function destroy( $id ) {
		$doctorGrade = DoctorGrade::withCount('doctor')->findOrFail( $id );
		if( $doctorGrade->doctor_count > 0 ) {
			return response()->json([
					'status'  => false,
					'message' => '该等级下还有医生，请先删除医生',
			]);
		}
		if ($this->form()->destroy($id)) {
			return response()->json([
					'status'  => true,
					'message' => trans('admin::lang.delete_succeeded'),
			]);
		} else {
			return response()->json([
					'status'  => false,
					'message' => trans('admin::lang.delete_failed'),
			]);
		}
	}
	
	/**
	 * 从回收站中移出
	 * @param unknown $id
	 */
	public function restore( $id ) {
		$restore = DoctorGrade::onlyTrashed()->find( $id );
		if( $restore->restore() ) {
			return response()->json([
					'status'  => true ,
					'message' => '恢复完成',
			]);
		} else {
			return response()->json([
					'status'  => false,
					'message' => '恢复失败',
			]);
		}
	}
}
