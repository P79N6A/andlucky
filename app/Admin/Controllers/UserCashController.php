<?php
namespace App\Admin\Controllers;

use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;
use App\Models\UserCash;
use Encore\Admin\Grid\Filter;

class UserCashController extends Controller
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
            
            $content->header('铜板流水');
            $content->description('日志');
            
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
		return Admin::grid ( UserCash::class, function (Grid $grid) {
			
			$grid->id ( 'ID' )->sortable ();
			$grid->column('user.name' , '用户名') ;
			$grid->column('user.nickname' , '用户昵称');
			$grid->event('事务类型')->display( function( $v ){
				return data_get( config('global.cash_event') , $v , '' );
			} );
			$grid->act('收入/支出')->display(function( $v ){
				return 'in' == $v ? '收入' : '支出' ;
			});
			$grid->cash('铜板');
			$grid->target_desc('说明');
            $grid->created_at( '创建时间');
            $grid->updated_at( '最后一次更新时间');
            $grid->disableCreation();
            $grid->disableRowSelector();
            $grid->filter( function( Filter $filter ) {
            	$filter->where(function( $query ){
            		$input = $this->input ;
            		$query->whereIn('user_id' , function( $query ) use( $input ) {
            			return $query->from('users')->where('mobile' , 'like' , "%{$input}%")->select('id');
            		});
            	} , '手机号');
            	
            	$filter->equal('act' , '收支类型')->select([
            			'in' => '收入' ,
            			'out' => '支出'
            	]);
            	$filter->between('cash' , '铜板区间');
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
            $form->display('created_at', '创建时间');
            $form->display('updated_at', '最后一次更新时间');
        });
    }
}
