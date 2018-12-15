<?php
namespace App\Admin\Controllers;

use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;
use App\Models\ChargeLog;

class FinanceController extends Controller
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
            
            $content->header('充值记录');
            $content->description('充值日志');
            
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
            
            $content->header('header');
            $content->description('description');
            
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
    protected function grid() {
		return Admin::grid ( ChargeLog::class, function (Grid $grid) {
			
			$grid->id ( 'ID' )->sortable ();
            $grid->column('user.nickname' , '用户昵称') ;
            $grid->column('user.mobile' , '电话') ;
			$grid->out_trade_no ( '订单编号' );
			$grid->trade_no ( '支付宝单号' )->display ( function ($v) {
				return $v ? $v : '';
			} );
			$grid->charge ( '充值铜板' );
			$grid->status ( '充值状态' )->display( function( $v ){
			    return data_get( config('global.charge_status' ) , $v , '' ) ;
            } );
			$grid->notice_time ( '完成时间' )->display( function( $v ){
            	return $v ? date('Y-m-d H:i:s' , $v ) : '' ;
            } );
            $grid->created_at( '创建时间');
            $grid->updated_at( '最后一次更新时间');
            $grid->disableCreation();
            $grid->disableRowSelector();
        });
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        return Admin::form(YourModel::class, function (Form $form) {
            
            $form->display('id', 'ID');
            $form->display('created_at', '创建时间');
            $form->display('updated_at', '最后一次更新时间');
        });
    }
}
