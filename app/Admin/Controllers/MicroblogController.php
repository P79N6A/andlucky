<?php
namespace App\Admin\Controllers;

use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;
use App\Models\Microblog;
use Encore\Admin\Grid\Filter;
use Encore\Admin\Grid\Displayers\Actions;
use App\Models\MicroblogCate;

class MicroblogController extends Controller
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
            
            $content->header('说说');
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
            
            $content->header('资讯');
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
        return Admin::grid( Microblog::class, function (Grid $grid) {
            $grid->model()->orderBy('id' , 'desc' );
            $grid->id('ID')->sortable();
            $grid->column('user.name' , '发布者');
            $grid->title('标题')->limit( 10 );
            $grid->content('内容')->display( function( $v ) {
            	return $v ? $v : '' ;
            } )->limit( 50 );
            $grid->column('category.title' , '分类')->label();
            $grid->auth_status('审核状态')->display( function( $v ){
            	$v = (int) $v ;
            	return data_get( config('global.microblog_auth_status' ) , $v , '' );
            } )->label();
            $grid->allow_auth('允许送审')->display( function( $v ){
            	$v = (int) $v ;
            	return data_get( config('global.microblog_allow_auth' ) , $v , '' );
            } )->label();
            $grid->views('查看次数');
			$grid->created_at ( '创建时间' );
			$grid->updated_at ( '最后更新' );
			$grid->disableCreation ();
			$grid->disableRowSelector ();
			$grid->filter ( function ( Filter $filter) {
				$filter->where ( function ($query) {
					$input = $this->input;
					$query->whereIn ( 'user_id', function ($query) use ($input) {
						return $query->from ( 'users' )->where ( 'mobile', 'like', "%{$input}%" )->select ( 'id' );
					} );
				}, '用户' );
				$filter->like('content' , '内容');
            });
			$grid->actions( function( Actions $action ){
				//$action->disableEdit();
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
        return Admin::form(Microblog::class, function (Form $form) {
            
            $form->display('id', 'ID');
            $form->text('title' , '标题' );
            $form->select('cate_id' , '分类')->options( function(){
            	return MicroblogCate::where('display' , 1 )->orderBy('sort' , 'asc' )->pluck('title' , 'id');
            }  );
            $form->textarea('content' , '内容' );
            $form->display('extra' , '图片')->with( function( $v ){
            	$return = [] ;
            	if('image' == data_get( $v , 'type' ) ) {
            		$data = data_get( $v , 'data' , [] );
            		if( is_array( $data ) ) {
            			foreach( $data as $v ) {
            				$return[] = "<img src='" . asset( $v ) . "' width='120' />";
            			}
            		}
            	}
            	return implode('<br/>' , $return ) ;
            });
            $form->radio('auth_status' , '审核状态')->options( config('global.microblog_auth_status' ) );
            
            $form->display('allow_status' , '送审状态')->with( function( $v ){
            	$v = (int) $v ;
            	return data_get( config('global.microblog_allow_auth' ) , $v , '' ) ;
            } );
            
            $form->image('adv_cover' , '广告封面') ;
            $form->url('adv_link' , '链接地址' );
            $form->radio('adv_pos' , '广告位置' )->options( config('global.microblog_adv_pos' ) )->default('top') ;
            
        });
    }
    
}
