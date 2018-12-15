<?php
namespace App\Admin\Controllers;

use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;
use App\Models\Comment;
use Encore\Admin\Grid\Displayers\Actions;
use Encore\Admin\Grid\Filter;

class CommentController extends Controller
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
            
            $content->header('评论');
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
            
            $content->header('评论');
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
            
            $content->header('评论');
            $content->description('添加');
            
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
        return Admin::grid( Comment::class, function (Grid $grid) {
            
            $grid->id('ID')->sortable();
            $grid->column('user.nickname' , '昵称')->display( function( $v ){
            	return $v ? $v : '连运' ;
            });
            $grid->column ( 'user.mobile', '手机号码' )->display ( function ($v) {
				return $v ? $v : '连运';
			} );
            $grid->content('评论');
            $grid->actions( function( Actions $action ){
            	$action->disableEdit() ;
            });
            $grid->created_at("发表时间");
            $grid->filter( function( Filter $filter ){
            	$filter->like('content' , '内容');
            });
            $grid->disableCreation();
        });
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        return Admin::form( Comment::class, function (Form $form) {
            
            $form->display('id', 'ID');
            
            $form->display('created_at', 'Created At');
            $form->display('updated_at', 'Updated At');
        });
    }
}
