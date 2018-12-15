<?php
namespace App\Admin\Controllers;

use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;
use App\User;
use Encore\Admin\Grid\Filter;
use Encore\Admin\Grid\Displayers\Actions;
use Encore\Admin\Grid\Tools;
use App\Admin\Extensions\Tools\Trashed;

class MemberController extends Controller
{
    use ModelForm;

    /**
     * Index interface.
     *
     * @return Content
     */
    public function index()
    {
        return Admin::content(function (Content $content) {
            
            $content->header('会员管理');
            $content->description('列表');
            
            $content->body($this->grid());
        });
    }

    /**
     * Edit interface.
     *
     * @param
     *            $id
     * @return Content
     */
    public function edit($id)
    {
        return Admin::content(function (Content $content) use ($id) {
            
            $content->header('会员管理');
            $content->description('编辑');
            
            $content->body($this->form()
                ->edit($id));
        });
    }

    /**
     * Create interface.
     *
     * @return Content
     */
    public function create()
    {
        return Admin::content(function (Content $content) {
            
            $content->header('header');
            $content->description('description');
            
            $content->body($this->form());
        });
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        return Admin::grid( User::class, function (Grid $grid) {
            $grid->model()->orderBy('id' , 'desc' );
            if( request('trash') == 1 ) {
            	$grid->model()->onlyTrashed();
            }
            $grid->id('ID')->sortable();
            $grid->name('账号');
            $grid->mobile('手机号');
            $grid->nickname('昵称');
            $grid->avatar('头像')->image();
            $grid->total_big_small('总次数');
            $grid->lose_big_small('失败次数');
            $grid->not_pay_big_small('未支付次数');
            $grid->invite_code('邀请码');
            $grid->created_at('创建时间');
            
            $grid->disableCreation();
            $grid->disableRowSelector();
            $grid->tools( function( Tools $tools ){
            	$tools->append( new Trashed() ) ;
            });
            $grid->filter( function( Filter $filter ){
            	$filter->disableIdFilter();
            	$filter->like('mobile' , '手机号码');
            	
            });
            
            $grid->actions( function( Actions $action ){
            	//
            	if( request('trash') == 1 ) {
            		$action->disableEdit();
            		$action->disableDelete();
            		$url = route('admin.member.restore' , ['id' => $this->row->id ] ) ;
            		$action->append('&nbsp;<a href="'. $url .'" ><i class="fa fa-refresh"></i></a>');
            	}
            });
        });
    }
	
	/**
	 * Make a form builder.
	 *
	 * @return Form
	 */
	protected function form() {
		return Admin::form ( User::class, function (Form $form) {
			
			$form->display ( 'id', 'ID' );
			$form->display('nickname' , '昵称');
			$form->display('name' , '电话');
			$form->display('alipay_account' , '支付宝');
			$form->display('cash_reward' , '余额');
			//$form->display('cash_reward' , '余额');
			//$form->display('cash_reward' , '余额');
			//$form->display('cash_reward' , '余额');
			$form->display('invite_code' , '邀请码');
		} );
	}
	
	/**
	 * 还原
	 */
	public function restore($id) {
		$member = User::onlyTrashed ()->find ( $id );
		if ( $member->restore ()) {
			admin_toastr("恢复完成") ;
			return back();
		} else {
			admin_toastr("恢复失败" , 'error') ;
			return back();
		}
    }
    
}
