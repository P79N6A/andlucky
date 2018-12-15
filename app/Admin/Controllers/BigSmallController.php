<?php
namespace App\Admin\Controllers;

use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;
use App\Models\SmallBig;
use Encore\Admin\Grid\Filter;
use Encore\Admin\Grid\Displayers\Actions;

class BigSmallController extends Controller
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
            
            $content->header('比大小');
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
    protected function grid()
    {
        return Admin::grid( SmallBig::class, function (Grid $grid) {
            
            $grid->id('ID')->sortable();
            $grid->column('user.name' , '邀请者')->display( function( $v ){
            	$user = data_get( $this , 'user');
            	$u = $user ? data_get( $user , 'nickname' ) . '<br/>' . data_get( $user , 'name') :'' ;
            	$num = data_get( $this , 'user_num' , '');
            	$num = sprintf("<br/><span class='label label-success'>点数:%s</span>" , $num );
            	return $u . $num ;
            } );
            $grid->column('inviter.name' , '被邀请者')->display( function( $v ){
            	$user = data_get( $this , 'inviter');
            	$u = $user ? data_get( $user , 'nickname' ) . '<br/>' . data_get( $user , 'name') :'' ;
            	$num = data_get( $this , 'inviter_num' , '');
            	$num = sprintf("<br/><span class='label label-success'>点数:%s</span>" , $num );
            	return $u . $num ;
            } );
            $grid->cash_deposit('铜板')->sortable();
            //$grid->deposit_status('支付状态');
            $grid->status('战斗状态')->display( function( $v ){
            	return data_get( config('global.big_small_status') , $v , '' );
			} )->label ();
			$grid->created_at ( '创建时间' );
			$grid->updated_at ( '最后更新' );
			$grid->disableCreation ();
			$grid->disableRowSelector ();
			$grid->actions( function( Actions $action ){
				$action->disableDelete();
				$action->disableEdit() ;
			});
			$grid->filter ( function ( Filter $filter) {
				$filter->where ( function ($query) {
					$input = $this->input;
					$query->whereIn ( 'user_id', function ($query) use ($input) {
						return $query->from ( 'users' )->where ( 'mobile', 'like', "%{$input}%" )->select ( 'id' );
					} );
				}, '发起方手机' );
				$filter->where ( function ($query) {
					$input = $this->input;
					$query->whereIn ( 'user_id', function ($query) use ($input) {
						return $query->from ( 'users' )->where ( 'mobile', 'like', "%{$input}%" )->select ( 'id' );
					} );
				}, '接受方手机');
            		 
            	$filter->equal('status' , '战斗')->select( config('global.big_small_status')  );
            	$filter->between('cash_deposit' , '铜板区间');
            });
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
            
            $form->display('created_at', 'Created At');
            $form->display('updated_at', 'Updated At');
        });
    }
    
}
