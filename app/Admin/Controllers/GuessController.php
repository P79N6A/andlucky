<?php
namespace App\Admin\Controllers;

use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;
use App\Models\Guess;
use Encore\Admin\Grid\Filter;
use Encore\Admin\Grid\Displayers\Actions;
use Encore\Admin\Widgets\Table;
use App\Models\GuessJoin;
use Encore\Admin\Widgets\Box;

class GuessController extends Controller
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
            
            $content->header('押大小');
            $content->description('列表');
            
            $content->body($this->grid());
        });
    }
    
    public function show($id)
    {
    	return Admin::content(function (Content $content) use ($id) {
    
    		$content->header('押大小');
    		$content->description('详情');
    		$header = [
    				'参与者' ,
    				'投注' ,
    				'押注' ,
    				'赢/输' ,
    				'获得铜板' ,
    				'状态' 
    		] ;
    		$rows = [] ;
    		$joins = GuessJoin::with('user')->where('guess_id' , $id )->take( 10 )->get( );
    		if( $joins->isNotEmpty() ) {
    			foreach( $joins as $val ) {
    				$rows[] = [
    						$val->user->nickname ,
    						data_get( $val , 'cash' ) ,
    						data_get( config( 'global.guess_seed' ) , $val->seed ) ,
    						data_get( $val , 'is_win' ) == 1 ? '玩家赢' : '庄家赢' ,
    						data_get( $val , 'win_cash' ) ,
    						data_get( config( 'global.guess_join_status') , $val->status ) ,
    				];
    			}
    		}
    		$table = new Table( $header , $rows  ) ;
    		$box = new Box("押大小参与记录");
    		$box->content( $table );
    		$content->row( $box );
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
        return Admin::grid( Guess::class, function (Grid $grid) {

            $grid->model()->orderBy('id' , 'desc' );
            $grid->id('ID')->sortable();
            $grid->column('user.name' , '庄家')->display( function( $v ){
            	$user = data_get( $this , 'user');
            	$u = $user ? data_get( $user , 'nickname' ) . '<br/>' . data_get( $user , 'name') :'' ;
            	return $u ;
            } );
            $grid->cash('奖池')->sortable();
            $grid->seed('种子')->display( function( $v ){
            	return data_get( config('global.guess_seed') , $v , '' );
            } );
            $grid->status('战斗状态')->display( function( $v ){
            	return data_get( config('global.guess_status') , $v , '' );
			} )->label ();
			$grid->has_join('参加人数');
			$grid->win_cash('赢得');
			$grid->lose_cash('输掉');
			$grid->created_at ( '创建时间' );
			$grid->end_time ( '结束时间' )->display( function( $v ){
				return $v ? date('Y-m-d H:i:s' , $v ) : $v ;
			} );
			$grid->disableCreation ();
			$grid->disableRowSelector ();
			$grid->disableCreation();
			$grid->actions( function( Actions $action ){
				$action->disableDelete() ;
				$action->disableEdit();
				$url = route('admin.guess.show' , ['id' => $this->row->id ] );
				$action->append('&nbsp;<a href="'. $url .'" ><i class="fa fa-globe"></i></a>');
			});
			$grid->filter ( function ( Filter $filter) {
				$filter->where ( function ($query) {
					$input = $this->input;
					$query->whereIn ( 'user_id', function ($query) use ($input) {
						return $query->from ( 'users' )->where ( 'mobile', 'like', "%{$input}%" )->select ( 'id' );
					} );
				}, '发起方手机' );
				/**
				$filter->where ( function ($query) {
					$input = $this->input;
					$query->whereIn ( 'user_id', function ($query) use ($input) {
						return $query->from ( 'users' )->where ( 'mobile', 'like', "%{$input}%" )->select ( 'id' );
					} );
				}, '接受方手机');
            	**/ 
            	$filter->equal('status' , '战斗')->select( config('global.guess_status')  );
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
            
            $form->display('created_at', 'Created At');
            $form->display('updated_at', 'Updated At');
        });
    }
    
}
